<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferLog extends Model
{
    use HasFactory;

    protected $table = 'transfer_logs';

    protected $guarded = [];
}
