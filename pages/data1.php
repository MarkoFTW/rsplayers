<?php
include 'baza.inc.php';

$result = $mysqli->prepare("SELECT extract(day from WORLD_TIME) as dan, extract(hour from WORLD_TIME) as ura, ROUND(SUM(WORLD_POP)/4,0) as sum FROM `world_history` WHERE extract(minute from `WORLD_TIME`) IN(0,15,30,45) AND extract(day from `WORLD_TIME`) = extract(day from NOW()) AND extract(month from `WORLD_TIME`) = extract(month from NOW()) GROUP BY ura");
$result->execute();

while($row = $result->fetch()) {
  echo $row['ura'] . "\t" . $row['sum']. "\n";
}


?> 