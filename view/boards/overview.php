<?php if(!empty($_SESSION["user"])){ ?>

<section id="content" class="overview">
    <article class="overview-content">
    	<header>
    		<h1>Overzicht whiteboards</h1>
    	</header>
    	<div class="knop">
    		<a href="index.php?page=add">Nieuwe whiteboard</a>
    	</div>
    </article>
</section>

<?php }else{
	$this->redirect("index.php");
} ?>