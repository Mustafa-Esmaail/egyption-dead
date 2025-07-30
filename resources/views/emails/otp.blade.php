<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Verification Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .otp-box {
            background-color: #f8f9fa;
            border: 2px solid #4CAF50;
            border-radius: 5px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #4CAF50;
            letter-spacing: 5px;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #666666;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            padding: 10px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Password Reset Verification</h1>
        </div>
        <div class="content">
            <p>Dear {{ $user->first_name }},</p>

            <p>We received a request to reset your password. To proceed with the password reset, please use the following verification code:</p>

            <div class="otp-box">
                <div class="otp-code">{{ $otp }}</div>
            </div>

            <div class="warning">
                <strong>Important:</strong> This verification code will expire in 10 minutes. If you did not request this password reset, please ignore this email and ensure your account is secure.
            </div>

            <p>For security reasons:</p>
            <ul>
                <li>Do not share this code with anyone</li>
                <li>Our team will never ask for this code</li>
                <li>This code can only be used once</li>
            </ul>

            <p>If you need any assistance, please contact our support team.</p>

            <p>Best regards,<br>
            <strong>{{config('app.name')}}</strong></p>
        </div>
        <div class="footer">
            <p>This is an automated message, please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} {{config('app.name')}} All rights reserved.</p>
        </div>
    </div>
</body>
</html>
