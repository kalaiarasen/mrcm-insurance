<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Insurance Issued</title>
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
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
            border-left: 4px solid #28a745;
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
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: 600;
            text-align: center;
        }
        .button:hover {
            opacity: 0.9;
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
            color: #28a745;
        }
        .success-box {
            background-color: #d4edda;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
            border: 1px solid #c3e6cb;
            text-align: center;
        }
        .success-box h2 {
            color: #155724;
            margin: 0 0 10px 0;
            font-size: 20px;
        }
        .success-box p {
            margin: 5px 0;
            font-size: 14px;
            color: #155724;
        }
        .notice-box {
            background-color: #fff3cd;
            padding: 12px;
            border-radius: 4px;
            margin: 15px 0;
            border: 1px solid #ffeeba;
            font-size: 13px;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Certificate of Insurance Issued</h1>
        </div>
        
        <div class="email-body">
            <div class="greeting">
                Dear {{ $clientName }},
            </div>
            
            <div class="content">
                We are pleased to inform you that your <strong>Certificate of Insurance (CI)</strong> has been issued and is now <strong>active</strong>.
            </div>
            
            @if($policyApplication->reference_number)
            <div class="info-box">
                <p><strong>Policy Reference:</strong> {{ $policyApplication->reference_number }}</p>
                <p><strong>Activated Date:</strong> {{ $policyApplication->activated_at ? $policyApplication->activated_at->format('d M Y, h:i A') : now()->format('d M Y, h:i A') }}</p>
                <p><strong>Status:</strong> <span style="color: #28a745; font-weight: 600;">ACTIVE</span></p>
            </div>
            @endif
            
            <div class="success-box">
                <h2>Your Certificate is Ready!</h2>
                <p>You may download the CI at any time via our portal.</p>
            </div>
            
            @if($certificateUrl)
            <div style="text-align: center;">
                <a href="{{ $certificateUrl }}" class="button" target="_blank">Download Certificate</a>
            </div>
            @endif
            
            <div style="text-align: center; margin-top: 10px;">
                <a href="{{ $portalUrl }}" class="button">View on Portal</a>
            </div>
            
            <div class="notice-box">
                <strong>Important Notice:</strong><br>
                Please note that the full policy schedule and tax receipt will be updated within <strong>30 days</strong>.
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
