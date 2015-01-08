<!DOCTYPE html>
<html>
    <head>
    <link rel="icon" href="favicon.ico" type="image/x-icon"> 
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <title>RuneTool Average Players</title>
    <?php
        include 'baza.inc.php';
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
    ?>
   </head>
   
   <body>
       
        <form name="obrazec" action="stats.php" method="POST">            
            World:<?php
                $stmt = $mysqli->prepare("SELECT ID, WORLD FROM world_list WHERE ACTIVE IS NULL");
                $stmt->execute();
                echo "<select name='id_world'>";
                echo "<option value='0'>Select world</option>";
                while($row = $stmt->fetch()){ 
                    echo "<option value='" . $row['ID'] . "'>" . $row['WORLD'] . "</option>";
                }
                echo "</select>";
            ?>
            <input type="submit" name="dodaj" value="Check"/>
        </form>
       
        <?php
            if(isset($_POST['id_world'])){
                include 'class.php';
                $stats = new stats();
                $stats->setWorld($_POST['id_world']);
                $stats->AveragePlayers();
            } else {
                echo "empty";
            }
        ?>
   </body>
</html>