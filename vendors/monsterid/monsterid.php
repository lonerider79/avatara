<?php



class MonsterId {
    function __construct() {
       
   }

const MONSTER_LEGS ='5';
const MONSTER_HAIR ='5';
const MONSTER_ARMS = '5';
const MONSTER_BODY = '15';
const MONSTER_EYES = '15'; 
const MONSTER_MOUTH = '10';

public static function avatar_check($entity){

    //make sure the image functions are available before trying to make avatars
    if (function_exists(imagecreatetruecolor)) {
        // entity is group, user or something else?
        if ($entity instanceof ElggGroup) {
            $file = new ElggFile();
            $file->owner_guid = $entity->owner_guid;

            $seed = avatara_seed($entity);
            $file->setFilename('avatara/' . $seed . '/master.jpg');
            $file->setMimeType('image/jpeg');

            if (!$file->exists()) {
                if ($this->monsterid_build_group($seed, $file)) {
                    return true;
                } else {
                    // there was some error building the icon
                    return false;
                }
            } else {
                // file's already there
                return true;
            }
        } else if ($entity instanceof ElggUser) {
            $file = new ElggFile();
            $file->owner_guid = $entity->getGUID();

            $seed = avatara_seed($entity);
            $file->setFilename('avatara/' . $seed . '/master.jpg');
            $file->setMimeType('image/jpeg');

            if (!$file->exists()) {
                if ($this->monsterid_build($seed, $file)) {
                    return true;
                } else {
                    // there was some error building the icon
                    return false;
                }
            } else {
                // file's already there
                return true;
            }
        } else {
            // neither group nor user
            return false;
        }
    
}  
}

/**Preview Avatar **/
public static function preview($entity) {

    //make sure the image functions are available before trying to make avatars
    if (function_exists(imagecreatetruecolor)) {
        // entity is group, user or something else?
        if ($entity instanceof ElggGroup) {

            $seed = avatara_seed($entity);

                if ($this->monsterid_build_group($seed)) {
                    return true;
                } else {
                    // there was some error building the icon
                    return false;
                }

        } else if ($entity instanceof ElggUser) {

            $seed = avatara_seed($entity);

                if ($this->monsterid_build($seed)) {
                    return true;
                } else {
                    // there was some error building the icon
                    return false;
                }
        } else {
            // neither group nor user
            return false;
        }
    }

    
}


   //Create monster image 
function monsterid_build($seed='',$file=NULL){
    // init random seed
    /**if($seed) srand( hexdec(substr(md5($seed),0,6)) );**/


    // throw the dice for body parts
   /** $parts = array(
        'legs' => rand(1,5),
        'hair' => rand(1,5),
        'arms' => rand(1,5),
        'body' => rand(1,15),
        'eyes' => rand(1,15),
        'mouth'=> rand(1,10)
    ); **/


$parts = array(
        'legs' => 1 + (hexdec (substr ($seed,  1, 2)) % (self::MONSTER_LEGS)),
        'hair' => 1 + (hexdec (substr ($seed,  5, 2)) % (self::MONSTER_HAIR)),
        'arms' => 1 + (hexdec (substr ($seed,  9, 2)) % (self::MONSTER_ARMS)),
        'body' => 1 + (hexdec (substr ($seed, 11, 2)) % (self::MONSTER_BODY)),
        'eyes' => 1 + (hexdec (substr ($seed, 13, 2)) % (self::MONSTER_EYES)),
        'mouth'=> 1 + (hexdec (substr ($seed, 15, 2)) % (self::MONSTER_MOUTH))
    );
    // create backgound
    $monster = @imagecreatetruecolor(120, 120)
        or die("GD image create failed");
    $white   = imagecolorallocate($monster, 255, 255, 255);
    imagefill($monster,0,0,$white);

    // add parts
    foreach($parts as $part => $num){
        $file = dirname(__FILE__).'/parts/'.$part.'_'.$num.'.png';

        $im = @imagecreatefrompng($file);
        if(!$im) die('Failed to load '.$file);
        imageSaveAlpha($im, true);
        imagecopy($monster,$im,0,0,0,0,120,120);
        imagedestroy($im);

        // color the body
        if($part == 'body'){
            $color = imagecolorallocate($monster, rand(20,235), rand(20,235), rand(20,235));
            imagefill($monster,60,60,$color);
        }
    }

    // restore random seed
    //if($seed) srand();
    $size = 200;
    // resize if needed, then output
    $out = @imagecreatetruecolor($size,$size)
        or die("GD image create failed");
    
    imagecopyresampled($out,$monster,0,0,0,0,$size,$size,120,120);
    if(!is_null($file)) {
        $filename = $file->getFilenameOnFilestore();
        $file->open('write');
        imagejpeg($out,$filename);
        $file->close();
        imagedestroy($out);
        imagedestroy($monster);
        avatara_build($filename,$seed);
        return true;
    }else{
        ob_start();
        imagejpeg($out);
        $image = ob_get_contents();
        ob_end_clean();
        imagedestroy($out);
        imagedestroy($monster);
        return $image;
    }
    return false;
}
function monsterid_build_group($seed='',$file=NULL){
    // init random seed
    /**if($seed) srand( hexdec(substr(md5($seed),0,6)) );**/


$parts = array(
        'legs' => 1 + (hexdec (substr ($seed,  1, 2)) % (self::MONSTER_LEGS)),
        'hair' => 1 + (hexdec (substr ($seed,  5, 2)) % (self::MONSTER_HAIR)),
        'arms' => 1 + (hexdec (substr ($seed,  9, 2)) % (self::MONSTER_ARMS)),
        'body' => 1 + (hexdec (substr ($seed, 11, 2)) % (self::MONSTER_BODY)),
        'eyes' => 1 + (hexdec (substr ($seed, 13, 2)) % (self::MONSTER_EYES)),
        'mouth'=> 1 + (hexdec (substr ($seed, 15, 2)) % (self::MONSTER_MOUTH))
    );
    // create backgound
    $monster = @imagecreatetruecolor(120, 120)
        or die("GD image create failed");
    $white   = imagecolorallocate($monster, 255, 255, 255);
    imagefill($monster,0,0,$white);

    // add parts
    foreach($parts as $part => $num){
        $file = dirname(__FILE__).'/parts/'.$part.'_'.$num.'.png';

        $im = @imagecreatefrompng($file);
        if(!$im) die('Failed to load '.$file);
        imageSaveAlpha($im, true);
        imagecopy($monster,$im,0,0,0,0,120,120);
        imagedestroy($im);

        // color the body
        if($part == 'body'){
            $color = imagecolorallocate($monster, rand(20,235), rand(20,235), rand(20,235));
            imagefill($monster,60,60,$color);
        }
    }

    // restore random seed
    //if($seed) srand();
    $size = 200;
    // resize if needed, then output
    $out = @imagecreatetruecolor($size,$size)
        or die("GD image create failed");
    
    imagecopyresampled($out,$monster,0,0,0,0,$size,$size,120,120);
    if(!is_null($file)) {
        $filename = $file->getFilenameOnFilestore();
        $file->open('write');
        imagejpeg($out,$filename);
        $file->close();
        imagedestroy($out);
        imagedestroy($monster);
        avatara_build($filename,$seed);
        return true;
    }else{
        ob_start();
        imagejpeg($out);
        $image = ob_get_contents();
        ob_end_clean();
        imagedestroy($out);
        imagedestroy($monster);
        return $image;        
    }
}
}