<?php
include(dirname(__FILE__) . '/func.php');
@header("Content-Type: text/html; charset=utf-8");
@unlink(dirname(__FILE__) . '/tmp/log.html');
ini_set("pcre.backtrack_limit", "99999999999");
comment('<html lang="ru"><head><meta charset="utf-8">');

$p = get_page('https://www.tuwroclaw.com/katalog-firm,kfi1,a.html');
$last_page = 0;
$pages = array();
$result[] = array(
    'id' => 'ID',
    'title' => 'TITLE',
    'address' => 'ADDRESS',
    'phone' => 'PHONE',
    'kom' => 'KOM.',
    'mail' => 'E-MAIL',
    'site' => 'SITE',
);

comment('Generate pagination list...');

preg_match('/<ul class="pagination">.*?<\/li>.*?<li>.*?<a[^>]*href="([^"]+)">.*?<!-- Next page link -->/isU', $p, $t);

$cur_last_page = preg_replace("/[^0-9]/", '', mb_substr($t[1], -11));

while ($last_page < $cur_last_page) {

    $last_page = $cur_last_page;

    $p = get_page('https://www.tuwroclaw.com/katalog-firm,kfi1,a-page' . $last_page . '.html');

    preg_match('/<ul class="pagination">.*?<\/li>.*?<li>.*?<a[^>]*href="([^"]+)">.*?<!-- Next page link -->/isU', $p,
        $t);
    $cur_last_page = preg_replace("/[^0-9]/", '', mb_substr($t[1], -11));
};


for ($i = 0; $i <= $last_page;) {
    $pages[$i] = 'https://www.tuwroclaw.com/katalog-firm,kfi1,a-page' . $i . '.html';
    $i++;
}

comment('Generate is complete');
comment('Generate data...');

$w = 0;
foreach ($pages as $page) {
    $p = get_page($page);
    preg_match_all('/<div[^>]*class="industry-unit">.*?<h3[^>]*>([^<>]+)<\/h3>\s*<p>(([^"]*"){2}).*?<\/a>/ims', $p,
        $content, PREG_OFFSET_CAPTURE);

    foreach ($content[0] as $key => $value) {

        preg_match_all('/<h3[^>]*class="industry-name"[^>]*>([^<>]+)<\/h3>/isU', $value[0], $name);
        preg_match_all('/<p>(.*?)<\/a>/isU', $value[0], $desc);

        $description = explode('<br/>', $desc[0][0]);

        $phones = strip_tags($description[2]);
        $phone = explode(',', $phones);

        $result[] = array(
            'id' => $w,
            'title' => strip_tags($name[0][0]),
            'address' => trim(strip_tags($description[0])) . trim($description[1]),
            'phone' => trim(preg_replace("/[^0-9]/", '', $phone[0])),
            'kom' => trim(preg_replace("/[^0-9]/", '', $phone[1])),
            'mail' => trim(strip_tags($description[3])),
            'site' => trim(strip_tags($description[4]))
        );
        $w++;
    }
}
comment('Generate is complete');
comment('Save in file...');
$fp = fopen(__DIR__ . '/result/tuwroclaw-' . date('G-i-d-m-Y') . '.csv', 'w');
foreach ($result as $fields) {
    fputcsv($fp, $fields);
}
fclose($fp);


comment('DONE');
?>