<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laundry Kita - Solusi Laundry Terpercaya</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('front/style.css') }}">
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <i class="fas fa-tint"></i>
                    <h1>Umamis Looundry</h1>
                </div>
                <nav>
                    <ul>
                        <li><a href="#tentang">Tentang</a></li>
                        <li><a href="#jasa">Layanan</a></li>
                        <li><a href="#cari">Cari Cucian</a></li>
                        <li><a href="#kontak">Kontak</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <section id="tentang" class="section hero">
                <i class="fas fa-star"></i>
                <h2>Tentang Kami</h2>
                <p>Laundry cepat, bersih, dan profesional. Kami melayani dengan sepenuh hati untuk memberikan hasil terbaik bagi pakaian Anda.</p>
                <a href="#jasa" class="cta-button">Lihat Layanan Kami</a>
            </section>

            <section id="jasa" class="section">
                <h2><i class="fas fa-concierge-bell"></i>Layanan Kami</h2>
                <p>Kami menyediakan berbagai layanan laundry profesional untuk memenuhi kebutuhan Anda:</p>
                
                <div class="services-grid">
                    <div class="service-card">
                        <i class="fas fa-wind"></i>
                        <h3>Cuci Kering</h3>
                        <p>Layanan cuci kering profesional untuk pakaian berbahan khusus dan delicate. Dengan harga Rp.7.000</p>
                    </div>
                    <div class="service-card">
                        <i class="fas fa-tshirt"></i>
                        <h3>Cuci Lipat</h3>
                        <p>Layanan cuci lengkap dengan pelipatan rapi siap pakai.</p>
                    </div>
                    <div class="service-card">
                        <i class="fas fa-iron"></i>
                        <h3>Setrika</h3>
                        <p>Layanan setrika profesional untuk hasil yang rapi dan berkualitas.</p>
                    </div>
                </div>
            </section>

            <section id="cari" class="section">
                <h2><i class="fas fa-search"></i>Cari Cucian Anda</h2>
                <p>Masukkan nama Anda untuk melacak status cucian:</p>
                
                <div class="search-form">
                    <form method="GET" action="{{ route('cari.transaksi') }}">
                        <div class="form-group">
                            <input type="text" name="nama" placeholder="Masukkan nama Anda" required>
                            <button type="submit"><i class="fas fa-search"></i> Cari</button>
                        </div>
                    </form>
                </div>

                @if(session('hasil'))
                    <div class="hasil-pencarian">
                        <h3>Hasil Pencarian:</h3>
                        @foreach(session('hasil') as $trx)
                            <div class="hasil-item">
                                <div class="item-info">
                                    <span class="tanggal"><i class="fas fa-calendar"></i> {{ $trx->tanggal }}</span>
                                    <span class="total"><i class="fas fa-money-bill-wave"></i> Rp {{ number_format($trx->total, 0, ',', '.') }}</span>
                                    <span class="status status-{{ strtolower($trx->status_pembayaran) }}">
                                        <i class="fas fa-info-circle"></i> {{ $trx->status_pembayaran }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <section id="kontak" class="section">
                <h2><i class="fas fa-phone"></i>Kontak Kami</h2>
                <p>Hubungi kami untuk informasi lebih lanjut atau konsultasi gratis:</p>
                
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-phone-alt"></i>
                        <div>
                            <h3>Telepon</h3>
                            <p>0812-3456-7890</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <h3>Email</h3>
                            <p>laundry@example.com</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-clock"></i>
                        <div>
                            <h3>Jam Operasional</h3>
                            <p>Senin - Minggu: 08:00 - 22:00</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 Laundry Kita. Semua hak dilindungi undang-undang.</p>
        </div>
    </footer>
</body>
</html>