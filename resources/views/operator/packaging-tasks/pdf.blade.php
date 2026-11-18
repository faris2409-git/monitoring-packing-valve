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
        .info-table td:first-child { font-weight: bold; width: 200px; background-color: #f0fdfa; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Hasil Packaging Engine Valve</h2>
        <p>Federal Nittan Industries (FNI)</p>
    </div>

    <table class="info-table">
        <tr>
            <td>Kode Tugas</td>
            <td>{{ $packagingTask->task_code }}</td>
        </tr>
        <tr>
            <td>Jenis Barang</td>
            <td>{{ $packagingTask->engineValve->name }} ({{ $packagingTask->engineValve->code }})</td>
        </tr>
        <tr>
            <td>Quantity Selesai</td>
            <td>{{ $packagingTask->quantity_packed }} / {{ $packagingTask->quantity_target }}</td>
        </tr>
        <tr>
            <td>Tanggal Pengerjaan</td>
            <td>{{ $packagingTask->completed_at?->format('d F Y, H:i') }} WIB</td>
        </tr>
        <tr>
            <td>Dikerjakan Oleh</td>
            <td>{{ $packagingTask->operator->name }}</td>
        </tr>
        <tr>
            <td>Catatan</td>
            <td>{{ $packagingTask->notes ?? '-' }}</td>
        </tr>
    </table>

    <p style="margin-top: 40px;">Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</p>
</body>
</html>