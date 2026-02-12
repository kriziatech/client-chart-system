<?php

use App\Models\Client;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

// Simulate Admin User
$admin = User::where('email', 'admin@interiortouchpm.com')->first();
Auth::login($admin);

try {
    echo "--- Starting Verification ---\n";

    // 1. Create a client
    echo "Creating client...\n";
    $client = Client::create([
        'first_name' => 'Test',
        'last_name' => 'Client',
        'email' => 'test@example.com',
        'file_number' => 'FILE-TEST',
        'mobile' => '1234567890',
    ]);
    echo "Client created: ID {$client->id}\n";

    // 2. Update the client - This triggered the _oldAuditValues error before
    echo "Updating client...\n";
    $client->update(['first_name' => 'Updated Name']);
    echo "Client updated successfully.\n";

    // 3. Check Audit Logs
    $logs = AuditLog::where('model_type', Client::class)
        ->where('model_id', $client->id)
        ->get();

    echo "Audit Logs found: " . $logs->count() . "\n";
    foreach ($logs as $log) {
        echo "- [{$log->action}] {$log->description}\n";
    }

    if ($logs->where('action', 'updated')->count() > 0) {
        echo "SUCCESS: Update log exists.\n";
    }
    else {
        echo "FAILURE: Update log missing.\n";
    }

    // Clean up
    $client->delete();
    echo "Client deleted.\n";

}
catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}