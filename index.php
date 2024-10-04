<?php
session_start();
ob_start();
include 'header.php';
include 'slider.php';
echo '<div class="container px-4 py-5 my-5">';
?>

<div class="row g-5">
    <div class="col-md-4">
        <div class="position-sticky" style="top: 2rem;">
            <div class="p-4 mb-3 bg-body-tertiary rounded">
                <h4 class="fst-italic">About</h4>
                <p class="mb-0">You arrived, and life arrived with you in all its forms and colors: plants grow, trees leaf and bloom, the cat meows, the dove coos, the sheep bleats, the cow moos, and every pet calls its mate. Everything feels life and forgets life's worries, remembering only life's happiness. If time were a body, you are its soul, and if it were an age, you are its youth.</p>
            </div>

            <div>
                <h4 class="fst-italic">Recent Posts</h4>
                <ul class="list-unstyled">
                    <li>
                        <a class="d-flex flex-column flex-lg-row gap-3 align-items-start align-items-lg-center py-3 link-body-emphasis text-decoration-none border-top" href="#">
                            <svg class="bd-placeholder-img" width="100%" height="96" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false">
                                <rect width="100%" height="100%" fill="#777"></rect>
                            </svg>
                            <div class="col-lg-8">
                                <h6 class="mb-0">kjashskjdajkshdة</h6>
                                <small class="text-body-secondary">15 12312 2024</small>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="d-flex flex-column flex-lg-row gap-3 align-items-start align-items-lg-center py-3 link-body-emphasis text-decoration-none border-top" href="#">
                            <svg class="bd-placeholder-img" width="100%" height="96" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false">
                                <rect width="100%" height="100%" fill="#777"></rect>
                            </svg>
                            <div class="col-lg-8">
                                <h6 class="mb-0">alksjdlkasjkldjaskljd</h6>
                                <small class="text-body-secondary">14 12312 2024</small>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="d-flex flex-column flex-lg-row gap-3 align-items-start align-items-lg-center py-3 link-body-emphasis text-decoration-none border-top" href="#">
                            <svg class="bd-placeholder-img" width="100%" height="96" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false">
                                <rect width="100%" height="100%" fill="#777"></rect>
                            </svg>
                            <div class="col-lg-8">
                                <h6 class="mb-0">alsjdlkasjkldjaslkjdaklsdlkas</h6>
                                <small class="text-body-secondary">13 12312 2024</small>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="p-4">
                <h4 class="fst-italic">Year</h4>
                <ol class="list-unstyled mb-0">
                    <li><a href="#"> 2021</a></li>
                    <li><a href="#"> 2021</a></li>
                    <li><a href="#"> 2021</a></li>
                    <li><a href="#"> 2020</a></li>
                    <li><a href="#"> 2020</a></li>
                    <li><a href="#"> 2020</a></li>
                    <li><a href="#"> 2020</a></li>
                    <li><a href="#"> 2020</a></li>
                    <li><a href="#"> 2020</a></li>
                    <li><a href="#"> 2020</a></li>
                    <li><a href="#"> 2020</a></li>
                    <li><a href="#"> 2020</a></li>
                </ol>
            </div>

            <div class="p-4">
                <h4 class="fst-italic"> ádasdasd</h4>
                <ol class="list-unstyled">
                    <li><a href="#">GitHub</a></li>
                    <li><a href="#">Twitter</a></li>
                    <li><a href="#">Facebook</a></li>
                </ol>
            </div>
        </div>
    </div>
    <div class="col-md-8 d-flex align-items-center flex-column">
        <?php
        $page = isset($_GET['page']) ? $_GET['page'] : '';

        switch ($page) {
            case 'signup':
                include "./signup.php";
                break;
            case "login":
                include './login.php';
                break;
            case "logout":
                session_unset();
                session_destroy();
                header("Location: index.php?page=login");
                break;
            case "upload":
                include './upload.php';
                break;
            default:
                include './home.php';
                break;
        }
        ?>
    </div>
</div>

<?php
echo '</div>';
include 'footer.php';
?>