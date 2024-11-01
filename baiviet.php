<?php

use function PHPSTORM_META\map;

include 'common/header.php';
require 'connectSql.php';
$postId = null;
$title = $desc = $fileUrl = $createdAt = $username = $avatar = "";

if (!isset($_GET['postId'])) {
    header("Location: index.php");
} else {
    $postId = $_GET['postId'];
    $title = $desc = $fileUrl = $createdAt = "";

    $sql = "SELECT * FROM posts where postId = $postId";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $title = $row['title'];
        $desc = $row['desc'];
        $fileUrl = $row['fileUrl'];
        $createdAt = $row['createdAt'];
        $userSql = "SELECT username, avatar FROM users WHERE userId = " . $row['userId'];
        $userResult = $conn->query($userSql);
        if ($userResult->num_rows > 0) {
            $userRow = $userResult->fetch_assoc();
        }
    }
}

$ketQuaThanhCong = "";
$ketQuaLoi = "";

// Xử lý khi người dùng nhấn nút trả lời
if (isset($_POST['sbUpload'])) {
    $content = $_REQUEST['txtContent'];
    $postId = $_GET['postId'];
    $userId = $_SESSION['userId'];

    if (isset($_FILES['uploadFileTraLoi']) && $_FILES['uploadFileTraLoi']['error'] == 0) {
        $targetDir = "uploads/";
        $fileName = time() . '_' . basename($_FILES["uploadFileTraLoi"]["name"]);
        $targetFilePath = $targetDir . $fileName;

        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        $allowedTypes = ['jpg', 'png', 'jpeg', 'gif'];
        if (in_array($fileType, $allowedTypes)) {
            // Upload file to server
            if (move_uploaded_file($_FILES['uploadFileTraLoi']['tmp_name'], $targetFilePath)) {
                $sql = "INSERT INTO comments (userId, content, postId, fileUrl) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("isis", $userId, $content, $postId, $targetFilePath);
                if ($stmt->execute()) {
                    $ketQuaThanhCong = "Upload thành công";
                    header("Location: baiviet.php?postId=$postId");
                } else {
                    $ketQuaLoi = "Error: {$stmt->error}";
                }
                $stmt->close();
            } else {
                $ketQuaLoi = "File không upload được";
            }
        } else {
            $ketQuaLoi = "Chỉ cho phép JPG, JPEG, PNG, GIF";
        }
    } else {
        $sql = "INSERT INTO comments (userId, content, postId) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isi", $userId, $content, $postId);
        if ($stmt->execute()) {
            $ketQuaThanhCong = "Upload thành công";
            header("Location: baiviet.php?postId=$postId");
        } else {
            $ketQuaLoi = "Error: {$stmt->error}";
        }
        $stmt->close();
    }
}


// Xử lý khi người dùng nhấn nút phản hồi
if (isset($_POST['sbPhanHoi'])) {
    $content = $_REQUEST['txtContent'];
    $userId = $_SESSION['userId'];
    $commentIdReply = $_REQUEST['commentIdReply'];

    if ($commentIdReply) {
        $sql = "INSERT INTO comments (userId, content, postId, parentId) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isii", $userId, $content, $postId, $commentIdReply);
        if ($stmt->execute()) {
            $ketQuaThanhCong = "Upload thành công";
            header("Location: baiviet.php?postId=$postId");
        } else {
            $ketQuaLoi = "Error: {$stmt->error}";
        }
        $stmt->close();
    }
}

//Xử lý ẩn comment
if (isset($_POST['toggle_visibility'])) {
    $commentId = $_REQUEST['hideCommentId'];
    $sql = "UPDATE comments SET status = 0 WHERE commentId = $commentId";
    $conn->query($sql);
    header("Location: baiviet.php?postId=$postId");
}

?>

<!-- // Bài viết -->
<div class="container my-3" style="min-height: 80vh">
    <div class="p-3 mb-4 bg-body-tertiary rounded-3">
        <div class="container-fluid">
            <div>
                <h1 class="display-5 fw-bold"><?php echo $title ?></h1>
                <div class="d-flex flex-row gap-2">
                    <div>
                        <img width="30px" height="30px" class="rounded-circle" src="<?php echo $userRow['avatar'] ?>" alt="">
                        <span><strong><?php echo $userRow['username'] ?></strong></span>
                    </div>
                    <span><i><?php echo $createdAt ?></i></span>
                </div>
            </div>
            <hr />
            <p class="col-md-8 fs-5"><?php echo $desc ?></p>
            <?php
            if ($fileUrl) {
            ?>
                <div style="width: 600px; height: 600px">
                    <img src="<?php echo $fileUrl ?>" class="img-fluid" alt="..." />
                </div>
            <?php
            }
            ?>
            <!-- Button trigger modal -->
            <?php if (isset($_SESSION['userId'])): ?>
                <button type="button" class="btn btn-primary mt-4" data-bs-toggle="modal" data-bs-target="#modalTraLoi">
                    Trả lời
                </button>
            <?php else: ?>
                <div class="alert alert-warning mt-4" role="alert">
                    Bạn cần đăng nhập để trả lời.
                </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- // Bình luận -->
    <div>
        <div class="">
            <h2 class="display-6">Bình luận</h2>
        </div>

        <?php
        $commentSql = "SELECT c.*, u.username, u.avatar 
                       FROM comments c 
                       JOIN users u ON c.userId = u.userId 
                       WHERE c.postId = $postId and c.status = 1 and c.parentId IS NULL
                       ORDER BY c.createdAt DESC";
        $commentResult = $conn->query($commentSql);

        if ($commentResult->num_rows > 0) {
            while ($commentRow = $commentResult->fetch_assoc()) {
        ?>
                <div class="card media border p-3 d-flex flex-column mb-2" style="width: 100%">
                    <div class="d-flex flex-row gap-2 align-items-center">
                        <img width="50px" height="50px" src="<?php echo $commentRow['avatar'] ?>" alt="John Doe" class="rounded-circle">
                        <h4><?php echo $commentRow['username'] ?></h4>
                        <small><i><?php echo $commentRow['createdAt'] ?></i></small>
                    </div>
                    <div class="media-body">
                        <p><?php echo $commentRow['content'] ?></p>
                        <?php
                        if ($commentRow['fileUrl']) {
                        ?>
                            <div>
                                <img style="width: 600px; height: 600px" src="<?php echo $commentRow['fileUrl'] ?>" class="img-fluid" alt="..." />
                            </div>
                        <?php
                        }
                        if ($_SESSION['role'] == 0) {
                        ?>
                            <form style="display: inline;" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn ẩn bình luận này không?');"></form>
                            <input type="hidden" name="hideCommentId" value="<?php echo $commentRow['commentId'] ?>">
                            <button type="submit" name="toggle_visibility" class="btn btn-danger mt-4">
                                Ẩn bình luận
                            </button>
                            </form>
                        <?php
                        }
                        ?>
                        <?php if (isset($_SESSION['userId'])): ?>
                            <button type="submit" class="btn btn-primary mt-4" data-bs-toggle="modal" data-bs-target="#modalPhanHoiBinhLuan"
                                onclick="document.getElementById('commentIdReply').value = <?php echo $commentRow['commentId'] ?>">
                                Phản hồi bình luận
                            </button>
                        <?php else: ?>
                            <div class=" alert alert-warning mt-4" role="alert">
                                Bạn cần đăng nhập để trả lời.
                            </div>
                    </div>
                <?php endif; ?>
                </div>
    </div>

    <!-- Bình luận con -->
    <?php
                $replySql = "SELECT c.* , u.username, u.avatar 
                             FROM comments c 
                             JOIN users u ON c.userId = u.userId 
                             WHERE c.parentId = " . $commentRow['commentId'] . " and c.status = 1 
                             ORDER BY c.createdAt DESC";
                $replyResult = $conn->query($replySql);

                if ($replyResult->num_rows > 0) {
                    while ($replyRow = $replyResult->fetch_assoc()) {
    ?>
            <div class="card media border p-3 d-flex flex-column mb-2" style="width: 90%; margin-left: 10%;">
                <div class="d-flex flex-row gap-2 align-items-center">
                    <img width="40px" height="40px" src="<?php echo $replyRow['avatar'] ?>" alt="John Doe" class="rounded-circle">
                    <h5><?php echo $replyRow['username'] ?></h5>
                    <small><i><?php echo $replyRow['createdAt'] ?></i></small>
                </div>
                <div class="media-body">
                    <p><?php echo $replyRow['content'] ?></p>
                    <?php
                        if ($replyRow['fileUrl']) {
                    ?>
                        <div>
                            <img style="width: 500px; height: 500px" src="<?php echo $replyRow['fileUrl'] ?>" class="img-fluid" alt="..." />
                        </div>
                    <?php
                        }
                    ?>
                    <?php
                        if ($_SESSION['role'] == 0) {
                    ?>
                        <form method="POST">
                            <input type="hidden" name="hideCommentId" value="<?php echo $replyRow['commentId'] ?>">
                            <button type="submit" name="toggle_visibility" class="btn btn-danger mt-4">
                                Ẩn bình luận
                            </button>
                        </form>
                    <?php
                        }
                    ?>
                </div>
            </div>
    <?php
                    }
                }
    ?>
<?php
            }
        } else {
            echo '<p>No comments yet.</p>';
        }
?>
</div>
</div>


<div class="modal fade" id="modalPhanHoiBinhLuan" tabindex="-1" aria-labelledby="modalPhanHoiBinhLuan" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalPhanHoiBinhLuan">Phản hồi bình luận</h1>
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

                    <input type="text" class="form-control" id="commentIdReply" name="commentIdReply" readonly>

                    <div class=" mb-3">
                        <label for="description" class="form-label">Trả lời</label>
                        <textarea class="form-control" id="txtContent" name="txtContent" rows="6" style="resize: none;" placeholder="Nhập mô tả" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <div>
                        <input type="submit" class="btn btn-primary" name="sbPhanHoi" value="Submit">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal bình luận bài viết -->
<div class="modal fade" id="modalTraLoi" tabindex="-1" aria-labelledby="modalTraLoiLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalTraLoiLabel">Trả lời</h1>
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
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="txtContent" name="txtContent" rows="6" style="resize: none;" placeholder="Nhập mô tả" required></textarea>
                    </div>
                    <div>
                        <label for="image" class="form-label">Chọn ảnh</label>
                        <input class="form-control" type="file" id="uploadFileTraLoi" name="uploadFileTraLoi" accept="image/*">
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
</div>


<?php
include 'common/footer.php';
?>