<?php
include '../koneksi.php'; // Menghubungkan ke database

$message = ""; // Inisialisasi variabel untuk menyimpan pesan

// Memeriksa apakah metode permintaan adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email']; // Mengambil email dari input form
    $username = $_POST['username']; // Mengambil username dari input form
    $password = $_POST['password']; // Mengambil password dari input form
    $level = 'off'; // Menetapkan level default untuk admin baru

    // Memeriksa apakah email sudah ada di database
    $cek = mysqli_query($conn, "SELECT * FROM admin WHERE email = '$email'");
    if (mysqli_num_rows($cek) > 0)  { // Jika email sudah terdaftar
        $message = "<div class='alert alert-danger'>Email sudah terdaftar!</div>"; // Menyimpan pesan error
    } else {
        // Menyisipkan admin baru ke dalam database
        $query = "INSERT INTO admin (email, username, password, level) VALUES ('$email', '$username', '$password', '$level')"; // PENTING: Hash password di aplikasi nyata!
        if (mysqli_query($conn, $query)) { // Jika query berhasil dieksekusi
            // Jika berhasil, arahkan ke t_admin.php
            header("location: t_admin.php");
            exit(); // Menghentikan eksekusi skrip setelah pengalihan
        } else {
            $message = "<div class='alert alert-danger'>Gagal menambahkan admin!</div>"; // Menyimpan pesan error jika gagal
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Tambah Admin</title>
    <style>
        body {
            background: linear-gradient(to right, #007eea, #764ba2); /* Example gradient, adjust as needed */
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            padding: 3rem;
            border-radius: 1rem;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        h3 {
            color: #444; /* Darker color for heading */
        }
    </style>
</head>
<body>
    <div class="card col-md-6 bg-light">
        <h3 class="card-title text-center mb-4">Tambah Admin Baru</h3>
        <?php echo $message; // Display message ?>
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" maxlength="8" class="form-control" required>
                <div class="form-text">Maksimal 8 karakter sesuai struktur tabel.</div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Tambah Admin</button>
            <a href="t_admin.php" class="btn btn-secondary w-100 mt-2">Batal</a>
        </form>
    </div>
</body>
</html>
