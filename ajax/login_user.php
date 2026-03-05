<?php
    include("../settings/connect_datebase.php");

	$login = $_POST['login'] ?? '';
	$password = $_POST['password'] ?? '';

	if ($mysqli->connect_error) {
		die("Ошибка подключения");
	}

	// Подготавливаем SQL-запрос (защита от SQL-инъекций через prepare)
    $stmt = $mysqli->prepare("SELECT id FROM users WHERE login=? AND password=?");
    // Привязываем параметры: "ss" означает две строки (string)
    $stmt->bind_param("ss", $login, $password);
    // Выполняем запрос
    $stmt->execute();
    // Получаем результат выполнения
    $result = $stmt->get_result();
    // Извлекаем данные пользователя в виде ассоциативного массива
    $user = $result->fetch_assoc();

	if($user) {
		$id = $user['id'];
		$token = md5($id . "secret_salt_123"); 
		// Устанавливаем Cookies на 24 часа (86400 сек) для ID и токена
		setcookie("user_id", $id, time() + 86400, "/");
		setcookie("user_token", $token, time() + 86400, "/");

		echo "success"; 
	} else {
		echo ""; 
	}
?>