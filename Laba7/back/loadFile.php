<?php
session_start();

function loadFile ($file_path) {
    if (file_exists($file_path)) {
        $file_reader = fopen($file_path, "r");
        return fread($file_reader, filesize($file_path));
    } else {
        $_SESSION['message']['text'] = "Такого файла не существует";
        $_SESSION['message']['flag'] = "";
    }
    return "";
}