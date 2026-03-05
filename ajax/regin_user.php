<?php
    include("../settings/connect_datebase.php");
    
    $login = $mysqli->real_escape_string($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($login) || empty($password)) {
        echo -1;
        exit();
    }
    $query_user = $mysqli->query("SELECT id FROM `users` WHERE `login`='$login'");
    
    if($query_user->num_rows > 0) {
        echo -1; 
        exit();
    } else {
        $mysqli->query("INSERT INTO `users`(`login`, `password`, `roll`) VALUES ('$login', '$password', 0)");
        $new_user_id = $mysqli->insert_id; 
        
        if($new_user_id) {
            $token = md5($new_user_id . "secret_salt_123");

            setcookie("user_id", $new_user_id, time() + 3600 * 24, "/");
            setcookie("user_token", $token, time() + 3600 * 24, "/");

            echo $new_user_id;
        } else {
            echo -1;
        }
    }
?>