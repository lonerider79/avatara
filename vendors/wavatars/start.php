<?php

  /**
   * Elgg Wavatar plugin.
   *
   * Based off of the original Wavatar WordPress plugin from Shamus Young:
   *   http://www.shamusyoung.com/twentysidedtale/?p=1462
   * 
   * @author Justin Richer
   * @copyright The MITRE Corporation 2009
   * @link http://mitre.org/
   */

function wavatar_init() {
			
	// Get config
	global $CONFIG;

	extend_view('profile/editicon', 'wavatar/editicon');
			
	register_action('wavatar/preference', false, $CONFIG->pluginspath . 'wavatar/actions/preference.php');

	register_plugin_hook('entity:icon:url', 'user', 'wavatar_usericon_hook', 900); 
	register_plugin_hook('entity:icon:url', 'group', 'wavatar_groupicon_hook', 900); 

}

define('WAVATAR_SIZE',           '80');
define('WAVATAR_BACKGROUNDS',   '4');
define('WAVATAR_FACES',         '11');
define('WAVATAR_BROWS',         '8');
define('WAVATAR_EYES',          '13');
define('WAVATAR_PUPILS',        '11');
define('WAVATAR_MOUTHS',        '19');

/*-----------------------------------------------------------------------------
 Handy function for converting hus/sat/lum color values to RGB, which makes it
 very easy to generate random-yet-still-vibrant colors.
 -----------------------------------------------------------------------------*/

function wavatar_hsl ($h, $s, $l) 
{

	if ($h>240 || $h<0) return array(0,0,0);
	if ($s>240 || $s<0) return array(0,0,0);
	if ($l>240 || $l<0) return array(0,0,0);     
	if ($h<=40) {
		$R=255;
		$G=(int)($h/40*256);
		$B=0;
	} elseif ($h>40 && $h<=80) {
		$R=(1-($h-40)/40)*256;
		$G=255;
		$B=0;
	} elseif ($h>80 && $h<=120) {
		$R=0;
		$G=255;
		$B=($h-80)/40*256;
	} elseif ($h>120 && $h<=160) {
		$R=0;
		$G=(1-($h-120)/40)*256;
		$B=255;
	} elseif ($h>160 && $h<=200) {
		$R=($h-160)/40*256;
		$G=0;
		$B=255;
	} elseif ($h>200) {
		$R=255;
		$G=0;
		$B=(1-($h-200)/40)*256;
	}
	$R=$R+(240-$s)/240*(128-$R);
	$G=$G+(240-$s)/240*(128-$G);
	$B=$B+(240-$s)/240*(128-$B);
	if ($l<120) {
		$R=($R/120)*$l;
		$G=($G/120)*$l;
		$B=($B/120)*$l;
	} else {
		$R=$l*((256-$R)/120)+2*$R-256;
		$G=$l*((256-$G)/120)+2*$G-256;
		$B=$l*((256-$B)/120)+2*$B-256;
	}
	if ($R<0) $R=0;
	if ($R>255) $R=255;
	if ($G<0) $G=0;
	if ($G>255) $G=255;
	if ($B<0) $B=0;
	if ($B>255) $B=255;
	return array((int)$R,(int)$G,(int)$B);

}

/*-----------------------------------------------------------------------------
 Helper function for building a wavatar.  This loads an image and adds it to 
 our composite using the given color values.
 -----------------------------------------------------------------------------*/

function wavatar_apply_image ($base, $part)
{
  
	$file = dirname(__FILE__).'/parts/' . $part . '.png';
	//echo $file . '<br>';
	$im = @imagecreatefrompng($file);
	if(!$im)
		return;
	imageSaveAlpha($im, true);
	imagecopy($base,$im, 0, 0, 0, 0, WAVATAR_SIZE, WAVATAR_SIZE);
	imagedestroy($im);

}

/*-----------------------------------------------------------------------------
 Builds the avatar.
 -----------------------------------------------------------------------------*/

function wavatar_build ($seed, $file)
{

	$face =         1 + (hexdec (substr ($seed,  1, 2)) % (WAVATAR_FACES));
	$bg_color =         (hexdec (substr ($seed,  3, 2)) % 240);
	$fade =         1 + (hexdec (substr ($seed,  5, 2)) % (WAVATAR_BACKGROUNDS));
	$wav_color =        (hexdec (substr ($seed,  7, 2)) % 240);
	$brow =         1 + (hexdec (substr ($seed,  9, 2)) % (WAVATAR_BROWS));
	$eyes =         1 + (hexdec (substr ($seed, 11, 2)) % (WAVATAR_EYES));
	$pupil =        1 + (hexdec (substr ($seed, 13, 2)) % (WAVATAR_PUPILS));
	$mouth =        1 + (hexdec (substr ($seed, 15, 2)) % (WAVATAR_MOUTHS));
	//echo "<div>face=$face fade=$fade brow=$brow eyes=$eyes $pupil mouth=$mouth<br></div>";
	//echo "<div><p>$seed</p></div>";
	// create backgound
	$avatar = imagecreatetruecolor (WAVATAR_SIZE, WAVATAR_SIZE);
	//Pick a random color for the background
	$c = wavatar_hsl ($bg_color, 240, 50);
	$bg = imagecolorallocate ($avatar, $c[0], $c[1], $c[2]);
	imagefill($avatar,0,0,$bg);
	$c = wavatar_hsl ($wav_color, 240, 170);
	$fg = imagecolorallocate ($avatar, $c[0], $c[1], $c[2]);
	//Now add the various layers onto the image
	wavatar_apply_image ($avatar, "fade$fade");
	wavatar_apply_image ($avatar, "mask$face");
	imagefill($avatar, WAVATAR_SIZE / 2,WAVATAR_SIZE / 2,$fg);
	wavatar_apply_image ($avatar, "shine$face");
	wavatar_apply_image ($avatar, "brow$brow");
	wavatar_apply_image ($avatar, "eyes$eyes");
	wavatar_apply_image ($avatar, "pupils$pupil");
	wavatar_apply_image ($avatar, "mouth$mouth");
//     //resize if needed
//     if ($size != WAVATAR_SIZE) {
//         $out = imagecreatetruecolor($size,$size);
//         imagecopyresampled ($out,$avatar, 0, 0, 0, 0, $size, $size, WAVATAR_SIZE, WAVATAR_SIZE);
//         //header ("Content-type: image/png");
//         imagepng($out, $filename);
//         imagedestroy($out);
//         imagedestroy($avatar);
//     } else {
//         imagepng($avatar, $filename);
//         imagedestroy($avatar);
//     }
    
	$filename = $file->getFilenameOnFilestore();
	//print $filename;
	//print_r($file->getFilestore()->make_file_matrix($file->getOwnerEntity()->username));
	//print_r($file->getOwnerEntity()->username);
	imagejpeg($avatar, $filename);
	imagedestroy($avatar);

	$topbar = get_resized_image_from_existing_file($filename, 16, 16, true);
	$tiny = get_resized_image_from_existing_file($filename, 25, 25, true);
	$small = get_resized_image_from_existing_file($filename, 40, 40, true);
	$medium = get_resized_image_from_existing_file($filename, 100, 100, true);
	$large = get_resized_image_from_existing_file($filename, 200, 200);
    
	$file->setFilename('wavatar/' . $seed . '/large.jpg');
	$file->open('write');
	$file->write($large);
	$file->close();
	$file->setFilename('wavatar/' . $seed . '/medium.jpg');
	$file->open('write');
	$file->write($medium);
	$file->close();
	$file->setFilename('wavatar/' . $seed . '/small.jpg');
	$file->open('write');
	$file->write($small);
	$file->close();
	$file->setFilename('wavatar/' . $seed . '/tiny.jpg');
	$file->open('write');
	$file->write($tiny);
	$file->close();
	$file->setFilename('wavatar/' . $seed . '/topbar.jpg');
	$file->open('write');
	$file->write($topbar);
	$file->close();
            
	return true;

}

function wavatar_build_group ($seedbase, $file)
{

	// build a four-up

	$grid = imagecreatetruecolor(WAVATAR_SIZE * 2, WAVATAR_SIZE * 2);

	/*
	$bg_color =         (hexdec (substr ($seedbase,  3, 2)) % 240);
	$c = wavatar_hsl ($bg_color, 240, 50);
	$bg = imagecolorallocate ($avatar, $c[0], $c[1], $c[2]);
	imagefill($avatar,0,0,$bg);
	*/

	for ($i = 0; $i < 4; $i++) {

		$md5 = md5(substr($seedbase, $i * 4, 4));
		$seed = substr ($md5, 0, 17);

		$face =         1 + (hexdec (substr ($seed,  1, 2)) % (WAVATAR_FACES));
		$bg_color =         (hexdec (substr ($seed,  3, 2)) % 240);
		$fade =         1 + (hexdec (substr ($seed,  5, 2)) % (WAVATAR_BACKGROUNDS));
		$wav_color =        (hexdec (substr ($seed,  7, 2)) % 240);
		$brow =         1 + (hexdec (substr ($seed,  9, 2)) % (WAVATAR_BROWS));
		$eyes =         1 + (hexdec (substr ($seed, 11, 2)) % (WAVATAR_EYES));
		$pupil =        1 + (hexdec (substr ($seed, 13, 2)) % (WAVATAR_PUPILS));
		$mouth =        1 + (hexdec (substr ($seed, 15, 2)) % (WAVATAR_MOUTHS));
		//echo "<div>face=$face fade=$fade brow=$brow eyes=$eyes $pupil mouth=$mouth<br></div>";
		//echo "<div><p>$seed</p></div>";
		// create backgound
		$avatar = imagecreatetruecolor (WAVATAR_SIZE, WAVATAR_SIZE);
		//Pick a random color for the background
		$c = wavatar_hsl ($bg_color, 240, 50);
		$bg = imagecolorallocate ($avatar, $c[0], $c[1], $c[2]);
		imagefill($avatar,0,0,$bg);
		$c = wavatar_hsl ($wav_color, 240, 170);
		$fg = imagecolorallocate ($avatar, $c[0], $c[1], $c[2]);
		//Now add the various layers onto the image
		wavatar_apply_image ($avatar, "fade$fade");
		wavatar_apply_image ($avatar, "mask$face");
		imagefill($avatar, WAVATAR_SIZE / 2,WAVATAR_SIZE / 2,$fg);
		wavatar_apply_image ($avatar, "shine$face");
		wavatar_apply_image ($avatar, "brow$brow");
		wavatar_apply_image ($avatar, "eyes$eyes");
		wavatar_apply_image ($avatar, "pupils$pupil");
		wavatar_apply_image ($avatar, "mouth$mouth");

		// put this avatar into the grid in the right spot
		imagecopy($grid, $avatar, ($i % 2) * WAVATAR_SIZE, intval($i / 2) * WAVATAR_SIZE,
			  0, 0, WAVATAR_SIZE, WAVATAR_SIZE);

	}
//     //resize if needed
//     if ($size != WAVATAR_SIZE) {
//         $out = imagecreatetruecolor($size,$size);
//         imagecopyresampled ($out,$avatar, 0, 0, 0, 0, $size, $size, WAVATAR_SIZE, WAVATAR_SIZE);
//         //header ("Content-type: image/png");
//         imagepng($out, $filename);
//         imagedestroy($out);
//         imagedestroy($avatar);
//     } else {
//         imagepng($avatar, $filename);
//         imagedestroy($avatar);
//     }
    
	$filename = $file->getFilenameOnFilestore();
	//print $filename;
	//print_r($file->getFilestore()->make_file_matrix($file->getOwnerEntity()->username));
	//print_r($file->getOwnerEntity()->username);
	imagejpeg($grid, $filename);
	imagedestroy($grid);

	$topbar = get_resized_image_from_existing_file($filename, 16, 16, true);
	$tiny = get_resized_image_from_existing_file($filename, 25, 25, true);
	$small = get_resized_image_from_existing_file($filename, 40, 40, true);
	$medium = get_resized_image_from_existing_file($filename, 100, 100, true);
	$large = get_resized_image_from_existing_file($filename, 200, 200);
    
	$file->setFilename('wavatar/' . $seedbase . '/large.jpg');
	$file->open('write');
	$file->write($large);
	$file->close();
	$file->setFilename('wavatar/' . $seedbase . '/medium.jpg');
	$file->open('write');
	$file->write($medium);
	$file->close();
	$file->setFilename('wavatar/' . $seedbase . '/small.jpg');
	$file->open('write');
	$file->write($small);
	$file->close();
	$file->setFilename('wavatar/' . $seedbase . '/tiny.jpg');
	$file->open('write');
	$file->write($tiny);
	$file->close();
	$file->setFilename('wavatar/' . $seedbase . '/topbar.jpg');
	$file->open('write');
	$file->write($topbar);
	$file->close();
            
	return true;

}

/**
 * builds a standard seed from the entity's email field
 */
function wavatar_seed($entity) {
	if ($entity instanceof ElggUser) {
		$start = strtolower ($entity->email);
	} else if ($entity instanceof ElggGroup) {
		$start = strtolower ($entity->name);
	}

	if (!$start) {
		$start = (string) $entity->getGUID();
	}

	$md5 = md5($start);
	$seed = substr ($md5, 0, 17);
	return $seed;
}

/*-----------------------------------------------------------------------------
 This makes sure that the image is present (builds it if it isn't) and then
 displays it.
 -----------------------------------------------------------------------------*/

function wavatar_check ($entity)
{

	global $CONFIG;

	//make sure the image functions are available before trying to make wavatars
	if (function_exists (imagecreatetruecolor)) {
		//make sure the cache directory is available

		$file = new ElggFile();
		$file->owner_guid = $entity->getGUID();

		$seed = wavatar_seed($entity);
		$file->setFilename('wavatar/' . $seed . '/master.jpg');
		$file->setMimeType('image/jpeg');

		if (!$file->exists()) {
			// if the wavatar doesn't exist, then build it
			if ($entity instanceof ElggGroup) {
				// groups get a special four-up icon
				if (wavatar_build_group($seed, $file)) {
					$entity->icontime = time();
					return true;
				} else {
					// there was some error building the icon
					return false;
				}
			} else {
				if (wavatar_build($seed, $file)) {
					$entity->icontime = time();
					return true;
				} else {
					// there was some error building the icon
					return false;
				}
			}
		} else {
			// file's already there
			return true;
		}
	}

	// we can't build the icon
	return false;
}

	function wavatar_usericon_hook($hook, $entity_type, $returnvalue, $params) {
		global $CONFIG;
		/** /
		 print "\n\nWAV:\n";
		 print $returnvalue;
		 print $user->preferWavatar;
		 / **/
		if (($hook == 'entity:icon:url') && ($params['entity'] instanceof ElggUser)) {
			$entity = $params['entity'];
			// if we don't have an icon or the user just prefers the wavatar
			if ($entity->preferWavatar || !$returnvalue) {
				return wavatar_url($entity, $params['size']);
			}
		} else {
			return $returnvalue;
		}
    
    
	}

	function wavatar_groupicon_hook($hook, $entity_type, $returnvalue, $params) {
		global $CONFIG;
		/*
		 print "\n\nWAV:\n";
		 print $returnvalue;
		 print $user->preferWavatar;
		*/
		if (($hook == 'entity:icon:url') && ($params['entity'] instanceof ElggGroup)) {
			$entity = $params['entity'];
			// if we don't have an icon or the user just prefers the wavatar
			if ($entity->preferWavatar || !$returnvalue) {
				return wavatar_url($entity, $params['size']);
			}
		} else {
			return $returnvalue;
		}
    
    
	}

	function wavatar_url($entity, $size) {
		global $CONFIG;
		// make sure the wavatar is built for this user
		if (wavatar_check($entity)) {
			return $CONFIG->wwwroot . 'mod/wavatar/img.php?entity=' . $entity->getGUID() . '&size=' . $size;
		} else {
			return false;
		}
	}

// Make sure the profile initialisation function is called on initialisation
	register_elgg_event_handler('init','system','wavatar_init');

	?>
