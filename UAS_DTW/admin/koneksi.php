<?php
$conn = mysqli_connect("localhost", "root", "", "db_gobu"); 

if (!$conn) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}
?>