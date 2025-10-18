<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Activate Your Account - POS SaaS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #f97316 0%, #dc2626 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #f97316 0%, #dc2626 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background: linear-gradient(135deg, #ea580c 0%, #b91c1c 100%);
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to Dukaantech POS!</h1>
        <p>Activate your account to get started</p>
    </div>
    
    <div class="content">
        <h2>Hello {{ $user->name }},</h2>
        
        <p>Thank you for registering with Dukaantech POS! To complete your registration and activate your account, please click the button below:</p>
        
        <div style="text-align: center;">
            <a href="{{ $activationUrl }}" class="button">Activate Account</a>
        </div>
        
        <p>If the button doesn't work, you can copy and paste this link into your browser:</p>
        <p style="word-break: break-all; background: #e9ecef; padding: 10px; border-radius: 5px;">
            {{ $activationUrl }}
        </p>
        
        <p><strong>What happens next?</strong></p>
        <ul>
            <li>Click the activation link above</li>
            <li>Set up your organization and first outlet</li>
            <li>Start using your POS system!</li>
        </ul>
        
        <p>If you didn't create an account with us, please ignore this email.</p>
        
        <p>Best regards,<br>The Dukaantech POS Team</p>
    </div>
    
    <div class="footer">
        <p>This email was sent to {{ $user->email }}</p>
        <p>© {{ date('Y') }} Dukaantech POS. All rights reserved.</p>
    </div>
</body>
</html>