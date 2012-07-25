<h2><?php echo _('Register Application');?></h2>
<div class='registrationFormWrapper'>
	<form method='post' action='register.php' id='regForm' class='regForm' onsubmit="return validate(this)">
		<label><?php echo _('Username');?></label>
		<input type='text' name='username' id='username'/>
		<label><?php echo _('Password');?></label>
		<input type='password' name='password' id='password'/>
		<label><?php echo _('Verify Password');?></label>
		<input type='password' name='password_again' id='password_again'/>
		<label><?php echo _('First Name');?></label>
		<input type='text' name='first_name' id='first_name'/>
		<label><?php echo _('Last Name');?></label>
		<input type='text' name='last_name' id='last_name'/>
		<label><?php echo _('AEM');?></label>
		<input type='text' name='aem' id='aem'/>
		<label><?php echo _('Email');?></label>
		<input type='text' name='email' id='email'/>
		<label><?php echo _('Semester');?></label>
		<input type='text' name='semester' id='semester'/>
		<input type='submit' value = "<?php echo _('Apply');?>" class='submit'/>
	</form>
</div>
