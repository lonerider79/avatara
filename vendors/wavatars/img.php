<?php

  // Load the Elgg framework
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

$guid = get_input('entity');
$entity = get_entity($guid);

$seed = wavatar_seed($entity);
		
// Get the size
$size = strtolower(get_input('size'));
if (!in_array($size,array('large','medium','small','tiny','master','topbar')))
	$size = "medium";
		
// Try and get the icon
	

$filehandler = new ElggFile();
$filehandler->owner_guid = $entity->getGUID();
$filehandler->setFilename("wavatar/" . $seed . '/' . $size . ".jpg");
//print $filehandler->getFilenameOnFilestore();		
$success = false;
if ($filehandler->open("read")) {
	if ($contents = $filehandler->read($filehandler->size())) {
		$success = true;
	} 
}
		
if (!$success) {
//   global $CONFIG;
//   $path = elgg_view('icon/user/default/'.$size);
//   header("Location: {$path}");
//   exit;
	//$contents = @file_get_contents($CONFIG-pluginspath . "profile/graphics/default{$size}.jpg");
			
}

header("Content-type: image/jpeg");
header('Expires: ' . date('r',time() + 864000));
header("Pragma: public");
header("Cache-Control: public");
header("Content-Length: " . strlen($contents));
$splitString = str_split($contents, 1024);
foreach($splitString as $chunk)
	echo $chunk;

?>