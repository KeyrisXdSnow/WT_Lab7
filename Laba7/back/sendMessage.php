<?php
session_start();

function send_mail ($from, $to, $subject, $message, $file_path)
{
    $answer = "";$flag = "true";

    if ( ($addr = handle_addr(trim($to))) != "" and ($subject = handle_subject($subject)) != "" and
            ($message = handle_text(trim($message))) != "" ) {

        $headers = ""; $info = "" ;

        if (isset($file_path) and $file_path != "") {
            $info = handle_message_with_file ($from, $message,$file_path);
            if ($info != null) {
                $message = $info['message'];
                $headers = $info['headers'];
            } else $flag = false;
        }

        if ( isset($info) and mail($addr, $subject, $message,$headers)) $answer = "Сообщение отправлено";
        else {
            $answer = "При отправке сообщения возникла ошибка";
            $flag = "false";
        }

    } else $flag = "false" ;

    $_SESSION['message']['text'] = $answer;
    $_SESSION['message']['flag'] = $flag;

}

function handle_addr ($addr) {
    global $answer;
    if (!isset($addr)) {
        $answer = "Заполните email получателя. ";
        return "";
    }
    $addr = trim($addr);
    $recipients = preg_split("/,/",$addr);
    foreach ($recipients as $recipient) {
        if (!preg_match("/^[a-zA-z0-9_\-.]+@[a-zA-z0-9\-]+\.[a-zA-z0-9\-.]+$/", trim($recipient))) {
            $answer = "Неверно заполнен email ".$recipient;
            $addr = "";
            break;
        }
    }
    return $addr;
}

function handle_subject ($subject) {
    global $answer;
//
//    if (!isset($subject)) {
//        $answer = "Введите тему сообщения. ";
//        return "";
//    }
//
//    $subject = trim($subject);
//
//    if ($subject == "") $answer = "Введите тему сообщения. ";
//    else
        $subject = "=?utf-8?b?".base64_encode($subject)."?=";

    return $subject ;
}

function handle_text ($text) {
    global $answer ;

    if (!isset($text)) {
        $answer = "Введите текст сообщения. ";
        return "";
    }

    trim($text);
    if ($text == "") $answer = "Введите текст сообщения. ";

    $text = preg_replace("/\n/","<br>",$text);

    return $text ;
}

function handle_message_with_file ($from, $message,$file_paths) {

    global $answer;
    $separator = "\r\n"; // ограничитель строк, некоторые почтовые сервера требуют \n - подобрать опытным путём
    $boundary     = "--".md5(uniqid(time()));  // любая строка, которой не будет ниже в потоке данных.

    $headers    = "MIME-Version: 1.0;$separator";
    $headers   .= "Content-Type: multipart/mixed; boundary=\"$boundary\"$separator";
    $headers   .= "From: $from";

    $bodyMail  = "--$boundary$separator";
    $bodyMail .= "Content-Type: text/html; charset=utf-8$separator";
    $bodyMail .= "Content-Transfer-Encoding: base64$separator";
    $bodyMail .= $separator; // раздел между заголовками и телом html-части
    $bodyMail .= chunk_split(base64_encode($message));


    foreach ($file_paths as $file_path) {
        $file_name = basename($file_path);

        if (file_exists($file_path)) {
            $fp = fopen($file_path, "rb");
            if (!$fp) {
                $answer = 'При открытии файла'.basename($file_path).' возникла ошибка';
                return null;
            }
            $file = fread($fp, filesize($file_path));
            fclose($fp);
        } else {
            $answer = 'Файл '.basename($file_path).' не найден';
            return null;
        }
        $bodyMail .= "$separator--$boundary$separator";
        $bodyMail .= "Content-Type: application/octet-stream; name=\"$file_name\"$separator";
        $bodyMail .= "Content-Transfer-Encoding: base64$separator";
        $bodyMail .= "Content-Disposition: attachment; filename=\"$file_path\"$separator";
        $bodyMail .= $separator; // раздел между заголовками и телом прикрепленного файла
        $bodyMail .= chunk_split(base64_encode($file));
    }

    return array ("message" => $bodyMail, "headers" => $headers);

}


?>