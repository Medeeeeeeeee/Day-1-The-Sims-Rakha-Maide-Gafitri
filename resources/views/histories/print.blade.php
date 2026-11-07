<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Riwayat Aset</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; color: #111; margin: 24px; }
        h1 { margin: 0 0 6px; font-size: 24px; }
        p { margin: 0 0 16px; font-size: 13px; color: #555; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #cfcfcf; padding: 8px 10px; font-size: 12px; text-align: left; vertical-align: top; }
        th { background: #f0f0f0; }
    </style>
</head>
<body onload="window.print()">
    <h1>Laporan Riwayat Aset</h1>
    <p>Dibuat pada {{ now()->format('d M Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Lab</th>
                <th>Aset</th>
                <th>Judul Ticket</th>
                <th>Asisten</th>
                <th>Tindakan</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($histories as $history)
            <tr>
                <td>#{{ str_pad($history->id, 3, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $history->lab->nama_lab ?? '-' }}</td>
                <td>
                    @php
                        $assetPc = $history->ticket?->nomor_pc ?: ($history->nomor_pc > 0 ? $history->nomor_pc : null);
                    @endphp
                    {{ $assetPc ? 'PC-' . str_pad($assetPc, 2, '0', STR_PAD_LEFT) : 'Per Lab' }}
                </td>
                <td>{{ $history->ticket?->judul ?? '-' }}</td>
                <td>{{ $history->asisten?->displayName() ?: ($history->asisten->email ?? '-') }}</td>
                <td>{{ $history->tindakan }}</td>
                <td>{{ \Illuminate\Support\Carbon::parse($history->tanggal_perawatan)->format('d M Y H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7">Belum ada riwayat aset.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
