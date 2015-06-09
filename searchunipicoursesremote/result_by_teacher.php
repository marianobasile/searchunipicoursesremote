<?php

/*require_once('../../config.php');

if(!defined('AJAX_SCRIPT')) {
    define('AJAX_SCRIPT', true);
}


require_once($CFG->libdir . '/datalib.php');
require_once($CFG->libdir . '/accesslib.php');
*/

$query = $_GET['query'];
$query = utf8_decode($query);

$params = array( 'teachername' =>  $query);
$function_name = 'local_unipipluginautocomplete_search_by_teacher';

$token = "6dc147add46ca603298a129046ab2471";

require_once( './curl.php' );
$curl = new curl;

$remoteSites = 1;
$domain = array("http://131.114.28.114/elearn");
$response = array(); //array contenente i risultati ottenuti. I risultati sono ritornati sotto forma di stringa.

	for ($i = 0; $i < $remoteSites; $i++) {
		
			$response[$i] = $curl->post($domain[$i] . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$function_name, $params);
			$temp = strip_tags($response[$i]);
			$temp = trim ($temp);	
			$temp = str_replace("&quot;","\"", $temp);
			echo $temp;		
	}
