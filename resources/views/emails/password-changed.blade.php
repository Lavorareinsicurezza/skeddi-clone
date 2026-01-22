<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Changed</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f9f9f9; padding:20px;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table width="600" style="background:#ffffff; padding:20px; border-radius:6px;">
                    <tr>
                        <td>
                            <h2 style="color:#0C3183; margin-bottom:10px;">
                                Password Changed Successfully
                            </h2>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:14px; color:#333;">
                            <p>Dear {{ $user->name }},</p>
                            <p>Your password has been successfully changed.</p>
                            <p>If you did not perform this action, please contact your administrator immediately.</p>
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