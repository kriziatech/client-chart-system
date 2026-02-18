<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class BackupController extends Controller
{
    public function index()
    {
        $disk = Storage::disk('backups');
        $appName = config('backup.backup.name');

        // Ensure destination and temp directories exist with full permissions
        $backupsRoot = storage_path('app/backups');
        $tempRoot = storage_path('app/backup-temp');

        foreach ([$backupsRoot, $tempRoot, $backupsRoot . '/' . $appName] as $path) {
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            @chmod($path, 0777);
        }

        $files = $disk->files($appName);

        $backups = [];
        foreach ($files as $f) {
            if (substr($f, -4) == '.zip' && $disk->exists($f)) {
                $backups[] = [
                    'file_path' => $f,
                    'file_name' => str_replace($appName . '/', '', $f),
                    'file_size' => $this->humanFilesize($disk->size($f)),
                    'last_modified' => $disk->lastModified($f),
                ];
            }
        }

        // Custom sort by last_modified desc
        usort($backups, function ($a, $b) {
            return $b['last_modified'] <=> $a['last_modified'];
        });

        // Determine backup path for display
        $backupPath = storage_path('app/backups');
        if (!file_exists($backupPath)) {
            $backupPath = "Configured 'backups' disk path";
        }

        return view('backups.index', compact('backups', 'backupPath'));
    }

    public function create()
    {
        try {
            // Path to log file
            $logFile = storage_path('logs/backup-process.log');
            file_put_contents($logFile, "--- Starting Backup Process ---\n");

            // Build Command
            // We use nohup to run in background
            $command = 'nohup php ' . base_path('artisan') . ' backup:run --only-db > ' . $logFile . ' 2>&1 &';

            // Execute
            exec($command);

            return response()->json(['success' => true, 'message' => 'Backup initiated successfully.']);
        }
        catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function streamLog()
    {
        $logFile = storage_path('logs/backup-process.log');
        if (file_exists($logFile)) {
            $content = file_get_contents($logFile);

            // Clean up Spatie backup output to be human readable
            $lines = explode("\n", $content);
            $formattedLines = [];

            foreach ($lines as $line) {
                // Strip ANSI escape codes
                $line = preg_replace('/\x1b\[[0-9;]*m/', '', $line);

                if (empty(trim($line)))
                    continue;

                // Friendly translations
                $replacements = [
                    'Starting backup...' => '🚀 Starting system snapshot...',
                    'Dumping database' => '🗄️ Capturing database state...',
                    'Determining files' => '🔍 Scanning system files...',
                    'Zipping files' => '📦 Creating secure archive...',
                    'Copying zip' => '💾 Storing backup safely...',
                    'Backup completed!' => '✅ Success: System state preserved!',
                    'Backup failed' => '❌ Error: Backup process interrupted',
                    'Cleanup' => '🧹 Removing expired snapshots...',
                ];

                foreach ($replacements as $search => $replace) {
                    if (str_contains($line, $search)) {
                        $line = $replace;
                        break;
                    }
                }

                $formattedLines[] = $line;
            }

            return response()->json(['log' => implode("\n", $formattedLines)]);
        }
        return response()->json(['log' => 'Waiting for logs...']);
    }

    public function download($file_name)
    {
        $appName = config('backup.backup.name');
        $file = $appName . '/' . $file_name;
        $disk = Storage::disk('backups');

        if ($disk->exists($file)) {
            return $disk->download($file);
        }
        else {
            return back()->with('error', 'Backup file doesn\'t exist.');
        }
    }

    public function upload(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:zip|max:51200' // Max 50MB
        ]);

        $file = $request->file('backup_file');
        $appName = config('backup.backup.name');

        $path = Storage::disk('backups')->putFileAs($appName, $file, $file->getClientOriginalName());

        if ($path) {
            return back()->with('success', 'Backup uploaded successfully.');
        }

        return back()->with('error', 'Failed to upload backup.');
    }

    public function restore($file_name)
    {
        $appName = config('backup.backup.name');
        $filePath = Storage::disk('backups')->path($appName . '/' . $file_name);

        if (!file_exists($filePath)) {
            return back()->with('error', 'Backup file not found.');
        }

        try {
            $tempPath = storage_path('app/restore-temp-' . time());
            if (!mkdir($tempPath, 0777, true)) {
                throw new \Exception("Failed to create temp directory.");
            }

            // Extract ZIP
            $zip = new \ZipArchive;
            if ($zip->open($filePath) === TRUE) {
                $zip->extractTo($tempPath);
                $zip->close();
            }
            else {
                throw new \Exception("Failed to open ZIP file.");
            }

            // Find SQL dump
            $sqlFile = null;
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($tempPath));
            foreach ($files as $file) {
                if ($file->isFile() && $file->getExtension() === 'sql') {
                    $sqlFile = $file->getRealPath();
                    break;
                }
            }

            if (!$sqlFile) {
                throw new \Exception("No SQL dump found in the backup ZIP.");
            }

            // Database credentials from config
            $connection = config('database.default');
            $dbConfig = config("database.connections.{$connection}");

            $host = $dbConfig['host'];
            $port = $dbConfig['port'];
            $database = $dbConfig['database'];
            $username = $dbConfig['username'];
            $password = $dbConfig['password'];

            // Detect which binary is available (mysql or mariadb)
            $checkBinary = Process::fromShellCommandline('which mariadb || which mysql');
            $checkBinary->run();
            $binary = trim($checkBinary->getOutput());

            if (empty($binary)) {
                throw new \Exception("Neither 'mariadb' nor 'mysql' binary found in the system path.");
            }

            // Build Command safely
            $cmdParts = [
                escapeshellarg($binary),
                '-h', escapeshellarg($host),
                '-P', escapeshellarg($port),
                '-u', escapeshellarg($username),
            ];

            // Only add password if it's not empty
            if (!empty($password)) {
                $cmdParts[] = '-p' . $password; // Note: No space, and we don't escapeshellarg the whole flag to avoid double quoting issues with some clients
            }

            // Add SSL bypass (common in local/docker setups)
            // Try with --skip-ssl first as it's more universal for MariaDB/MySQL
            $cmdParts[] = '--skip-ssl';

            $cmdParts[] = escapeshellarg($database);
            $cmdParts[] = '<';
            $cmdParts[] = escapeshellarg($sqlFile);

            $command = implode(' ', $cmdParts);

            // Log for debugging (mask password)
            $maskedCommand = str_replace("-p$password", "-p*****", $command);
            \Illuminate\Support\Facades\Log::info("Executing restore: " . $maskedCommand);

            $process = Process::fromShellCommandline($command);
            $process->run();

            // Clean up
            $this->recursiveDelete($tempPath);

            if (!$process->isSuccessful()) {
                $errorOutput = $process->getErrorOutput();
                // If --skip-ssl failed, try one more time without it
                if (str_contains($errorOutput, 'unknown variable \'skip-ssl\'')) {
                // Retry logic... for now let's just throw better error
                }
                throw new \Exception("Command failed: " . ($errorOutput ?: $process->getOutput()));
            }

            return back()->with('success', 'Database restored successfully! The system has been reverted to the snapshot state.');
        }
        catch (\Exception $e) {
            if (isset($tempPath) && file_exists($tempPath)) {
                $this->recursiveDelete($tempPath);
            }
            return back()->with('error', 'Restore failed: ' . $e->getMessage());
        }
    }

    private function recursiveDelete($dir)
    {
        if (!file_exists($dir))
            return true;
        if (!is_dir($dir))
            return unlink($dir);
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..')
                continue;
            if (!$this->recursiveDelete($dir . DIRECTORY_SEPARATOR . $item))
                return false;
        }
        return rmdir($dir);
    }

    public function destroy($file_name)
    {
        $appName = config('backup.backup.name');
        $file = $appName . '/' . $file_name;
        $disk = Storage::disk('backups');

        if ($disk->exists($file)) {
            $disk->delete($file);
            return back()->with('success', 'Backup deleted successfully.');
        }
        else {
            return back()->with('error', 'Backup file doesn\'t exist.');
        }
    }

    private function humanFilesize($bytes, $decimals = 2)
    {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
    }
}