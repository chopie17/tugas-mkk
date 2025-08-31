<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../admin/index.php");
    exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Tamu</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        
    body {
        margin: 0;
        padding: 0;
        font-family: "Segoe UI", sans-serif;
        background-color: #ecf0f1;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    .header {
        background-color: #2c3e50;
        color: white;
        padding: 20px 0;
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .content {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        padding: 40px;
        text-align: center;
    }

    .content h2 {
        color: #2c3e50;
        margin-bottom: 10px;
        font-size: 26px;
    }

    .content p {
        font-size: 18px;
        color: #34495e;
        margin-bottom: 20px;
    }

    .btn-isi {
        display: inline-block;
        background-color: #1abc9c;
        color: white;
        padding: 12px 25px;
        border-radius: 8px;
        font-size: 16px;
        text-decoration: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .btn-isi i {
        margin-right: 8px;
    }

    .btn-isi:hover {
        background-color: #16a085;
        transform: translateY(-2px);
    }

    .footer {
        background-color: #2c3e50;
        color: white;
        text-align: center;
        padding: 10px;
        font-size: 14px;
    }
</style>

</head>
<body>

    <div class="header">
        <h1><i class="fas fa-building"></i> Buku Tamu Digital</h1>
    </div>

    <div class="content">
        <h2>Selamat Datang di SMKN 71 Jakarta</h2>
        <p>Silakan isi form buku tamu di bawah ini untuk melakukan kunjungan.</p>
        <a href="form_tamu.php" class="btn-isi"><i class="fas fa-pen"></i> Isi Buku Tamu</a>
    </div>

    <div class="footer">
        &copy; <?= date('Y') ?> SMKN 71 Jakarta – Sistem Buku Tamu Digital
    </div>

</body>
</html>
