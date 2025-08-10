<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogsActivity;

class AllocateOther extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'allocate_others';

    protected $guarded = [];
    
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'item_id');
    }  

    public function transferLogs()
    {
        return $this->morphMany(TransferLog::class, 'item');
    }

    public function getAuditDescription($event)
    {
        $inventoryName = $this->item && $this->item->inventory ? $this->item->inventory->name : 'Unknown Inventory';
        $inventoryDesc = $this->item && $this->item->inventory ? $this->item->inventory->description : '';
        $serialNumber = $this->item ? $this->item->serial_number : 'No Serial Number';

        return "{$inventoryName} {$inventoryDesc} (SN: {$serialNumber}) at location '{$this->location->name}' (Data ID #{$this->id}) has been {$event}.";
    }
}
