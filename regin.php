<?php
    include("./settings/connect_datebase.php");
    if (isset($_COOKIE['user_id']) && isset($_COOKIE['user_token'])) {
        $c_id = intval($_COOKIE['user_id']);
        $c_token = $_COOKIE['user_token'];
        
        if ($c_token === md5($c_id . "secret_salt_123")) {
            $user_query = $mysqli->query("SELECT role FROM `users` WHERE `id` = " . $c_id);
            if($user_read = $user_query->fetch_assoc()) {
                if($user_read['role'] == 0) header("Location: user.php");
                else if($user_read['role'] == 1) header("Location: admin.php");
                exit();
            }
        }
    }
?>
<html>
	<head> 
		<meta charset="utf-8">
		<title> Регистрация </title>
		
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
					<div class="name">Регистрация</div>
				
					<div class = "sub-name">Логин:</div>
					<input name="_login" type="text" placeholder="" onkeypress="return PressToEnter(event)"/>
					<div class = "sub-name">Пароль:</div>
					<input name="_password" type="password" placeholder="" onkeypress="return PressToEnter(event)"/>
					<div class = "sub-name">Повторите пароль:</div>
					<input name="_passwordCopy" type="password" placeholder="" onkeypress="return PressToEnter(event)"/>
					
					<a href="login.php">Вернуться</a>
					<input type="button" class="button" value="Зайти" onclick="RegIn()" style="margin-top: 0px;"/>
					<img src = "img/loading.gif" class="loading" style="margin-top: 0px;"/>
				</div>
				
				<div class="footer">
					© КГАПОУ "Авиатехникум", 2020
					<a href=#>Конфиденциальность</a>
					<a href=#>Условия</a>
				</div>
			</div>
		</div>
		
		<script>
			var loading = document.getElementsByClassName("loading")[0];
			var button = document.getElementsByClassName("button")[0];
			
			function RegIn() {
				var _login = document.getElementsByName("_login")[0].value;
				var _password = document.getElementsByName("_password")[0].value;
				var _passwordCopy = document.getElementsByName("_passwordCopy")[0].value;
				
				if(_login != "") {
					if(_password != "") {
						if(_password == _passwordCopy) {
							loading.style.display = "block";
							button.className = "button_diactive";
							
							var data = new FormData();
							data.append("login", _login);
							data.append("password", _password);
							
							$.ajax({
								url         : 'ajax/regin_user.php',
								type        : 'POST', // важно!
								data        : data,
								cache       : false,
								dataType    : 'html',
								processData : false,
								contentType : false, 
								success: function (_data) {
									console.log("Ответ сервера: " + _data);
									
									if(_data == -1) {
										alert("Ошибка: Пользователь с таким логином уже существует или данные неверны.");
										loading.style.display = "none";
										button.className = "button";
									} else {
										alert("Регистрация успешна!");
										window.location.href = "user.php"; 
									}
								},
								error: function( ){
									console.log('Системная ошибка!');
									loading.style.display = "none";
									button.className = "button";
								}
							});
						} else alert("Пароли не совподают.");
					} else alert("Введите пароль.");
				} else alert("Введите логин.");
			}
			
			function PressToEnter(e) {
				if (e.keyCode == 13) {
					var _login = document.getElementsByName("_login")[0].value;
					var _password = document.getElementsByName("_password")[0].value;
					var _passwordCopy = document.getElementsByName("_passwordCopy")[0].value;
					
					if(_password != "") {
						if(_login != "") {
							if(_passwordCopy != "") {
								RegIn();
							}
						}
					}
				}
			}
			
		</script>
	</body>
</html>