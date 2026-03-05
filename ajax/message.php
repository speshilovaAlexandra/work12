<?
    include("../settings/connect_datebase.php");

    if (!isset($_COOKIE['user_id']) || !isset($_COOKIE['user_token'])) {
        die("Доступ запрещен");
    }

    $c_id = $_COOKIE['user_id'];
    $c_token = $_COOKIE['user_token'];

    if ($c_token !== md5($c_id . "secret_salt_123")) {
        die("Неверный токен");
    }

    $IdUser = intval($c_id);
    $Message = $mysqli->real_escape_string($_POST["Message"]);
    $IdPost = intval($_POST["IdPost"]);

    $sql = "INSERT INTO `comments`(`IdUser`, `IdPost`, `Messages`) VALUES ($IdUser, $IdPost, '$Message')";

    if($mysqli->query($sql)) {
        echo "success";
    } else {
        echo "Ошибка БД: " . $mysqli->error;
    }
?>