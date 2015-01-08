<div class="row">
    <div class="col-sm-4 col-md-3 sidebar" id="sidebar1">
        <div class="list-group">
            <a href="index.php?p=usercp" class="list-group-item active">
                UserCP
            </a>
            <a href="index.php?p=usercp&a=chat" class="list-group-item">
                <i class="fa fa-comment-o"></i> Chat
            </a>
            <a href="index.php?p=usercp&a=search" class="list-group-item">
                <i class="fa fa-search"></i> Search
            </a>
            <!--<a href="index.php?p=profile&a=settings" class="list-group-item">
                <i class="fa fa-user"></i> Settings
            </a>-->
            <a href="index.php?p=usercp&a=files" class="list-group-item">
                <i class="fa fa-folder-open-o"></i> Files
            </a>
            <a href="index.php?p=usercp&a=messages" class="list-group-item">
                <i class="fa fa-envelope"></i> Messages <span class="badge">0</span>
            </a>
            <a href="index.php?p=rstrack&sns" class="list-group-item">
                <i class="fa fa-bar-chart-o"></i> Running trackers <span class="badge"><?php include 'pages/class.php'; $a = new Profile(); $a->setUserID2($_SESSION['UserID']); $a->showActiveNum(); ?></span>
            </a>
            <a href="#" data-toggle="collapse" data-target="#sub1" class="list-group-item"><i class="fa fa-user"></i> Settings <b class="caret"></b></a>
            <ul class="nav collapse" id="sub1">
                  <li><a href="index.php?p=usercp&a=email" class="list-group-item"><i>Email & Password</i></a></li>
                  <li><a href="index.php?p=usercp&a=settings" class="list-group-item"><i>Edit profile</i></a></li>
                  <li><a href="#3" class="list-group-item"><i>3</i></a></li>
            </ul>
        </div>
    </div>
    <div class="col-md-8" id="profSettings">
        <?php
        if(isset($_GET['a']) && $_GET['a'] == "chat"){
            echo "chat";
        }elseif(isset($_GET['a']) && $_GET['a'] == "search"){
            echo "search";
        }elseif(isset($_GET['a']) && $_GET['a'] == "messages"){
            echo "msg";
        }elseif(isset($_GET['a']) && $_GET['a'] == "files"){
            echo "files";
        }elseif(isset($_GET['a']) && $_GET['a'] == "settings"){
            ?>
        <div class="row">      
            <div class="col-sm-6 col-sm-offset-0">
                <form method="post" id="emailForm" action="pages/ResetEmail.php">
                    <input type="text" class="input-sm form-control" name="country" id="country" placeholder="Country" autocomplete="off">
                    <input type="submit" class="col-xs-12 btn btn-primary btn-load btn-sm" data-loading-text="Changing email..." value="Change email">
                </form>
            </div><!--/col-sm-6-->
        </div><!--/row-->
        <?php
        }elseif(isset($_GET['a']) && $_GET['a'] == "email"){
         ?>
        <h3 style="color:white;"><b>Email & Password</b></h3>
        <h4>Email Address</h4>
        Current email address: <?php echo $_SESSION['Email'] ?><br/><br/>
        <div class="row">      
            <div class="col-sm-6 col-sm-offset-0">
                <form method="post" id="emailForm" action="pages/ResetEmail.php">
                    <input type="password" class="input-sm form-control" name="passwordCurrE" id="passwordCurrE" placeholder="Current Password" autocomplete="off"><br/>
                    <input type="text" class="input-sm form-control" name="email1" id="email1" placeholder="New email" autocomplete="off">
                    <input type="text" class="input-sm form-control" name="email2" id="email2" placeholder="Confirm new email" autocomplete="off">
                    <input type="submit" class="col-xs-12 btn btn-primary btn-load btn-sm" data-loading-text="Changing email..." value="Change email">
                </form>
            </div><!--/col-sm-6-->
        </div><!--/row-->
        <h4><b>Password</b></h4>
        We will attempt to update your current session after your successful password change. If, however, you do experience difficulties, please try signing out and signing back in before contacting a staff member to help resolve the problem.<br/>
        Password validation is not required, but is recommended to use.<br/><br/>
        <div class="row">      
            <div class="col-sm-6 col-sm-offset-0">
                <form method="post" id="passwordForm" action="pages/ResetPass.php">
                    <input type="password" class="input-sm form-control" name="passwordCurr" id="passwordCurr" placeholder="Current Password" autocomplete="off"><br/>
                    <input type="password" class="input-sm form-control" name="password1" id="password1" placeholder="New Password" autocomplete="off">
                    <div class="row">
                    <div class="col-sm-6">
                        <span id="8char" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> 8 characters long<br>
                        <span id="ucase" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> One uppercase letter
                    </div>
                    <div class="col-sm-6">
                        <span id="lcase" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> One lowercase letter<br>
                        <span id="num" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> One number
                    </div>
                    </div>
                    <input type="password" class="input-sm form-control" name="password2" id="password2" placeholder="Confirm new password" autocomplete="off">
                    <div class="row">
                    <div class="col-sm-12">
                        <span id="pwmatch" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> Passwords match
                    </div>
                    </div>
                    <input type="submit" class="col-xs-12 btn btn-primary btn-load btn-sm" data-loading-text="Changing password..." value="Change password">
                </form>
            </div><!--/col-sm-6-->
        </div><!--/row-->
        
        <?php
        } else {
        ?>
        <div id='profilepic'><img alt='profile' height='150' width='150' src="<?php $pic = new Profile(); $pic->ProfilePic($_SESSION['UserID']);?>"/></div>
            <?php 
            echo "<h1 style='color:white;'>" . $_SESSION['Username'] . "</h1>";
            $p = new Profile();
            $p->setEmail1($_SESSION['Email']);
            $p->setUserID2($_SESSION['UserID']);
            $p->showFullProfile();
        }
        ?>
    </div><!--sidebar-->
</div><!--/row-->