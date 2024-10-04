<?php
require 'connectSql.php';
$username = $password = $gender = $email = $fullname = "";
$ketQuaLoi = "";

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_POST['sbSubmit'])) {
    $username = test_input($_POST['username']);
    $fullname = test_input($_POST['fullname']);
    $password = test_input($_POST['password']);
    $email = test_input($_POST['email']);
    $gender = test_input($_POST['gender']);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $ketQuaLoi = "Invalid email format";
        return;
    }

    // Check if username or email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? OR email=?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "Username or email already exists. Please choose a different username or email.";
        return;
    }
    $stmt->close();

    $targetDir = "uploads/";
    $fileName = time() . '_' . $_FILES["fileToUpload"]["name"];
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    if (empty($_FILES["fileToUpload"]["name"])) {
        echo 'Please select a file to upload';
        return;
    }

    // Allow certain file formats
    $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
    if (!in_array($fileType, $allowTypes)) {
        echo 'Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.';
        return;
    }

    // Upload file to server
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFilePath)) {
        // Hash the password before storing it
        // $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, password, fullname, avatar, gender, email) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $username, $password, $fullname, $targetFilePath, $gender, $email);

        if ($stmt->execute()) {
            echo "Created user successfully";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo 'Sorry, there was an error uploading your file.';
    }
}

mysqli_close($conn);
?>

<div>
    <h2>Đăng ký</h2>
    <form method="POST" action="" enctype="multipart/form-data">
        <?php
        if ($ketQuaLoi) {
            echo '<div class="alert alert-danger" role="alert">';
            echo $ketQuaLoi;
            echo '</div>';
        } else {
            echo '';
        }
        ?>
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
            <input type="password" name="password" class="form-control" id="formGroupExampleInput2" placeholder="Another input placeholder">
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
            <input type="submit" name="sbSubmit" value="Submit">
        </div>
    </form>
</div>
</div>