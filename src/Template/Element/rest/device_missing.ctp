<?php
$recipes = array('id'=>1, 'name'=>'Jitendra');

echo "====>".$ext."<====";
if($ext=='xml') {
	$xml = Xml::fromArray(array('response' => $recipes));
	echo $xml->asXML();
} else {
	echo json_encode($recipes);
}
?>