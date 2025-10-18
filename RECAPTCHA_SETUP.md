# reCAPTCHA Setup Instructions

## Overview
The contact form at `http://localhost:8000/contact-us` now includes Google reCAPTCHA v2 protection to prevent spam submissions.

## Setup Steps

### 1. Get reCAPTCHA Keys
1. Go to [Google reCAPTCHA Admin Console](https://www.google.com/recaptcha/admin)
2. Click "Create" to add a new site
3. Choose reCAPTCHA v2 ("I'm not a robot" Checkbox)
4. Add your domain: `localhost` (for development) and your production domain
5. Accept the Terms of Service and submit
6. Copy the **Site Key** and **Secret Key**

### 2. Configure Environment Variables
Add these variables to your `.env` file:

```env
# Google reCAPTCHA Configuration
RECAPTCHA_SITE_KEY=your_site_key_here
RECAPTCHA_SECRET_KEY=your_secret_key_here

# Contact form email (optional)
MAIL_CONTACT_EMAIL=info@dukaantech.com
```

### 3. Test the Implementation
1. Visit `http://localhost:8000/contact-us`
2. Fill out the contact form
3. Complete the reCAPTCHA verification
4. Submit the form
5. Check that you receive the success message

## Features Implemented

- ✅ Google reCAPTCHA v2 integration
- ✅ Form validation with error handling
- ✅ Success/error message display
- ✅ Loading state during submission
- ✅ Email notification system
- ✅ Form data persistence on validation errors
- ✅ Responsive design with TalentLit branding

## Files Modified/Created

- `app/Http/Controllers/ContactController.php` - Form handling logic
- `resources/views/pages/contact-us.blade.php` - Updated form with reCAPTCHA
- `resources/views/emails/contact-form.blade.php` - Email template
- `config/services.php` - reCAPTCHA configuration
- `routes/web.php` - Added POST route for form submission

## Troubleshooting

### reCAPTCHA Not Loading
- Ensure the site key is correctly set in `.env`
- Check that the domain is registered in Google reCAPTCHA console
- Verify internet connection (reCAPTCHA requires external API calls)

### Form Not Submitting
- Check that all required fields are filled
- Ensure reCAPTCHA is completed
- Check Laravel logs for any errors
- Verify email configuration if using email notifications

### Email Not Sending
- Configure your mail settings in `.env`
- Check Laravel logs for mail errors
- Ensure `MAIL_CONTACT_EMAIL` is set to a valid email address
