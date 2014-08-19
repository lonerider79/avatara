<?php


/**
 * avatara - Avatar Automatic plugin for Elgg 1.8
 * @license GNU Public License version 3 (other licenses may apply for respective plugins)
 * @author Vinu Felix <vinu.felix@gmail.com>
 * @package avatara
 */

// call init for plugin
elgg_register_event_handler('init','system','avatara_init');

function avatara_init() {
    $action_path = elgg_get_plugins_path() . 'avatara/actions';
    
}
?>

