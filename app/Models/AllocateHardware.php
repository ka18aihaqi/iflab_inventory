<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AllocateHardware extends Model
{
    use HasFactory;
    
    protected $table = 'allocate_hardwares';

    protected $guarded = [];

    public function location() 
    { 
        return $this->belongsTo(Location::class); 
    }

    public function computer()
    {
        return $this->belongsTo(Inventory::class, 'computer_id');
    }

    public function diskDrive1()
    {
        return $this->belongsTo(Inventory::class, 'disk_drive_1_id');
    }
    
    public function diskDrive2()
    {
        return $this->belongsTo(Inventory::class, 'disk_drive_2_id');
    }
    
    public function processor()
    {
        return $this->belongsTo(Inventory::class, 'processor_id');
    }
    
    public function vgaCard()
    {
        return $this->belongsTo(Inventory::class, 'vga_card_id');
    }
    
    public function ram()
    {
        return $this->belongsTo(Inventory::class, 'ram_id');
    }    

    public function monitor()
    {
        return $this->belongsTo(Inventory::class, 'monitor_id');
    }


    public function vga_card() {
        return $this->vgaCard();
    }

    public function disk_drive1() {
        return $this->diskDrive1();
    }

    public function disk_drive2() {
        return $this->diskDrive2();
    }

}
