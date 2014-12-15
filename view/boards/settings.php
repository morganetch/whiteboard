<section id="content" class="settingspage">
    <article class="settingspage-content">

		<header><h2>Whiteboard wijzigen</h2></header>
        <form action="index.php?page=settings&id=<?php echo $_GET['id']; ?>" method="post" class="form-horizontal">
            <div class="form-group<?php if(!empty($errors['name'])) echo ' has-error'; ?>">
                <label for="whiteboardName">Naam:</label>
                <input type="text" name="name" id="whiteboardName" class="form-control reg" value="<?php if(!empty($_POST['name'])){ echo $_POST['name']; }else{ echo $board["name"]; } ?>" />
                <?php if(!empty($errors['name'])) echo '<span class="error-message">' . $errors['name'] . '</span>'; ?>
            </div>
            <div class="form-group">
                <div><input type="submit" name="action" value="Wijzigen" class="knop-reg"></div>
            </div>
        </form>

            <h3>Gebruikers</h3>
            <ul>
                <?php
                if($invites){
                    foreach ($invites as $invite) {
                        echo "<li><span>" . $invite['username'] . "</span></li>";
                    }
                } else {
                    echo "<p>U bent de enige gebruiker</p>";
                }
                ?>
            </ul>
        <form action="index.php?page=settings&id=<?php echo $_GET['id']; ?>" class="search" method="post">
            <div class="form-group<?php if(!empty($errors['user_id'])) echo ' has-error'; ?>">
                <label for="inviteUser">Uitnodigen:</label>
                <input type="search" class="form-control reg" value="<?php if(!empty($_POST['name'])){ echo $_POST['name']; } ?>" autocomplete="off" placeholder="Typ een username in"/>
                <?php if(!empty($errors['user_id'])) echo '<span class="error-message">' . $errors['user_id'] . '</span>'; ?>
            </div>

            <div class="result">
                <?php
                if(isset($users)){
                    if(empty($users)) {
                        echo '<p>Geen resulaten</p>';
                    } else {
                        echo '<ul>';
                        foreach($users as $user) { ?>
                            <li>
                                <input type='checkbox' name="user-<?php echo $user['id']; ?>" value='<?php echo $user["id"]; ?>'> <?php echo $user['username']; ?>    
                            </li>
                        <?php }
                        echo '</ul>';
                    }
                    echo '<input type="submit" name="action" value="Toevoegen" class="knop-reg">';
                }
                ?>

            </div>
            
        </form>

        <a href="index.php?page=view&amp;id=<?php echo $_GET['id']; ?>">Terug naar board</a>
        
    </article>
</section>