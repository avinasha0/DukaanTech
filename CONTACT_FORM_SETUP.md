# Contact Form Setup Guide

## ✅ **Fixed Issues:**

1. **reCAPTCHA Error Fixed** - The "No reCAPTCHA clients exist" error has been resolved
2. **Email Configuration** - Enhanced error handling and logging for email issues
3. **Optional reCAPTCHA** - Form works with or without reCAPTCHA configuration

## 🔧 **Setup Instructions:**

### 1. **Create `.env` file** in your project root:

```env
# Basic App Configuration
APP_NAME="TalentLit POS"
APP_ENV=local
APP_KEY=base64:your-app-key-here
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# Mail Configuration (REQUIRED for email functionality)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"

# Contact Form Email (where contact form emails will be sent)
MAIL_CONTACT_EMAIL=info@dukaantech.com

# reCAPTCHA Configuration (OPTIONAL - form works without it)
RECAPTCHA_SITE_KEY=your_site_key_here
RECAPTCHA_SECRET_KEY=your_secret_key_here
```

### 2. **For Gmail SMTP Setup:**

1. **Enable 2-Factor Authentication** on your Google account
2. **Generate App Password:**
   - Go to Google Account → Security → 2-Step Verification → App passwords
   - Select "Mail" and generate a password
   - Use this password (not your regular Gmail password) as `MAIL_PASSWORD`

### 3. **For reCAPTCHA Setup (Optional):**

1. Go to [Google reCAPTCHA Admin Console](https://www.google.com/recaptcha/admin)
2. Create a new site with reCAPTCHA v2
3. Add `localhost` to your domains
4. Copy the Site Key and Secret Key to your `.env` file

### 4. **Test the Setup:**

Run the test script to verify email configuration:
```bash
php test-email.php
```

## 🚀 **How It Works Now:**

### **Without reCAPTCHA:**
- Contact form works normally
- No reCAPTCHA widget is shown
- Form validation works for all other fields

### **With reCAPTCHA:**
- reCAPTCHA widget appears
- Form validates reCAPTCHA before submission
- Better spam protection

### **Email Sending:**
- Sends to `MAIL_CONTACT_EMAIL` address
- Detailed error logging in `storage/logs/laravel.log`
- Clear error messages for users

## 🔍 **Troubleshooting:**

### **reCAPTCHA Issues:**
- If you see "No reCAPTCHA clients exist" error, it means reCAPTCHA is not properly configured
- The form will work without reCAPTCHA - it's optional
- Check browser console for detailed error messages

### **Email Issues:**
- Check `storage/logs/laravel.log` for detailed error messages
- Verify SMTP credentials in `.env` file
- Test with `php test-email.php` script

### **Common SMTP Issues:**
- **Gmail:** Use App Password, not regular password
- **Port 587:** Most reliable for Gmail
- **TLS Encryption:** Required for Gmail
- **Firewall:** Ensure port 587 is not blocked

## 📧 **Alternative SMTP Providers:**

### **Mailgun:**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=your-mailgun-username
MAIL_PASSWORD=your-mailgun-password
MAIL_ENCRYPTION=tls
```

### **SendGrid:**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
```

## ✅ **Current Status:**

- ✅ reCAPTCHA error fixed
- ✅ Email configuration enhanced
- ✅ Error handling improved
- ✅ Form works with or without reCAPTCHA
- ✅ Detailed logging added
- ✅ Test script provided

The contact form at `http://localhost:8000/contact-us` should now work properly!
