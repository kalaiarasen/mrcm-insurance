<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claim Status Update</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    @php
        $headerColor = match($claim->status) {
            'approved' => '#28a745',
            'rejected' => '#dc3545',
            'closed' => '#6c757d',
            default => '#ffc107'
        };
        $statusTitle = match($claim->status) {
            'approved' => 'Claim Approved',
            'rejected' => 'Claim Rejected',
            'closed' => 'Claim Closed',
            default => 'Claim Status Updated'
        };
    @endphp
    
    <div style="background-color: {{ $headerColor }}; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0;">
        <h1 style="margin: 0; font-size: 24px;">🔔 {{ $statusTitle }}</h1>
    </div>
    
    <div style="background-color: #f8f9fa; padding: 30px; border: 1px solid #dee2e6; border-top: none; border-radius: 0 0 5px 5px;">
        <p style="font-size: 16px; margin-bottom: 20px;">Dear {{ $claim->user->name }},</p>
        
        <p style="font-size: 16px; margin-bottom: 20px;">
            Your claim status has been updated.
        </p>
        
        <div style="background-color: white; padding: 20px; border-radius: 5px; margin: 20px 0; border-left: 4px solid {{ $headerColor }};">
            <h3 style="margin-top: 0; color: {{ $headerColor }};">Claim Details</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6;"><strong>Claim ID:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6; text-align: right;">#{{ $claim->id }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6;"><strong>Claim Title:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6; text-align: right;">{{ $claim->claim_title }}</td>
                </tr>
                @if($claim->policyApplication)
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6;"><strong>Policy Reference:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6; text-align: right;">{{ $claim->policyApplication->reference_number }}</td>
                </tr>
                @endif
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6;"><strong>Incident Date:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6; text-align: right;">{{ $claim->incident_date->format('d M Y') }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6;"><strong>Current Status:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6; text-align: right;">
                        <span style="color: {{ $headerColor }}; font-weight: bold;">{{ ucfirst($claim->status) }}</span>
                    </td>
                </tr>
                @if($claim->claim_amount)
                <tr>
                    <td style="padding: 8px 0;"><strong>Claim Amount:</strong></td>
                    <td style="padding: 8px 0; text-align: right; font-weight: bold;">RM {{ number_format($claim->claim_amount, 2) }}</td>
                </tr>
                @endif
            </table>
        </div>
        
        @if($claim->status === 'approved')
        <div style="background-color: #d4edda; border: 1px solid #28a745; border-radius: 5px; padding: 15px; margin: 20px 0;">
            <p style="margin: 0; font-size: 16px; color: #155724;">
                <strong>✅ Good News!</strong><br>
                Your claim has been approved. The approved amount will be processed according to your policy terms.
            </p>
        </div>
        @elseif($claim->status === 'rejected')
        <div style="background-color: #f8d7da; border: 1px solid #dc3545; border-radius: 5px; padding: 15px; margin: 20px 0;">
            <p style="margin: 0; font-size: 16px; color: #721c24;">
                <strong>❌ Claim Rejected</strong><br>
                Unfortunately, your claim has been rejected. Please contact our support team for more details.
            </p>
        </div>
        @elseif($claim->status === 'closed')
        <div style="background-color: #e2e3e5; border: 1px solid #6c757d; border-radius: 5px; padding: 15px; margin: 20px 0;">
            <p style="margin: 0; font-size: 16px; color: #383d41;">
                <strong>🔒 Claim Closed</strong><br>
                This claim has been closed. No further action is required.
            </p>
        </div>
        @endif
        
        @if($claim->admin_notes)
        <div style="background-color: white; padding: 15px; border-radius: 5px; margin: 20px 0; border: 1px solid #dee2e6;">
            <h4 style="margin-top: 0; color: #495057;">Admin Notes:</h4>
            <p style="margin: 0; font-size: 14px; color: #6c757d;">{{ $claim->admin_notes }}</p>
        </div>
        @endif
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('claims.show', $claim->id) }}" style="background-color: #007bff; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">
                View Claim Details
            </a>
        </div>
        
        <p style="font-size: 14px; color: #6c757d; margin-top: 30px;">
            If you have any questions about your claim, please contact our claims department.
        </p>
        
        <p style="font-size: 16px; margin-top: 30px;">
            Best regards,<br>
            <strong>MRCM Insurance Claims Team</strong>
        </p>
    </div>
    
    <div style="text-align: center; padding: 20px; font-size: 12px; color: #6c757d;">
        <p>This is an automated email. Please do not reply to this message.</p>
        <p>&copy; {{ date('Y') }} MRCM Insurance. All rights reserved.</p>
    </div>
</body>
</html>
