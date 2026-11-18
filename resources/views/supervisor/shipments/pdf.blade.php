<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #14b8a6; padding-bottom: 10px; }
        .header h2 { margin: 0; color: #0d9488; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table, th, td { border: 1px solid #ccc; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #14b8a6; color: #fff; }
        .info-table td:first-child { font-weight: bold; width: 180px; background-color: #f0fdfa; }
        .signature { margin-top: 60px; display: table; width: 100%; }
        .signature div { display: table-cell; text-align: center; width: 50%; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Surat Jalan Pengiriman Barang</h2>
        <p>Federal Nittan Industries (FNI)</p>
    </div>

    <table class="info-table">
        <tr><td>No. Surat Jalan</td><td>{{ $shipment->shipment_code }}</td></tr>
        <tr><td>Nama Driver</td><td>{{ $shipment->driver_name }}</td></tr>
        <tr><td>No. Kendaraan</td><td>{{ $shipment->vehicle_no ?? '-' }}</td></tr>
        <tr><td>Tanggal Kirim</td><td>{{ \Carbon\Carbon::parse($shipment->shipment_date)->format('d F Y') }}</td></tr>
        <tr><td>Catatan</td><td>{{ $shipment->notes ?? '-' }}</td></tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Tugas</th>
                <th>Nama Barang</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($shipment->items as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->packagingTask->task_code }}</td>
                    <td>{{ $item->packagingTask->engineValve->name }}</td>
                    <td>{{ $item->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature">
        <div>
            Supervisor,<br><br><br><br>
            ( {{ $shipment->creator->name }} )
        </div>
        <div>
            Driver,<br><br><br><br>
            ( {{ $shipment->driver_name }} )
        </div>
    </div>
</body>
</html>