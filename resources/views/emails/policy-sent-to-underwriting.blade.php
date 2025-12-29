<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Submitted to Underwriting</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .email-body {
            padding: 30px 25px;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
            color: #2d3748;
        }
        .content {
            font-size: 15px;
            color: #4a5568;
            margin-bottom: 15px;
        }
        .info-box {
            background-color: #f7fafc;
            border-left: 4px solid #17a2b8;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box p {
            margin: 5px 0;
            font-size: 14px;
        }
        .info-box strong {
            color: #2d3748;
        }
        .email-footer {
            background-color: #f7fafc;
            padding: 20px 25px;
            border-top: 1px solid #e2e8f0;
            font-size: 13px;
            color: #718096;
        }
        .signature {
            margin-top: 25px;
            font-size: 14px;
            color: #4a5568;
        }
        .company-name {
            font-weight: 600;
            color: #17a2b8;
        }
        .timeline-box {
            background-color: #d1ecf1;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            border: 1px solid #bee5eb;
        }
        .timeline-box strong {
            color: #0c5460;
            font-size: 16px;
        }
        .timeline-box p {
            margin: 10px 0 0 0;
            font-size: 14px;
            color: #0c5460;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Application Submitted to Underwriting</h1>
        </div>
        
        <div class="email-body">
            <div class="greeting">
                Dear {{ $clientName }},
            </div>
            
            <div class="content">
                We are pleased to inform you that your application for <strong>Indemnity Insurance</strong> has been successfully processed and submitted to the underwriting department.
            </div>
            
            @if($policyApplication->reference_number)
            <div class="info-box">
                <p><strong>Reference Number:</strong> {{ $policyApplication->reference_number }}</p>
                <p><strong>Submitted to UW:</strong> {{ $policyApplication->sent_to_underwriter_at ? $policyApplication->sent_to_underwriter_at->format('d M Y, h:i A') : now()->format('d M Y, h:i A') }}</p>
            </div>
            @endif
            
            <div class="timeline-box">
                <strong>Processing Timeline</strong>
                <p>
                    Please note that the policy issuance will take up to <strong>five (5) working days</strong>. 
                    We kindly request your patience during this period.
                </p>
            </div>
            
            <div class="content">
                Should you require any assistance in the meantime, please do not hesitate to contact us.
            </div>
            
            <div class="content">
                Thank you.
            </div>
            
            <div class="signature">
                <strong>Kind regards,</strong><br>
                Suresh<br>
                <span class="company-name">MRCM Services (M) Sdn. Bhd.</span>
            </div>
        </div>
        
        <div class="email-footer">
            <p>This is an automated email from MRCM Insurance Portal. Please do not reply to this email.</p>
            <p>If you have any questions, please contact us through our portal.</p>
        </div>
    </div>
</body>
</html>
