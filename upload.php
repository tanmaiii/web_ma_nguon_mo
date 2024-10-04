<?php

?>

<div>
    <h2 class="mb-4">Upload Ảnh</h2>

    <form action="upload.php" method="POST" enctype="multipart/form-data">

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
            <input type="text" class="form-control" id="title" name="title" placeholder="Nhập tiêu đề" required>
        </div>

        <!-- Nhập mô tả -->
        <div class="mb-3">
            <label for="description" class="form-label">Mô tả</label>
            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Nhập mô tả" required></textarea>
        </div>

        <!-- Input upload ảnh -->
        <div class="mb-3">
            <label for="image" class="form-label">Chọn ảnh</label>
            <input class="form-control" type="file" id="image" name="image" accept="image/*" onchange="previewImage()" required>
        </div>


        <!-- Nút submit -->
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
</div>
<script>
    function previewImage() {
        var file = document.getElementById("image").files[0];
        var reader = new FileReader();

        reader.onload = function(e) {
            document.getElementById("imgPreview").src = e.target.result;
            document.getElementById("imgPreview").style.display = "block";
        };

        reader.readAsDataURL(file);
    }
</script>