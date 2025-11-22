<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #007bff;
            margin-bottom: 20px;
        }
        .details {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            padding: 15px;
            margin: 20px 0;
        }
        .details table {
            width: 100%;
            border-collapse: collapse;
        }
        .details table td {
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .details table td:first-child {
            font-weight: bold;
            width: 40%;
            color: #495057;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="margin: 0; color: #007bff;">GEGM Professional Indemnity Insurance Application</h2>
        </div>

        <p>Dear Underwriting Department,</p>

        <p>Please find the attachment for the Application <strong>{{ $policyApplication->reference_number ?? 'MRCM#' . $policyApplication->id }}</strong> 
            <strong>{{ strtoupper($policyApplication->user->name ?? 'N/A') }}</strong></p>

        <div class="details">
            <table>
                <tr>
                    <td>Name:</td>
                    <td>{{ strtoupper($policyApplication->user->name ?? 'N/A') }}</td>
                </tr>
                <tr>
                    <td>NRIC No:</td>
                    <td>{{ $profile && $profile->nric_number ? $profile->nric_number : ($profile && $profile->passport_number ? $profile->passport_number : 'N/A') }}</td>
                </tr>
                <tr>
                    <td>Period:</td>
                    <td>
                        @if($pricing && $pricing->policy_start_date && $pricing->policy_expiry_date)
                            {{ \Carbon\Carbon::parse($pricing->policy_start_date)->format('d-M-Y') }} to {{ \Carbon\Carbon::parse($pricing->policy_expiry_date)->format('d-M-Y') }}
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Cover Type:</td>
                    <td>Professional Indemnity - {{ $healthcare ? ucfirst(str_replace('_', ' ', $healthcare->professional_indemnity_type)) : 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Medical Status:</td>
                    <td>{{ $healthcare ? ucfirst(str_replace('_', ' ', $healthcare->professional_indemnity_type)) : 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Class:</td>
                    <td>{{ $healthcare && $healthcare->cover_type ? ucfirst(str_replace('_', ' ', $healthcare->cover_type)) : 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Liability Limit:</td>
                    <td>RM{{ number_format($pricing && $pricing->liability_limit ? $pricing->liability_limit : 0, 0) }}</td>
                </tr>
                <tr>
                    <td>Premium:</td>
                    <td>RM{{ number_format($pricing && $pricing->total_payable ? $pricing->total_payable : 0, 0) }}</td>
                </tr>
            </table>
        </div>

        <p>Please review the attached proposal form and proceed with the underwriting process.</p>

        <p>Should you require any additional information or clarification, please do not hesitate to contact us.</p>

        <p>Best regards,<br>
        <strong>MRCM Insurance Team</strong></p>

        <div class="footer">
            <p>This is an automated email. Please do not reply directly to this email.</p>
            <p>Malaysian Retired Consultants & Medics (MRCM)<br>
            Email: insurance@mrcm.com.my</p>
        </div>
    </div>
</body>
</html>
