# Production Deployment Guide

## 🚨 **URGENT: Contact Form Not Working on Production**

The production server at `dukaantech.com` is still using the old ContactController code that has the Blade template error.

## 🔧 **Quick Fix Options:**

### Option 1: Deploy from GitHub (Recommended)
```bash
# On production server
cd /home/u248666255/domains/dukaantech.com/public_html
git pull origin master
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Option 2: Manual Fix on Production Server
If you can't deploy from GitHub, manually update the ContactController:

1. **Edit:** `/home/u248666255/domains/dukaantech.com/public_html/app/Http/Controllers/ContactController.php`

2. **Replace the email sending section** (around line 80-144) with:
```php
// Send email with proper error handling using raw HTML
$htmlContent = "
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>New Contact Form Submission</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #6E46AE, #00B6B4); color: white; padding: 20px; border-radius: 8px 8px 0 0; text-align: center; }
        .content { background: #f9f9f9; padding: 20px; border-radius: 0 0 8px 8px; }
        .field { margin-bottom: 15px; }
        .field-label { font-weight: bold; color: #6E46AE; display: inline-block; width: 120px; }
        .field-value { color: #333; }
        .message-box { background: white; padding: 15px; border-left: 4px solid #00B6B4; margin: 15px 0; border-radius: 4px; }
        .footer { margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class='header'>
        <h1>New Contact Form Submission</h1>
        <p>DukaanTech</p>
    </div>
    <div class='content'>
        <div class='field'>
            <span class='field-label'>Name:</span>
            <span class='field-value'>{$emailData['firstName']} {$emailData['lastName']}</span>
        </div>
        <div class='field'>
            <span class='field-label'>Email:</span>
            <span class='field-value'>{$emailData['email']}</span>
        </div>" . 
        (!empty($emailData['phone']) ? "
        <div class='field'>
            <span class='field-label'>Phone:</span>
            <span class='field-value'>{$emailData['phone']}</span>
        </div>" : "") . 
        (!empty($emailData['company']) ? "
        <div class='field'>
            <span class='field-label'>Company:</span>
            <span class='field-value'>{$emailData['company']}</span>
        </div>" : "") . "
        <div class='field'>
            <span class='field-label'>Subject:</span>
            <span class='field-value'>" . ucfirst($emailData['subject']) . "</span>
        </div>
        <div class='message-box'>
            <strong>Message:</strong><br>
            {$emailData['message']}
        </div>
        <div class='footer'>
            <p><strong>Submission Details:</strong></p>
            <p>IP Address: {$emailData['ip']}</p>
            <p>User Agent: {$emailData['userAgent']}</p>
            <p>Timestamp: {$emailData['timestamp']}</p>
        </div>
    </div>
</body>
</html>";

Mail::html($htmlContent, function ($message) use ($emailData, $contactEmail) {
    $message->to($contactEmail)
            ->subject('Dukaantech - New Contact Form Submission: ' . ($emailData['subject'] ?? 'No Subject'))
            ->replyTo($emailData['email'] ?? 'noreply@example.com', ($emailData['firstName'] ?? '') . ' ' . ($emailData['lastName'] ?? ''));
});
```

3. **Clear caches:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## 📧 **Email Configuration Check**

Make sure your production server has the correct email configuration in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=n0_reply@talentlit.com
MAIL_PASSWORD=Avinash!08!08
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=n0_reply@talentlit.com
MAIL_FROM_NAME="TalentLit HRMS"
MAIL_CONTACT_EMAIL=aavi10111@gmail.com
```

## 🧪 **Test After Fix**

1. Go to `https://dukaantech.com/contact-us`
2. Fill out the form
3. Submit it
4. Check if you receive email at `aavi10111@gmail.com`

## ⚠️ **Current Status**

- ✅ **Local Development:** Working perfectly
- ✅ **GitHub Repository:** Updated with fixes
- ❌ **Production Server:** Still has old code with errors

The contact form will work once the production server is updated with the latest code!
