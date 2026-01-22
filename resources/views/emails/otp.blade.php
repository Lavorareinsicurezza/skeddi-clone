<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Reset OTP</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f9f9f9; padding:20px;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table width="600" style="background:#ffffff; padding:20px; border-radius:6px;">
                    <tr>
                        <td>
                            <h2 style="color:#0C3183; margin-bottom:10px;">
                                Password Reset Request
                            </h2>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:14px; color:#333;">
                            <p>Dear {{ $user->name }},</p>
                            <p>A password reset has been requested for your account. Please use the following OTP (One-Time Password) to complete the process:</p>
                            <div style="background-color: #f3f4f6; padding: 15px; text-align: center; border-radius: 4px; margin: 20px 0;">
                                <span style="font-size: 24px; font-weight: bold; letter-spacing: 5px; color: #0C3183;">{{ $otp }}</span>
                            </div>
                            <p>This OTP is valid for 10 minutes.</p>
                            <p>If you did not request this, please contact your administrator immediately.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:14px; color:#333;">
                            <p style="margin-top:20px;">
                                Best regards,<br>
                                <strong>{{ config('app.name') }}</strong>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>