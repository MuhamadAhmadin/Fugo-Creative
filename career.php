<?php
session_start();
$_SESSION["state"] = '';
$_SESSION["message"] = "";
if (isset($_POST['submit']) && isset($_FILES['cv'])) {
    include "phpmailer/classes/class.phpmailer.php";
    $mail = new PHPMailer; 
    $mail->IsSMTP();
    $mail->SMTPSecure = 'ssl'; 
    $mail->Host = "smtp.gmail.com"; //host masing2 provider email
    $mail->SMTPDebug = 0;
    $mail->Port = 465;
    $mail->SMTPAuth = true;
    $mail->Username = "youremail@gmail.com"; //user email
    $mail->Password = "your_app_password"; //password email 
    $mail->SetFrom("youremail@gmail.com","Your Name"); //set email pengirim

    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $mail->Subject = $subject; //subyek email
    $mail->AddAddress($email,$email);  //tujuan email

    // Direktori untuk menyimpan file CV yang diunggah
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["cv"]["name"]);
    $uploadOk = 1;
    $cvFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Cek apakah file adalah dokumen asli
    if(isset($_POST["submit"])) {
        $check = filesize($_FILES["cv"]["tmp_name"]);
        if($check !== false) {
            // echo "File is valid.";
            $uploadOk = 1;
        } else {
            // echo "File is not valid.";
            $uploadOk = 0;
            $_SESSION["state"] = 'error';
            $_SESSION["message"] = "File is not valid";
        }
    }

    // Cek ukuran file (maksimal 2MB)
    if ($_FILES["cv"]["size"] > 2000000) {
        // echo "Sorry, your file is too large.";
        $_SESSION["state"] = 'error';
        $_SESSION["message"] = "Sorry, you file was too large (max 2MB)";
        $uploadOk = 0;
    }

    // Hanya izinkan file dengan format tertentu
    if($cvFileType != "pdf" && $cvFileType != "doc" && $cvFileType != "docx") {
        // echo "Sorry, only PDF, DOC & DOCX files are allowed.";
        $_SESSION["state"] = 'error';
        $_SESSION["message"] = "Only PDF, DOC & DOCX files are allowed";
        $uploadOk = 0;
    }

    // Jika file aman untuk diunggah
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["cv"]["tmp_name"], $target_file)) {

            $file_to_attach = $target_file;
            $filename=$_FILES['cv']['name'];

            // Coba kirim email
            $mail->AddAttachment( $file_to_attach , $filename );
            $string = "<h2>Fugo Creative Career Submission</h2>
                <div>From: ". $email ."</div><br>
                <div>Subject: ". $subject ."</div><br>
                <div>Message: ". $message ."</div><br>
            ";
            $mail->MsgHTML($string);
            // die(var_dump($string));
            if($mail->Send()) {
                $_SESSION["state"] = 'success';
                $_SESSION["message"] = "Message has been sent";
                // echo "Message has been sent";
            } else {
                // echo "Failed to sending message";
                $_SESSION["state"] = 'error';
                $_SESSION["message"] = "Failed to sending message";;
            } 
        } else {
            // echo "Sorry, there was an error uploading your file.";
            $_SESSION["state"] = 'error';
            $_SESSION["message"] = "Sorry, there was an error uploading your file";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Fugo Creative - Careers</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="assets/img/new-logo-putih-2023.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet">
</head>

<body>

    <?php
  include('menu.php')
  ?>

    <!-- Career Form Section -->
    <section id="career" class="career-section">
        <div class="container">
            <div class="col-lg-8 mx-auto">
                <h2 class="mb-4">Career Opportunities</h2>
                <?php if($_SESSION['state'] == 'success'): ?>
                <div class="alert alert-success"><?= $_SESSION['message'] ?></div>
                <?php
                    $_SESSION["state"] = '';
                    $_SESSION["message"] = "";
                    ?>
                <?php endif; ?>
                <?php if($_SESSION['state'] == 'error'): ?>
                <div class="alert alert-danger"><?= $_SESSION['message'] ?></div>
                <?php
                    $_SESSION["state"] = '';
                    $_SESSION["message"] = "";
                    ?>
                <?php endif; ?>
                <form action="" method="post" class="php-email-form" enctype="multipart/form-data">
                    <div class="row gy-4">
                        <div class="col-md-12">
                            <input type="email" class="form-control" name="email" placeholder="Your Email" required="">
                        </div>
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="subject" placeholder="Subject" required="">
                        </div>
                        <div class="col-md-12">
                            <textarea class="form-control" name="message" rows="6" placeholder="Message"
                                required=""></textarea>
                        </div>
                        <div class="col-md-12">
                            <input type="file" class="form-control" name="cv" required="">
                        </div>
                        <div class="col-md-12 text-center">
                            <button type="submit" name="submit" class="btn btn-primary">Publish CV</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <footer id="footer" class="footer dark-background">

        <div class="container footer-top">
            <div class="row gy-4">
                <div class="col-lg-5 col-md-12 footer-about">
                    <a href="index.php" class="logo d-flex align-items-center">
                        <span class="sitename">FUGO CREATIVE</span>
                    </a>
                </div>



                <div class="col-lg-3 col-md-12 footer-contact text-center text-md-start">
                    <h4>Contact Us</h4>
                    <p>Jl. Jupiter Barat S2 No.13</p>
                    <p>Sekejati, Buahbatu, Bandung City</p>
                    <p>West Java 40286</p>
                    <p class="mt-4"><strong>Phone:</strong> <span>082121000680</span></p>
                    <p><strong>Email:</strong> <span>fugocreative2017@gmail.com</span></p>
                </div>

            </div>
        </div>

        <div class="container copyright text-center mt-4">
            <p>© <span>Copyright</span> <strong class="px-1 sitename">FUGO</strong> <span>All Rights Reserved</span></p>
            <div class="credits">
                Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
            </div>
        </div>

    </footer>

    <script src="path/to/your/script.js"></script>
</body>

</html>