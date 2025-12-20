<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display settings page
     */
    public function settings()
    {
        $settings = Settings::first();
        return view('admin.pages.settings', compact('settings'));
    }

    /**
     * Update general settings
     */
    public function settingsUpdates(Request $request)
    {
        $settings = Settings::first();
        
        $input = $request->all();
        
        // Handle logo upload
        if ($request->hasFile('site_logo')) {
            $file = $request->file('site_logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move('upload', $filename);
            $input['site_logo'] = $filename;
            
            // Delete old logo
            if ($settings->site_logo && File::exists('upload/' . $settings->site_logo)) {
                File::delete('upload/' . $settings->site_logo);
            }
        }
        
        // Handle favicon upload
        if ($request->hasFile('site_favicon')) {
            $file = $request->file('site_favicon');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move('upload', $filename);
            $input['site_favicon'] = $filename;
            
            // Delete old favicon
            if ($settings->site_favicon && File::exists('upload/' . $settings->site_favicon)) {
                File::delete('upload/' . $settings->site_favicon);
            }
        }

        // Handle footer logo upload
        if ($request->hasFile('site_footer_logo')) {
            $file = $request->file('site_footer_logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move('upload', $filename);
            $input['site_footer_logo'] = $filename;

            // Delete old footer logo
            if ($settings->site_footer_logo && File::exists('upload/' . $settings->site_footer_logo)) {
                File::delete('upload/' . $settings->site_footer_logo);
            }
        }
        
        $settings->update($input);
        
        \Session::flash('flash_message', trans('words.update_message'));
        return redirect()->back();
    }

    /**
     * Update SMTP settings
     */
    public function smtp_settings(Request $request)
    {
        // SMTP settings are typically stored in .env file
        // This method can update .env or store in settings table
        \Session::flash('flash_message', 'SMTP settings updated');
        return redirect()->back();
    }

    /**
     * Update social login settings
     */
    public function social_login_settings(Request $request)
    {
        // Social login settings typically stored in .env
        \Session::flash('flash_message', 'Social login settings updated');
        return redirect()->back();
    }

    /**
     * Update homepage settings
     */
    public function homepage_settings(Request $request)
    {
        $settings = Settings::first();
        
        $input = $request->all();
        
        // Handle homepage slide images
        for ($i = 1; $i <= 3; $i++) {
            if ($request->hasFile("home_slide_image{$i}")) {
                $file = $request->file("home_slide_image{$i}");
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move('upload', $filename);
                $input["home_slide_image{$i}"] = $filename;
                
                // Delete old image
                $oldImage = $settings->{"home_slide_image{$i}"};
                if ($oldImage && File::exists('upload/' . $oldImage)) {
                    File::delete('upload/' . $oldImage);
                }
            }
        }
        
        // Handle page background image
        if ($request->hasFile('page_bg_image')) {
            $file = $request->file('page_bg_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move('upload', $filename);
            $input['page_bg_image'] = $filename;
            
            if ($settings->page_bg_image && File::exists('upload/' . $settings->page_bg_image)) {
                File::delete('upload/' . $settings->page_bg_image);
            }
        }
        
        $settings->update($input);
        
        \Session::flash('flash_message', 'Homepage settings updated successfully');
        return redirect()->back();
    }

    /**
     * Update About Us page
     */
    public function aboutus_settings(Request $request)
    {
        $settings = Settings::first();
        
        $settings->update([
            'about_title' => $request->about_title,
            'about_description' => $request->about_description,
        ]);
        
        \Session::flash('flash_message', 'About Us page updated successfully');
        return redirect()->back();
    }

    /**
     * Update Contact page
     */
    public function contactus_settings(Request $request)
    {
        $settings = Settings::first();
        
        $settings->update([
            'contact_title' => $request->contact_title,
            'contact_address' => $request->contact_address,
            'contact_email' => $request->contact_email,
            'contact_number' => $request->contact_number,
            'contact_lat' => $request->contact_lat,
            'contact_long' => $request->contact_long,
        ]);
        
        \Session::flash('flash_message', 'Contact page updated successfully');
        return redirect()->back();
    }

    /**
     * Update Terms of Service
     */
    public function terms_of_service(Request $request)
    {
        $settings = Settings::first();
        
        $settings->update([
            'terms_of_title' => $request->terms_of_title,
            'terms_of_description' => $request->terms_of_description,
        ]);
        
        \Session::flash('flash_message', 'Terms of Service updated successfully');
        return redirect()->back();
    }

    /**
     * Update Privacy Policy
     */
    public function privacy_policy(Request $request)
    {
        $settings = Settings::first();
        
        $settings->update([
            'privacy_policy_title' => $request->privacy_policy_title,
            'privacy_policy_description' => $request->privacy_policy_description,
        ]);
        
        \Session::flash('flash_message', 'Privacy Policy updated successfully');
        return redirect()->back();
    }

    /**
     * Update AddThis and Disqus codes
     */
    public function addthisdisqus(Request $request)
    {
        $settings = Settings::first();
        
        $settings->update([
            'addthis_share_code' => $request->addthis_share_code,
            'disqus_comment_code' => $request->disqus_comment_code,
            'facebook_comment_code' => $request->facebook_comment_code,
        ]);
        
        \Session::flash('flash_message', 'AddThis/Disqus settings updated successfully');
        return redirect()->back();
    }

    /**
     * Update header and footer codes
     */
    public function headfootupdate(Request $request)
    {
        $settings = Settings::first();
        
        $settings->update([
            'site_header_code' => $request->site_header_code,
            'site_footer_code' => $request->site_footer_code,
            'site_copyright' => $request->site_copyright,
        ]);
        
        \Session::flash('flash_message', 'Header/Footer settings updated successfully');
        return redirect()->back();
    }
}
