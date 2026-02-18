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
            return response()->json(['log' => $content]);
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