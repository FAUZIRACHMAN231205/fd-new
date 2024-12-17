<?php
session_start();
include 'koneksi.php';

// Query data bus
$query = "SELECT * FROM buses";
$result = $koneksi->query($query);

if (!$result) {
    die("Query gagal: " . $koneksi->error);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Jadwal Bus</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }

        .card-schedule {
            background: #fff;
            border: none;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .schedule-info p {
            margin: 0;
            color: #555;
        }

        .schedule-time {
            font-size: 28px;
            font-weight: bold;
            color: #333;
        }

        .status-seats {
            color: #28a745;
            font-weight: bold;
        }

        .btn-pilih {
            background-color: #ff6f00;
            color: white;
            border-radius: 20px;
        }

        .btn-habis {
            background-color: #ccc;
            color: white;
            border-radius: 20px;
        }

        /* Sidebar */
        .form-section {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .form-section h4 {
            color: #333;
        }

        .btn-danger {
            background-color: #ff6f00;
            border: none;
        }

        /* Image */
        .bus-image {
            width: 120px;
            height: auto;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <!-- Bagian Jadwal -->
            <div class="col-md-8">
                <h3 class="mb-4">Jadwal Bus</h3>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="card-schedule">
                            <!-- Gambar Bus -->
                            <div>
                                <?php if (!empty($row['foto_bus'])): ?>
                                    <img src="<?= htmlspecialchars($row['foto_bus']); ?>" alt="Foto Bus" class="bus-image">
                                <?php else: ?>
                                    <img src="default_bus.png" alt="Foto Default" class="bus-image">
                                <?php endif; ?>
                            </div>

                            <!-- Informasi Jadwal -->
                            <div class="schedule-info">
                                <p><strong><?= htmlspecialchars($row['keberangkatan']); ?> - <?= htmlspecialchars($row['tujuan']); ?></strong></p>
                                <p class="schedule-time"><?= htmlspecialchars($row['departure_time']); ?></p>
                                <p>
                                    <span class="status-seats">
                                        <?= ($row['available_seats'] > 0) ? "Tersedia {$row['available_seats']} Kursi" : "Tersedia 0 Kursi"; ?>
                                    </span>
                                </p>
                            </div>

                            <!-- Harga dan Tombol -->
                            <div>
                                <p class="mb-2 font-weight-bold text-success">Rp <?= number_format($row['price']); ?></p>
                                <?php if ($row['available_seats'] == 0): ?>
    <button class="btn btn-habis" disabled>HABIS</button>
<?php else: ?>
    <a href="reservasi.php?bus_id=<?= $row['id']; ?>" class="btn btn-pilih">PILIH</a>
<?php endif; ?>

                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-center">Tidak ada jadwal bus tersedia.</p>
                <?php endif; ?>
            </div>

            <!-- Sidebar Formulir -->
            <div class="col-md-4">
                <div class="form-section">
                    <h4>Ubah Keberangkatan</h4>
                    <form action="dashboard.php" method="get">
                        <div class="form-group">
                            <label for="keberangkatan">Keberangkatan</label>
                            <select name="keberangkatan" id="keberangkatan" class="form-control">
                                <option value="KARAWANG - MELATI">KARAWANG - MELATI</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tujuan">Tujuan</label>
                            <select name="tujuan" id="tujuan" class="form-control">
                                <option value="BANDUNG - SUCI">BANDUNG - SUCI</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tanggal">Tanggal Berangkat</label>
                            <input type="date" id="tanggal" name="tanggal" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-danger btn-block mt-3">CARI</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
