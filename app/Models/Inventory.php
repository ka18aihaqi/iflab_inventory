<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Inventory extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'inventories';

    protected $guarded = [];

    /**
     * Daftar kategori yang valid
     */
    public const CATEGORIES = [
        'Computer',
        'Disk Drive',
        'Processor',
        'VGA',
        'RAM',
        'Monitor',
        'Other'
    ];

    /**
     * Relasi ke InventoryItem (satu inventory punya banyak unit barang)
     */
    public function items()
    {
        return $this->hasMany(InventoryItem::class);
    }

    public function getAuditDescription($event)
    {
        return "{$this->name} {$this->description} (Record ID #{$this->id}) has been {$event}.";
    }
}
