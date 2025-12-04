<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Policy Application - {{ $policyApplication->reference_number ?? 'MRCM#' . $policyApplication->id }}</title>
    <style>
        @page {
            margin: 20px;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #000;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
        }

        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 14px;
            font-weight: bold;
        }

        .header h3 {
            margin: 5px 0;
            font-size: 12px;
        }

        .period-info {
            text-align: left;
            margin: 10px 0;
            font-size: 9px;
        }

        .section-title {
            background-color: #000;
            color: #fff;
            padding: 5px 10px;
            margin-top: 15px;
            margin-bottom: 10px;
            font-size: 11px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th {
            background-color: #f0f0f0;
            padding: 5px;
            text-align: left;
            font-weight: bold;
            font-size: 9px;
        }

        td {
            padding: 5px;
            font-size: 9px;
        }

        .label {
            font-weight: bold;
            width: 35%;
        }

        .sub-section {
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .declaration {
            margin-top: 20px;
            font-size: 8px;
            text-align: justify;
        }

        .declaration ol {
            padding-left: 20px;
        }

        .declaration li {
            margin-bottom: 5px;
        }

        .signature-section {
            margin-top: 30px;
        }

        .signature-box {
            border: 1px solid #000;
            height: 60px;
            margin-top: 10px;
        }

        .page-break {
            page-break-after: always;
        }

        .pricing-table td {
            text-align: right;
        }

        .pricing-table td:first-child {
            text-align: left;
        }

        .total-row {
            font-weight: bold;
            background-color: #f0f0f0;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <img src="{{ public_path('img/logo.png') }}" alt="MRCM Logo" class="logo">
        <h2>GEGM Professional Indemnity Insurance</h2>
        <h3>Proposal Form For Individual Healthcare Practitioners</h3>
    </div>

    <!-- Period Info -->
    <div class="period-info">
        <strong>Period of Insurance</strong><br>
        <strong>From:</strong>
        {{ $pricing && $pricing->policy_start_date ? \Carbon\Carbon::parse($pricing->policy_start_date)->format('d-M-Y') : 'N/A' }}
        <strong>To:</strong>
        {{ $pricing && $pricing->policy_expiry_date ? \Carbon\Carbon::parse($pricing->policy_expiry_date)->format('d-M-Y') : 'N/A' }}<br>
        <strong>{{ $policyApplication->reference_number ?? 'MRCM#23-' . str_pad($policyApplication->id, 4, '0', STR_PAD_LEFT) }}</strong>
    </div>

    <!-- 1. Details of Applicant -->
    <div class="section-title">1. Details of Applicant</div>
    <table>
        <tr>
            <td class="label">1.1 Name</td>
            <td>{{ strtoupper($policyApplication->user->name ?? 'N/A') }}</td>
        </tr>
        <tr>
            <td class="label">1.2 NRIC</td>
            <td>{{ $profile && $profile->nric_number ? $profile->nric_number : ($profile && $profile->passport_number ? $profile->passport_number : 'N/A') }}
            </td>
        </tr>
        <tr>
            <td class="label">1.3 Gender</td>
            <td>{{ $profile ? ucfirst($profile->gender) : 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">1.4 Mailing address</td>
            <td>{{ $addresses->where('type', 'mailing')->first()?->address ?? 'N/A' }}</td>
        </tr>
    </table>

    @php
        $primaryClinic = $addresses->where('type', 'primary_clinic')->first();
        $secondaryClinic = $addresses->where('type', 'secondary_clinic')->first();
    @endphp

    <!-- Primary Practicing Address -->
    <div class="sub-section">
        <strong>1.5 (A) Primary practicing address</strong>
        <table>
            <tr>
                <td class="label">Type:</td>
                <td>{{ $primaryClinic ? ucfirst($primaryClinic->clinic_type) : 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Name of clinic/hospital:</td>
                <td>{{ $primaryClinic?->clinic_name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Address:</td>
                <td>{{ $primaryClinic ? $primaryClinic->address . ', ' . $primaryClinic->postcode . ', ' . $primaryClinic->city . ', ' . $primaryClinic->state : 'N/A' }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Secondary Practicing Address -->
    @if ($secondaryClinic)
        <div class="sub-section">
            <strong>1.5 (B) Secondary practicing address</strong>
            <table>
                <tr>
                    <td class="label">Type:</td>
                    <td>{{ ucfirst($secondaryClinic->clinic_type) }}</td>
                </tr>
                <tr>
                    <td class="label">Name of clinic/hospital:</td>
                    <td>{{ $secondaryClinic->clinic_name }}</td>
                </tr>
                <tr>
                    <td class="label">Address:</td>
                    <td>{{ $secondaryClinic->address }}, {{ $secondaryClinic->postcode }},
                        {{ $secondaryClinic->city }}, {{ $secondaryClinic->state }}</td>
                </tr>
            </table>
        </div>
    @endif

    <!-- Contact and Other Details -->
    <table>
        <tr>
            <td class="label">1.6 Contact phone number</td>
            <td>{{ $contact ? $contact->contact_no : $policyApplication->user->contact_no ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">1.7 Email address</td>
            <td>{{ $policyApplication->user->email ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">1.8 Employment status</td>
            <td>{{ $healthcare ? ucfirst(str_replace('_', ' ', $healthcare->employment_status)) : 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">1.9 Specialty</td>
            <td>{{ $healthcare ? ucfirst(str_replace('_', ' ', $healthcare->professional_indemnity_type)) : 'N/A' }}
            </td>
        </tr>
    </table>

    <!-- Qualifications -->
    <div class="sub-section">
        <strong>1.10 Please indicate your qualification(s):</strong>
        <table>
            <tr>
                <th>Institution</th>
                <th>Degree or Qualification</th>
                <th>Year Obtained</th>
            </tr>
            @forelse($qualifications as $qual)
                <tr>
                    <td>{{ $qual->institution }}</td>
                    <td>{{ $qual->degree_or_qualification }}</td>
                    <td>{{ $qual->year_obtained }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No qualifications recorded</td>
                </tr>
            @endforelse
        </table>
    </div>

    <!-- Registration Details -->
    <div class="sub-section">
        <strong>1.11 Please provide the details of your registration below:</strong>
        <table>
            <tr>
                <td class="label">(a) Licensing / Registration Body (MMC/MDC)</td>
                <td>
                    @if ($profile)
                        @if ($profile->registration_council === 'mmc')
                            MMC
                        @elseif($profile->registration_council === 'mdc')
                            MDC
                        @else
                            {{ $profile->other_council ?? 'Others' }}
                        @endif
                    @else
                        N/A
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">(b) Registration Number:</td>
                <td>{{ $profile ? $profile->registration_number : 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <div class="page-break"></div>

    <!-- 2. Details of Healthcare Services Business -->
    <div class="section-title">2. Details of Healthcare Services Business</div>
    <table>
        <tr>
            <td class="label">Cover Type</td>
            <td>Professional Indemnity -
                {{ $healthcare ? ucfirst(str_replace('_', ' ', $healthcare->professional_indemnity_type)) : 'N/A' }}
            </td>
        </tr>
        <tr>
            <td class="label">Medical Status</td>
            <td>{{ $healthcare ? ucfirst(str_replace('_', ' ', $healthcare->professional_indemnity_type)) : 'N/A' }}
            </td>
        </tr>
        <tr>
            <td class="label">Class</td>
            <td>{{ $healthcare && $healthcare->cover_type ? ucfirst(str_replace('_', ' ', $healthcare->cover_type)) : 'N/A' }}
            </td>
        </tr>
        <tr>
            <td class="label">Liability Limit</td>
            <td>RM{{ number_format($pricing && $pricing->liability_limit ? $pricing->liability_limit : 0, 0) }}</td>
        </tr>
        <tr>
            <td class="label">Premium Per Annum</td>
            <td>RM{{ number_format($pricing->base_premium ?? 0, 0) }}</td>
        </tr>
        @if (($pricing->loading_amount ?? 0) > 0)
            <tr>
                <td class="label">Loading ({{ number_format($pricing->loading_percentage ?? 0, 2) }}%)</td>
                <td>RM{{ number_format($pricing->loading_amount, 0) }}</td>
            </tr>
        @endif
        <tr>
            <td class="label">Gross Premium</td>
            <td>RM{{ number_format($pricing->gross_premium ?? 0, 0) }}</td>
        </tr>
        <tr>
            <td class="label">0% SST</td>
            <td>RM{{ number_format($pricing->sst ?? 0, 0) }}</td>
        </tr>
        <tr>
            <td class="label">Stamp Duty</td>
            <td>RM{{ number_format($pricing->stamp_duty ?? 10, 2) }}</td>
        </tr>
        @if (($pricing->wallet_used ?? 0) > 0)
            <tr>
                <td class="label">Wallet Amount Used</td>
                <td class="text-success">- RM{{ number_format($pricing->wallet_used, 2) }}</td>
            </tr>
        @endif
        <tr class="total-row">
            <td class="label">Total Payable</td>
            <td>RM{{ number_format($pricing->total_payable ?? 0, 0) }}</td>
        </tr>
    </table>

    <!-- 2.(A) Payment Details -->
    @if ($policyApplication->payment_method === 'credit_card' && $policyApplication->card_no)
        <div class="section-title">2. (A) Payment Details</div>
        <p style="font-size: 9px; margin: 5px 0;">I hereby authorise Great Eastern General Insurance (Malaysia) Berhad
            (GEGM) to charge one-off payment for the above insurance policy to my card as started below:</p>
        <table>
            <tr>
                <td class="label">Payment Type</td>
                <td>Online</td>
            </tr>
            <tr>
                <td class="label">Name On Card</td>
                <td>{{ $policyApplication->name_on_card ?? '' }}</td>
            </tr>
            <tr>
                <td class="label">NRIC No</td>
                <td>{{ $policyApplication->nric_no ?? '' }}</td>
            </tr>
            <tr>
                <td class="label">Card No</td>
                <td>{{ $policyApplication->card_no ?? '' }}</td>
            </tr>
            <tr>
                <td class="label">Card Type:</td>
                <td>
                    @if ($policyApplication->card_type && is_array($policyApplication->card_type))
                        {{ implode(', ', array_map('ucfirst', $policyApplication->card_type)) }}
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Card Issuing Bank</td>
                <td>{{ $policyApplication->card_issuing_bank ?? '' }}</td>
            </tr>
            <tr>
                <td class="label">Relationship To policy holders</td>
                <td>
                    @if ($policyApplication->relationship && is_array($policyApplication->relationship))
                        {{ implode(', ', array_map('ucfirst', str_replace('_', ' ', $policyApplication->relationship))) }}
                    @endif
                </td>
            </tr>
        </table>
        <p style="font-size: 8px; margin-top: 10px;">I undertake that all information stated above is true and complete
            in all respects. I have read and understood the terms & conditions contained in this form and I hereby
            agreed that the company may process the manner as stated in GEGM's Easi-pay Service Form (A copy can be
            obtained upon request).</p>
    @endif

    <!-- 3. Risk Management -->
    <div class="section-title">3. Risk Management</div>
    <table>
        <tr>
            <td class="label">3.1 Do you maintain accurate records of medical services rendered?</td>
            <td>{{ $risk && $risk->medical_records ? 'Yes' : 'No' }}</td>
        </tr>
        <tr>
            <td class="label">3.2 Is consent/informed consent obtained and recorded as and when indicated?</td>
            <td>{{ $risk && $risk->informed_consent ? 'Yes' : 'No' }}</td>
        </tr>
        <tr>
            <td class="label">3.3 Do you have procedures for reporting adverse incidents and events?</td>
            <td>{{ $risk && $risk->adverse_incidents ? 'Yes' : 'No' }}</td>
        </tr>
        <tr>
            <td class="label">3.4 Do you have facilities for sterilisation of instruments in accordance with relevant
                guidelines/standards applying to your industry?</td>
            <td>{{ $risk && $risk->sterilisation_facilities ? 'Yes' : 'No' }}</td>
        </tr>
    </table>

    <!-- 4. Insurance History -->
    <div class="section-title">4. Insurance History</div>
    <table>
        <tr>
            <td class="label">4.1 Do you currently hold medical malpractice insurance? (If YES, please provide
                details.)</td>
            <td>{{ $insurance && $insurance->current_insurance ? 'Yes' : 'No' }}</td>
        </tr>
    </table>

    @if ($insurance && $insurance->current_insurance)
        <table>
            <tr>
                <th>Period of Insurance</th>
                <th>Insurer</th>
                <th>Policy Limit</th>
                <th>Excess</th>
                <th>Retroactive Date</th>
            </tr>
            <tr>
                <td>{{ $insurance->period_of_insurance ?? 'N/A' }}</td>
                <td>{{ $insurance->insurer_name ?? 'N/A' }}</td>
                <td>{{ $insurance->policy_limit_myr ?? 'N/A' }}</td>
                <td>{{ $insurance->excess_myr ?? 'N/A' }}</td>
                <td>Unlimited</td>
            </tr>
        </table>
    @endif

    <table>
        <tr>
            <td class="label">4.2 Have you ever had any application for medical malpractice insurance refused, or had
                any medical malpractice insurance coverage rescinded or cancelled? (If YES, please provide details on a
                separate sheet, noting the Section number.)</td>
            <td>{{ $insurance && $insurance->previous_claims ? 'Yes' : 'No' }}</td>
        </tr>
    </table>

    <!-- 5. Claims Experience -->
    <div class="section-title">5. Claims Experience</div>
    <table>
        <tr>
            <td class="label">5.1 Have any claims ever been made, or lawsuits been brought against you?</td>
            <td>{{ $claims && $claims->claims_made ? 'Yes' : 'No' }}</td>
        </tr>
        <tr>
            <td class="label">5.2 Are you aware of any errors, omissions, offences, circumstances or allegations which
                might result in a claim being made against you?</td>
            <td>{{ $claims && $claims->aware_of_errors ? 'Yes' : 'No' }}</td>
        </tr>
        <tr>
            <td class="label">5.3 Have you ever been the subject of disciplinary action or investigation by any
                authority or regulator or professional body?</td>
            <td>{{ $claims && $claims->disciplinary_action ? 'Yes' : 'No' }}</td>
        </tr>
    </table>

    @if ($claims && ($claims->claims_made || $claims->aware_of_errors || $claims->disciplinary_action))
        <p style="font-size: 9px; margin: 10px 0;"><strong>If you had answered Yes to any of the questions in this
                section, please provide full details overleaf and the status of each claim, lawsuits, allegation or
                matter, including</strong></p>
        <table>
            <tr>
                <td class="label">• the date of the claim, suit or allegation</td>
                <td>{{ $claims->claim_date_of_claim ?? '' }}</td>
            </tr>
            <tr>
                <td class="label">• The date you notified your previous insurers</td>
                <td>{{ $claims->claim_notified_date ?? '' }}</td>
            </tr>
            <tr>
                <td class="label">• The name of the claimant(s) and the services rendered</td>
                <td>{{ $claims->claim_claimant_name ?? '' }}</td>
            </tr>
            <tr>
                <td class="label">• The allegations made against you</td>
                <td>{{ $claims->claim_allegations ?? '' }}</td>
            </tr>
            <tr>
                <td class="label">• The amount claimed by the claimant(s)</td>
                <td>{{ $claims->claim_amount_claimed ?? '' }}</td>
            </tr>
            <tr>
                <td class="label">• Whether the status is outstanding or finalised</td>
                <td>{{ $claims->claim_status ?? '' }}</td>
            </tr>
            <tr>
                <td class="label">• The amounts paid for claims and defence costs to date</td>
                <td>{{ $claims->claim_amounts_paid ?? '' }}</td>
            </tr>
        </table>
    @endif

    <!-- DECLARATION -->
    <div class="section-title">DECLARATION</div>
    <div class="declaration">
        <p>I/We hereby declare and agree to the following on behalf of my self/ourselves and any person or entity who
            may have or claim any interest in the policy issued pursuant to this proposal form.</p>

        <ol>
            <li>All the foregoing statement s and answer s in this pr oposa l form together with any other document s or
                questionnaires submitted in connection with the proposal form all statements made and answers given to
                the Company's medical examiner(s), are complete and accurate ("the Information") and I under stand that
                the Information given by me is relevant to the Company in deciding whether to accept my proposal or not
                and the rates and terms to be applied. The Company may terminate or void the policy or reduce the amount
                of my claim, or reduce or vary the terms of the policy contract, if there is any non-disclosure,
                misrepresentation, misstatement, inaccuracy or omission.</li>

            <li>I/We would like to receive updates on Company's products, services, promotions, charitable causes or
                other marketing information from relevant third parties of the Company.</li>

            <li>I/We have fully read and under stood all the contents of, and the warnings and advice contained in this
                proposal form.</li>

            <li>I/We have fully read and understood the Data Protection Notice above and I/we agree that the Company may
                process the personal sensitive information inline with the manner as set out in the Notice.</li>

            <li>I/We declare that any funds and/or assets I/we place with the Company, as well as any profits that they
                generate, comply with the tax laws of the country (ies) where I/we am/are resident(s), as well as the
                tax laws of the country(ies) of which I/we am/are citizen(s).</li>

            <li>In the event the Company becomes aware that I/we and/or any other named insured(s) am/are or have become
                a prohibited person, meaning a person/entity who is subject to any laws, regulations and/or sanctions
                administered by any governmental or regulatory authorities or any other relevant authority as are
                currently or may become in the future in force or on any relevant list issued by and as determined by
                any such authority(ies), that have the effect of prohibiting the Company from providing insurance
                coverage or otherwise offer ing any benefit s to me/us or any other named insured(s) under the policy,
                whichever applicable, I/we agree that the Company may suspend, terminate or void the policy or my/our
                insurance coverage under the policy, whichever applicable, with effect from the appropriate date or from
                inception, as appropriate and at the sole discretion of the Company, and shall not be required to
                transact any business with me/us in connection with the policy, including but not limited to, making or
                receiving any payments under the policy or proposal submitted or any cover note issued, whichever.</li>
        </ol>

        <p style="margin-top: 10px;">Further, in the event the Company becomes aware that any of the Life Assured,
            Trustee, Assignee, Beneficiary, Beneficial Owner and/or Nominee and/or Mortgagee/Financier named in or
            connected with the policy is or has become a prohibited person, I/we agree that the Company may suspend,
            terminate, or void the policy or my/our insurance coverage under the policy, whichever applicable with
            effect from the appropriate date or from inception, as appropriate and at the sole discretion of the
            Company, and shall not be required to transact any business in connection with the policy, including but not
            limited to, making or receiving any payments under the policy or proposal submitted or any cover note
            issued, whichever</p>

        <p style="margin-top: 10px;">Under any of the above circumstances, the Company shall not be deemed to provide
            cover and/or be liable to pay any claim or benefits under the policy or proposal submitted or any cover note
            issued, whichever applicable.</p>
    </div>

    <!-- Signature Section -->
    <div class="signature-section">
        <table>
            <tr>
                <td style="width: 30%;"><strong>Date:</strong>
                    {{ $policyApplication->submitted_at ? $policyApplication->submitted_at->format('d/M/Y') : now()->format('d/M/Y') }}
                </td>
                <td style="width: 70%; text-align: right;">
                    @if ($policyApplication->signature_data)
                        @php
                            // Check if signature is base64 or file path
                            $isBase64 = str_starts_with($policyApplication->signature_data, 'data:image');
                            if ($isBase64) {
                                // Base64 images work directly in DomPDF
                                $signatureUrl = $policyApplication->signature_data;
                            } else {
                                // Old data: strip "app/" prefix if present
                                $signaturePath = $policyApplication->signature_data;
                                if (str_starts_with($signaturePath, 'app/')) {
                                    $signaturePath = substr($signaturePath, 4); // Remove "app/" prefix
                                }
                                // DomPDF requires absolute file paths, not URLs
                                // Convert storage path to absolute path
                                $signatureUrl = storage_path('app/' . $signaturePath);
                            }
                        @endphp
                        <img src="{{ $signatureUrl }}" alt="Signature" style="max-height: 40px;">
                    @else
                        <div class="signature-box"></div>
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: right; padding-top: 5px;">
                    <strong>Signatures of Proposer(s) / Proposer's Stamp (if any)</strong>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>
