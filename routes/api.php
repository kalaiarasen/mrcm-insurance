<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PolicySubmissionController;

Route::middleware('api')->group(function () {
    // Policy Submission Endpoint
    Route::post('/policies/submit', [PolicySubmissionController::class, 'submit'])
        ->name('api.policies.submit')
        ->middleware('auth:sanctum');
});
