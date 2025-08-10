<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogsActivity;

class Location extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'locations';

    protected $guarded = [];

    public function allocateHardwares()
    {
        return $this->hasMany(AllocateHardware::class, 'location_id');
    }

    public function getAuditDescription($event)
    {
        return "Location data '{$this->name}' (Record ID #{$this->id}) has been {$event}.";
    }
}
