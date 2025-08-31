<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../admin/index.php");
    exit();
}

include '../koneksi.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $hapus = mysqli_query($conn, "DELETE FROM tamu WHERE id = $id");

    if ($hapus) {
        header("Location: tamu.php?pesan=hapus_sukses");
    } else {
        echo "Gagal menghapus data!";
    }
} else {
    echo "ID tidak ditemukan!";
}
?>
