<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\AgentCommissionController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ClaimsController;
use App\Http\Controllers\CustomerProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardSettingController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\MyPoliciesController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\PolicyHolderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuotationRequestController;
use App\Http\Controllers\YourActionController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\Api\PolicySubmissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::view('layout-light', 'starter_kit.color_version.layout_light')->name('layout_light');
Route::view('layout-dark', 'starter_kit.color_version.layout_dark')->name('layout_dark');
Route::view('box-layout', 'starter_kit.page_layout.box_layout')->name('box_layout');
Route::view('rtl-layout', 'starter_kit.page_layout.rtl_layout')->name('rtl_layout');
Route::view('hide-menu-on-scroll', 'starter_kit.hide_menu_on_scroll')->name('hide_menu_on_scroll');
Route::view('footer-light', 'starter_kit.footers.footer_light')->name('footer_light');
Route::view('footer-dark', 'starter_kit.footers.footer_dark')->name('footer_dark');
Route::view('footer-fixed', 'starter_kit.footers.footer_fixed')->name('footer_fixed');

Route::get('/', function () {
    // Ensure the 'login' route exists and is named 'login'
    return redirect()->route('login');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('policy-holders', [PolicyHolderController::class, 'index'])->name('policy-holder');
    Route::get('policy-holders/{user}/edit', [PolicyHolderController::class, 'edit'])->name('policy-holders.edit');
    Route::get('policy-holders/{user}', [PolicyHolderController::class, 'show'])->name('policy-holders.show');
    Route::put('policy-holders/{user}', [PolicyHolderController::class, 'update'])->name('policy-holders.update');
    Route::get('policy-holders/{user}/application/{application}', [PolicyHolderController::class, 'showApplication'])->name('policy-holders.application.show');
    
    // Agent Referral Management
    Route::post('policy-holders/search-by-code', [PolicyHolderController::class, 'searchByCode'])->name('policy-holders.search-by-code');
    Route::post('policy-holders/assign-agent', [PolicyHolderController::class, 'assignAgent'])->name('policy-holders.assign-agent');

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('client-policy/{id}', [DashboardController::class, 'showPolicy'])->name('client-policy.show');
    Route::post('client-policy/{id}/upload-payment', [DashboardController::class, 'uploadPayment'])->name('client-policy.upload-payment');
    
    Route::get('for-your-action', [YourActionController::class, 'index'])->name('for-your-action');
    Route::get('for-your-action/export', [YourActionController::class, 'exportExcel'])->name('for-your-action.export');
    Route::get('for-your-action/{id}', [YourActionController::class, 'show'])->name('for-your-action.show');
    Route::get('for-your-action/{id}/export-pdf', [YourActionController::class, 'exportPdf'])->name('for-your-action.export-pdf');
    Route::get('for-your-action/{id}/edit', [YourActionController::class, 'edit'])->name('for-your-action.edit');
    Route::put('for-your-action/{id}/update-status', [YourActionController::class, 'updateStatus'])->name('for-your-action.update-status');
    Route::post('for-your-action/{id}/upload-documents', [YourActionController::class, 'uploadDocuments'])->name('for-your-action.upload-documents');
    Route::post('for-your-action/{id}/upload-payment', [YourActionController::class, 'uploadPayment'])->name('for-your-action.upload-payment');
    Route::post('for-your-action/{id}/reupload-ci', [YourActionController::class, 'reuploadCI'])->name('for-your-action.reupload-ci');
    Route::delete('for-your-action/{id}/remove-ci', [YourActionController::class, 'removeCI'])->name('for-your-action.remove-ci');
    Route::put('for-your-action/{id}/update', [YourActionController::class, 'update'])->name('for-your-action.update');
    Route::delete('for-your-action/{id}', [YourActionController::class, 'destroy'])->name('for-your-action.destroy');

    Route::redirect('settings', 'settings/profile');

    Route::resource('claims', ClaimsController::class);
    Route::get('claims/{claim}/documents/{document}/download', [ClaimsController::class, 'downloadDocument'])->name('claims.documents.download');
    Route::put('claims/{claim}/status', [ClaimsController::class, 'updateStatus'])->name('claims.updateStatus');
    Route::get('new-policy', [PolicyController::class, 'newPolicy'])->name('new-policy');

    Route::post('policies/submit', [PolicySubmissionController::class, 'submit'])
        ->name('policies.submit');

    Route::resource('announcements', AnnouncementController::class);
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('agents', AgentController::class);
    Route::post('agents/{agent}/approve', [AgentController::class, 'approve'])->name('agents.approve');
    Route::post('agents/{agent}/reject', [AgentController::class, 'reject'])->name('agents.reject');
    
    // Agent Commission Dashboard
    Route::get('agent/commissions', [AgentCommissionController::class, 'index'])->name('agent.commissions');
    Route::get('agent/profile', [AgentCommissionController::class, 'profile'])->name('agent.profile');
    Route::put('agent/profile', [AgentCommissionController::class, 'updateProfile'])->name('agent.profile.update');
    
    Route::resource('discounts', DiscountController::class);
    
    // Wallet Management
    Route::get('wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::post('wallet/add-amount', [WalletController::class, 'addAmount'])->name('wallet.add-amount');
    Route::post('wallet/deduct-amount', [WalletController::class, 'deductAmount'])->name('wallet.deduct-amount');
    
    Route::resource('discounts', DiscountController::class);
    // Get active discount by date
    Route::get('discounts-api/active', [DiscountController::class, 'getActiveDiscount'])->name('discounts.active');
    Route::post('discounts-api/validate-voucher', [DiscountController::class, 'validateVoucher'])->name('discounts.validate-voucher');

    // Dashboard Settings
    Route::get('dashboard-settings', [DashboardSettingController::class, 'edit'])->name('dashboard-settings.edit');
    Route::put('dashboard-settings', [DashboardSettingController::class, 'update'])->name('dashboard-settings.update');

    // Product Management (Admin)

    // Product Types Management (AJAX)
    Route::get('product-types', [ProductTypeController::class, 'index'])->name('product-types.index');
    Route::post('product-types', [ProductTypeController::class, 'store'])->name('product-types.store');
    Route::delete('product-types/{id}', [ProductTypeController::class, 'destroy'])->name('product-types.destroy');
    
    // Products
    Route::resource('products', ProductController::class);
    
    // Customer Products
    Route::get('customer-products', [CustomerProductController::class, 'index'])->name('customer.products.index');
    Route::get('customer-products/{id}', [CustomerProductController::class, 'show'])->name('customer.products.show');
    Route::post('customer-products/{id}/quotation', [CustomerProductController::class, 'submitQuotation'])->name('customer.products.quotation');
    
    // Client Quotation Request View
    Route::get('my-quotations/{id}', [CustomerProductController::class, 'showQuotation'])->name('customer.quotations.show');
    Route::post('my-quotations/{id}/upload-payment', [CustomerProductController::class, 'uploadPayment'])->name('customer.quotations.upload-payment');

    // Profile Management
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

    // My Policies (Combined view of Professional Indemnity and Other Policies)
    Route::get('my-policies', [MyPoliciesController::class, 'index'])->name('my-policies.index');

    // Quotation Request Management (Admin)
    Route::resource('quotation-requests', QuotationRequestController::class)->only(['index', 'show', 'update', 'destroy']);
    
    // Quotation Options Management (Admin)
    Route::post('quotation-requests/{quotationRequest}/options', [QuotationRequestController::class, 'storeOption'])->name('quotation-requests.options.store');
    Route::put('quotation-options/{option}', [QuotationRequestController::class, 'updateOption'])->name('quotation-options.update');
    Route::delete('quotation-options/{option}', [QuotationRequestController::class, 'deleteOption'])->name('quotation-options.delete');
    
    // Policy Document Upload (Admin)
    Route::post('quotation-requests/{quotationRequest}/upload-policy', [QuotationRequestController::class, 'uploadPolicy'])->name('quotation-requests.upload-policy');
    
    // Customer Quotation Option Selection
    Route::post('my-quotations/{quotation}/select-option/{option}', [CustomerProductController::class, 'selectOption'])->name('customer.quotations.select-option');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
    
    // Serve signature files from storage
    Route::get('signature/{path}', function($path) {
        $decodedPath = base64_decode($path);
        // Path in DB is already relative to storage/, e.g., "app/Document/PolicySignature/..."
        $fullPath = storage_path($decodedPath);
        
        if (!file_exists($fullPath)) {
            abort(404);
        }
        
        return response()->file($fullPath);
    })->name('signature.show');
});

require __DIR__.'/auth.php';
