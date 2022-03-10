<?php
date_default_timezone_set('Asia/Kolkata');
// Color
$red = "\033[0;31m";
$green = "\033[0;32m";
$yellow = "\033[0;33m";
$cyan = "\033[0;36m";
$blue = "\033[0;34m";
$normal = "\033[0m";

$banned = "$cyan

.##..##...####...##......######..#####............####...##...##..######.
.##..##..##..##..##........##....##..##..........##..##..###.###.....##..
.##..##..######..##........##....##..##..........######..##.#.##....##...
..####...##..##..##........##....##..##..........##..##..##...##...##....
...##....##..##..######..######..#####...........##..##..##...##..######.
.........................................................................
                                                                       $normal
\n";

function email_chk($email)
{
    $ch = curl_init("https://apiceker.ddns.net/?email=$email");
    curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:97.0) Gecko/20100101 Firefox/97.0");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $response = curl_exec($ch);
    return $response;
}

echo $banned;
echo $blue . "Masukkan List : ";
$getlist = trim(fgets(STDIN));

$inilist = preg_split(
    '/\n|\r\n?/',
    trim(file_get_contents($getlist))
);

for ($i = 0; $i < count($inilist); $i++) {
    $action = email_chk($inilist[$i]);
    $res = json_decode($action);
    $hit = $i + 1;
    if ($res->status == 'live') {
        echo $green . "[ $hit / " . count($inilist) . " ] " . $inilist[$i] . " ==> Live [ Valid Amazon by ./Artes | Expired : " . $res->expired . " Lagi ] \n" . $normal;
        file_put_contents('live.txt', $inilist[$i] . PHP_EOL, FILE_APPEND);
    } elseif ($res->status == 'die') {
        echo $red . "[ $hit / " . count($inilist) . " ] " . $inilist[$i] . " ==> Die [ Valid Amazon by ./Artes  | Expired : " . $res->expired . " Lagi ] \n" . $normal;
        file_put_contents('die.txt', $inilist[$i] . PHP_EOL, FILE_APPEND);
    } elseif ($res->status == 'Update Now') {
        echo $yellow . "Checker Need To Fixed! \n" . $normal;
        // exit;
    } elseif ($res->expired == -1) {
        echo $yellow . "Checker Expired!\n" . $normal;
        // exit;
    } else {
        echo $yellow . "Maybe IP BLOKED\n" . $normal;
        // exit;
    }
}
