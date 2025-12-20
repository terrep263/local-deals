<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlatformSetting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlatformSettingsController extends Controller
{
    public function index()
    {
        $groups = [
            'general' => PlatformSetting::getByGroup('general'),
            'deal' => PlatformSetting::getByGroup('deal'),
            'email' => PlatformSetting::getByGroup('email'),
            'payment' => PlatformSetting::getByGroup('payment'),
            'seo' => PlatformSetting::getByGroup('seo'),
            'maintenance' => PlatformSetting::getByGroup('maintenance'),
        ];

        return view('admin.settings.index', compact('groups'));
    }

    public function update(Request $request)
    {
        $settings = $request->except(['_token', '_method']);

        foreach ($settings as $key => $value) {
            if ($request->hasFile($key)) {
                // Handle file uploads
                $file = $request->file($key);
                $path = $file->store('settings', 'public');
                $value = $path;
            }

            PlatformSetting::set($key, $value);
        }

        ActivityLog::log(
            'settings.updated',
            'Admin updated platform settings',
            auth()->id(),
            'admin'
        );

        return redirect()->back()->with('success', 'Settings updated successfully');
    }

    public function testEmail(Request $request)
    {
        try {
            \Mail::raw('This is a test email from Lake County Local Deals.', function($message) use ($request) {
                $message->to($request->email)
                        ->subject('Test Email - Lake County Local Deals');
            });

            return redirect()->back()->with('success', 'Test email sent successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }
}


