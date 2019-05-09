<?php
set_time_limit(0);
ignore_user_abort(1);
$o = PREG_SET_ORDER;

function GetInTranslit($string)
{
    $replace = array(
        "'" => "",
        "`" => "",
        "а" => "a",
        "А" => "a",
        "б" => "b",
        "Б" => "b",
        "в" => "v",
        "В" => "v",
        "г" => "g",
        "Г" => "g",
        "д" => "d",
        "Д" => "d",
        "е" => "e",
        "Е" => "e",
        "ж" => "zh",
        "Ж" => "zh",
        "з" => "z",
        "З" => "z",
        "и" => "i",
        "И" => "i",
        "й" => "y",
        "Й" => "y",
        "к" => "k",
        "К" => "k",
        "л" => "l",
        "Л" => "l",
        "м" => "m",
        "М" => "m",
        "н" => "n",
        "Н" => "n",
        "о" => "o",
        "О" => "o",
        "п" => "p",
        "П" => "p",
        "р" => "r",
        "Р" => "r",
        "с" => "s",
        "С" => "s",
        "т" => "t",
        "Т" => "t",
        "у" => "u",
        "У" => "u",
        "ф" => "f",
        "Ф" => "f",
        "х" => "h",
        "Х" => "h",
        "ц" => "c",
        "Ц" => "c",
        "ч" => "ch",
        "Ч" => "ch",
        "ш" => "sh",
        "Ш" => "sh",
        "щ" => "sch",
        "Щ" => "sch",
        "ъ" => "",
        "Ъ" => "",
        "ы" => "y",
        "Ы" => "y",
        "ь" => "",
        "Ь" => "",
        "э" => "e",
        "Э" => "e",
        "ю" => "yu",
        "Ю" => "yu",
        "я" => "ya",
        "Я" => "ya",
        "і" => "i",
        "І" => "i",
        "ї" => "yi",
        "Ї" => "yi",
        "є" => "e",
        "Є" => "e"
    );
    return $str = iconv("UTF-8", "UTF-8//IGNORE", strtr($string, $replace));
}

function encodestring($st)
{
    $st = GetInTranslit($st);
    $st = str_replace(" ", "-", $st);
    $st = preg_replace('/[^a-z\d-_]/iU', '', $st);
    $st = mb_convert_case($st, MB_CASE_LOWER, "UTF-8");
    $st = str_replace("_", "-", $st);
    $st = str_replace("--", "-", $st);
    $st = str_replace("--", "-", $st);
    $st = str_replace("--", "-", $st);
    return $st;
}

function comment($comment)
{
    echo $comment . "<br>";
    flush();
    ob_flush();
    flush();
    $f = fopen(dirname(__FILE__) . '/tmp/log.html', 'a+');
    fwrite($f, $comment . "<br>");
    fclose($f);
}


function get_page($url, $data = null, $options = null)
{
    $process = curl_init($url);
    curl_setopt($process, CURLOPT_HEADER, 0);
    if (!is_null($data)) {
        curl_setopt($process, CURLOPT_POST, 1);
        curl_setopt($process, CURLOPT_POSTFIELDS, $data);
    }
    if (!is_null($options)) {
        curl_setopt_array($process, $options);
    }
    curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($process, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/tmp/cookiefile.txt');
    curl_setopt($process, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/tmp/cookiefile.txt');
    //curl_setopt($process, CURLOPT_COOKIE, $cok) ;
    curl_setopt($process, CURLOPT_USERAGENT,
        'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0');
    @curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($process, CURLOPT_MAXREDIRS, 10);
    curl_setopt($process, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($process, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($process, CURLOPT_TIMEOUT, 30);
    $return = curl_exec($process);
    //die(curl_error($process));
    curl_close($process);
    //sleep(rand(2,5));
    return $return;
}

?>