<?php

use App\Models\User;
use App\Models\PolicyApplication;
use App\Models\PolicyPricing;

$userId = 2;

$user = User::find($userId);
$applications = PolicyApplication::where('user_id', $userId)->get();
$pricings = PolicyPricing::where('user_id', $userId)->get();

echo "User ID: $userId\n";
echo "Applications Count: " . $applications->count() . "\n";
foreach ($applications as $app) {
    echo "App ID: {$app->id}, Created: {$app->created_at}, Status: {$app->status}\n";
}

echo "\nPricings Count: " . $pricings->count() . "\n";
foreach ($pricings as $price) {
    echo "Pricing ID: {$price->id}, Created: {$price->created_at}, App ID: {$price->policy_application_id}\n";
}
