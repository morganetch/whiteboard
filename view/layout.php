<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Whiteboard</title>
	<link rel="stylesheet" href="css/screen.css">
</head>
<body>
<div class="container">
	<nav class="navbar" role="navigation">
        <ul>
            <li><a href="index.php" <?php if($_GET['page']=="home") { echo "class=\"active\""; } ?>>Home</a></li>
            <?php
                if(empty($_SESSION["user"])){
            ?>
            <form class="navbar-form navbar-right" role="login" action="index.php?page=login" method="post">
                <input type="text" name="username" placeholder="gebruikersnaam" class="form-control" />
                <input type="password" name="password" placeholder="wachtwoord" class="form-control" />
                <input type="submit" value="inloggen" class="submit" />
            </form>
                <li><a href="index.php?page=register" <?php if($_GET['page']=="register") { echo "class=\"active\""; } ?>>Register</a></li>
            <?php 
                }else{ 
            ?>
                <p class="navbar-right">Signed in as <?php echo $_SESSION['user']['username'];?> - <a href="index.php?page=logout" class="navbar-link">Logout</a></p>
            <?php } ?>
            </ul>
        </nav>

	<?php echo $content; ?>
<script type="text/javascript" src="js/vendor/jquery.js"></script>
<script type="text/javascript" src="js/vendor/bean.min.js"></script>
<script type="text/javascript" src="js/script.dist.js"></script>
</div>
</body>
</html>