<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\ItemType;
use App\Models\Inventory;
use App\Models\TransferLog;
use App\Models\AllocateHardware;

class DashboardController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $components = [
            'Computer'   => 'computer_id',
            'Disk Drive' => ['disk_drive_1_id', 'disk_drive_2_id'],
            'Processor'  => 'processor_id',
            'VGA'        => 'vga_card_id',
            'RAM'        => 'ram_id',
            'Monitor'    => 'monitor_id',
        ];

        $stats = [];

        foreach ($components as $name => $column) {
            $itemType = ItemType::where('name', $name)->first();
            $stock = 0;
            $allocated = 0;

            if ($itemType) {
                $stock = Inventory::where('item_type_id', $itemType->id)->sum('stock');

                if (is_array($column)) {
                    foreach ($column as $col) {
                        $allocated += AllocateHardware::whereNotNull($col)->count();
                    }
                } else {
                    $allocated = AllocateHardware::whereNotNull($column)->count();
                }

                $total = $stock + $allocated;
                $percent_stock = $total > 0 ? round(($stock / $total) * 100, 2) : 0;

                $stats[] = [
                    'name'       => $name,
                    'stock'      => $stock,
                    'allocated'  => $allocated,
                    'percent'    => $percent_stock,
                ];
            }
        }

        $averageAvailability = count($stats) > 0
            ? round(array_sum(array_column($stats, 'percent')) / count($stats), 2)
            : 0;

        // ✅ Ambil 5 transfer terbaru
        $recentTransfers = TransferLog::latest()->take(5)->get();

        $dailyTransfers = TransferLog::whereDate('created_at', Carbon::today())->count();

        return view('dashboard', compact('stats', 'averageAvailability', 'recentTransfers', 'dailyTransfers'));
    }
}
