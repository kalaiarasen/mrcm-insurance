<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commission Payment Received</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #17a2b8; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0;">
        <h1 style="margin: 0; font-size: 24px;">💵 Commission Payment Received</h1>
    </div>
    
    <div style="background-color: #f8f9fa; padding: 30px; border: 1px solid #dee2e6; border-top: none; border-radius: 0 0 5px 5px;">
        <p style="font-size: 16px; margin-bottom: 20px;">Dear {{ $payment->agent->name }},</p>
        
        <p style="font-size: 16px; margin-bottom: 20px;">
            Great news! Your commission payment has been processed successfully.
        </p>
        
        <div style="background-color: white; padding: 20px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #17a2b8;">
            <h3 style="margin-top: 0; color: #17a2b8;">Payment Details</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6;"><strong>Payment Amount:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6; text-align: right; color: #28a745; font-weight: bold; font-size: 18px;">
                        RM {{ number_format($payment->amount, 2) }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6;"><strong>Payment Date:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6; text-align: right;">
                        {{ $payment->payment_date->format('d M Y') }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6;"><strong>Payment Method:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6; text-align: right;">
                        {{ ucfirst($payment->payment_method) }}
                    </td>
                </tr>
                @if($payment->reference_number)
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6;"><strong>Reference Number:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6; text-align: right;">
                        {{ $payment->reference_number }}
                    </td>
                </tr>
                @endif
                @if($payment->notes)
                <tr>
                    <td colspan="2" style="padding: 8px 0;"><strong>Notes:</strong><br>{{ $payment->notes }}</td>
                </tr>
                @endif
            </table>
        </div>
        
        <p style="font-size: 16px; margin-bottom: 20px;">
            The payment has been processed and should reflect in your account shortly.
        </p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('agent.commissions') }}" style="background-color: #007bff; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">
                View Commission History
            </a>
        </div>
        
        <div style="background-color: #e7f3ff; border: 1px solid #007bff; border-radius: 5px; padding: 15px; margin: 20px 0;">
            <p style="margin: 0; font-size: 14px;">
                <strong>📌 Tip:</strong> You can view all your commission payments and history in your agent dashboard.
            </p>
        </div>
        
        <p style="font-size: 14px; color: #6c757d; margin-top: 30px;">
            If you have any questions about this payment, please contact our finance team.
        </p>
        
        <p style="font-size: 16px; margin-top: 30px;">
            Thank you for your continued partnership!<br>
            <strong>MRCM Insurance Team</strong>
        </p>
    </div>
    
    <div style="text-align: center; padding: 20px; font-size: 12px; color: #6c757d;">
        <p>This is an automated email. Please do not reply to this message.</p>
        <p>&copy; {{ date('Y') }} MRCM Insurance. All rights reserved.</p>
    </div>
</body>
</html>
