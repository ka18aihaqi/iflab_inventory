<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = AuditLog::with('user')->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                ->orWhere('table_name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('created_at', 'like', "%{$search}%")
                ->orWhereHas('user', function($q2) use ($search) {
                    $q2->where('username', 'like', "%{$search}%");
                });
            });
        }

        $logs = $query->paginate(10)->appends(['search' => $search]);

        return view('auditlogs.index', compact('logs'));
    }

    public function downloadTxt()
    {
        $logs = AuditLog::with('user')->latest()->get();

        $content = "=== Audit Logs Export ===\n\n";
        foreach ($logs as $log) {
            $content .= "User: " . ($log->user->username ?? '-') . "\n";
            $content .= "Action: {$log->action}\n";
            $content .= "Table: {$log->table_name}\n";
            $content .= "Description: {$log->description}\n";
            $content .= "Date: {$log->created_at}\n";
            $content .= "---------------------------\n";
        }

        $filename = "audit_logs_" . now()->format('Y-m-d_H-i-s') . ".txt";

        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', "attachment; filename={$filename}");
    }

}
