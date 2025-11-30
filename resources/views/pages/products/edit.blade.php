@extends('layouts.main')

@section('title', 'Edit Product')

@section('css')
    <!-- CKEditor for rich text editing -->
    <script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>
@endsection

@section('main_content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Edit Product</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                                </svg>
                            </a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                        <li class="breadcrumb-item active">Edit Product</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form id="productForm" action="{{ route('products.update', $product->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="card">
                        <div class="card-header pb-0">
                            <h5>Product Information</h5>
                        </div>
                        <div class="card-body">
                            <!-- Title -->
                            <div class="mb-3">
                                <label class="form-label" for="title">Product Title <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title', $product->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Type -->
                            <div class="mb-3">
                                <label class="form-label" for="type">Product Type <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type"
                                    name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="car_insurance"
                                        {{ old('type', $product->type) == 'car_insurance' ? 'selected' : '' }}>Car Insurance
                                    </option>
                                    <option value="rahmah_insurance"
                                        {{ old('type', $product->type) == 'rahmah_insurance' ? 'selected' : '' }}>Rahmah
                                        Insurance</option>
                                    <option value="hiking_insurance"
                                        {{ old('type', $product->type) == 'hiking_insurance' ? 'selected' : '' }}>Hiking
                                        Insurance</option>
                                    <option value="other" {{ old('type', $product->type) == 'other' ? 'selected' : '' }}>
                                        Other</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Coverage & Benefits -->
                            <div class="mb-3">
                                <label class="form-label" for="coverage_benefits">Coverage & Benefits</label>
                                <textarea class="form-control @error('coverage_benefits') is-invalid @enderror" id="coverage_benefits"
                                    name="coverage_benefits" rows="10">{{ old('coverage_benefits', $product->coverage_benefits) }}</textarea>
                                @error('coverage_benefits')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Use the rich text editor to format your content</small>
                            </div>

                            <!-- Notification Email -->
                            <div class="mb-3">
                                <label class="form-label" for="notification_email">Notification Email <span
                                        class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('notification_email') is-invalid @enderror"
                                    id="notification_email" name="notification_email"
                                    value="{{ old('notification_email', $product->notification_email) }}" required>
                                @error('notification_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Quotation requests will be sent to this email</small>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header pb-0">
                            <h5>Files & Documents</h5>
                        </div>
                        <div class="card-body">
                            <!-- Brochure Upload -->
                            <div class="mb-3">
                                <label class="form-label" for="brochure">Brochure Image (JPG)</label>
                                @if ($product->brochure_path)
                                    <div class="mb-2">
                                        <img src="{{ $product->brochure_url }}" alt="Current Brochure"
                                            style="max-width: 200px; max-height: 150px;">
                                        <p class="text-muted small">Current brochure</p>
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('brochure') is-invalid @enderror"
                                    id="brochure" name="brochure" accept=".jpg,.jpeg">
                                @error('brochure')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Recommended size: 1200x800px, Max 2MB. Leave empty to keep current
                                    brochure.</small>
                            </div>

                            <!-- PDF Upload -->
                            <div class="mb-3">
                                <label class="form-label" for="pdf">PDF Document</label>
                                @if ($product->pdf_path)
                                    <div class="mb-2">
                                        <a href="{{ $product->pdf_url }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fa fa-file-pdf"></i> View Current PDF
                                        </a>
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('pdf') is-invalid @enderror" id="pdf"
                                    name="pdf" accept=".pdf">
                                @error('pdf')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Max 10MB. Leave empty to keep current PDF.</small>
                            </div>

                            <!-- PDF Title -->
                            <div class="mb-3">
                                <label class="form-label" for="pdf_title">Download Button Text</label>
                                <input type="text" class="form-control @error('pdf_title') is-invalid @enderror"
                                    id="pdf_title" name="pdf_title" value="{{ old('pdf_title', $product->pdf_title) }}">
                                @error('pdf_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Text shown on the download button</small>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header pb-0">
                            <h5>Quotation Form Builder</h5>
                            <p class="text-muted">Create a custom form for quotation requests</p>
                        </div>
                        <div class="card-body">
                            <div id="formBuilder">
                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle"></i> Add fields to create your quotation request form
                                </div>
                                <div id="formFields"></div>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="addFormField()">
                                    <i class="fa fa-plus"></i> Add Field
                                </button>
                            </div>
                            <input type="hidden" id="form_fields" name="form_fields" value="">
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header pb-0">
                            <h5>Display Settings</h5>
                        </div>
                        <div class="card-body">
                            <!-- Display Order -->
                            <div class="mb-3">
                                <label class="form-label" for="display_order">Display Order</label>
                                <input type="number" class="form-control @error('display_order') is-invalid @enderror"
                                    id="display_order" name="display_order"
                                    value="{{ old('display_order', $product->display_order) }}" min="0">
                                @error('display_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Lower numbers appear first</small>
                            </div>

                            <!-- Is Active -->
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                    value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active (visible to customers)
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Update Product
                            </button>
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                <i class="fa fa-times"></i> Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            console.log('=== PRODUCT EDIT PAGE LOADED ===');

            // Initialize CKEditor
            let editor;
            ClassicEditor
                .create(document.querySelector('#coverage_benefits'), {
                    toolbar: {
                        items: [
                            'heading', '|',
                            'bold', 'italic', 'link', '|',
                            'bulletedList', 'numberedList', '|',
                            'indent', 'outdent', '|',
                            'blockQuote', 'insertTable', '|',
                            'undo', 'redo'
                        ]
                    },
                    table: {
                        contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
                    }
                })
                .then(newEditor => {
                    editor = newEditor;
                    // Set minimum height for the editing area
                    const editingView = editor.editing.view;
                    editingView.change(writer => {
                        writer.setStyle('min-height', '600px', editingView.document.getRoot());
                    });
                })
                .catch(error => {
                    console.error('CKEditor error:', error);
                });

            // Form Builder
            let fieldCounter = 0;
            const formFieldsContainer = document.getElementById('formFields');
            const existingFields = @json($product->form_fields ?? ['fields' => []]);

            console.log('Existing fields:', existingFields);

            // Make functions global so onclick can access them (DEFINE FIRST!)
            window.addFormField = function(existingField = null) {
                fieldCounter++;
                const fieldHtml = `
                    <div class="card mb-2" id="field_${fieldCounter}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">Field Type</label>
                                    <select class="form-select field-type" data-id="${fieldCounter}">
                                        <option value="text" ${existingField?.type === 'text' ? 'selected' : ''}>Text</option>
                                        <option value="email" ${existingField?.type === 'email' ? 'selected' : ''}>Email</option>
                                        <option value="phone" ${existingField?.type === 'phone' ? 'selected' : ''}>Phone</option>
                                        <option value="number" ${existingField?.type === 'number' ? 'selected' : ''}>Number</option>
                                        <option value="textarea" ${existingField?.type === 'textarea' ? 'selected' : ''}>Textarea</option>
                                        <option value="select" ${existingField?.type === 'select' ? 'selected' : ''}>Select</option>
                                        <option value="date" ${existingField?.type === 'date' ? 'selected' : ''}>Date</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Label</label>
                                    <input type="text" class="form-control field-label" placeholder="Full Name" value="${existingField?.label || ''}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control field-name" placeholder="full_name" value="${existingField?.name || ''}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Required</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input field-required" type="checkbox" ${existingField?.required ? 'checked' : ''}>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" class="btn btn-danger btn-sm w-100" onclick="removeField(${fieldCounter})">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="row mt-2 options-row" id="options_${fieldCounter}" style="display: ${existingField?.type === 'select' ? 'block' : 'none'};">
                                <div class="col-md-12">
                                    <label class="form-label">Options (comma-separated)</label>
                                    <input type="text" class="form-control field-options" placeholder="Option 1, Option 2, Option 3" value="${existingField?.options?.join(', ') || ''}">
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                formFieldsContainer.insertAdjacentHTML('beforeend', fieldHtml);

                // Add event listener for type change
                document.querySelector(`select.field-type[data-id="${fieldCounter}"]`).addEventListener(
                    'change',
                    function() {
                        const optionsRow = document.getElementById(`options_${this.dataset.id}`);
                        optionsRow.style.display = this.value === 'select' ? 'block' : 'none';
                    });
            };

            window.removeField = function(id) {
                document.getElementById(`field_${id}`).remove();
            };

            // NOW load existing fields (after functions are defined)
            if (existingFields.fields && existingFields.fields.length > 0) {
                console.log('Loading existing fields:', existingFields.fields.length);
                existingFields.fields.forEach(field => {
                    window.addFormField(field);
                });
            }

            // Before form submit, collect form fields
            const form = document.getElementById('productForm');
            console.log('Form found:', form);

            if (form) {
                form.addEventListener('submit', function(e) {
                    console.log('=== FORM SUBMITTING (EDIT) ===');
                    console.log('Number of field cards found:', document.querySelectorAll(
                        '#formFields .card').length);

                    const fields = [];
                    document.querySelectorAll('#formFields .card').forEach((card, index) => {
                        const type = card.querySelector('.field-type').value;
                        const label = card.querySelector('.field-label').value;
                        const name = card.querySelector('.field-name').value;
                        const required = card.querySelector('.field-required').checked;
                        const options = card.querySelector('.field-options')?.value || '';

                        console.log(`Field ${index}:`, {
                            type,
                            label,
                            name,
                            required,
                            options
                        });

                        if (label && name) {
                            const field = {
                                id: `field_${index + 1}`,
                                type: type,
                                label: label,
                                name: name,
                                required: required,
                                validation: required ? 'required' : 'nullable'
                            };

                            if (type === 'select' && options) {
                                field.options = options.split(',').map(opt => opt.trim());
                            }

                            fields.push(field);
                        }
                    });

                    const formFieldsData = JSON.stringify({
                        fields: fields
                    });
                    const hiddenInput = document.getElementById('form_fields');

                    console.log('Hidden input BEFORE setting:', hiddenInput);
                    console.log('Hidden input value BEFORE:', hiddenInput.value);

                    hiddenInput.value = formFieldsData;

                    console.log('Form fields data:', formFieldsData);
                    console.log('Hidden input value AFTER:', hiddenInput.value);
                    console.log('Hidden input name:', hiddenInput.name);

                    // Verify it's in the form
                    const formData = new FormData(this);
                    console.log('FormData form_fields:', formData.get('form_fields'));

                    // Let the form submit naturally - don't prevent default!
                });
            } else {
                console.error('Form not found!');
            }
        });
    </script>
@endsection
