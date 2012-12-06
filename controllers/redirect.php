<?php
include_once('../config/general.h');

class redirect
{
	public static function run()
	{
		global $error,$message;
		global $MESSAGE_SEPERATOR,$INDEX_ROOT;
			
		if(isset($_GET['AJAX']))
		{ 
			echo '{ "error" : ['.(count($error)?('"'.implode('","',$error).'"'):'').'],
				"message" : ['.(count($message)?('"'.implode('","',$message).'"'):'').']}';
		}
		else
		{
			if(isset($message) && count($message))
				setcookie('message',implode($MESSAGE_SEPERATOR,$message),time()+3600,$INDEX_ROOT);

			if(isset($error) && count($error))
				setcookie('notify',implode($MESSAGE_SEPERATOR,$error),time()+3600,$INDEX_ROOT);

			$redirect = "profile/$uid/";
			include('redirect.php');
		}
	}
}
?>
