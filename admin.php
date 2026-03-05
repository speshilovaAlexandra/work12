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
<!DOCTYPE HTML>
<html>
	<head> 
		<script src="https://code.jquery.com/jquery-1.8.3.js"></script>
		<meta charset="utf-8">
		<title> Admin панель </title>
		
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
				<input type="button" class="button" value="Выйти" onclick="logout()"/>
				
				<div class="name">Административная панель</div>
			
				Административная панель служит для создания, редактирования и удаления записей на сайте.
			
				<div class="footer">
					© КГАПОУ "Авиатехникум", 2020
					<a href=#>Конфиденциальность</a>
					<a href=#>Условия</a>
				</div>
			</div>
		</div>
		
		<script>
			function logout() {
				$.ajax({
					url         : 'ajax/logout.php',
					type        : 'POST', // важно!
					data        : null,
					cache       : false,
					dataType    : 'html',
					processData : false,
					contentType : false, 
					success: function (_data) {
						location.reload();
					},
					error: function( ){
						console.log('Системная ошибка!');
					}
				});
			}
		</script>
	</body>
</html>