<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PolicySubmissionController;

// Policy Submission Endpoint - using web middleware for session auth
Route::post('/policies/submit', [PolicySubmissionController::class, 'submit'])
    ->name('api.policies.submit')
    ->middleware(['web', 'auth']);

Route::middleware('api')->group(function () {
    // Future API routes can go here
});
