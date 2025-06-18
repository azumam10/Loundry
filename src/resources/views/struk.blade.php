<!DOCTYPE html>
<html>
<head>
    <title>Struk Transaksi Laundry</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 20px; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laundry Bersih</h2>
        <p>Jl. Contoh No.123, Telp: 08123456789</p>
    </div>
    <hr>
    <p><strong>ID Transaksi:</strong> {{ $transaksi->id }}</p>
    <p><strong>Nama Client:</strong> {{ $transaksi->client->name ?? '-' }}</p>
    <p><strong>Paket:</strong> {{ $transaksi->paket->nama ?? '-' }}</p>
    <p><strong>Metode Pembayaran:</strong> {{ ucfirst($transaksi->metode) }}</p>
    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d M Y') }}</p>
    <p><strong>Berat:</strong> {{ $transaksi->berat }} kg</p>
    <p><strong>Harga/kg:</strong> Rp{{ number_format($transaksi->harga, 0, ',', '.') }}</p>
    <p class="total"><strong>Total:</strong> Rp{{ number_format($transaksi->total, 0, ',', '.') }}</p>
    <hr>
    <p>Terima kasih atas kepercayaan Anda.</p>
</body>
</html>
