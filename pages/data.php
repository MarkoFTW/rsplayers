<?php
include 'baza.inc.php';

$result = $mysqli->prepare("SELECT extract(hour from WORLD_TIME) as test, ROUND(AVG(WORLD_POP),0) as average FROM `world_history` GROUP BY test");
$result->execute();

while($row = $result->fetch()) {
  echo $row['test'] . "\t" . $row['average']. "\n";
}


?> 