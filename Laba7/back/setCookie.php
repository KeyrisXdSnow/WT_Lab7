<?php
//$sd = "sd";
////set_cookie($sd,"12");
//foreach ($_COOKIE[$sd] as $key => $value) {
//    setcookie($sd.'[' . $key . ']', 'kta', time()-3600, "/Laba7/back/","localhost");
//}

function set_cookie ($name,$value)
{
    if (isset($_COOKIE[$name])) setcookie($name . '[' . count($_COOKIE[$name]) . ']', $value, "localhost", "/Laba7/back/");
    else setcookie($name . '[0]', $value, "localhost","/Laba7/back/");
}
function delete_cookie ($name) {
    if (isset($_COOKIE[$name])) {
        foreach ($_COOKIE[$name] as $key => $value) {
            setcookie($name.'[' . $key . ']', 'kta', time()-3600, "/Laba7/back/","localhost");
        }
    }
}

