<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari form
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    
    // Direktori untuk menyimpan file CV yang diunggah
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["cv"]["name"]);
    $uploadOk = 1;
    $cvFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Cek apakah file adalah dokumen asli
    if(isset($_POST["submit"])) {
        $check = filesize($_FILES["cv"]["tmp_name"]);
        if($check !== false) {
            echo "File is valid.";
            $uploadOk = 1;
        } else {
            echo "File is not valid.";
            $uploadOk = 0;
        }
    }

    // Cek ukuran file (maksimal 2MB)
    if ($_FILES["cv"]["size"] > 2000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Hanya izinkan file dengan format tertentu
    if($cvFileType != "pdf" && $cvFileType != "doc" && $cvFileType != "docx") {
        echo "Sorry, only PDF, DOC & DOCX files are allowed.";
        $uploadOk = 0;
    }

    // Jika file aman untuk diunggah
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["cv"]["tmp_name"], $target_file)) {
            echo "The file ". basename( $_FILES["cv"]["name"]). " has been uploaded.";

            // Kirim email ke Tsania
            $to = "tsaniafariha31@gmail.com";  // Email tujuan
            $headers = "From: $email";  // Email pengirim
            $fullMessage = "Subject: $subject\nMessage: $message\nCV Uploaded: " . $target_file;

            // Coba kirim email
            if (mail($to, $subject, $fullMessage, $headers)) {
                echo "Your message has been sent!";
            } else {
                echo "Sorry, something went wrong. Please try again.";
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>
