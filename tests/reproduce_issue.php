<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- Reproduction Script ---\n";

// 1. Simulate Registration with Double Hashing (Current Bug)
$email = 'bug' . time() . '@example.com';
$password = 'password123';

echo "Creating user with Hash::make() (Bug Simulation)...\n";
try {
    // Current implementation in RegisteredUserController does this:
    $userBug = User::create([
        'name' => 'Bug User',
        'email' => $email,
        'password' => Hash::make($password), // Explicit hashing
    ]);
} catch (\Exception $e) {
    echo "Error creating user: " . $e->getMessage() . "\n";
    exit(1);
}

// Reload user from DB to see what was saved
$userBug = User::find($userBug->id);
echo "Saved Password Hash: " . substr($userBug->password, 0, 20) . "...\n";

// Attempt to verify
if (Hash::check($password, $userBug->password)) {
    echo "[FAIL] Password verification SUCCESS (Unexpected if double hashed, maybe casting handles it?)\n";
} else {
    echo "[SUCCESS] Password verification FAILED (Expected if double hashed)\n";
}


// 2. Simulate Registration without Double Hashing (The Fix)
$emailFix = 'fix' . time() . '@example.com';
echo "\nCreating user WITHOUT Hash::make() (The Fix)...\n";

try {
    $userFix = User::create([
        'name' => 'Fix User',
        'email' => $emailFix,
        'password' => $password, // Let casting handle it
    ]);
} catch (\Exception $e) {
    echo "Error creating user: " . $e->getMessage() . "\n";
    exit(1);
}

// Reload
$userFix = User::find($userFix->id);
echo "Saved Password Hash: " . substr($userFix->password, 0, 20) . "...\n";

// Attempt to verify
if (Hash::check($password, $userFix->password)) {
    echo "[SUCCESS] Password verification SUCCESS\n";
} else {
    echo "[FAIL] Password verification FAILED\n";
}
