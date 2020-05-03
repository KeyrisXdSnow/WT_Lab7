<?php
session_start();
if (isset($_POST['load_file'])) {

    if (file_exists($_POST['file_path'])) {
        $_SESSION['message']['text'] = "Файл ".basename($_POST['file_path'])." найден";
        $_SESSION['message']['flag'] = "true";
        include ("setCookie.php");
        set_cookie("path",$_POST['file_path']);
    } else {
        $_SESSION['message']['text'] = "Такого файла не существует";
        $_SESSION['message']['flag'] = "";
    }
    header("Location: ./../front/html/index.php");
}

if (isset($_POST['send_mail'])) {

    $from = "example@gmail.com"; // your email

    $file_path = $_COOKIE['path'];

    include("sendMessage.php");
    send_mail($from,$_POST['addr'],$_POST['subject'],$_POST['message'],$file_path);

    include("setCookie.php");
    delete_cookie("path");

    header("Location: ./../front/html/index.php");
}
