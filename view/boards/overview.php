<section id="content" class="overview">
    <article class="overview-content">
    	<header>
    		<h1>Overzicht whiteboards</h1>
    	</header>

		<a href="index.php?page=add" class="knop">Nieuw whiteboard</a>

    	<div class="boards">
    		<div class="eigen-boards">
    			<h2>Eigen boards</h2>
    			<?php if(!empty($ownBoards)){ ?>
	    			<ol>
				    <?php foreach($ownBoards as $ownBoard) { ?>
						<li>
							<a href="index.php?page=view&id=<?php echo $ownBoard['id']; ?>"><?php echo $ownBoard['name']; ?></a>
						</li>
					<?php } 
					echo "</ol>";
				} else {
					echo "<p>Nog geen boards</p>";
				}
					?>
					</ol>	
    		</div>

    		<div class="invited-boards">
    			<h2>Ge&iuml;nviteerde boards</h2>
    			<?php if(!empty($invitedBoards)){ ?>
	    			<ol>
				    <?php foreach($invitedBoards as $invitedBoard) { ?>
						<li>
							<a href="index.php?page=view&id=<?php echo $invitedBoard['board_id']; ?>"><?php echo $invitedBoard['name']; ?></a>
						</li>
					<?php }
					echo "</ol>";
				} else {
					echo "<p>Nog geen boards</p>";
				}
				?>
    		</div>
		</div>
    </article>
</section>