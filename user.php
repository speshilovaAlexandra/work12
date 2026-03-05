<?php
    include("./settings/connect_datebase.php");
    
    $is_authorized = false;
    $current_user_id = 0;
    
    if (isset($_COOKIE['user_id']) && isset($_COOKIE['user_token'])) {
        $c_id = $_COOKIE['user_id'];
        $c_token = $_COOKIE['user_token'];
        
        if ($c_token === md5($c_id . "secret_salt_123")) {
            $current_user_id = intval($c_id);
            $user_query = $mysqli->query("SELECT * FROM `users` WHERE `id` = " . $current_user_id);
            if($user_read = $user_query->fetch_row()) {
                $is_authorized = true;
                $user_data = $user_read;
            }
        }
    }
    if (!$is_authorized) {
        header("Location: login.php");
        exit();
    }
?>
<!DOCTYPE HTML>
<html>
    <head> 
        <script src="https://code.jquery.com/jquery-1.8.3.js"></script>
        <meta charset="utf-8">
        <title> Личный кабинет </title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="top-menu">
            <a href="index.php"><img src="img/logo1.png"/></a>
            <div class="name">
                <a href="index.php">
                    <div class="subname">БЕЗОПАСНОСТЬ ВЕБ-ПРИЛОЖЕНИЙ</div>
                    Пермский авиационный техникум им. А. Д. Швецова
                </a>
            </div>
        </div>

        <div class="space"> </div>
        <div class="main">
            <div class="content">
                <input type="button" class="button" value="Выйти" onclick="logout()"/>
                
                <div class="name" style="padding-bottom: 0px;">Личный кабинет</div>
                
                <div class="description">
                    Добро пожаловать: <b><?php echo htmlspecialchars($user_data[1]); ?></b>
                    <br>Ваш идентификатор: <b><?php echo $user_data[0]; ?></b>
                </div>
            
                <div class="footer">
                    © КГАПОУ "Авиатехникум", 2020
                    <a href=#>Конфиденциальность</a>
					<a href=#>Условия</a>
                </div>
            </div>
        </div>
        
        <script>
            function logout() {
                window.location.href = 'ajax/logout.php';
            }

            function DeleteStatementt(id_statement) {
                if(id_statement != -1) {
                    var data = new FormData();
                    data.append("id_statement", id_statement);
                    $.ajax({
                        url: 'ajax/delete_statement.php',
                        type: 'POST',
                        data: data,
                        processData: false,
                        contentType: false, 
                        success: function (_data) {
                            location.reload();
                        }
                    });
                }
            }
        </script>
    </body>
</html>