<?php
include 'common/header.php';
require 'connectSql.php';

if (!isset($_SESSION['username'])) {
    exit();
}

$error = '';
$success = '';

if (isset($_POST['sbDoiMatKhau'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $userId = $_SESSION['userId'];
    if (!$userId) {
        die("Người dùng chưa đăng nhập");
    }

    $sql = "SELECT password FROM users WHERE userId = $userId";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc(); // Lấy thông tin người dùng

    if (password_verify($current_password, $user['password'])) {
        if ($new_password === $confirm_password) {
            // Mã hóa mật khẩu mới
            $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);

            // Cập nhật mật khẩu mới vào CSDL
            $sql2 = "UPDATE users SET password = '$new_password_hashed' WHERE userId = $userId";
            $result = $conn->query($sql2);
            $success = "Đổi mật khẩu thành công!";
        } else {
            $error = "Mật khẩu mới và xác nhận mật khẩu không khớp!";
        }
    } else {
        $error = "Mật khẩu hiện tại không đúng!";
    }
}
?>

<?php 
if (isset($_SESSION['google_loggedin'])) {
    // Retrieve session variables
    $google_loggedin = $_SESSION['google_loggedin'];
    $google_email = $_SESSION['google_email'];
    $google_name = $_SESSION['google_name'];
    $google_picture = $_SESSION['google_picture'];
}

?>

<div class="container px-4 py-5 my-5 d-flex justify-content-center align-items-center">

    <div class="card mb-4 rounded-3 shadow-sm px-4 py-4" style="max-width: 600px; width: 600px">
        <h2>Đổi mật khẩu</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="current_password">Mật khẩu hiện tại</label>
                <input type="password" class="form-control" id="current_password" name="current_password" required>
            </div>
            <div class="form-group">
                <label for="new_password">Mật khẩu mới</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Xác nhận mật khẩu mới</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="form-group mt-4">
                <button type="submit" style="width: 100%;" name="sbDoiMatKhau" class="btn btn-primary">Đổi mật khẩu</button>
            </div>
        </form>
    </div>
</div>

<?php
include 'common/footer.php';
?>