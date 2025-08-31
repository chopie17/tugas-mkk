<?php
include '../koneksi.php';
session_start();

// Cek login
if (!isset($_SESSION['username'])) {
    header("Location: ../admin/index.php");
    exit();
}

// HANDLE DOWNLOAD EXCEL (XLS)
if (isset($_GET['download']) && $_GET['download'] == 'excel') {
    $tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : date("Y");
    $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=rekap_tamu_{$tahun}.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo "<table border='1'>";
    echo "<tr style='background-color:#4F81BD;color:#FFFFFF;font-weight:bold;'>
            <th>No</th>
            <th>Nama</th>
            <th>Tanggal</th>
            <th>Waktu</th>
          </tr>";

    $sql = "SELECT * FROM tamu WHERE YEAR(tanggal) = $tahun";
    if (!empty($search)) {
        $sql .= " AND nama LIKE '%$search%'";
    }
    // Urutkan dari yang terbaru
    $sql .= " ORDER BY tanggal DESC, waktu DESC";
    $result = mysqli_query($conn, $sql);

    $no = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$no}</td>
                <td>{$row['nama']}</td>
                <td>{$row['tanggal']}</td>
                <td>{$row['waktu']}</td>
              </tr>";
        $no++;
    }
    echo "</table>";
    exit();
}

// HANDLE TAMPILAN DATA DI WEB
$tahun_filter = isset($_GET['tahun']) ? (int)$_GET['tahun'] : date("Y");
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$sql = "SELECT * FROM tamu WHERE YEAR(tanggal) = $tahun_filter";
if (!empty($search)) {
    $sql .= " AND nama LIKE '%$search%'";
}
// Urutkan dari yang terbaru
$sql .= " ORDER BY tanggal DESC, waktu DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kehadiran Tahun <?php echo $tahun_filter; ?></title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f9f9f9;
        padding: 30px;
        color: #333;
    }

    h2 {
        margin-bottom: 20px;
    }

    .filter-container {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 15px;
        align-items: center;
    }

    select, input[type="text"] {
        padding: 8px 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
    }

    .btn {
        padding: 8px 14px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        transition: 0.2s ease;
    }

    .btn-blue {
        background-color: #3498db;
        color: #fff;
    }

    .btn-blue:hover {
        background-color: #2980b9;
    }

    .btn-green {
        background-color: #2ecc71;
        color: #fff;
    }

    .btn-green:hover {
        background-color: #27ae60;
    }

    .btn-gray {
        background-color: #7f8c8d;
        color: #fff;
    }

    .btn-gray:hover {
        background-color: #616a6b;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        background-color: #fff;
        border-radius: 6px;
        overflow: hidden;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    th, td {
        padding: 12px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #555;
        color: white;
    }

    .pagination {
        margin-top: 15px;
    }

    .pagination button {
        padding: 6px 12px;
        border: none;
        border-radius: 4px;
        background-color: #3498db;
        color: white;
        cursor: pointer;
    }

    .pagination button:hover {
        background-color: #2980b9;
    }
</style>

</head>
<body>

    <h2>Rekap Kehadiran Tahun <?php echo $tahun_filter; ?></h2>

    <form method="get" action="">
        <label for="tahun">Pilih Tahun:</label>
        <select name="tahun" id="tahun">
            <?php
            for ($i = 2020; $i <= date("Y"); $i++) {
                $selected = ($i == $tahun_filter) ? 'selected' : '';
                echo "<option value='$i' $selected>$i</option>";
            }
            ?>
        </select>

        <label for="search">Cari Nama:</label>
        <input type="text" name="search" id="search" placeholder="cari nama..." value="<?php echo htmlspecialchars($search); ?>">

        <button type="submit" class="btn btn-blue">Tampilkan</button>
        <a href="?download=excel&tahun=<?php echo $tahun_filter; ?>&search=<?php echo $search; ?>" class="btn btn-green">Download Excel</a>
    </form>

    <table>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Tanggal</th>
            <th>Waktu</th>
        </tr>
        <?php
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>".$no++."</td>";
            echo "<td>".$row['nama']."</td>";
            echo "<td>".$row['tanggal']."</td>";
            echo "<td>".$row['waktu']."</td>";
            echo "</tr>";
        }

        if (mysqli_num_rows($result) == 0) {
            echo "<tr><td colspan='4'>Tidak ada data tamu untuk tahun ini.</td></tr>";
        }
        ?>
    </table>

    <br>
    <a href="../admin/dashboard.php" class="btn btn-gray">← Kembali ke Dashboard</a>

</body>
</html>
