<?php
require 'connectSql.php';
$username = $password = $avatar = $gender = $email = $fullname = "";
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "db_laragon";
// $port = 3307;

// $conn = new mysqli($servername, $username, $password, $dbname, $port);

// Kiểm tra kết nối
// if ($conn->connect_error) {
//     die("Kết nối thất bại: " . $conn->connect_error);
// } else {
//     echo "Kết nối thành công";
// }

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_POST['sbSubmit'])) {
    $ketquaupload = "";

    $username = test_input($_POST['username']);
    $fullname = test_input($_POST['fullname']);
    $password = test_input($_POST['password']);
    $avatar = test_input($_POST['avatar']);
    $email = test_input($_POST['email']);
    $gender = test_input($_POST['gender']);
    $email = test_input($_POST['email']);

    $sql_check_username = "SELECT * FROM users WHERE username='$username'";
    $sql_check_email = "SELECT * FROM users WHERE email='$email'";

    $result_usename = $conn->query($sql_check_username);
    $result_email = $conn->query($sql_check_email);

    if ($result_usename->num_rows > 0 || $result_email->num_rows > 0) {
        echo "Username or email already exists. Please choose a different username or email.";
    } else {
        // if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        //     $target_dir = "uploads/";
        //     $target_file = $target_dir . basename($_FILES["avatar"]["name"]);
        //     $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        //     // Check if image file is a actual image or fake image
        //     $check = getimagesize($_FILES["avatar"]["tmp_name"]);
        //     if ($check !== false) {
        //         if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
        //             $avatar = $target_file;
        //         } else {
        //             echo "Sorry, there was an error uploading your file.";
        //             exit;
        //         }
        //     } else {
        //         echo "File is not an image.";
        //         exit;
        //     }
        // } else {
        //     echo "No file was uploaded.";
        //     exit;
        // }

        $sql = "INSERT INTO users (username, password, fullname, avatar, gender, email)
    VALUES ('$username', '$password', '$fullname', '$avatar', 0, '$email')";

        // echo $sql;

        if ($conn->query($sql) === TRUE) {
            echo "Created user successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

mysqli_close($conn);
?>

<div>
    <h2>Đăng ký</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="mb-3">
            <label for="formGroupExampleInput" class="form-label">Username</label>
            <input type="text" name="username" class="form-control" id="formGroupExampleInput" placeholder="Example input placeholder">
        </div>
        <div class="mb-3">
            <label for="formGroupExampleInput" class="form-label">Fullname</label>
            <input type="text" name="fullname" class="form-control" id="formGroupExampleInput" placeholder="Example input placeholder">
        </div>
        <div class="mb-3">
            <label for="formGroupExampleInput2" class="form-label">Email</label>
            <input type="text" name="email" class="form-control" id="formGroupExampleInput2" placeholder="Another input placeholder">
        </div>
        <div class="mb-3">
            <label for="formGroupExampleInput2" class="form-label">Password</label>
            <input type="text" name="password" class="form-control" id="formGroupExampleInput2" placeholder="Another input placeholder">
        </div>

        <div class="mb-3">
            <label for="formFile" class="form-label">Avatar</label>
            <input class="form-control" name="avatar" type="file">
        </div>
        <div class="mb-3">
            <label for="formFile" class="form-label">Giới tính</label>
            <div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" name="gender" type="radio" value="1" name="inlineRadioOptions" id="inlineRadio1">
                    <label class="form-check-label" for="inlineRadio1">Nam</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" name="gender" type="radio" value="0" name="inlineRadioOptions" id="inlineRadio2">
                    <label class="form-check-label" for="inlineRadio2">Nữ</label>
                </div>
            </div>
        </div>
        <div>
            <input type="submit" name="sbSubmit" value="Submit">
        </div>
    </form>
</div>