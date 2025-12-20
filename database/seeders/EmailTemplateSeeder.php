<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class EmailTemplateSeeder extends Seeder
{
    public function run()
    {
        $templates = [
            // Vendor Templates
            [
                'key' => 'deal.approved',
                'name' => 'Deal Approved',
                'category' => 'vendor',
                'subject' => 'Your deal "{deal_title}" has been approved!',
                'body' => '<p>Hi {vendor_name},</p><p>Great news! Your deal "{deal_title}" has been approved and is now live on the platform.</p><p><a href="{deal_url}">View your deal</a></p><p>Best regards,<br>Lake County Local Deals Team</p>',
                'variables' => ['vendor_name', 'deal_title', 'deal_url'],
                'is_active' => true,
                'is_default' => true,
            ],
            [
                'key' => 'deal.rejected',
                'name' => 'Deal Rejected',
                'category' => 'vendor',
                'subject' => 'Your deal "{deal_title}" needs changes',
                'body' => '<p>Hi {vendor_name},</p><p>Your deal "{deal_title}" has been reviewed and requires some changes before it can be approved.</p><p><strong>Reason:</strong> {rejection_reason}</p><p>Please review and update your deal: <a href="{deal_url}">Edit Deal</a></p><p>Best regards,<br>Lake County Local Deals Team</p>',
                'variables' => ['vendor_name', 'deal_title', 'rejection_reason', 'deal_url'],
                'is_active' => true,
                'is_default' => true,
            ],
            [
                'key' => 'deal.changes_requested',
                'name' => 'Deal Changes Requested',
                'category' => 'vendor',
                'subject' => 'Changes requested for your deal "{deal_title}"',
                'body' => '<p>Hi {vendor_name},</p><p>An admin has requested changes to your deal "{deal_title}".</p><p><strong>Requested Changes:</strong></p><p>{changes_message}</p><p>Please review and update your deal: <a href="{deal_url}">Edit Deal</a></p><p>Best regards,<br>Lake County Local Deals Team</p>',
                'variables' => ['vendor_name', 'deal_title', 'changes_message', 'deal_url'],
                'is_active' => true,
                'is_default' => true,
            ],
            [
                'key' => 'deal.sold_out',
                'name' => 'Deal Sold Out',
                'category' => 'vendor',
                'subject' => 'Your deal "{deal_title}" has sold out!',
                'body' => '<p>Hi {vendor_name},</p><p>Congratulations! Your deal "{deal_title}" has sold out.</p><p>Total sales: {total_sales}<br>Total revenue: ${total_revenue}</p><p><a href="{deal_url}">View deal details</a></p><p>Best regards,<br>Lake County Local Deals Team</p>',
                'variables' => ['vendor_name', 'deal_title', 'total_sales', 'total_revenue', 'deal_url'],
                'is_active' => true,
                'is_default' => true,
            ],
            [
                'key' => 'subscription.created',
                'name' => 'Subscription Created',
                'category' => 'vendor',
                'subject' => 'Welcome to {site_name}!',
                'body' => '<p>Hi {vendor_name},</p><p>Your subscription to the <strong>{package_tier} Plan</strong> has been successfully activated!</p><p>You now have access to all features included in your plan.</p><p><a href="{dashboard_url}">Go to Dashboard</a></p><p>Best regards,<br>{site_name} Team</p>',
                'variables' => ['vendor_name', 'package_tier', 'site_name', 'dashboard_url'],
                'is_active' => true,
                'is_default' => true,
            ],
            [
                'key' => 'subscription.expired',
                'name' => 'Subscription Expired',
                'category' => 'vendor',
                'subject' => 'Your subscription has expired',
                'body' => '<p>Hi {vendor_name},</p><p>Your subscription to the {package_tier} Plan has expired.</p><p>Please renew your subscription to continue using all features.</p><p><a href="{subscription_url}">Renew Subscription</a></p><p>Best regards,<br>{site_name} Team</p>',
                'variables' => ['vendor_name', 'package_tier', 'site_name', 'subscription_url'],
                'is_active' => true,
                'is_default' => true,
            ],
            [
                'key' => 'vendor.suspended',
                'name' => 'Account Suspended',
                'category' => 'vendor',
                'subject' => 'Your account has been suspended',
                'body' => '<p>Hi {vendor_name},</p><p>Your account has been suspended.</p><p><strong>Reason:</strong> {suspension_reason}</p><p>If you have any questions, please contact support.</p><p>Best regards,<br>{site_name} Team</p>',
                'variables' => ['vendor_name', 'suspension_reason', 'site_name'],
                'is_active' => true,
                'is_default' => true,
            ],
            // Consumer Templates
            [
                'key' => 'purchase.confirmation',
                'name' => 'Purchase Confirmation',
                'category' => 'consumer',
                'subject' => 'Your purchase confirmation - {deal_title}',
                'body' => '<p>Hi {consumer_name},</p><p>Thank you for your purchase!</p><p><strong>Deal:</strong> {deal_title}<br><strong>Price:</strong> ${purchase_amount}<br><strong>Purchase Date:</strong> {purchase_date}</p><p><a href="{deal_url}">View Deal Details</a></p><p>Best regards,<br>{site_name} Team</p>',
                'variables' => ['consumer_name', 'deal_title', 'purchase_amount', 'purchase_date', 'deal_url', 'site_name'],
                'is_active' => true,
                'is_default' => true,
            ],
            [
                'key' => 'purchase.receipt',
                'name' => 'Purchase Receipt',
                'category' => 'consumer',
                'subject' => 'Receipt for your purchase - {deal_title}',
                'body' => '<p>Hi {consumer_name},</p><p>Here is your receipt for your recent purchase.</p><p><strong>Order ID:</strong> {order_id}<br><strong>Deal:</strong> {deal_title}<br><strong>Amount:</strong> ${purchase_amount}<br><strong>Date:</strong> {purchase_date}</p><p>Best regards,<br>{site_name} Team</p>',
                'variables' => ['consumer_name', 'order_id', 'deal_title', 'purchase_amount', 'purchase_date', 'site_name'],
                'is_active' => true,
                'is_default' => true,
            ],
            // Admin Templates
            [
                'key' => 'admin.new_deal',
                'name' => 'New Deal Submitted',
                'category' => 'admin',
                'subject' => 'New deal submitted: {deal_title}',
                'body' => '<p>A new deal has been submitted for review.</p><p><strong>Deal:</strong> {deal_title}<br><strong>Vendor:</strong> {vendor_name}<br><strong>Category:</strong> {category}</p><p><a href="{deal_url}">Review Deal</a></p>',
                'variables' => ['deal_title', 'vendor_name', 'category', 'deal_url'],
                'is_active' => true,
                'is_default' => true,
            ],
            [
                'key' => 'admin.ai_flagged',
                'name' => 'AI Flagged Deal',
                'category' => 'admin',
                'subject' => 'Deal flagged by AI: {deal_title}',
                'body' => '<p>A deal has been flagged by the AI quality scoring system.</p><p><strong>Deal:</strong> {deal_title}<br><strong>Vendor:</strong> {vendor_name}<br><strong>AI Score:</strong> {ai_score}/100<br><strong>Reason:</strong> {flag_reason}</p><p><a href="{deal_url}">Review Deal</a></p>',
                'variables' => ['deal_title', 'vendor_name', 'ai_score', 'flag_reason', 'deal_url'],
                'is_active' => true,
                'is_default' => true,
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::updateOrCreate(
                ['key' => $template['key']],
                $template
            );
        }
    }
}


