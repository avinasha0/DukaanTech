<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Contact Form Submission</title>
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
            background: linear-gradient(135deg, #6E46AE, #00B6B4);
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }
        .content {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 0 0 8px 8px;
        }
        .field {
            margin-bottom: 15px;
        }
        .field-label {
            font-weight: bold;
            color: #6E46AE;
            display: inline-block;
            width: 120px;
        }
        .field-value {
            color: #333;
        }
        .message-box {
            background: white;
            padding: 15px;
            border-left: 4px solid #00B6B4;
            margin: 15px 0;
            border-radius: 4px;
        }
        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>New Contact Form Submission</h1>
        <p>DukaanTech</p>
    </div>
    
    <div class="content">
        <div class="field">
            <span class="field-label">Name:</span>
            <span class="field-value">{{ $firstName ?? '' }} {{ $lastName ?? '' }}</span>
        </div>
        
        <div class="field">
            <span class="field-label">Email:</span>
            <span class="field-value">{{ $email ?? '' }}</span>
        </div>
        
        @if(!empty($phone))
        <div class="field">
            <span class="field-label">Phone:</span>
            <span class="field-value">{{ $phone ?? '' }}</span>
        </div>
        @endif
        
        @if(!empty($company))
        <div class="field">
            <span class="field-label">Company:</span>
            <span class="field-value">{{ $company ?? '' }}</span>
        </div>
        @endif
        
        <div class="field">
            <span class="field-label">Subject:</span>
            <span class="field-value">{{ ucfirst($subject ?? '') }}</span>
        </div>
        
        <div class="message-box">
            <strong>Message:</strong><br>
            {{ $message ?? '' }}
        </div>
        
        <div class="footer">
            <p><strong>Submission Details:</strong></p>
            <p>IP Address: {{ $ip ?? 'Unknown' }}</p>
            <p>User Agent: {{ $userAgent ?? 'Unknown' }}</p>
            <p>Timestamp: {{ $timestamp ?? now()->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
