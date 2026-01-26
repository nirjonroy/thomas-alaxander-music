<?php
namespace App\Http\Controllers\WEB\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaintainanceText;
use App\Models\AnnouncementModal;
use App\Models\Setting;
use App\Models\BannerImage;
use App\Models\ShopPage;
use App\Models\SeoSetting;
use Image;
use File;
use Illuminate\Support\Str;

class ContentController extends Controller
{
    

    public function maintainanceMode()
    {
        $maintainance = MaintainanceText::first();
        return view("admin.maintainance_mode", compact("maintainance"));
    }

    public function maintainanceModeUpdate(Request $request)
    {
        $rules = [
            "description" => "required",
        ];

        $customMessages = [
            "description.required" => trans(
                "admin_validation.Description is required"
            ),
            "status.required" => trans("admin_validation.Status is required"),
        ];

        $this->validate($request, $rules, $customMessages);
        $maintainance = MaintainanceText::first();

        if ($request->image) {
            $old_image = $maintainance->image;
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $image_name =
                "maintainance-mode-" .
                date("Y-m-d-h-i-s-") .
                rand(999, 9999) .
                "." .
                $ext;

            $image_name = "uploads/website-images/" . $image_name;
            Image::make($image)
            ->save(public_path() . "/" . $image_name);
            $maintainance->image = $image_name;
            $maintainance->save();

            if ($old_image) {
                if (File::exists(public_path() . "/" . $old_image)) {
                    unlink(public_path() . "/" . $old_image);
                }
            }
        }

        $maintainance->status = $request->maintainance_mode ? 1 : 0;
        $maintainance->description = $request->description;
        $maintainance->save();
        $notification = trans("admin_validation.Updated Successfully");
        $notification = ["messege" => $notification, "alert-type" => "success"];

        return redirect()->back()->with($notification);
    }

    public function announcementModal()
    {
        $announcement = AnnouncementModal::first();

        return view("admin.announcement", compact("announcement"));
    }

    public function announcementModalUpdate(Request $request)
    {
        $rules = [
            "description" => "required",
            "title" => "required",
            "expired_date" => "required",
        ];

        $customMessages = [
            "description.required" => trans(
                "admin_validation.Description is required"
            ),
            "title.required" => trans("admin_validation.Title is required"),
            "status.required" => trans("admin_validation.Status is required"),
            "expired_date.required" => trans(
                "admin_validation.Expired date is required"
            ),
        ];

        $this->validate($request, $rules, $customMessages);

        $announcement = AnnouncementModal::first();
        if ($request->image) {
            $old_image = $announcement->image;
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();

            $image_name =
                "announcement-" .
                date("Y-m-d-h-i-s-") .
                rand(999, 9999) .
                "." .
                $ext;

            $image_name = "uploads/website-images/" . $image_name;
            Image::make($image)
            ->save(public_path() . "/" . $image_name);
            $announcement->image = $image_name;
            $announcement->save();

            if ($old_image) {
                if (File::exists(public_path() . "/" . $old_image)) {
                    unlink(public_path() . "/" . $old_image);
                }
            }
        }

        $announcement->description = $request->description;
        $announcement->title = $request->title;
        $announcement->expired_date = $request->expired_date;
        $announcement->status = $request->status ? 1 : 0;
        $announcement->save();
        $notification = trans("admin_validation.Updated Successfully");
        $notification = ["messege" => $notification, "alert-type" => "success"];

        return redirect()->back()->with($notification);
    }

    public function headerPhoneNumber()
    {
        $setting = Setting::select("topbar_phone", "topbar_email")->first();

        return response()->json(["setting" => $setting], 200);
    }

    public function updateHeaderPhoneNumber(Request $request)
    {
        $rules = [
            "topbar_phone" => "required",
            "topbar_email" => "required",
        ];

        $customMessages = [
            "topbar_phone.required" => trans(
                "admin_validation.Topbar phone is required"
            ),
            "topbar_email.required" => trans(
                "admin_validation.Topbar email is required"
            ),
        ];

        $this->validate($request, $rules, $customMessages);

        $setting = Setting::first();
        $setting->topbar_phone = $request->topbar_phone;
        $setting->topbar_email = $request->topbar_email;
        $setting->save();

        $notification = trans("admin_validation.Update Successfully");
        return response()->json(["notification" => $notification], 200);
    }

    public function loginPage()
    {
        $banner = BannerImage::select("image")
            ->whereId("13")
            ->first();

        return view("admin.login_page", compact("banner"));
    }

    public function updateloginPage(Request $request)
    {
        $banner = BannerImage::whereId("13")->first();

        if ($request->image) {
            $existing_banner = $banner->image;
            $extention = $request->image->getClientOriginalExtension();
            $banner_name = "banner" .date("-Y-m-d-h-i-s-") .rand(999, 9999) ."." .$extention;
            $banner_name = "uploads/website-images/" . $banner_name;

            Image::make($request->image)
            ->save(public_path() . "/" . $banner_name);
            $banner->image = $banner_name;
            $banner->save();

            if ($existing_banner) {
                if (File::exists(public_path() . "/" . $existing_banner)) {
                    unlink(public_path() . "/" . $existing_banner);
                }
            }
        }

        $notification = trans("admin_validation.Update Successfully");
        $notification = ["messege" => $notification, "alert-type" => "success"];

        return redirect()->back()->with($notification);
    }

    public function shopPage()
    {
        $shop_page = ShopPage::first();
        return view("admin.shop_page", compact("shop_page"));
    }

    public function updateFilterPrice(Request $request)
    {
        $rules = [
            "filter_price_range" => "required|numeric",
        ];
        $customMessages = [
            "filter_price_range.required" => trans(
                "admin_validation.Filter price is required"
            ),
            "filter_price_range.numeric" => trans(
                "admin_validation.Filter price should be numeric number"
            ),
        ];

        $this->validate($request, $rules, $customMessages);

        $shop_page = ShopPage::first();
        $shop_page->filter_price_range = $request->filter_price_range;
        $shop_page->save();

        $notification = trans("admin_validation.Update Successfully");
        $notification = ["message" => $notification, "alert-type" => "success"];

        return redirect()->back()->with($notification);
    }


    public function seoSetup()
    {
        if(!auth()->user()->can('seoSetup')){
            abort(403, 'Unauthorized action.');
        } 
        $pages = SeoSetting::all();
        return view("admin.seo_setup", compact("pages"));
    }

    public function getSeoSetup($id)
    {
        $page = SeoSetting::find($id);
        return response()->json(["page" => $page], 200);
    }

    public function updateSeoSetup(Request $request, $id)
    {
        $rules = [
            "seo_title" => "required",
            "seo_description" => "required",
            "meta_title" => "nullable|string|max:255",
            "meta_description" => "nullable|string",
            "meta_image" => "nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096",
            "meta_copyright" => "nullable|string|max:255",
            "site_name" => "nullable|string|max:255",
        ];

        $customMessages = [
            "seo_title.required" => trans(
                "admin_validation.Seo title is required"
            ),
            "seo_description.required" => trans(
                "admin_validation.Seo description is required"
            ),
        ];

        $this->validate($request, $rules, $customMessages);

        $page = SeoSetting::find($id);
        $page->seo_title = $request->seo_title;
        $page->seo_description = $request->seo_description;
        $page->seo_author = $request->seo_author;
        $page->seo_keywords = $request->seo_keywords;
        $page->seo_publisher = $request->seo_publisher;
        $page->canonical_url = $request->canonical_url;
        $page->meta_title = $request->meta_title;
        $page->meta_description = $request->meta_description;
        $page->meta_copyright = $request->meta_copyright;
        $page->site_name = $request->site_name;

        if ($request->hasFile('meta_image')) {
            $old_image = $page->meta_image;
            $image = $request->file('meta_image');
            $ext = $image->getClientOriginalExtension();
            $image_name = 'meta-image-' . date('Y-m-d-h-i-s-') . rand(999, 9999) . '.' . $ext;
            $image_name = 'uploads/website-images/' . $image_name;
            Image::make($image)->save(public_path() . '/' . $image_name);
            $page->meta_image = $image_name;

            if ($old_image && File::exists(public_path() . '/' . $old_image)) {
                unlink(public_path() . '/' . $old_image);
            }
        }

        $page->save();

        $notification = trans("admin_validation.Update Successfully");
        $notification = ["messege" => $notification, "alert-type" => "success"];

        return redirect()->back()->with($notification);
    }

    public function productProgressbar()
    {
        $setting = Setting::select("show_product_progressbar")->first();
        return response()->json(["setting" => $setting], 200);
    }

    public function updateProductProgressbar()
    {
        $setting = Setting::first();

        if ($setting->show_product_progressbar == 1) {
            $setting->show_product_progressbar = 0;
            $setting->save();
            $message = trans("admin_validation.Inactive Successfully");
        } else {
            $setting->show_product_progressbar = 1;
            $setting->save();
            $message = trans("admin_validation.Active Successfully");
        }

        return response()->json($message);
    }

    public function defaultAvatar()
    {
        $defaultProfile = BannerImage::select("title", "image")
            ->whereId("15")
            ->first();

        return view("admin.default_profile_image", compact("defaultProfile"));
    }

    public function updateDefaultAvatar(Request $request)
    {
        $defaultProfile = BannerImage::whereId("15")->first();
        if ($request->avatar) {
            $existing_avatar = $defaultProfile->image;
            $extention = $request->avatar->getClientOriginalExtension();
            $default_avatar = "default-avatar" .date("-Y-m-d-h-i-s-") .rand(999, 9999) ."." .$extention;
            $default_avatar = "uploads/website-images/" . $default_avatar;
            Image::make($request->avatar)
            ->save(public_path() . "/" . $default_avatar);
            $defaultProfile->image = $default_avatar;
            $defaultProfile->save();

            if ($existing_avatar) {
                if (File::exists(public_path() . "/" . $existing_avatar)) {
                    unlink(public_path() . "/" . $existing_avatar);
                }
            }
        }

        $notification = trans("admin_validation.Update Successfully");
        $notification = ["messege" => $notification, "alert-type" => "success"];

        return redirect()->back()->with($notification);
    }

    public function sellerCondition()
    {
        $setting = Setting::select("seller_condition")->first();
        return view("admin.seller_condition", compact("setting"));
    }

    public function updatesellerCondition(Request $request)
    {
        $rules = [
            "terms_and_condition" => "required",
        ];

        $customMessages = [
            "terms_and_condition.required" => trans(
                "admin_validation.Terms and condition is required"
            ),
        ];

        $this->validate($request, $rules, $customMessages);

        $setting = Setting::first();
        $setting->seller_condition = $request->terms_and_condition;
        $setting->save();

        $notification = trans("admin_validation.Update Successfully");
        $notification = ["messege" => $notification, "alert-type" => "success"];
        return redirect()->back()->with($notification);
    }

    public function subscriptionBanner()
    {
        $subscription_banner = BannerImage::select(
            "id",
            "image",
            "banner_location",
            "header",
            "title"
        )->find(27);

        return view("admin.subscription_banner",compact("subscription_banner"));
    }

    public function updatesubscriptionBanner(Request $request)
    {
        $rules = [
            "title" => "required",
            "header" => "required",
        ];

        $customMessages = [
            "title.required" => trans("admin_validation.Title is required"),
            "header.required" => trans("admin_validation.Header is required"),
        ];

        $this->validate($request, $rules, $customMessages);

        $subscription_banner = BannerImage::find(27);
        if ($request->image) {
            $existing_banner = $subscription_banner->image;
            $extention = $request->image->getClientOriginalExtension();
            $banner_name = "banner" .date("-Y-m-d-h-i-s-") .rand(999, 9999) ."." .$extention;
            $banner_name = "uploads/website-images/" . $banner_name;
            Image::make($request->image)
            ->save(public_path() . "/" . $banner_name);
            $subscription_banner->image = $banner_name;
            $subscription_banner->save();

            if ($existing_banner) {
                if (File::exists(public_path() . "/" . $existing_banner)) {
                    unlink(public_path() . "/" . $existing_banner);
                }
            }
        }

        $subscription_banner->title = $request->title;
        $subscription_banner->header = $request->header;
        $subscription_banner->save();

        $notification = trans("admin_validation.Update Successfully");
        $notification = ["messege" => $notification, "alert-type" => "success"];
        return redirect()->back()->with($notification);
    }

    public function image_content()
    {
        $image_content = Setting::select(
            "empty_cart",
            "empty_wishlist",
            "change_password_image",
            "become_seller_avatar",
            "become_seller_banner",
            "admin_login_page"
        )->first();

        return view("admin.image_content", compact("image_content"));
    }

    public function updateImageContent(Request $request)
    {
        $image_content = Setting::first();

        if ($request->empty_cart) {
            $existing_banner = $image_content->empty_cart;
            $extention = $request->empty_cart->getClientOriginalExtension();
            $banner_name =
                "empty_cart" .
                date("-Y-m-d-h-i-s-") .
                rand(999, 9999) .
                "." .
                $extention;
            $banner_name = "uploads/website-images/" . $banner_name;
            Image::make($request->empty_cart)->save(
                public_path() . "/" . $banner_name
            );
            $image_content->empty_cart = $banner_name;
            $image_content->save();
            if ($existing_banner) {
                if (File::exists(public_path() . "/" . $existing_banner)) {
                    unlink(public_path() . "/" . $existing_banner);
                }
            }
        }

        if ($request->empty_wishlist) {
            $existing_banner = $image_content->empty_wishlist;
            $extention = $request->empty_wishlist->getClientOriginalExtension();
            $banner_name =
                "empty_wishlist" .
                date("-Y-m-d-h-i-s-") .
                rand(999, 9999) .
                "." .
                $extention;
            $banner_name = "uploads/website-images/" . $banner_name;
            Image::make($request->empty_wishlist)->save(
                public_path() . "/" . $banner_name
            );
            $image_content->empty_wishlist = $banner_name;
            $image_content->save();
            if ($existing_banner) {
                if (File::exists(public_path() . "/" . $existing_banner)) {
                    unlink(public_path() . "/" . $existing_banner);
                }
            }
        }

        if ($request->change_password_image) {
            $existing_banner = $image_content->change_password_image;
            $extention = $request->change_password_image->getClientOriginalExtension();
            $banner_name =
                "change_password_image" .
                date("-Y-m-d-h-i-s-") .
                rand(999, 9999) .
                "." .
                $extention;
            $banner_name = "uploads/website-images/" . $banner_name;
            Image::make($request->change_password_image)->save(
                public_path() . "/" . $banner_name
            );
            $image_content->change_password_image = $banner_name;
            $image_content->save();
            if ($existing_banner) {
                if (File::exists(public_path() . "/" . $existing_banner)) {
                    unlink(public_path() . "/" . $existing_banner);
                }
            }
        }

        if ($request->become_seller_avatar) {
            $existing_banner = $image_content->become_seller_avatar;
            $extention = $request->become_seller_avatar->getClientOriginalExtension();
            $banner_name =
                "become_seller_avatar" .
                date("-Y-m-d-h-i-s-") .
                rand(999, 9999) .
                "." .
                $extention;
            $banner_name = "uploads/website-images/" . $banner_name;
            Image::make($request->become_seller_avatar)->save(
                public_path() . "/" . $banner_name
            );
            $image_content->become_seller_avatar = $banner_name;
            $image_content->save();
            if ($existing_banner) {
                if (File::exists(public_path() . "/" . $existing_banner)) {
                    unlink(public_path() . "/" . $existing_banner);
                }
            }
        }

        if ($request->become_seller_banner) {
            $existing_banner = $image_content->become_seller_banner;
            $extention = $request->become_seller_banner->getClientOriginalExtension();
            $banner_name =
                "become_seller_banner" .
                date("-Y-m-d-h-i-s-") .
                rand(999, 9999) .
                "." .
                $extention;
            $banner_name = "uploads/website-images/" . $banner_name;
            Image::make($request->become_seller_banner)->save(
                public_path() . "/" . $banner_name
            );
            $image_content->become_seller_banner = $banner_name;
            $image_content->save();
            if ($existing_banner) {
                if (File::exists(public_path() . "/" . $existing_banner)) {
                    unlink(public_path() . "/" . $existing_banner);
                }
            }
        }

        if ($request->admin_login_page) {
            $existing_banner = $image_content->admin_login_page;
            $extention = $request->admin_login_page->getClientOriginalExtension();
            $banner_name =
                "admin_login_page" .
                date("-Y-m-d-h-i-s-") .
                rand(999, 9999) .
                "." .
                $extention;
            $banner_name = "uploads/website-images/" . $banner_name;
            Image::make($request->admin_login_page)->save(
                public_path() . "/" . $banner_name
            );
            $image_content->admin_login_page = $banner_name;
            $image_content->save();
            if ($existing_banner) {
                if (File::exists(public_path() . "/" . $existing_banner)) {
                    unlink(public_path() . "/" . $existing_banner);
                }
            }
        }



        $notification = trans("admin_validation.Update Successfully");
        $notification = ["messege" => $notification, "alert-type" => "success"];
        return redirect() ->back() ->with($notification);
    }

    public function livingArchivePage()
    {
        $setting = Setting::select(
            'living_archive_title',
            'living_archive_subtitle',
            'living_archive_intro',
            'living_archive_affirmation',
            'living_archive_primary_cta_label',
            'living_archive_primary_cta_url',
            'living_archive_secondary_cta_label',
            'living_archive_secondary_cta_url',
            'living_archive_qr_text',
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
            'living_archive_logo_image',
            'living_archive_hero_image',
            'living_contact_phone',
            'living_contact_email',
            'living_phases_intro',
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
            'living_phase1_title',
            'living_phase1_affirmation',
            'living_phase2_title',
            'living_phase2_affirmation',
            'living_phase3_title',
            'living_phase3_affirmation',
            'living_phase4_title',
            'living_phase4_affirmation'
        )->first();

        $seo = SeoSetting::firstOrNew(['page_name' => 'Living Archive']);

        return view('admin.living_archive_settings', compact('setting', 'seo'));
    }

    public function updateLivingArchivePage(Request $request)
    {
        $rules = [
            'living_archive_title' => 'nullable|string|max:255',
            'living_archive_subtitle' => 'nullable|string|max:255',
            'living_archive_intro' => 'nullable|string',
            'living_archive_affirmation' => 'nullable|string|max:255',
            'living_archive_primary_cta_label' => 'nullable|string|max:255',
            'living_archive_primary_cta_url' => 'nullable|string|max:255',
            'living_archive_secondary_cta_label' => 'nullable|string|max:255',
            'living_archive_secondary_cta_url' => 'nullable|string|max:255',
            'living_archive_qr_text' => 'nullable|string',
            'living_ritual_before' => 'nullable|string',
            'living_ritual_during' => 'nullable|string',
            'living_ritual_after' => 'nullable|string',
            'living_crest_title' => 'nullable|string|max:255',
            'living_crest_body_one' => 'nullable|string',
            'living_crest_body_two' => 'nullable|string',
            'living_crest_body_three' => 'nullable|string',
            'living_crest_mission' => 'nullable|string',
            'living_crest_secondary_caption' => 'nullable|string|max:255',
            'living_crest_primary_image' => 'nullable|string|max:255',
            'living_crest_secondary_image' => 'nullable|string|max:255',
            'living_lineage_title' => 'nullable|string|max:255',
            'living_lineage_intro' => 'nullable|string',
            'living_lineage_tree_text' => 'nullable|string',
            'living_lineage_clan_text' => 'nullable|string',
            'living_lineage_shields_text' => 'nullable|string',
            'living_lineage_feathers_text' => 'nullable|string',
            'living_lineage_endline' => 'nullable|string|max:255',
            'living_crests_title' => 'nullable|string|max:255',
            'living_crests_intro' => 'nullable|string',
            'living_youth_crest_title' => 'nullable|string|max:255',
            'living_youth_crest_declaration' => 'nullable|string|max:255',
            'living_youth_crest_body' => 'nullable|string',
            'living_youth_crest_image' => 'nullable|string|max:255',
            'living_keeper_crest_title' => 'nullable|string|max:255',
            'living_keeper_crest_declaration' => 'nullable|string|max:255',
            'living_keeper_crest_body' => 'nullable|string',
            'living_keeper_crest_image' => 'nullable|string|max:255',
            'living_witness_crest_title' => 'nullable|string|max:255',
            'living_witness_crest_declaration' => 'nullable|string|max:255',
            'living_witness_crest_body' => 'nullable|string',
            'living_witness_crest_image' => 'nullable|string|max:255',
            'living_qr_crest_image' => 'nullable|string|max:255',
            'living_pathway_title' => 'nullable|string|max:255',
            'living_pathway_intro' => 'nullable|string',
            'living_pathway_step1_title' => 'nullable|string|max:255',
            'living_pathway_step1_body' => 'nullable|string',
            'living_pathway_step2_title' => 'nullable|string|max:255',
            'living_pathway_step2_body' => 'nullable|string',
            'living_pathway_step3_title' => 'nullable|string|max:255',
            'living_pathway_step3_body' => 'nullable|string',
            'living_media_merch_title' => 'nullable|string|max:255',
            'living_media_merch_intro' => 'nullable|string',
            'living_media_merch_card1_title' => 'nullable|string|max:255',
            'living_media_merch_card1_body' => 'nullable|string',
            'living_media_merch_card1_cta_label' => 'nullable|string|max:255',
            'living_media_merch_card1_cta_url' => 'nullable|string|max:255',
            'living_media_merch_card2_title' => 'nullable|string|max:255',
            'living_media_merch_card2_body' => 'nullable|string',
            'living_media_merch_card2_cta_label' => 'nullable|string|max:255',
            'living_media_merch_card2_cta_url' => 'nullable|string|max:255',
            'living_qr_title' => 'nullable|string|max:255',
            'living_qr_intro' => 'nullable|string',
            'living_qr_cta_label' => 'nullable|string|max:255',
            'living_qr_cta_url' => 'nullable|string|max:255',
            'living_contact_title' => 'nullable|string|max:255',
            'living_contact_intro' => 'nullable|string',
            'living_contact_training_title' => 'nullable|string|max:255',
            'living_contact_training_body' => 'nullable|string',
            'living_contact_training_cta_label' => 'nullable|string|max:255',
            'living_contact_training_cta_url' => 'nullable|string|max:255',
            'living_contact_events_title' => 'nullable|string|max:255',
            'living_contact_events_body' => 'nullable|string',
            'living_contact_events_cta_label' => 'nullable|string|max:255',
            'living_contact_events_cta_url' => 'nullable|string|max:255',
            'living_contact_general_title' => 'nullable|string|max:255',
            'living_contact_general_body' => 'nullable|string',
            'living_contact_general_cta_label' => 'nullable|string|max:255',
            'living_contact_general_cta_url' => 'nullable|string|max:255',
            'living_contact_support_cta_label' => 'nullable|string|max:255',
            'living_contact_support_cta_url' => 'nullable|string|max:255',
            'living_certification_title' => 'nullable|string|max:255',
            'living_certification_intro' => 'nullable|string',
            'living_certification_text' => 'nullable|string',
            'living_archive_logo_image' => 'nullable|string|max:255',
            'living_archive_hero_image' => 'nullable|string|max:255',
            'living_contact_phone' => 'nullable|string|max:120',
            'living_contact_email' => 'nullable|string|max:120',
            'living_phases_intro' => 'nullable|string',
            'living_handoff_page_name' => 'nullable|string|max:255',
            'living_handoff_logo_url' => 'nullable|string|max:255',
            'living_handoff_email' => 'nullable|string|max:255',
            'living_handoff_phone' => 'nullable|string|max:120',
            'living_handoff_social_instagram' => 'nullable|string|max:255',
            'living_handoff_social_facebook' => 'nullable|string|max:255',
            'living_handoff_social_youtube' => 'nullable|string|max:255',
            'living_handoff_address' => 'nullable|string',
            'living_handoff_intro' => 'nullable|string',
            'living_handoff_mission' => 'nullable|string',
            'living_handoff_supporter' => 'nullable|string',
            'living_handoff_coming_soon' => 'nullable|string',
            'living_handoff_tagline1' => 'nullable|string|max:255',
            'living_handoff_tagline2' => 'nullable|string|max:255',
            'living_handoff_tagline3' => 'nullable|string|max:255',
            'living_handoff_tagline4' => 'nullable|string|max:255',
            'living_handoff_merch_apparel' => 'nullable|string|max:255',
            'living_handoff_merch_posters' => 'nullable|string|max:255',
            'living_handoff_merch_music' => 'nullable|string|max:255',
            'living_handoff_merch_donor' => 'nullable|string|max:255',
            'living_handoff_merch_digital' => 'nullable|string|max:255',
            'living_handoff_visual_hierarchy' => 'nullable|string',
            'living_handoff_palette_primary' => 'nullable|string|max:255',
            'living_handoff_palette_secondary' => 'nullable|string|max:255',
            'living_handoff_palette_accent' => 'nullable|string|max:255',
            'living_handoff_background_guide' => 'nullable|string',
            'living_handoff_footer_line' => 'nullable|string|max:255',
            'living_archive_logo_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'living_archive_hero_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:6144',
            'living_crest_primary_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'living_crest_secondary_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'living_youth_crest_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'living_keeper_crest_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'living_witness_crest_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'living_qr_crest_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'living_phase1_title' => 'nullable|string|max:255',
            'living_phase1_affirmation' => 'nullable|string',
            'living_phase2_title' => 'nullable|string|max:255',
            'living_phase2_affirmation' => 'nullable|string',
            'living_phase3_title' => 'nullable|string|max:255',
            'living_phase3_affirmation' => 'nullable|string',
            'living_phase4_title' => 'nullable|string|max:255',
            'living_phase4_affirmation' => 'nullable|string',
        ];

        $seoRules = [
            'living_seo_title' => 'nullable|string|max:255',
            'living_seo_description' => 'nullable|string',
            'living_seo_keywords' => 'nullable|string|max:255',
            'living_seo_author' => 'nullable|string|max:255',
            'living_seo_publisher' => 'nullable|string|max:255',
            'living_seo_canonical' => 'nullable|string|max:255',
            'living_seo_meta_image' => 'nullable|string|max:255',
        ];

        $this->validate($request, array_merge($rules, $seoRules));

        $setting = Setting::first();
        if (!$setting) {
            $setting = new Setting();
        }

        $fields = array_keys($rules);
        foreach ($fields as $field) {
            if (str_contains($field, '_file')) {
                continue;
            }
            if ($request->exists($field)) {
                $setting->{$field} = $request->input($field, $setting->{$field});
            }
        }

        $uploadMap = [
            'living_archive_logo_image_file' => 'living_archive_logo_image',
            'living_archive_hero_image_file' => 'living_archive_hero_image',
            'living_crest_primary_image_file' => 'living_crest_primary_image',
            'living_crest_secondary_image_file' => 'living_crest_secondary_image',
            'living_youth_crest_image_file' => 'living_youth_crest_image',
            'living_keeper_crest_image_file' => 'living_keeper_crest_image',
            'living_witness_crest_image_file' => 'living_witness_crest_image',
            'living_qr_crest_image_file' => 'living_qr_crest_image',
        ];

        foreach ($uploadMap as $fileKey => $targetField) {
            if ($request->hasFile($fileKey)) {
                $uploadedPath = $this->handleLivingUpload($request->file($fileKey), $targetField, $setting->{$targetField} ?? null);
                $setting->{$targetField} = $uploadedPath;
            }
        }

        $setting->save();

        // Upsert SEO settings for Living Archive page
        $seo = SeoSetting::firstOrNew(['page_name' => 'Living Archive']);
        $seo->page_name = 'Living Archive';
        $seo->seo_title = $request->input('living_seo_title', $seo->seo_title);
        $seo->seo_description = $request->input('living_seo_description', $seo->seo_description);
        $seo->seo_keywords = $request->input('living_seo_keywords', $seo->seo_keywords);
        $seo->seo_author = $request->input('living_seo_author', $seo->seo_author);
        $seo->seo_publisher = $request->input('living_seo_publisher', $seo->seo_publisher);
        $seo->canonical_url = $request->input('living_seo_canonical', $seo->canonical_url);
        $seo->meta_title = $seo->seo_title; // keep meta_title in sync if present
        $seo->meta_description = $seo->seo_description;
        $seo->meta_image = $request->input('living_seo_meta_image', $seo->meta_image);
        $seo->save();

        $notification = trans("admin_validation.Update Successfully");
        $notification = ["messege" => $notification, "alert-type" => "success"];
        return redirect()->back()->with($notification);
    }

    protected function handleLivingUpload($file, string $label, ?string $oldPath = null): string
    {
        $slug = Str::slug($label, '-');
        $ext = $file->getClientOriginalExtension();
        $directory = public_path('uploads/living-archive');
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }
        $relative = "uploads/living-archive/{$slug}-" . time() . '-' . uniqid() . ".{$ext}";

        Image::make($file)->save(public_path() . "/" . $relative);

        if ($oldPath && File::exists(public_path() . "/" . $oldPath)) {
            @unlink(public_path() . "/" . $oldPath);
        }

        return $relative;
    }
}
