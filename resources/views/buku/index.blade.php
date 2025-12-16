<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen Data Buku | Perpustakaan Digital Premium</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        /* CSS Kustom untuk Tampilan Premium & Modern */
        :root {
            --color-primary: #00bee9; /* Ungu Gelap (Kesan Mewah) */
            --color-accent: #FFC300; /* Kuning Emas (Aksen Kontras) */
            --color-text: #333333;
            --color-background: #c3c3c3; /* Abu-abu Muda Hangat */
            --card-bg: #def4f9;
            --shadow-strong: 0 10px 30px rgba(0, 0, 0, 0.1);
            --shadow-subtle: 0 5px 15px rgba(0, 0, 0, 0.2);
            
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--color-background);
            color: var(--color-text);
            padding-top: 5rem; /* Memberi ruang untuk fixed header */
        }

        /* HEADER FIX: Bar Navigasi Premium */
        .premium-header {
            background-color: var(--color-primary);
            box-shadow: var(--shadow-strong);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            padding: 1.2rem 0;
        }
        .premium-header h3 {
            font-weight: 800;
            color: #ffffff;
            margin: 0;
        }

        /* CARD STYLE: Untuk Form dan Daftar Buku */
        .data-card {
            background-color: var(--card-bg);
            border-radius: 15px;
            box-shadow: var(--shadow-subtle);
            padding: 3rem;
            margin-bottom: 2.5rem;
            border-left: 5px solid var(--color-accent);
            transition: all 0.4s ease;
        }
        .data-card:hover {
            box-shadow: var(--shadow-strong);
            transform: translateY(-3px);
        }

        /* TYPOGRAPHY */
        h4 {
            font-weight: 700;
            color: var(--color-primary);
            margin-bottom: 2rem;
            border-bottom: 2px solid var(--color-accent);
            padding-bottom: 0.5rem;
            display: inline-block;
        }

        /* FORM STYLE */
        .form-control {
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 0.8rem 1rem;
        }
        .form-control:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 0.25rem rgba(90, 24, 154, 0.15);
        }

        /* BUTTONS */
        .btn {
            font-weight: 600;
            border-radius: 50px; /* Tombol Pilul */
            padding: 0.75rem 2rem;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .btn-primary {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
        }
        .btn-primary:hover {
            background-color: #48147d;
            border-color: #48147d;
        }
        .btn-warning {
            background-color: var(--color-accent);
            border-color: var(--color-accent);
            color: var(--color-text);
        }
        .btn-warning:hover {
            background-color: #e6b200;
            border-color: #e6b200;
        }

        /* LIST BUKU: Mengganti Tabel dengan Card Grid */
        .book-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }
        
        .book-item-card {
            background-color: var(--card-bg);
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--shadow-subtle);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
            border-top: 8px solid var(--color-accent);
        }
        .book-item-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        
        .book-title {
            font-weight: 700;
            font-size: 1.3rem;
            color: var(--color-primary);
            margin-bottom: 10px;
        }
        
        .book-meta p {
            margin-bottom: 5px;
            font-size: 0.95rem;
        }
        .book-meta span {
            font-weight: 600;
            color: #555;
        }
        
        .action-buttons {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px dashed #eee;
        }
    </style>
</head>

<body>
    
    <header class="premium-header">
        <div class="container">
            <h3 class="d-flex align-items-center">
                <i class="fas fa-book-sparkles me-3" style="color: var(--color-accent);"></i> 
                Perpustakaan Digital Premium
            </h3>
        </div>
    </header>

    <main class="container py-5">
        
        <div class="data-card">
            
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <h5 class="alert-heading fw-bold"><i class="fas fa-exclamation-triangle me-2"></i> Perhatian! Ada Kesalahan Input</h5>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                </div>
            @endif
            
            <h4 class="mb-4">
                {{ $mode == 'edit' ? '✏️ Edit Data Buku' : '➕ Tambah Data Buku Baru' }}
            </h4>

            <form action="{{ $mode == 'edit' ? url('buku/'.$editData['id']) : url('buku') }}" method="POST">
                @csrf
                @if($mode == 'edit')
                    @method('PUT')
                @endif

                <div class="mb-4 row align-items-center">
                    <label for="judul" class="col-sm-3 col-form-label">Judul Buku</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="judul" id="judul"
                            value="{{ old('judul', $editData['judul'] ?? '') }}" placeholder="Masukkan Judul Buku yang Menarik">
                    </div>
                </div>

                <div class="mb-4 row align-items-center">
                    <label for="pengarang" class="col-sm-3 col-form-label">Pengarang</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="pengarang" id="pengarang"
                            value="{{ old('pengarang', $editData['pengarang'] ?? '') }}" placeholder="Nama Lengkap Pengarang">
                    </div>
                </div>

                <div class="mb-4 row align-items-center">
                    <label for="tanggal_publikasi" class="col-sm-3 col-form-label">Tanggal Rilis</label>
                    <div class="col-sm-9">
                        <input type="date" class="form-control w-100 w-md-50" name="tanggal_publikasi" id="tanggal_publikasi"
                            value="{{ old('tanggal_publikasi', $editData['tanggal_publikasi'] ?? '') }}">
                    </div>
                </div>

                <div class="row pt-4">
                    <div class="col-sm-9 offset-sm-3">
                        <button type="submit" class="btn btn-primary me-3">
                            <i class="fas fa-rocket me-1"></i>
                            {{ $mode == 'edit' ? 'UPDATE SEKARANG' : 'SIMPAN DATA' }}
                        </button>

                        @if($mode == 'edit')
                            <a href="{{ url('buku') }}" class="btn btn-secondary" style="border-radius: 50px; padding: 0.75rem 1.5rem;">
                                BATAL
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <div class="data-card">

            <h4>✨ Koleksi Buku Tersedia</h4>
            
            @if (count($data) > 0)
                <div class="book-grid">
                    @php $i = 1; @endphp
                    @foreach ($data as $item)
                        <div class="book-item-card">
                            <span class="badge bg-dark position-absolute top-0 end-0 m-2" style="font-size: 1rem; background-color: var(--color-primary) !important;">#{{ $i++ }}</span>
                            
                            <h5 class="book-title">{{ $item['judul'] }}</h5>
                            
                            <div class="book-meta">
                                <p><i class="fas fa-user-edit me-2" style="color: var(--color-accent);"></i> 
                                    Pengarang: <span>{{ $item['pengarang'] }}</span>
                                </p>
                                <p><i class="fas fa-calendar-alt me-2" style="color: var(--color-accent);"></i> 
                                    Rilis: <span>{{ date('d F Y', strtotime($item['tanggal_publikasi'])) }}</span>
                                </p>
                            </div>

                            <div class="action-buttons">
                                <a href="{{ url('buku/'.$item['id'].'/edit') }}"
                                    class="btn btn-warning btn-sm me-2">
                                    <i class="fas fa-pencil-alt"></i> Edit
                                </a>

                                <form action="{{ url('buku/'.$item['id']) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button onclick="return confirm('Yakin hapus buku {{ $item['judul'] }}?')"
                                        class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i> Hapus
                                    </button>
                                    
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center p-5" style="border: 2px dashed #ccc; border-radius: 10px;">
                    <i class="fas fa-box-open fa-3x mb-3" style="color: #ccc;"></i>
                    <p class="mb-0 fs-5 text-muted">Koleksi buku Anda masih kosong. Mari tambahkan data pertama!</p>
                </div>
            @endif

        </div>

    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>