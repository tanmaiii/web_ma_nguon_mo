<?php
include 'common/header.php';
require 'connectSql.php';


// Kiểm tra nếu có ID slide và lấy thông tin slide từ CSDL
if (isset($_GET['id'])) {
    $slide_id = $_GET['id'];

    // Lấy thông tin của slide
    $sql = "SELECT * FROM slideshow WHERE id = " . $slide_id;
    $result = $conn->query($sql);

    if ($result->num_rows <= 0) {
        die("Slide không tồn tại!");
    } else {
        $slide = $result->fetch_assoc();
        if (!$slide) {
            die("Slide không tồn tại!");
        } else {
            $desc = $slide['desc'];
            $title = $slide['title'];
            $status = $slide['status'];
            $imageUrl = $slide['imageUrl'];
        }
    }
} else {
    header("Location: slideshow.php");
}


if (isset($_POST['editSubmit'])) {
    $title = $_POST['txtTitle'];
    $desc = $_POST['txtDescript'];
    $status = $_POST['status']; // 1: ẩn, 0: hiện
    $userId = $_SESSION['userId'];
    if (!$userId) {
        die("Người dùng chưa đăng nhập");
    }

    // echo $title . "<br>" .  $desc . "<br>" . $status . "<br>" . $userId . "<br>" . $slide_id . "<br>";

    // return;

    if (isset($_FILES['uploadSlide']) && $_FILES['uploadSlide']['error'] == 0) {
        $targetDir = "uploads/";
        $fileName = time() . '_' . $_FILES["uploadSlide"]["name"];
        $targetFilePath = $targetDir . $fileName;

        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        $allowedTypes = ['jpg', 'png', 'jpeg', 'gif'];
        if (in_array($fileType, $allowedTypes)) {
            // Upload file to server
            if (move_uploaded_file($_FILES['uploadSlide']['tmp_name'], $targetFilePath)) {
                $sql = "UPDATE slideshow SET `userId` = $userId, `title` = '$title', `desc` = '$desc', `status` = '$status', `imageUrl` = '$targetFilePath'
                WHERE id = $slide_id";
                $result = $conn->query($sql);

                if ($result === TRUE) {
                    header("Location: slideshow.php");
                } else {
                    echo "Error: {$sql}<br>{$conn->error}";
                }
            } else {
                echo "File không upload được";
            }
        } else {
            echo "Chỉ cho phép JPG, JPEG, PNG, GIF";
        }
    } else {
        $sql = "UPDATE slideshow SET `userId` = $userId, `title` = '$title', `desc` = '$desc', `status` = '$status'
        WHERE id = $slide_id";
        $result = $conn->query($sql);

        if ($result === TRUE) {
            header("Location: slideshow.php");
        } else {
            echo "Error: {$sql}<br>{$conn->error}";
        }
    }
}

?>

<div class="container px-4 py-5 my-5 d-flex justify-content-center align-items-center">
    <div class="card mb-4 rounded-3 shadow-sm px-4 py-4" style="max-width: 600px; width: 600px">
        <form action="" method="POST" enctype="multipart/form-data">
            <h2>Cập nhật</h2>

            <!-- Hiển thị ảnh xem trước -->
            <div class="mb-3">
                <div>
                    <img
                        style="width: 200px; height: 200px; object-fit: contain, <?php echo !$slide['imageUrl'] ? 'display: none;' : '' ?>" id="imgPreview"
                        class="img-preview " src="<?php echo $slide['imageUrl'] ?>" alt="Chưa chọn ảnh">
                </div>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">Tiêu đề</label>
                <input type="text" class="form-control" id="txtTitle" name="txtTitle" placeholder="Nhập tiêu đề" value="<?php echo $slide['title']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">Mô tả</label>
                <textarea class="form-control" id="txtDescript" name="txtDescript" rows="3" placeholder="Nhập mô tả" required>
                        <?php echo $slide['desc']; ?>
                </textarea>
            </div>

            <div class="mb-3">
                <label for="formFile" class="form-label">Trạng thái</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="status" <?php echo $slide['status'] == 1 ? 'checked' : '' ?> type="radio" value="1" id="inlineRadio1">
                        <label class="form-check-label" for="inlineRadio1">Ẩn</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="status" <?php echo $slide['status'] == 0 ? 'checked' : '' ?> type="radio" value="0" id="inlineRadio2">
                        <label class="form-check-label" for="inlineRadio2">Hiện</label>
                    </div>
                </div>
            </div>

            <!-- Input upload ảnh -->
            <div class="mb-3">
                <label for="image" class="form-label">Chọn ảnh mới</label>
                <input class="form-control" type="file" id="uploadSlide" name="uploadSlide" accept="image/*" onchange="previewImage()">
            </div>

            <div>
                <input type="submit" class="btn btn-primary" style="width: 100%;" name="editSubmit" value="Submit">
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