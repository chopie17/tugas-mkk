<?php
include '../koneksi.php';

$email_verified = false;
$email = "";
$success = "";
$error = "";

// Step 1: Cek email
if (isset($_POST['check_email'])) {
    $email = $_POST['email'];
    
    // PERINGATAN: Kode ini tetap menggunakan Prepared Statement untuk mencegah SQL Injection,
    // namun password akan disimpan dalam teks biasa.
    $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $email_verified = true;
    } else {
        $error = "Email tidak ditemukan!";
    }
}

// Step 2: Simpan password baru
if (isset($_POST['change_password'])) {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password != $confirm_password) {
        $error = "Password dan konfirmasi tidak cocok!";
        $email_verified = true;
    } elseif (strlen($new_password) < 3) {
        $error = "Password minimal 3 karakter!";
        $email_verified = true;
    } else {
        // **PERINGATAN: Password akan disimpan dalam teks biasa (plain text).**
        // Ini sangat tidak disarankan karena alasan keamanan.
        $stmt = $conn->prepare("UPDATE admin SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $new_password, $email);

        if ($stmt->execute()) {
            $success = "Password berhasil diubah. Silakan login kembali.";
        } else {
            $error = "Gagal mengubah password.";
        }
        $email_verified = true; 
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Lupa Password</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      height: 100vh;
      background: linear-gradient(to right, #eef0f1ff);
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .forgot-container {
      width: 420px;
      background: #ffffff;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .forgot-container h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #0072ff;
    }
  </style>
</head>
<body>
  <div class="forgot-container">
    <h2>Lupa Password</h2>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger text-center"><?= $error ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
      <div class="alert alert-success text-center"><?= $success ?></div>
      <div class="text-center">
        <a href="index.php" class="btn btn-success w-100 mt-2">Kembali ke Login</a>
      </div>
    <?php elseif (!$email_verified): ?>
      <form method="POST" class="mb-3">
        <div class="mb-3">
          <label class="form-label">Masukkan Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <button type="submit" name="check_email" class="btn btn-primary w-100">Lanjut</button>
        <a href="index.php" class="btn btn-link w-100 text-center mt-2">Kembali ke Login</a>
      </form>
    <?php else: ?>
      <form method="POST">
        <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
        
        <div class="mb-3">
          <label class="form-label">Password Baru</label>
          <input type="password" name="new_password" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Konfirmasi Password</label>
          <input type="password" name="confirm_password" class="form-control" required>
        </div>

        <button type="submit" name="change_password" class="btn btn-danger w-100">Simpan Password</button>
      </form>
    <?php endif; ?>
  </div>
</body>
</html>