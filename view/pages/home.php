<section id="content" class="home">
    <article class="home-content">
    	<header>
    		<h1>Welkom</h1>
    	</header>
    	<?php if(empty($_SESSION["user"])){ ?>
    	<p>Maak zelf een online whiteboard met jouw vrienden.
            Wil jij ook gebruik maken van onze online whiteboards?
            Registreer je nu, log je nadien in en ga aan de slag!</p>
    	<a class="knop" href="index.php?page=register">Registreren</a>
    	<?php }else{ ?>
    		<p>Nu je zelf een account hebt aangemaakt kan je jouw eigen whiteboards aanmaken en vrienden toevoegen. 
            Jullie kunnen er samen aan werken. Je kan items toevoegen, 
            wijzigen en verwijderen.</p>
    	<a class="knop start" href="index.php?page=overview">Start</a>
    	<?php } ?>
    </article>
</section>