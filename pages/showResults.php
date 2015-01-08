<?php
session_start();
include 'baza.inc.php';
$stmnt = $mysqli->prepare("SELECT * FROM track_history WHERE userID=:uid AND clan=:clan AND trackNumHistory=:tid ORDER BY ID");
$stmnt->execute(array(
    "uid" => $_SESSION['UserID'],
    "clan" => $_POST['CLAN'],
    "tid" => $_POST['TID']
));
$stmnt1 = $mysqli->prepare("SELECT * FROM track_history WHERE userID=:uid AND clan=:clan AND trackNumHistory=:tid ORDER BY ID");
$stmnt1->execute(array(
    "uid" => $_SESSION['UserID'],
    "clan" => $_POST['CLAN'],
    "tid" => $_POST['TID']
));
echo"<table id='myTableEr' class='tablesorter' border='1'>";
$em = $stmnt1->fetch();
if($em['realName'] == "empty") {
    echo"<thead><th>RSN</th><th style='width:80px;'>Overall</th><th style='width:80px;'>Melee</th><th style='width:80px;'>HP</th><th style='width:80px;'>Ranged</th><th style='width:80px;'>Magic</th></thead>";
} else {
    echo"<thead><th>RSN</th><th style='width:80px;'>RealName</th><th style='width:80px;'>Overall</th><th style='width:80px;'>Melee</th><th style='width:80px;'>HP</th><th style='width:80px;'>Ranged</th><th style='width:80px;'>Magic</th></thead>";
}
echo "<tbody>";
while($yolo = $stmnt->fetch()){
    if ($yolo['realName'] == "empty"){
        echo "</tr><td>". $yolo['rsn'] ."</td><td>". $yolo['finalOverall'] ."</td><td>". $yolo['finalMelee'] ."</td><td>". $yolo['finalHP'] ."</td><td>". $yolo['finalRanged'] ."</td><td>". $yolo['finalMagic'] ."</td></tr>";
    } else {
        echo "</tr><td>". $yolo['rsn'] ."</td><td>". $yolo['realName'] ."</td><td>". $yolo['finalOverall'] ."</td><td>". $yolo['finalMelee'] ."</td><td>". $yolo['finalHP'] ."</td><td>". $yolo['finalRanged'] ."</td><td>". $yolo['finalMagic'] ."</td></tr>";
    }
}
echo"</tbody>";
echo"</table>"; 
?>