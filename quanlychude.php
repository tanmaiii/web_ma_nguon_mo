<?php
include 'common/header.php';
echo '<div class="container px-4 py-5 my-5 d-flex justify-content-center align-items-center flex-column">
<div style="max-width: 600px; width: 600px " class="card mb-4 rounded-3 shadow-sm px-4 py-4">';

if (!isset($_SESSION['username']) && isset($_SESSION['role']) == 1) {
    header("Location: index.php");
}

require 'connectSql.php';

$ketQuaThanhCong = "";
$ketQuaLoi = "";
$editMode = false;
$editId = null;
$title = "";
$description = "";
$status = 0;

if (isset($_POST['sbThemChuDe'])) {
    $title = $_REQUEST['txtTitle'];
    $description = $_REQUEST['txtDesc'];
    $status = $_REQUEST['status'];


    $sql = "INSERT INTO categories (`title`, `desc`, `status`) VALUES ('$title', '$description', $status)";
    $result = mysqli_query($conn, $sql);
    if ($result === TRUE) {
        $ketQuaThanhCong = "Upload thành công";
    } else {
        $ketQuaLoi = "Error: {$sql}<br>{$conn->error}";
    }
}

if (isset($_POST['sbSua']) && isset($_POST['edit_id'])) {
    $title = $_REQUEST['txtTitle'];
    $description = $_REQUEST['txtDesc'];
    $status = $_REQUEST['status'];
    $editId = $_POST['edit_id'];
    $sql = "UPDATE categories SET `title`='$title', `desc`='$description', `status` ='$status' WHERE categoryId='$editId'";
    $result = mysqli_query($conn, $sql);
    if ($result === TRUE) {
        $ketQuaThanhCong = "Cập nhật thành công";
    } else {
        $ketQuaLoi = "Error: {$sql}<br>{$conn->error}";
    }
}

if (isset($_GET['edit_id'])) {
    $editMode = true;
    $editId = $_GET['edit_id'];
    $sql = "SELECT * FROM categories WHERE categoryId='$editId'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $title = $row['title'];
        $description = $row['desc'];
        $status = $row['status'];
    }
}

if (isset($_GET['delete_id'])) {
    $sql = "DELETE FROM categories WHERE categoryId = " . $_GET['delete_id'];
    $result = $conn->query($sql);
    if ($result === TRUE) {
        $ketQuaThanhCong = "Xóa thành công";
    } else {
        $ketQuaLoi = "Error: {$sql}<br>{$conn->error}";
    }
}
?>

<form action="" method="POST" enctype="multipart/form-data">
    <h2>
        <?php echo $editMode ? "Edit Category" : "Add Category"; ?>
    </h2>

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

    <div class="form-group">
        <label for="title">Tiêu đề</label>
        <input type="text" class="form-control" id="txtTitle" name="txtTitle" value="<?php echo $title; ?>" required>
    </div>
    <div class="form-group">
        <label for="desc">Mô tả</label>
        <textarea class="form-control" id="txtDesc" name="txtDesc" rows="3" required><?php echo $description; ?></textarea>
    </div>

    <div class="mb-3">
        <label for="formFile" class="form-label">Trạng thái</label>
        <div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" name="status" <?php echo $status == 0 ? 'checked' : '' ?> type="radio" value="0" id="inlineRadio1">
                <label class="form-check-label" for="inlineRadio1">Ẩn</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" name="status" <?php echo $status == 1 ? 'checked' : '' ?> type="radio" value="1" id="inlineRadio2">
                <label class="form-check-label" for="inlineRadio2">Hiện</label>
            </div>
        </div>
    </div>

    <?php if ($editMode) { ?>
        <input type="hidden" name="edit_id" value="<?php echo $editId; ?>">
    <?php } ?>
    <div class="py-2">
        <input type="submit" class="btn btn-primary" name="sbThemChuDe" value="Thêm">
        <input type="submit" class="btn btn-primary" name="sbSua" value="Sửa">
    </div>
</form>

<?php
require 'connectSql.php';
$sql = "SELECT * FROM categories";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
?>
    <div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="width: 20%;">Title</th>
                    <th style="width: 30%;">Desc</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 30%;"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $result->fetch_assoc()) {
                ?>
                    <tr>
                        <td><?php echo $row['title'] ?></td>
                        <td><?php echo $row['desc'] ?></td>
                        <td><?php echo $row['status'] == 1 ? 'Hiện' : 'Ẩn' ?></td>
                        <td>
                            <a href="quanlychude.php?edit_id=<?php echo $row['categoryId']; ?>" class="btn btn-primary btn-sm">Edit</a>
                            <a href="quanlychude.php?delete_id=<?php echo $row['categoryId']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa thể loại này không?')">Xóa</a>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
<?php
} else {
    echo "Không có dữ liệu";
    return;
}
?>

<?php
echo ' </div> </div>';
include 'common/footer.php';
?>