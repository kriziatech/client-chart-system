<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupController extends Controller
{
    public function index()
    {
        $disk = Storage::disk('backups');
        $files = $disk->files(config('backup.backup.name'));

        $backups = [];
        foreach ($files as $k => $f) {
            if (substr($f, -4) == '.zip' && $disk->exists($f)) {
                $backups[] = [
                    'file_path' => $f,
                    'file_name' => str_replace(config('backup.backup.name') . '/', '', $f),
                    'file_size' => $this->humanFilesize($disk->size($f)),
                    'last_modified' => $disk->lastModified($f),
                ];
            }
        }

        // Reverse to see latest first
        $backups = array_reverse($backups);

        return view('backups.index', compact('backups'));
    }

    public function create()
    {
        try {
            // start the backup process
            Artisan::call('backup:run --only-db');

            return back()->with('success', 'Database backup started in background.');
        }
        catch (\Exception $e) {
            return back()->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    public function download($file_name)
    {
        $file = config('backup.backup.name') . '/' . $file_name;
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
        $file = config('backup.backup.name') . '/' . $file_name;
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