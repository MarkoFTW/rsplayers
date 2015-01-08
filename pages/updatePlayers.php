<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="icon" href="favicon.ico" type="image/x-icon"> 
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <title>Update players stats</title>
        <?php
            include "baza.inc.php";
            //include 'class.php';
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        ?>
        <script src="http://code.jquery.com/jquery-2.1.1.js"></script>
        <link href="./style/style.css" type="text/css" rel="stylesheet"/>
        <script type="text/javascript">
        $(document).ready(function() {
            //test
        };
        </script>
    </head>
    <body>
        <?php
        
        $url = 'http://oldschool.runescape.com/slu.ws?order=WMLPA';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.001 (windows; U; NT4.0; en-US; rv:1.0) Gecko/25250101');
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $jagexdb = curl_exec($ch);
        $info = curl_getinfo($ch);
        //print_r($info);
        //print_r($jagexdb);
        
        $stmt = $mysqli->prepare("SELECT * FROM world_list WHERE ACTIVE IS NULL");
        $stmt->execute();
    
        while($dataList = $stmt->fetch()){ 
           
            preg_match('/' . $dataList['WORLD'] . ',(true|false),(0|1|2),\"oldschool([a-zA-Z0-9 ]*)"\,([0-9 ]*),\"([a-zA-Z0-9 ]*)\","' . $dataList['LOCATION'] . '"/',$jagexdb,$matchme);
            echo "Players in world " . $dataList['WORLD'] . "(". $matchme[5] ."): " . $matchme[4] ." ";
            
            if($dataList['NEWPLAYERS'] == $matchme[4]){
                $newStatus = 0;
                echo "(Change: " . $newStatus . ")";
            } else {
                $history = $mysqli->prepare("INSERT INTO world_history (WORLD_ID, WORLD_POP, WORLD_TIME) VALUES (:wid, :wpop, NOW())");
                $history->execute(array(
                    'wid' => $dataList['ID'],
                    'wpop' => $matchme[4]
                ));
                $stmnt = $mysqli->prepare("UPDATE `world_list` SET `OLDPLAYERS` = :oldplayers, `NEWPLAYERS` = :players WHERE `WORLD` = :world");
                $stmnt->execute(array(
                    'oldplayers' => $dataList['NEWPLAYERS'],
                    'players' => $matchme[4],
                    'world' => $dataList['WORLD']
                ));
                $newStatus = $matchme[4] - $dataList['NEWPLAYERS'];
                if ($newStatus < 0) {
                    echo "<span class='negative'>(Change: " . $newStatus . ")</span>";
                } else {
                    echo "<span class='positive'>(Change: " . $newStatus . ")</span>";
                }
            }
            echo "<br/>";
        }
        
        curl_close($ch);
        if(strpos($jagexdb,'Page not found') != false) {
            echo "Page was not found!<br/>";
        } else {
            
            if(strpos($jagexdb,'Old School 2') != false) {
            
                $test = preg_split('/[\s]+/', $jagexdb);
                
            }
        }
        ?>
    </body>
</html>
