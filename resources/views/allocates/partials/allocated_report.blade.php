<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Berita Acara</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
        .container { width: 100%; }
        .header-table, .header-table td { border: none !important; }
        .main-title { text-align: center; font-size: 16px; font-weight: bold; margin: 20px 0; }
        .content { margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <table class="header-table" width="100%">
            <tr>
                <td width="150px">
                    <img src="{{ public_path('assets/img/iflabs.png') }}" width="120px">
                </td>
                <td style="text-align: right; font-size: 12px;">
                    LABORATORIUM PRAKTIKUM INFORMATIKA<br>
                    Gedung F Lantai 3 IFLAB 1 s/d IFLAB 4<br>
                    Gedung TULT Lantai 6 & 7<br>
                    Fakultas Informatika<br>
                    Universitas Telkom<br>
                    Bandung
                </td>
            </tr>
        </table>

        <div class="main-title">PENDATAAN INVENTARIS SPESIFIKASI PC INFORMATICS LABORATORY</div>

        <div class="content">
            
            @if ($allocateHardware->isNotEmpty() || $allocateOther->isNotEmpty())
                <h4 style="text-align: center;">{{ $location->name }}</h4>

                <!-- Tabel Hardwares -->
                @if ($allocateHardware->isNotEmpty())
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 5%">Desk No.</th>
                                <th>Computer Brand</th>
                                <th>Disk Drive 1</th>
                                <th>Disk Drive 2</th>
                                <th>Processor</th>
                                <th>VGA</th>
                                <th>RAM</th>
                                <th>Monitor</th>
                                <th>Year (Approx)</th>
                                <th>UPS Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($allocateHardware as $index => $pc)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        {{ $pc->computer->inventory->name ?? '-' }}
                                        {{ $pc->computer->inventory->description ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $pc->diskDrive1->inventory->name ?? '-' }}
                                        {{ $pc->diskDrive1->inventory->description ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $pc->diskDrive2->inventory->name ?? '-' }}
                                        {{ $pc->diskDrive2->inventory->description ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $pc->processor->inventory->name ?? '-' }}
                                        {{ $pc->processor->inventory->description ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $pc->vgaCard->inventory->name ?? '-' }}
                                        {{ $pc->vgaCard->inventory->description ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $pc->ram->inventory->name ?? '-' }}
                                        {{ $pc->ram->inventory->description ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $pc->monitor->inventory->name ?? '-' }}
                                        {{ $pc->monitor->inventory->description ?? '-' }}
                                    </td>
                                    <td>{{ $pc->year_approx ?? '-' }}</td>
                                    <td>{{ $pc->ups_status ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                <!-- Tabel Other Items -->
                @if ($allocateOther->isNotEmpty())
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 5%">No.</th>
                                <th>Item Name</th>
                                <th>Description</th>
                                <th>Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($allocateOther as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        {{ $item->item->inventory->name ?? '-' }}
                                    </td>
                                    <td>{{ $item->description ?? '-' }}</td>
                                    <td>{{ $item->quantity ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

            @else
                <p style="text-align: center; font-style: italic; color: #6b7280;">Tidak ada data pada {{ $location->name }}.</p>
            @endif
        </div>
    </div>
</body>
</html>
