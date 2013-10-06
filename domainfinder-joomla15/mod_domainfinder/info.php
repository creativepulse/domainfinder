<?php

/**
 * domain finder
 *
 * @version 1.1
 * @author Creative Pulse
 * @copyright Creative Pulse 2011-2013
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

    require_once(dirname(__FILE__) . '/data.inc.php');
    $mhs = $data_mgr->start_session(false);

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

document.mod_domainfinder_mhs = "<?php echo $mhs; ?>";

function mod_domainfinder_h_response() {
    if (document.mod_domainfinder_ajax.readyState == 4) {
        if (document.mod_domainfinder_ajax.status == 200) {
            document.getElementById("info").innerHTML = document.mod_domainfinder_ajax.responseText.replace(/\n/g, "<br/>");
        }
        else {
            alert("HTTP error status " + document.mod_domainfinder_ajax.status + ":\n" + document.mod_domainfinder_ajax.responseText);
            document.mod_domainfinder_ajax = null;
        }
    }
}

function mod_domainfinder_init() {
    if (window.XMLHttpRequest) {
        document.mod_domainfinder_ajax = new window.XMLHttpRequest();
    }
    else if (window.ActiveXObject) {
        document.mod_domainfinder_ajax = new window.ActiveXObject("Microsoft.XMLHTTP");
    }

    if (!document.mod_domainfinder_ajax) {
        alert("Critical Error: Your browser was unable to initialize the AJAX sub-system");
    }
    else {
        document.mod_domainfinder_ajax.open("GET", "<?php echo $url ?>/search.php?domain=" + encodeURIComponent("<?php echo $domain ?>") + "&host=" + encodeURIComponent("<?php echo $host ?>") + "&task=info&mhs=" + document.mod_domainfinder_mhs + "&nhc=" + document.mod_domainfinder_nhc, true);
        document.mod_domainfinder_ajax.onreadystatechange = mod_domainfinder_h_response;
        document.mod_domainfinder_ajax.send();
    }
}

if (window.addEventListener) {
    window.addEventListener("load", mod_domainfinder_init, false);
}
else if (window.attachEvent) {
    window.attachEvent("onload", mod_domainfinder_init);
}

</script>

<?php

    }

}

?>

</body>

</html>
