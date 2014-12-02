<?php if(!empty($_SESSION["user"])){ ?>

<section id="content" class="overview">
    <article class="overview-content">
    	<header>
    		<h1>Overzicht whiteboards</h1>
    	</header>
    	<div class="knop">
    		<a href="index.php?page=add">Nieuwe whiteboard</a>
    	</div>
    	<div class="boards">
			<ol class="eigen-boards">
		    <?php foreach($ownBoards as $ownBoard) { ?>
				<li>
					<a href="index.php?page=view&id=<?php echo $ownBoard['id']; ?>"><?php echo $ownBoard['name']; ?></a>
				</li>
			<?php } ?>
			</ol>
			<ol class="invited-boards">
		    <?php foreach($invitedBoards as $invitedBoard) { ?>
				<li>
					<a href="index.php?page=view&id=<?php echo $invitedBoard['id']; ?>"><?php echo $invitedBoard['name']; ?></a>
				</li>
			<?php } ?>
			</ol>
		</div>
    </article>
</section>

<?php }else{
	$this->redirect("index.php");
} ?>