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
 * 
 */
function avatara_build($filename){
    
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


