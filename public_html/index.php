<?php
	include_once("../lib/localization.php");
	include_once("../lib/login.php");
	include_once("../views/views.php");
	$logged_userid = get_logged_user();
	$user_type = isset($logged_userid[0])?$logged_userid[0]:'g';
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="description" content="<?php echo _('Ηλεκτρονική τάξη Τμήματος Ηλεκτρολόγων Μηχανικών και Μηχανικών Υπολογιστών Αριστοτελείου Πανεπιστημίου Θεσσαλονίκης');?>">
	<title><?php echo "$TITLE - nethmmy";?></title>
	<link rel="shortcut icon" type="image/png" href="images/resource/icon.png" />
	<link rel="stylesheet" type="text/css" href="css/index.css" />
	<link rel="stylesheet" type="text/css" href="css/views.css" />
	<script type="text/javascript" src="js/jquery.js"></script>

</head>
<body>
<div class='bodyWrapper'>
	<div class='topBar'>
		<div class='loginInfo'>
<?php		if($logged_userid)
		{?>
			<span><?php echo sprintf(_("Welcome ,%s (Last login %s)"),$logged_userid,time());?></span>
<?php		}
		else
		{?>
			<script type="text/javascript" >
				var loginExpanded = false;
				$(document).ready(function(){
					$('#showLoginLink').click(function(){
						if(!loginExpanded){
							$('#loginPrompt').show('fast');
							$(this).addClass('showLoginLinkExpanded');	
							loginExpanded = true;
						}
						else{
							$('#loginPrompt').hide('fast');
							$(this).removeClass('showLoginLinkExpanded');
							loginExpanded = false;
						}					
					});
				});
			</script>
			<a id='showLoginLink' href='javascript:void(0)'><?php echo _('Login');?></a>
			<div id='loginPrompt'>
				<form method='post' action='actions/login.php'>
					<div class='loginPromptLine1'>
						<input type='text' name='username' placeholder="<?php echo _('Username');?>" />
						<input type='password' name='password' placeholder="<?php echo _('Password');?>" />
					</div>
					<div class='loginPromptLine2'>
						<input type='submit' value="<?php echo _('Login');?>" />
						<a href='' id='forgotPasswordLink'><?php echo _('Remind password');?></a>
					</div>
				</form>
			</div>
<?php		}?>
		</div>
		<div class='topNavigation'>
			<ul>
				<li><a href='index.php
?v=home'><?php echo _('Home');?></a></li>
<?php				if($logged_userid)
				{?>
					<li><a href=''><?php echo _('Logout');?></a></li>			
<?php				}?>
			</ul>		
		</div>
	</div>
	<div class='header'>
		<div class='headerMain'>
			<h1> <?php echo _('eTHMMY');?> </h1>
			<p> <?php echo _('Online classes application');?> </p>		
		</div>
		<div class='topBar'>
			<div class='topNavigation'>
				<ul>
					<li><a href=''><?php echo _('Link1');?></a></li>
					<li><a href=''><?php echo _('Link2');?></a></li>			
				</ul>
			</div>
		</div>
	</div>
	<div class='mainBody'>
		<div class='navigationSide'>
			<ul>
				<li><a href='index.php?v=class&amp;id=5'><?php echo _('Class 1');?></a></li>
<?php
				if($user_type == 's')
				{
					echo "<li><a href='index.php?v=classes'>"._('All classes')."</a></li>";
				}
?>
			</ul>
		</div>
		<div class='mainView'>
		<?php			
			include($VIEW);
		?>
		</div>
	</div>
	<div class='footer'>
		<?php echo _('Blah blah blah 2012');?>
	</div>
</div>
</body>
</html>
