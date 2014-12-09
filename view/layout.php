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
<?php if($_GET['page'] != 'view'){ ?>
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
                <li><a href="index.php?page=register" <?php if($_GET['page']=="register") { echo "class=\"active\""; } ?>>Registreren</a></li>
            <?php 
                }else{ 
            ?>
                <p class="navbar-right">Ingelogd als <?php echo $_SESSION['user']['username'];?> - <a href="index.php?page=logout" class="navbar-link">Uitloggen</a></p>
            <?php } ?>
            </ul>
        </nav>
<?php } ?>
	<?php echo $content; ?>

<script type="text/template" id="1-template">
    <article class="item picture" style="top: {{top}}; left: {{left}}" id="{{id}}">
        <div class="border picture"><img src="img/move.png" alt="move"></div>
        <section id="{{type}}">
            <img src="img/settingsblack.png" alt="settings">
            <header><h1>{{title}}</h1></header>
            <img src="{{content}}" alt="{{title}}">
            <p class="desc">{{description}}</p>
        </section>
    </article>
</script>

<script type="text/template" id="2-template">
    <article class="item video" style="top: {{top}}; left: {{left}}" id="{{id}}">
        <div class="border video"><img src="img/move.png" alt="move"></div>
        <section id="{{type}}">
            <img src="img/settingsblack.png" alt="settings">
            <header><h1>{{title}}</h1></header>
            <video src="{{content}}" controls></video>
            <p class="desc">{{description}}</p>
        </section>
    </article>
</script>

<script type="text/template" id="3-template">
    <article class="item text" style="top: {{top}}; left: {{left}}" id="{{id}}">
        <div class="border text"><img src="img/move.png" alt="move"></div>
        <section id="{{type}}">
            <img src="img/settingsblack.png" alt="settings">
            <header><h1>{{title}}</h1></header>
            <p>{{content}}</p>
            <p class="desc">{{description}}</p>
        </section>
    </article>
</script>

<script type="text/template" id="edit-1-template">
    <form action="index.php?page=view&amp;id=<?php echo $_GET['id'];?>" method="POST" name="action" value="update">
        <input type="text" name="title" value="{{title}}">
        <input type="file" name="image">
        <textarea name="desc">{{description}}</textarea>
        <input type="number" name="id" class="hidden" value="{{id}}">
        <input type="submit" name="action" value="Wijzig">
        <a href="delete">Verwijder</a>
    </form>
 </script>

 <script type="text/template" id="edit-2-template">
    <form action="index.php?page=view&amp;id=<?php echo $_GET['id'];?>" method="POST" name="action" value="update">
        <input type="text" name="title" value="{{title}}">
        <input type="file" name="image">
        <textarea name="desc">{{description}}</textarea>
        <input type="number" name="id" class="hidden" value="{{id}}">
        <input type="submit" name="action" value="Wijzig">
        <a href="delete">Verwijder</a>
    </form>
 </script>

<script type="text/template" id="edit-3-template">
    <form action="index.php?page=view&amp;id=<?php echo $_GET['id'];?>" method="POST" name="action" value="updateText">
        <input type="text" name="title" value="{{title}}">
        <textarea name="content">{{content}}</textarea>
        <textarea name="desc">{{description}}</textarea>
        <input type="number" name="id" class="hidden" value="{{id}}">
        <input type="submit" name="action" value="Wijzig">
        <a href="delete">Verwijder</a>
    </form>
 </script>

<script type="text/javascript" src="js/vendor/jquery.js"></script>
<script type="text/javascript" src="js/vendor/bean.min.js"></script>
<script type="text/javascript" src="js/vendor/handlebars.min.js"></script>
<script type="text/javascript" src="js/script.dist.js"></script>
</div>
</body>
</html>