<?php

/*
error_reporting(E_ALL);
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
*/




require_once('core.php');



if (isset ( $_GET ['id'] )) {
	$id = $_GET ['id'];
} else {
	$id = false;
	echo '<strong>Welcome</strong> to wordpress automatic cron job...';
}
$gm = new wp_automatic ();

$gm->process_campaigns ( $id );

?>
