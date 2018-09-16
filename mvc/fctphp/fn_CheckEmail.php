<?php 
	function fn_CheckEmail($email)
	{
		if ( preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-]).*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email) )
		{
			list($username,$domain) = explode('@',$email);
			if ( ! preg_match("/^.*\..*$/", $domain) )
			{
				return false;
			}
			if ( function_exists('checkdnsrr') && ! checkdnsrr($domain,'MX') )
			{
				return false;
			}
			return true;
		}
		return false;
	}
?>