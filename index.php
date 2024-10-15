<?php
// session_start();
// ob_start();
include 'common/header.php';
echo '<div class="container px-4 py-2 my-5">';
include 'slider.php';
?>

<div class="mt-4">
    <div class="col-md-3">
        <div class="d-flex flex-column flex-shrink-0 p-3 bg-body-tertiary">
            <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
                <span class="fs-4">Chủ đề</span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <?php
                require 'connectSql.php';
                $sql = "SELECT * FROM categories";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                ?>
                        <li class="nav-item">
                            <a href="#" class="nav-link link-body-emphasis" aria-current="page">
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
    <div class="col-md-9"></div>
</div>

<?php
echo '</div>';
include 'common/footer.php';
?>