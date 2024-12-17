<?php
session_start();
include 'koneksi.php';

// Simulasi user_id jika sesi belum tersedia
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Simulasi user ID sementara untuk testing
}

// Tangkap bus_id
if (!isset($_GET['bus_id'])) {
    die("Bus tidak ditemukan.");
}
$bus_id = intval($_GET['bus_id']);

// Inisialisasi variabel kosong
$tanggal = $jam = '';
$kursi = 0;

// Tangani form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : '';
    $jam = isset($_POST['jam']) ? $_POST['jam'] : '';
    $kursi = isset($_POST['kursi']) ? intval($_POST['kursi']) : 0;

    // Validasi input
    if (empty($tanggal) || empty($jam) || $kursi <= 0) {
        $error_message = "Harap isi semua kolom dengan benar.";
    } else {
        // Simpan ke database
        $query = "INSERT INTO reservations (user_id, bus_id, tanggal, jam, kursi) VALUES (?, ?, ?, ?, ?)";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param("iissi", $user_id, $bus_id, $tanggal, $jam, $kursi);

        if ($stmt->execute()) {
            header("Location: notif-success.php");
            exit();
        } else {
            $error_message = "Terjadi kesalahan saat menyimpan data.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pemesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header text-center bg-primary text-white">
            <h4>Form Pemesanan Penumpang</h4>
        </div>
        <div class="card-body">
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error_message); ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal Keberangkatan</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="jam" class="form-label">Jam Keberangkatan</label>
                    <input type="time" name="jam" id="jam" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="kursi" class="form-label">Jumlah Kursi</label>
                    <input type="number" name="kursi" id="kursi" class="form-control" min="1" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Reservasi</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
