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
$stopTimer = $mysqli->prepare("UPDATE clans SET clanStatus = :status WHERE userID=:uid AND clantag=:cname");
$stopTimer->execute(array(
    "status" => "0",
    'uid' => $uid,
    'cname' => $cname
));
while ($row = $sqlrsn->fetch()){
    $getstats = new StatsTracker();
    $getstats->grabStats("{$row["rsn"]}","stop","{$uid}","{$cname}");                        
}