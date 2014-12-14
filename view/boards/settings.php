<section id="content" class="settingspage">
    <article class="settingspage-content">
		<header><h2>Whiteboard toevoegen</h2></header>
        <form action="index.php?page=settings&id=<?php echo $_GET['id']; ?>" method="post" class="form-horizontal">
            <div class="form-group<?php if(!empty($errors['name'])) echo ' has-error'; ?>">
                <label for="whiteboardName">Naam:</label>
                <input type="text" name="name" id="whiteboardName" class="form-control reg" value="<?php if(!empty($_POST['name'])){ echo $_POST['name']; }else{ echo $board["name"]; } ?>" />
                <?php if(!empty($errors['name'])) echo '<span class="error-message">' . $errors['name'] . '</span>'; ?>
            </div>
            <h3>Ge&iuml;nviteerden</h3>
            <ul>
                <?php
                foreach ($invites as $invite) {
                    echo "<li><span>" . $invite['username'] . "</span></li>";
                } ?>
            </ul>
            <div class="form-group<?php if(!empty($errors['user_id'])) echo ' has-error'; ?>">
                <label for="inviteUser">Uitnodigen:</label>
                <input type="text" name="user_id" id="inviteUser" class="form-control reg" />
                <?php if(!empty($errors['user_id'])) echo '<span class="error-message">' . $errors['user_id'] . '</span>'; ?>
            </div>
            <div class="form-group">
                <div><input type="submit" value="Toevoegen" class="knop-reg"></div>
            </div>
        </form>
    </article>
</section>