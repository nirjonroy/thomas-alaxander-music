<?php

namespace App\Http\Controllers\WEB\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\Brand;
use App\Models\AboutUs;
use App\Models\ChildCategory;
use App\Models\FlashSaleProduct;
use App\Models\FooterLink;
use App\Models\Footer;
use App\Models\CustomPage;
use App\Models\Blog;
use App\Models\Event;
use App\Models\eventReview;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use DB;

class HomeController extends Controller
{
    /**
     * Relationships that should always be eager-loaded for homepage products.
     */
    protected array $productRelations = ['category', 'subCategory', 'childCategory'];

    /**
     * Cache lifetime (seconds) for repeated homepage widgets.
     */
    protected int $homeCacheSeconds = 300;

    public function index()
    {
        $now = Carbon::now();

        $sliders = $this->rememberHomeData('home.sliders', function () {
            return Slider::where('status', 1)
                ->orderBy('serial', 'asc')
                ->get();
        });

        $products = $this->fetchProducts('home.products.latest', function (Builder $query) {
            $query->where('type', 'single');
        });

        $top_picks = $this->fetchProducts('home.products.top_picks', function (Builder $query) {
            $query->where('type', 'single')
                ->where('today_special', 1);
        });

        $tranding_songs = $this->fetchProducts('home.products.trending', function (Builder $query) {
            $query->where('type', 'single')
                ->where('tranding_songs', 1);
        });

        $physical_product = $this->fetchProducts('home.products.physical', function (Builder $query) {
            $query->where('type', 'variable');
        });

        $eventsKey = 'home.events.' . $now->format('Y-m');
        $events = $this->rememberHomeData($eventsKey, function () use ($now) {
            return Event::whereMonth('date', $now->month)
                ->whereYear('date', $now->year)
                ->orderBy('date', 'asc')
                ->get();
        });

        $blogs = $this->rememberHomeData('home.blogs', function () {
            return Blog::latest()->limit(12)->get();
        });

        return view('frontend.home.index', compact(
            'products',
            'tranding_songs',
            'top_picks',
            'physical_product',
            'events',
            'sliders',
            'blogs'
        ));
    }

    public function livingArchive()
    {
        $settings = Setting::select([
            'living_archive_title',
            'living_archive_subtitle',
            'living_archive_intro',
            'living_archive_affirmation',
            'living_archive_primary_cta_label',
            'living_archive_primary_cta_url',
            'living_archive_secondary_cta_label',
            'living_archive_secondary_cta_url',
            'living_archive_qr_text',
            'living_archive_logo_image',
            'living_archive_hero_image',
            'living_phase1_title',
            'living_phase1_affirmation',
            'living_phase2_title',
            'living_phase2_affirmation',
            'living_phase3_title',
            'living_phase3_affirmation',
            'living_phase4_title',
            'living_phase4_affirmation',
            'living_ritual_before',
            'living_ritual_during',
            'living_ritual_after',
            'living_crest_title',
            'living_crest_body_one',
            'living_crest_body_two',
            'living_crest_body_three',
            'living_crest_mission',
            'living_crest_secondary_caption',
            'living_crest_primary_image',
            'living_crest_secondary_image',
            'living_lineage_title',
            'living_lineage_intro',
            'living_lineage_tree_text',
            'living_lineage_clan_text',
            'living_lineage_shields_text',
            'living_lineage_feathers_text',
            'living_lineage_endline',
            'living_crests_title',
            'living_crests_intro',
            'living_youth_crest_title',
            'living_youth_crest_declaration',
            'living_youth_crest_body',
            'living_youth_crest_image',
            'living_keeper_crest_title',
            'living_keeper_crest_declaration',
            'living_keeper_crest_body',
            'living_keeper_crest_image',
            'living_witness_crest_title',
            'living_witness_crest_declaration',
            'living_witness_crest_body',
            'living_witness_crest_image',
            'living_qr_crest_image',
            'living_pathway_title',
            'living_pathway_intro',
            'living_pathway_step1_title',
            'living_pathway_step1_body',
            'living_pathway_step2_title',
            'living_pathway_step2_body',
            'living_pathway_step3_title',
            'living_pathway_step3_body',
            'living_media_merch_title',
            'living_media_merch_intro',
            'living_media_merch_card1_title',
            'living_media_merch_card1_body',
            'living_media_merch_card1_cta_label',
            'living_media_merch_card1_cta_url',
            'living_media_merch_card2_title',
            'living_media_merch_card2_body',
            'living_media_merch_card2_cta_label',
            'living_media_merch_card2_cta_url',
            'living_qr_title',
            'living_qr_intro',
            'living_qr_cta_label',
            'living_qr_cta_url',
            'living_contact_title',
            'living_contact_intro',
            'living_contact_training_title',
            'living_contact_training_body',
            'living_contact_training_cta_label',
            'living_contact_training_cta_url',
            'living_contact_events_title',
            'living_contact_events_body',
            'living_contact_events_cta_label',
            'living_contact_events_cta_url',
            'living_contact_general_title',
            'living_contact_general_body',
            'living_contact_general_cta_label',
            'living_contact_general_cta_url',
            'living_contact_support_cta_label',
            'living_contact_support_cta_url',
            'living_certification_title',
            'living_certification_intro',
            'living_certification_text',
            'living_phases_intro',
            'living_contact_phone',
            'living_contact_email',
            'topbar_phone',
            'topbar_email',
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

        $mediaUrl = function (?string $path, string $fallback) {
            if (!$path) {
                return $fallback;
            }
            return Str::startsWith($path, ['http://', 'https://', '//'])
                ? $path
                : asset($path);
        };

        $resolveMedia = function (?string $path, string $fallback) use ($mediaUrl) {
            if (!$path) {
                return $fallback;
            }
            if (Str::startsWith($path, ['http://', 'https://', '//'])) {
                return $path;
            }
            $localPath = public_path($path);
            return file_exists($localPath) ? asset($path) : $fallback;
        };

        $phaseKeys = collect([
            'phase1',
            'phase2',
            'phase3',
            'phase4',
        ]);

        $phaseMeta = $phaseKeys->mapWithKeys(function ($key, $index) use ($settings) {
            $humanIndex = $index + 1;
            $titleField = "living_phase{$humanIndex}_title";
            $affirmField = "living_phase{$humanIndex}_affirmation";

            return [
                $key => [
                    'key'         => $key,
                    'title'       => optional($settings)->{$titleField} ?? "Phase {$humanIndex}",
                    'affirmation' => optional($settings)->{$affirmField},
                    'phase_index' => $humanIndex,
                ],
            ];
        });

        $products = Product::with($this->productRelations)
            ->where('is_living_archive', 1)
            ->orderByRaw("CASE living_archive_phase
                WHEN 'phase1' THEN 1
                WHEN 'phase2' THEN 2
                WHEN 'phase3' THEN 3
                WHEN 'phase4' THEN 4
                ELSE 5 END")
            ->orderBy('living_archive_sort')
            ->get();

        $phases = $phaseMeta->map(function ($meta) use ($products) {
            $items = $products
                ->where('living_archive_phase', $meta['key'])
                ->map(function (Product $model) {
                    $image = $model->thumb_image
                        ? asset('uploads/custom-images2/' . ltrim($model->thumb_image, '/'))
                        : asset('frontend/nothing.png');

                    $price = $model->offer_price > 0 ? $model->offer_price : $model->price;
                    $compare = $model->offer_price > 0 ? $model->price : null;

                    return [
                        'model'        => $model,
                        'slug'         => $model->slug,
                        'name'         => $model->name,
                        'description'  => $model->living_archive_story ?: strip_tags($model->long_description),
                        'affirmation'  => $model->living_archive_affirmation,
                        'qr_caption'   => $model->living_archive_qr_caption ?: 'Scan to enter',
                        'feather'      => $model->living_archive_feather,
                        'image_url'    => $image,
                        'price'        => $price,
                        'compare'      => $compare,
                    ];
                })
                ->values();

            return [
                'key'         => $meta['key'],
                'phase_index' => $meta['phase_index'],
                'title'       => $meta['title'],
                'affirmation' => $meta['affirmation'],
                'products'    => $items,
            ];
        })->values();

        $primaryFallbackCandidates = [
            'frontend/Side_by_side_comparison_crest.png',
            'frontend/Side by side comparison crest  1 .png',
        ];
        $secondaryFallbackCandidates = [
            'frontend/Final Triad crest Eagle, Owl, Buffalo.png',
        ];

        $primaryFallbackPath = collect($primaryFallbackCandidates)
            ->first(fn ($path) => file_exists(public_path($path)));
        $secondaryFallbackPath = collect($secondaryFallbackCandidates)
            ->first(fn ($path) => file_exists(public_path($path)));

        $primaryFallbackImage = $primaryFallbackPath
            ? asset($primaryFallbackPath)
            : asset('frontend/living-archive/Dreamcatcher-style crest.jpeg');
        $secondaryFallbackImage = $secondaryFallbackPath
            ? asset($secondaryFallbackPath)
            : asset('frontend/living-archive/crest represents the Five Civilized Tribes.jpeg');

        $primaryCrestImage = $resolveMedia(
            optional($settings)->living_crest_primary_image,
            $primaryFallbackImage
        );
        $secondaryCrestImage = $resolveMedia(
            optional($settings)->living_crest_secondary_image,
            $secondaryFallbackImage
        );

        $youthDefaultPath = 'frontend/living-archive/crests/youth-crest.jpg';
        $keeperDefaultPath = 'frontend/living-archive/crests/eagle.jpg';
        $witnessDefaultPath = 'frontend/living-archive/crests/elder-crest.jpg';
        $qrDefaultPath = 'frontend/living-archive/crests/qr-crest.jpg';

        $youthDefaultImage = file_exists(public_path($youthDefaultPath))
            ? asset($youthDefaultPath)
            : $secondaryCrestImage;
        $keeperDefaultImage = file_exists(public_path($keeperDefaultPath))
            ? asset($keeperDefaultPath)
            : $secondaryCrestImage;
        $witnessDefaultImage = file_exists(public_path($witnessDefaultPath))
            ? asset($witnessDefaultPath)
            : $secondaryCrestImage;
        $qrDefaultImage = file_exists(public_path($qrDefaultPath))
            ? asset($qrDefaultPath)
            : $secondaryCrestImage;

        $page = [
            'header' => [
                'title'    => optional($settings)->living_archive_title
                    ?? optional($settings)->living_handoff_page_name
                    ?? 'The Yamassee Rising - Living Archive',
                'subtitle' => optional($settings)->living_archive_subtitle ?? 'This is not a store. This is ceremony.',
                'qr_intro' => optional($settings)->living_archive_qr_text ?? 'You have entered the Living Archive. This is ceremony. You are part of the convergence.',
            ],
            'intro' => optional($settings)->living_archive_intro
                ?? optional($settings)->living_handoff_intro
                ?? 'Welcome to The Yamassee Rising - Living Archive, the ceremonial hub where the Living Crest anchors our covenant. This archive is also known as Thomas Alexander\'s Living Crest of the Breath-line, a testimony to ancestral memory and the continuity of our heritage. Here, supporters become carriers of the Breathline, woven into the covenant through crest, music, and ceremony.',
            'hero' => [
                'affirmation' => optional($settings)->living_archive_affirmation
                    ?? 'We Were Never Erased. We Were Replanted.',
                'primary_cta_label' => optional($settings)->living_archive_primary_cta_label
                    ?? 'Explore the Five Feathers Lineage',
                'primary_cta_url' => optional($settings)->living_archive_primary_cta_url
                    ?? '#lineage-story',
                'secondary_cta_label' => optional($settings)->living_archive_secondary_cta_label
                    ?? 'Begin the Carrier Pathway',
                'secondary_cta_url' => optional($settings)->living_archive_secondary_cta_url
                    ?? '#carrier-pathway',
            ],
            'media' => [
                'logo' => $mediaUrl(
                    optional($settings)->living_archive_logo_image
                    ?? optional($settings)->living_handoff_logo_url,
                    asset('frontend/living-archive/images/logo.png')
                ),
                'hero' => $mediaUrl(optional($settings)->living_archive_hero_image, asset('frontend/living-archive/banner3.jpg')),
            ],
            'contact' => [
                'phone' => optional($settings)->living_contact_phone
                    ?? optional($settings)->living_handoff_phone
                    ?? optional($settings)->topbar_phone
                    ?? '(to be added)',
                'email' => optional($settings)->living_contact_email
                    ?? optional($settings)->living_handoff_email
                    ?? optional($settings)->topbar_email
                    ?? 'info@thomasalexanderthevoice.com',
            ],
            'phases_intro' => optional($settings)->living_phases_intro
                ?? 'Move through each phase to reveal artefacts, apparel, and recordings inscribed with their own affirmation.',
            'lineage' => [
                'title' => optional($settings)->living_lineage_title
                    ?? 'About the Lineage',
                'intro' => optional($settings)->living_lineage_intro
                    ?? 'The Living Archive is a ceremonial record -- an ancestral ledger where memory, symbol, and song return to their rightful lineage.',
                'tree' => optional($settings)->living_lineage_tree_text
                    ?? 'Root and canopy unite the Breath-line, keeping the living memory in motion.',
                'clan' => optional($settings)->living_lineage_clan_text
                    ?? 'Guardians of medicine, each one marking protection, vow, and teaching.',
                'shields' => optional($settings)->living_lineage_shields_text
                    ?? 'Three shields hold sovereignty, continuity, and ceremonial protection.',
                'feathers' => optional($settings)->living_lineage_feathers_text
                    ?? 'The five tribes honored; the Ghost Feather holds the ancestor still returning.',
                'endline' => optional($settings)->living_lineage_endline
                    ?? 'We Were Never Erased. We Were Replanted.',
            ],
            'crest' => [
                'title' => optional($settings)->living_crest_title ?? 'Ceremonial Crest',
                'body_one' => optional($settings)->living_crest_body_one
                    ?? 'Dreamcatcher-style crest with twelve animal segments, central tree, and five feathers.',
                'body_two' => optional($settings)->living_crest_body_two
                    ?? 'Five feathers honour the Five Civilized Tribes, with one feather reserved for the unknown yet to be recognized.',
                'body_three' => optional($settings)->living_crest_body_three
                    ?? 'Full breathline: Mali, Black Indigenous, Alexander Black Indigenous/Gaelic, and Williams.',
                'mission' => optional($settings)->living_crest_mission
                    ?? 'Texas Yamassee: Thomas Alexander (The Voice) - Every note is a thread in the ancestral tapestry. Mission: Yamassee Rising exists to inscribe survival, memory, and chosen legacy through ceremonial music, crest symbolism, and ritual garments. This archive honours the breath.',
                'secondary_caption' => optional($settings)->living_crest_secondary_caption
                    ?? 'Secondary crest: Five Feather Lineage',
                'primary_image' => $primaryCrestImage,
                'secondary_image' => $secondaryCrestImage,
            ],
            'crests' => [
                'title' => optional($settings)->living_crests_title
                    ?? 'The Three Crests',
                'intro' => optional($settings)->living_crests_intro
                    ?? 'These are sacred displays -- static and enduring, held as testimony for the youth, the keepers, and the elders of the lineage.',
                'youth' => [
                    'title' => optional($settings)->living_youth_crest_title
                        ?? 'Youth Crest - The Listener',
                    'declaration' => optional($settings)->living_youth_crest_declaration
                        ?? 'We perch where the roof gave way.',
                    'body' => optional($settings)->living_youth_crest_body
                        ?? "The Listener enters by listening first -- observing, gathering, and holding the earliest teachings.\nThey are welcomed into the lineage as the first witnesses, carrying the hush of beginnings.",
                    'image' => $resolveMedia(optional($settings)->living_youth_crest_image, $youthDefaultImage),
                ],
                'keeper' => [
                    'title' => optional($settings)->living_keeper_crest_title
                        ?? 'Keeper Crest - The Bearer',
                    'declaration' => optional($settings)->living_keeper_crest_declaration
                        ?? 'As the eagle, I did not blink, for I saw and see it all.',
                    'body' => optional($settings)->living_keeper_crest_body
                        ?? "The Bearer holds responsibility for the crest, the teachings, and the living record.\nThey rise into sight through service, courage, and the clear gaze of stewardship.",
                    'image' => $resolveMedia(optional($settings)->living_keeper_crest_image, $keeperDefaultImage),
                ],
                'witness' => [
                    'title' => optional($settings)->living_witness_crest_title
                        ?? 'Witness Crest - The Elder',
                    'declaration' => optional($settings)->living_witness_crest_declaration
                        ?? 'We kept the fire when the world went dark.',
                    'body' => optional($settings)->living_witness_crest_body
                        ?? "The Elder carries memory as ceremony, protecting the line when silence falls.\nThey are continuity itself -- the living archive made flesh and breath.",
                    'image' => $resolveMedia(optional($settings)->living_witness_crest_image, $witnessDefaultImage),
                ],
            ],
            'pathway' => [
                'title' => optional($settings)->living_pathway_title
                    ?? 'Carrier Pathway',
                'intro' => optional($settings)->living_pathway_intro
                    ?? 'The lineage moves with intention -- Youth to Keeper to Witness -- each step recognized through ceremony, accountability, and protection.',
                'steps' => [
                    [
                        'icon' => 'fa-owl',
                        'title' => optional($settings)->living_pathway_step1_title
                            ?? 'Youth -> Keeper',
                        'body' => optional($settings)->living_pathway_step1_body
                            ?? "Requirements: attentive listening, ceremonial training, and commitment to the Breath-line.\nRecognition: named by elders through witness and documented in the archive.",
                    ],
                    [
                        'icon' => 'fa-feather',
                        'title' => optional($settings)->living_pathway_step2_title
                            ?? 'Keeper -> Witness',
                        'body' => optional($settings)->living_pathway_step2_body
                            ?? "Requirements: stewardship of rituals, protection of crest teachings, and community responsibility.\nRecognition: rises into sight through service, guarded by the shields.",
                    ],
                    [
                        'icon' => 'fa-shield-alt',
                        'title' => optional($settings)->living_pathway_step3_title
                            ?? 'Protection of Lineage',
                        'body' => optional($settings)->living_pathway_step3_body
                            ?? "The lineage is protected by ceremony, council, and the living record held within the crest.\nEach carrier is acknowledged and affirmed in the archive.",
                    ],
                ],
            ],
            'media_merch' => [
                'title' => optional($settings)->living_media_merch_title
                    ?? 'Media & Merch as Ceremonial Artifacts',
                'intro' => optional($settings)->living_media_merch_intro
                    ?? 'Music scores, apparel, and recordings are extensions of the Breath-line -- artifacts that carry ceremony into the everyday.',
                'merch' => [
                    'title' => optional($settings)->living_media_merch_card1_title
                        ?? 'Merch Crest',
                    'body' => optional($settings)->living_media_merch_card1_body
                        ?? 'Apparel, scores, and ceremonial items are lineage extensions -- worn and shared to keep the crest visible.',
                    'cta_label' => optional($settings)->living_media_merch_card1_cta_label
                        ?? 'Enter the Artifact Hall',
                    'cta_url' => optional($settings)->living_media_merch_card1_cta_url
                        ?? route('front.shop'),
                    'image' => $secondaryCrestImage,
                ],
                'qr' => [
                    'title' => optional($settings)->living_media_merch_card2_title
                        ?? 'QR Crest',
                    'body' => optional($settings)->living_media_merch_card2_body
                        ?? 'The QR Crest is a digital gateway -- a quiet entry into the archive\'s living record.',
                    'cta_label' => optional($settings)->living_media_merch_card2_cta_label
                        ?? 'Open the QR Gateway',
                    'cta_url' => optional($settings)->living_media_merch_card2_cta_url
                        ?? '#qr-access',
                    'image' => $resolveMedia(optional($settings)->living_qr_crest_image, $qrDefaultImage),
                ],
            ],
            'qr' => [
                'title' => optional($settings)->living_qr_title
                    ?? 'QR Access',
                'intro' => optional($settings)->living_qr_intro
                    ?? 'The QR Crest offers a direct ceremonial passage -- a digital doorway into the lineage archive.',
                'cta_label' => optional($settings)->living_qr_cta_label
                    ?? 'Open the QR Gateway',
                'cta_url' => optional($settings)->living_qr_cta_url
                    ?? route('living-archive.donate'),
                'image' => $resolveMedia(optional($settings)->living_qr_crest_image, $qrDefaultImage),
            ],
            'contact_section' => [
                'title' => optional($settings)->living_contact_title
                    ?? 'Contact & Invitations',
                'intro' => optional($settings)->living_contact_intro
                    ?? 'Enter the circle through training, ceremony, and direct invitation.',
                'training' => [
                    'title' => optional($settings)->living_contact_training_title
                        ?? 'Training Invitation',
                    'body' => optional($settings)->living_contact_training_body
                        ?? 'Receive training in the Five Feathers lineage and learn the responsibilities of ceremonial care.',
                    'cta_label' => optional($settings)->living_contact_training_cta_label
                        ?? 'Request Training',
                    'cta_url' => optional($settings)->living_contact_training_cta_url
                        ?? 'mailto:' . (optional($settings)->living_contact_email ?? 'info@thomasalexanderthevoice.com'),
                ],
                'events' => [
                    'title' => optional($settings)->living_contact_events_title
                        ?? 'Ceremonial Events',
                    'body' => optional($settings)->living_contact_events_body
                        ?? 'Join ceremonial gatherings that affirm the Breath-line and honor the crest as living memory.',
                    'cta_label' => optional($settings)->living_contact_events_cta_label
                        ?? 'See Ceremonial Calendar',
                    'cta_url' => optional($settings)->living_contact_events_cta_url
                        ?? route('living-archive.donate'),
                ],
                'general' => [
                    'title' => optional($settings)->living_contact_general_title
                        ?? 'Contact',
                    'body' => optional($settings)->living_contact_general_body
                        ?? '',
                    'cta_label' => optional($settings)->living_contact_general_cta_label
                        ?? 'Email the Archive',
                    'cta_url' => optional($settings)->living_contact_general_cta_url
                        ?? 'mailto:' . (optional($settings)->living_contact_email ?? 'info@thomasalexanderthevoice.com'),
                    'support_label' => optional($settings)->living_contact_support_cta_label
                        ?? 'Offer Support',
                    'support_url' => optional($settings)->living_contact_support_cta_url
                        ?? route('living-archive.donate'),
                ],
            ],
            'certification' => [
                'title' => optional($settings)->living_certification_title
                    ?? 'Printable Certification',
                'intro' => optional($settings)->living_certification_intro
                    ?? 'Static ceremonial document for carriers within the Five Feathers lineage.',
                'text' => optional($settings)->living_certification_text
                    ?? "THE FIVE FEATHERS LINEAGE\nCARRIER CERTIFICATION DOCUMENT\nCARRIER NAME: ____________________________\nCREST ROLE: _______________________________\nFEATHER DESIGNATION: ______________________\nDATE RECEIVED: ____________________________\nWITNESS SIGNATURE: ________________________\nSEAL OF THE LIVING ARCHIVE: _______________",
            ],
            'ritual_flow' => [
                'before' => $this->formatRitualList(
                    optional($settings)->living_ritual_before,
                    [
                        'Speak Phase 1 affirmation aloud: "We begin with breath. We begin to live."',
                        'Center the crest watermark and dim the space to ceremony lighting.',
                    ]
                ),
                'during' => $this->formatRitualList(
                    optional($settings)->living_ritual_during,
                    [
                        'Assign tasks by feather tokens so each keeper knows their product and duty.',
                        'Move through tabs with ritual pauses (breath, silence, or drumbeat) between sections.',
                        'Confirm clarity and unity before publishing each section.',
                    ]
                ),
                'after' => $this->formatRitualList(
                    optional($settings)->living_ritual_after,
                    [
                        'Keeper\'s check-in: Is the page emotionally resonant?',
                        'Keeper\'s check-in: Is the archive clearly inscribed?',
                        'Keeper\'s check-in: Is overwhelm transformed into ceremony?',
                    ]
                ),
            ],
        ];

        $handoff = [
            'page_name' => optional($settings)->living_handoff_page_name
                ?? 'The Yamassee Rising - Living Archive',
            'logo_url' => $mediaUrl(
                optional($settings)->living_handoff_logo_url,
                $page['media']['logo']
            ),
            'email' => optional($settings)->living_handoff_email
                ?? 'info@thomasalexanderthevoice.com',
            'phone' => optional($settings)->living_handoff_phone
                ?? '(to be added)',
            'social' => [
                'instagram' => optional($settings)->living_handoff_social_instagram,
                'facebook' => optional($settings)->living_handoff_social_facebook,
                'youtube' => optional($settings)->living_handoff_social_youtube,
            ],
            'address' => optional($settings)->living_handoff_address
                ?? 'Pending (use P.O. Box or publishing company address once registered)',
            'intro' => optional($settings)->living_handoff_intro
                ?? 'Welcome to The Yamassee Rising - Living Archive, the ceremonial hub where the Living Crest anchors our covenant. This archive is also known as Thomas Alexander\'s Living Crest of the Breath-line, a testimony to ancestral memory and the continuity of our heritage. Here, supporters become carriers of the Breathline, woven into the covenant through crest, music, and ceremony.',
            'mission' => optional($settings)->living_handoff_mission
                ?? 'The Yamassee Rising - Living Archive exists to restore erased Black Indigenous narratives through ceremony, music, and ancestral testimony. Guided by the Living Crest and Breath-line, this archive is a covenant of memory, where heritage is not preserved as a static history but carried forward as a living presence. Every glyph, feather, and song is a thread in the tapestry of continuity, weaving youth and elders into the same ceremonial lineage.',
            'supporter' => optional($settings)->living_handoff_supporter
                ?? 'This archive is sustained by the covenant of supporters, each named and honored as carriers of the Breath-line. Your presence is not symbolic alone -- it is ceremonial. By standing within the Living Crest, you become part of the lineage, ensuring that Yamassee Rising continues as a living testimony for generations to come.',
            'coming_soon' => optional($settings)->living_handoff_coming_soon
                ?? 'The Yamassee Rising Suite - audio recording in progress. Soon, the Breath-line will be heard in a full ceremony.',
            'taglines' => array_values(array_filter([
                optional($settings)->living_handoff_tagline1
                    ?? 'Carrying the Breath-line, Restoring the Living Memory.',
                optional($settings)->living_handoff_tagline2
                    ?? 'Where the Crest breathes, the lineage lives.',
                optional($settings)->living_handoff_tagline3
                    ?? 'The Breath-line carried forward, the memory restored.',
                optional($settings)->living_handoff_tagline4
                    ?? 'Every supporter a carrier of the Living Crest.',
            ])),
            'merch' => [
                'apparel' => optional($settings)->living_handoff_merch_apparel
                    ?? 'Where the Crest breathes, the lineage lives.',
                'posters' => optional($settings)->living_handoff_merch_posters
                    ?? 'Carrying the Breath-line, Restoring the Living Memory.',
                'music' => optional($settings)->living_handoff_merch_music
                    ?? 'The Breath-line carried forward, the memory restored.',
                'donor' => optional($settings)->living_handoff_merch_donor
                    ?? 'Every supporter a carrier of the Living Crest.',
                'digital' => optional($settings)->living_handoff_merch_digital
                    ?? 'Carrying the Breath-line, Restoring the Living Memory.',
            ],
            'visual_hierarchy' => optional($settings)->living_handoff_visual_hierarchy
                ?? 'Primary: Living Crest (largest, central). Secondary: Tagline (beneath crest). Supporting: Glyphs, feathers, Yamassee wheel (framing accents). Informational: Mission statement, supporter text, contact info (smallest).',
            'palette' => [
                'primary' => optional($settings)->living_handoff_palette_primary
                    ?? 'Earth Ochre, Ceremonial White, Charcoal Black',
                'secondary' => optional($settings)->living_handoff_palette_secondary
                    ?? 'Muted Gold, Forest Green, Crimson Red',
                'accent' => optional($settings)->living_handoff_palette_accent
                    ?? 'Sky Silver, Deep Yamassee Blue',
            ],
            'background_guide' => optional($settings)->living_handoff_background_guide
                ?? 'Posters: parchment beige with texture. Apparel: charcoal black, deep blue, or warm brown. Website: ceremonial white or parchment beige; optional deep blue with crest border. Music scores: charcoal black or deep blue, matte/parchment overlay.',
            'footer' => optional($settings)->living_handoff_footer_line
                ?? 'The Yamassee Rising - A Living Archive of Ceremony and Song.',
        ];

        return view('frontend.home.living-archive', compact('page', 'phases', 'handoff'));
    }

    public function about(){
        $about = DB::table('about_us')->first();
        // dd($about);
        return view('frontend.home.about', compact('about'));
    }

    public function subCategoriesByCategory(Request $request)
    {
        if($request->type == 'subcategory')
        {
            $id = Category::whereSlug($request->slug)->first()->id;
            $categories = SubCategory::where(['category_id' => $id])->get();
            if($categories->count() <= 0)
            {
                return redirect()->route('front.shop', ['slug'=> $request->slug ] );
            }

            return view('frontend.category.sub-category', compact('categories'));
        }
        else if($request->type == 'childcategory')
        {
            $id = SubCategory::whereSlug($request->slug)->first()->id;
            $categories = ChildCategory::where(['sub_category_id' => $id])->get();
            if($categories->count() <= 0)
            {
                return redirect()->route('front.shop', ['slug'=> $request->slug ] );
            }

            return view('frontend.category.child-category', compact('categories'));
        }

    }

public function shop(Request $request, $slug = null)
{
    $data = null;

    if (!empty($slug)) {
        $data = Category::with('products')->whereSlug($slug)->first();

        if (!$data) {
            $data = SubCategory::with('products')->whereSlug($slug)->first();
        }

        if (!$data) {
            $data = ChildCategory::with('products')->whereSlug($slug)->first();
        }
    }

    if ($data instanceof Category || $data instanceof SubCategory || $data instanceof ChildCategory) {
        $products = $data->products;
    } else {
        $products = Product::with(['category', 'subCategory', 'childCategory'])->take(30)->get();
    }

    // Apply price range filter
    $minPrice = $products->min('price');
    $maxPrice = $products->max('price');

    $minPriceFilter = $request->input('min_price', $minPrice);
    $maxPriceFilter = $request->input('max_price', $maxPrice);

    $filteredProducts = $products->whereBetween('price', [$minPriceFilter, $maxPriceFilter]);

    // Apply availability filter
    $inStock = $request->input('in_stock');
    $outOfStock = $request->input('out_of_stock');

    if ($request->input('in_stock')) {
        $filteredProducts = $filteredProducts->where('qty', '>', 0);
    }

    if ($request->input('out_of_stock')) {
        $filteredProducts = $filteredProducts->where('sold_qty', '==', 'qty');
    }
    // dd($data);
    return view('frontend.shop.index', compact('filteredProducts', 'minPrice', 'maxPrice', 'data'));
}









    public function mostSellingProducts()
    {
        $products = Product::with(['category', 'subCategory', 'childCategory'])
                            ->leftJoin('order_products as op','products.id','=','op.product_id')
                            ->selectRaw('products.*, COALESCE(sum(op.qty),0) total')
                            ->groupBy('products.id')
                            ->orderBy('total','desc')
                            ->take(50)
                            ->get();

        return view('frontend.shop.most-selling', compact('products'));
    }

     public function flashSellProducts(Request $request)
    {
        $data = null;


    $products = Product::with(['category', 'subCategory', 'childCategory'])->take(30)->get();
       //dd($flashProd);
    // Apply price range filter

   $minPrice = $products->min('price');
    $maxPrice = $products->max('price');

    $minPriceFilter = $request->input('min_price', $minPrice);
    $maxPriceFilter = $request->input('max_price', $maxPrice);

    $filteredProducts = $products->whereBetween('price', [$minPriceFilter, $maxPriceFilter]);

    // Apply availability filter
    $inStock = $request->input('in_stock');
    $outOfStock = $request->input('out_of_stock');

    if ($request->input('in_stock')) {
        $filteredProducts = $filteredProducts->where('qty', '>', 0);
    }

    if ($request->input('out_of_stock')) {
        $filteredProducts = $filteredProducts->where('sold_qty', '==', 'qty');
    }

        $flashSell = FlashSaleProduct::with('product')->where('status', 1)->latest()->get();

        return view('frontend.shop.flash-sell', compact('flashSell', 'filteredProducts', 'minPrice', 'maxPrice'));
    }
    public function customPages($slug){
        $customPage=CustomPage::where('slug', $slug)->first();

        // dd($customPage);
        return view('frontend.pages', compact('customPage'));
    }

    public function all_category(){

        $categories = Category::where('status', 1)->with('products')->get();
        return view('frontend.category.category', compact('categories'));
    }

    public function contact_us(){
    	// $contact = contactPage::first();
      	return view('frontend.pages.contact');
      	//dd($contact);
    }

  	public function blog(){
    	$blogs = Blog::latest()->get();
      	// dd($blog);
      	return view('frontend.pages.blog', compact('blogs'));
      	//dd($contact);
    }
    
    public function event(){
    	$events = Event::latest()->orderBy('date', 'desc')->get();
      	// dd($blog);
      	return view('frontend.pages.events', compact('events'));
      	//dd($contact);
    }
    
    public function event_review(Request $r, Event $event) // resolved by ID
    {
        if (!optional($event->starts_at)->isPast()) {
            return back()->withErrors(['review' => 'Reviews open after the event ends.']);
        }

        $data = $r->validate([
            'name'    => ['nullable','string','max:80'],
            'email'   => ['nullable','email','max:120'],
            'rating'  => ['nullable','integer','min:1','max:5'],
            'comment' => ['required','string','min:5','max:2000'],
        ]);

        $data['user_id'] = auth()->id();
        // tweak policy: approve logged-in; guests pending
        $data['status']  = auth()->check() ? 'approved' : 'pending';

        $event->reviews()->create($data);

        return back()->with('ok',
            $data['status']==='approved'
                ? 'Thanks for your review!'
                : 'Thanks! Your review is awaiting approval.'
        );
    }

    public function event_show(Event $event)
    {
        $reviews  = $event->reviews()->latest()->paginate(10);
    
        $startsAt = \Carbon\Carbon::parse(trim(($event->date ?? '').' '.($event->time ?? '')));
        $hasEnded = $startsAt ? $startsAt->isPast() : false;
    
        return view('frontend.pages.show_event', compact('event','reviews','hasEnded','startsAt'));
    }

    
  	public function blog_details($slug){
    	$blog = Blog::where('slug', $slug)->first();
      	//dd($blog);
      	return view('frontend.pages.blog_details', compact('blog'));
    }
    
    

public function getImageUrlAttribute()
{
    $img = $this->image;

    if (!$img) return asset(siteInfo()->logo);

    if (Str::startsWith($img, ['http://','https://','//'])) {
        return $img;
    }

    $candidates = [
        $img,
        'uploads/events/'.$img,
        'uploads/custom-images/'.$img,
        'uploads/custom-images2/'.$img,
        'storage/'.$img,
    ];
    foreach ($candidates as $rel) {
        if (file_exists(public_path($rel))) return asset($rel);
    }
    return asset(siteInfo()->logo);
}



    protected function fetchProducts(string $cacheKey, ?callable $constraints = null, int $limit = 24)
    {
        return $this->rememberHomeData($cacheKey, function () use ($constraints, $limit) {
            $query = $this->baseProductQuery();

            if ($constraints) {
                $constraints($query);
            }

            return $query->take($limit)->get();
        });
    }

    protected function baseProductQuery(): Builder
    {
        return Product::with($this->productRelations)->latest();
    }

    protected function rememberHomeData(string $key, callable $callback)
    {
        return Cache::remember($key, now()->addSeconds($this->homeCacheSeconds), $callback);
    }

    protected function formatRitualList(?string $raw, array $fallback = []): array
    {
        $items = collect(preg_split('/\r\n|\r|\n/', (string) $raw))
            ->map(fn ($line) => trim($line))
            ->filter(fn ($line) => $line !== '')
            ->values();

        return $items->isNotEmpty() ? $items->all() : $fallback;
    }

}
