<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\ItemType;
use App\Models\Location;
use App\Models\Inventory;
use App\Models\TransferLog;
use App\Models\InventoryItem;
use App\Models\AllocateHardware;

class DashboardController extends Controller
{
    /**
     * Display a listing of the users.
     */
    // public function index()
    // {
    //     $components = [
    //         'Computer'   => 'computer_id',
    //         'Disk Drive' => ['disk_drive_1_id', 'disk_drive_2_id'],
    //         'Processor'  => 'processor_id',
    //         'VGA'        => 'vga_card_id',
    //         'RAM'        => 'ram_id',
    //         'Monitor'    => 'monitor_id',
    //     ];

    //     $stats = [];

    //     foreach ($components as $name => $column) {
    //         $itemType = ItemType::where('name', $name)->first();
    //         $stock = 0;
    //         $allocated = 0;

    //         if ($itemType) {
    //             $stock = Inventory::where('item_type_id', $itemType->id)->sum('stock');

    //             if (is_array($column)) {
    //                 foreach ($column as $col) {
    //                     $allocated += AllocateHardware::whereNotNull($col)->count();
    //                 }
    //             } else {
    //                 $allocated = AllocateHardware::whereNotNull($column)->count();
    //             }

    //             $total = $stock + $allocated;
    //             $percent_stock = $total > 0 ? round(($stock / $total) * 100, 2) : 0;

    //             $stats[] = [
    //                 'name'       => $name,
    //                 'stock'      => $stock,
    //                 'allocated'  => $allocated,
    //                 'percent'    => $percent_stock,
    //             ];
    //         }
    //     }

    //     $averageAvailability = count($stats) > 0
    //         ? round(array_sum(array_column($stats, 'percent')) / count($stats), 2)
    //         : 0;

    //     // ✅ Ambil 5 transfer terbaru
    //     $recentTransfers = TransferLog::latest()->take(5)->get();

    //     $dailyTransfers = TransferLog::whereDate('created_at', Carbon::today())->count();

    //     return view('dashboard', compact('stats', 'averageAvailability', 'recentTransfers', 'dailyTransfers'));
    // }

public function index()
{
    // Ambil semua lokasi dengan count allocateHardwares
    $locations = Location::withCount('allocateHardwares')->take(8)->get();

    // Set capacity_limit jadi 50 untuk semua lokasi
    foreach ($locations as $loc) {
        $loc->used_capacity = $loc->allocate_hardwares_count;
        $loc->capacity_limit = 50; // paksa capacity_limit = 50
        $loc->capacity_percent = $loc->capacity_limit > 0
            ? round(($loc->used_capacity / $loc->capacity_limit) * 100, 2)
            : 0;
    }

    // Statistik kondisi inventaris
    $conditionStats = InventoryItem::selectRaw('condition_status, COUNT(*) as total')
        ->groupBy('condition_status')
        ->get();

    // Tanggal terakhir update (lokasi dan inventaris)
    $lastUpdatedLocation = Location::orderBy('updated_at', 'desc')->first();
    $lastUpdatedInventory = InventoryItem::orderBy('updated_at', 'desc')->first();

    // Last Allocate Hardware terbaru
    $lastAllocateHardware = \App\Models\AllocateHardware::orderBy('created_at', 'desc')->first();

    // Last Allocate Other terbaru
    $lastAllocateOther = \App\Models\AllocateOther::orderBy('created_at', 'desc')->first();

    // Gabungkan untuk dapatkan last allocate overall (hardware atau other paling terbaru)
    if ($lastAllocateHardware && $lastAllocateOther) {
        $lastAllocate = $lastAllocateHardware->created_at > $lastAllocateOther->created_at
            ? $lastAllocateHardware
            : $lastAllocateOther;
    } else {
        $lastAllocate = $lastAllocateHardware ?? $lastAllocateOther;
    }

    // Last Transfer terbaru
    $lastTransfer = \App\Models\TransferLog::orderBy('created_at', 'desc')->first();

    // Kirim data ke view
    return view('dashboard', compact(
        'locations', 'conditionStats', 'lastUpdatedLocation', 'lastUpdatedInventory',
        'lastAllocate', 'lastTransfer'
    ));
}


}
