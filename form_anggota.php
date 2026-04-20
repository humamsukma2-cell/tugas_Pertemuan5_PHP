<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Anggota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>

<body class="bg-light">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-7">

                <?php
                // Fungsi sanitasi input
                function sanitize_input($data)
                {
                    $data = trim($data);
                    $data = stripslashes($data);
                    $data = htmlspecialchars($data);
                    return $data;
                }

                // Inisialisasi variabel
                $errors  = [];
                $success = '';

                $nama_lengkap  = '';
                $email         = '';
                $telepon       = '';
                $alamat        = '';
                $jenis_kelamin = '';
                $tanggal_lahir = '';
                $pekerjaan     = '';
                $umur          = 0;

                // Proses form jika POST
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                    // Ambil dan sanitasi data
                    $nama_lengkap  = sanitize_input($_POST['nama_lengkap']  ?? '');
                    $email         = sanitize_input($_POST['email']         ?? '');
                    $telepon       = sanitize_input($_POST['telepon']       ?? '');
                    $alamat        = sanitize_input($_POST['alamat']        ?? '');
                    $jenis_kelamin = sanitize_input($_POST['jenis_kelamin'] ?? '');
                    $tanggal_lahir = sanitize_input($_POST['tanggal_lahir'] ?? '');
                    $pekerjaan     = sanitize_input($_POST['pekerjaan']     ?? '');

                    // Validasi nama lengkap
                    if (empty($nama_lengkap)) {
                        $errors['nama_lengkap'] = "Nama lengkap wajib diisi";
                    } elseif (strlen($nama_lengkap) < 3) {
                        $errors['nama_lengkap'] = "Nama lengkap minimal 3 karakter";
                    } elseif (strlen($nama_lengkap) > 200) {
                        $errors['nama_lengkap'] = "Nama lengkap maksimal 200 karakter";
                    }

                    // Validasi email
                    if (empty($email)) {
                        $errors['email'] = "Email wajib diisi";
                    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $errors['email'] = "Format email tidak valid";
                    }

                    // Validasi telepon
                    if (empty($telepon)) {
                        $errors['telepon'] = "Telepon wajib diisi";
                    } elseif (!preg_match('/^08\d{8,11}$/', $telepon)) {
                        $errors['telepon'] = "Format telepon tidak valid (contoh: 081234567890)";
                    }

                    // Validasi alamat
                    if (empty($alamat)) {
                        $errors['alamat'] = "Alamat wajib diisi";
                    } elseif (strlen($alamat) < 10) {
                        $errors['alamat'] = "Alamat minimal 10 karakter";
                    }

                    // Validasi jenis kelamin
                    if (empty($jenis_kelamin)) {
                        $errors['jenis_kelamin'] = "Jenis kelamin wajib dipilih";
                    } else {
                        $valid_jk = ['Laki-laki', 'Perempuan'];
                        if (!in_array($jenis_kelamin, $valid_jk)) {
                            $errors['jenis_kelamin'] = "Jenis kelamin tidak valid";
                        }
                    }

                    // Validasi tanggal lahir
                    if (empty($tanggal_lahir)) {
                        $errors['tanggal_lahir'] = "Tanggal lahir wajib diisi";
                    } else {
                        $tgl_lahir = new DateTime($tanggal_lahir);
                        $hari_ini  = new DateTime();
                        $umur      = $hari_ini->diff($tgl_lahir)->y;
                        if ($umur < 10) {
                            $errors['tanggal_lahir'] = "Umur minimal 10 tahun";
                        }
                    }

                    // Validasi pekerjaan
                    if (empty($pekerjaan)) {
                        $errors['pekerjaan'] = "Pekerjaan wajib dipilih";
                    } else {
                        $valid_pekerjaan = ['Pelajar', 'Mahasiswa', 'Pegawai', 'Lainnya'];
                        if (!in_array($pekerjaan, $valid_pekerjaan)) {
                            $errors['pekerjaan'] = "Pekerjaan tidak valid";
                        }
                    }

                    // Jika tidak ada error
                    if (count($errors) == 0) {
                        $success = "Registrasi anggota berhasil!";
                    }
                }
                ?>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="mb-4"><i class="bi bi-person-plus"></i> Registrasi Anggota</h1>

                        <!-- Pesan sukses dan data -->
                        <?php if ($success): ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="bi bi-check-circle-fill"></i> <?= $success ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>

                            <div class="card border-success mb-4">
                                <div class="card-header bg-success text-white">
                                    <i class="bi bi-person-check"></i> Data Anggota Terdaftar
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless mb-0">
                                        <tr>
                                            <th width="160">Nama Lengkap</th>
                                            <td>: <?= $nama_lengkap ?></td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td>: <?= $email ?></td>
                                        </tr>
                                        <tr>
                                            <th>Telepon</th>
                                            <td>: <?= $telepon ?></td>
                                        </tr>
                                        <tr>
                                            <th>Alamat</th>
                                            <td>: <?= $alamat ?></td>
                                        </tr>
                                        <tr>
                                            <th>Jenis Kelamin</th>
                                            <td>: <?= $jenis_kelamin ?></td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Lahir</th>
                                            <td>: <?= date('d-m-Y', strtotime($tanggal_lahir)) ?></td>
                                        </tr>
                                        <tr>
                                            <th>Umur</th>
                                            <td>: <?= $umur ?> tahun</td>
                                        </tr>
                                        <tr>
                                            <th>Pekerjaan</th>
                                            <td>: <?= $pekerjaan ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Ringkasan error -->
                        <?php if (count($errors) > 0): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                Terdapat <?= count($errors) ?> kesalahan:
                                <ul class="mb-0 mt-1">
                                    <?php foreach ($errors as $field => $error): ?>
                                        <li><strong><?= ucfirst(str_replace('_', ' ', $field)) ?>:</strong> <?= $error ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Form -->
                        <form method="POST" action="" novalidate>

                            <!-- Nama Lengkap -->
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text"
                                    class="form-control <?= isset($errors['nama_lengkap']) ? 'is-invalid' : '' ?>"
                                    name="nama_lengkap"
                                    value="<?= $nama_lengkap ?>"
                                    placeholder="Masukkan nama lengkap">
                                <?php if (isset($errors['nama_lengkap'])): ?>
                                    <div class="invalid-feedback"><?= $errors['nama_lengkap'] ?></div>
                                <?php endif; ?>
                                <small class="text-muted">Minimal 3 karakter, maksimal 200 karakter</small>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email"
                                    class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                                    name="email"
                                    value="<?= $email ?>"
                                    placeholder="contoh@email.com">
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback"><?= $errors['email'] ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Telepon -->
                            <div class="mb-3">
                                <label class="form-label">Telepon <span class="text-danger">*</span></label>
                                <input type="text"
                                    class="form-control <?= isset($errors['telepon']) ? 'is-invalid' : '' ?>"
                                    name="telepon"
                                    value="<?= $telepon ?>"
                                    placeholder="081234567890">
                                <?php if (isset($errors['telepon'])): ?>
                                    <div class="invalid-feedback"><?= $errors['telepon'] ?></div>
                                <?php endif; ?>
                                <small class="text-muted">Format: 08xxxxxxxxxx (10-13 digit)</small>
                            </div>

                            <!-- Alamat -->
                            <div class="mb-3">
                                <label class="form-label">Alamat <span class="text-danger">*</span></label>
                                <textarea
                                    class="form-control <?= isset($errors['alamat']) ? 'is-invalid' : '' ?>"
                                    name="alamat"
                                    rows="3"
                                    placeholder="Masukkan alamat lengkap"><?= $alamat ?></textarea>
                                <?php if (isset($errors['alamat'])): ?>
                                    <div class="invalid-feedback"><?= $errors['alamat'] ?></div>
                                <?php endif; ?>
                                <small class="text-muted">Minimal 10 karakter</small>
                            </div>

                            <div class="row">
                                <!-- Jenis Kelamin -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="jenis_kelamin"
                                                value="Laki-laki"
                                                <?= ($jenis_kelamin == 'Laki-laki') ? 'checked' : '' ?>>
                                            <label class="form-check-label">
                                                <i class="bi bi-male"></i> Laki-laki
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="jenis_kelamin"
                                                value="Perempuan"
                                                <?= ($jenis_kelamin == 'Perempuan') ? 'checked' : '' ?>>
                                            <label class="form-check-label">
                                                <i class="bi bi-female"></i> Perempuan
                                            </label>
                                        </div>
                                        <?php if (isset($errors['jenis_kelamin'])): ?>
                                            <div class="text-danger small mt-1">
                                                <i class="bi bi-exclamation-circle"></i> <?= $errors['jenis_kelamin'] ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Tanggal Lahir -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                    <input type="date"
                                        class="form-control <?= isset($errors['tanggal_lahir']) ? 'is-invalid' : '' ?>"
                                        name="tanggal_lahir"
                                        value="<?= $tanggal_lahir ?>"
                                        max="<?= date('Y-m-d', strtotime('-10 years')) ?>">
                                    <?php if (isset($errors['tanggal_lahir'])): ?>
                                        <div class="invalid-feedback"><?= $errors['tanggal_lahir'] ?></div>
                                    <?php endif; ?>
                                    <small class="text-muted">Umur minimal 10 tahun</small>
                                </div>
                            </div>

                            <!-- Pekerjaan -->
                            <div class="mb-3">
                                <label class="form-label">Pekerjaan <span class="text-danger">*</span></label>
                                <select class="form-select <?= isset($errors['pekerjaan']) ? 'is-invalid' : '' ?>"
                                    name="pekerjaan">
                                    <option value="">-- Pilih Pekerjaan --</option>
                                    <option value="Pelajar" <?= ($pekerjaan == 'Pelajar')   ? 'selected' : '' ?>>Pelajar</option>
                                    <option value="Mahasiswa" <?= ($pekerjaan == 'Mahasiswa') ? 'selected' : '' ?>>Mahasiswa</option>
                                    <option value="Pegawai" <?= ($pekerjaan == 'Pegawai')   ? 'selected' : '' ?>>Pegawai</option>
                                    <option value="Lainnya" <?= ($pekerjaan == 'Lainnya')   ? 'selected' : '' ?>>Lainnya</option>
                                </select>
                                <?php if (isset($errors['pekerjaan'])): ?>
                                    <div class="invalid-feedback"><?= $errors['pekerjaan'] ?></div>
                                <?php endif; ?>
                            </div>

                            <hr>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Daftarkan
                                </button>
                                <button type="reset" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle"></i> Reset
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>