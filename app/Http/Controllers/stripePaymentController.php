<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Stripe;
use App\Models\Order;
use App\Models\Setting;
use App\Models\PaypalPayment;
use App\Models\StripePayment;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Payer;
use PayPal\Api\Amount as PaypalAmount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
class StripePaymentController extends Controller
{
    private function buildPaypalContext(PaypalPayment $paypal)
    {
        $apiContext = new ApiContext(new OAuthTokenCredential(
            $paypal->client_id,
            $paypal->secret_id
        ));

        $apiContext->setConfig([
            'mode' => $paypal->account_mode,
            'http.ConnectionTimeOut' => 30,
            'log.LogEnabled' => true,
            'log.FileName' => storage_path() . '/logs/paypal.log',
            'log.LogLevel' => 'ERROR',
        ]);

        return $apiContext;
    }
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripe($id)
    {
        $order_inv = Order::with('orderProducts', 'orderAddress')->where('id', $id)->first();
        $stripe = StripePayment::select('stripe_key', 'status')->first();
        $stripePublishableKey = $stripe ? $stripe->stripe_key : env('STRIPE_KEY');
        $stripeEnabled = $stripe && (int) $stripe->status === 1;
        // dd($order_inv);
        return view('stripe', compact('order_inv', 'stripePublishableKey', 'stripeEnabled'));
    }

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripePost(Request $request)
{
    $stripe = StripePayment::first();
    if (!$stripe || (int) $stripe->status !== 1) {
        return back()->with('error', 'Stripe is not available right now.');
    }

    $stripeSecret = $stripe->stripe_secret ?: env('STRIPE_SECRET');
    if (!$stripeSecret) {
        return back()->with('error', 'Stripe is not configured.');
    }

    Stripe\Stripe::setApiKey($stripeSecret);
    $currency = strtolower($stripe->currency_code ?: 'USD');
    $totalAmount = $request->total_amount;
    $orderId = $request->input('order_id');

    try {
        $charge = Stripe\Charge::create ([
            "amount" => $totalAmount * 100,
            "currency" => $currency,
            "source" => $request->stripeToken,
            "description" => "Test payment from ."
        ]);

        if (!empty($orderId)) {
            // Update order status to 5 (payment successful)
            $order = Order::findOrFail($orderId);
            $order->order_status = 5;
            $order->save();

            Session::flash('success', 'Payment successful!');

            return redirect()->route('front.order-thanks-page', ['order_id' => $orderId]);
        }

        Session::flash('success', 'Thank you for supporting the Living Archive.');
        return redirect()->route('front.home.living-archive');
    } catch (Stripe\Exception\CardException $e) {
        // Catch any errors thrown by Stripe (e.g., card errors)
        $error = $e->getError();
        $errorMessage = $error->message;

        // Display the error message to the user (you can modify this as needed)
        return back()->with('error', $errorMessage);
    } catch (Exception $e) {
        // Catch any other unexpected errors
        return back()->with('error', 'An unexpected error occurred. Please try again later.');
    }
}

    public function donationForm()
    {
        $setting = Setting::select([
            'living_handoff_page_name',
            'living_handoff_logo_url',
            'living_handoff_email',
            'living_handoff_phone',
            'living_handoff_social_instagram',
            'living_handoff_social_facebook',
            'living_handoff_social_youtube',
            'living_handoff_address',
            'living_handoff_intro',
            'living_handoff_mission',
            'living_handoff_supporter',
            'living_handoff_coming_soon',
            'living_handoff_tagline1',
            'living_handoff_tagline2',
            'living_handoff_tagline3',
            'living_handoff_tagline4',
            'living_handoff_merch_apparel',
            'living_handoff_merch_posters',
            'living_handoff_merch_music',
            'living_handoff_merch_donor',
            'living_handoff_merch_digital',
            'living_handoff_visual_hierarchy',
            'living_handoff_palette_primary',
            'living_handoff_palette_secondary',
            'living_handoff_palette_accent',
            'living_handoff_background_guide',
            'living_handoff_footer_line',
        ])->first();
        $paypal = PaypalPayment::select('status')->first();
        $paypalEnabled = $paypal && (int) $paypal->status === 1;
        $stripe = StripePayment::select('stripe_key', 'status')->first();
        $stripePublishableKey = $stripe ? $stripe->stripe_key : env('STRIPE_KEY');
        $stripeEnabled = $stripe && (int) $stripe->status === 1;

        $handoff = [
            'page_name' => optional($setting)->living_handoff_page_name ?? 'The Yamassee Rising - Living Archive',
            'logo_url' => optional($setting)->living_handoff_logo_url,
            'email' => optional($setting)->living_handoff_email ?? 'info@thomasalexanderthevoice.com',
            'phone' => optional($setting)->living_handoff_phone ?? '(to be added)',
            'social' => [
                'instagram' => optional($setting)->living_handoff_social_instagram,
                'facebook' => optional($setting)->living_handoff_social_facebook,
                'youtube' => optional($setting)->living_handoff_social_youtube,
            ],
            'address' => optional($setting)->living_handoff_address ?? 'Pending (use P.O. Box or publishing company address once registered)',
            'intro' => optional($setting)->living_handoff_intro
                ?? 'Welcome to The Yamassee Rising - Living Archive, the ceremonial hub where the Living Crest anchors our covenant. This archive is also known as Thomas Alexander\'s Living Crest of the Breath-line, a testimony to ancestral memory and the continuity of our heritage. Here, supporters become carriers of the Breathline, woven into the covenant through crest, music, and ceremony.',
            'mission' => optional($setting)->living_handoff_mission
                ?? 'The Yamassee Rising - Living Archive exists to restore erased Black Indigenous narratives through ceremony, music, and ancestral testimony. Guided by the Living Crest and Breath-line, this archive is a covenant of memory, where heritage is not preserved as a static history but carried forward as a living presence. Every glyph, feather, and song is a thread in the tapestry of continuity, weaving youth and elders into the same ceremonial lineage.',
            'supporter' => optional($setting)->living_handoff_supporter
                ?? 'This archive is sustained by the covenant of supporters, each named and honored as carriers of the Breath-line. Your presence is not symbolic alone — it is ceremonial. By standing within the Living Crest, you become part of the lineage, ensuring that Yamassee Rising continues as a living testimony for generations to come.',
            'coming_soon' => optional($setting)->living_handoff_coming_soon
                ?? 'The Yamassee Rising Suite — audio recording in progress. Soon, the Breath-line will be heard in a full ceremony.',
            'taglines' => array_filter([
                optional($setting)->living_handoff_tagline1 ?? 'Carrying the Breath-line, Restoring the Living Memory.',
                optional($setting)->living_handoff_tagline2 ?? 'Where the Crest breathes, the lineage lives.',
                optional($setting)->living_handoff_tagline3 ?? 'The Breath-line carried forward, the memory restored.',
                optional($setting)->living_handoff_tagline4 ?? 'Every supporter a carrier of the Living Crest.',
            ]),
            'merch' => [
                'apparel' => optional($setting)->living_handoff_merch_apparel ?? 'Where the Crest breathes, the lineage lives.',
                'posters' => optional($setting)->living_handoff_merch_posters ?? 'Carrying the Breath-line, Restoring the Living Memory.',
                'music' => optional($setting)->living_handoff_merch_music ?? 'The Breath-line carried forward, the memory restored.',
                'donor' => optional($setting)->living_handoff_merch_donor ?? 'Every supporter a carrier of the Living Crest.',
                'digital' => optional($setting)->living_handoff_merch_digital ?? 'Carrying the Breath-line, Restoring the Living Memory.',
            ],
            'visual_hierarchy' => optional($setting)->living_handoff_visual_hierarchy
                ?? 'Primary: Living Crest. Secondary: Tagline. Supporting: Glyphs, feathers, Yamassee wheel. Informational: Mission, supporter text, contact.',
            'palette_primary' => optional($setting)->living_handoff_palette_primary
                ?? 'Earth Ochre, Ceremonial White, Charcoal Black',
            'palette_secondary' => optional($setting)->living_handoff_palette_secondary
                ?? 'Muted Gold, Forest Green, Crimson Red',
            'palette_accent' => optional($setting)->living_handoff_palette_accent
                ?? 'Sky Silver, Deep Yamassee Blue',
            'background_guide' => optional($setting)->living_handoff_background_guide
                ?? 'Posters: parchment beige with texture. Apparel: charcoal black, deep blue, or warm brown. Website: ceremonial white or parchment beige; optional deep blue with crest border. Music scores: charcoal black or deep blue, matte/parchment overlay.',
            'footer' => optional($setting)->living_handoff_footer_line
                ?? 'The Yamassee Rising - A Living Archive of Ceremony and Song.',
        ];

        return view('frontend.home.living-archive-donate', compact('handoff', 'paypalEnabled', 'stripePublishableKey', 'stripeEnabled'));
    }

    public function donateWithPaypal(Request $request)
    {
        $request->validate([
            'total_amount' => 'required|numeric|min:5',
        ]);

        $paypal = PaypalPayment::first();
        if (!$paypal || (int) $paypal->status !== 1) {
            return back()->with('error', 'PayPal is not available right now.');
        }

        $amount = (float) $request->total_amount;
        $payableAmount = round($amount * $paypal->currency_rate, 2);
        if ($payableAmount <= 0) {
            return back()->with('error', 'Invalid donation amount.');
        }

        $apiContext = $this->buildPaypalContext($paypal);

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $paymentAmount = new PaypalAmount();
        $paymentAmount->setCurrency($paypal->currency_code ?: 'USD')
            ->setTotal($payableAmount);

        $transaction = new Transaction();
        $transaction->setAmount($paymentAmount)
            ->setDescription('Living Archive Donation');

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(route('living-archive.paypal.success'))
            ->setCancelUrl(route('living-archive.paypal.cancel'));

        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions([$transaction]);

        try {
            $payment->create($apiContext);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            return back()->with('error', 'PayPal payment failed. Please try again.');
        } catch (\Exception $ex) {
            return back()->with('error', 'PayPal payment failed. Please try again.');
        }

        return redirect($payment->getApprovalLink());
    }

    public function donatePaypalSuccess(Request $request)
    {
        $paypal = PaypalPayment::first();
        if (!$paypal || (int) $paypal->status !== 1) {
            return redirect()->route('living-archive.donate')
                ->with('error', 'PayPal is not available right now.');
        }

        if (empty($request->get('PayerID')) || empty($request->get('token'))) {
            return redirect()->route('living-archive.donate')
                ->with('error', 'Payment failed. Please try again.');
        }

        $paymentId = $request->get('paymentId');
        $apiContext = $this->buildPaypalContext($paypal);

        try {
            $payment = Payment::get($paymentId, $apiContext);
            $execution = new PaymentExecution();
            $execution->setPayerId($request->get('PayerID'));
            $result = $payment->execute($execution, $apiContext);
        } catch (\Exception $ex) {
            return redirect()->route('living-archive.donate')
                ->with('error', 'Payment failed. Please try again.');
        }

        if ($result->getState() === 'approved') {
            Session::flash('success', 'Thank you for supporting the Living Archive.');
        } else {
            Session::flash('error', 'Payment was not approved.');
        }

        return redirect()->route('living-archive.donate');
    }

    public function donatePaypalCancel()
    {
        return redirect()->route('living-archive.donate')
            ->with('error', 'Payment was canceled.');
    }
}
