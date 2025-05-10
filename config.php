<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once "class/db.php";
$SiteURL = "http://localhost/portal/"; // Site URL'si
$SiteName = "Çelikerler Portal"; // Site Adı
// Duruma göre Database sınıfını seç
$db = new Database();
//$ayarlar = $db->islemYap('getir', '__ayarlar', null, "id = 1");

// Avatar oluşturma fonksiyonu
function createDefaultAvatar($ad, $soyad)
{
    $initials = mb_strtoupper(mb_substr($ad, 0, 1, 'UTF-8') . mb_substr($soyad, 0, 1, 'UTF-8'), 'UTF-8');
    $bgColor = '762824'; // # işareti olmadan

    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100">';
    $svg .= '<rect width="100%" height="100%" fill="#' . $bgColor . '"/>';
    $svg .= '<text x="50%" y="50%" font-size="40" fill="white" text-anchor="middle" dy=".3em">' . $initials . '</text>';
    $svg .= '</svg>';

    return 'data:image/svg+xml;base64,' . base64_encode($svg);
}