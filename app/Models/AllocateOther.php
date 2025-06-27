<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AllocateOther extends Model
{
    use HasFactory;

    protected $table = 'allocate_others';

    protected $guarded = [];
    
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function others()
    {
        return $this->belongsTo(Inventory::class, 'others_id');
    }    

    public function transferLogs()
    {
        return $this->morphMany(TransferLog::class, 'item');
    }
}
