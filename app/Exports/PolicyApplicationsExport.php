<?php

namespace App\Exports;

use App\Models\PolicyApplication;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PolicyApplicationsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;
    protected $policyType;
    protected $status;
    protected $agentId;
    protected $cardFilter;
    protected $expiryYear;

    public function __construct($startDate = null, $endDate = null, $policyType = null, $status = null, $agentId = null, $cardFilter = null, $expiryYear = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->policyType = $policyType;
        $this->status = $status;
        $this->agentId = $agentId;
        $this->cardFilter = $cardFilter;
        $this->expiryYear = $expiryYear;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = PolicyApplication::with([
            'user.applicantProfile',
            'user.applicantContact',
            'user.healthcareService',
            'policyPricing'
        ]);

        // Filter by agent if specified
        if ($this->agentId) {
            $query->whereHas('user', function($q) {
                $q->where('agent_id', $this->agentId);
            });
        }

        // Apply date filters
        if ($this->startDate) {
            $query->whereDate('updated_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('updated_at', '<=', $this->endDate);
        }

        // Apply policy type filter
        if ($this->policyType) {
            $query->whereHas('user.healthcareService', function($q) {
                $q->where('professional_indemnity_type', $this->policyType);
            });
        }

        // Apply expiry year filter
        if ($this->expiryYear) {
            $selectedYear = $this->expiryYear;
            $query->whereHas('policyPricing', function($q) use ($selectedYear) {
                $q->whereYear('policy_expiry_date', $selectedYear);
            })
            // Exclude users who have a policy expiring in the year after the selected year
            ->whereNotExists(function($subQuery) use ($selectedYear) {
                $subQuery->select(\DB::raw(1))
                    ->from('policy_applications as pa2')
                    ->whereColumn('pa2.user_id', 'policy_applications.user_id')
                    ->whereColumn('pa2.id', '!=', 'policy_applications.id')
                    ->whereIn('pa2.admin_status', ['active', 'new_case', 'new_renewal', 'not_paid', 'paid', 'sent_uw'])
                    ->whereExists(function($pricingSubQuery) use ($selectedYear) {
                        $pricingSubQuery->select(\DB::raw(1))
                            ->from('policy_pricings')
                            ->whereColumn('policy_pricings.policy_application_id', 'pa2.id')
                            ->whereYear('policy_pricings.policy_expiry_date', $selectedYear + 1);
                    });
            });
        }

        // Apply status filter
        if ($this->status) {
            $query->where('admin_status', $this->status);
        }

        // Apply card filter (for clickable analytics cards)
        if ($this->cardFilter) {
            switch ($this->cardFilter) {
                case 'active_last_30':
                    $query->where('admin_status', 'active')
                          ->where('activated_at', '>=', now()->subDays(30));
                    break;
                case 'expiring_soon':
                    // Only show policies expiring in next 3 months
                    // EXCLUDE users who already have a policy (active OR submitted) for next year
                    $query->where('admin_status', 'active')
                          ->whereHas('policyPricing', function($q) {
                              $q->whereBetween('policy_expiry_date', [
                                  now()->toDateString(),
                                  now()->addMonths(3)->toDateString()
                              ]);
                          })
                          // Exclude users who have another policy expiring next year
                          ->whereNotExists(function($subQuery) {
                              $subQuery->select(\DB::raw(1))
                                  ->from('policy_applications as pa2')
                                  ->whereColumn('pa2.user_id', 'policy_applications.user_id')
                                  ->whereColumn('pa2.id', '!=', 'policy_applications.id')
                                  ->whereIn('pa2.admin_status', ['active', 'new_case', 'new_renewal', 'not_paid', 'paid', 'sent_uw'])
                                  ->whereExists(function($pricingSubQuery) {
                                      $pricingSubQuery->select(\DB::raw(1))
                                          ->from('policy_pricings')
                                          ->whereColumn('policy_pricings.policy_application_id', 'pa2.id')
                                          ->whereYear('policy_pricings.policy_expiry_date', now()->addYear()->year);
                                  });
                          });
                    break;
                case 'pending_payment':
                    $query->where('admin_status', 'not_paid');
                    break;
                case 'sent_uw':
                    $query->where('admin_status', 'sent_uw');
                    break;
            }
        }

        return $query->orderBy('updated_at', 'desc')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Policy ID',
            'Last Updated',
            'Status',
            'Admin Status',
            'Expiry Date',
            'Name',
            'Email',
            'Phone',
            'Type of Professional Indemnity',
            'Amount (RM)',
            'Agent',
            'Submitted At',
        ];
    }

    /**
     * @param mixed $policy
     * @return array
     */
    public function map($policy): array
    {
        $statusMap = [
            'new_case' => 'New Case',
            'new_renewal' => 'New Renewal',
            'not_paid' => 'Not Paid',
            'paid' => 'Paid',
            'sent_uw' => 'Sent UW',
            'active' => 'Active',
        ];

        $displayStatus = $statusMap[$policy->admin_status] ?? ucfirst($policy->admin_status ?? 'N/A');
        
        $agentId = $policy->user?->agent_id;
        $agentName = '-';
        if ($agentId) {
            $agent = \App\Models\User::find($agentId);
            $agentName = $agent?->name ?? '-';
        }

        return [
            $policy->reference_number ?? 'N/A',
            $policy->updated_at ? $policy->updated_at->format('d-M-Y h:i A') : 'N/A',
            ucfirst($policy->status ?? 'N/A'),
            $displayStatus,
            $policy->policyPricing?->policy_expiry_date 
                ? \Carbon\Carbon::parse($policy->policyPricing->policy_expiry_date)->format('d-M-Y') 
                : 'N/A',
            $policy->user?->name ?? 'Unknown',
            $policy->user?->email ?? 'N/A',
            $policy->user?->contact_no ?? $policy->user?->applicantContact?->contact_no ?? 'N/A',
            $this->formatProfessionalIndemnityType($policy->user?->healthcareService?->professional_indemnity_type),
            is_numeric($policy->policyPricing?->total_payable) 
                ? number_format($policy->policyPricing->total_payable, 2) 
                : ($policy->policyPricing?->total_payable ?? 'N/A'),
            $agentName,
            $policy->submitted_at ? $policy->submitted_at->format('d-M-Y h:i A') : 'N/A',
        ];
    }

    /**
     * Format professional indemnity type
     */
    private function formatProfessionalIndemnityType($type)
    {
        $typeMap = [
            'medical_practice' => 'Medical Practice',
            'dental_practice' => 'Dental Practice',
            'pharmacist' => 'Pharmacist',
        ];
        
        return $typeMap[$type] ?? 'N/A';
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
