<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wallet Credit Added</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #28a745; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0;">
        <h1 style="margin: 0; font-size: 24px;">💰 Wallet Credit Added</h1>
    </div>
    
    <div style="background-color: #f8f9fa; padding: 30px; border: 1px solid #dee2e6; border-top: none; border-radius: 0 0 5px 5px;">
        <p style="font-size: 16px; margin-bottom: 20px;">Dear {{ $user->name }},</p>
        
        <p style="font-size: 16px; margin-bottom: 20px;">
            Good news! Your wallet has been credited with <strong style="color: #28a745;">RM {{ number_format($amount, 2) }}</strong>.
        </p>
        
        <div style="background-color: white; padding: 20px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #28a745;">
            <h3 style="margin-top: 0; color: #28a745;">Wallet Summary</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6;"><strong>Amount Added:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6; text-align: right;">RM {{ number_format($amount, 2) }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0;"><strong>New Balance:</strong></td>
                    <td style="padding: 8px 0; text-align: right; color: #28a745; font-weight: bold;">RM {{ number_format($newBalance, 2) }}</td>
                </tr>
            </table>
        </div>
        
        <p style="font-size: 16px; margin-bottom: 20px;">
            You can use this wallet balance to pay for your insurance policies and other products.
        </p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('dashboard') }}" style="background-color: #007bff; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">
                View Dashboard
            </a>
        </div>
        
        <p style="font-size: 14px; color: #6c757d; margin-top: 30px;">
            If you have any questions, please contact our support team.
        </p>
        
        <p style="font-size: 16px; margin-top: 30px;">
            Best regards,<br>
            <strong>MRCM Insurance Team</strong>
        </p>
    </div>
    
    <div style="text-align: center; padding: 20px; font-size: 12px; color: #6c757d;">
        <p>This is an automated email. Please do not reply to this message.</p>
        <p>&copy; {{ date('Y') }} MRCM Insurance. All rights reserved.</p>
    </div>
</body>
</html>
