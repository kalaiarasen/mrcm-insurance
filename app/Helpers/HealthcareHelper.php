<?php

namespace App\Helpers;

class HealthcareHelper
{
    /**
     * Get Specialty/Medical Status based on cover type, employment status, and specialty
     */
    public static function getSpecialtyStatus($healthcareService)
    {
        if (!$healthcareService) {
            return 'N/A';
        }

        $coverType = $healthcareService->professional_indemnity_type;
        $employmentStatus = $healthcareService->employment_status;
        $specialtyArea = $healthcareService->specialty_area;

        // Dental Practitioner mappings
        if ($coverType === 'dental_practice') {
            if ($employmentStatus === 'government') {
                return 'Government Dental Practitioner - Locum Only';
            }
            if ($employmentStatus === 'private') {
                if ($specialtyArea === 'general_dental_practitioner' || $specialtyArea === 'general_dentist' || $specialtyArea === 'dental') {
                    return 'General Dental Practitioner';
                }
                if ($specialtyArea === 'government_private_dental_specialists' || $specialtyArea == 'dentist_specialist') {
                    return 'Government / Private Dental Specialists';
                }
            }
        }

        // Medical Practitioner mappings
        if ($coverType === 'medical_practice') {
            if ($employmentStatus === 'government') {
                if ($specialtyArea === 'medical_officer' || $specialtyArea === 'government_medical_officers_locum_only') {
                    return 'Government Medical Officers - Locum only';
                }
            }
            if ($employmentStatus === 'private') {
                if ($specialtyArea === 'general_practitioner' || $specialtyArea === 'general_practice') {
                    return 'General Practitioner';
                }
                if ($specialtyArea === 'medical_specialist' || $specialtyArea === 'government_private_medical_specialists') {
                    return 'Government / Private Medical Specialists';
                }
                if ($specialtyArea === 'non_practicing_doctor') {
                    return 'Non-Practicing Doctor';
                }
            }
            if($employmentStatus === 'non_practicing') {
                return 'Non-Practicing Doctor';
            }
        }

        // Pharmacist mappings
        if ($coverType === 'pharmacist') {
            return 'Pharmacist';
        }

        return 'N/A';
    }

    /**
     * Get Class value - EXACT LOGIC from show.blade.php
     */
    public static function getClassValue($healthcareService, $withLocumExtension = false)
    {
        if (!$healthcareService) {
            return 'N/A';
        }

        $coverType = $healthcareService->cover_type;
        $employmentStatus = $healthcareService->employment_status;
        $specialtyArea = $healthcareService->specialty_area;
        $practiceArea = $healthcareService->practice_area;
        $serviceType = $healthcareService->service_type;
        $professionalIndemnityType = $healthcareService->professional_indemnity_type;
        $locumPracticeLocation = $healthcareService->locum_practice_location;


        if($professionalIndemnityType === 'pharmacist') {
            return 'Pharmacist';
        }
        
        if ($coverType === 'dental_specialists' && $employmentStatus === 'private') {
            if ($specialtyArea === 'government_private_dental_specialists' || $specialtyArea == 'dentist_specialist') {
                return 'Government / Private Dental Specialists';
            }
        }

        if($coverType == 'medium_risk_specialist' || $locumPracticeLocation == 'private_hospital' || $locumPracticeLocation == 'private_clinic' || $coverType == 'low_risk_specialist') {
            $serviceTypeMap = [
                'dermatology_non_cosmetic' => 'Dermatology - Non Cosmetic',
                'clinic_based_non_general_anaesthetic' => 'Dental Specialists practising Oral and Maxillofacial Surgery (OMFS)- Clinic Based',
                'hospital_based_full_fledged_omfs' => 'Dental Specialists practising Oral and Maxillofacial Surgery (OMFS)- Hospital Based',
                'lecturer_trainee' => 'Lecturer/Trainee',
                'general_practitioner_private_hospital_outpatient' => 'General Practitioner in Private Hospital - Outpatient Services',
                'general_practitioner_private_hospital_emergency' => 'General Practitioner in Private Hospital – Emergency Department',
                'general_practice' => 'General Practice',
                'general_practice_with_specialized_procedures' => 'General Practice with Specialized Procedures',
                'general_dental_practice' => 'General Dental Practice',
                'general_dental_practice_with_locum_extension' => 'General Dental Practice (with locum extension)',
                'general_dental_practitioners_practising_accredited_specialised_procedures' => 'General Dental Practitioners, practising accredited specialised procedures',
                'general_dental_practitioners_practising_accredited_specialised_procedures_with_locum_extension' => 'General Dental Practitioners, practising accredited specialised procedures(with locum extension)',
                
                // Medical Practice Areas - General Practitioner
                'core_services' => 'Core Services',
                'core_services_with_locum_extension' => 'Core Services (with locum extension)',
                'core_services_with_procedures' => 'Core Services with Procedures',
                'outpatient_service' => 'Outpatient Service',
                'emergency_department' => 'Emergency Department',
                'general_practitioner_with_obstetrics' => 'General Practitioner with Obstetrics',
                'general_practitioner_with_obstetrics_with_locum_extension' => 'General Practitioner with Obstetrics (with locum extension)',
                'cosmetic_aesthetic_non_invasive' => 'Cosmetic & Aesthetic - Non-Invasive Elective Topical Enhancement',
                'cosmetic_aesthetic_non_surgical_invasive' => 'Cosmetic & Aesthetic - Non-Surgical Invasive Elective Topical Enhancement (with locum extension)',
                'cosmetic_aesthetic_surgical_invasive' => 'Cosmetic & Aesthetic - Non - Surgical Invasive Elective Topical Enhancement',
                
                // Medical Practice Areas - Medical Specialists
                'occupational_health_physicians' => 'Occupational Health Physicians/ Family Physicians',
                'general_physicians' => 'General Physicians',
                'dermatology_cosmetic' => 'Dermatology - cosmetic',
                'infectious_diseases' => 'Infectious Diseases',
                'pathology' => 'Pathology',
                'psychiatry' => 'Psychiatry',
                'endocrinology' => 'Endocrinology',
                'rehab_medicine' => 'Rehab, medicine',
                'paediatrics_non_neonatal' => 'Paediatrics (Non Neonatal)',
                'paediatrics_non_neonatal_with_locum_extension' => 'Paediatrics (Non Neonatal) (with locum extension)',
                'geriatrics' => 'Geriatrics',
                'haematology' => 'Haematology',
                'neurology' => 'Neurology',
                'radiology_non_interventional' => 'Radiology (Non Interventional)',
                'ophthalmology_surgeries_non_ga' => 'Ophthalmology Surgeries (Non G.A)',
                'ent_surgeries_non_ga' => 'ENT Surgeries (Non G.A)',
                'radiology_interventional' => 'Radiology - Interventional',
                'gastroenterology' => 'Gastroenterology',
                'office_clinical_gynaecology' => 'Office/Clinical Gynaecology',
                'office_clinical_gynaecology_with_locum_extension' => 'Office/Clinical Gynaecology (with locum extension)',
                'office_clinical_orthopaedics' => 'Office / Clinical Orthopaedics',
                'nuclear_medicine' => 'Nuclear medicine',
                'nephrology' => 'Nephrology',
                'immunology' => 'Immunology',
                'ophthalmology_office_procedures' => 'Ophthalmology/Office procedures',
                'office_ent_clinic_based' => 'Office ENT (Clinic based)',
                'ophthalmology_surgeries_non_ga' => 'Ophthalmology Surgeries (Non G.A)',
                'ent_surgeries_non_ga' => 'ENT Surgeries (Non G.A)',
                'radiology_interventional' => 'Radiology - Interventional',
                'gastroenterology' => 'Gastroentrology',
                'office_clinical_orthopaedics' => 'Office/Clinical Orthopaedics',
                'office_clinical_gynaecology' => 'Office/Clinical Gynaecology',
                'cosmetic_aesthetic_non_surgical_invasive' => 'Cosmetic and Aesthetic - Non-surgical Invasive elective topical enhancement',
                'cosmetic_aesthetic_surgical_invasive' => 'Cosmetic and Aesthetic -Surgical, Invasive',
            ];

            if (isset($serviceTypeMap[$serviceType])) {
                return $serviceTypeMap[$serviceType];
            }
        }

        if($specialtyArea == 'general_practitioner' || $specialtyArea == 'general_dentist' || $specialtyArea === 'dentist_specialist') {
            $coverTypeMap = [
                'general_dental_practitioners' => 'General Dental Practice',
                'general_practitioner_private_hospital_outpatient' => 'General Practitioner in Private Hospital - Outpatient Services',
                'general_practitioner_private_hospital_emergency' => 'General Practitioner in Private Hospital – Emergency Department',
                'general_practice' => 'General Practice',
                'general_practice_with_specialized_procedures' => 'General Practice with Specialized Procedures',
                'general_dental_practice' => 'General Dental Practice',
                'general_dental_practice_with_locum_extension' => 'General Dental Practice (with locum extension)',
                'general_dental_practitioners_practising_accredited_specialised_procedures' => 'General Dental Practitioners, practising accredited specialised procedures',
                'general_dental_practitioners_practising_accredited_specialised_procedures_with_locum_extension' => 'General Dental Practitioners, practising accredited specialised procedures(with locum extension)',
                
                // Medical Practice Areas - General Practitioner
                'core_services' => 'Core Services',
                'core_services_with_locum_extension' => 'Core Services (with locum extension)',
                'core_services_with_procedures' => 'Core Services with Procedures',
                'outpatient_service' => 'Outpatient Service',
                'emergency_department' => 'Emergency Department',
                'general_practitioner_with_obstetrics' => 'General Practitioner with Obstetrics',
                'general_practitioner_with_obstetrics_with_locum_extension' => 'General Practitioner with Obstetrics (with locum extension)',
                'cosmetic_aesthetic_non_invasive' => 'Cosmetic & Aesthetic - Non-Invasive Elective Topical Enhancement',
                'cosmetic_aesthetic_non_surgical_invasive' => 'Cosmetic & Aesthetic - Non-Surgical Invasive Elective Topical Enhancement (with locum extension)',
                'cosmetic_aesthetic_surgical_invasive' => 'Cosmetic & Aesthetic - Non - Surgical Invasive Elective Topical Enhancement',
                
                // Medical Practice Areas - Medical Specialists
                'occupational_health_physicians' => 'Occupational Health Physicians/ Family Physicians',
                'general_physicians' => 'General Physicians',
                'dermatology_cosmetic' => 'Dermatology - cosmetic',
                'infectious_diseases' => 'Infectious Diseases',
                'pathology' => 'Pathology',
                'psychiatry' => 'Psychiatry',
                'endocrinology' => 'Endocrinology',
                'rehab_medicine' => 'Rehab, medicine',
                'paediatrics_non_neonatal' => 'Paediatrics (Non Neonatal)',
                'paediatrics_non_neonatal_with_locum_extension' => 'Paediatrics (Non Neonatal) (with locum extension)',
                'geriatrics' => 'Geriatrics',
                'haematology' => 'Haematology',
                'neurology' => 'Neurology',
                'radiology_non_interventional' => 'Radiology (Non Interventional)',
                'ophthalmology_surgeries_non_ga' => 'Ophthalmology Surgeries (Non G.A)',
                'ent_surgeries_non_ga' => 'ENT Surgeries (Non G.A)',
                'radiology_interventional' => 'Radiology - Interventional',
                'gastroenterology' => 'Gastroenterology',
                'office_clinical_gynaecology' => 'Office/Clinical Gynaecology',
                'office_clinical_gynaecology_with_locum_extension' => 'Office/Clinical Gynaecology (with locum extension)',
                'office_clinical_orthopaedics' => 'Office / Clinical Orthopaedics',
                'nuclear_medicine' => 'Nuclear medicine',
                'nephrology' => 'Nephrology',
                'immunology' => 'Immunology',
                'ophthalmology_office_procedures' => 'Ophthalmology/Office procedures',
                'office_ent_clinic_based' => 'Office ENT (Clinic based)',
                'ent_surgeries_non_ga' => 'ENT Surgeries (Non G.A)',
                'radiology_interventional' => 'Radiology - Interventional',
                'gastroenterology' => 'Gastroentrology',
                'office_clinical_orthopaedics' => 'Office/Clinical Orthopaedics',
                'office_clinical_gynaecology' => 'Office/Clinical Gynaecology',
                'cosmetic_aesthetic_non_surgical_invasive' => 'Cosmetic and Aesthetic - Non-surgical Invasive elective topical enhancement',
                'cosmetic_aesthetic_surgical_invasive' => 'Cosmetic and Aesthetic - Surgical, Invasive',
                'dental_specialist_oral_maxillofacial_surgery' => 'Dental Specialists practising Oral and Maxillofacial Surgery (OMFS)- Hospital Based',
            ];

            if (isset($coverTypeMap[$coverType])) {
                return $coverTypeMap[$coverType];
            }
        }

        // Priority 1: Check practice_area first
        if ($practiceArea) {
            $practiceAreaMap = [
                // Dental Practice Areas
                'general_dental_practitioners_accredited_specialised_procedures' => 'General Dental Practitioners, practising accredited specialised procedures',
                'lecturer_trainee' => 'Lecturer/Trainee',
                'general_practice' => 'General Practice',
                'general_practice_with_specialized_procedures' => 'General Practice with Specialized Procedures',
                'general_dental_practice' => 'General Dental Practice',
                'general_dental_practice_with_locum_extension' => 'General Dental Practice (with locum extension)',
                'general_dental_practitioners_practising_accredited_specialised_procedures' => 'General Dental Practitioners, practising accredited specialised procedures',
                'general_dental_practitioners_practising_accredited_specialised_procedures_with_locum_extension' => 'General Dental Practitioners, practising accredited specialised procedures(with locum extension)',
                
                // Medical Practice Areas - General Practitioner
                'core_services' => 'Core Services',
                'core_services_with_locum_extension' => 'Core Services (with locum extension)',
                'core_services_with_procedures' => 'Core Services with Procedures',
                'outpatient_service' => 'Outpatient Service',
                'emergency_department' => 'Emergency Department',
                'general_practitioner_with_obstetrics' => 'General Practitioner with Obstetrics',
                'general_practitioner_with_obstetrics_with_locum_extension' => 'General Practitioner with Obstetrics (with locum extension)',
                'cosmetic_aesthetic_non_invasive' => 'Cosmetic & Aesthetic - Non-Invasive Elective Topical Enhancement',
                'cosmetic_aesthetic_non_surgical_invasive' => 'Cosmetic & Aesthetic - Non-Surgical Invasive Elective Topical Enhancement (with locum extension)',
                'cosmetic_aesthetic_surgical_invasive' => 'Cosmetic & Aesthetic - Non - Surgical Invasive Elective Topical Enhancement',
                
                // Medical Practice Areas - Medical Specialists
                'occupational_health_physicians' => 'Occupational Health Physicians/ Family Physicians',
                'general_physicians' => 'General Physicians',
                'dermatology_cosmetic' => 'Dermatology - cosmetic',
                'infectious_diseases' => 'Infectious Diseases',
                'pathology' => 'Pathology',
                'psychiatry' => 'Psychiatry',
                'endocrinology' => 'Endocrinology',
                'rehab_medicine' => 'Rehab, medicine',
                'paediatrics_non_neonatal' => 'Paediatrics (Non Neonatal)',
                'paediatrics_non_neonatal_with_locum_extension' => 'Paediatrics (Non Neonatal) (with locum extension)',
                'geriatrics' => 'Geriatrics',
                'haematology' => 'Haematology',
                'neurology' => 'Neurology',
                'radiology_non_interventional' => 'Radiology (Non Interventional)',
                'ophthalmology_surgeries_non_ga' => 'Ophthalmology Surgeries (Non G.A)',
                'ent_surgeries_non_ga' => 'ENT Surgeries (Non G.A)',
                'radiology_interventional' => 'Radiology - Interventional',
                'gastroenterology' => 'Gastroenterology',
                'office_clinical_gynaecology' => 'Office/Clinical Gynaecology',
                'office_clinical_gynaecology_with_locum_extension' => 'Office/Clinical Gynaecology (with locum extension)',
                'office_clinical_orthopaedics' => 'Office / Clinical Orthopaedics',
                'nuclear_medicine' => 'Nuclear medicine',
                'nephrology' => 'Nephrology',
                'immunology' => 'Immunology',
                'ophthalmology_office_procedures' => 'Ophthalmology/Office procedures',
                'office_ent_clinic_based' => 'Office ENT (Clinic based)',
                'ophthalmology_surgeries_non_ga' => 'Ophthalmology Surgeries (Non G.A)',
                'ent_surgeries_non_ga' => 'ENT Surgeries (Non G.A)',
                'radiology_interventional' => 'Radiology - Interventional',
                'gastroenterology' => 'Gastroentrology',
                'office_clinical_orthopaedics' => 'Office/Clinical Orthopaedics',
                'office_clinical_gynaecology' => 'Office/Clinical Gynaecology',
                'cosmetic_aesthetic_non_surgical_invasive' => 'Cosmetic and Aesthetic - Non-surgical Invasive elective topical enhancement',
                'cosmetic_aesthetic_surgical_invasive' => 'Cosmetic and Aesthetic - Surgical, Invasive',
                'dental_specialist_oral_maxillofacial_surgery' => 'Dental Specialists practising Oral and Maxillofacial Surgery (OMFS)- Hospital Based',
            ];

            if (isset($practiceAreaMap[$practiceArea])) {
                return $practiceAreaMap[$practiceArea];
            }
        }

        // Priority 2: Check service_type
        if ($serviceType) {
            $serviceTypeMap = [
                'clinic_based_non_general_anaesthetic' => 'Dental Specialists practising Oral and Maxillofacial Surgery (OMFS)- Clinic Based',
                'hospital_based_full_fledged_omfs' => 'Dental Specialists practising Oral and Maxillofacial Surgery (OMFS)- Hospital Based',
                'lecturer_trainee' => 'Lecturer/Trainee',
                'general_practitioner_private_hospital_outpatient' => 'General Practitioner in Private Hospital - Outpatient Services',
                'general_practitioner_private_hospital_emergency' => 'General Practitioner in Private Hospital – Emergency Department',
                'general_practice' => 'General Practice',
                'general_practice_with_specialized_procedures' => 'General Practice with Specialized Procedures',
                'general_dental_practice' => 'General Dental Practice',
                'general_dental_practice_with_locum_extension' => 'General Dental Practice (with locum extension)',
                'general_dental_practitioners_practising_accredited_specialised_procedures' => 'General Dental Practitioners, practising accredited specialised procedures',
                'general_dental_practitioners_practising_accredited_specialised_procedures_with_locum_extension' => 'General Dental Practitioners, practising accredited specialised procedures(with locum extension)',
                
                // Medical Practice Areas - General Practitioner
                'core_services' => 'Core Services',
                'core_services_with_locum_extension' => 'Core Services (with locum extension)',
                'core_services_with_procedures' => 'Core Services with Procedures',
                'outpatient_service' => 'Outpatient Service',
                'emergency_department' => 'Emergency Department',
                'general_practitioner_with_obstetrics' => 'General Practitioner with Obstetrics',
                'general_practitioner_with_obstetrics_with_locum_extension' => 'General Practitioner with Obstetrics (with locum extension)',
                'cosmetic_aesthetic_non_invasive' => 'Cosmetic & Aesthetic - Non-Invasive Elective Topical Enhancement',
                'cosmetic_aesthetic_non_surgical_invasive' => 'Cosmetic & Aesthetic - Non-Surgical Invasive Elective Topical Enhancement (with locum extension)',
                'cosmetic_aesthetic_surgical_invasive' => 'Cosmetic & Aesthetic - Non - Surgical Invasive Elective Topical Enhancement',
                
                // Medical Practice Areas - Medical Specialists
                'occupational_health_physicians' => 'Occupational Health Physicians/ Family Physicians',
                'general_physicians' => 'General Physicians',
                'dermatology_cosmetic' => 'Dermatology - cosmetic',
                'infectious_diseases' => 'Infectious Diseases',
                'pathology' => 'Pathology',
                'psychiatry' => 'Psychiatry',
                'endocrinology' => 'Endocrinology',
                'rehab_medicine' => 'Rehab, medicine',
                'paediatrics_non_neonatal' => 'Paediatrics (Non Neonatal)',
                'paediatrics_non_neonatal_with_locum_extension' => 'Paediatrics (Non Neonatal) (with locum extension)',
                'geriatrics' => 'Geriatrics',
                'haematology' => 'Haematology',
                'neurology' => 'Neurology',
                'radiology_non_interventional' => 'Radiology (Non Interventional)',
                'ophthalmology_surgeries_non_ga' => 'Ophthalmology Surgeries (Non G.A)',
                'ent_surgeries_non_ga' => 'ENT Surgeries (Non G.A)',
                'radiology_interventional' => 'Radiology - Interventional',
                'gastroenterology' => 'Gastroenterology',
                'office_clinical_gynaecology' => 'Office/Clinical Gynaecology',
                'office_clinical_gynaecology_with_locum_extension' => 'Office/Clinical Gynaecology (with locum extension)',
                'office_clinical_orthopaedics' => 'Office / Clinical Orthopaedics',
                'nuclear_medicine' => 'Nuclear medicine',
                'nephrology' => 'Nephrology',
                'immunology' => 'Immunology',
                'ophthalmology_office_procedures' => 'Ophthalmology/Office procedures',
                'office_ent_clinic_based' => 'Office ENT (Clinic based)',
                'ophthalmology_surgeries_non_ga' => 'Ophthalmology Surgeries (Non G.A)',
                'ent_surgeries_non_ga' => 'ENT Surgeries (Non G.A)',
                'radiology_interventional' => 'Radiology - Interventional',
                'gastroenterology' => 'Gastroentrology',
                'office_clinical_orthopaedics' => 'Office/Clinical Orthopaedics',
                'office_clinical_gynaecology' => 'Office/Clinical Gynaecology',
                'cosmetic_aesthetic_non_surgical_invasive' => 'Cosmetic and Aesthetic - Non-surgical Invasive elective topical enhancement',
                'cosmetic_aesthetic_surgical_invasive' => 'Cosmetic and Aesthetic -Surgical, Invasive',
            ];

            if (isset($serviceTypeMap[$serviceType])) {
                return $serviceTypeMap[$serviceType];
            }
        }

        if ($specialtyArea) {
            $specialtyAreaMap = [
                'lecturer_trainee' => 'Lecturer/Trainee',
                'general_practitioner_private_hospital_outpatient' => 'General Practitioner in Private Hospital - Outpatient Services',
                'general_practitioner_private_hospital_emergency' => 'General Practitioner in Private Hospital – Emergency Department',
                'general_practice' => 'General Practice',
                'general_practice_with_specialized_procedures' => 'General Practice with Specialized Procedures',
                'general_dental_practice' => 'General Dental Practice',
                'general_dental_practice_with_locum_extension' => 'General Dental Practice (with locum extension)',
                'general_dental_practitioners_practising_accredited_specialised_procedures' => 'General Dental Practitioners, practising accredited specialised procedures',
                'general_dental_practitioners_practising_accredited_specialised_procedures_with_locum_extension' => 'General Dental Practitioners, practising accredited specialised procedures(with locum extension)',
                
                // Medical Practice Areas - General Practitioner
                'core_services' => 'Core Services',
                'core_services_with_locum_extension' => 'Core Services (with locum extension)',
                'core_services_with_procedures' => 'Core Services with Procedures',
                'outpatient_service' => 'Outpatient Service',
                'emergency_department' => 'Emergency Department',
                'general_practitioner_with_obstetrics' => 'General Practitioner with Obstetrics',
                'general_practitioner_with_obstetrics_with_locum_extension' => 'General Practitioner with Obstetrics (with locum extension)',
                'cosmetic_aesthetic_non_invasive' => 'Cosmetic & Aesthetic - Non-Invasive Elective Topical Enhancement',
                'cosmetic_aesthetic_non_surgical_invasive' => 'Cosmetic & Aesthetic - Non-Surgical Invasive Elective Topical Enhancement (with locum extension)',
                'cosmetic_aesthetic_surgical_invasive' => 'Cosmetic & Aesthetic - Non - Surgical Invasive Elective Topical Enhancement',
                
                // Medical Practice Areas - Medical Specialists
                'occupational_health_physicians' => 'Occupational Health Physicians/ Family Physicians',
                'general_physicians' => 'General Physicians',
                'dermatology_cosmetic' => 'Dermatology - cosmetic',
                'infectious_diseases' => 'Infectious Diseases',
                'pathology' => 'Pathology',
                'psychiatry' => 'Psychiatry',
                'endocrinology' => 'Endocrinology',
                'rehab_medicine' => 'Rehab, medicine',
                'paediatrics_non_neonatal' => 'Paediatrics (Non Neonatal)',
                'paediatrics_non_neonatal_with_locum_extension' => 'Paediatrics (Non Neonatal) (with locum extension)',
                'geriatrics' => 'Geriatrics',
                'haematology' => 'Haematology',
                'neurology' => 'Neurology',
                'radiology_non_interventional' => 'Radiology (Non Interventional)',
                'ophthalmology_surgeries_non_ga' => 'Ophthalmology Surgeries (Non G.A)',
                'ent_surgeries_non_ga' => 'ENT Surgeries (Non G.A)',
                'radiology_interventional' => 'Radiology - Interventional',
                'gastroenterology' => 'Gastroenterology',
                'office_clinical_gynaecology' => 'Office/Clinical Gynaecology',
                'office_clinical_gynaecology_with_locum_extension' => 'Office/Clinical Gynaecology (with locum extension)',
                'office_clinical_orthopaedics' => 'Office / Clinical Orthopaedics',
                'nuclear_medicine' => 'Nuclear medicine',
                'nephrology' => 'Nephrology',
                'immunology' => 'Immunology',
                'ophthalmology_office_procedures' => 'Ophthalmology/Office procedures',
                'office_ent_clinic_based' => 'Office ENT (Clinic based)',
                'ophthalmology_surgeries_non_ga' => 'Ophthalmology Surgeries (Non G.A)',
                'ent_surgeries_non_ga' => 'ENT Surgeries (Non G.A)',
                'radiology_interventional' => 'Radiology - Interventional',
                'gastroenterology' => 'Gastroentrology',
                'office_clinical_orthopaedics' => 'Office/Clinical Orthopaedics',
                'office_clinical_gynaecology' => 'Office/Clinical Gynaecology',
                'cosmetic_aesthetic_non_surgical_invasive' => 'Cosmetic and Aesthetic - Non-surgical Invasive elective topical enhancement',
                'cosmetic_aesthetic_surgical_invasive' => 'Cosmetic and Aesthetic -Surgical, Invasive',
            ];

            if (isset($specialtyAreaMap[$specialtyArea])) {
                return $specialtyAreaMap[$specialtyArea];
            }
        }

        return 'N/A';
    }

    /**
     * Format field names for display
     */
    public static function formatFieldName($value)
    {
        if (!$value) {
            return 'N/A';
        }

        // Special cases for proper display
        $specialCases = [
            'locum_cover_only' => 'Locum Cover Only',
            'dental_practice' => 'Dental Practitioner',
            'medical_practice' => 'Medical Practitioner',
            'private_clinic' => 'Private Clinic',
            'private_hospital' => 'Private Hospital',
            'medical_specialist' => 'Medical Specialist',
            'general_practitioner' => 'General Practitioner',
            'low_risk_specialist' => 'Low Risk Specialist',
            'medium_risk_specialist' => 'Medium Risk Specialist',
            'lecturer_trainee' => 'Lecturer/Trainee',
            'non_practicing' => 'Non-Practicing',
        ];

        return $specialCases[$value] ?? ucfirst(str_replace('_', ' ', $value));
    }
}
