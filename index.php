<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta charset="UTF-8">
        <link rel="icon" href="favicon.ico" type="image/x-icon"> 
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <title>RuneTool</title>
        <?php
            session_start();
            include "pages/baza.inc.php";
            if (isset($_COOKIE['userid'])){
                $_SESSION['UserID'] = $_COOKIE['userid'];
                $_SESSION['Username'] = $_COOKIE['username'];
                $_SESSION['Email'] = $_COOKIE['email'];
            }
        ?>
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/bootstrap-theme.css">
        <link rel="stylesheet" href="css/bootstrap-formhelpers.css">
        <link rel="stylesheet" href="css/style.css">

    </head>
    <body>
        <div id="hiddenOnline"></div>
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                
                <button class="navbar-toggle" data-toggle="collapse" data-target="#navHeaderCollapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navHeaderCollapse">
                    <ul class="nav navbar-nav navbar-left">
                        <li class="active"><a href="index.php">Home</a></li>
                        <li class="dropdown">
                            
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Statistics<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="index.php?p=worldstats">World stats</a></li>
                                <li><a href="#">? stats</a></li>
                            </ul>
                            
                        </li>
                        <li><a href="index.php?p=rstrack">Tracker</a></li>
                        <li><a href="index.php?p=about">About</a></li>
                        <li><a href="index.php?p=contact" data-toggle="modal">Contact</a></li>
                    </ul>
                    
                    <ul class="nav navbar-nav navbar-right">
                        <?php
                            if(isset($_SESSION['Username'])) {
                                echo '<li><a href="index.php?p=usercp" id="UserProfile">Logged in as '. $_SESSION['Username'] .'</a></li>';
                                echo '<li><a href="#Logout">Logout</a></li>';
                            } else {
                                echo '<li><a href="#Login" id="LoginUser" data-toggle="modal">Login</a></li>';
                                echo '<li><a href="#Register" id="RegisterUser" data-toggle="modal">Register</a></li>';
                            }
                        ?> 
                    </ul>
                    
                </div> 
            </div><!-- end container-->
        </div>
        
        
        
        
        
        <div class="container" id="ContMove">
            
            <?php
                if(isset($_GET['p']) && $_GET['p'] == "worldstats"){
                    include 'pages/class.php';
                    echo "<div class=\"onlinePlayers\">";
                    $s = new stats();
                    $s->AverageAll();
                    echo "</div>";
                    echo '<br/><div id="container3" style="width: 100%; height: 400px; margin: 0 auto"></div>';
                } elseif(isset($_GET['p']) && $_GET['p'] == "rstrack"){
                    include 'pages/rstrack.php';
                } elseif(isset($_GET['p']) && $_GET['p'] == "usercp" && isset($_SESSION['UserID'])){
                    include 'pages/profile.php';
                } elseif(isset($_GET['p']) && $_GET['p'] == "about"){
                    include 'pages/about.php'; 
                } elseif(isset($_GET['p']) && $_GET['p'] == "contact"){
                    include 'pages/contact.php';
                } elseif(isset($_GET['p']) && $_GET['p'] == "admin"){
                    //include 'pages/contact.php';
                    echo "admin";
                } else {
                    
                ?>
                <div class="onlinePlayers">
                    <?php
                        include 'pages/class.php';
                        $plrs = new stats();
                        $plrs->getOnlinePlayers();
                    ?>
                    <br/><br/>
                </div>
                <div id="wstats">
                    <div id="container1" style="width: 100%; height: 400px; margin: 0 auto"></div>
                    <span style="color:#999900; text-align: center;">(Updated every 15 minutes)</span>
                </div>
              <?php
                }
            ?>

            <div class="navbar navbar-default navbar-fixed-bottom">

                <div class="container">
                    <p class="navbar-text pull-left">&copy; 2014-2015 RuneTool.com. Data based on Oldschool RuneScape.</p>
                    <a href="http://RuneTool.com/rsplayers" class="navbar-btn btn-info btn pull-right">RuneTool</a>
                </div>

            </div>
            
        </div>
       
        <?php if(!isset($_SESSION['Username'])) {       ?>
                
        <div class="modal fade" id="Login" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">×</a>
                        <h4>Login</h4>
                        <div class="alert alert-success" id="succLog">
                            <strong><span class="glyphicon glyphicon-ok"></span> Success! Login complete.</strong>
                        </div>
                        <div class="alert alert-danger" id="errLog">
                            <span class="glyphicon glyphicon-remove"></span><strong> Error! Invalid username or password.</strong>
                        </div>
                    </div>
                    <div class="modal-body">
                        <form>
                        <div class="form-group">
                            <label for="inputUser">Username / Email</label>
                            <input type="text" class="form-control" id="inputUser" placeholder="Username / Email">
                        </div>
                        <div class="form-group">
                            <label for="inputPassword">Password</label>
                            <input type="password" class="form-control" id="inputPassword" placeholder="Password">
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" id="remember"> Remember me</label>
                        </div>
                        </form>                    
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-default" data-dismiss="modal">Close</a>
                        <button type="submit" class="btn btn-primary" id="loginnow">Login</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="Register" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">×</a>
                        <h4>Register</h4>
                        <div class="alert alert-success" id="succReg">
                            <strong><span class="glyphicon glyphicon-ok"></span> Success! Registration complete.</strong>
                        </div>
                        <div class="alert alert-danger" id="errRegUE">
                            <span class="glyphicon glyphicon-remove"></span><strong> Error! Username or email already in use.</strong>
                        </div>
                        <div class="alert alert-danger" id="errRegPW">
                            <span class="glyphicon glyphicon-remove"></span><strong> Error! Passwords do not match.</strong>
                        </div>
                    </div>
                    <div class="modal-body">
                        <form role="form">
                        <div class="form-group">
                            <label for="inputUsername">Username</label>
                            <input type="text" class="form-control" id="inputUsername" placeholder="John">
                        </div>
                        <div class="form-group">
                            <label for="inputEmail1">Email</label>
                            <input type="email" class="form-control" id="inputEmail1" placeholder="john.smith@email.com">
                        </div>
                        <div class="form-group">
                            <label for="inputPassword1">Password</label>
                            <input type="password" class="form-control" id="inputPassword1" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <label for="inputPassword2">Repeat password</label>
                            <input type="password" class="form-control" id="inputPassword2" placeholder="Retype password">
                        </div>
                        </form>                    
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-default" data-dismiss="modal">Close</a>
                        <button type="submit" class="btn btn-primary" id="regnow">Register</button>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>

        <script type="text/javascript" src="js/jquery211.js"></script>
        <?php
        //if($_GET['p'] == "worldstats" || strpos($_SERVER['SCRIPT_NAME'], 'index.php') !== false) {
            echo '<script type="text/javascript" src="js/highcharts.js" ></script>';
            echo '<script type="text/javascript" src="js/themes/gray.js"></script>';
        //}
        ?>
        <script type="text/javascript" src="js/jlivetime.js"></script>
        <script type="text/javascript" src="js/functions.js"></script>
        <script type="text/javascript" src="js/bootstrap.js"></script>
        <script type="text/javascript" src="js/bootstrap-formhelpers.js"></script>
        <script type="text/javascript" src="js/jquery.leanModal.min.js"></script>
        <script src="js/jquery.tablesorter.js" type="text/javascript"></script>
    </body>
</html>
