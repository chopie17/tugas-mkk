<?php
session_start();
include '../koneksi.php';

// Ambil ID admin yang sedang login (jika ada)
$currentAdminId = isset($_SESSION['admin_id']) ? (int) $_SESSION['admin_id'] : 0;

// Pagination setup
$limit = 5; // Maks 5 data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// --- AKTIVASI / NONAKTIVASI ADMIN
// Cegah aksi pada akun sendiri
if(isset($_GET['aktifkan_id'])) {
  $id = (int) $_GET['aktifkan_id'];
  if ($id !== $currentAdminId) {
    mysqli_query($conn, "UPDATE admin SET level = 'on' WHERE id = $id");
  }
  header("Location: t_admin.php?page=" . $page);
  exit();
}

if (isset($_GET['nonaktifkan_id'])) {
  $id = (int) $_GET['nonaktifkan_id'];
  if ($id !== $currentAdminId) {
    mysqli_query($conn, "UPDATE admin SET level = 'off' WHERE id = $id");
  }
  header("Location: t_admin.php?page=" . $page);
  exit();
}

// --- Query data + pagination ---
// Sembunyikan akun yang sedang login dengan WHERE id != $currentAdminId
$whereNotSelf = $currentAdminId > 0 ? "WHERE id != $currentAdminId" :"";

$result = mysqli_query(
  $conn, 
  "SELECT * FROM admin $whereNotSelf ORDER BY id ASC LIMIT $start, $limit"
);

$total_result = mysqli_query(
  $conn, 
  "SELECT COUNT(*) AS total FROM admin $whereNotSelf"
);
$total_row = mysqli_fetch_assoc($total_result);
$total_data = (int) $total_row['total'];
$total_page = max(1, (int) ceil($total_data / $limit));
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
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    table thead {
      background: #007bff;
      color: white;
    }
  </style>
</head>
<body>

<div class="container table-container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Daftar Admin</h4>
    <div>
      <a href="dashboard.php" class="btn btn-secondary me-2">Kembali</a>
    <a href="create_admin.php" class="btn btn-success">+ Tambah Admin</a>
    </div>
  </div>

  <table class="table table-striped table-hover text-center">
    <thead>
      <tr>
        <th>No</th>
        <th>Email</th>
        <th>Username</th>
        <th>Status / Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $no = $start + 1;

      if($result && mysqli_num_rows($result) > 0):
        while ($row = mysqli_fetch_assoc($result)):
          if ($row['level'] === 'on') {
            $statusAksi = "
              <span class='badge bg-success me-2'>Aktif</span>
              <a href='?page={$page}&nonaktifkan_id={$row['id']}' class='btn btn-sm btn-outline-danger'>Off</a>
            ";
          } else {
            $statusAksi = "
              <span class='badge bg-secondary me-2'>Nonaktif</span>
              <a href='?page={$page}&aktifkan_id={$row['id']}' class='btn btn-sm btn-warning'>Aktifkan</a>
            ";
          }
          echo "<tr>
                  <td>{$no}</td>
                  <td>{$row['email']}</td>
                  <td>{$row['username']}</td>
                  <td>{$statusAksi}</td>
                </tr>";
          $no++;
        endwhile;
      else:
        echo "<tr><td colspan='4'>Tidak ada data admin.</td></tr>";
      endif;  
      ?>
    </tbody>
  </table>

  <!-- Pagination -->
  <nav>
    <ul class="pagination justify-content-center">
      <?php if ($page > 1): ?>
        <li class="page-item">
          <a class="page-link" href="?page=<?= $page - 1 ?>">#</a>
        </li>
      <?php endif; ?>

      <?php for ($i = 1; $i <= $total_page; $i++): ?>
        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
          <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
        </li>
      <?php endfor; ?>

      <?php if ($page < $total_page): ?>
        <li class="page-item">
          <a class="page-link" href="?page=<?= $page + 1 ?>">#</a>
        </li>
      <?php endif; ?>
    </ul>
  </nav>
</div>

      <!-- Hapus otomatis admin level 'off' setelah 10 detik -->
<script>
  setTimeout(() => {
    fetch('delete.php')
      .then(res => res.text())
      .then(() => location.reload());
  }, 10000);    
</script>

</body>
</html>