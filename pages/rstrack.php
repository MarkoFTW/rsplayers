<div class="container text-center" id="rstrackpage">
<?php
if(isset($_SESSION['UserID'])) {
    ?>
    <button type="button" class="btn btn-danger" id="viewStats">View stats</button>
    <button type="button" class="btn btn-danger" id="startANDstop">Start&AMP;Stop trackers</button>
    <button type="button" class="btn btn-danger" id="manageClans">Manage clans</button><br/><br/>

    <div id="uploadsStuff">
    Upload memberlists by<br/>
    <button type="button" class="btn btn-warning" id="copypastelist">Copy and paste</button>
    <button type="button" class="btn btn-warning" id="uploadList">Upload by file</button><br/><br/>

    <?php

    if (isset($_POST['editClan'])) {
        if (trim($_POST['clantag']) == "" || trim($_POST['clanname']) == "" ){
            echo "<span class='confItems'>Empty fields.</span><br/>";
        } else if ($_GET['action'] == 'editnow') {
            $clantag = $_POST['clantag'];
            $clanname = $_POST['clanname'];
            $id = $_GET['id'];
            $stmnt = $mysqli->prepare("UPDATE clans SET clantag = :ctag, clanname = :cname WHERE ID = :cid");
            $stmnt->execute(array(
                "cid" => $id,
                "ctag" => $clantag,
                "cname" => $clanname
            ));
            echo "<span class='confItems'>Clan successfully updated.</span><br/>";
        }
    } elseif (isset($_POST['editClanName'])) {
        if (trim($_POST['clanTrackName']) == ""){
            echo "<span class='confItems'>Empty fields.</span><br/>";
        } else if ($_GET['action'] == 'editname') {
            $stmnt = $mysqli->prepare("UPDATE track_history SET trackName = :name WHERE clan = :c AND trackNumHistory = :r AND userID = :s");
            $stmnt->execute(array(
                "name" => $_POST['clanTrackName'],
                "c" => $_GET['c'],
                "r" => $_GET['r'],
                "s" => $_SESSION['UserID']
            ));
            echo "<span class='confItems'>Tracking name successfully updated.</span><br/>";
        } 
    } elseif(isset($_GET['action']) && $_GET['action'] == "editname" && isset($_GET['r']) && isset($_GET['c'])){
        $r = $_GET['r'];
        $c = $_GET['c'];
        $s = $_SESSION['UserID'];
        $stmnt = $mysqli->prepare("SELECT * FROM track_history WHERE trackNumHistory = :r AND clan = :c AND userID = :s GROUP BY trackNumHistory");
        $stmnt->execute(array(
            "r" => $r,
            "c" => $c,
            "s" => $s
        ));
        while($editTrack = $stmnt->fetch()){
            echo '<form name="obrazec" action="index.php?p=rstrack&amp;page=stats&amp;r=' . $_GET['r'] . '&amp;c=' . $_GET['c'] . '&amp;action=editname" method="POST">';
                echo '<div class="input-group">Track description:<br/><textarea class="besedilo form-control" name="clanTrackName">' . $editTrack['trackName']  . '</textarea></div><br/>';
                echo '<input type="submit" name="editClanName" class="btn btn-success" value="Save" />';
            echo '</form>';   
        }
    } else if (isset($_GET['id']) && $_GET['action'] == 'delnow') {
        $id = $_GET['id'];
        $ctag = $_GET['tag'];
        $stmnt = $mysqli->prepare("DELETE FROM clans WHERE ID = :cid");
        $stmnt->execute(array(
            "cid" => $id
        ));
        $stmnt2 = $mysqli->prepare("DELETE FROM memberlist WHERE clan = :ctag");
        $stmnt2->execute(array(
            "ctag" => $ctag
        ));
        echo "<span class='confItems'>Clan with ID: " . $_GET['id'] . " successfully removed.</span>";
    } else if (isset($_GET['id']) && $_GET['action'] == 'edit') {
        echo "<span class='confItems'>Selected clan with ID number: " . $_GET['id'] . ".</span><br/>";
        $id_clan = $_GET['id'];

        $stmnt = $mysqli->prepare("SELECT * FROM clans WHERE ID = :cid");
        $stmnt->execute(array(
            "cid" => $id_clan
        ));
        while($editClan = $stmnt->fetch()){
            echo '<form name="obrazec1" action="index.php?p=rstrack&amp;page=clans&amp;id=' . $id_clan . '&amp;action=editnow" method="POST">';
                echo '<div class="input-group">Clan tag:<br/><input class="naslovnovice form-control" type="text" name="clantag" value="' . $editClan['clantag'] .'" /></div><br/>';
                echo '<div class="input-group">Clan name:<br/><textarea class="besedilo form-control" name="clanname">' . $editClan['clanname']  . '</textarea></div><br/>';
                echo '<input type="submit" name="editClan" class="btn btn-success" value="Save" />';
            echo '</form>';
        }
    } else if (isset($_GET['view']) && $_GET['view'] == 'history') {      
        $stmnt = $mysqli->prepare("SELECT * FROM track_history WHERE userID=:uid AND clan=:clan AND trackNumHistory=:tid ORDER BY ID");
        $stmnt->execute(array(
            "uid" => $_SESSION['UserID'],
            "clan" => $_GET['ct'],
            "tid" => $_GET['tid']
        ));
        echo"<table id='myTable' class='tablesorter' border='1'>";
        echo"<thead><th>Clan</th><th>RSN</th><th>RealName</th><th>Overall</th><th>Melee</th><th>HP</th><th>Ranged</th><th>Magic</th></thead>";
        echo "<tbody>";
        while($yolo = $stmnt->fetch()){
            echo "</tr><td>". $yolo['clan'] ."</td><td>". $yolo['rsn'] ."</td><td>". $yolo['realName'] ."</td><td>". $yolo['finalOverall'] ."</td><td>". $yolo['finalMelee'] ."</td><td>". $yolo['finalHP'] ."</td><td>". $yolo['finalRanged'] ."</td><td>". $yolo['finalMagic'] ."</td></tr>";
        }
        echo"</tbody>";
        echo"</table>";        
    } else if (isset($_GET['sns'])){
        ?>
        
        <!--START AND STOP-->
        <div id="startStop">
            <form id="FormStart" name="buttons" action="index.php?" method="GET">
                <input type="hidden" name="p" value="rstrack">
                <input type="hidden" name="sns">
                <input type="hidden" name="tracker" value="start">
                <?php
                $getName = $mysqli->prepare("SELECT * FROM clans WHERE userID = :uid AND clanStatus = 0 AND ml = 1 ORDER BY ID");
                $getName->execute(array('uid' => $_SESSION['UserID']));
                echo "<div class='input-group'><select id='optVal' name='clan' class='form-control'>";
                echo "<option value='0'>Select</option>";
                $RC = $getName->rowCount();
                if ($RC != 0) {
                    while($nameClan = $getName->fetch()){
                       echo "<option value='" . $nameClan['clantag'] . "'>" . $nameClan['clanname'] . "</option>";
                    }
                } else {
                    echo "<option value='0'>Empty memberlists</option>";
                }
                echo "</select></div>";
                ?>
                <a class='btn btn-success' data-modal='#myModal2' data-href='startTrack.php'>Start track</a>
                <!--<input type="submit" class="btn btn-success" value="Start track">-->
            </form>
            <br/><br/>

        </div>
        <div id="activeTrackers">
            <?php 
            include 'class.php';
            $activeClans = new StatsTracker();
            $activeClans->setUserID1($_SESSION['UserID']);
            $activeClans->showActive();
            ?>
        </div>
        

        <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close close-reload" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only close-reload">Close</span></button>
                  <h4 class="modal-title">Starting tracker...</h4>
                </div>
                <div class="modal-body">
                  <p>Log&hellip;</p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-info close-reload" data-dismiss="modal">Close</button>
                </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div> <!-- /.modal-end -->
        
        <div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close close-reload" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only close-reload">Close</span></button>
                  <h4 class="modal-title">Stopping tracker...</h4>
                </div>
                <div class="modal-body">
                  <p>Log&hellip;</p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-info close-reload" data-dismiss="modal">Close</button>
                </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div> <!-- /.modal-end -->
        
        
        <?php
    } else if (isset($_GET['upload']) && $_GET['upload'] == "cp"){
    ?>
        
    <div id="upload1">
        <span>One member per line!</span>
        <form name="obrazec1" action="index.php?p=rstrack" method="POST">
            <div class="input-group"><textarea class="form-control" rows='30' cols='50' name='izvorna' id='podatki'>Example1&#13;&#10;Example2&#13;&#10;Example3</textarea></div><br/>
            <?php
            $getClan = $mysqli->prepare("SELECT * FROM clans WHERE userID = :uid ORDER BY ID");
            $getClan->execute(array('uid' => $_SESSION['UserID']));
            echo " <div class='input-group'><select class='form-control' name='clanListUpload'>";
            echo "<option value='0'>Select clan</option>";
            while($nameClan = $getClan->fetch()){
               echo "<option value='" . $nameClan['clantag'] . "'>" . $nameClan['clanname'] . "</option>";
            }
            echo "</select></div>";
            ?>
            <input type="checkbox" name="chkbox1" value="chkbox1"/> use real names (John.RSN)<br/>
            <input type="submit" class="btn btn-success" name="nalozi1" value="Confirm List"/>
        </form>
    </div>
    <?php
     
    } else if (isset($_GET['upload']) && $_GET['upload'] == "file"){
    ?>
        <div id="upload">
        <br/><span>One member per line!(<a href="http://i.imgur.com/I43atJq.png" target="_blank">Example</a>)</span>
        <form name="obrazec" action="index.php?p=rstrack" method="POST" enctype="multipart/form-data">
            <label for="uploadedfile">Select file:</label>
            <input type="file" name="uploadedfile" id="uploadedfile"/>
            <?php
            $getClan1 = $mysqli->prepare("SELECT * FROM clans WHERE userID = :uid ORDER BY ID");
            $getClan1->execute(array('uid' => $_SESSION['UserID']));
            echo " <div class='input-group'><select class='form-control' name='clanUpload'>";
            echo "<option value='0'>Select clan</option>";
            while($nameClan1 = $getClan1->fetch()){
               echo "<option value='" . $nameClan1['clantag'] . "'>" . $nameClan1['clanname'] . "</option>";
            }
            echo "</select></div>";
            ?>
            <br/><input type="checkbox" name="chkbox2" value="chkbox2"/> use real names (John.RSN)<br/>
            <input type="submit" class="btn btn-success" name="nalozi" value="Submit"/>
        </form>
    </div>
    
    <?php    
    } else if (isset($_GET['stats']) && isset($_GET['r']) && isset($_GET['c'])){
        if(isset($_GET['a']) && $_GET['a'] == "yes"){
            $r = $_GET['r'];
            $c = $_GET['c'];
            $remove = $mysqli->prepare("DELETE FROM track_history WHERE clan = :c AND trackNumHistory = :r AND userID = :s");
            $remove->execute(array(
                "c" => $c,
                "r" => $r,
                "s" => $_SESSION['UserID']
            ));
            echo "Tracking history removed!";
        } else {
            echo 'Are you sure?<br/>';
            echo "<a href='index.php?p=rstrack&stats&r=".$_GET['r']."&c=".$_GET['c']."&a=yes' class='btn btn-danger'>Yes</a>";
            echo "<a href='index.php?p=rstrack&stats' class='btn btn-danger'>No</a>";
        }
    } else if (isset($_GET['stats'])){
    ?>
        <div id="clanpages">
            <?php
            include_once 'class.php';
            $lol = new StatsTracker();
            $lol->showAllHistory($_SESSION['UserID']);
            
            ?>
            
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                      <h4 class="modal-title">Log</h4>
                    </div>
                    <div class="modal-body">
                      <p>&hellip;</p>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                    </div>
                  </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div> <!-- /.modal-end -->
            
        </div>
      <?php  
      
    } else if (isset($_GET['clan']) && $_GET['clan']=="manage") {
        ?>
        <form name="obrazec" action="index.php?p=rstrack&amp;add=userclan" method="POST">    
            <div class="input-group">Clan tag: <br/><input type="text" name="clantag" class="form-control" value="<?php if(isset($_POST['clantag'])) { echo htmlspecialchars($_POST['clantag']); } ?>"/></div><br/>
            <div class="input-group">Clan full name: <br/><input type="text" name="clanname" class="form-control" value="<?php if(isset($_POST['clanname'])) { echo htmlspecialchars($_POST['clanname']); } ?>"/></div><br/>
            <input type="submit" name="addClan" class="btn btn-success" value="Add clan" />
        </form><br/>
        <?php
        echo "List of your currently added clans:<br />";
        $delClan = $mysqli->prepare("SELECT * FROM clans WHERE userID=:uid ORDER BY ID");
        $delClan->execute(array(
            "uid" => $_SESSION['UserID']
        ));
        while($delNow = $delClan->fetch()){
            $getCount = $mysqli->prepare("SELECT count(*) as counting FROM memberlist WHERE userID=:userID AND clan=:clanTAG");
            $getCount->execute(array(
                "userID" => $_SESSION['UserID'],
                "clanTAG" => $delNow['clantag']
            ));
            while($count = $getCount->fetch()) {
                echo "<a class=\"x\" href=\"index.php?p=rstrack&amp;page=clans&amp;id=" . $delNow['ID'] . "&amp;tag=".$delNow['clantag']."&amp;action=delnow \"><img src='img/x.png' title='Delete' alt='Delete'/></a><a href=\"index.php?p=rstrack&amp;page=clans&amp;id=" . $delNow['ID'] . "&amp;action=edit \">" . $delNow['clanname'] . "</a> (".$count['counting']." members "
                        . "<a href='index.php?p=rstrack&amp;page=final&amp;clan=".$delNow['clantag']."'>View</a> - <a href='index.php?p=rstrack&amp;page=clear&amp;clan=".$delNow['clantag']."'>Clear</a>)<br/>";
            }
        }
    } else if (isset($_GET['page'])){
            if ($_GET['page']=="start"){
                $stmnt = $mysqli->prepare("SELECT ID, status, clan, rsn, startOverall, startMelee, startHP, startRanged, startMagic FROM memberlist WHERE userID=:uid ORDER BY ID");
                $stmnt->execute(array("uid" => $_SESSION['UserID']));
                echo"<table border='1'>";
                echo"<th>ID</th><th>Status</th><th>Clan</th><th>RSN</th><th>startOverall</th><th>startMelee</th><th>startHP</th><th>startRanged</th><th>startMagic</th>";

                while($yolo = $stmnt->fetch()){
                    echo "<tr><td>". $yolo['ID'] ."</td><td>". $yolo['status'] ."</td><td>". $yolo['clan'] ."</td><td>". $yolo['rsn'] ."</td><td>". $yolo['startOverall'] ."</td><td>". $yolo['startMelee'] ."</td><td>". $yolo['startHP'] ."</td><td>". $yolo['startRanged'] ."</td><td>". $yolo['startMagic'] ."</td></tr>";
                }
                echo"</table><br/><br/><br/>";
            } else if ($_GET['page']=="end"){
                $stmnt = $mysqli->prepare("SELECT ID, status, clan, rsn, endOverall, endMelee, endHP, endRanged, endMagic FROM memberlist WHERE userID=:uid ORDER BY ID");
                $stmnt->execute(array("uid" => $_SESSION['UserID']));
                echo"<table border='1'>";
                echo"<th>ID</th><th>Status</th><th>Clan</th><th>RSN</th><th>endOverall</th><th>endMelee</th><th>endHP</th><th>endRanged</th><th>endMagic</th>";
                while($yolo = $stmnt->fetch()){
                    echo "<tr><td>". $yolo['ID'] ."</td><td>". $yolo['status'] ."</td><td>". $yolo['clan'] ."</td><td>". $yolo['rsn'] ."</td><td>". $yolo['endOverall'] ."</td><td>". $yolo['endMelee'] ."</td><td>". $yolo['endHP'] ."</td><td>". $yolo['endRanged'] ."</td><td>". $yolo['endMagic'] ."</td></tr>";
                }
                echo"</table><br/><br/><br/>";
            } else if ($_GET['page']=="final" && isset($_GET['clan'])){
                $stmnt = $mysqli->prepare("SELECT ID, clan, rsn, realName FROM memberlist WHERE userID=:uid AND clan=:clan ORDER BY ID");
                $stmnt->execute(array(
                    "uid" => $_SESSION['UserID'],
                    "clan" => $_GET['clan']
                ));
                echo"<table id='myTable' class='tablesorter' border='1'>";
                echo"<thead><th>ID</th><th>Clan</th><th>RSN</th><th>RealName</th></thead>";
                echo "<tbody>";
                while($yolo = $stmnt->fetch()){
                    echo "<tr><td>". $yolo['ID'] ."</td><td>". $yolo['clan'] ."</td><td>". $yolo['rsn'] ."</td><td>". $yolo['realName'] ."</td></tr>";
                }
                echo "</tbody>";
                echo"</table><br/><br/><br/>";                
            } else if($_GET['page'] == "clear"){
                $up = $mysqli->prepare("UPDATE clans SET ml = :status WHERE userID=:uid AND clantag=:cname");
                $up->execute(array(
                    "status" => "0",
                    "uid" => $_SESSION['UserID'],
                    "cname" => $_GET['clan']
                ));
                $stmnt = $mysqli->prepare("DELETE FROM memberlist WHERE userID=:uid AND clan=:clan");
                $stmnt->execute(array(
                    "uid" => $_SESSION['UserID'],
                    "clan" => $_GET['clan']
                ));
                echo "<span class='confItems'>Successfully cleared ". $_GET['clan'] ." list.</span>";
            } else {
                //header("Location: index.php?p=rstrack");
            }
        }
    ?>

    </div>
    <?php
    echo "<br/>";
    if (isset($_POST['nalozi'])) {
        if(!empty($_FILES["uploadedfile"]["name"])) {
            $datoteka = $_FILES['uploadedfile']['name'];                        
            list($ime, $format) = explode(".", $datoteka);
            if(($format=="txt")){
                $file1 = $_FILES["uploadedfile"]["tmp_name"];
                $lines = file($file1);
                $num_lines = count(file($file1));
                echo "<span class='confItems'>Added ". $num_lines ." members.</span>";
                $up = $mysqli->prepare("UPDATE clans SET ml = :status WHERE userID=:uid AND clantag=:cname");
                $up->execute(array(
                    "status" => "1",
                    "uid" => $_SESSION['UserID'],
                    "cname" => $_POST['clanUpload']
                    ));
                foreach($lines as $line_num => $line){
                    if(isset($_POST['chkbox2'])){
                        $line2 = explode(".", $line);
                        //print_r($line2);
                        $insertMembers = $mysqli->prepare("INSERT INTO memberlist (status,rsn,realName,clan,userID) VALUES ('0',:rsn,:realName,:clan,:user)");
                        $insertMembers->execute(array(
                            "rsn" => $line2[1],
                            "realName" => $line2[0],
                            "user" => $_SESSION['UserID'],
                            "clan" => $_POST['clanUpload']
                        ));
                        echo "<br/>";
                    } else {
                        $insertMembers = $mysqli->prepare("INSERT INTO memberlist (status,rsn,realName,clan,userID) VALUES ('0',:rsn,:realName,:clan,:user)");
                        $insertMembers->execute(array(
                            "rsn" => $line,
                            "realName" => "empty",
                            "user" => $_SESSION['UserID'],
                            "clan" => $_POST['clanUpload']
                        ));
                        echo "<br/>";
                    }
                }
            } else {
                echo "<span class='confItems'>Allowed formats: .txt</span>";
            }
        } else { 
            echo "<span class='confItems'>No files selected.</span>"; 
        }
    } 

    if (isset($_POST['nalozi1'])) {
        if(!empty($_POST['izvorna'])){
            $text = htmlspecialchars(trim($_POST['izvorna']));
            $text1 = nl2br($text);
            $text2 = explode("<br />", $text1);
            $num_lines = count($text2);
            echo "<span class='confItems'>Added ". $num_lines ." members.</span>";
            $up = $mysqli->prepare("UPDATE clans SET ml = :status WHERE userID=:uid AND clantag=:cname");
            $up->execute(array(
                "status" => "1",
                "uid" => $_SESSION['UserID'],
                "cname" => $_POST['clanListUpload']
                ));
            foreach($text2 as $line_num => $line){
                if(isset($_POST['chkbox1'])){
                    $line = preg_replace( "/\r|\n/", "", $line );
                    $line2 = explode(".",$line);
                    if(!empty($line)){
                    $insertMembers = $mysqli->prepare("INSERT INTO memberlist (status,rsn,realName,clan,userID) VALUES ('0',:rsn,:realName,:clan,:user)");
                    $insertMembers->execute(array(
                        "rsn" => $line2[1],
                        "realName" => $line2[0],
                        "clan" => $_POST['clanListUpload'],
                        "user" => $_SESSION['UserID']
                        ));
                    }
                } else {
                    $line = preg_replace( "/\r|\n/", "", $line );
                    if(!empty($line)){
                    $insertMembers = $mysqli->prepare("INSERT INTO memberlist (status,rsn,realName,clan,userID) VALUES ('0',:rsn,:realName,:clan,:user)");
                    $insertMembers->execute(array(
                        "rsn" => $line,
                        "realName" => "empty",
                        "clan" => $_POST['clanListUpload'],
                        "user" => $_SESSION['UserID']
                        ));
                    }
                }
            }
        } else {
            echo "<span class='confItems'>Empty field.</span>";
        }
    }
    
    if (isset($_POST['addClan'])) {
        if (trim($_POST['clantag']) == "" || trim($_POST['clanname']) == ""){
            echo "Empty fields!";
        } else {
            $addClan = $mysqli->prepare("INSERT INTO clans (clantag, clanname, userID, clanStatus, clanDateTime, trackNum, ml) VALUES (:clantag, :clanname, :userid, :clanstatus, NOW(), :tracknm, :ml)");
            $addClan->execute(array(
                "clantag" => $_POST['clantag'],
                "clanname" => $_POST['clanname'],
                "userid" => $_SESSION['UserID'],
                "clanstatus" => "0",
                "tracknm" => "0",
                "ml" => "0"
            ));
            echo "<span class='confItems'>Clan added successfully!</span>";
        }
    }


    if (isset($_GET['tracker'])){ //unused
        if ($_GET['tracker'] == "start") {
            $uid = $_SESSION['UserID'];
            $cname = $_GET['clan'];
            echo "<span class='confItems'>Starting tracker...</span><br/>";
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
            echo "<br><br>";
         } else if ($_GET['tracker'] == "stop") {
            $uid = $_SESSION['UserID'];
            $cname = $_GET['clan'];
            echo "<span class='confItems'>Stopping tracker...</span><br/>";
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
            echo "<br><br>";
         } else {
            header("Location: index.php");
         }
    }
    
    if (isset($_POST['clansdd'])){
        if ($_POST['clansdd'] != "") {
            echo "<span class='confItems'>You have selected: " . $_POST['clansdd'] . "</span>";
            $stmnt = $mysqli->prepare("SELECT * FROM track_history WHERE userID=:uid AND clan=:clan ORDER BY ID");
            $stmnt->execute(array(
                "uid" => $_SESSION['UserID'],
                "clan" => $_POST['clansdd']
            ));
            echo"<table id='myTable' class='tablesorter' border='1'>";
            echo"<thead><th>Clan</th><th>RSN</th><th>RealName</th><th>Overall</th><th>Melee</th><th>HP</th><th>Ranged</th><th>Magic</th></thead>";
            echo "<tbody>";
            while($yolo = $stmnt->fetch()){
                echo "</tr><td>". $yolo['clan'] ."</td><td>". $yolo['rsn'] ."</td><td>". $yolo['realName'] ."</td><td>". $yolo['finalOverall'] ."</td><td>". $yolo['finalMelee'] ."</td><td>". $yolo['finalHP'] ."</td><td>". $yolo['finalRanged'] ."</td><td>". $yolo['finalMagic'] ."</td></tr>";
            }
            echo"</tbody>";
            echo"</table>";
        }
    }
?>

<?php
} else {
    echo "<br/><span class='confItems'>Please login to use this feature.</span>";
}
?>
</div>   