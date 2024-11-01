<?php
session_start();
ob_start();


if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web mã nguồn mở</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="apple-touch-icon" sizes="180x180" href="./assets/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./assets/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./assets/favicon-16x16.png">
    <link rel="manifest" href="./assets/site.webmanifest">
</head>

<body>
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Learn PHP</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse d-flex justify-content-between" id="collapsibleNavbar">
                <div class="d-flex justify-content-between">
                    <ul class="navbar-nav">
                        <?php
                        if (isset($_SESSION['role']) && $_SESSION['role'] == 0) {
                            echo '
                            <li class="nav-item">
                                <a class="nav-link" href="upload.php">Upload</a>
                            </li>
                              <li class="nav-item">
                                <a class="nav-link" href="slideshow.php">Slide</a>
                            </li>
                        ';
                        } else {
                            echo  '';
                        }
                        ?>

                    </ul>
                </div>
                <div class="d-flex justify-content-between">
                    <?php
                    if (isset($_SESSION['username']) && isset($_SESSION['username']) != '') {
                        $usename = $_SESSION['username'];
                        $avatar = $_SESSION['avatar'];
                    ?>
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link d-flex justify-content-between align-items-center gap-2 " href="profile.php">
                                    <img class="rounded-circle object-fit-cover border border-info-subtle" style="width: 40px; height: 40px;" src='<?php echo $avatar ?>' alt="">
                                    <span class="fw-medium">
                                        <?php echo $usename; ?>
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item d-flex justify-content-between align-items-center">
                                <a href="logout.php" class="btn btn-danger">Đăng Xuất</a>
                            </li>
                        </ul>
                    <?php
                    } else {
                    ?>
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="login.php">Đăng nhập</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="signup.php">Đăng ký</a>
                            </li>
                        </ul>';
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </nav>