<?php
include 'common/header.php';
?>

<?php
require 'connectSql.php';
$username = $password = $gender = $email = $fullname = "";
$ketQuaLoi = "";
$ketQuaThanhCong = "";

if (isset($_SESSION['username']) && isset($_SESSION['username']) != '') {
    header("Location: index.php?page=upload");
    exit();
}

function test_input($data)
{
    $data = trim($data); // Xoas khoang trang dau va cuoi
    $data = stripslashes($data); // Xoa dau /
    $data = htmlspecialchars($data); // Chuyen cac ki tu dac biet sang ma html
    return $data;
}

if (isset($_POST['sbSubmit'])) {
    $username = test_input($_POST['username']);
    $fullname = test_input($_POST['fullname']);
    $password = test_input($_POST['password']);
    $email = test_input($_POST['email']);
    $gender = test_input($_POST['gender']);

    // Check if username or email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? OR email=?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    // Validate email format
    if (filter_var($email, FILTER_VALIDATE_EMAIL) && $result->num_rows === 0) {
        $targetDir = "uploads/";
        $fileName = time() . '_' . $_FILES["fileToUpload"]["name"];
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        if (!empty($_FILES["fileToUpload"]["name"])) {
            // Allow certain file formats
            $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
            if (!in_array($fileType, $allowTypes)) {
                $ketQuaLoi = 'Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.';
            } else {
                // Upload file to server
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFilePath)) {
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                    $stmt = $conn->prepare("INSERT INTO users (username, password, fullname, avatar, gender, email) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssss", $username, $passwordHash, $fullname, $targetFilePath, $gender, $email);

                    if ($stmt->execute()) {
                        $ketQuaThanhCong = "Tạo tài khoản thành công";
                    } else {
                        $ketQuaLoi = 'Xin lỗi, vui lòng tải hình ảnh lên.';
                    }
                    $stmt->close();
                } else {
                    $ketQuaLoi = 'Xin lỗi, vui lòng tải hình ảnh lên.';
                }
            }
        } else {
            $ketQuaLoi  = 'Làm ơn bạn chọn hình ảnh.';
        }
    } else {
        $ketQuaLoi = "Username hoặc email đã tồn tại";
    }
}

mysqli_close($conn);
?>


<div class="container py-4 d-flex justify-content-center align-items-center" style="min-height: 80vh">
    <div class="card mb-4 rounded-3 shadow-sm px-4 py-4" style="max-width: 600px; width: 600px">
        <h2>Đăng nhập</h2>
        <form method="POST" action="" enctype="multipart/form-data">

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

            <div class="mb-3">
                <label for="formGroupExampleInput" class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Nhập username">
            </div>
            <div class="mb-3">
                <label for="formGroupExampleInput" class="form-label">Fullname</label>
                <input type="text" name="fullname" class="form-control" placeholder="Nhập fullname">
            </div>
            <div class="mb-3">
                <label for="formGroupExampleInput2" class="form-label">Email</label>
                <input type="text" name="email" class="form-control" placeholder="Nhập email">
            </div>
            <div class="mb-3">
                <label for="formGroupExampleInput2" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Nhập password">
            </div>

            <div class="mb-3">
                <label for="formFile" class="form-label">Avatar</label>
                <input class="form-control" id="fileToUpload" name="fileToUpload" type="file">
            </div>
            <div class="mb-3">
                <label for="formFile" class="form-label">Giới tính</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="gender" type="radio" value="1" id="inlineRadio1">
                        <label class="form-check-label" for="inlineRadio1">Nam</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="gender" type="radio" value="0" id="inlineRadio2">
                        <label class="form-check-label" for="inlineRadio2">Nữ</label>
                    </div>
                </div>
            </div>

            <div>
                <input type="submit" class="btn btn-primary" style="width: 100%;" name="sbSubmit" value="Submit">
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