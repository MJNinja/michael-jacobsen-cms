<?php
class browserDetails{
	function getBrowser($u_agent, $field)
	{
		$bname = 'Unknown';
		$version=  "Unknown";

		//First get the platform?
		if (preg_match('/linux/i', $u_agent)) {
			$platform = 'Linux';
		}
		elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
			$platform = 'Macintosh';
		}
		elseif (preg_match('/windows|win32/i', $u_agent)) {
			$platform = 'Windows';
    	}else{
			$platform = 'Others';
		}

		if(preg_match('/Trident/i', $u_agent) && !preg_match('/Opera/i',$u_agent)){
			if(preg_match('/MSIE/i', $u_agent)){
				if(preg_match('/chromeframe/i', $u_agent)){
					$bname = 'IE with Chrome Frame';
					$ub = "chromeframe";
				}else{
					$bname = 'Internet Explorer';
					$ub = "MSIE";
				}
			}else{
				$bname = 'Internet Explorer';
				// no $ub because we use another pattern
			}
		}elseif(preg_match('/Firefox/i',$u_agent)){
			$bname = 'Mozilla Firefox';
			$ub = "Firefox";
		}
		elseif(preg_match('/Edge/i',$u_agent)){
			$bname = 'Microsoft Edge';
			$ub = "Edge";
		}elseif(preg_match('/Chrome/i',$u_agent)){
			$bname = 'Google Chrome';
			$ub = "Chrome";
		}elseif(preg_match('/Safari/i',$u_agent)){
			$bname = 'Apple Safari';
			$ub = "Safari";
		}elseif(preg_match('/Opera/i',$u_agent)){
			$bname = 'Opera';
			$ub = "Opera";
		}elseif(preg_match('/Netscape/i',$u_agent)){
			$bname = 'Netscape';
			$ub = "Netscape";
		}

		if(preg_match('/Trident/i', $u_agent) && !preg_match('/MSIE/i', $u_agent)){
			$pattern = '/Trident\/.*rv:([0-9]{1,}[\.0-9.]{0,})/';
		if(preg_match($pattern, $u_agent, $matches) AND isset($matches[1]))
			$version = $matches[1];
		}
		// for others (It can be nice to combinate this nice code with the code below !)
		else{
			$known = array('Version', $ub, 'other');
			$pattern = '#(?<browser>' . join('|', $known) .
			')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		if(preg_match_all($pattern, $u_agent, $matches)){
			// see how many we have
			$i = count($matches['browser']); // we have matching
		if ($i != 1) {
		//we will have two since we are not using 'other' argument yet
		//see if version is before or after the name
		if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
		$version= $matches['version'][0];
		}else {
		$version= isset($matches['version'][1])?$matches['version'][1]:'';
		}
		}else {
		$version= $matches['version'][0];
		}
		}
		}

		if($field=='version'){
			return $version;
		}elseif($field=='platform'){
			return $platform;
		}else{
			return $bname;
		}
	}
}
$browserDetails = new browserDetails();
?>
