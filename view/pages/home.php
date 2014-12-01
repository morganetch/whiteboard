<section id="content" class="home">
    <article class="home-content">
    	<header>
    		<h1>Welkom</h1>
    	</header>
    	<?php if(empty($_SESSION["user"])){ ?>
    	<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi semper nisl vitae libero viverra, ut eleifend tellus facilisis. Pellentesque at dignissim lorem. Phasellus hendrerit vitae lectus vel feugiat.</p>
    	<div class="knop">
    		<a href="index.php?page=register">Registreren</a>
    	</div>
    	<?php }else{ ?>
    		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi semper nisl vitae libero viverra, ut eleifend tellus facilisis. Pellentesque at dignissim lorem. Phasellus hendrerit vitae lectus vel feugiat.</p>
    	<div class="knop">
    		<a href="index.php?page=overview">Start</a>
    	</div>
    	<?php } ?>
    </article>
</section>