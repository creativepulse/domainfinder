<?php

/**
 * Domain Finder
 *
 * @version 1.2
 * @author Creative Pulse
 * @copyright Creative Pulse 2011-2014
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link http://www.creativepulse.gr
 */


$url = @$_GET['url'];
$domain = @$_GET['domain'];
$host = @$_GET['host'];

?>
<!DOCTYPE html>

<html>

<head>

<title></title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<style type="text/css">
body {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
}
button {
	float: right;
	cursor: pointer;
}
hr {
	clear: both;
}
</style>

</head>

<body>

<?php

if (empty($url) || empty($domain)) {

?>

<p>Missing input parameters</p>

<?php

}
else {

	require_once(dirname(__FILE__) . '/inc/data_manager.php');
	$mhs = $data_manager->start_session(false);

	if ($mhs === false) {

?>

<p>Session timed out. Please refresh the main page and retry.</p>

<?php

	}
	else {

?>
<script type="text/javascript" src="<?php echo $url ?>/js/domainfinder_loader.php"></script>

<button onclick="window.print();">Print</button>
<h1><?php echo $domain; ?></h1>
<hr>

<p id="info">Retrieving information ...</p>

<script type="text/javascript">

document.cpwdg_domainfinder_mhs = "<?php echo $mhs; ?>";

function cpwdg_domainfinder_h_response() {
	if (document.cpwdg_domainfinder_ajax.readyState == 4) {
		if (document.cpwdg_domainfinder_ajax.status == 200) {
			document.getElementById("info").innerHTML = document.cpwdg_domainfinder_ajax.responseText.replace(/\n/g, "<br/>");
		}
		else {
			alert("HTTP error status " + document.cpwdg_domainfinder_ajax.status + ":\n" + document.cpwdg_domainfinder_ajax.responseText);
			document.cpwdg_domainfinder_ajax = null;
		}
	}
}

function cpwdg_domainfinder_init() {
	if (window.XMLHttpRequest) {
		document.cpwdg_domainfinder_ajax = new window.XMLHttpRequest();
	}
	else if (window.ActiveXObject) {
		document.cpwdg_domainfinder_ajax = new window.ActiveXObject("Microsoft.XMLHTTP");
	}

	if (!document.cpwdg_domainfinder_ajax) {
		alert("Critical Error: Your browser was unable to initialize the AJAX sub-system");
	}
	else {
		document.cpwdg_domainfinder_ajax.open("GET", "<?php echo $url ?>/search.php?domain=" + encodeURIComponent("<?php echo $domain ?>") + "&host=" + encodeURIComponent("<?php echo $host ?>") + "&task=info&mhs=" + document.cpwdg_domainfinder_mhs + "&nhc=" + document.cpwdg_domainfinder_nhc, true);
		document.cpwdg_domainfinder_ajax.onreadystatechange = cpwdg_domainfinder_h_response;
		document.cpwdg_domainfinder_ajax.send();
	}
}

if (window.addEventListener) {
	window.addEventListener("load", cpwdg_domainfinder_init, false);
}
else if (window.attachEvent) {
	window.attachEvent("onload", cpwdg_domainfinder_init);
}

</script>

<?php

	}

}

?>

</body>

</html>
