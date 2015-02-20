<?php

class user{
    private $UserID,$Username,$Email,$Password,$PasswordConfirm,$OldPassword,$Country,$IP;
    
    public function getUserID(){
        return $this->UserID;
    }
    public function setUserID($UserID){
        $this->UserID = $UserID;
    }
    
    public function getUsername(){
        return $this->Username;
    }
    public function setUsername($Username){
        $this->Username = $Username;
    }

    public function getEmail(){
        return $this->Email;
    }
    public function setEmail($Email){
        $this->Email = $Email;
    }

    public function getOldPassword(){
        return $this->OldPassword;
    }
    public function setOldPassword($OldPassword){
        $this->OldPassword = $OldPassword;
    }
    
    public function getPassword(){
        return $this->Password;
    }
    public function setPassword($Password){
        $this->Password = $Password;
    }
    
    public function getPasswordConfirm(){
        return $this->PasswordConfirm;
    }
    public function setPasswordConfirm($PasswordConfirm){
        $this->PasswordConfirm = $PasswordConfirm;
    }
    
    public function getCountry(){
        return $this->Country;
    }
    public function setCountry($Country){
        $this->Country = $Country;
    }
    
    public function getIP(){
        return $this->IP;
    }
    public function setIP($IP){
        $this->IP = $IP;
    }
    
    public function checkUsername(){
        include "baza.inc.php";
        $req = $mysqli->prepare("SELECT * FROM users WHERE username=:Username");
        $req->execute(array(
            'Username' => $this->getUsername(),
            ));
        if ($req->rowCount() >= 1) {
            return true; // does exist
        } else {
            return false;
        }
    }
    public function checkEmail(){
        include "baza.inc.php";
        $req = $mysqli->prepare("SELECT * FROM users WHERE email=:Email");
        $req->execute(array(
            'Email' => $this->getEmail(),
            ));
        if ($req->rowCount() >= 1) {
            return true; // does exist
        } else {
            return false;
        }
    }
    
    public function Register(){
        if($this->checkUsername() == false && $this->checkEmail() == false){
            include "baza.inc.php";
            $req = $mysqli->prepare("INSERT INTO users(username,email,password,access,registered,last_seen,birthday,gender,country,IP_ADDR) VALUES (:Username,:Email,:Password,:Access,NOW(),NOW(),NOW(),:G,:C,:IP)");
            $req->execute(array(
                'Username' => $this->getUsername(),
                'Email' => $this->getEmail(),
                'Password' => $this->getPassword(),
                'Access' => "100",
                'G' => "0",
                'C' => $this->FindCountry($this->getIP()),
                'IP' => $this->getIP()
                ));
            echo "true";
        } else {
            echo "false";
        }
    }
    
    public function LoginUser(){
        include "baza.inc.php";
        global $mysqli;
        $req = $mysqli->prepare("SELECT * FROM users WHERE email=:Email AND password=:Password");
        $req->execute(array(
            'Email' => $this->getEmail(),
            'Password' => $this->getPassword()
            ));

        if ($req->rowCount() == 0) {
            header("Location: ../index.php?error=1");
            return false;
        } else {
            while ($data = $req->fetch()) {
                $this->setUserID($data['ID']);
                $this->setUsername($data['username']);
                $this->setPassword($data['password']);
                $this->setEmail($data['email']);
                
                $u = $mysqli->prepare("UPDATE users SET last_seen = NOW() WHERE ID = :id");
                $u->execute(array(
                    'id' => $this->getUserID()
                    ));
                
                $_SESSION['UserID'] = $this->getUserID();
                $_SESSION['Username'] = $this->getUsername();
                $_SESSION['Email'] = $this->getEmail();
                return true;
            }
        }
    }
    public function LoginUserName(){
        include "baza.inc.php";
        global $mysqli;
        $req = $mysqli->prepare("SELECT * FROM users WHERE username=:Username AND password=:Password");
        $req->execute(array(
            'Username' => $this->getUsername(),
            'Password' => $this->getPassword()
            ));
        if ($req->rowcount() == 0) {
            header("Location: ../index.php?error=1");
            return false;
        } else {
            while ($data = $req->fetch()) {
                
                $this->setUserID($data['ID']);
                $this->setUsername($data['username']);
                $this->setPassword($data['password']);
                $this->setEmail($data['email']);
                
                $u = $mysqli->prepare("UPDATE users SET last_seen = NOW() WHERE ID = :id");
                $u->execute(array(
                    'id' => $this->getUserID()
                    ));
                
                $_SESSION['UserID'] = $this->getUserID();
                $_SESSION['Username'] = $this->getUsername();
                $_SESSION['Email'] = $this->getEmail();                
                return true;
            }
        }
    }
    
    public function ChangePass(){
        include 'baza.inc.php';
        $ChangePW = $mysqli->prepare("SELECT * FROM users WHERE ID = :UserID");
        $ChangePW->execute(array('UserID' => $this->getUserID()));
        $PWInfo = $ChangePW->fetch();
        if($this->getOldPassword() == $PWInfo['password']){
            $newpw = $mysqli->prepare("UPDATE users SET password = :NewPassword WHERE ID = :UserID");
            $newpw->execute(array(
                'UserID' => $this->getUserID(),
                'NewPassword' => $this->getPassword()
            ));
            header("Location: ../index.php?p=profile&a=settings&success=1");
        } else {
            header("Location: ../index.php?p=profile&a=settings&error=1");
        }
    }
    public function ChangeEmail(){
        include 'baza.inc.php';
        $ChangePW = $mysqli->prepare("SELECT * FROM users WHERE ID = :UserID");
        $ChangePW->execute(array('UserID' => $this->getUserID()));
        $PWInfo = $ChangePW->fetch();
        if($this->getOldPassword() == $PWInfo['password']){
            $newpw = $mysqli->prepare("UPDATE users SET email = :NewEmail WHERE ID = :UserID");
            $newpw->execute(array(
                'UserID' => $this->getUserID(),
                'NewEmail' => $this->getPassword()
            ));
            header("Location: ../index.php?p=profile&a=settings&success=1");
        } else {
            header("Location: ../index.php?p=profile&a=settings&error=1");
        }
    }
    
    public function FindCountry($ip){ //class
        $url = 'http://viewdns.info/whois/?domain='.$ip;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.001 (windows; U; NT4.0; en-US; rv:1.0) Gecko/25250101');
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $db = curl_exec($ch);
        //$info = curl_getinfo($ch);
        //print_r($info);
        //print_r($db);

        preg_match('/country:        ([a-zA-Z0-9 ]*)/',$db,$matchme);
        $this->setCountry($matchme[1]);
    }
}

class stats {
    private $World;
    
    public function getWorld(){
        return $this->World;
    }
    public function setWorld($World){
        $this->World = $World;
    }
    
    public function AveragePlayers(){
        include 'baza.inc.php';
        //global $mysqli;
        $stmt = $mysqli->prepare("SELECT avg(WORLD_POP) as average FROM world_history WHERE WORLD_ID = :wid");
        $stmt->execute(array('wid' => $this->getWorld()));
        while($test = $stmt->fetch()){
            echo "Average players for world with ID " . $this->getWorld() . ": ". $test['average'];
            $avgpop = $mysqli->prepare("UPDATE world_list SET AVG_POP = :avgpop WHERE ID = :worldid");
            $avgpop->execute(array(
                'avgpop' => $test['average'],
                'worldid' => $this->getWorld()
            ));
        }
    }
    
    public function getOnlinePlayers(){
        $url = 'http://oldschool.runescape.com/';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.001 (windows; U; NT4.0; en-US; rv:1.0) Gecko/25250101');
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $jagexdb = curl_exec($ch);
        $info = curl_getinfo($ch);
          
        preg_match("/There are currently ([0-9 ]*) people playing!/",$jagexdb,$matchme);
        echo "Number of people currently playing Oldschool RuneScape: " . $matchme[1];
        
    }
    
    public function AverageAll(){
        include 'baza.inc.php';
        $stmt = $mysqli->prepare("SELECT ROUND(AVG(WORLD_POP),0) as average FROM world_history");
        $stmt->execute();
        while($test = $stmt->fetch()){
            echo "<span>There is an average of ". $test['average'] ." players per world.</span>";
        }
    }
}

class StatsTracker {
    private $UserID1;
    
    public function getUserID1(){
        return $this->UserID1;
    }
    public function setUserID1($UserID1){
        $this->UserID1 = $UserID1;
    }
    public function grabStats($player, $status, $uid, $clanname) {
        $url = 'http://services.runescape.com/m=hiscore_oldschool/index_lite.ws?player=' . $player;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.001 (windows; U; NT4.0; en-US; rv:1.0) Gecko/25250101');
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $jagexdb = curl_exec($ch);
        //$info = curl_getinfo($ch);
        //print_r($info);
        //echo "<br />";
        curl_close($ch);
        if(strpos($jagexdb,'Page not found') != false) {
            echo "<span style='color:red';>" . $player ." NOT found!</span><br />";
        } else {
            $playerStats = preg_split('/[\s]+/', $jagexdb);

            $overall = $playerStats[0];
            $att = $playerStats[1];
            $def = $playerStats[2];
            $str = $playerStats[3];
            $hp = $playerStats[4];
            $ranged = $playerStats[5];
            $prayer = $playerStats[6];
            $magic = $playerStats[7];

            list($overallRank, $overallLvl, $overallXP) = explode(",", $overall);
            list($attRank, $attLvl, $attXP) = explode(",", $att);
            list($defRank, $defLvl, $defXP) = explode(",", $def);
            list($strRank, $strLvl, $strXP) = explode(",", $str);
            list($hpRank, $hpLvl, $hpXP) = explode(",", $hp);
            list($rangedRank, $rangedLvl, $rangedXP) = explode(",", $ranged);
            list($prayerRank, $prayerLvl, $prayerXP) = explode(",", $prayer);
            list($magicRank, $magicLvl, $magicXP) = explode(",", $magic);

            $combatXP = ($attXP+$strXP+$defXP);
            $noCombat = ($overallXP - $combatXP - $hpXP - $rangedXP - $magicXP);      

            $skills = array($noCombat, $combatXP, $hpXP, $rangedXP, $magicXP, $player, $uid, $clanname);


            if ($status == "start") {
                $this->insertStats("start", $skills);
            } else if ($status == "stop") {
                $this->insertStats("end", $skills);
            }
        }
    }
    public function findTrack($clan, $user){
        global $mysqli;
        $findTrack = $mysqli->prepare("SELECT * FROM clans WHERE userID = :uid AND clantag = :ctag");
        $findTrack->execute(array(
            "uid" => $user,
            "ctag" => $clan
        ));
        while($f = $findTrack->fetch()){
            return $f['trackNum'];
        }
    }
    public function findTrackDate($clan, $user){
        global $mysqli;
        $findTrack = $mysqli->prepare("SELECT * FROM clans WHERE userID = :uid AND clantag = :ctag");
        $findTrack->execute(array(
            "uid" => $user,
            "ctag" => $clan
        ));
        while($f = $findTrack->fetch()){
            return $f['clanDateTime'];
        }
    }
    public function insertStats($status, $skills) {
        global $mysqli;//if status = end do 1
        $stmnt = $mysqli->prepare("UPDATE memberlist SET " . $status . "Overall=:overall," . $status . "Melee=:melee, " . $status . "HP=:hp, " . $status . "Ranged=:ranged, " . $status . "Magic=:magic WHERE rsn=:rsn AND userID=:uid AND clan=:cname");
        $stmnt->execute(array(
            'overall' => $skills[0],
            'melee' => $skills[1],
            'hp' => $skills[2],
            'ranged' => $skills[3],
            'magic' => $skills[4],
            'rsn' => $skills[5],
            'uid' => $skills[6],
            'cname' => $skills[7]
        ));
        if ($stmnt->rowCount() == 0 || $stmnt->rowCount() == 1) {
            echo "<span style='color:#33CC33';>Username " . $skills[5] . " has been found.</span><br />";
        }
        if($status == "end"){
            $getHistory = $mysqli->prepare("SELECT * FROM memberlist WHERE rsn=:rsn AND userID=:uid AND clan=:cname");
            $getHistory->execute(array(
                'rsn' => $skills[5],
                'uid' => $skills[6],
                'cname' => $skills[7]
            ));
            while($h = $getHistory->fetch()){
                $n = $this->findTrack($h['clan'],$h['userID']);
                $d = $this->findTrackDate($h['clan'],$h['userID']);
                $history = $mysqli->prepare("INSERT INTO track_history (status,rsn,realName,clan,userID,finalOverall,finalMelee,finalHP,finalRanged,finalMagic,trackNumHistory,trackTime,trackName,trackDuration) VALUES (:s,:rsn,:realName,:clan,:user,:o,:m,:h,:r,:mg,:num,DATE_SUB(NOW(), INTERVAL 1 HOUR),:tname,:ttime)");
                $history->execute(array(
                    's' => $h['status'],
                    'rsn' => $h['rsn'],
                    'realName' => $h['realName'],
                    'clan' => $h['clan'],
                    'user' => $h['userID'],
                    'o' => $skills[0] - $h['startOverall'],
                    'm' => $skills[1] - $h['startMelee'],
                    'h' => $skills[2] - $h['startHP'],
                    'r' => $skills[3] - $h['startRanged'],
                    'mg' => $skills[4] - $h['startMagic'],
                    'num' => $n,
                    'tname' => "Click to edit",
                    'ttime' => $d
                ));               
            }
        }
    }
    public function showAllHistory($user){
        global $mysqli;
        $showAllHistory = $mysqli->prepare("SELECT * FROM track_history WHERE userID = :uid GROUP BY trackNumHistory, clan ORDER BY trackNumHistory, clan");
        $showAllHistory->execute(array(
            "uid" => $user
        ));
        if($showAllHistory->rowCount() >= 1) {
            echo "Tracking history";
            echo "<table id='myTable' class='tablesorter myTable2' border='1'>";
            echo "<thead><th>#</th><th>Track Name</th><th>Clan</th><th>View</th><th>Full Date</th><th>Tracker duration</td><th>R</td></thead>";
            echo "<tbody>";
            while($history = $showAllHistory->fetch()){
                $to = new DateTime($history['trackTime']);
                $from = new DateTime($history['trackDuration']);
                $interval = $to->diff($from);
                $timer = $interval->format('%a days %h hours %i minutes %S seconds');
                //$timer = $interval->format('%y years %m months %a days %h hours %i minutes %S seconds');
                
                //$timer = $from->diff($to);
                echo "</tr><td>".$history['trackNumHistory']."</td><td><a href='index.php?p=rstrack&amp;page=stats&amp;r=".$history['trackNumHistory']."&amp;c=".$history['clan']."&amp;action=editname'>". $history['trackName'] ."</a></td><td>". $history['clan'] ."</td><td><a class='btn btn-primary btn-xs' data-modal='#myModal' data-href='showResults.php'>View</a></td><td>". $history['trackTime']. "</td><td>".$timer."</td><td><a href='index.php?p=rstrack&stats&r=".$history['trackNumHistory']."&c=".$history['clan']."'><img src='http://www.qweas.com/icon/remove-duplicate-files-platinum.gif' alt='remove' width='20' height='20'></td></a></tr>";
            }
            echo "</tbody>";
            echo "</table><br/><br/><br/>";
        } else {
            echo "Tracking history is empty<br/><br/>";
        }
    }

    public function resetStart($clan){
        global $mysqli;
        $resetStats = $mysqli->prepare("UPDATE memberlist SET startOverall=");
        $resetStats->execute();
    }
    
    public function showActive() {
        global $mysqli;
        $showActive = $mysqli->prepare("SELECT * FROM clans WHERE userID = :uid AND clanStatus = :status");
        $showActive->execute(array(
            "uid" => $this->getUserID1(),
            "status" => "1"// 1 = active, 0 = inactive
            ));
        if($showActive->rowCount() >= 1) {
            echo "<br/>Currently running trackers:";
            echo "<br/><table id='myTable' class='tablesorter myTable1' border='1'>";
            echo "<thead><th>Clan tag</th><th>Clan name</th><th>Active since</th><th>Stop tracker</th><th>Started</th></thead>";
            echo "<tbody>";
            while($p = $showActive->fetch()){
                echo "</tr><td>". $p['clantag'] ."</td><td>". $p['clanname'] ."</td><td>". $p['clanDateTime'] ."</td><td><a class='btn btn-primary btn-xs' data-modal='#myModal1' data-href='stopTrack.php'>Stop</a></td></tr>";
            }
            echo "</tbody>";
            echo "</table><br/><br/><br/>";
        } else {
            echo "<br/>Currently running trackers: 0<br/><br/>";
        }
    }
}

class Profile {
    private $UserID2, $Email1;
    
    public function getUserID2(){
        return $this->UserID2;
    }
    public function setUserID2($UserID2){
        $this->UserID2 = $UserID2;
    }

    public function getEmail1(){
        return $this->Email1;
    }
    public function setEmail1($Email1){
        $this->Email1 = $Email1;
    }
    public function showActiveNum() {
        global $mysqli;
        $showActiveNum = $mysqli->prepare("SELECT count(*) as stevilo FROM clans WHERE userID = :uid AND clanStatus = :status");
        $showActiveNum->execute(array(
            "uid" => $this->getUserID2(),
            "status" => "1"// 1 = active, 0 = inactive
            ));
        $result = $showActiveNum->fetch();
        echo $result['stevilo'];
    }
    public function overGroup($num){
        if($num == 999){
            return "Owner";
        } elseif($num == 950){
            return "Administrator";
        } elseif($num == 888){
            return "Moderator";
        }else{
            return "Member";
        }
    }
    public function overGender($g){
        if($g == 1){
            return "Male";
        } elseif($g == 2){
            return "Female";
        } else {
            return "Alien";
        }
    }
    public function ProfilePic($picid){
        $ext = array("jpg", "png", "jpeg", "gif");
        $tmp = "";           
        foreach($ext as $a){
            $profileimg = "./img/users/".$picid."/profile.".$a;
            if(file_exists($profileimg)){
                $tmp = $a;
                break;
            }
        }
        $img = "./img/users/".$picid."/profile.".$tmp;
        if (file_exists($img)){
            echo "./img/users/".$picid."/profile.".$tmp;
        } else {
            echo "http://www.madisonfund.org/wp-content/uploads/2011/06/Empty-Face.jpg";
        }
    }
    public function showFullProfile(){
        global $mysqli;
        $showFull = $mysqli->prepare("SELECT * FROM users WHERE ID = :uid AND email = :email");
        $showFull->execute(array(
            "uid" => $this->getUserID2(),
            "email" => $this->getEmail1()
            ));
        while($p = $showFull->fetch()){

            $age = floor((time() - strtotime($p['birthday'])) / 31556926);

            $dt = explode(" ", $p['registered']);
            $memdt = explode("-", $dt[0]);
            $monthName = date("F", mktime(0, 0, 0, $memdt[1], 10));

            $o = explode(" ", $p['last_seen']);
            $on = explode("-", $o[0]);
            $onl = explode(":", $o[1]);
            if (strtotime($p['last_seen']) >= strtotime("today")){
                $last = "Today, " . $onl[0] .":".$onl[1];
            }elseif (strtotime($p['last_seen']) >= strtotime("yesterday")){
                $last = "Yesterday, " . $onl[0] .":".$onl[1];
            } else {
                $omonth = date("F", mktime(0, 0, 0, $on[1], 10));
                $last = $omonth." ".$on[2]." ".$on[0].", ".$onl[0].":".$onl[1];
            }

            $b = explode("-", $p['birthday']);
            $bmonthName = date("F", mktime(0, 0, 0, $b[1], 10));

            echo "<br/><b>Member since</b> " . $memdt[2]. " ".$monthName." ". $memdt[0];
            echo "<br/><b>Last active</b> " . $last;
            echo "<br/><br/><b>Group</b>: " . $this->overGroup($p['access']);
            echo "<br/><b>Country</b>: " . $p['country'];
            echo "<br/><b>Age</b>: " . $age;
            echo "<br/><b>Birthday</b>: " . $bmonthName. " ".$b[2].", " . $b[0];
            echo "<br/><b>Gender</b>: " . $this->overGender($p['gender']);  
        }
    }
}
?>