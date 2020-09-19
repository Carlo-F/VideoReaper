<?php
ini_set('memory_limit', '-1'); // unlimited

$opts = getopt("u:h::");

$regex = "/<a\shref=\"\.?\/?(.+?)\">.+?(.MOV|.mov|.mp4|.mpg|.mpeg|.webm)<\/a>/";

if(isset($opts["u"])){
    $url = $opts["u"];
} elseif(isset($opts["h"])) {
    print("videoReaper\nHelps you download all the videos in a url\nRequired options:\n-u : url");
    exit;  
} else {
    print("Error: missing 'u'[Url] parameter\nCorrect command example: php videoReaper.php -u https://www.google.com/");
    exit;
}


$curl = curl_init("$url");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$web_page = curl_exec($curl);
curl_close($curl);

if (!file_exists("downloads") && !mkdir("downloads/", 0775, true)) {
    die('Error unable to create specified dir.');
}

preg_match_all($regex, $web_page, $images, PREG_SET_ORDER, 0);

$total_files = count ($images);

echo "[" . date("H:i:s") . "] Download started..\n";
echo "[" . date("H:i:s") . "] Downloading $total_files files...\n";
for ($i = 0; $i < $total_files; $i++)
{
    $save = "$url{$images[$i][1]}";
    $extension = "{$images[$i][2]}";

    echo "[" . date("H:i:s") . "] Downloading file($extension) n°".($i+1)."/$total_files\n";
    file_put_contents("downloads/$i$extension", file_get_contents($save));

}
echo "[" . date("H:i:s") . "] ..Download completed!\n";
