<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class ConsumerEmailTemplatesSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name' => 'purchase_confirmation',
                'category' => 'consumer',
                'subject' => 'Purchase Confirmation - {{deal_title}}',
                'body' => '<h1>Thank You for Your Purchase!</h1>
                          <p>Hi {{customer_name}},</p>
                          <p>Your purchase of <strong>{{deal_title}}</strong> has been confirmed!</p>
                          <h3>Order Details:</h3>
                          <ul>
                            <li><strong>Order #:</strong> {{confirmation_code}}</li>
                            <li><strong>Quantity:</strong> {{quantity}}</li>
                            <li><strong>Total Paid:</strong> ${{total_amount}}</li>
                            <li><strong>Purchase Date:</strong> {{purchase_date}}</li>
                          </ul>
                          <h3>Your Voucher Codes:</h3>
                          <p>{{voucher_codes_list}}</p>
                          <p>Your vouchers are attached as a PDF. You can also download them anytime from your account.</p>
                          <p><a href="{{order_url}}" style="background-color: #4CAF50; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px;">View Order</a></p>
                          <h3>How to Redeem:</h3>
                          <ol>
                            <li>Present your voucher code to the vendor</li>
                            <li>Show the QR code for quick scanning</li>
                            <li>Enjoy your deal!</li>
                          </ol>
                          <p>Valid Until: {{valid_until}}</p>
                          <p>Thank you for supporting local businesses!</p>
                          <p>Best regards,<br>{{site_name}} Team</p>'
            ],
            [
                'name' => 'voucher_delivery',
                'category' => 'consumer',
                'subject' => 'Your Vouchers - {{deal_title}}',
                'body' => '<h1>Your Vouchers Are Ready!</h1>
                          <p>Hi {{customer_name}},</p>
                          <p>Your vouchers for <strong>{{deal_title}}</strong> are attached to this email.</p>
                          <h3>Voucher Details:</h3>
                          <ul>
                            <li><strong>Voucher Codes:</strong> {{voucher_codes_list}}</li>
                            <li><strong>Valid Until:</strong> {{valid_until}}</li>
                            <li><strong>Quantity:</strong> {{quantity}}</li>
                          </ul>
                          <p><a href="{{download_url}}">Download Vouchers PDF</a></p>
                          <p>Save this email or download the PDF for easy access to your vouchers.</p>
                          <p>Best regards,<br>{{site_name}} Team</p>'
            ],
            [
                'name' => 'voucher_reminder',
                'category' => 'consumer',
                'subject' => 'Reminder: Your Voucher Expires Soon',
                'body' => '<h1>Don\'t Forget to Use Your Voucher!</h1>
                          <p>Hi {{customer_name}},</p>
                          <p>This is a friendly reminder that your voucher for <strong>{{deal_title}}</strong> will expire in {{days_remaining}} days.</p>
                          <h3>Voucher Details:</h3>
                          <ul>
                            <li><strong>Voucher Code:</strong> {{voucher_code}}</li>
                            <li><strong>Expires:</strong> {{valid_until}}</li>
                          </ul>
                          <p>Don\'t miss out! Redeem your voucher before it expires.</p>
                          <p><a href="{{order_url}}">View Voucher</a></p>
                          <p>Best regards,<br>{{site_name}} Team</p>'
            ],
            [
                'name' => 'refund_confirmation',
                'category' => 'consumer',
                'subject' => 'Refund Processed - Order {{confirmation_code}}',
                'body' => '<h1>Refund Confirmation</h1>
                          <p>Hi {{customer_name}},</p>
                          <p>Your refund for order #{{confirmation_code}} has been processed.</p>
                          <h3>Refund Details:</h3>
                          <ul>
                            <li><strong>Order #:</strong> {{confirmation_code}}</li>
                            <li><strong>Deal:</strong> {{deal_title}}</li>
                            <li><strong>Refund Amount:</strong> ${{refund_amount}}</li>
                            <li><strong>Refund Date:</strong> {{refund_date}}</li>
                          </ul>
                          <p>Please allow 5-10 business days for the refund to appear in your account.</p>
                          <p>If you have any questions, please contact our support team.</p>
                          <p>Best regards,<br>{{site_name}} Team</p>'
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::updateOrCreate(
                ['name' => $template['name']],
                $template
            );
        }
    }
}
