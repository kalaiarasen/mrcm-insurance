<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation Status Update</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    @php
        $headerColor = match($status) {
            'quote' => '#17a2b8',
            'active' => '#28a745',
            'declined' => '#dc3545',
            default => '#6c757d'
        };
        $statusTitle = match($status) {
            'quote' => 'Quote Provided',
            'declined' => 'Application Declined',
            'active' => 'Policy Activated',
            default => 'Status Updated'
        };
    @endphp
    
    <div style="background-color: {{ $headerColor }}; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0;">
        <h1 style="margin: 0; font-size: 24px;">📋 {{ $statusTitle }}</h1>
    </div>
    
    <div style="background-color: #f8f9fa; padding: 30px; border: 1px solid #dee2e6; border-top: none; border-radius: 0 0 5px 5px;">
        <p style="font-size: 16px; margin-bottom: 20px;">Dear {{ $quotation->user->name }},</p>
        
        <p style="font-size: 16px; margin-bottom: 20px;">
            Your quotation request for <strong>{{ $quotation->product->title }}</strong> has been updated.
        </p>
        
        <div style="background-color: white; padding: 20px; border-radius: 5px; margin: 20px 0; border-left: 4px solid {{ $headerColor }};">
            <h3 style="margin-top: 0; color: {{ $headerColor }};">Quotation Details</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6;"><strong>Product:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6; text-align: right;">{{ $quotation->product->title }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6;"><strong>Status:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6; text-align: right;">
                        <span style="color: {{ $headerColor }}; font-weight: bold;">{{ ucfirst($status) }}</span>
                    </td>
                </tr>
                @if($quotation->quoted_price)
                <tr>
                    <td style="padding: 8px 0;"><strong>Quoted Price:</strong></td>
                    <td style="padding: 8px 0; text-align: right; font-weight: bold;">RM {{ number_format($quotation->quoted_price, 2) }}</td>
                </tr>
                @endif
            </table>
        </div>
        
        @if($status === 'quote')
        <p style="font-size: 16px; margin-bottom: 20px;">
            We have provided a quote for your request. Please review the details and proceed with payment if you wish to activate the policy.
        </p>
        @elseif($status === 'active')
        <p style="font-size: 16px; margin-bottom: 20px;">
            Congratulations! Your policy is now active. You can view your policy details in your dashboard.
        </p>
        @elseif($status === 'declined')
        <p style="font-size: 16px; margin-bottom: 20px;">
            Unfortunately, we are unable to proceed with your quotation request at this time. Please contact us for more information.
        </p>
        @endif
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('customer.quotations.show', $quotation->id) }}" style="background-color: #007bff; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">
                View Quotation
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
