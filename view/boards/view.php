<section id="content" class="view">
	<header>
		<div class="settings">
			<a href="index.php?page=overview"><img src="img/close.png" alt="close"></a>
			<a href="index.php?page=settings&id=<?php echo $_GET['id']; ?>"><img src="img/settings.png" alt="settings"></a>
			<a href="save"><img src="img/save.png" alt="save"></a>
		</div>
		<div class="name"><?php echo $board['name']; ?></div>
		<div class="account">Ingelogd als <?php echo $_SESSION['user']['username'];?></div>
	</header>
	<section class="buttons">
		<form action="index.php?page=view&amp;id=<?php echo $_GET['id']; ?>" method="post" enctype="multipart/form-data">
			<label for="image-upload">
				<input type="file" id="image-upload" name="image" class="hidden" accept="image/x-png, image/gif, image/jpeg">	
			</label>
			
			<input type="submit" name="action" value="image" class="hidden">
		</form>
		<form action="index.php?page=view&amp;id=<?php echo $_GET['id']; ?>" method="post" enctype="multipart/form-data">
			<label for="video-upload">
				<input type="file" id="video-upload" name="video" class="hidden" accept="video/*">	
			</label>
			
			<input type="submit" name="action" value="video" class="hidden">
		</form>
		<form action="index.php?page=view&amp;id=<?php echo $_GET['id']; ?>" method="post">
			<label for="text-upload">
				<input type="text" id="text-upload" name="text" class="hidden">	
			</label>
			
			<input type="submit" name="action" value="text" class="hidden">
		</form>
	</section>
	<section class="holder">
		<?php
		foreach ($items as $item) {
		 	if($item['type'] == 1){ ?>
		 		<article class="item picture" style="top: <?php echo $item['y']; ?>px; left: <?php echo $item['x']; ?>px; z-index: <?php echo $item['z']; ?>;" id="<?php echo $item['id']; ?>">
		 			<div class="border picture"><img src="img/move.png" alt="move"></div>
					<section id="<?php echo $item['type']; ?>">
						<img src="img/settingsblack.png" alt="settings">
						<header><h1><?php echo $item['title']; ?></h1></header>
						<img src="uploads/<?php echo $item['content']; ?>" alt="cute cat">
						<p class="desc"><?php echo $item['description']; ?></p>
					</section>
				</article>
		 	<?php 
		 	} else if($item['type'] == 2){ ?>
				<article class="item video" style="top: <?php echo $item['y']; ?>px; left: <?php echo $item['x']; ?>px; z-index: <?php echo $item['z']; ?>;" id="<?php echo $item['id']; ?>">
					<div class="border video"><img src="img/move.png" alt="move"></div>
					<section id="<?php echo $item['type']; ?>">
						<img src="img/settingsblack.png" alt="settings">
						<header><h1><?php echo $item['title']; ?></h1></header>
						<video src="uploads/<?php echo $item['content']; ?>" controls></video>
						<p class="desc"><?php echo $item['description']; ?></p>
					</section>
				</article>
		 	<?php
		 	} else { ?>
				<article class="item text" style="top: <?php echo $item['y']; ?>px; left: <?php echo $item['x']; ?>px; z-index: <?php echo $item['z']; ?>;" id="<?php echo $item['id']; ?>">
					<div class="border text"><img src="img/move.png" alt="move"></div>
					<section id="<?php echo $item['type']; ?>">
						<img src="img/settingsblack.png" alt="settings">
						<header><h1><?php echo $item['title']; ?></h1></header>
						<p><?php echo $item['content']; ?></p>
						<p class="desc"><?php echo $item['description']; ?></p>
					</section>
				</article>
		 	<?php
		 	}
		 } ?>

	</section>
    
</section>