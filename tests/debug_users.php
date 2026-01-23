<?php

use App\Models\User;

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- User List ---\n";
$users = User::all();
foreach ($users as $user) {
    echo "ID: {$user->id} | Name: {$user->name} | Email: {$user->email} | Password: " . substr($user->password, 0, 15) . "... | Verified: " . ($user->email_verified_at ? 'Yes' : 'No') . "\n";
}
