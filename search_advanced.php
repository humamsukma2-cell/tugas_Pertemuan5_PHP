<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pencarian Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>

<body class="bg-light">
    <div class="container py-4">

        <h1 class="mb-4"><i class="bi bi-book"></i> Pencarian Buku</h1>

        <?php
        session_start();

        $buku_list = [
            [
                "kode"      => "BK-001",
                "judul"     => "Pemrograman PHP untuk Pemula",
                "kategori"  => "Programming",
                "pengarang" => "Budi Raharjo",
                "penerbit"  => "Informatika",
                "tahun"     => 2023,
                "harga"     => 75000,
                "stok"      => 10
            ],
            [
                "kode"      => "BK-002",
                "judul"     => "Belajar Python dari Nol",
                "kategori"  => "Programming",
                "pengarang" => "Dwi Santoso",
                "penerbit"  => "Andi",
                "tahun"     => 2022,
                "harga"     => 82000,
                "stok"      =>0
            ],
            [
                "kode"      => "BK-003",
                "judul"     => "Desain UI/UX Modern",
                "kategori"  => "Web Design",
                "pengarang" => "Lestari Putri",
                "penerbit"  => "Elex Media",
                "tahun"     => 2023,
                "harga"     => 95000,
                "stok"      => 4
            ],
            [
                "kode"      => "BK-004",
                "judul"     => "SQL Server untuk Bisnis",
                "kategori"  => "Database",
                "pengarang" => "Hendra Kurniawan",
                "penerbit"  => "Graha Ilmu",
                "tahun"     => 2021,
                "harga"     => 110000,
                "stok"      => 0
            ],
            [
                "kode"      => "BK-005",
                "judul"     => "Responsive Web dengan CSS",
                "kategori"  => "Web Design",
                "pengarang" => "Nia Permata",
                "penerbit"  => "Informatika",
                "tahun"     => 2024,
                "harga"     => 78000,
                "stok"      => 12
            ],
            [
                "kode"      => "BK-006",
                "judul"     => "React JS untuk Developer",
                "kategori"  => "Programming",
                "pengarang" => "Fajar Nugroho",
                "penerbit"  => "Erlangga",
                "tahun"     => 2024,
                "harga"     => 130000,
                "stok"      => 6
            ],
            [
                "kode"      => "BK-007",
                "judul"     => "MySQL Database Lengkap",
                "kategori"  => "Database",
                "pengarang" => "Rini Anggraini",
                "penerbit"  => "Graha Ilmu",
                "tahun"     => 2023,
                "harga"     => 98000,
                "stok"      => 9
            ],
            [
                "kode"      => "BK-008",
                "judul"     => "Node.js dan Express",
                "kategori"  => "Programming",
                "pengarang" => "Bagas Pratama",
                "penerbit"  => "Andi",
                "tahun"     => 2022,
                "harga"     => 115000,
                "stok"      => 0
            ],
            [
                "kode"      => "BK-009",
                "judul"     => "Figma untuk UI Designer",
                "kategori"  => "Web Design",
                "pengarang" => "Sari Dewi",
                "penerbit"  => "Informatika",
                "tahun"     => 2024,
                "harga"     => 88000,
                "stok"      => 15
            ],
            [
                "kode"      => "BK-010",
                "judul"     => "Redis dan NoSQL Dasar",
                "kategori"  => "Database",
                "pengarang" => "Teguh Wibowo",
                "penerbit"  => "Elex Media",
                "tahun"     => 2023,
                "harga"     => 105000,
                "stok"      => 0
            ],
        ];

        // Ambil parameter GET
        $keyword   = $_GET['keyword']   ?? '';
        $kategori  = $_GET['kategori']  ?? '';
        $min_harga = $_GET['min_harga'] ?? '';
        $max_harga = $_GET['max_harga'] ?? '';
        $tahun     = $_GET['tahun']     ?? '';
        $status    = $_GET['status']    ?? 'semua';
        $sort      = $_GET['sort']      ?? 'judul';
        $page      = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $per_page  = 10;

        // Validasi input
        $errors = [];

        if (!empty($min_harga) && !empty($max_harga)) {
            if ($min_harga > $max_harga) {
                $errors[] = "Harga minimum tidak boleh lebih besar dari harga maksimum.";
            }
        }

        if (!empty($tahun)) {
            if ($tahun < 1900 || $tahun > date('Y')) {
                $errors[] = "Tahun harus antara 1900 sampai " . date('Y') . ".";
            }
        }

        // Simpan recent searches ke session
        if (!empty($keyword) && empty($errors)) {
            if (!isset($_SESSION['recent_searches'])) {
                $_SESSION['recent_searches'] = [];
            }
            if (!in_array($keyword, $_SESSION['recent_searches'])) {
                array_unshift($_SESSION['recent_searches'], $keyword);
                $_SESSION['recent_searches'] = array_slice($_SESSION['recent_searches'], 0, 5);
            }
        }

        // Proses filter
        $hasil = [];
        if (empty($errors)) {
            foreach ($buku_list as $buku) {

                // Filter keyword (judul atau pengarang)
                if (!empty($keyword)) {
                    $kw = strtolower($keyword);
                    if (
                        strpos(strtolower($buku['judul']), $kw) === false &&
                        strpos(strtolower($buku['pengarang']), $kw) === false
                    ) {
                        continue;
                    }
                }

                // Filter kategori
                if (!empty($kategori) && $buku['kategori'] != $kategori) continue;

                // Filter harga
                if (!empty($min_harga) && $buku['harga'] < $min_harga) continue;
                if (!empty($max_harga) && $buku['harga'] > $max_harga) continue;

                // Filter tahun
                if (!empty($tahun) && $buku['tahun'] != $tahun) continue;

                // Filter status stok
                if ($status == 'tersedia' && $buku['stok'] == 0) continue;
                if ($status == 'habis'    && $buku['stok'] >  0) continue;

                $hasil[] = $buku;
            }

            // Sorting
            usort($hasil, function ($a, $b) use ($sort) {
                if ($sort == 'harga') return $a['harga'] - $b['harga'];
                if ($sort == 'tahun') return $b['tahun'] - $a['tahun'];
                return strcasecmp($a['judul'], $b['judul']);
            });
        }

        // Pagination
        $total_hasil = count($hasil);
        $total_page  = max(1, ceil($total_hasil / $per_page));
        $page        = max(1, min($page, $total_page));
        $offset      = ($page - 1) * $per_page;
        $hasil_page  = array_slice($hasil, $offset, $per_page);

        // Fungsi highlight keyword
        function highlight($text, $keyword)
        {
            if (empty($keyword)) return $text;
            return preg_replace('/(' . preg_quote($keyword, '/') . ')/i', '<mark>$1</mark>', $text);
        }

        // Export CSV
        if (isset($_GET['export']) && $_GET['export'] == 'csv' && !empty($hasil)) {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="hasil_pencarian.csv"');
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Kode', 'Judul', 'Kategori', 'Pengarang', 'Penerbit', 'Tahun', 'Harga', 'Stok']);
            foreach ($hasil as $b) fputcsv($out, $b);
            fclose($out);
            exit;
        }

        // Query string untuk pagination & export
        $query_params = $_GET;
        unset($query_params['page'], $query_params['export']);
        $query_string = http_build_query($query_params);
        ?>

        <!-- Recent Searches -->
        <?php if (!empty($_SESSION['recent_searches'])): ?>
            <div class="mb-3">
                <small class="text-muted">
                    <i class="bi bi-clock-history"></i> Pencarian terakhir:
                    <?php foreach ($_SESSION['recent_searches'] as $recent): ?>
                        <a href="?keyword=<?= urlencode($recent) ?>" class="badge bg-secondary text-decoration-none me-1">
                            <?= htmlspecialchars($recent) ?>
                        </a>
                    <?php endforeach; ?>
                </small>
            </div>
        <?php endif; ?>

        <!-- Form Pencarian -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <form method="GET" action="">

                    <!-- Keyword -->
                    <div class="mb-3">
                        <label class="form-label">Keyword (Judul / Pengarang)</label>
                        <input type="text" class="form-control" name="keyword"
                            value="<?= htmlspecialchars($keyword) ?>"
                            placeholder="Cari judul atau pengarang...">
                    </div>

                    <div class="row g-3">
                        <!-- Kategori -->
                        <div class="col-md-4">
                            <label class="form-label">Kategori</label>
                            <select class="form-select" name="kategori">
                                <option value="">-- Semua --</option>
                                <option value="Programming" <?= $kategori == 'Programming' ? 'selected' : '' ?>>Programming</option>
                                <option value="Database" <?= $kategori == 'Database'    ? 'selected' : '' ?>>Database</option>
                                <option value="Web Design" <?= $kategori == 'Web Design'  ? 'selected' : '' ?>>Web Design</option>
                            </select>
                        </div>

                        <!-- Tahun -->
                        <div class="col-md-4">
                            <label class="form-label">Tahun Terbit</label>
                            <input type="number" class="form-control" name="tahun"
                                value="<?= htmlspecialchars($tahun) ?>"
                                min="1900" max="<?= date('Y') ?>" placeholder="contoh: 2023">
                        </div>

                        <!-- Sort -->
                        <div class="col-md-4">
                            <label class="form-label">Urutkan</label>
                            <select class="form-select" name="sort">
                                <option value="judul" <?= $sort == 'judul' ? 'selected' : '' ?>>Judul (A-Z)</option>
                                <option value="harga" <?= $sort == 'harga' ? 'selected' : '' ?>>Harga (Termurah)</option>
                                <option value="tahun" <?= $sort == 'tahun' ? 'selected' : '' ?>>Tahun (Terbaru)</option>
                            </select>
                        </div>

                        <!-- Min Harga -->
                        <div class="col-md-4">
                            <label class="form-label">Harga Minimum (Rp)</label>
                            <input type="number" class="form-control" name="min_harga"
                                value="<?= htmlspecialchars($min_harga) ?>"
                                min="0" placeholder="contoh: 50000">
                        </div>

                        <!-- Max Harga -->
                        <div class="col-md-4">
                            <label class="form-label">Harga Maksimum (Rp)</label>
                            <input type="number" class="form-control" name="max_harga"
                                value="<?= htmlspecialchars($max_harga) ?>"
                                min="0" placeholder="contoh: 150000">
                        </div>

                        <!-- Status -->
                        <div class="col-md-4">
                            <label class="form-label">Status</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" value="semua"
                                    <?= $status == 'semua'    ? 'checked' : '' ?>>
                                <label class="form-check-label">Semua</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" value="tersedia"
                                    <?= $status == 'tersedia' ? 'checked' : '' ?>>
                                <label class="form-check-label">Tersedia</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" value="habis"
                                    <?= $status == 'habis'    ? 'checked' : '' ?>>
                                <label class="form-check-label">Habis</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Cari
                        </button>
                        <a href="?" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Reset
                        </a>
                    </div>

                </form>
            </div>
        </div>

        <!-- Pesan Error -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $e): ?>
                    <div><i class="bi bi-exclamation-triangle"></i> <?= $e ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Hasil Pencarian -->
        <?php if (empty($errors)): ?>
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>
                        Ditemukan: <strong><?= $total_hasil ?> buku</strong>
                    </span>
                    <?php if ($total_hasil > 0): ?>
                        <a href="?<?= $query_string ?>&export=csv" class="btn btn-sm btn-outline-success">
                            <i class="bi bi-download"></i> Export CSV
                        </a>
                    <?php endif; ?>
                </div>
                <div class="card-body p-0">

                    <?php if ($total_hasil == 0): ?>
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            Tidak ada buku yang ditemukan.
                        </div>
                    <?php else: ?>
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Judul</th>
                                    <th>Kategori</th>
                                    <th>Pengarang</th>
                                    <th>Tahun</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = $offset + 1;
                                foreach ($hasil_page as $buku):
                                    $row = ($buku['stok'] == 0) ? 'table-info' : '';
                                ?>
                                    <tr class="<?= $row ?>">
                                        <td><?= $no++ ?></td>
                                        <td><?= $buku['kode'] ?></td>
                                        <td><?= highlight($buku['judul'], $keyword) ?></td>
                                        <td>
                                            <?php
                                            $badge = match ($buku['kategori']) {
                                                'Programming' => 'bg-primary',
                                                'Database'    => 'bg-success',
                                                'Web Design'  => 'bg-warning text-dark',
                                                default       => 'bg-secondary'
                                            };
                                            ?>
                                            <span class="badge <?= $badge ?>"><?= $buku['kategori'] ?></span>
                                        </td>
                                        <td><?= highlight($buku['pengarang'], $keyword) ?></td>
                                        <td><?= $buku['tahun'] ?></td>
                                        <td>Rp <?= number_format($buku['harga'], 0, ',', '.') ?></td>
                                        <td><?= $buku['stok'] ?></td>
                                        <td>
                                            <?php if ($buku['stok'] > 0): ?>
                                                <span class="badge bg-success">Tersedia</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Habis</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <?php if ($total_page > 1): ?>
                            <div class="p-3">
                                <nav>
                                    <ul class="pagination justify-content-center mb-1">
                                        <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                                            <a class="page-link" href="?<?= $query_string ?>&page=<?= $page - 1 ?>">
                                                &laquo; Prev
                                            </a>
                                        </li>
                                        <?php for ($p = 1; $p <= $total_page; $p++): ?>
                                            <li class="page-item <?= $p == $page ? 'active' : '' ?>">
                                                <a class="page-link" href="?<?= $query_string ?>&page=<?= $p ?>"><?= $p ?></a>
                                            </li>
                                        <?php endfor; ?>
                                        <li class="page-item <?= $page == $total_page ? 'disabled' : '' ?>">
                                            <a class="page-link" href="?<?= $query_string ?>&page=<?= $page + 1 ?>">
                                                Next &raquo;
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                                <p class="text-center text-muted small mb-0">
                                    Halaman <?= $page ?> dari <?= $total_page ?>
                                    (<?= count($hasil_page) ?> dari <?= $total_hasil ?> buku)
                                </p>
                            </div>
                        <?php endif; ?>

                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>