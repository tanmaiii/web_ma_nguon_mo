<?php
// Initialize the session
session_start();
// Update the following variables
$google_oauth_client_id = '139024025713-g006hbe3mumdrso1tshtll9qvqi6pl22.apps.googleusercontent.com'; // Lấy từ Google Developer Console
$google_oauth_client_secret = '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'; // Lấy từ Google Developer Console
$google_oauth_redirect_uri = 'http://localhost/web_ma_nguon_mo/google-oauth.php'; // Địa chỉ URL của trang web
$google_oauth_version = 'v3';

// If the captured code param exists and is valid
if (isset($_GET['code']) && !empty($_GET['code'])) {
    // Execute cURL request to retrieve the access token
    $params = [
        'code' => $_GET['code'],
        'client_id' => $google_oauth_client_id,
        'client_secret' => $google_oauth_client_secret,
        'redirect_uri' => $google_oauth_redirect_uri,
        'grant_type' => 'authorization_code'
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($response, true);
    // Make sure access token is valid
    if (isset($response['access_token']) && !empty($response['access_token'])) {
        // Execute cURL request to retrieve the user info associated with the Google account
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/oauth2/' . $google_oauth_version . '/userinfo');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $response['access_token']]);
        $response = curl_exec($ch);
        curl_close($ch);
        $profile = json_decode($response, true);
        // Make sure the profile data exists
        if (isset($profile['email'])) {
            $google_name_parts = [];
            $google_name_parts[] = isset($profile['given_name']) ? preg_replace('/[^a-zA-Z0-9]/s', '', $profile['given_name']) : '';
            $google_name_parts[] = isset($profile['family_name']) ? preg_replace('/[^a-zA-Z0-9]/s', '', $profile['family_name']) : '';
            // Authenticate the user
            session_regenerate_id();

            // $_SESSION['google_loggedin'] = TRUE;
            // $_SESSION['google_email'] = $profile['email'];
            // $_SESSION['google_name'] = implode(' ', $google_name_parts);
            // $_SESSION['google_picture'] = isset($profile['picture']) ? $profile['picture'] : '';

            $google_email = $profile['email'];
            $google_name = implode(' ', $google_name_parts);
            $google_picture = isset($profile['picture']) ? $profile['picture'] : '';
            $gender = 1;
            $password = '';

            include 'connectSql.php'; // Include your database connection file

            // Kiểm tra người dùng đã tồn tại trong bảng users chưa
            $sql = "SELECT * FROM users WHERE email = '$google_email'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            // Chưa tồn tại thì thêm vào csdl
            if ($result->num_rows == 0) {
                $stmt = $conn->prepare("INSERT INTO users (username, password, fullname, avatar, gender, email) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $google_email, $password, $google_name, $google_picture, $gender, $google_email);
                $stmt->execute();

                $_SESSION['username'] = $google_email;
                $_SESSION['avatar'] = $google_picture;

                $_SESSION['userId'] = $conn->insert_id; // Assuming userId is auto-incremented

                $_SESSION['role'] = 1; // Default role for new users

                header("Location: index.php");

                $_SESSION['role'] = $row['role'];
                header("Location: index.php");
            } else { // đã tồn tại thì lấy thông tin người dùng ra
                $_SESSION['username'] = $row['username'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['avatar'] = $row['avatar'];
                $_SESSION['userId'] = $row['userId'];
                $_SESSION['role'] = $row['role'];
                header("Location: index.php");
                exit;
            }


            // Redirect to profile page
            header('Location: profile.php'); // Trang sẽ được chuyển hướng đến khi đăng nhập thành công
            exit;
        } else {
            exit('Could not retrieve profile information! Please try again later!');
        }
    } else {
        exit('Invalid access token! Please try again later!');
    }
} else {
    // Define params and redirect to Google Authentication page
    $params = [
        'response_type' => 'code',
        'client_id' => $google_oauth_client_id,
        'redirect_uri' => $google_oauth_redirect_uri,
        'scope' => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
        'access_type' => 'offline',
        'prompt' => 'consent'
    ];
    header('Location: https://accounts.google.com/o/oauth2/auth?' . http_build_query($params));
    exit;
}
