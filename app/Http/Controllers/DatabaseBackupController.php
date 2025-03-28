<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DatabaseBackupController extends Controller
{
    public function backup()
    {
        $databaseName = env('DB_DATABASE', 'railway');
        $username = env('DB_USERNAME', 'root');
        $password = env('DB_PASSWORD', 'vGyxcmGpDyrbAlxQVpNdFciMLkpDDZod');
        $host = env('DB_HOST', 'switchback.proxy.rlwy.net');
        $port = env('DB_PORT', '11701');

        $backupFile = storage_path('app/backup/' . $databaseName . '_' . date('Y-m-d_H-i-s') . '.sql');

        // Ganti dengan path lengkap ke mysqldump
        $mysqldumpPath = 'C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysqldump.exe'; // Ganti dengan path yang sesuai
        $command = "$mysqldumpPath --user={$username} --password={$password} --host={$host} --port={$port} {$databaseName} > {$backupFile}";

        // Log the command for debugging
        Log::info("Running command: $command");

        $output = [];
        $returnVar = 0;

        // Execute the command and capture both stdout and stderr
        exec($command . ' 2>&1', $output, $returnVar);

        // Log the output and return status
        Log::info("Command output: " . implode("\n", $output));
        Log::info("Return status: $returnVar");

        if ($returnVar === 0) {
            return response()->download($backupFile)->deleteFileAfterSend(true);
        } else {
            Log::error("Backup failed with status $returnVar. Output: " . implode("\n", $output));
            return back()->withErrors(['msg' => 'Backup failed! Check logs for details.']);
        }
    }
}