<?php
// Koneksi ke database
$servername = "localhost";
$username = "root"; // ganti dengan username DB Anda
$password = ""; // ganti dengan password DB Anda
$dbname = "db_agronomi"; // ganti dengan nama database Anda

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil data gambar dari database
$sql = "SELECT file_name, latitude, longitude FROM uploaded_images";
$result = $conn->query($sql);

$images = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $images[] = $row;
    }
}

// Kembalikan data dalam format JSON
echo json_encode($images);

$conn->close();
?>
