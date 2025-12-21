<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmailTemplateController extends Controller
{
    // Default template content
    private $defaultTemplates = [
        'deal_created_confirmation' => [
            'subject' => 'Your Deal Has Been Created',
            'body' => '<p>Hi {{vendor_name}},</p><p>Your deal "{{deal_title}}" has been created successfully and is pending approval.</p><p>We will review it within 24 hours and notify you once it is approved.</p><p>Thank you for using {{site_name}}!</p>'
        ],
        'deal_approved' => [
            'subject' => 'Your Deal Has Been Approved!',
            'body' => '<p>Hi {{vendor_name}},</p><p>Great news! Your deal "{{deal_title}}" has been approved and is now live.</p><p><a href="{{deal_url}}">View Your Deal</a></p><p>Thank you for using {{site_name}}!</p>'
        ],
        'deal_rejected' => [
            'subject' => 'Your Deal Needs Updates',
            'body' => '<p>Hi {{vendor_name}},</p><p>Your deal "{{deal_title}}" requires some changes before it can be approved.</p><p><strong>Reason:</strong> {{rejection_reason}}</p><p>Please update your deal and resubmit.</p><p>Thank you for using {{site_name}}!</p>'
        ],
        'deal_expiring_soon' => [
            'subject' => 'Your Deal is Expiring Soon',
            'body' => '<p>Hi {{vendor_name}},</p><p>Your deal "{{deal_title}}" will expire in {{days_remaining}} days.</p><p>Consider creating a new deal to keep your presence on our platform.</p><p>Thank you for using {{site_name}}!</p>'
        ],
    ];

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
            'site_name' => 'Lake County Local Deals',
            'rejection_reason' => 'Please provide more details about the deal.',
            'days_remaining' => '3',
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
            'rejection_reason' => 'This is a test rejection reason.',
            'days_remaining' => '7',
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
        
        // Check if we have a default template for this
        if (!isset($this->defaultTemplates[$template->name])) {
            return redirect()->back()->with('error', 'No default template available for this template.');
        }

        $default = $this->defaultTemplates[$template->name];
        
        DB::transaction(function() use ($template, $default) {
            $template->update([
                'subject' => $default['subject'],
                'body' => $default['body'],
            ]);
        });

        return redirect()->back()->with('success', 'Template has been reset to default.');
    }
}
