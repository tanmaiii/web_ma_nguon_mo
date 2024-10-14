<?php
require 'connectSql.php';
$sql = "SELECT * FROM slideshow WHERE status = 0";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
?>
    <div id="myCarousel" class="carousel slide mb-6 rounded-4" data-bs-ride="carousel">
        <div class=" carousel-inner rounded-4" style="height: 600px">
            <?php
            $index = 1;
            while ($row = $result->fetch_assoc()) {
            ?>
                <div class="carousel-item <?php echo $index === 1 ? 'active' : '' ?>" style="height: 600px">
                    <img class="bd-placeholder-img object-fit-cover" width="100%" height="100%" src="<?php echo $row['imageUrl']; ?>" alt="">
                    <div class="container">
                        <div class="carousel-caption text-start">
                            <h1><?php echo $row['title']; ?></h1>
                            <p class="opacity-75"><?php echo $row['desc']; ?></p>
                        </div>
                    </div>
                </div>
            <?php
                $index++;
            }
            ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
<?php
} else {
    echo "Không có dữ liệu";
    return;
}
?>