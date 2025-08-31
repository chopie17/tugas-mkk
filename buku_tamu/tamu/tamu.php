<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../admin/index.php");
    exit();
}

include '../koneksi.php';
$username = $_SESSION['username'];

// Ambil input filter tanggal
$tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : '';
$tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : '';

// Pagination
$batas = 10;
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$mulai = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

// Filter query
$filter_sql = "";
if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
    $filter_sql = " WHERE tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'";
}

// Query data tamu
$sql = "SELECT * FROM tamu $filter_sql ORDER BY tanggal DESC, waktu DESC, id DESC LIMIT $mulai, $batas";
$data = mysqli_query($conn, $sql);

// Hitung total data
$total_data = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tamu $filter_sql"));
$total_halaman = ceil($total_data / $batas);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Daftar Tamu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h2 {
            margin: 0;
            font-size: 22px;
            display: flex;
            align-items: center;
        }
        .header h2 i {
            margin-right: 8px;
        }
        .admin-name {
            font-size: 14px;
            font-weight: bold;
        }
        .container {
            max-width: 1000px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .filter {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        .filter label {
            font-weight: bold;
        }
        .filter input[type="date"] {
            padding: 5px 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .filter button {
            background: #007bff;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
        }
        .filter .reset-btn {
            background: #dc3545;
            color: white;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background: #343a40;
            color: white;
        }
        table tr:hover {
            background-color: #f1f1f1;
        }
        .pagination {
            margin-top: 20px;
            text-align: center;
        }
        .pagination a {
            display: inline-block;
            padding: 8px 12px;
            margin: 0 3px;
            border-radius: 4px;
            background-color: #e9ecef;
            color: #333;
            text-decoration: none;
        }
        .pagination a.active {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        .btn-back {
            display: inline-block;
            margin-top: 20px;
            background-color: #6c757d;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
        }
        .btn-back i {
            margin-right: 5px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2><i class="fas fa-users"></i> Daftar Tamu</h2>
        <div class="admin-name">
         <?= htmlspecialchars($username) ?>
        </div>
    </div>

    <div class="container">
        <form class="filter" method="GET" action="">
            <label><i class="fas fa-calendar-alt"></i> Dari:</label>
            <input type="date" name="tanggal_awal" value="<?= htmlspecialchars($tanggal_awal) ?>">

            <label>Sampai:</label>
            <input type="date" name="tanggal_akhir" value="<?= htmlspecialchars($tanggal_akhir) ?>">

            <button type="submit"><i class="fas fa-search"></i> Tampilkan</button>
            <a href="tamu.php" class="reset-btn">Reset</a>
        </form>

        <table>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Instansi</th>
                <th>Keperluan</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                 <th>Aksi</th>
            </tr>
            
            <?php 
            $no = $mulai + 1;
            if (mysqli_num_rows($data) > 0) {
                while ($row = mysqli_fetch_assoc($data)) {
                    echo "<tr>
                        <td>$no</td>
                        <td>{$row['nama']}</td>
                        <td>{$row['instansi']}</td>
                        <td>{$row['keperluan']}</td>
                        <td>{$row['tanggal']}</td>
                        <td>{$row['waktu']}</td>
                         <td>
                <a href='delete_tamu.php?id={$row['id']}' 
                   onclick=\"return confirm('Yakin ingin menghapus data ini?')\" 
                   style='color: red; font-size: 18px;'>
                   <i class='fas fa-minus-circle'></i>
                </a>
            </td>
                    </tr>";
                    $no++;
                }
            } 
            else {
                echo "<tr><td colspan='6' style='text-align:center;'>Tidak ada data tamu</td></tr>";
            }
            ?>
        </table>

        <div class="pagination">
            <?php for ($i = 1; $i <= $total_halaman; $i++) : ?>
                <a class="<?= ($i == $halaman) ? 'active' : '' ?>" href="?halaman=<?= $i ?>&tanggal_awal=<?= urlencode($tanggal_awal) ?>&tanggal_akhir=<?= urlencode($tanggal_akhir) ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>

        <a href="../admin/dashboard.php" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
    </div>

</body>
</html>