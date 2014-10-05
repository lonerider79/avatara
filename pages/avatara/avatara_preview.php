<?php

/* 
 * Preview Avatar based on input
 * 
 * 
 */

$avatar = get_input('avatar'); //Identicon,Wavatar...
$size = 'medium';
$entity_guid = get_input('entity_guid');
$entity = get_input('entity');
if($entity_guid) {
    $ee = get_entity($entity_guid);
}
if (!$ee || !($ee instanceof ElggUser || $ee instanceof ElggGroup)) {
    $url = "_graphics/icons/default/{$size}.png";
    $url = elgg_normalize_url($url);
    forward($url);
};

    switch($avatar) {
    case 'Identicon': 
       $contents = Identicon::preview($entity); 
       break;
    case 'MonsterId':
       $contents = MonsterId::preview($entity); 
       break;
    case 'Wavatar':
       $contents = Wavatar::preview($entity); 
       break;

    default:
        header("HTTP/1.1 404 Not Found");
        exit;
    };


header("Content-type: image/jpeg");
header('Expires: ' . date('r',time() + 864000));
header("Pragma: public");
header("Cache-Control: public");
header("Content-Length: " . strlen($contents));
if($entity == 'group'){
    $etag = $ee->icontime . $entity_guid;
    header("ETag: $etag");
};
echo $contents;