<?php
include 'common/header.php';
require 'connectSql.php';

if (!isset($_GET['postId'])) {
    header("Location: index.php");
} else {
    $postId = $_GET['postId'];
    $title = $desc = $fileUrl = "";

    $sql = "SELECT * FROM posts where postId = $postId";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $title = $row['title'];
        $desc = $row['desc'];
        $fileUrl = $row['fileUrl'];
    }
}
?>
<div class="container py-4 d-flex justify-content-center align-items-center flex-column" style="min-height: 80vh">
    <div class="p-5 mb-4 bg-body-tertiary rounded-3" style="min-height:600px; min-width:600px">
        <div class="container-fluid py-5">
            <h1 class="display-5 fw-bold"><?php echo $title ?></h1>
            <p class="col-md-8 fs-4"><?php echo $desc ?></p>
            <button class="btn btn-primary btn-lg" type="button">Example button</button>
        </div>
    </div>
    <div>
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="button" id="button-addon2">Button</button>
        </div>

        <div class="card media border p-3 d-flex flex-row mb-2" style="width: 100%">
            <img src="uploads/1728900922_1e75f3b2-dd19-46c6-ae1a-84611017eaf9.jpg" alt="John Doe" class="mx-3 mt-3 rounded-circle" style="width:60px;">
            <div class="media-body">
                <h4>John Doe <small><i>Posted on February 19, 2016</i></small></h4>
                <p>Lorem ipsum...</p>
            </div>
        </div>
        <div class="media border p-3">
            <img src="img_avatar3.png" alt="John Doe" class="mr-3 mt-3 rounded-circle" style="width:60px;">
            <div class="media-body">
                <h4>John Doe <small><i>Posted on February 19, 2016</i></small></h4>
                <p>Lorem ipsum...</p>
            </div>
        </div>
    </div>
</div>


<?php
include 'common/footer.php';
?>