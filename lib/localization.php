<?php
	$locale = "el_GR.utf8";
	if (isset($_GET["locale"]))
	{ 
		$locale = $_GET["locale"];
		setcookie("locale",$locale,time()+10000000);
	}
	else if(isset($_COOKIE['locale']))
	{
		$locale = $_COOKIE['locale'];
	}

	setlocale(LC_MESSAGES, $locale);
	setlocale(LC_TIME, $locale);
	bindtextdomain("messages", "../locale");
	bind_textdomain_codeset("messages", 'utf-8');
	textdomain("messages");
?>
