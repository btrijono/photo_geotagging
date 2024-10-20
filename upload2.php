<?php
// Folder tujuan untuk menyimpan gambar
$target_dir = "uploads/";

// Cek apakah folder uploads sudah ada, jika belum buat foldernya
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

$target_file = $target_dir . basename($_FILES["image"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Cek apakah file adalah gambar sebenarnya atau bukan
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
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
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Maaf, hanya file JPG, JPEG, PNG & GIF yang diizinkan.";
    $uploadOk = 0;
}

// Cek apakah $uploadOk bernilai 0 karena ada error
if ($uploadOk == 0) {
    echo "Maaf, file Anda tidak dapat diunggah.";
// Jika semua cek lolos, coba unggah file
} else {
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        echo "File ". htmlspecialchars( basename( $_FILES["image"]["name"])). " telah berhasil diunggah.";
    } else {
        echo "Maaf, terjadi kesalahan saat mengunggah file Anda.";
    }
}
?>
