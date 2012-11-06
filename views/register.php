<h2><?php echo _('Register Application');?></h2>
<div class='registrationFormWrapper'>
	<form method='post' action='register.php' id='regForm' class='regForm' onsubmit="return validate(this)">
		<fieldset>
			<legend><?php echo _('User Information');?></legend>	
			<p><label><?php echo _('Account type');?></label></p>	
			<input type = 'radio' Name ='user_type' value= '1' id='student_radio' checked><span class='radioText'>Student</span>
			<input type = 'radio' Name ='user_type' value= '2' id='professor_radio'><span class='radioText'>Professor</span>
			<label><?php echo _('Username');?></label>
			<input type='text' name='username' id='username' placeholder="<?php echo _('Your unique username');?>" />
			<label><?php echo _('Password');?></label>
			<input type='password' name='password' id='password' placeholder="<?php echo _('Your login password');?>" />
			<label><?php echo _('Confirm Password');?></label>
			<input type='password' name='password_again' id='password_again' placeholder="<?php echo _('Your login password again');?>" />
			<label><?php echo _('First Name');?></label>
			<input type='text' name='first_name' id='first_name' placeholder="<?php echo _('Your first name');?>" />
			<label><?php echo _('Last Name');?></label>
			<input type='text' name='last_name' id='last_name' placeholder="<?php echo _('Your last name');?>" />
			<label><?php echo _('Email');?></label>
			<input type='text' name='email' id='email' placeholder="<?php echo _('Your email address');?>" />
			<label id='aemLabel'><?php echo _('AEM');?></label>
			<input type='text' name='aem' id='aem' placeholder="<?php echo _('Your AEM');?>" />
			<label id='semesterLabel'><?php echo _('Semester');?></label>
			<input type='text' name='semester' id='semester' placeholder="<?php echo _('Your current semester');?>" />
			<input type='submit' value = "<?php echo _('Send');?>" class='submit' id='button'/>
		</fieldset>
	</form>
</div>
<script type="text/javascript">
// form validation function //
function validate(form) {
  var username = form.username.value;
  var first_name = form.first_name.value;
  var last_name = form.last_name.value;
  var email = form.email.value;
  var aem = form.aem.value;
  var password = form.password.value;
  var password_again = form.password_again.value;
  var semester = form.semester.value;
  var nameRegex = /^[\u0000-\u007F\u0370-\u03FF\u1F00-\u1FFF\u2000-\u206F]*$/;
  var emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
  var passRegex = /(?=.*\d)(?=.*[a-z]).{<?php echo $MIN_PASSWORD_LENGTH;?>,}/;
  var numRegex = /^\s*\d+\s*$/;
  
  if (username.length<<?php echo $MIN_USERNAME_LENGTH;?>) {
	inlineMsg('username','<?php echo _('Your username has to be bigger than that.');?>', 2, 0);
    return false;
  }
  
  if (password.length<<?php echo $MIN_PASSWORD_LENGTH;?>) {
	inlineMsg('password','<?php echo _('Your password has to be bigger than that.');?>', 2, 0);
    return false;
  }
  
  if (username.length><?php echo $MAX_USERNAME_LENGTH;?>) {
	inlineMsg('username','<?php echo _('Your username has to be smaller than that.');?>', 2, 0);
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
  if(first_name == "") {
    inlineMsg('first_name','<?php echo _('You must enter your name');?>',2, 0);
    return false;
  }
  if(!first_name.match(nameRegex)) {
    inlineMsg('first_name','<?php echo _('You have entered an invalid name');?>',2, 0);
    return false;
  }
  if(last_name == "") {
    inlineMsg('last_name','<?php echo _('You must enter your last name');?>',2, 0);
    return false;
  }
  if(!last_name.match(nameRegex)) {
    inlineMsg('last_name','<?php echo _('You have entered an invalid last name');?>',2, 0);
    return false;
  }
  if(email == "") {
    inlineMsg('email','<?php echo _('You must enter your email');?>',2, 0);
    return false;
  }
  if(!email.match(emailRegex)) {
    inlineMsg('email','<?php echo _('You have entered an invalid email.');?>',2, 0);
    return false;
  }
  if(aem == ""&&($('#student_radio:checked').val())) {
	inlineMsg('aem','<?php echo _('You have to enter your AEM');?>',2, 0);
    return false;
  }
  if(!aem.match(numRegex)&&($('#student_radio:checked').val())) {
	inlineMsg('aem','<?php echo _('You have entered an invalid AEM');?>',2, 0);
    return false;
  }
  if(semester == ""&&($('#student_radio:checked').val())) {
	inlineMsg('semester','<?php echo _('You have to enter your semester');?>',2, 0);
    return false;
  }
  if(!semester.match(numRegex)&&($('#student_radio:checked').val())) {
	inlineMsg('semester','<?php echo _('You have entered an invalid semester');?>',2, 0);
    return false;
  }
  if(exists==1) {
	inlineMsg('username','<?php echo _('Username already exists');?>', 1, 0);
	return false;
  }
  
  
  return true;
}



var exists;
function username_check(){	
	var username = $('#username').val();

	if(username.length >= <?php echo $MIN_USERNAME_LENGTH;?>) {
		
		$.ajax({
		   type: "POST",
		   url: "../public_html/check_username.php?AJAX",
		   data: 'username='+ username,
		   cache: false,
		   success: function(response){
				var ob = $.parseJSON(response);
				if(ob.error!=''){
					inlineMsg('username',ob.error, 1, 0);
					exists=1;
				}
				else {
					inlineMsg('username',ob.message, 1, 1);
					exists=0;
				}

			}
		});
	}
}

//Unique username evaluation//
$(document).ready(function(){
	$('#username').focusout(username_check);
	//User type form formatting//
	$('#professor_radio').click(function() {	
		$('#aemLabel, #semesterLabel, #aem, #semester').hide('fast');
	});
	$('#student_radio').click(function() {	
		$('#aemLabel, #semesterLabel, #aem, #semester').show('fast');
	});
});
</script>
