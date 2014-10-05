<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



/**
 * builds a standard seed from the entity's email field (if a user) or the name (if a group)
 * if neither is available, use the guid
 */
function avatara_seed($entity) {

    if ($entity instanceof ElggUser) {
        $start = strtolower($entity->email);
    } else if ($entity instanceof ElggGroup) {
        $start = strtolower($entity->name);
    }

    if (!$start) {
        $start = (string) $entity->getGUID();
    }

    $md5 = md5($start);
    $seed = substr($md5, 0, 17);
    return $seed;
}
/* Builds the all sizes avatar icons for the newly created avatar
 * Used by individual avatar image functions
 * 
 */
function avatara_build($filename,$seed){
    
    $icon_sizes = elgg_get_config('icon_sizes');

    $topbar = get_resized_image_from_existing_file($filename, $icon_sizes['topbar']['w'], $icon_sizes['topbar']['h'], $icon_sizes['topbar']['square']);
    $tiny = get_resized_image_from_existing_file($filename, $icon_sizes['tiny']['w'], $icon_sizes['tiny']['h'], $icon_sizes['tiny']['square']);
    $small = get_resized_image_from_existing_file($filename, $icon_sizes['small']['w'], $icon_sizes['small']['h'], $icon_sizes['small']['square']);
    $medium = get_resized_image_from_existing_file($filename, $icon_sizes['medium']['w'], $icon_sizes['medium']['h'], $icon_sizes['medium']['square']);
    $large = get_resized_image_from_existing_file($filename, $icon_sizes['large']['w'], $icon_sizes['large']['h'], $icon_sizes['large']['square']);

    $file->setFilename('avatara/' . $seed . '/large.jpg');
    $file->open('write');
    $file->write($large);
    $file->close();
    $file->setFilename('avatara/' . $seed . '/medium.jpg');
    $file->open('write');
    $file->write($medium);
    $file->close();
    $file->setFilename('avatara/' . $seed . '/small.jpg');
    $file->open('write');
    $file->write($small);
    $file->close();
    $file->setFilename('avatara/' . $seed . '/tiny.jpg');
    $file->open('write');
    $file->write($tiny);
    $file->close();
    $file->setFilename('avatara/' . $seed . '/topbar.jpg');
    $file->open('write');
    $file->write($topbar);
    $file->close();


}
/*
 * Returns the url for the avatar for the user.
 * If the third parameter is specified preview of the avatar is made and the avatar image is not saved
 * 
 * @param object $ent   Passes the user/group entity for which the avatar is to be generated
 * @param int   $size   The size for the avatar to be generated(default medium 200
 * @param string    $avatar This could  be null if the avatar images are to be generated otherwise specify the avatar(Identicon,Wavatar,MonsterId)
 * 
 * @return string The URL to the plugin view which will display the avatar
 *   
 */
function avatara_url($ent, $size, $avatar = NULL) {
    $status = false;
    
    if ($ent instanceof ElggUser) {
        if(is_null($avatar)) { //non preview mode
        $avatarsel = trim($ent->preferAvatara);
        switch($avatarsel) {
        case 'Identicon': 
           $status = Identicon::avatar_check($ent); 
           break;
        case 'MonsterId':
           $status = MonsterId::avatar_check($ent); 
           break;
        case 'Wavatar':
           $status = Wavatar::avatar_check($ent); 
           break;
        case 'Elgg Default': //avatara option removed
        default:
            return FALSE;
        };
        
        if($status) return elgg_get_site_url() .'avatara/avatara_user_icon/' . $ent->getGUID() . '/' . $size;
        
        } else { //preview
            switch($avatarsel) {
            case 'Identicon': 
               //$status = Identicon::preview($ent); 
               return elgg_get_site_url() .'avatara/preview/user/' . $ent->getGUID() . '/Identicon'; 
               break;
            case 'MonsterId':
               //$status = MonsterId::preview($ent); 
               return elgg_get_site_url() .'avatara/preview/user/' . $ent->getGUID() . '/MonsterId';
               break;
            case 'Wavatar':
               //$status = Wavatar::preview($ent); 
               return elgg_get_site_url() .'avatara/preview/user/' . $ent->getGUID() . '/Wavatar';
               break;
            case 'Elgg Default': //avatara option removed
            default:
                return FALSE;
            };
            
        };
    } else if ($ent instanceof ElggGroup) {
        if(is_null($avatar)) { //non preview mode
        $avatarsel = trim($ent->preferGroupAvatara);
        switch($avatarsel) {
        case 'Identicon': 
           $status = Identicon::avatar_check($ent); 
           break;
        case 'MonsterId':
           $status = MonsterId::avatar_check($ent); 
           break;
        case 'Wavatar':
           $status = Wavatar::avatar_check($ent); 
           break;
        case 'Elgg Default': //avatara option removed
        default:
            return FALSE;
        };
        
        if(status) return elgg_get_site_url() . 'avatara/avatara_group_icon/' . $ent->getGUID() . '/' . $size;
        
        } else { //preview
            switch($avatarsel) {
            case 'Identicon': 
               //$status = Identicon::preview($ent); 
               return elgg_get_site_url() .'avatara/preview/group/' . $ent->getGUID() . '/Identicon'; 
               break;
            case 'MonsterId':
               //$status = MonsterId::preview($ent); 
               return elgg_get_site_url() .'avatara/preview/group/' . $ent->getGUID() . '/MonsterId';
               break;
            case 'Wavatar':
               //$status = Wavatar::preview($ent); 
               return elgg_get_site_url() .'avatara/preview/group/' . $ent->getGUID() . '/Wavatar';
               break;
            case 'Elgg Default': //avatara option removed
            default:
                return FALSE;
            };
            
        };

        
    }

    return false;
}
