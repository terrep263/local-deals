# AI Marketing Assistant - Quick Start

## 🚀 Getting Started (For Developers)

### Prerequisites
- Laravel 11 running on Laragon
- PHP 8.3+
- MySQL database
- Anthropic API key (in .env file)

### Installation Status
✅ **All components installed and migrated**

### Verify Installation

```powershell
# Navigate to project
cd c:\laragon\www\local-deals

# Check database tables exist
php artisan tinker
# In Tinker: Schema::hasTable('ai_marketing_content')  # Should return true
```

### Key Files

**Service Layer**
- `app/Services/ClaudeAIService.php` - Extended with 4 marketing methods
- `app/Services/MarketingContentService.php` - Business logic

**Models**
- `app/Models/AIMarketingContent.php` - Marketing content storage
- `app/Models/VendorEmailCampaign.php` - Email campaign tracking

**Controller**
- `app/Http/Controllers/Vendor/MarketingController.php` - 7 API endpoints

**Views**
- `resources/views/vendor/marketing/index.blade.php` - Dashboard
- `resources/views/vendor/marketing/partials/` - Result displays

**Routes**
- 7 new routes in `routes/web.php` for marketing operations

## 🎯 For End Users (Vendors)

### Access the Feature
1. Log in to your vendor dashboard
2. Go to `/vendor/marketing` 
3. Or look for "Marketing Assistant" in the main menu

### Generate Content

1. **Select Content Type**
   - Email Campaigns
   - Social Media Posts
   - Ad Copy
   - In-Store Signage

2. **Select Your Deal**
   - Choose from your active deals
   - More deals = better content options

3. **Select Platform** (if applicable)
   - Social: Facebook, Instagram, or Twitter
   - Ads: Google Ads or Facebook Ads
   - Email & Signage: No platform selection needed

4. **Generate**
   - Click "Generate Content"
   - Wait 10-15 seconds
   - Review the results

5. **Copy & Use**
   - Click copy button for each section
   - Paste into your marketing platform
   - Customize as needed

### Daily Limits

Check top right of dashboard for remaining content today:

- Free: 5/day
- Starter: 10/day  
- Basic: 25/day
- Pro: 100/day
- Enterprise: Unlimited

### Tips

- **Review before copying** - AI content is a starting point
- **Customize when needed** - Make it sound like your brand
- **Test different versions** - See which works best
- **Mark as used** - Track which content you're actually implementing
- **Check statistics** - See your usage patterns

## 🔧 For System Administrators

### Database Migrations

All migrations are complete:
```
✅ ai_marketing_content table
✅ vendor_email_campaigns table
✅ ai_usage_tracking extended for marketing
```

To verify:
```bash
php artisan migrate:status | grep "2025_12_22_100"
```

### Clear Caches After Updates

```bash
php artisan cache:clear
php artisan route:cache
```

### Monitor API Usage

Track Claude API calls in logs:
```bash
tail -f storage/logs/laravel.log | grep -i marketing
```

### Database Maintenance

```bash
# Check table sizes
SELECT TABLE_NAME, ROUND(((DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024), 2) FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 'local_deals' AND TABLE_NAME LIKE 'ai_%';

# Clean old generated content (optional)
php artisan tinker
DB::table('ai_marketing_content')->where('created_at', '<', now()->subDays(90))->delete();
```

## 📊 API Endpoints Reference

### Dashboard
```
GET /vendor/marketing
Returns dashboard with deals and usage stats
```

### Generate Email
```
POST /vendor/marketing/generate-email
Body: { deal_id: 123 }
Returns: subject_lines[], body_content, call_to_action
```

### Generate Social
```
POST /vendor/marketing/generate-social
Body: { deal_id: 123, platform: "facebook" | "instagram" | "twitter" }
Returns: post_content, hashtags[], call_to_action
```

### Generate Ads
```
POST /vendor/marketing/generate-ads
Body: { deal_id: 123, platform: "google_ads" | "facebook_ads" }
Returns: headlines[], descriptions[], call_to_action
```

### Generate Signage
```
POST /vendor/marketing/generate-signage
Body: { deal_id: 123 }
Returns: headline, subheadline, body_text, fine_print
```

### Mark as Used
```
POST /vendor/marketing/mark-used
Body: { content_id: 456 }
Returns: success message
```

### Rate Content
```
POST /vendor/marketing/rate
Body: { content_id: 456, rating: 4 }
Returns: success message
```

## 🐛 Troubleshooting

### "Daily limit reached"
- You've used all your available content for today
- Limit resets at midnight
- Upgrade your plan for more daily content

### Generated content seems generic
- AI uses deal information provided
- Add more specific details to your deal
- Try generating again with more details

### Can't access /vendor/marketing
- Make sure you're logged in as a vendor
- Check your account status is active
- Clear browser cache

### API returns error
- Check database migrations ran: `php artisan migrate:status`
- Verify ANTHROPIC_API_KEY is set in .env
- Check Laravel logs: `storage/logs/laravel.log`

### Copy button doesn't work
- Try refreshing the page
- Use a different browser
- Check browser console for JavaScript errors

## 📱 Content Type Details

### Email Campaigns
**Best for:** Newsletters, announcements, promotions  
**What you get:** 5 subject options, full email body, CTA  
**Platform:** Any email service (Resend, Mailchimp, etc.)

### Social Media
**Best for:** Reaching customers where they are  
**What you get:** Platform-optimized post, hashtags, CTA  
**Platforms:** Facebook, Instagram, Twitter

### Ad Copy
**Best for:** Paid advertising  
**What you get:** 3 headlines, 3 descriptions, CTA  
**Platforms:** Google Ads, Facebook Ads Manager

### In-Store Signage
**Best for:** Physical retail locations  
**What you get:** Headline, subheadline, body, fine print  
**Formats:** Window clings, posters, counter displays

## 💰 Costs

**Per Generation:** ~$0.02 USD
- Email: 1 generation = 5 subject lines + body
- Social: 1 generation per platform selected
- Ads: 1 generation per platform selected
- Signage: 1 generation = all sections

**Monthly Estimate:**
- Free (5/day): ~$3/month
- Starter (10/day): ~$6/month
- Basic (25/day): ~$15/month
- Pro (100/day): ~$60/month

## 📚 Documentation

For more details, see:
- `MARKETING_ASSISTANT_USER_GUIDE.md` - Full user guide
- `AI_MARKETING_ASSISTANT_IMPLEMENTATION.md` - Technical specs
- `MARKETING_ASSISTANT_CHECKLIST.md` - Testing checklist
- `LARAGON_PHP_SETUP_GUIDE.md` - PHP command setup

## ✅ What's Working

- ✅ Email campaign generation
- ✅ Social media content (3 platforms)
- ✅ Ad copy (2 platforms)
- ✅ In-store signage
- ✅ Rate limiting per tier
- ✅ Content persistence
- ✅ Usage tracking
- ✅ Copy to clipboard
- ✅ Content rating system
- ✅ Recent content history

## 🚀 Next Steps

**For Vendors:**
1. Log in and navigate to `/vendor/marketing`
2. Select a deal and content type
3. Generate your first piece of content
4. Copy and use in your marketing

**For Developers:**
1. Test each API endpoint
2. Monitor logs for errors
3. Track API usage patterns
4. Plan for scaling

**For Admins:**
1. Monitor database growth
2. Track monthly API costs
3. Set up alerts for high usage
4. Plan tier limit adjustments

---

**Questions?** Check the full documentation or the GitHub repository.

**Last Updated:** December 22, 2025  
**Status:** ✅ Production Ready
