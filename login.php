<?php
    include("./settings/connect_datebase.php");
    
    $is_authorized = false;
    
    if (isset($_COOKIE['user_id']) && isset($_COOKIE['user_token'])) {
        $c_id = $_COOKIE['user_id'];
        $c_token = $_COOKIE['user_token'];
        
        if ($c_token === md5($c_id . "secret_salt_123")) {
            $is_authorized = true;
            
            $user_query = $mysqli->query("SELECT * FROM `users` WHERE `id` = " . intval($c_id));
            if($user_read = $user_query->fetch_row()) {
                if($user_read[3] == 0) header("Location: user.php");
                else if($user_read[3] == 1) header("Location: admin.php");
            }
        }
    }
?>
<html>
	<head> 
		<meta charset="utf-8">
		<title> Авторизация </title>
		
		<script src="https://code.jquery.com/jquery-1.8.3.js"></script>
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<div class="top-menu">
			<a href=#><img src = "img/logo1.png"/></a>
			<div class="name">
				<a href="index.php">
					<div class="subname">БЗОПАСНОСТЬ  ВЕБ-ПРИЛОЖЕНИЙ</div>
					Пермский авиационный техникум им. А. Д. Швецова
				</a>
			</div>
		</div>
		<div class="space"> </div>
		<div class="main">
			<div class="content">
				<div class = "login">
					<div class="name">Авторизация</div>
				
					<div class = "sub-name">Логин:</div>
					<input name="_login" type="text" placeholder="" onkeypress="return PressToEnter(event)"/>
					<div class = "sub-name">Пароль:</div>
					<input name="_password" type="password" placeholder="" onkeypress="return PressToEnter(event)"/>
					
					<a href="regin.php">Регистрация</a>
					<br><a href="recovery.php">Забыли пароль?</a>
					<input type="button" class="button" value="Войти" onclick="LogIn()"/>
					<img src = "img/loading.gif" class="loading"/>
				</div>
				
				<div class="footer">
					© КГАПОУ "Авиатехникум", 2020
					<a href=#>Конфиденциальность</a>
					<a href=#>Условия</a>
				</div>
			</div>
		</div>
		
				<script>
			function LogIn() {
				var loading = document.getElementsByClassName("loading")[0];
				var button = document.getElementsByClassName("button")[0];
				
				var _login = document.getElementsByName("_login")[0].value;
				var _password = document.getElementsByName("_password")[0].value;
				loading.style.display = "block";
				button.className = "button_diactive";
				
				var data = new FormData();
				data.append("login", _login);
				data.append("password", _password);
				
				// AJAX запрос
				$.ajax({
					url         : 'ajax/login_user.php',
					type        : 'POST', // важно!
					data        : data,
					cache       : false,
					dataType    : 'html',
					// отключаем обработку передаваемых данных, пусть передаются как есть
					processData : false,
					// отключаем установку заголовка типа запроса. Так jQuery скажет серверу что это строковой запрос
					contentType : false, 
					// функция успешного ответа сервера
					success: function (_data) {
						if(_data.trim() == "") { // Добавьте trim() для надежности
							loading.style.display = "none";
							button.className = "button";
							alert("Логин или пароль не верный.");
						} else {
							// Куки уже установлены сервером в login_user.php
							location.href = "user.php"; // Перенаправляем на главную
						}
					},
										// функция ошибки
					error: function( ){
						console.log('Системная ошибка!');
						loading.style.display = "none";
						button.className = "button";
					}
				});
			}
			
			function PressToEnter(e) {
				if (e.keyCode == 13) {
					var _login = document.getElementsByName("_login")[0].value;
					var _password = document.getElementsByName("_password")[0].value;
					
					if(_password != "") {
						if(_login != "") {
							LogIn();
						}
					}
				}
			}
			
		</script>
	</body>
</html>