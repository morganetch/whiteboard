<section id="content" class="view">
	<header>
		<div class="settings">
			<a href="close"><img src="img/close.png" alt="close"></a>
			<a href="settings"><img src="img/settings.png" alt="settings"></a>
			<a href="save"><img src="img/save.png" alt="save"></a>
		</div>
		<div class="name">Whiteboard name</div>
		<div class="account">Ingelogd als <?php echo $_SESSION['user']['username'];?></div>
	</header>
	<section class="buttons">
		<a href="picture"><img src="img/picture.png" alt="picture"></a>
		<a href="video"><img src="img/video.png" alt="video"></a>
		<a href="text"><img src="img/balloon.png" alt="text"></a>
	</section>
	<section class="holder">
		<?php
		foreach ($items as $item) {
		 	if($item['type'] == 1){ ?>
		 		<article class="item picture" style="top: <?php echo $item['y']; ?>px; left: <?php echo $item['x']; ?>px" id="<?php echo $item['id']; ?>">
					<h1><?php echo $item['title']; ?></h1>
					<img src="uploads/<?php echo $item['content']; ?>" alt="cute cat">
					<p class="desc"><?php echo $item['description']; ?></p>
					<a href="edit">Edit</a>
					<a href="delete">Delete</a>
				</article>
		 	<?php 
		 	} else if($item['type'] == 2){ ?>
				<article class="item video" style="top: <?php echo $item['y']; ?>px; left: <?php echo $item['x']; ?>px" id="<?php echo $item['id']; ?>">
					<h1><?php echo $item['title']; ?></h1>
					<video src="uploads/<?php echo $item['content']; ?>" controls></video>
					<p class="desc"><?php echo $item['description']; ?></p>
					<a href="edit">Edit</a>
					<a href="delete">Delete</a>
				</article>
		 	<?php
		 	} else { ?>
				<article class="item text" style="top: <?php echo $item['y']; ?>px; left: <?php echo $item['x']; ?>px" id="<?php echo $item['id']; ?>">
					<h1><?php echo $item['title']; ?></h1>
					<p><?php echo $item['content']; ?></p>
					<p class="desc"><?php echo $item['description']; ?></p>
					<a href="edit">Edit</a>
					<a href="delete">Delete</a>
				</article>
		 	<?php
		 	}
		 } ?>
	</section>
    
</section>