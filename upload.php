<?php
$target_dir = "uploads/";

if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

$target_file = $target_dir . basename($_FILES["image"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Koneksi ke database (ganti dengan detail koneksi Anda)
$servername = "localhost";
$username = "root"; // ganti dengan username DB Anda
$password = ""; // ganti dengan password DB Anda
$dbname = "db_agronomi"; // ganti dengan nama database Anda

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Cek apakah file adalah gambar
if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}

// Cek jika file sudah ada
if (file_exists($target_file)) {
    echo "Maaf, file sudah ada.";
    $uploadOk = 0;
}

// Batasi jenis file yang diizinkan
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
    echo "Maaf, hanya file JPG, JPEG, PNG & GIF yang diizinkan.";
    $uploadOk = 0;
}

// Jika semua cek lolos, coba unggah file
if ($uploadOk == 1) {
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Dapatkan latitude dan longitude dari POST
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        $company = 'MAU'; // Isi default untuk field company
        
        // Simpan informasi ke database
        $stmt = $conn->prepare("INSERT INTO uploaded_images (file_name, latitude, longitude, company) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            die("Prepared Statement Error: " . $conn->error);
        }
        $stmt->bind_param("sdds", $_FILES["image"]["name"], $latitude, $longitude, $company);
        
        if ($stmt->execute()) {
            echo "File " . htmlspecialchars(basename($_FILES["image"]["name"])) . " berhasil diunggah dan disimpan.";
        } else {
            echo "Error: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        echo "Maaf, terjadi kesalahan saat mengunggah file Anda.";
    }
} else {
    echo "Maaf, file Anda tidak dapat diunggah.";
}

$conn->close();
?>
