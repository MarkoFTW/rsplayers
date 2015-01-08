<?php
session_start();
include_once 'baza.inc.php';
include_once 'class.php';
$uid = $_SESSION['UserID'];
$cname = $_POST['clan'];
$sqlrsn = $mysqli->prepare("SELECT rsn FROM memberlist WHERE userID=:uid AND clan=:cname ORDER BY ID");
$sqlrsn->execute(array(
    'uid' => $uid,
    'cname' => $cname
));
$startTimer = $mysqli->prepare("UPDATE clans SET clanStatus = :status, clanDateTime = DATE_SUB(NOW(), INTERVAL 1 HOUR), trackNum = trackNum + 1 WHERE userID=:uid AND clantag=:cname");
$startTimer->execute(array(
    "status" => "1",
    'uid' => $uid,
    'cname' => $cname
));
while ($row = $sqlrsn->fetch()){
    $getstats = new StatsTracker();
    $getstats->grabStats("{$row["rsn"]}","start","{$uid}","{$cname}");                       
}