<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogsActivity;

class AllocateHardware extends Model
{
    use HasFactory, LogsActivity;
    
    protected $table = 'allocate_hardwares';

    protected $guarded = [];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function computer()
    {
        return $this->belongsTo(InventoryItem::class, 'computer_id');
    }

    public function diskDrive1()
    {
        return $this->belongsTo(InventoryItem::class, 'disk_drive_1_id');
    }

    public function diskDrive2()
    {
        return $this->belongsTo(InventoryItem::class, 'disk_drive_2_id');
    }

    public function processor()
    {
        return $this->belongsTo(InventoryItem::class, 'processor_id');
    }

    public function vgaCard()
    {
        return $this->belongsTo(InventoryItem::class, 'vga_card_id');
    }

    public function ram()
    {
        return $this->belongsTo(InventoryItem::class, 'ram_id');
    }

    public function monitor()
    {
        return $this->belongsTo(InventoryItem::class, 'monitor_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getAuditDescription($event)
    {
        $locationName = $this->location ? $this->location->name : 'Unknown location';
        $deskNumber = $this->desk_number ? "Desk No. {$this->desk_number}" : "No desk number";
        return "{$deskNumber} at location '{$locationName}' (Record ID #{$this->id}) has been {$event}.";
    }
}
