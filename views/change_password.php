<?php 
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');
	include_once('../config/general.php');
	
	if(!isset($error))
                $error = array();
	
	$token=isset($_GET['token'])?$_GET['token']:'';
?>
<h2><?php echo _('Reset your password');?></h2>
<div class='passResetWrapper'>
<form class='resetForm' action='change_password.php' method='post' onsubmit="return validate(this)">
	<fieldset>
		<legend><?php echo _('Change Password');?></legend>
		<label><?php echo _('New Password');?></label>
		<input type='password' name='password' id='password' placeholder="<?php echo _('Your new login password');?>" />
		<label><?php echo _('Confirm New Password');?></label>
		<input type='password' name='password_again' id='password_again' placeholder="<?php echo _('Your new login password again');?>" />
		<label><?php echo _('Code');?></label>
		<input type='text' name='token' id='token' value="<?php echo $token;?>" /><div class='passResetNote'><p><?php echo _('(Leave it unchanged if it is auto completed)');?></p></div>
		<input type='submit' value = "<?php echo _('Reset');?>" class='submit' id='button'/>
	</fieldset>
</form>
</div>
<script type="text/javascript">
// form validation function //
function validate(form) {
  var password = form.password.value;
  var password_again = form.password_again.value;
  var token = form.token.value;
  var passRegex = /(?=.*\d)(?=.*[a-z]).{<?php echo $MIN_PASSWORD_LENGTH;?>,}/;
  
  if (password.length<<?php echo $MIN_PASSWORD_LENGTH;?>) {
	inlineMsg('password','<?php echo _('Your password has to be bigger than that.');?>', 2, 0);
    return false;
  }  
  if (password.length><?php echo $MAX_PASSWORD_LENGTH;?>) {
	inlineMsg('password','<?php echo _('Your password has to be smaller than that.');?>', 2, 0);
    return false;
  }
  if(password == "") {
    inlineMsg('password','<?php echo _('You must enter a password');?>', 2, 0);
    return false;
  }
  if(!password.match(passRegex)) {
    inlineMsg('password','<?php sprintf(_("Password must contain at least %s letters and numbers"), $MIN_PASSWORD_LENGTH);?>', 2, 0);
    return false;
  }
  if(password!=password_again) {
	inlineMsg('password_again','<?php echo _('Passwords must match');?>', 2, 0);
	return false;
  }
  if(token.length<1) {
	inlineMsg('token','<?php echo _('You have to enter the code you received in your mail.');?>', 2, 0);
	return false;
  }

  return true;
}
</script>