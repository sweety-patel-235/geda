<?php
if(isset($ext) && $ext=='xml') {
	$xml = Xml::fromArray(array('response' => $response));
	echo $xml->asXML();
} else {
	echo json_encode($response);
}
?>