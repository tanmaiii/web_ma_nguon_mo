<?php
// session_start();
// ob_start();
include 'common/header.php';
require 'connectSql.php';

echo '<div class="container px-4 py-2 my-5">';
include 'slider.php';

$ketQuaThanhCong = "";
$ketQuaLoi = "";

$title = $desc = $categoryId = $userId = "";

if (isset($_POST['sbUpload']) && isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
    $title = $_REQUEST['txtTitle'];
    $desc = $_REQUEST['txtDesc'];
    $categoryId = $_REQUEST['categoryId'];
    if (isset($_FILES['uploadFileCauhoi']) && $_FILES['uploadFileCauhoi']['error'] == 0) {

        $targetDir = "uploads/";
        $fileName = time() . '_' . basename($_FILES["uploadFileCauhoi"]["name"]);
        $targetFilePath = $targetDir . $fileName;

        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        $allowedTypes = ['jpg', 'png', 'jpeg', 'gif'];
        if (in_array($fileType, $allowedTypes)) {
            // Upload file to server
            if (move_uploaded_file($_FILES['uploadFileCauhoi']['tmp_name'], $targetFilePath)) {
                $sql = "INSERT INTO posts (`userId`, `title`, `desc`, `categoryId`, `fileUrl`) 
                        VALUES ($userId ,'$title', '$desc', $categoryId, '$targetFilePath')";
                $result = mysqli_query($conn, $sql);
                if ($result === TRUE) {
                    $ketQuaThanhCong = "Upload thành công";
                    header("Location: index.php");
                } else {
                    $ketQuaLoi = "Error: {$sql}<br>{$conn->error}";
                }
            } else {
                $ketQuaLoi = "File không upload được";
            }
        } else {
            $ketQuaLoi = "Chỉ cho phép JPG, JPEG, PNG, GIF";
        }
    } else {
        $sql = "INSERT INTO posts (`userId`, `title`, `desc`, `categoryId`) 
                        VALUES ($userId ,'$title', '$desc', $categoryId)";
        $result = mysqli_query($conn, $sql);
        if ($result === TRUE) {
            $ketQuaThanhCong = "Upload thành công";
            header("Location: index.php");
        } else {
            $ketQuaLoi = "Error: {$sql}<br>{$conn->error}";
        }
    }
}

?>

<div class="mt-4 d-flex">
    <div class="col-md-3">
        <div class="d-flex flex-column flex-shrink-0 p-3 bg-body-tertiary">
            <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
                <span class="fs-4">Chủ đề</span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item ">
                    <a href="?" class="nav-link link-body-emphasis <?php echo !isset($_GET['theloai_id']) ? 'active' : '' ?>" aria-current="page">
                        Tất cả
                    </a>
                </li>
                <?php
                require 'connectSql.php';
                $sql = "SELECT * FROM categories";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                ?>
                        <li class="nav-item">
                            <a href="?theloai_id=<?php echo $row['categoryId'] ?>"
                                class="nav-link link-body-emphasis <?php echo isset($_GET['theloai_id']) && $_GET['theloai_id'] == $row['categoryId'] ? 'active' : '' ?>"
                                aria-current="page">
                                <?php echo $row['title']  ?>
                            </a>
                        </li>
                <?php
                    }
                } else {
                    echo "Không có dữ liệu";
                    return;
                }
                ?>
                <?php
                if (isset($_SESSION['username']) && isset($_SESSION['role']) != 0) {
                ?>
                    <li class="nav-item">
                        <a href="quanlychude.php" class="nav-link link-body-emphasis text-primary" aria-current="page">
                            Thêm
                        </a>
                    </li>
                <?php
                }
                ?>
            </ul>
            <hr>
        </div>
    </div>
    <div class="col-md-9 px-2">
        <?php
        if (isset($_SESSION['username']) && isset($_SESSION['role']) != 0) {
        ?>
            <a type="button" class="btn btn-primary mb-4 " data-bs-toggle="modal" data-bs-target="#modalCauhoi">
                Đặt câu hỏi
            </a>
        <?php
        } else {
            echo '
                <a class="btn btn-primary mb-4" role="alert" href="login.php">
                    Đặt câu hỏi
                </a>
            ';
        }
        ?>

        <h1 class="display-6 fw-bold">Bài viết</h1>
        <?php
        require 'connectSql.php';
        $categoryIdSelect = $_GET['theloai_id'] ?? null;
        $sql = isset($_GET['theloai_id']) && $_GET['theloai_id'] ?
            "SELECT * FROM posts WHERE categoryId = $categoryIdSelect ORDER BY createdAt DESC" :
            "SELECT * FROM posts ORDER BY createdAt DESC";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

        ?>
                <div class="media card mb-2 rounded-3 shadow-sm px-4 py-4">
                    <div class="media-body">
                        <a href="baiviet.php?postId=<?php echo $row['postId'] ?>">
                            <h4 class="card-title"><?php echo $row['title'] ?></h4>
                        </a>
                        <p class="card-text">
                            <?php
                            $categorySql = "SELECT title FROM categories WHERE categoryId = " . $row['categoryId'];
                            $categoryResult = $conn->query($categorySql);
                            if ($categoryResult->num_rows > 0) {
                                $categoryRow = $categoryResult->fetch_assoc();
                                echo '<span class="bg-dark text-white px-2 rounded">' . $categoryRow['title'] . '</span>';
                            }
                            ?>

                            <?php
                            $userSql = "SELECT username FROM users WHERE userId = " . $row['userId'];
                            $userResult = $conn->query($userSql);
                            if ($userResult->num_rows > 0) {
                                $userRow = $userResult->fetch_assoc();
                                echo '<span class="text-muted"> Đăng bởi: <strong>' . $userRow['username'] . '</strong></span>';
                                echo '<span class="text-muted"> Ngày đăng: ' . $row['createdAt'] . '</span>';
                            }
                            ?>
                        </p>
                    </div>
                </div>
        <?php
            }
        }
        ?>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalCauhoi" tabindex="-1" aria-labelledby="modalCauhoiLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalCauhoiLabel">Trả lời</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
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
                    <div class="mb-3">
                        <label for="description" class="form-label">Tiêu đề</label>
                        <input class="form-control" id="txtTitle" name="txtTitle" rows="6" style="resize: none;" placeholder="Nhập tiêu đề" required />
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="txtDesc" name="txtDesc" rows="6" style="resize: none;" placeholder="Nhập mô tả" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Chủ đề</label>

                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Chọn chủ đề
                            </button>
                            <input type="hidden" id="categoryId" name="categoryId" value="<?php echo $categoryId ?>">
                            <ul class="dropdown-menu" require>
                                <?php
                                require 'connectSql.php';
                                $sql = "SELECT * FROM categories";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                ?>
                                        <li>
                                            <a class="dropdown-item" onclick="selectCategory('<?php echo $row['categoryId']; ?>', '<?php echo $row['title']; ?>')">
                                                <?php echo $row['title']; ?>
                                            </a>
                                        </li>

                                <?php
                                    }
                                } else {
                                    echo "Không có dữ liệu";
                                    return;
                                }
                                ?>
                            </ul>
                        </div>
                    </div>


                    <div>
                        <label for="image" class="form-label">Chọn ảnh</label>
                        <input class="form-control" type="file" id="uploadFileCauhoi" name="uploadFileCauhoi" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <div>
                        <input type="submit" class="btn btn-primary" name="sbUpload" value="Submit">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        // Hàm JavaScript để cập nhật input ẩn với ID của chủ đề đã chọn
        function selectCategory(id, title) {
            document.getElementById('categoryId').value = id; // Cập nhật ID của chủ đề
            document.querySelector('.dropdown-toggle').innerText = title; // Cập nhật tiêu đề của dropdown
        }
    </script>
</div>


<?php
echo '</div>';
include 'common/footer.php';
?>