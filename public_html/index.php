<?php
	include_once("../lib/localization.php");
	include_once("../lib/login.php");
	include_once("../views/views.php");
	include_once("../lib/connect_db.php");

	setcookie('last_view',(isset($_SERVER['QUERY_STRING'])?$_SERVER['QUERY_STRING']:'v=home'),0);//save last view for after some actions

	/* Get logged user identification data */
	$user_type = '';
	$logged_userid = 0;
	$logged_user = get_logged_user();
	if(isset($logged_user) && $logged_user)
	{
		$user_type = $logged_user['type'];
		$logged_userid = $logged_user['id'];
	}
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
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>

</head>
<body>
<div class='bodyWrapper'>
	<div class='topBar'>
		<div class='loginInfo'>
<?php		if($logged_userid)
		{
			$username = $logged_userid;
			$last_login = time();
			$query = "SELECT username,last_login FROM users WHERE id='$logged_userid' LIMIT 1";
			$ret = mysql_query($query);
			if($ret && mysql_numrows($ret))
			{
				$result = mysql_fetch_array($ret);
				$username = $result['username'];
				$last_login = $result['last_login'];
			}
			echo "<span><a href='index.php?v=profile&amp;uid=$logged_userid'>$username</a></span>";
		}
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
			<a href='index.php?v=register'><?php echo _('Register');?></a>
			<div id='loginPrompt'>
				<form method='post' action='login.php'>
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
					<li><a href='logout.php'><?php echo _('Logout');?></a></li>			
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
			if(isset($_COOKIE['notify']))
			{?>
			<div class='notificationSide'>
<?php				echo $_COOKIE['notify'];
				setcookie('notify','',time()-3600);
?>			</div>		
<?php			}?>
			<div>
			<?php
				include($VIEW);
			?>
			</div>
		</div>
	</div>
	<div class='footer'>
		<?php echo _('Blah blah blah 2012');?>
	</div>
</div>
</body>
</html>
