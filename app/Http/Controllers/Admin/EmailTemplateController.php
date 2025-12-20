<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function index()
    {
        $templates = EmailTemplate::orderBy('category')->orderBy('name')->get();
        
        $categories = [
            'vendor' => $templates->where('category', 'vendor'),
            'consumer' => $templates->where('category', 'consumer'),
            'admin' => $templates->where('category', 'admin'),
        ];

        return view('admin.email-templates.index', compact('categories'));
    }

    public function edit($id)
    {
        $template = EmailTemplate::findOrFail($id);
        return view('admin.email-templates.edit', compact('template'));
    }

    public function update(Request $request, $id)
    {
        $template = EmailTemplate::findOrFail($id);
        
        $template->update([
            'subject' => $request->subject,
            'body' => $request->body,
        ]);

        return redirect()->route('admin.email-templates.index')
            ->with('success', 'Template updated successfully');
    }

    public function preview($id)
    {
        $template = EmailTemplate::findOrFail($id);
        
        // Sample variables for preview
        $variables = [
            'vendor_name' => 'John Doe',
            'deal_title' => 'Sample Deal Title',
            'deal_url' => url('/deals/sample-deal'),
        ];

        $rendered = $template->render($variables);

        return view('admin.email-templates.preview', [
            'template' => $template,
            'subject' => $rendered['subject'],
            'body' => $rendered['body'],
        ]);
    }

    public function test(Request $request, $id)
    {
        $template = EmailTemplate::findOrFail($id);
        
        $request->validate([
            'email' => 'required|email',
        ]);
        
        $variables = [
            'vendor_name' => 'Test Vendor',
            'deal_title' => 'Test Deal',
            'deal_url' => url('/deals/test'),
            'site_name' => 'Lake County Local Deals',
        ];

        $rendered = $template->render($variables);

        try {
            \Mail::html($rendered['body'], function($message) use ($rendered, $request) {
                $message->to($request->email)
                        ->subject($rendered['subject']);
            });

            return redirect()->back()->with('success', 'Test email sent successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }

    public function resetToDefault($id)
    {
        $template = EmailTemplate::findOrFail($id);
        
        // Reset to default - you would load defaults from a config or seeder
        // For now, just mark as needing reset
        return redirect()->back()->with('info', 'Reset functionality requires default templates to be defined');
    }
}

