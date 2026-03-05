<?php
    include("./settings/connect_datebase.php");
    $is_authorized = false;
    $user_id = 0;
    if (isset($_COOKIE['user_id']) && isset($_COOKIE['user_token'])) {
        $c_id = $_COOKIE['user_id'];
        $c_token = $_COOKIE['user_token'];

        if ($c_token === md5($c_id . "secret_salt_123")) {
            $is_authorized = true;
            $user_id = intval($c_id);
        }
    }
?>
<!DOCTYPE HTML>
<html>
    <head> 
        <meta charset="utf-8">
        <title> WEB-безопасность </title>
        <link rel="stylesheet" href="style.css">
        <script src="https://code.jquery.com/jquery-1.8.3.js"></script>
    </head>
    <body>
        <div class="top-menu">
            <?php if (!$is_authorized): ?>
                <a class="button" href="./login.php">Войти</a>
            <?php else: ?>
                <a class="button" href="./ajax/logout.php">Выйти</a>
            <?php endif; ?>
        
            <a href="#"><img src="img/logo1.png"/></a>
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
                <div class="name">Новости:</div>
                <div>
                    <?php
                        $query_news = $mysqli->query("SELECT * FROM `news`;");
                        while($read_news = $query_news->fetch_assoc()) {
                            $QueryMessages = $mysqli->query("SELECT * FROM `comments` WHERE `IdPost` = " . intval($read_news["id"]));

                            echo '
                                <div class="specialty">
                                    <div class="slider">
                                        <div class="inner">
                                            <div class="name">'.htmlspecialchars($read_news["title"]).'</div>
                                            <div class="description" style="overflow: auto;">
                                                <img src="'.$read_news["img"].'" style="width: 50px; float: left; margin-right: 10px;">
                                                '.$read_news["text"].'
                                            </div>
                                            <div class="messages-list">';
                                                while($ReadMessages = $QueryMessages->fetch_assoc()) {
                                                    echo "<div>" . htmlspecialchars($ReadMessages["Messages"]) . "</div>";
                                                }
                                            echo '</div>';

                                            if ($is_authorized) {
                                                echo 
                                                    '<div class="comment-form" id="'.$read_news["id"].'">
                                                        <input type="text" style="width: 80%">
                                                        <div class="button" style="display:inline-block; cursor:pointer;" onclick="SendMessage(this)">Отправить</div>
                                                    </div>';
                                            }
                                            
                                        echo '</div>
                                    </div>
                                </div>';
                        }
                    ?>
                </div>
            </div>
        </div>
    </body>

    <script>
		
        function SendMessage(sender) {
            let container = sender.parentElement;
            let Message = container.querySelector('input').value;
            let IdPost = container.id;

            if(Message.trim() == "") return;

            var Data = new FormData();
            Data.append("Message", Message);
            Data.append("IdPost", IdPost);
            
            $.ajax({
                url: 'ajax/message.php',
                type: 'POST',
                data: Data,
                processData: false,
                contentType: false, 
                success: function (_data) {
                    if(_data == "success") {
                        let list = container.parentElement.querySelector('.messages-list');
                        let newMsg = document.createElement('div');
                        newMsg.textContent = Message;
                        list.appendChild(newMsg);
                        container.querySelector('input').value = "";
                    } else {
                        alert("Ошибка при отправке: " + _data);
                    }
                }
            });
        }
    </script>
</html>