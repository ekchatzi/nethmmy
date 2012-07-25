// form validation function //
function validate(form) {
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
  
  if(password == "") {
    inlineMsg('password','You must enter a password.', 2);
    return false;
  }
  if(!password.match(passRegex)) {
    inlineMsg('password','Password must contain at least 6 letters and numbers', 2);
    return false;
  }
  if(password!==password_again) {
	inlineMsg('password_again','Passwords must match', 2);
	return false;
  }
  if(first_name == "") {
    inlineMsg('first_name','You must enter your name.',2);
    return false;
  }
  if(!first_name.match(nameRegex)) {
    inlineMsg('first_name','You have entered an invalid name.',2);
    return false;
  }
  if(last_name == "") {
    inlineMsg('last_name','You must enter your last name.',2);
    return false;
  }
  if(!last_name.match(nameRegex)) {
    inlineMsg('last_name','You have entered an invalid last name.',2);
    return false;
  }
  if(aem == "") {
	inlineMsg('aem','You must enter your aem.', 2);
    return false;
  }
  if(!aem.match(numRegex)) {
	inlineMsg('aem','Your aem must contain only numbers.', 2);
    return false;
  }
  if(email == "") {
    inlineMsg('email','You must enter your email.',2);
    return false;
  }
  if(!email.match(emailRegex)) {
    inlineMsg('email','You have entered an invalid email.',2);
    return false;
  }
  if(semester == "") {
    inlineMsg('semester','You must enter your current semester.',2);
    return false;
  }
  if(!semester.match(numRegex)) {
    inlineMsg('semester','You have entered an invalid semester.',2);
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
function inlineMsg(target,string,autohide) {
  var msg;
  var msgcontent;
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
  } else {
    msg = document.getElementById('msg');
    msgcontent = document.getElementById('msgcontent');
  }
  msgcontent.innerHTML = string;
  msg.style.display = 'block';
  var msgheight = msg.offsetHeight;
  var targetdiv = document.getElementById(target);
  targetdiv.focus();
  var targetheight = targetdiv.offsetHeight;
  var targetwidth = targetdiv.offsetWidth;
  var topposition = topPosition(targetdiv) - ((msgheight - targetheight) / 2);
  var leftposition = leftPosition(targetdiv) + targetwidth + MSGOFFSET;
  msg.style.top = topposition + 'px';
  msg.style.left = leftposition + 'px';
  clearInterval(msg.timer);
  msg.timer = setInterval("fadeMsg(1)", MSGTIMER);
  if(!autohide) {
    autohide = MSGHIDE;  
  }
  window.setTimeout("hideMsg()", (autohide * 1000));
}

// hide the form alert //
function hideMsg(msg) {
  var msg = document.getElementById('msg');
  if(!msg.timer) {
    msg.timer = setInterval("fadeMsg(0)", MSGTIMER);
  }
}

// fade the message box //
function fadeMsg(flag) {
  if(flag == null) {
    flag = 1;
  }
  var msg = document.getElementById('msg');
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
  arrow = new Image(7,80); 
  arrow.src = "images/msg_arrow.gif"; 
}