<?php
include 'common/header.php';
echo '<div class="container px-4 py-5 my-5">'
?>


<?php
require 'connectSql.php';
$idSua = "";
$username = $password = $status = "";

if (!isset($_SESSION['username'])) {
    exit();
}

// Xử lý khi người dùng nhấn nút xóa
if (isset($_GET['delete_id'])) {

    $sqlSelectImageUrl = "SELECT imageUrl FROM slideshow WHERE id = " . $_GET['delete_id'];
    $resultImgageUrl = $conn->query($sqlSelectImageUrl);
    $slide = $resultImgageUrl->fetch_assoc();

    if ($slide) {
        $imagePath = $slide['imageUrl'];
        
        if (file_exists($imagePath)) {
            unlink($imagePath);  // Xóa hình ảnh khỏi thư mục
        }

        $sql = "DELETE FROM slideshow WHERE id = " . $_GET['delete_id'];
        $result = $conn->query($sql);

        header("Location: slideshow.php");
    }
    exit();
}
?>

<?php
require 'connectSql.php';
$sql = "SELECT * FROM slideshow";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
?>
    <div style="width: 100%">
        <h2>
            Slide
        </h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="width: 20%;">Title</th>
                    <th style="width: 30%;">Desc</th>
                    <th style="width: 20%;">Image</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 20%;"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $result->fetch_assoc()) {
                ?>
                    <tr>
                        <td><?php echo $row['title'] ?></td>
                        <td><?php echo $row['desc'] ?></td>
                        <td>
                            <img class="object-fit-cover border border-info-subtle" style="width: 60px; height: 60px;" src='<?php echo $row['imageUrl']  ?>' alt="">
                        </td>
                        <td><?php echo $row['status'] == 1 ? 'Ẩn' : 'Hiện' ?></td>
                        <td>
                            <a href="edit_slide.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Sửa</a>

                            <a
                                href="slideshow.php?delete_id=<?php echo $row['id']; ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Bạn có chắc muốn xóa slide này không?')">Xóa</a>

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
echo '</div>';
include 'common/footer.php';
?>