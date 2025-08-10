<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class TransferLog extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'transfer_logs';

    protected $guarded = [];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'item_id');
    }

    public function transferredBy()
    {
        return $this->belongsTo(User::class, 'transferred_by');
    }

    public function getAuditDescription($event)
    {
        $itemName = $this->item ? ($this->item->inventory->name ?? 'Unknown Item') : 'Unknown Item';
        $fromLoc = $this->from_location ?? 'Unknown Source';
        $toLoc = $this->to_location ?? 'Unknown Destination';
        $note = $this->note ? " Note: {$this->note}" : '';

        return "Transfer of item '{$itemName}' from '{$fromLoc}' to '{$toLoc}' (Record ID #{$this->id}) has been {$event}.{$note}";
    }
}
