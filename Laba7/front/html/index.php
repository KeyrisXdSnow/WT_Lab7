<?php session_start(); ?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Simple Mail</title>
    <link rel="stylesheet" href="./../css/index.css">
</head>
<body>
<header class="header">
</header>
<section class="container">
<form action="./../../back/back.php" method="post">

    <input type="text" name="addr" placeholder="введите email" />
    <input type="text" name="subject" placeholder="тема письма" />
    <textarea  name="message" placeholder="письмо" class="message"></textarea>

    <p><input type="text" name="file_path" placeholder="введите путь к файлу" />
        <input type="submit" name="load_file" value="Загрузить файл" />
    </p>

    <p><input type="submit" value="Отправить" name="send_mail" /></p>
    <p> <?php
        if ( isset($_SESSION['message']) and ($_SESSION['message']['flag'] == true))
            echo "<span style='color: #59991b'>" . $_SESSION['message']['text'] . "</span>";
        else
            echo "<span style='color: rgba(179,25,25,0.89)'>" .$_SESSION['message']['text']."</span>" ;

        unset($_SESSION['message'])
        ?>
    </p>
</form>
</section>
<footer class="footer">
</footer>
</body>
</html>
