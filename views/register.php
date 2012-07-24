<h2><?php echo _('Register Application');?></h2>
<div class='registrationFormWrapper'>
	<form method='post' action='register.php'>
		<label><?php echo _('Username');?></label>
		<input type='text' name='username' />
		<label><?php echo _('Password');?></label>
		<input type='password' name='password' />
		<label><?php echo _('Verify Password');?></label>
		<input type='password' name='password_again' />
		<label><?php echo _('First Name');?></label>
		<input type='text' name='first_name' />
		<label><?php echo _('Last Name');?></label>
		<input type='text' name='last_name' />
		<label><?php echo _('AEM');?></label>
		<input type='text' name='aem' />
		<label><?php echo _('Email');?></label>
		<input type='text' name='email' />
		<input type='submit' value = "<?php echo _('Apply');?>" />
	</form>
</div>
