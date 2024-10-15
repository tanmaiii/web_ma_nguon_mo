<?php
// session_start();
// ob_start();
include 'common/header.php';
echo '<div class="container px-4 py-2 my-5">';
include 'slider.php';
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
                                <?php echo $row['title'] . $row['categoryId']  ?>
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
        require 'connectSql.php';
        $categoryIdSelect = $_GET['theloai_id'] ?? null;
        $sql =  isset($_GET['theloai_id']) && $_GET['theloai_id'] ? "SELECT * FROM posts where categoryId = $categoryIdSelect" : "SELECT * FROM posts";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

        ?>
                <div class="media card mb-2 rounded-3 shadow-sm px-4 py-4">
                    <div class="media-body">
                        <a href="baiviet.php?postId=<?php echo $row['postId'] ?>">
                            <h4 class="card-title"><?php echo $row['title'] ?></h4>
                        </a>
                        <p class="card-text"><?php echo $row['desc'] ?></p>
                    </div>
                </div>
        <?php
            }
        }
        ?>
    </div>

    <?php
    echo '</div>';
    include 'common/footer.php';
    ?>