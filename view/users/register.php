<section id="content" class="register">
    <article class="register-content">
		<header><h2>Register</h2></header>
            <form action="index.php?page=register" method="post" class="form-horizontal">
                <div class="form-group<?php if(!empty($errors['username'])) echo ' has-error'; ?>">
                    <label class="col-sm-2 control-label" for="registerUsername">Gebruikersnaam:</label>
                    <input type="username" name="username" id="registerUsername" class="form-control reg" value="<?php if(!empty($_POST['username'])) echo $_POST['username'];?>" />
                    <?php if(!empty($errors['username'])) echo '<span class="error-message">' . $errors['username'] . '</span>'; ?>
                </div>
                <div class="form-group<?php if(!empty($errors['password'])) echo ' has-error'; ?>">
                    <label class="col-sm-2 control-label" for="registerPassword">Wachtwoord:</label>
                        <input type="password" name="password" id="registerPassword" class="form-control reg" />
                        <?php if(!empty($errors['password'])) echo '<span class="error-message">' . $errors['password'] . '</span>'; ?>
                </div>
                <div class="form-group<?php if(!empty($errors['confirm_password'])) echo ' has-error'; ?>">
                    <label class="col-sm-2 control-label" for="registerConfirmPassword">Herhaal wachtwoord:</label>
                    <input type="password" name="confirm_password" id="registerConfirmPassword" class="form-control reg" />
                    <?php if(!empty($errors['confirm_password'])) echo '<span class="error-message">' . $errors['confirm_password'] . '</span>'; ?>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10"><input type="submit" value="Registreer" class="knop-reg"></div>
                </div>
            </form>
    </div>
</section>