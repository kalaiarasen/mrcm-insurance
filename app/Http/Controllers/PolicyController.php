<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PolicyApplication;
use Illuminate\Support\Facades\Auth;

class PolicyController extends Controller
{
    public function newPolicy(Request $request)
    {
        $user = Auth::user();
        
        // Check if editing a rejected policy
        $editingPolicyId = $request->query('edit');
        $rejectedPolicy = null;
        $existingData = null;
        
        if ($editingPolicyId) {
            // Load the rejected policy
            $rejectedPolicy = PolicyApplication::where('id', $editingPolicyId)
                ->where('user_id', $user->id)
                ->where('status', 'rejected')
                ->first();
                
            if (!$rejectedPolicy) {
                return redirect()->route('dashboard')->with('error', 'Policy not found or cannot be edited.');
            }
        }
        
        // Load existing Step 1 data for renewal or from rejected policy
        if ($rejectedPolicy) {
            // Load data from rejected policy
            $profile = $rejectedPolicy->user->applicantProfile;
            $mailingAddress = $rejectedPolicy->user->addresses()->where('type', 'mailing')->first();
            $primaryAddress = $rejectedPolicy->user->addresses()->where('type', 'primary_clinic')->first();
            $secondaryAddress = $rejectedPolicy->user->addresses()->where('type', 'secondary_clinic')->first();
            
            $existingData = [
                // Step 1: Applicant Details
                'title' => $profile->title ?? null,
                'full_name' => $profile ? 
                    str_replace(strtoupper($profile->title ?? '') . '. ', '', $rejectedPolicy->user->name) : 
                    $rejectedPolicy->user->name,
                'nationality_status' => $profile->nationality_status ?? null,
                'nric_number' => $profile->nric_number ?? null,
                'passport_number' => $profile->passport_number ?? null,
                'gender' => $profile->gender ?? null,
                'contact_no' => $rejectedPolicy->user->applicantContact ? $rejectedPolicy->user->applicantContact->contact_no : $rejectedPolicy->user->contact_no,
                'email_address' => $rejectedPolicy->user->applicantContact ? $rejectedPolicy->user->applicantContact->email_address : $rejectedPolicy->user->email,
                
                // Mailing Address
                'mailing_address' => $mailingAddress->address ?? null,
                'mailing_postcode' => $mailingAddress->postcode ?? null,
                'mailing_city' => $mailingAddress->city ?? null,
                'mailing_state' => $mailingAddress->state ?? null,
                'mailing_country' => $mailingAddress->country ?? null,
                
                // Primary Practicing Address
                'primary_clinic_type' => $primaryAddress->clinic_type ?? null,
                'primary_clinic_name' => $primaryAddress->clinic_name ?? null,
                'primary_address' => $primaryAddress->address ?? null,
                'primary_postcode' => $primaryAddress->postcode ?? null,
                'primary_city' => $primaryAddress->city ?? null,
                'primary_state' => $primaryAddress->state ?? null,
                'primary_country' => $primaryAddress->country ?? null,
                
                // Secondary Practicing Address
                'secondary_clinic_type' => $secondaryAddress->clinic_type ?? null,
                'secondary_clinic_name' => $secondaryAddress->clinic_name ?? null,
                'secondary_address' => $secondaryAddress->address ?? null,
                'secondary_postcode' => $secondaryAddress->postcode ?? null,
                'secondary_city' => $secondaryAddress->city ?? null,
                'secondary_state' => $secondaryAddress->state ?? null,
                'secondary_country' => $secondaryAddress->country ?? null,
                
                // Qualifications
                'registration_council' => $profile->registration_council ?? null,
                'other_council' => $profile->other_council ?? null,
                'registration_number' => $profile->registration_number ?? null,
            ];
            
            // Add qualifications (up to 3)
            $qualifications = $rejectedPolicy->user->qualifications()->orderBy('sequence')->get();
            foreach ($qualifications as $index => $qual) {
                $num = $index + 1;
                if ($num <= 3) {
                    $existingData["institution_$num"] = $qual->institution;
                    $existingData["qualification_$num"] = $qual->degree_or_qualification;
                    $existingData["year_obtained_$num"] = $qual->year_obtained;
                }
            }
        } elseif ($user) {
            // Check if user has previous policy data (where is_used = true) for renewal
            $hasExistingData = $user->applicantProfile()->exists();
            
            if ($hasExistingData) {
                // Get addresses with null safety
                $mailingAddress = $user->addresses()->where('type', 'mailing')->first();
                $primaryAddress = $user->addresses()->where('type', 'primary_clinic')->first();
                $secondaryAddress = $user->addresses()->where('type', 'secondary_clinic')->first();
                
                $existingData = [
                    // Step 1: Applicant Details Only
                    'title' => $user->applicantProfile->title ?? null,
                    'full_name' => $user->applicantProfile ? 
                        str_replace(strtoupper($user->applicantProfile->title ?? '') . '. ', '', $user->name) : 
                        $user->name,
                    'nationality_status' => $user->applicantProfile->nationality_status ?? null,
                    'nric_number' => $user->applicantProfile->nric_number ?? null,
                    'passport_number' => $user->applicantProfile->passport_number ?? null,
                    'gender' => $user->applicantProfile->gender ?? null,
                    'contact_no' => $user->applicantContact ? $user->applicantContact->contact_no : $user->contact_no,
                    'email_address' => $user->applicantContact ? $user->applicantContact->email_address : $user->email,
                    
                    // Mailing Address
                    'mailing_address' => $mailingAddress->address ?? null,
                    'mailing_postcode' => $mailingAddress->postcode ?? null,
                    'mailing_city' => $mailingAddress->city ?? null,
                    'mailing_state' => $mailingAddress->state ?? null,
                    'mailing_country' => $mailingAddress->country ?? null,
                    
                    // Primary Practicing Address
                    'primary_clinic_type' => $primaryAddress->clinic_type ?? null,
                    'primary_clinic_name' => $primaryAddress->clinic_name ?? null,
                    'primary_address' => $primaryAddress->address ?? null,
                    'primary_postcode' => $primaryAddress->postcode ?? null,
                    'primary_city' => $primaryAddress->city ?? null,
                    'primary_state' => $primaryAddress->state ?? null,
                    'primary_country' => $primaryAddress->country ?? null,
                    
                    // Secondary Practicing Address
                    'secondary_clinic_type' => $secondaryAddress->clinic_type ?? null,
                    'secondary_clinic_name' => $secondaryAddress->clinic_name ?? null,
                    'secondary_address' => $secondaryAddress->address ?? null,
                    'secondary_postcode' => $secondaryAddress->postcode ?? null,
                    'secondary_city' => $secondaryAddress->city ?? null,
                    'secondary_state' => $secondaryAddress->state ?? null,
                    'secondary_country' => $secondaryAddress->country ?? null,
                    
                    // Qualifications
                    'registration_council' => $user->applicantProfile->registration_council ?? null,
                    'other_council' => $user->applicantProfile->other_council ?? null,
                    'registration_number' => $user->applicantProfile->registration_number ?? null,
                ];
                
                // Add qualifications (up to 3)
                $qualifications = $user->qualifications()->orderBy('sequence')->get();
                foreach ($qualifications as $index => $qual) {
                    $num = $index + 1;
                    if ($num <= 3) {
                        $existingData["institution_$num"] = $qual->institution;
                        $existingData["qualification_$num"] = $qual->degree_or_qualification;
                        $existingData["year_obtained_$num"] = $qual->year_obtained;
                    }
                }
            }
        }
        
        return view('pages.new-policy.index', compact('existingData', 'rejectedPolicy'));
    }
}
