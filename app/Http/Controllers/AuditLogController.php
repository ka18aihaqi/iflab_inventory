<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;

class AuditLogController extends Controller
{
    public function index()
    {
        // Ambil data audit logs terbaru, 20 per halaman
        $logs = AuditLog::with('user')->latest()->paginate(20);

        // Kirim data ke view
        return view('auditlogs.index', compact('logs'));
    }
}
