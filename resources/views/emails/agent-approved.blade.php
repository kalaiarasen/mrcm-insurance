<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Application Approved</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #28a745; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0;">
        <h1 style="margin: 0; font-size: 24px;">✅ Agent Application Approved!</h1>
    </div>
    
    <div style="background-color: #f8f9fa; padding: 30px; border: 1px solid #dee2e6; border-top: none; border-radius: 0 0 5px 5px;">
        <p style="font-size: 16px; margin-bottom: 20px;">Dear {{ $agent->name }},</p>
        
        <p style="font-size: 16px; margin-bottom: 20px;">
            Congratulations! Your agent application has been <strong style="color: #28a745;">approved</strong>.
        </p>
        
        <div style="background-color: white; padding: 20px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #28a745;">
            <h3 style="margin-top: 0; color: #28a745;">Commission Details</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6;"><strong>Agent Name:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6; text-align: right;">{{ $agent->name }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6;"><strong>Email:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #dee2e6; text-align: right;">{{ $agent->email }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0;"><strong>Commission Rate:</strong></td>
                    <td style="padding: 8px 0; text-align: right; color: #28a745; font-weight: bold; font-size: 18px;">{{ number_format($commissionPercentage, 2) }}%</td>
                </tr>
            </table>
        </div>
        
        <p style="font-size: 16px; margin-bottom: 20px;">
            You can now start referring clients to MRCM Insurance and earn <strong>{{ number_format($commissionPercentage, 2) }}%</strong> commission on all successful policy applications.
        </p>
        
        <div style="background-color: #fff3cd; border: 1px solid #ffc107; border-radius: 5px; padding: 15px; margin: 20px 0;">
            <h4 style="margin-top: 0; color: #856404;">📝 Next Steps:</h4>
            <ul style="margin: 0; padding-left: 20px;">
                <li style="margin-bottom: 8px;">Login to your agent dashboard</li>
                <li style="margin-bottom: 8px;">Share your unique referral code with clients</li>
                <li style="margin-bottom: 8px;">Track your commissions in real-time</li>
                <li style="margin-bottom: 8px;">Receive payments when policies are activated</li>
            </ul>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('login') }}" style="background-color: #007bff; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">
                Login to Agent Dashboard
            </a>
        </div>
        
        <p style="font-size: 14px; color: #6c757d; margin-top: 30px;">
            If you have any questions about being an agent, please contact our support team.
        </p>
        
        <p style="font-size: 16px; margin-top: 30px;">
            Welcome to the team!<br>
            <strong>MRCM Insurance Team</strong>
        </p>
    </div>
    
    <div style="text-align: center; padding: 20px; font-size: 12px; color: #6c757d;">
        <p>This is an automated email. Please do not reply to this message.</p>
        <p>&copy; {{ date('Y') }} MRCM Insurance. All rights reserved.</p>
    </div>
</body>
</html>
