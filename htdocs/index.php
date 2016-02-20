<?php include_once('includes/header.php'); ?>
	<main>
		<?php if (!$_SESSION['username']) { ?>
			<div id='main-form'>
				<div class='block-form'>
					<div class='title-form'>Connexion</div>
					<form action='verif_signin.php' method='POST'>
			            <label for='login-signin'>Identifiant<br /><input type='text' name='login' id='login-signin'/>
			            <br />
			            <label for='password-signin'>Mot de passe<label><br /><input type='password' name='password' id='password-signin' />
			            <br />
			            <input type='submit' name='submit' value='connexion' />
			            <br />
						<a href='request_password.php'>J'ai oublié mon mot de passe</a>
			            <br />
			            <?php if ($_SESSION['errors_signin']) {
				            echo "<span class='error'>". $_SESSION['errors_signin'] ."</span><br />";
			            	unset($_SESSION['errors_signin']);
			        	} ?>
			        </form>
				</div>
			    <div class='block-form'>
			    	<div class='title-form'>Inscription</div>
			    	<form action='verif_signup.php' method='POST'>
			    		<label for='login-signup'>Identifiant<br /><input type='text' name='login' id='login-signup' />
			    		<br />
			    		<label for='email-signup'>Email<br /><input type='email' name='email' id='login-signup' />
			            <br />
			            <label for='password-signup'>Mot de passe<br /><input type='password' name='password' id='password-signup' />
			            <br />
			            <input type='submit' name='submit' value='sinscrire' />
			    	</form>
			    	<?php if ($_SESSION['errors_signup']) {
			            foreach ($_SESSION['errors_signup'] as $value) {
			            	echo "<br /><span class='error'>". $value ."</span>";
			            }
			            unset($_SESSION['errors_signup']);
			        } ?>				 
				</div>
			</div>
	    <?php } else { ?>
	    	<div id='list-png'>
	    	<form>
	    	<?php
	    		$dir = 'img/png/';
	    		$dh = opendir($dir);
	    		while ($file = readdir($dh)) {
	    			if (substr($file, -4) === '.png' && substr($file, 0) !== '.') {
	    				echo '<input type="radio" name="imgpng" value="'. substr($file, 0, -4) .'"><img src="'. $dir.$file .'" width=50 height=50  alt="'. substr($file, 0, -4) .'" />';
	    			}
	    		}
	    	?>
	    	</form>
	    	</div>
	    	<div class='camera'>
    			<video id='video'>Video stream not available.</video>
    			<button id='startbutton' >Prendre une photo</button>
  			</div>
  			<div class='result'>
				<canvas id='canvas'>
  				</canvas>
  				<button id='savebutton' >Sauvegarder</button>
  			</div>
	    <?php } ?>
	</main>
	<?php include_once('includes/side.php'); ?>
	<?php include_once('includes/footer.php'); ?>