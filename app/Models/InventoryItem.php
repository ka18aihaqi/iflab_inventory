<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class InventoryItem extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'inventory_items';

    protected $guarded = [];

    /**
     * Relasi ke Inventory (unit barang milik satu inventory)
     */
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function lastCheckedBy()
    {
        return $this->belongsTo(User::class, 'last_checked_by');
    }

    public function allocateHardware()
    {
        return $this->hasOne(AllocateHardware::class, 'computer_id')
            ->orWhere('ram_id', $this->id)
            ->orWhere('processor_id', $this->id)
            ->orWhere('vga_card_id', $this->id)
            ->orWhere('disk_drive_1_id', $this->id)
            ->orWhere('disk_drive_2_id', $this->id)
            ->orWhere('monitor_id', $this->id);
    }

    public function allocateOther()
    {
        return $this->hasOne(AllocateOther::class, 'item_id');
    }

    public function getAuditDescription($event)
    {
        $inventoryName = $this->inventory ? $this->inventory->name : 'Unknown Inventory';
        $inventoryDesc = $this->inventory ? $this->inventory->description : '';
        return "{$inventoryName} {$inventoryDesc} 'SN: {$this->serial_number}' (Record ID #{$this->id}) has been {$event}.";
    }

}
