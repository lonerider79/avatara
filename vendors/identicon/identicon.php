<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Identicon functions
 *
 * @author Matt
 * @package avatara
 */
class Identicon {

     function __construct() {
       
   }
    
/**
 * This makes sure that the image is present (builds it if it isn't) and then
 * displays it.
 */
public static function avatar_check($entity) {

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
                if (identicon_build_group($seed, $file)) {
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
                if (identicon_build($seed, $file)) {
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

    // we can't build the icon
    return false;
}

    /** generate sprite for corners and sides */
private function identicon_getsprite($shape,$R,$G,$B,$rotation, $spriteZ) {

    $sprite=imagecreatetruecolor($spriteZ,$spriteZ);
    imageantialias($sprite,true);
    $fg=imagecolorallocate($sprite,$R,$G,$B);
    $bg=imagecolorallocate($sprite,255,255,255);
    imagefilledrectangle($sprite,0,0,$spriteZ,$spriteZ,$bg);
    switch($shape) {
        case 0: // triangle
            $shape=array(
                         0.5,1,
                         1,0,
                         1,1
                        );
            break;
        case 1: // parallelogram
            $shape=array(
                         0.5,0,
                         1,0,
                         0.5,1,
                         0,1
                        );
            break;
        case 2: // mouse ears
            $shape=array(
                         0.5,0,
                         1,0,
                         1,1,
                         0.5,1,
                         1,0.5
                        );
            break;
        case 3: // ribbon
            $shape=array(
                         0,0.5,
                         0.5,0,
                         1,0.5,
                         0.5,1,
                         0.5,0.5
                        );
            break;
        case 4: // sails
            $shape=array(
                         0,0.5,
                         1,0,
                         1,1,
                         0,1,
                         1,0.5
                        );
            break;
        case 5: // fins
            $shape=array(
                         1,0,
                         1,1,
                         0.5,1,
                         1,0.5,
                         0.5,0.5
                        );
            break;
        case 6: // beak
            $shape=array(
                         0,0,
                         1,0,
                         1,0.5,
                         0,0,
                         0.5,1,
                         0,1
                        );
            break;
        case 7: // chevron
            $shape=array(
                         0,0,
                         0.5,0,
                         1,0.5,
                         0.5,1,
                         0,1,
                         0.5,0.5
                        );
            break;
        case 8: // fish
            $shape=array(
                         0.5,0,
                         0.5,0.5,
                         1,0.5,
                         1,1,
                         0.5,1,
                         0.5,0.5,
                         0,0.5
                        );
            break;
        case 9: // kite
            $shape=array(
                         0,0,
                         1,0,
                         0.5,0.5,
                         1,0.5,
                         0.5,1,
                         0.5,0.5,
                         0,1
                        );
            break;
        case 10: // trough
            $shape=array(
                         0,0.5,
                         0.5,1,
                         1,0.5,
                         0.5,0,
                         1,0,
                         1,1,
                         0,1
                        );
            break;
        case 11: // rays
            $shape=array(
                         0.5,0,
                         1,0,
                         1,1,
                         0.5,1,
                         1,0.75,
                         0.5,0.5,
                         1,0.25
                        );
            break;
        case 12: // double rhombus
            $shape=array(
                         0,0.5,
                         0.5,0,
                         0.5,0.5,
                         1,0,
                         1,0.5,
                         0.5,1,
                         0.5,0.5,
                         0,1
                        );
            break;
        case 13: // crown
            $shape=array(
                         0,0,
                         1,0,
                         1,1,
                         0,1,
                         1,0.5,
                         0.5,0.25,
                         0.5,0.75,
                         0,0.5,
                         0.5,0.25
                        );
            break;
        case 14: // radioactive
            $shape=array(
                         0,0.5,
                         0.5,0.5,
                         0.5,0,
                         1,0,
                         0.5,0.5,
                         1,0.5,
                         0.5,1,
                         0.5,0.5,
                         0,1
                        );
            break;
        default: // tiles
            $shape=array(
                         0,0,
                         1,0,
                         0.5,0.5,
                         0.5,0,
                         0,0.5,
                         1,0.5,
                         0.5,1,
                         0.5,0.5,
                         0,1
                        );
            break;
    }

    // apply ratios
    for ($i=0;$i<count($shape);$i++) {
        $shape[$i]=$shape[$i]*$spriteZ;
    }
    imagefilledpolygon($sprite,$shape,count($shape)/2,$fg);
    // rotate the sprite
    for ($i=0;$i<$rotation;$i++) {
        $sprite=imagerotate($sprite,90,$bg);
    }

    return $sprite;
}


/** generate sprite for center block */
private function identicon_getcenter($shape,$fR,$fG,$fB,$bR,$bG,$bB,$usebg, $spriteZ) {
    $sprite=imagecreatetruecolor($spriteZ,$spriteZ);
    imageantialias($sprite,true);
    $fg=imagecolorallocate($sprite,$fR,$fG,$fB);
    /** make sure there's enough contrast before we use background color of side sprite */
    if ($usebg>0 && (abs($fR-$bR)>127 || abs($fG-$bG)>127 || abs($fB-$bB)>127)) {
        $bg=imagecolorallocate($sprite,$bR,$bG,$bB);
    } else {
        $bg=imagecolorallocate($sprite,255,255,255);
    }
    imagefilledrectangle($sprite,0,0,$spriteZ,$spriteZ,$bg);
    switch($shape) {
        case 0: // empty
            $shape=array();
            break;
        case 1: // fill
            $shape=array(
                         0,0,
                         1,0,
                         1,1,
                         0,1
                        );
            break;
        case 2: // diamond
            $shape=array(
                         0.5,0,
                         1,0.5,
                         0.5,1,
                         0,0.5
                        );
            break;
        case 3: // reverse diamond
            $shape=array(
                         0,0,
                         1,0,
                         1,1,
                         0,1,
                         0,0.5,
                         0.5,1,
                         1,0.5,
                         0.5,0,
                         0,0.5
                        );
            break;
        case 4: // cross
            $shape=array(
                         0.25,0,
                         0.75,0,
                         0.5,0.5,
                         1,0.25,
                         1,0.75,
                         0.5,0.5,
                         0.75,1,
                         0.25,1,
                         0.5,0.5,
                         0,0.75,
                         0,0.25,
                         0.5,0.5
                        );
            break;
        case 5: // morning star
            $shape=array(
                         0,0,
                         0.5,0.25,
                         1,0,
                         0.75,0.5,
                         1,1,
                         0.5,0.75,
                         0,1,
                         0.25,0.5
                        );
            break;
        case 6: // small square
            $shape=array(
                         0.33,0.33,
                         0.67,0.33,
                         0.67,0.67,
                         0.33,0.67
                        );
            break;
        case 7: // checkerboard
            $shape=array(
                         0,0,
                         0.33,0,
                         0.33,0.33,
                         0.66,0.33,
                         0.67,0,
                         1,0,
                         1,0.33,
                         0.67,0.33,
                         0.67,0.67,
                         1,0.67,
                         1,1,
                         0.67,1,
                         0.67,0.67,
                         0.33,0.67,
                         0.33,1,
                         0,1,
                         0,0.67,
                         0.33,0.67,
                         0.33,0.33,
                         0,0.33
                        );
            break;
    }
    /** apply ratios */
    for ($i=0;$i<count($shape);$i++) {
        $shape[$i]=$shape[$i]*$spriteZ;
    }
    if (count($shape)>0) {
        imagefilledpolygon($sprite,$shape,count($shape)/2,$fg);
    }
    return $sprite;
}


/** Builds the avatar. */
protected function identicon_build($seed, $file) {

    /** parse hash string */
    $csh = hexdec(substr($seed, 0, 1)); // corner sprite shape
    $ssh = hexdec(substr($seed, 1, 1)); // side sprite shape
    $xsh = hexdec(substr($seed, 2, 1))&7; // center sprite shape

    $cro = hexdec(substr($seed, 3, 1))&3; // corner sprite rotation
    $sro = hexdec(substr($seed, 4, 1))&3; // side sprite rotation
    $xbg = hexdec(substr($seed, 5, 1))%2; // center sprite background

    /** corner sprite foreground color */
    $cfr = hexdec(substr($seed, 6, 2));
    $cfg = hexdec(substr($seed, 8, 2));
    $cfb = hexdec(substr($seed, 10, 2));

    /** side sprite foreground color */
    $sfr = hexdec(substr($seed, 12, 2));
    $sfg = hexdec(substr($seed, 14, 2));
    $sfb = hexdec(substr($seed, 16, 2));

    /** final angle of rotation */
    $angle = hexdec(substr($seed, 18, 2));

    /** size of each sprite */
    $spriteZ = 128;

    /** start with blank 3x3 identicon */
    $identicon = imagecreatetruecolor($spriteZ*3, $spriteZ*3);
    imageantialias($identicon, true);

    /** assign white as background */
    $bg = imagecolorallocate($identicon, 255, 255, 255);
    imagefilledrectangle($identicon, 0, 0, $spriteZ, $spriteZ, $bg);

    /** generate corner sprites */
    $corner = identicon_getsprite($csh, $cfr, $cfg, $cfb, $cro, $spriteZ);
    imagecopy($identicon, $corner, 0, 0, 0, 0, $spriteZ, $spriteZ);
    $corner = imagerotate($corner, 90, $bg);
    imagecopy($identicon, $corner, 0, $spriteZ*2, 0, 0, $spriteZ, $spriteZ);
    $corner = imagerotate($corner, 90, $bg);
    imagecopy($identicon, $corner, $spriteZ*2, $spriteZ*2, 0, 0, $spriteZ, $spriteZ);
    $corner = imagerotate($corner, 90, $bg);
    imagecopy($identicon, $corner, $spriteZ*2, 0, 0, 0, $spriteZ, $spriteZ);

    /** generate side sprites */
    $side = identicon_getsprite($ssh, $sfr, $sfg, $sfb, $sro, $spriteZ);
    imagecopy($identicon, $side, $spriteZ, 0, 0, 0, $spriteZ, $spriteZ);
    $side = imagerotate($side, 90, $bg);
    imagecopy($identicon, $side, 0, $spriteZ, 0, 0, $spriteZ, $spriteZ);
    $side = imagerotate($side, 90, $bg);
    imagecopy($identicon, $side, $spriteZ, $spriteZ*2, 0, 0, $spriteZ, $spriteZ);
    $side = imagerotate($side, 90, $bg);
    imagecopy($identicon, $side, $spriteZ*2, $spriteZ, 0, 0, $spriteZ, $spriteZ);

    /** generate center sprite */
    $center = identicon_getcenter($xsh, $cfr, $cfg, $cfb, $sfr, $sfg, $sfb, $xbg, $spriteZ);
    imagecopy($identicon, $center, $spriteZ, $spriteZ, 0, 0, $spriteZ, $spriteZ);

    /** make white transparent */
    imagecolortransparent($identicon, $bg);

    $size = 200;

    /** create blank image according to specified dimensions */
    $resized = imagecreatetruecolor($size, $size);
    imageantialias($resized, true);

    /** assign white as background */
    $bg = imagecolorallocate($resized, 255, 255, 255);
    imagefilledrectangle($resized, 0, 0, $size, $size, $bg);

    /** resize identicon according to specification */
    imagecopyresampled($resized, $identicon, 0, 0, (imagesx($identicon)-$spriteZ*3)/2, (imagesx($identicon)-$spriteZ*3)/2, $size, $size, $spriteZ*3, $spriteZ*3);

    /** make white transparent */
    imagecolortransparent($resized, $bg);

    /** and finally, save */
    $filename = $file->getFilenameOnFilestore();
    $file->open('write');
    imagejpeg($resized, $filename);
    $file->close();
    imagedestroy($resized);
    avatara_build($filename,$seed);
    return true;
}


protected function identicon_build_group($seedbase, $file) {

    $size = 200;

    $grid = imagecreatetruecolor($size * 2, $size * 2);

    for ($i = 0; $i < 4; $i++) {

        $md5 = md5(substr($seedbase, $i * 4, 4));
        $seed = substr ($md5, 0, 17);

        /** parse hash string */
        $csh = hexdec(substr($seed, 0, 1)); // corner sprite shape
        $ssh = hexdec(substr($seed, 1, 1)); // side sprite shape
        $xsh = hexdec(substr($seed, 2, 1))&7; // center sprite shape

        $cro = hexdec(substr($seed, 3, 1))&3; // corner sprite rotation
        $sro = hexdec(substr($seed, 4, 1))&3; // side sprite rotation
        $xbg = hexdec(substr($seed, 5, 1))%2; // center sprite background

        /** corner sprite foreground color */
        $cfr = hexdec(substr($seed, 6, 2));
        $cfg = hexdec(substr($seed, 8, 2));
        $cfb = hexdec(substr($seed, 10, 2));

        /** side sprite foreground color */
        $sfr = hexdec(substr($seed, 12, 2));
        $sfg = hexdec(substr($seed, 14, 2));
        $sfb = hexdec(substr($seed, 16, 2));

        /** final angle of rotation */
        $angle = hexdec(substr($seed, 18, 2));

        /** size of each sprite */
        $spriteZ = 128;

        /** start with blank 3x3 identicon */
        $identicon = imagecreatetruecolor($spriteZ*3, $spriteZ*3);
        imageantialias($identicon, true);

        /** assign white as background */
        $bg = imagecolorallocate($identicon, 255, 255, 255);
        imagefilledrectangle($identicon, 0, 0, $spriteZ, $spriteZ, $bg);

        /** generate corner sprites */
        $corner = identicon_getsprite($csh, $cfr, $cfg, $cfb, $cro, $spriteZ);
        imagecopy($identicon, $corner, 0, 0, 0, 0, $spriteZ, $spriteZ);
        $corner = imagerotate($corner, 90, $bg);
        imagecopy($identicon, $corner, 0, $spriteZ*2, 0, 0, $spriteZ, $spriteZ);
        $corner = imagerotate($corner, 90, $bg);
        imagecopy($identicon, $corner, $spriteZ*2, $spriteZ*2, 0, 0, $spriteZ, $spriteZ);
        $corner = imagerotate($corner, 90, $bg);
        imagecopy($identicon, $corner, $spriteZ*2, 0, 0, 0, $spriteZ, $spriteZ);

        /** generate side sprites */
        $side = identicon_getsprite($ssh, $sfr, $sfg, $sfb, $sro, $spriteZ);
        imagecopy($identicon, $side, $spriteZ, 0, 0, 0, $spriteZ, $spriteZ);
        $side = imagerotate($side, 90, $bg);
        imagecopy($identicon, $side, 0, $spriteZ, 0, 0, $spriteZ, $spriteZ);
        $side = imagerotate($side, 90, $bg);
        imagecopy($identicon, $side, $spriteZ, $spriteZ*2, 0, 0, $spriteZ, $spriteZ);
        $side = imagerotate($side, 90, $bg);
        imagecopy($identicon, $side, $spriteZ*2, $spriteZ, 0, 0, $spriteZ, $spriteZ);

        /** generate center sprite */
        $center = identicon_getcenter($xsh, $cfr, $cfg, $cfb, $sfr, $sfg, $sfb, $xbg, $spriteZ);
        imagecopy($identicon, $center, $spriteZ, $spriteZ, 0, 0, $spriteZ, $spriteZ);

        /** make white transparent */
        imagecolortransparent($identicon, $bg);

        /** create blank image according to specified dimensions */
        $resized = imagecreatetruecolor($size, $size);
        imageantialias($resized, true);

        /** assign white as background */
        $bg = imagecolorallocate($resized, 255, 255, 255);
        imagefilledrectangle($resized, 0, 0, $size, $size, $bg);

        /** resize identicon according to specification */
        imagecopyresampled($resized, $identicon, 0, 0, (imagesx($identicon)-$spriteZ*3)/2, (imagesx($identicon)-$spriteZ*3)/2, $size, $size, $spriteZ*3, $spriteZ*3);

        /** make white transparent */
        imagecolortransparent($resized, $bg);

        // put this avatar into the grid in the right spot
        imagecopy($grid, $resized, ($i % 2) * $size, intval($i / 2) * $size, 0, 0, $size, $size);
    }

    /** and finally, save */
    $filename = $file->getFilenameOnFilestore();
    $file->open('write');
    imagejpeg($grid, $filename);
    $file->close();
    imagedestroy($grid);
    avatara_build($filename,$seedbase);

    return true;
}

}
