<!DOCTYPE html>
<html>
<head>
    <title>Report Perjalanan</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            border: 1px solid #000;
            font-size: 12px;
        }
        img {
            max-width: 100px;
            height: auto;
        }
    </style>
</head>
<body>
    <h2>Laporan Perjalanan</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Driver</th>
                <th>Perjalanan</th>
                <th>Bukti</th>
                <th>Tanggal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $index => $report)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $report->user->name }}</td>
                    <td>{{ $report->perjalanan->rute->pluck('nama')->implode(', ') }}</td>
                    <td>
                        @if ($report->bukti)
                            <img src="{{ public_path('storage/' . $report->bukti) }}" alt="Bukti">
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $report->tanggal }}</td>
                    <td>{{ $report->status == 'confirmed' ? 'Terkonfirmasi' : 'Tidak Terkonfirmasi' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
