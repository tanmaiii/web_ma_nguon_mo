<?php
session_start();
ob_start();

// if (isset($_SESSION['google_loggedin'])) {
//     // Retrieve session variables
//     $google_loggedin = $_SESSION['google_loggedin'];
//     $google_email = $_SESSION['google_email'];
//     $google_name = $_SESSION['google_name'];
//     $google_picture = $_SESSION['google_picture'];

//     include 'connectSql.php'; // Include your database connection file

//     // Check if google_email exists in the users table
//     $query = "SELECT * FROM users WHERE email = ?";
//     $stmt = $conn->prepare($query);
//     $stmt->bind_param("s", $google_email);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $row = $result->fetch_assoc();

//     if ($result->num_rows == 0) {
//         $insert_query = $conn->prepare("INSERT INTO users (username, password, fullname, avatar, gender, email) VALUES (?, ?, ?, ?, 1, ?)");
//         $insert_query->bind_param("sssss", $google_email, $google_email, $google_email, $google_picture, $google_email);

//         if ($insert_query->execute()) {
//             $_SESSION['username'] = $google_email;
//             $_SESSION['email'] = $google_email;
//             $_SESSION['avatar'] = $google_picture;
//             $_SESSION['userId'] = $conn->insert_id;
//             $_SESSION['role'] = 1;
//             header("Location: index.php");
//             exit;
//         } else {
//             echo "Error: {$insert_query}<br>{$conn->error}";
//         }
//     } else {
//         $_SESSION['username'] = $row['username'];
//         $_SESSION['email'] = $row['email'];
//         $_SESSION['avatar'] = $row['avatar'];
//         $_SESSION['userId'] = $row['userId'];
//         $_SESSION['role'] = $row['role'];
//         header("Location: index.php");
//         exit;
//     }

//     $stmt->close();
//     $conn->close();
// }

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
                        if (isset($_SESSION['username']) && isset($_SESSION['username']) != '') {
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