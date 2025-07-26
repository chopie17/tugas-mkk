<?php
// Koneksi ke database (pastikan path ini sesuai dengan lokasi file koneksi.php)
include '../koneksi.php';

// ✅ AKTIVASI ADMIN LANGSUNG DI FILE INI
if (isset($_GET['aktifkan_id'])) {
    $id = $_GET['aktifkan_id'];
    mysqli_query($conn, "UPDATE admin SET level = 'on' WHERE id = '$id'");
    header("Location: t_admin.php");
    exit();
}


// Pagination setup
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Ambil data admin
$result = mysqli_query($conn, "SELECT * FROM admin LIMIT $start, $limit");

// Hitung total data
$total_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM admin");
$total_row = mysqli_fetch_assoc($total_result);
$total_data = $total_row['total'];
$total_page = ceil($total_data / $limit);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #83a4d4, #b6fbff);
      padding: 30px;
      font-family: Arial, sans-serif;
    }

    .table-container {
      background: #ffffff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .table thead {
      background: #007bff;
      color: white;
    }

    .pagination .page-item.active .page-link {
      background-color: #007bff;
      border-color: #007bff;
      color: white;
    }
  </style>
</head>
<body>
<div class="container table-container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Data Admin</h2>
    <a href="create_admin.php" class="btn btn-success">+ Tambah Admin</a>
     <a href="delete.php" class="btn btn-success">- delete</a>
  </div>

  <table class="table table-striped table-hover text-center">
    <thead>
      <tr>
        <th>id</th>
        <th>email</th>
        <th>username</th>
        <th>password</th>
        <th>Level</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $no = $start + 1;
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$no}</td>
                <td>{$row['email']}</td>
                <td>{$row['username']}</td>
                <td>{$row['password']}</td>
                <td>{$row['level']}</td>
              </tr>";
        $no++;
      }

      if (mysqli_num_rows($result) === 0) {
        echo "<tr><td colspan='5'>Tidak ada data admin.</td></tr>";
      }
      ?>
    </tbody>
  </table>

  <!-- Pagination -->
  <nav>
    <ul class="pagination justify-content-center">
      <?php if ($page > 1): ?>
        <li class="page-item">
          <a class="page-link" href="?page=<?= $page - 1 ?>">&laquo;</a>
        </li>
      <?php endif; ?>

      <?php for ($i = 1; $i <= $total_page; $i++): ?>
        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
          <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
        </li>
      <?php endfor; ?>

      <?php if ($page < $total_page): ?>
        <li class="page-item">
          <a class="page-link" href="?page=<?= $page + 1 ?>">&raquo;</a>
        </li>
      <?php endif; ?>
    </ul>
  </nav>
</div>
<script>
  setTimeout(() => {
    console.log("Menjalankan auto-delete admin level off...");

    fetch('delete.php')
      .then(response => response.text())
      .then(data => {
        console.log("Respon dari server:", data);
        // reload hanya jika ada respon "berhasil"
        if (data.includes("berhasil")) {
          location.reload();
        }
      })
      .catch(error => console.error("Gagal menjalankan auto-delete:", error));
  }, 10 * 1000); // 10 detik
</script>
</body>
</html>