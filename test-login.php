<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== Testing Login Credentials ===\n\n";

$users = User::all(['id', 'name', 'email']);

foreach ($users as $user) {
    echo "ID: {$user->id}\n";
    echo "Name: {$user->name}\n";
    echo "Email: {$user->email}\n";

    // Try to get full user with password
    $fullUser = User::find($user->id);
    echo "Has Password: " . (!empty($fullUser->password) ? "Yes" : "No") . "\n";
    echo "Role ID: " . ($fullUser->role_id ?? 'NULL') . "\n";

    if ($fullUser->role) {
        echo "Role Type: {$fullUser->role->type}\n";
    }
    else {
        echo "Role Type: NO ROLE ASSIGNED\n";
    }

    echo "---\n\n";
}

// Test password verification for admin
echo "\n=== Testing Password Verification ===\n";
$admin = User::where('email', 'admin@interiortouchpm.com')->first();
if ($admin) {
    $testPasswords = ['password', 'admin123', '12345678', 'admin', 'Password123'];
    foreach ($testPasswords as $testPass) {
        $result = Hash::check($testPass, $admin->password);
        echo "Password '{$testPass}': " . ($result ? "✓ MATCH" : "✗ No match") . "\n";
    }
}