<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>

    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Learn PHP</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse d-flex justify-content-between" id="collapsibleNavbar">
                <div>
                    <ul class="navbar-nav">
                        <?php
                        if (isset($_SESSION['username']) && isset($_SESSION['username']) != '') {
                            echo '
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?page=upload">Upload</a>
                            </li>
                        ';
                        }
                        ?>
                    </ul>

                </div>
                <div>
                    <?php
                    if (isset($_SESSION['username']) && isset($_SESSION['username']) != '') {
                        echo '<ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="#">Welcome ' . $_SESSION['username'] . '</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=logout">Logout</a>
                        </li>';
                    } else {
                        echo '
                          <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?page=login">Đăng nhập</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?page=signup">Đăng ký</a>
                            </li>
                        </ul>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </nav>