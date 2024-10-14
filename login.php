<?php
include 'common/header.php';
?>

<?php
require 'connectSql.php';
$username = $password = "";
$ketQuaLoi = "";
$ketQuaThanhCong = "";

if (isset($_SESSION['username']) && isset($_SESSION['username']) != '') {
    header("Location: index.php");
    exit();
}

function test_input($data)
{
    $data = trim($data); // Xoas khoang trang dau va cuoi
    $data = stripslashes($data); // Xoa dau /
    $data = htmlspecialchars($data); // Chuyen cac ki tu dac biet sang ma html
    return $data;
}

if (isset(($_POST['submitLogin']))) {
    $username = test_input($_POST['username']);
    $password = test_input($_POST['password']);

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['avatar'] = $row['avatar'];
            $_SESSION['userId'] = $row['userId'];
            $_SESSION['role'] = $row['role'];

            $ketQuaThanhCong = "Đăng nhập thành công";
            header("Location: index.php");
            exit();
        } else {
            $ketQuaLoi = "Sai mật khẩu";
        }
    } else {
        $ketQuaLoi = "Không tìm thấy người dùng";
    }
}

mysqli_close($conn);
?>


<div class="container py-4 d-flex justify-content-center align-items-center" style="min-height: 80vh">
    <div class="card mb-4 rounded-3 shadow-sm px-4 py-4" style="max-width: 600px; width: 600px">
        <h2>Đăng nhập</h2>
        <?php
        if ($ketQuaLoi) {
            echo '<div class="alert alert-danger" role="alert">';
            echo $ketQuaLoi;
            echo '</div>';
        } else if ($ketQuaThanhCong) {
            echo '<div class="alert alert-success" role="alert">';
            echo $ketQuaThanhCong;
            echo '</div>';
        } else {
            echo '';
        }
        ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="formGroupExampleInput" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Nhập username">
            </div>
            <div class="mb-3">
                <label for="formGroupExampleInput2" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu">
            </div>
            <div class="mb-3">
                <input type="submit" class="btn btn-primary" style="width: 100%;" name="submitLogin" value="Submit">
            </div>
            <hr>
            <div class="mb-3">
                <a class="btn btn-primary" style="width: 100%;" href="google-oauth.php">
                    <img src="./uploads/Google.png" alt="" style="width: 30px; height: 30px">
                    <span>Login with google</span>
                </a>
            </div>
        </form>
    </div>
</div>

<?php
include 'common/footer.php';
?>