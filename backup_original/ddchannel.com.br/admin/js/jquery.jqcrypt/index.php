<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title>Demo - Client-Side to Server-Side Encryption</title>
		<style>
		
		html { font: normal 12px Arial, Helvetica, sans-serif; line-height: 170%; }
		body { width: 500px; margin: 20px auto; }
		
		</style>
		<script type="text/javascript" src="scripts/jquery.js"></script>
		<script type="text/javascript" src="scripts/jquery.jqcrypt.pack.js"></script>
		<script type="text/javascript">

		$(function(){
			$('#testform').jqcrypt({
				keyname:    'jqckval',
				callback:   function(form){ form.submit(); }
			});
		});
		
		</script>
	</head>
	<body>
	
	<h1>Demo - JQCrypt Plugin</h1>
	
	<form method="post" action="index.php?e=t" id="testform">
	Name:<br />
	<input type="text" name="name" /><br />
	Email:<br />
	<input type="text" name="email" /><br />
	Password:<br />
	<input type="password" name="password" /><br />
	Profession:<br />
	<select name="profession">
		<option value="Designer">Designer</option>
		<option value="Developer">Developer</option>
		<option value="Other">Other</option>
	</select>
	<br />
	Comments:<br />
	<textarea name="comments"></textarea><br />
	<br />
	<input type="submit" value="Submit!" />	
	</form>
	
	<hr />
	
	<?php 
	
	function c2sdecrypt($s,$k){
	   $k = base64_decode(urldecode($k));
	   $s = urldecode($s);
	   $k = str_split(str_pad('', strlen($s), $k));
	   $sa = str_split($s);
	   foreach($sa as $i=>$v){
		   $t = ord($v)-ord($k[$i]);
		   $sa[$i] = chr( $t < 0 ?($t+256):$t);
	   }
	   return urldecode(join('', $sa));
	}
	
	
	if(!empty($_GET['e'])){
		$key = $_POST['jqckval'];
		$output = "<h3>Encrypted:</h3>";
		$output .= "Name: " . $_POST['name'] . "<br />";
		$output .= "Email: " . $_POST['email'] . "<br />";
		$output .= "Password: " . $_POST['password'] . "<br />";
		$output .= "Profession: " . $_POST['profession'] . "<br />";
		$output .= "Comments: " . $_POST['comments'] . "<br />";
		$output .= "<h3>Decrypted:</h3>";
		$output .= "Key: " . $key . "<br />";
		$output .= "Name: " . c2sdecrypt($_POST['name'],$key) . "<br />";
		$output .= "Email: " . c2sdecrypt($_POST['email'],$key) . "<br />";
		$output .= "Password: " . c2sdecrypt($_POST['password'],$key) . "<br />";
		$output .= "Profession: " . c2sdecrypt($_POST['profession'],$key) . "<br />";
		$output .= "Comments: " . c2sdecrypt($_POST['comments'],$key) . "<br />";
		
		echo($output);
		
	}
	else{
		echo("<p>Please fill out and submit the form...<p>");
	}
	
	
	?>
	</body>
</html>
