<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        foreach (['created', 'updated', 'deleted'] as $event) {
            static::$event(function ($model) use ($event) {
                $userId = Auth::id() ?? null;
                $table = $model->getTable();
                $recordId = $model->getKey();

                // Bisa override method getAuditDescription di model untuk custom pesan
                $description = method_exists($model, 'getAuditDescription')
                    ? $model->getAuditDescription($event)
                    : "Record ID {$recordId} has been {$event}d.";

                AuditLog::create([
                    'user_id' => $userId,
                    'action' => strtoupper($event),
                    'table_name' => $table,
                    'record_id' => $recordId,
                    'description' => $description,
                ]);
            });
        }
    }
}
