<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
</head>

<body
    style="margin: 0; padding: 0; background-color: #f0f2f5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%"
        style="max-width: 600px; margin: 30px auto;">
        <tr>
            <td
                style="background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15); padding: 40px 20px;">
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td style="text-align: center; padding-bottom: 20px;">
                            <h1 style="color: #8338EB; font-size: 28px; font-weight: 600; margin: 0;">Reset Your
                                Password</h1>
                            <p style="color: #666666; font-size: 16px; margin: 10px 0 0;">Let's get you back into your
                                account!</p>
                        </td>
                    </tr>
                </table>
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td style="color: #333333; font-size: 16px; line-height: 24px; padding: 0 20px;">
                            <p style="margin: 0 0 16px; font-weight: 500;">Salam,</p>
                            <p style="margin: 0 0 16px;">You requested a password reset. Click the button below to set a
                                new password and get back to exploring!</p>
                            <p style="text-align: center; margin: 24px 0;">
                                <a href="{{ $url }}"
                                    style="display: inline-block; background: #8338EB; color: #ffffff; padding: 14px 32px; text-decoration: none; border-radius: 8px; font-size: 16px; font-weight: 600; box-shadow: 0 2px 8px rgba(131, 56, 235, 0.3); transition: background 0.3s;">Reset
                                    Password</a>
                            </p>
                            <p style="margin: 0 0 16px;">If the button doesn't work, copy and paste this link into your
                                browser:</p>
                            <p style="margin: 0 0 16px; word-break: break-all;"><a href="{{ $url }}"
                                    style="color: #8338EB; text-decoration: none; font-weight: 500;">{{ $url }}</a></p>
                            <p style="margin: 0 0 16px;">Didn't request this? No worries, just ignore this email, and
                                your account will stay secure.</p>
                        </td>
                    </tr>
                </table>
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td
                            style="text-align: center; padding-top: 30px; border-top: 1px solid #e0e0e0; color: #666666; font-size: 14px; line-height: 20px;">
                            <p style="margin: 0;">© 2025 AssignMate. All rights reserved.</p>
                            <p style="margin: 8px 0 0;">
                                <a href="#" style="color: #8338EB; text-decoration: none; font-weight: 500;">Contact
                                    Us</a> |
                                <a href="#" style="color: #8338EB; text-decoration: none; font-weight: 500;">Privacy
                                    Policy</a>
                            </p>
                            <p style="margin: 8px 0 0; color: #999999;">Sent with peace from AssignMate</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
