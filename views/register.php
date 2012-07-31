<h2><?php echo _('Register Application');?></h2>
<div class='registrationFormWrapper'>
	<form method='post' action='register.php' id='regForm' class='regForm' onsubmit="return validate(this)">
		<fieldset>
			<legend><?php echo _('User Information');?></legend>	
			<p><label><?php echo _('Identity');?></label></p>	
			<input type = 'radio' Name ='user_type' value= '1' id='student_radio' checked>Student
			<input type = 'radio' Name ='user_type' value= '2' id='professor_radio'>Professor
			<label><?php echo _('Username');?></label>
			<input type='text' name='username' id='username' placeholder="<?php echo _('Your unique username');?>" />
			<label><?php echo _('Password');?></label>
			<input type='password' name='password' id='password' placeholder="<?php echo _('Your login password');?>" />
			<label><?php echo _('Password Again');?></label>
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
			<input type='submit' value = "<?php echo _('Send');?>" class='submit'/>
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
  var nameRegex = /^[a-zA-Z]+(([\'\,\.\- ][a-zA-Z ])?[a-zA-Z]*)*$/;
  var emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
  var passRegex = /(?=.*\d)(?=.*[a-z]).{6,}/;
  var numRegex = /^\s*\d+\s*$/;
  
  if (username.length<<?php echo $MIN_USERNAME_LENGTH;?>) {
	inlineMsg('username','Your username has to be bigger than that.', 2, 0);
    return false;
  }
  
  if (password.length<<?php echo $MIN_PASSWORD_LENGTH;?>) {
	inlineMsg('password','Your password has to be bigger than that.', 2, 0);
    return false;
  }
  
  if (username.length><?php echo $MAX_USERNAME_LENGTH;?>) {
	inlineMsg('username','Your username has to be smaller than that.', 2, 0);
    return false;
  }
  
  if (password.length><?php echo $MAX_PASSWORD_LENGTH;?>) {
	inlineMsg('password','Your password has to be smaller than that.', 2, 0);
    return false;
  }
  
  if(password == "") {
    inlineMsg('password','You must enter a password', 2, 0);
    return false;
  }
  if(!password.match(passRegex)) {
    inlineMsg('password','Password must contain at least 6 letters and numbers', 2, 0);
    return false;
  }
  if(password!=password_again) {
	inlineMsg('password_again','Passwords must match', 2, 0);
	return false;
  }
  if(first_name == "") {
    inlineMsg('first_name','You must enter your name',2, 0);
    return false;
  }
  if(!first_name.match(nameRegex)) {
    inlineMsg('first_name','You have entered an invalid name',2, 0);
    return false;
  }
  if(last_name == "") {
    inlineMsg('last_name','You must enter your last name',2, 0);
    return false;
  }
  if(!last_name.match(nameRegex)) {
    inlineMsg('last_name','You have entered an invalid last name',2, 0);
    return false;
  }
  if(email == "") {
    inlineMsg('email','You must enter your email',2, 0);
    return false;
  }
  if(!email.match(emailRegex)) {
    inlineMsg('email','You have entered an invalid email.',2, 0);
    return false;
  }
  if(aem == ""&&($('#student_radio:checked').val())) {
	inlineMsg('aem','You have to enter your AEM',2, 0);
    return false;
  }
  if(!aem.match(numRegex)&&($('#student_radio:checked').val())) {
	inlineMsg('aem','You have entered an invalid AEM',2, 0);
    return false;
  }
  if(semester == ""&&($('#student_radio:checked').val())) {
	inlineMsg('semester','You have to enter your semester',2, 0);
    return false;
  }
  if(!semester.match(numRegex)&&($('#student_radio:checked').val())) {
	inlineMsg('semester','You have entered an invalid semester',2, 0);
    return false;
  }
  if(exists==1) {
	inlineMsg('username','Username already exists', 1, 0);
	return false;
  }
  
  
  return true;
}

// START OF MESSAGE SCRIPT //

var MSGTIMER = 20;
var MSGSPEED = 5;
var MSGOFFSET = 3;
var MSGHIDE = 3;

// build out the divs, set attributes and call the fade function //
function inlineMsg(target,string,autohide, type) {
  var msg;
  var msgcontent;
  if (type==0) {
	  if(!document.getElementById('msg')) {
		msg = document.createElement('div');
		msg.id = 'msg';
		msgcontent = document.createElement('div');
		msgcontent.id = 'msgcontent';
		document.body.appendChild(msg);
		msg.appendChild(msgcontent);
		msg.style.filter = 'alpha(opacity=0)';
		msg.style.opacity = 0;
		msg.alpha = 0;
	  } 
	  else {
			msg = document.getElementById('msg');
			msgcontent = document.getElementById('msgcontent');
	  }
  }
  else {
	  if(!document.getElementById('msg2')) {
		msg = document.createElement('div');
		msg.id = 'msg2';
		msgcontent = document.createElement('div');
		msgcontent.id = 'msgcontent2';
		document.body.appendChild(msg);
		msg.appendChild(msgcontent);
		msg.style.filter = 'alpha(opacity=0)';
		msg.style.opacity = 0;
		msg.alpha = 0;
	  } 
	  else {
			msg = document.getElementById('msg2');
			msgcontent = document.getElementById('msgcontent2');
	  }
  }
		
  msgcontent.innerHTML = string;
  msg.style.display = 'block';
  var msgheight = msg.offsetHeight;
  var targetdiv = document.getElementById(target);
  if (type==0) {
	targetdiv.focus();
  }
  var targetheight = targetdiv.offsetHeight;
  var targetwidth = targetdiv.offsetWidth;
  var topposition = topPosition(targetdiv) - ((msgheight - targetheight) / 2);
  var leftposition = leftPosition(targetdiv) + targetwidth + MSGOFFSET;
  msg.style.top = topposition + 'px';
  msg.style.left = leftposition + 'px';
  clearInterval(msg.timer);
  if (type==0) {
	msg.timer = setInterval("fadeMsg(1, 0)", MSGTIMER);
  }
  else {
	msg.timer = setInterval("fadeMsg(1, 1)", MSGTIMER);
  }
  if(!autohide) {
    autohide = MSGHIDE;  
  }
  if (type==0) {
	window.setTimeout("hideMsg(1, 0)", (autohide * 1000));
  }
  else {
	window.setTimeout("hideMsg(1, 1)", (autohide * 1000));
  }
}

// hide the form alert //
function hideMsg(msg, type) {
  if (type==0) {
	  var msg = document.getElementById('msg');
	  if(!msg.timer) {
		msg.timer = setInterval("fadeMsg(0, 0)", MSGTIMER);
	  }
  }
  else {
	var msg = document.getElementById('msg2');
	  if(!msg.timer) {
		msg.timer = setInterval("fadeMsg(0, 1)", MSGTIMER);
	  }
  }
}

// fade the message box //
function fadeMsg(flag, type) {
  if(flag == null) {
    flag = 1;
  }
  if (type==0) {
	var msg = document.getElementById('msg');
  }
  else {
    var msg = document.getElementById('msg2');
  }
  var value;
  if(flag == 1) {
    value = msg.alpha + MSGSPEED;
  } else {
    value = msg.alpha - MSGSPEED;
  }
  msg.alpha = value;
  msg.style.opacity = (value / 100);
  msg.style.filter = 'alpha(opacity=' + value + ')';
  if(value >= 99) {
    clearInterval(msg.timer);
    msg.timer = null;
  } else if(value <= 1) {
    msg.style.display = "none";
    clearInterval(msg.timer);
  }
}

// calculate the position of the element in relation to the left of the browser //
function leftPosition(target) {
  var left = 0;
  if(target.offsetParent) {
    while(1) {
      left += target.offsetLeft;
      if(!target.offsetParent) {
        break;
      }
      target = target.offsetParent;
    }
  } else if(target.x) {
    left += target.x;
  }
  return left;
}

// calculate the position of the element in relation to the top of the browser window //
function topPosition(target) {
  var top = 0;
  if(target.offsetParent) {
    while(1) {
      top += target.offsetTop;
      if(!target.offsetParent) {
        break;
      }
      target = target.offsetParent;
    }
  } else if(target.y) {
    top += target.y;
  }
  return top;
}

// preload the arrow //
if(document.images) {
  arrow1 = new Image(7,80); 
  arrow1.src = "js/images/msg_arrow.gif"; 
  arrow2 = new Image(7,80); 
  arrow2.src = "js/images/msg_arrow2.png"; 
}



//Unique username evaluation//
$(document).ready(function(){
$('#username').focusout(username_check);
});
var exists;
function username_check(){	
	var username = $('#username').val();

	if(username.length >= <?php echo $MIN_USERNAME_LENGTH;?>) {
		
		$.ajax({
		   type: "POST",
		   url: "../public_html/check_username.php",
		   data: 'username='+ username,
		   cache: false,
		   success: function(response){
				if(response){
					inlineMsg('username',response, 1, 0);
					exists=1;
				}
				else {
					inlineMsg('username','Username available', 1, 1);
					exists=0;
				}

			}
		});
	}
}

//User type form formatting//
$('#professor_radio').click(function() {	
	$('#aemLabel, #semesterLabel, #aem, #semester').fadeOut('fast');//css("display","none");
});
$('#student_radio').click(function() {	
	$('#aemLabel, #semesterLabel, #aem, #semester').fadeIn('fast');//css("display","block");
});


</script>
