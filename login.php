<?php
require 'connectSql.php';
$username = $password = "";

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

    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['username'] = $row['username'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['role'] = $row['role'];

        header("Location: index.php?page=upload");
        exit();
    } else {
        echo '<div class="alert alert-danger" role="alert">';
        echo "Tài khoản hoặc mật khẩu không đúng";
        echo '</div>';
    }
}
mysqli_close($conn);
?>

<div style="max-width: 600px; width: 600px">
    <h2>Đăng nhập</h2>
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="formGroupExampleInput" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Nhập username">
        </div>
        <div class="mb-3">
            <label for="formGroupExampleInput2" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu">
        </div>
        <div>
            <input type="submit" class="btn btn-primary" style="width: 100%;" name="submitLogin" value="Submit">
        </div>
    </form>
</div>