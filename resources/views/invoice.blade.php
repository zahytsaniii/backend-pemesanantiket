<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice_number }}</title>
    <style>
        /* Gunakan DejaVu Sans agar simbol Unicode tampil */
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 14px;
        }
        .header { text-align: center; margin-bottom: 20px; }
        .details { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Invoice {{ $invoice_number }}</h2>
    </div>

    <div class="details">
        <p>Nama: {{ $user->name }}</p>
        <p>Email: {{ $user->email }}</p>
        <p>Tanggal: {{ now()->format('d-m-Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Rute</th>
                <th>Kursi</th>
                <th>Harga per Kursi</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $booking->schedule->departure_city }} &rarr; {{ $booking->schedule->destination }}</td>
                <td>{{ $booking->seats }}</td>
                <td>Rp {{ number_format($booking->schedule->price, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($booking->seats * $booking->schedule->price, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <p>Total Bayar: <strong>Rp {{ number_format($booking->seats * $booking->schedule->price, 0, ',', '.') }}</strong></p>
</body>
</html>
