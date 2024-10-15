<?php
include 'common/header.php';
?>

<?php
require 'connectSql.php';

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$ketQuaThanhCong = "";
$ketQuaLoi = "";

if (isset($_POST['sbUpload'])) {
    $title = htmlspecialchars($_REQUEST['txtTitle']);
    $description = htmlspecialchars($_REQUEST['txtDescript']);

    if (isset($_FILES['uploadSlide']) && $_FILES['uploadSlide']['error'] == 0) {

        $targetDir = "uploads/";
        $fileName = time() . '_' . $_FILES["uploadSlide"]["name"];
        $targetFilePath = $targetDir . $fileName;

        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        $allowedTypes = ['jpg', 'png', 'jpeg', 'gif'];
        if (in_array($fileType, $allowedTypes)) {
            // Upload file to server
            if (move_uploaded_file($_FILES['uploadSlide']['tmp_name'], $targetFilePath)) {
                $userId = $_SESSION['userId'];

                if ($userId) {
                    $sql = "INSERT INTO slideshow (`userId`, `title`, `desc`, `imageUrl`) 
                        VALUES ($userId ,'$title', '$description', '$targetFilePath')";
                    $result = mysqli_query($conn, $sql);
                    if ($result === TRUE) {
                        $ketQuaThanhCong = "Upload thành công";
                    } else {
                        $ketQuaLoi = "Error: {$sql}<br>{$conn->error}";
                    }
                } else {
                    $ketQuaLoi = "Người dùng chưa đăng nhập";
                }
            } else {
                $ketQuaLoi = "File không upload được";
            }
        } else {
            $ketQuaLoi = "Chỉ cho phép JPG, JPEG, PNG, GIF";
        }
    } else {
        $ketQuaLoi = "Không có file được chọn";
    }
}
?>

<div class="container py-4 d-flex justify-content-center align-items-center" style="min-height: 80vh">
    <div class="card mb-4 rounded-3 shadow-sm px-4 py-4" style="max-width: 600px; width: 600px">
        <h2 class="mb-4">Upload Ảnh</h2>

        <?php
        if ($ketQuaThanhCong) {
            echo '<div class="alert alert-success" role="alert">';
            echo $ketQuaThanhCong;
            echo '</div>';
        } else if ($ketQuaLoi) {
            echo '<div class="alert alert-danger" role="alert">';
            echo $ketQuaLoi;
            echo '</div>';
        } else {
            echo '';
        }
        ?>

        <form action="" method="POST" enctype="multipart/form-data">

            <!-- Hiển thị ảnh xem trước -->
            <div class="mb-3">
                <div>
                    <img
                        style="display: none; width: 200px; height: 200px; object-fit: contain" id="imgPreview"
                        class="img-preview " src="" alt="Chưa chọn ảnh">
                </div>
            </div>

            <!-- Nhập tiêu đề -->
            <div class="mb-3">
                <label for="title" class="form-label">Tiêu đề</label>
                <input type="text" class="form-control" id="txtTitle" name="txtTitle" placeholder="Nhập tiêu đề" required>
            </div>

            <!-- Nhập mô tả -->
            <div class="mb-3">
                <label for="description" class="form-label">Mô tả</label>
                <textarea class="form-control" id="txtDescript" name="txtDescript" rows="3" placeholder="Nhập mô tả" required></textarea>
            </div>

            <!-- Input upload ảnh -->
            <div class="mb-3">
                <label for="image" class="form-label">Chọn ảnh</label>
                <input class="form-control" type="file" id="uploadSlide" name="uploadSlide" accept="image/*" onchange="previewImage()" required>
            </div>


            <!-- Nút submit -->
            <div>
                <input type="submit" class="btn btn-primary" style="width: 100%;" name="sbUpload" value="Submit">
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage() {
        var file = document.getElementById("uploadSlide").files[0];
        var reader = new FileReader();

        reader.onload = function(e) {
            document.getElementById("imgPreview").src = e.target.result;
            document.getElementById("imgPreview").style.display = "block";
        };

        reader.readAsDataURL(file);
    }
</script>


<?php
include 'common/footer.php';
?>