<?php
    include("../settings/connect_datebase.php");
    $login = $mysqli->real_escape_string($_POST['login'] ?? '');
    
    if (empty($login)) {
        echo -1;
        exit();
    }

    $query_user = $mysqli->query("SELECT id FROM `users` WHERE `login`='$login'");
    $user_read = $query_user->fetch_assoc();
    
    if(!$user_read) {
        echo -1;
        exit();
    }

    $id = $user_read['id'];

    function PasswordGeneration() {
        $chars = "qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
        $max = 10;
        $size = strlen($chars) - 1;
        $password = "";
        while($max--) {
            $password .= $chars[rand(0, $size)];
        }
        return $password;
    }

    $new_password = PasswordGeneration();
    
    $hashed_password = md5($new_password);
    $update = $mysqli->query("UPDATE `users` SET `password`='$hashed_password' WHERE `id` = $id");

    if($update) {
        echo $id;
    } else {
        echo -1;
    }
?>