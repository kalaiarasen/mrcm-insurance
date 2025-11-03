<script>
    document.addEventListener('DOMContentLoaded', function() {
        const professionalIndemnityType = document.getElementById('professionalIndemnityType');
        const employmentStatusSection = document.getElementById('employmentStatusSection');
        const employmentStatus = document.getElementById('employmentStatus');
        const specialtySection = document.getElementById('specialtySection');
        const specialtyArea = document.getElementById('specialtyArea');
        const coverTypeSection = document.getElementById('coverTypeSection');
        const coverType = document.getElementById('coverType');
        const practiceAreaSection = document.getElementById('practiceAreaSection');
        const practiceArea = document.getElementById('practiceArea');
        const locumPracticeSection = document.getElementById('locumPracticeSection');
        const locumPracticeLocation = document.getElementById('locumPracticeLocation');
        const serviceTypeSection = document.getElementById('serviceTypeSection');
        const serviceTypeSelection = document.getElementById('serviceTypeSelection');
        const serviceDefinitionSection = document.getElementById('serviceDefinitionSection');

        professionalIndemnityType.addEventListener('change', function() {
            const selectedValue = this.value;
            
            resetAllFields();
            hideAllSections();
            
            if (selectedValue === 'medical_practice' || selectedValue === 'dental_practice') {
                employmentStatusSection.style.display = 'block';
                employmentStatus.required = true;
                updateEmploymentStatusOptions(selectedValue);
            }
        });

        employmentStatus.addEventListener('change', function() {
            const professionalType = professionalIndemnityType.value;
            const employmentType = this.value;
            
            resetFieldsFromSpecialty();
            hideAllSectionsFromEmployment();
            
            if (employmentType) {
                specialtySection.style.display = 'block';
                specialtyArea.required = true;
                updateSpecialtyOptions(professionalType, employmentType);
            }
        });

        specialtyArea.addEventListener('change', function() {
            const selectedValue = this.value;
            const professionalType = professionalIndemnityType.value;
            const employmentType = employmentStatus.value;
            
            resetFieldsFromCoverType();
            hideAllSectionsFromSpecialty();
            
            if (selectedValue) {
                if (selectedValue === 'lecturer_trainee') {

                } else {
                    coverTypeSection.style.display = 'block';
                    coverType.required = true;
                    updateCoverTypeOptions(professionalType, employmentType, selectedValue);
                }
            }
        });

        coverType.addEventListener('change', function() {
            const selectedValue = this.value;
            const employmentType = employmentStatus.value;
            const specialtyType = specialtyArea.value;
            const professionalType = professionalIndemnityType.value;
            
            resetFieldsFromPracticeArea();
            hideAllSectionsFromCoverType();
            
            if (selectedValue) {
                if (professionalType === 'dental_practice' && specialtyType === 'dentist_specialist' && selectedValue === 'dental_specialist_oral_maxillofacial_surgery') {
                    serviceTypeSection.style.display = 'block';
                    serviceTypeSelection.required = true;
                    updateDentalSpecialistFieldOptions();
                }
                else if (professionalType === 'dental_practice' && specialtyType === 'dentist_specialist' && selectedValue === 'dental_specialists') {
                }
                else if (professionalType === 'medical_practice' && employmentType === 'private' && specialtyType === 'general_practitioner') {
                    const servicesWithDefinitions = [
                        'core_services',
                        'core_services_with_procedures',
                        'general_practitioner_with_obstetrics',
                        'cosmetic_aesthetic_non_invasive',
                        'cosmetic_aesthetic_non_surgical_invasive'
                    ];
                    
                    if (servicesWithDefinitions.includes(selectedValue)) {
                        showServiceDefinition(selectedValue);
                    }
                    
                }
                else if (selectedValue === 'locum_cover') {
                    locumPracticeSection.style.display = 'block';
                    locumPracticeLocation.required = true;
                } else if (selectedValue === 'general_cover') {
                    serviceTypeSection.style.display = 'block';
                    serviceTypeSelection.required = true;
                    // Check if it's dental or medical practice
                    if (professionalType === 'dental_practice' && specialtyType === 'general_dentist') {
                        updateDentalServiceOptions('general_cover');
                    } else {
                        updateServiceTypeOptions('general_cover');
                    }
                } else if (selectedValue === 'locum_cover_only') {
                    // For dental locum cover only
                    serviceTypeSection.style.display = 'block';
                    serviceTypeSelection.required = true;
                    updateDentalServiceOptions('locum_cover_only');
                }
                else if (professionalType === 'dental_practice' && specialtyType === 'general_dentist') {
                    // Handle private dental practice (general_dental_practice, general_dental_practitioners)
                    const servicesWithDefinitions = [
                        'general_dental_practice',
                        'general_dental_practitioners'
                    ];
                    
                    if (servicesWithDefinitions.includes(selectedValue)) {
                        showServiceDefinition(selectedValue);
                    }
                    
                    setTimeout(() => {
                    }, 100);
                }
                else if (selectedValue === 'low_risk_specialist' || selectedValue === 'medium_risk_specialist') {
                    serviceTypeSection.style.display = 'block';
                    serviceTypeSelection.required = true;
                    updateSpecialistServiceOptions(selectedValue);
                } else {
                    practiceAreaSection.style.display = 'block';
                    practiceArea.required = true;
                }
                
                // Update locum extension visibility when cover type changes (for Private GP path)
                if (typeof window.updateLocumExtensionVisibility === 'function') {
                    window.updateLocumExtensionVisibility();
                }
            }
        });

        locumPracticeLocation.addEventListener('change', function() {
            const selectedValue = this.value;
            
            resetFieldsFromServiceType();
            hideAllSectionsFromServiceType();
            
            if (selectedValue) {
                serviceTypeSection.style.display = 'block';
                serviceTypeSelection.required = true;
                updateServiceTypeOptions('locum_cover', selectedValue);
            }
        });

        serviceTypeSelection.addEventListener('change', function() {
            const selectedValue = this.value;
            const coverTypeValue = coverType.value;
            const professionalType = professionalIndemnityType.value;
            const specialtyType = specialtyArea.value;
            
            resetFieldsFromServiceType();
            hideAllSectionsFromServiceType();
            
            if (selectedValue) {
                if (professionalType === 'dental_practice' && specialtyType === 'dentist_specialist' && 
                    coverTypeValue === 'dental_specialist_oral_maxillofacial_surgery') {
                } else {
                    const servicesWithDefinitions = [
                        'office_clinical_orthopaedics',
                        'cosmetic_aesthetic_surgical_invasive', 
                        'ophthalmology_surgeries_non_ga',
                        'core_services',
                        'core_services_with_procedures',
                        'general_practitioner_with_obstetrics',
                        'cosmetic_aesthetic_non_invasive',
                        'cosmetic_aesthetic_non_surgical_invasive',
                        'general_practice',
                        'general_practice_with_specialized_procedures',
                        'general_dental_practice',
                        'general_dental_practitioners'
                    ];
                    
                    if (servicesWithDefinitions.includes(selectedValue)) {
                        showServiceDefinition(selectedValue);
                    }
                    
                }
                
                // Update liability limit options when service type changes
                if (typeof window.autoSetLiabilityLimit === 'function') {
                    window.autoSetLiabilityLimit(selectedValue);
                }
                
                // Update locum extension visibility when service type changes
                if (typeof window.updateLocumExtensionVisibility === 'function') {
                    window.updateLocumExtensionVisibility();
                }
            }
        });

        practiceArea.addEventListener('change', function() {
            const selectedValue = this.value;
            
            resetFieldsFromCoverageDetails();
            hideAllSectionsFromCoverageDetails();
            
            if (selectedValue) {
            }
        });

         function updateServiceTypeOptions(coverType, locumLocation = null) {
            const serviceSelect = document.getElementById('serviceTypeSelection');
            
            if (coverType === 'locum_cover') {
                if (locumLocation === 'private_clinic') {
                    serviceSelect.innerHTML = `
                        <option value="">Select</option>
                        <option value="core_services">Core Services</option>
                        <option value="core_services_with_procedures">Core Services with procedures</option>
                    `;
                } else if (locumLocation === 'private_hospital') {
                    serviceSelect.innerHTML = `
                        <option value="">Select</option>
                        <option value="general_practitioner_private_hospital_outpatient">Outpatient Service</option>
                        <option value="general_practitioner_private_hospital_emergency">Emergency Department</option>
                    `;
                }
            } else if (coverType === 'general_cover') {
                serviceSelect.innerHTML = `
                    <option value="">Select</option>
                    <option value="core_services">Core Services</option>
                    <option value="core_services_with_procedures">Core Services with procedures</option>
                    <option value="general_practitioner_private_hospital_outpatient">General Practitioner in Private Hospital - Outpatient Services</option>
                    <option value="general_practitioner_private_hospital_emergency">General Practitioner in Private Hospital– Emergency Department</option>
                    <option value="general_practitioner_with_obstetrics">General Practitioner with Obstetrics</option>
                    <option value="cosmetic_aesthetic_non_invasive">Cosmetic & Aesthetic – Non - Invasive Elective Topical Enhancement</option>
                    <option value="cosmetic_aesthetic_non_surgical_invasive">Cosmetic & Aesthetic – Non - Surgical Invasive Elective Topical Enhancement</option>
                `;
            }
        }

        function showServiceDefinition(serviceType) {
            const definitionContent = document.getElementById('definitionContent');
            
            if (serviceType === 'core_services') {
                definitionContent.innerHTML = `
                    <h6>Definition / Information</h6>
                    <ol>
                        <li>History taking, Examination, and Diagnosis.</li>
                        <li>Prescription, Injections IM & IV, and IV Drips in Emergencies.</li>
                        <li>Immunizations, I&D, T&S under Local Anesthesia. Simple Removal of Foreign Bodies from ENT & Eye</li>
                        <li>Neubelisation.</li>
                        <li>CPR.</li>
                        <li>Medical Examinations / Screenings.</li>
                        <li>Urine and Blood Testings / Analysis.</li>
                        <li>Plain Xrays.</li>
                        <li>Ante-Natal Screenings up to 24 weeks. Pap Smears.</li>
                        <li>Family Planning Advice and Prescriptions.</li>
                        <li>Other similar services traditionally carried out by General Practitioners</li>
                    </ol>
                `;
                serviceDefinitionSection.style.display = 'block';
            } else if (serviceType === 'general_dental_practice') {
                definitionContent.innerHTML = `
                    <h6>Definition / Information</h6>
                    <ol>
                        <li>Complete dental examinations and diagnosis of disease including x-rays</li>
                        <li>Preventive dentistry (e.g cleanings, oral hygiene instruction, fluoride treatments, fissure sealants, scaling)</li>
                        <li>Extractions, fillings, crowns, veneers, bridges, dentures</li>
                        <li>Minor oral surgeries (e.g. laserations, gum injuries, broken tooths, simple root canal treatment and wisdom tooth extractions)<br />
                            <strong style="font-weight:bold">*Please choose 2 million cover if you do minor oral surgeries.</strong>
                        </li>
                    </ol>
                `;
                serviceDefinitionSection.style.display = 'block';
            } else if (serviceType === 'general_dental_practitioners') {
                definitionContent.innerHTML = `
                    <h6>Definition / Information</h6>
                    <strong class="text-dark">Accredited specialized procedures includes:</strong>
                    <ol>
                        <li>Braces</li>
                        <li>Periodontics</li>
                        <li>Endodontics</li>
                        <li>Implants</li>
                        <li>Orthodontics</li>
                        <li>Oral Surgeries</li>
                    </ol>
                `;
                serviceDefinitionSection.style.display = 'block';
            } else if (serviceType === 'general_practice') {
                definitionContent.innerHTML = `
                    <h6>Definition / Information</h6>
                    <ol>
                        <li>Complete dental examinations and diagnosis of disease including x-rays</li>
                        <li>Preventive dentistry (e.g cleanings, oral hygiene instruction, fluoride treatments, fissure sealants, scaling)</li>
                        <li>Extractions, fillings, crowns, veneers, bridges, dentures</li>
                    </ol>
                `;
                serviceDefinitionSection.style.display = 'block';
            } else if (serviceType === 'general_practice_with_specialized_procedures') {
                definitionContent.innerHTML = `
                    <h6>Definition / Information</h6>
                    <ol>
                        <li>Braces</li>
                        <li>Periodontics</li>
                        <li>Endodontics</li>
                        <li>Implants</li>
                        <li>Orthodontics</li>
                        <li>Oral Surgeries</li>
                    </ol>
                `;
                serviceDefinitionSection.style.display = 'block';
            }
            else if (serviceType === 'core_services_with_procedures') {
                definitionContent.innerHTML = `
                    <h6>Definition / Information</h6>
                    <ol>
                        <li>Removal of Ingrowing Toe Nails.</li>
                        <li>Excisions of Lumps & Bumps (Non-Facial Warts, Cysts, Lipomas, Granulomas)</li>
                        <li>Insertions and Removals of IUCDs</li>
                        <li>Cortisone Injections. ( Tendonitis, Teno-synovitis, Plantar Fascitis )</li>
                        <li>Immobilizations of Undisplaced Fractures of Metacarpal and Phalangeal Joints.</li>
                        <li>Circumcision</li>
                        <li>Other similar procedures traditionally carried out by General Practitioners</li>
                    </ol>
                `;
                serviceDefinitionSection.style.display = 'block';
            } else if (serviceType === 'general_practitioner_with_obstetrics') {
                definitionContent.innerHTML = `
                    <h6>Definition / Information</h6>
                    <p>36 weeks (full term of pregnancy) exclude deliveries</p>
                `;
                serviceDefinitionSection.style.display = 'block';
            } else if (serviceType === 'cosmetic_aesthetic_non_invasive') {
                definitionContent.innerHTML = `
                    <h6>Definition / Information</h6>
                    <p>Non-invasive procedures: External applications or treatment procedures that are carried out without creating a break in the skin or penetration of the integument. They target the epidermis only.</p>
                    <ul>
                        <li>Superficial chemical peels</li>
                        <li>Microdermabrasion</li>
                        <li>Intense pulsed light</li>
                    </ul>
                `;
                serviceDefinitionSection.style.display = 'block';
            } else if (serviceType === 'cosmetic_aesthetic_non_surgical_invasive') {
                definitionContent.innerHTML = `
                    <h6>Definition / Information</h6>
                    <p>Minimally invasive procedures: Treatment procedures that induce minimal damage to the tissues at the point of entry of instruments. These procedures involve penetration or transgression of integument but are limited to the sub-dermis and subcutaneous fat; not extending beyond the superficial musculo- aponeurotic layer of the face and neck, or beyond the superficial fascial layer of the torso and limbs.</p>
                    <strong class="text-dark">They are limited to the following procedures:</strong>
                    <ul>
                        <li>Chemical peel (Medium depth)</li>
                        <li>Botulinum toxin injection</li>
                        <li>Filler injection - excluding silicone and fat</li>
                        <li>Skin tightening procedures-up to upper dermis (radiofrequency, infrared, ultrasound and other devices)</li>
                        <li>Superficial sclerotherapy</li>
                        <li>Lasers for treating skin pigmentation</li>
                        <li>Lasers for treating benign skin lesions</li>
                        <li>Lasers for skin rejuvenation (including non-ablative)</li>
                        <li>Lasers for hair removal</li>
                    </ul>
                `;
                serviceDefinitionSection.style.display = 'block';
            } else if (serviceType === 'office_clinical_orthopaedics') {
                definitionContent.innerHTML = `
                    <h6>Definition / Information</h6>
                    <p><strong>Jobscope:</strong></p>
                    <ul>
                        <li>All excision biopsy of lumps under local</li>
                        <li>All nail/ nail bed procedures</li>
                        <li>administration of local anesthesia</li>
                        <li>Arthrocentesis and joint or soft tissue injections</li>
                        <li>Application of splints or casts</li>
                        <li>Simple amputation under local</li>
                        <li>Tendon/ nerve entrapment release</li>
                        <li>Incision and drainage of soft tissue infection</li>
                        <li>Closed reduction and immobilization of fracture and dislocation</li>
                        <li>Debridement of soft tissue and closer of wound</li>
                        <li>Removal of foreign bodies under local</li>
                        <li>Repair of muscle/ tendon under local</li>
                        <li>Use of fluoroscopy (sedation or local anesthesia procedures)</li>
                        <li>Tissue flap under local</li>
                        <li>Manipulation of joint under sedation/local anesthesia</li>
                    </ul>
                `;
                serviceDefinitionSection.style.display = 'block';
            } else if (serviceType === 'cosmetic_aesthetic_surgical_invasive') {
                definitionContent.innerHTML = `
                    <h6>Definition / Information</h6>
                    <p>Surgical includes excisions of warts, mole, scars and other External Cosmetic Surgery under L.A.</p>
                `;
                serviceDefinitionSection.style.display = 'block';
            } else if (serviceType === 'ophthalmology_surgeries_non_ga') {
                definitionContent.innerHTML = `
                    <h6>Definition / Information</h6>
                    <p>Cataract etc under L.A. (Non G.A.)</p>
                `;
                serviceDefinitionSection.style.display = 'block';
            } else if (serviceType === 'general_practitioner_private_hospital_outpatient' || serviceType === 'general_practitioner_private_hospital_emergency') {
                serviceDefinitionSection.style.display = 'none';
            } else {
                serviceDefinitionSection.style.display = 'none';
            }
        }

        function resetAllFields() {
            employmentStatus.value = '';
            specialtyArea.value = '';
            coverType.value = '';
            serviceTypeSelection.value = '';
            locumPracticeLocation.value = '';
            practiceArea.value = '';
        }

        function resetFieldsFromSpecialty() {
            specialtyArea.value = '';
            coverType.value = '';
            serviceTypeSelection.value = '';
            locumPracticeLocation.value = '';
            practiceArea.value = '';
        }

        function resetFieldsFromCoverType() {
            coverType.value = '';
            serviceTypeSelection.value = '';
            locumPracticeLocation.value = '';
            practiceArea.value = '';
        }

        function resetFieldsFromServiceType() {
            practiceArea.value = '';
        }

        function resetFieldsFromPracticeArea() {
            practiceArea.value = '';
        }

        function resetFieldsFromCoverageDetails() {
        }

        function hideAllSections() {
            employmentStatusSection.style.display = 'none';
            specialtySection.style.display = 'none';
            coverTypeSection.style.display = 'none';
            serviceTypeSection.style.display = 'none';
            serviceDefinitionSection.style.display = 'none';
            locumPracticeSection.style.display = 'none';
            practiceAreaSection.style.display = 'none';
            
            employmentStatus.required = false;
            specialtyArea.required = false;
            coverType.required = false;
            serviceTypeSelection.required = false;
            locumPracticeLocation.required = false;
            practiceArea.required = false;
        }

        function hideAllSectionsFromEmployment() {
            specialtySection.style.display = 'none';
            coverTypeSection.style.display = 'none';
            serviceTypeSection.style.display = 'none';
            serviceDefinitionSection.style.display = 'none';
            locumPracticeSection.style.display = 'none';
            practiceAreaSection.style.display = 'none';
            
            specialtyArea.required = false;
            coverType.required = false;
            serviceTypeSelection.required = false;
            locumPracticeLocation.required = false;
            practiceArea.required = false;
        }

        function hideAllSectionsFromSpecialty() {
            coverTypeSection.style.display = 'none';
            serviceTypeSection.style.display = 'none';
            serviceDefinitionSection.style.display = 'none';
            locumPracticeSection.style.display = 'none';
            practiceAreaSection.style.display = 'none';
            
            coverType.required = false;
            serviceTypeSelection.required = false;
            locumPracticeLocation.required = false;
            practiceArea.required = false;
        }

        function hideAllSectionsFromCoverType() {
            serviceTypeSection.style.display = 'none';
            serviceDefinitionSection.style.display = 'none';
            locumPracticeSection.style.display = 'none';
            practiceAreaSection.style.display = 'none';
            
            serviceTypeSelection.required = false;
            locumPracticeLocation.required = false;
            practiceArea.required = false;
        }

        function hideAllSectionsFromServiceType() {
            serviceDefinitionSection.style.display = 'none';
            practiceAreaSection.style.display = 'none';
            
            practiceArea.required = false;
        }

        function hideAllSectionsFromPracticeArea() {
            serviceDefinitionSection.style.display = 'none';
        }

        function hideAllSectionsFromCoverageDetails() {
        }

        function updateEmploymentStatusOptions(indemnityType) {
            const employmentSelect = document.getElementById('employmentStatus');
            
            employmentSelect.innerHTML = '<option value="">Select Employment Status</option>';
            
            if (indemnityType === 'medical_practice') {
                employmentSelect.innerHTML += `
                    <option value="government">Government</option>
                    <option value="private">Private</option>
                    <option value="non_practicing">Non-Practicing</option>
                `;
            } else if (indemnityType === 'dental_practice') {
                employmentSelect.innerHTML += `
                    <option value="government">Government</option>
                    <option value="private">Private</option>
                `;
            }
        }

        function updateSpecialtyOptions(professionalType, employmentType) {
            const specialtySelect = document.getElementById('specialtyArea');
            
            specialtySelect.innerHTML = '<option value="">Select Specialty</option>';
            
            if (professionalType === 'medical_practice') {
                if (employmentType === 'government') {
                    specialtySelect.innerHTML += `
                        <option value="medical_officer">Medical Officer</option>
                        <option value="medical_specialist">Medical Specialist</option>
                    `;
                } else if (employmentType === 'private') {
                    specialtySelect.innerHTML += `
                        <option value="general_practitioner">General Practitioner</option>
                        <option value="medical_specialist">Medical Specialist</option>
                    `;
                } else if (employmentType === 'non_practicing') {
                    specialtySelect.innerHTML += `
                        <option value="lecturer_trainee">Lecturer/Trainee</option>
                    `;
                }
            } else if (professionalType === 'dental_practice') {
                if (employmentType === 'government' || employmentType === 'private') {
                    specialtySelect.innerHTML += `
                        <option value="general_dentist">General Dentist</option>
                        <option value="dentist_specialist">Dentist Specialist</option>
                    `;
                }
            }
        }

        function updateCoverTypeOptions(professionalType, employmentType, specialtyType) {
            const coverTypeSelect = document.getElementById('coverType');
            const coverTypeLabel = document.querySelector('label[for="coverType"]') || document.querySelector('#coverTypeSection p');
            
            if (specialtyType === 'dentist_specialist') {
                if (coverTypeLabel) {
                    coverTypeLabel.innerHTML = 'Your type of service <span class="text-danger">*</span>';
                }
                coverTypeSelect.innerHTML = '<option value="">Select Specialty</option>';
            } else if (professionalType === 'dental_practice') {
                if (coverTypeLabel) {
                    coverTypeLabel.innerHTML = 'Type of cover <span class="text-danger">*</span>';
                }
                coverTypeSelect.innerHTML = '<option value="">Select Type of Cover</option>';
            } else if (specialtyType === 'medical_specialist') {
                if (coverTypeLabel) {
                    coverTypeLabel.innerHTML = 'Select Your Specialist Category <span class="text-danger">*</span>';
                }
                coverTypeSelect.innerHTML = '<option value="">Select Your Specialist Category</option>';
            } else if (specialtyType === 'medical_officer') {
                if (coverTypeLabel) {
                    coverTypeLabel.innerHTML = 'Type of cover <span class="text-danger">*</span>';
                }
                coverTypeSelect.innerHTML = '<option value="">Select Type of Cover</option>';
            } else {
                if (coverTypeLabel) {
                    coverTypeLabel.innerHTML = 'Your type of service <span class="text-danger">*</span>';
                }
                coverTypeSelect.innerHTML = '<option value="">Select</option>';
            }
            
            if (professionalType === 'medical_practice') {
                if (employmentType === 'government') {
                    if (specialtyType === 'medical_officer') {
                        coverTypeSelect.innerHTML += `
                            <option value="locum_cover">Locum Cover</option>
                            <option value="general_cover">General Cover</option>
                        `;
                    } else if (specialtyType === 'medical_specialist') {
                        coverTypeSelect.innerHTML += `
                            <option value="low_risk_specialist">Low Risk Specialist</option>
                            <option value="medium_risk_specialist">Medium Risk Specialist</option>
                        `;
                    }
                } else if (employmentType === 'private') {
                    if (specialtyType === 'general_practitioner') {
                        coverTypeSelect.innerHTML += `
                            <option value="core_services">Core Services</option>
                            <option value="core_services_with_procedures">Core Services with procedures</option>
                            <option value="general_practitioner_private_hospital_outpatient">General Practitioner in Private Hospital - Outpatient Services</option>
                            <option value="general_practitioner_private_hospital_emergency">General Practitioner in Private Hospital– Emergency Department</option>
                            <option value="general_practitioner_with_obstetrics">General Practitioner with Obstetrics</option>
                            <option value="cosmetic_aesthetic_non_invasive">Cosmetic & Aesthetic – Non - Invasive Elective Topical Enhancement</option>
                            <option value="cosmetic_aesthetic_non_surgical_invasive">Cosmetic & Aesthetic – Non - Surgical Invasive Elective Topical Enhancement</option>
                        `;
                    } else if (specialtyType === 'medical_specialist') {
                        coverTypeSelect.innerHTML += `
                            <option value="low_risk_specialist">Low Risk Specialist</option>
                            <option value="medium_risk_specialist">Medium Risk Specialist</option>
                        `;
                    }
                }
            } else if (professionalType === 'dental_practice') {
                if (specialtyType === 'dentist_specialist') {
                    coverTypeSelect.innerHTML += `
                        <option value="dental_specialists">Dental Specialists</option>
                        <option value="dental_specialist_oral_maxillofacial_surgery">Dental Specialist practicing Oral and Maxillofacial Surgery</option>
                    `;
                } else if (specialtyType === 'general_dentist') {
                    if (employmentType === 'government') {
                        coverTypeSelect.innerHTML += `
                            <option value="locum_cover_only">Locum cover only</option>
                            <option value="general_cover">General Cover</option>
                        `;
                    } else if (employmentType === 'private') {
                        coverTypeSelect.innerHTML += `
                            <option value="general_dental_practice">General Dental Practice</option>
                            <option value="general_dental_practitioners">General Dental Practitioners, practising accredited specialised procedures</option>
                        `;
                    }
                }
            }
        }

        function updateSpecialistServiceOptions(specialistCategory) {
            const serviceSelect = document.getElementById('serviceTypeSelection');
            
            if (specialistCategory === 'low_risk_specialist') {
                serviceSelect.innerHTML = `
                    <option value="">Select</option>
                    <option value="occupational_health_physicians">Occupational Health Physicians / Family Physicians</option>
                    <option value="general_physicians">General Physicians</option>
                    <option value="dermatology_non_cosmetic">Dermatology - Non - Cosmetic</option>
                    <option value="infections_diseases">Infections Diseases</option>
                    <option value="pathology">Pathology</option>
                    <option value="psychiatry">Psychiatry</option>
                    <option value="endocrinology">Endocrinology</option>
                    <option value="rehab_medicine">Rehab, medicine</option>
                    <option value="paediatrics_non_neonatal">Paediatrics - (Non Neonatal)</option>
                    <option value="geriatrics">Geriatrics</option>
                    <option value="haemotology">Haemotology</option>
                    <option value="immunology">Immunology</option>
                    <option value="nephrology">Nephrology</option>
                    <option value="nuclear_medicine">Nuclear medicine</option>
                    <option value="neurology">Neurology</option>
                    <option value="radiology_non_interventional">Radiology(Non Interventional)</option>
                `;
            } else if (specialistCategory === 'medium_risk_specialist') {
                serviceSelect.innerHTML = `
                    <option value="">Select</option>
                    <option value="ophthalmology_office_procedures">Ophthalmology / Office procedures</option>
                    <option value="office_ent_clinic_based">Office ENT(Clinic based)</option>
                    <option value="ophthalmology_surgeries_non_ga">Ophthalmology Surgeries (Non G.A.)</option>
                    <option value="ent_surgeries_non_ga">ENT Surgeries(Non G.A.)</option>
                    <option value="radiology_interventional">Radiology - Interventional</option>
                    <option value="gastroenterology">Gastroenterology</option>
                    <option value="office_clinical_orthopaedics">Office / Clinical Orthopaedics</option>
                    <option value="office_clinical_gynaecology">Office / Clinical Gynaecology</option>
                    <option value="cosmetic_aesthetic_non_surgical_invasive">Cosmetic and Aesthetic (Non-surgical Invasive elective topical enhancement)</option>
                    <option value="cosmetic_aesthetic_surgical_invasive">Cosmetic and Aesthetic ( Surgical, Invasive)</option>
                `;
            }
        }

        function updateDentalServiceOptions(coverType) {
            const serviceSelect = document.getElementById('serviceTypeSelection');
            const serviceTypeLabel = document.querySelector('label[for="serviceTypeSelection"]') || document.querySelector('#serviceTypeSection p');
            
            console.log('updateDentalServiceOptions called with:', coverType, 'in restoration');
            
            if (serviceTypeLabel) {
                serviceTypeLabel.innerHTML = 'Your type of service <span class="text-danger">*</span>';
            }
            
            if (coverType === 'general_cover') {
                // For General Cover - show General Dentist Practice options
                serviceSelect.innerHTML = `
                    <option value="">Select</option>
                    <option value="general_dental_practice">General Dentist Practice</option>
                    <option value="general_dental_practitioners">General Dentist Practice, practicing accredited specialised procedures</option>
                `;
                console.log('Service options populated for general_cover. Current value:', serviceSelect.value);
            } else if (coverType === 'locum_cover_only') {
                // For Locum Cover Only - show General Practice options
                serviceSelect.innerHTML = `
                    <option value="">Select</option>
                    <option value="general_practice">General Practice</option>
                    <option value="general_practice_with_specialized_procedures">General Practice with Specialized Procedures</option>
                `;
                console.log('Service options populated for locum_cover_only. Current value:', serviceSelect.value);
            }
        }

        function updateDentalSpecialistFieldOptions() {
            const serviceSelect = document.getElementById('serviceTypeSelection');
            const serviceTypeLabel = document.querySelector('label[for="serviceTypeSelection"]') || document.querySelector('#serviceTypeSection p');
            
            if (serviceTypeLabel) {
                serviceTypeLabel.innerHTML = 'Please select your field of practice <span class="text-danger">*</span>';
            }
            
            serviceSelect.innerHTML = `
                <option value="">Select</option>
                <option value="clinic_based_non_general_anaesthetic">Clinic based Non-General Anaesthetic Dental only procedures</option>
                <option value="hospital_based_full_fledged_omfs">Hospital-based full-fledged OMFS</option>
            `;
        }
    
    // Enhanced function to restore healthcare services form state without timeouts
    function restoreHealthcareServicesState() {
        const savedData = loadFormData(2);
        
        if (Object.keys(savedData).length === 0) {
            console.log('No saved healthcare data to restore');
            return;
        }
        
        console.log('Restoring healthcare services state:', savedData);
        
        // Temporarily disable the change event listeners during restoration
        const isRestoring = true;
        
        // Step 1: Set professional indemnity type and manually trigger updates
        if (savedData.professional_indemnity_type) {
            const professionalIndemnitySelect = document.getElementById('professionalIndemnityType');
            if (professionalIndemnitySelect) {
                professionalIndemnitySelect.value = savedData.professional_indemnity_type;
                
                // Manually update employment status options
                updateEmploymentStatusOptions(savedData.professional_indemnity_type);
                
                // Show employment status section if needed
                if (savedData.professional_indemnity_type === 'medical_practice' || savedData.professional_indemnity_type === 'dental_practice') {
                    employmentStatusSection.style.display = 'block';
                    employmentStatus.required = true;
                }
            }
        }
        
        // Step 2: Set employment status and manually trigger updates
        if (savedData.employment_status) {
            const employmentStatusSelect = document.getElementById('employmentStatus');
            if (employmentStatusSelect) {
                employmentStatusSelect.value = savedData.employment_status;
                
                // Manually update specialty options
                updateSpecialtyOptions(savedData.professional_indemnity_type, savedData.employment_status);
                
                // Show specialty section
                specialtySection.style.display = 'block';
                specialtyArea.required = true;
            }
        }
        
        // Step 3: Set specialty area and manually trigger updates
        if (savedData.specialty_area) {
            const specialtyAreaSelect = document.getElementById('specialtyArea');
            if (specialtyAreaSelect) {
                specialtyAreaSelect.value = savedData.specialty_area;
                
                // Manually update cover type options
                updateCoverTypeOptions(savedData.professional_indemnity_type, savedData.employment_status, savedData.specialty_area);
                
                // Show cover type section unless it's lecturer_trainee
                if (savedData.specialty_area !== 'lecturer_trainee') {
                    coverTypeSection.style.display = 'block';
                    coverType.required = true;
                }
            }
        }
        
        // Step 4: Set cover type and manually trigger updates
        if (savedData.cover_type) {
            const coverTypeSelect = document.getElementById('coverType');
            if (coverTypeSelect) {
                coverTypeSelect.value = savedData.cover_type;
                
                console.log('Restoring cover_type:', savedData.cover_type);
                
                // Manually handle different cover type scenarios
                const professionalType = savedData.professional_indemnity_type;
                const employmentType = savedData.employment_status;
                const specialtyType = savedData.specialty_area;
                const selectedValue = savedData.cover_type;
                
                console.log('Professional:', professionalType, 'Employment:', employmentType, 'Specialty:', specialtyType);
                
                if (professionalType === 'dental_practice' && specialtyType === 'dentist_specialist' && selectedValue === 'dental_specialist_oral_maxillofacial_surgery') {
                    serviceTypeSection.style.display = 'block';
                    serviceTypeSelection.required = true;
                    updateDentalSpecialistFieldOptions();
                }
                else if (professionalType === 'dental_practice' && specialtyType === 'dentist_specialist' && selectedValue === 'dental_specialists') {
                    // Dental Specialists - no additional fields needed
                }
                else if (professionalType === 'medical_practice' && employmentType === 'private' && specialtyType === 'general_practitioner') {
                    const servicesWithDefinitions = [
                        'core_services',
                        'core_services_with_procedures',
                        'general_practitioner_with_obstetrics',
                        'cosmetic_aesthetic_non_invasive',
                        'cosmetic_aesthetic_non_surgical_invasive'
                    ];
                    
                    if (servicesWithDefinitions.includes(selectedValue)) {
                        showServiceDefinition(selectedValue);
                    }
                }
                else if (selectedValue === 'locum_cover') {
                    locumPracticeSection.style.display = 'block';
                    locumPracticeLocation.required = true;
                } else if (selectedValue === 'general_cover') {
                    serviceTypeSection.style.display = 'block';
                    serviceTypeSelection.required = true;
                    // Check if it's dental or medical practice
                    if (professionalType === 'dental_practice' && specialtyType === 'general_dentist') {
                        console.log('Restoration: Calling updateDentalServiceOptions for general_cover');
                        updateDentalServiceOptions('general_cover');
                    } else {
                        updateServiceTypeOptions('general_cover');
                    }
                } else if (selectedValue === 'locum_cover_only') {
                    // For dental locum cover only
                    console.log('Restoration: Handling locum_cover_only');
                    serviceTypeSection.style.display = 'block';
                    serviceTypeSelection.required = true;
                    updateDentalServiceOptions('locum_cover_only');
                }
                else if (professionalType === 'dental_practice' && specialtyType === 'general_dentist') {
                    // Handle private dental practice (general_dental_practice, general_dental_practitioners)
                    const servicesWithDefinitions = [
                        'general_dental_practice',
                        'general_dental_practitioners'
                    ];
                    
                    if (servicesWithDefinitions.includes(selectedValue)) {
                        showServiceDefinition(selectedValue);
                    }
                }
                else if (selectedValue === 'low_risk_specialist' || selectedValue === 'medium_risk_specialist') {
                    serviceTypeSection.style.display = 'block';
                    serviceTypeSelection.required = true;
                    updateSpecialistServiceOptions(selectedValue);
                } else {
                    practiceAreaSection.style.display = 'block';
                    practiceArea.required = true;
                }
            }
        }
        
        // Step 5: Set locum practice location if applicable
        if (savedData.locum_practice_location) {
            const locumSelect = document.getElementById('locumPracticeLocation');
            if (locumSelect && locumPracticeSection.style.display === 'block') {
                locumSelect.value = savedData.locum_practice_location;
                
                // Manually update service type options for locum
                serviceTypeSection.style.display = 'block';
                serviceTypeSelection.required = true;
                updateServiceTypeOptions('locum_cover', savedData.locum_practice_location);
            }
        }
        
        // Step 6: Set service type selection if applicable
        if (savedData.service_type) {
            const serviceTypeSelect = document.getElementById('serviceTypeSelection');
            if (serviceTypeSelect && serviceTypeSection.style.display === 'block') {
                serviceTypeSelect.value = savedData.service_type;
                
                // Show service definition if applicable
                const servicesWithDefinitions = [
                    'office_clinical_orthopaedics',
                    'cosmetic_aesthetic_surgical_invasive', 
                    'ophthalmology_surgeries_non_ga',
                    'core_services',
                    'core_services_with_procedures',
                    'general_practitioner_with_obstetrics',
                    'cosmetic_aesthetic_non_invasive',
                    'cosmetic_aesthetic_non_surgical_invasive',
                    'general_practice',
                    'general_practice_with_specialized_procedures',
                    'general_dental_practice',
                    'general_dental_practitioners'
                ];
                
                if (servicesWithDefinitions.includes(savedData.service_type)) {
                    showServiceDefinition(savedData.service_type);
                }
            }
        }
        
        // Step 7: Set practice area if applicable
        if (savedData.practice_area) {
            const practiceAreaSelect = document.getElementById('practiceArea');
            if (practiceAreaSelect && practiceAreaSection.style.display === 'block') {
                practiceAreaSelect.value = savedData.practice_area;
            }
        }
        
        console.log('Healthcare services state restoration completed instantly');
    }

    // Make the function globally available so it can be called from main navigation
    window.restoreHealthcareServicesState = restoreHealthcareServicesState;

    function preventPasswordAutofill() {
        const passwordFields = document.querySelectorAll('input[type="password"]');
        passwordFields.forEach(field => {
            setTimeout(() => {
                const savedData = loadFormData(1);
                if (savedData[field.name]) {
                    field.value = savedData[field.name];
                }
            }, 500);
            
            field.addEventListener('focus', function() {
                if (this.hasAttribute('data-browser-filled')) {
                    this.value = '';
                    this.removeAttribute('data-browser-filled');
                }
            });
        });
    }
    
    preventPasswordAutofill();
    
    setTimeout(preventPasswordAutofill, 1000);
    });
</script>