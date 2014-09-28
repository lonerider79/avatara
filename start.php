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
    $action_path = elgg_get_plugins_path() . 'avatara/actions/avatara/';
        // Register a page handler so we can have nice URLs
    elgg_register_page_handler('avatara', 'avatara_page_handler');
    elgg_register_action('avatara/userpreference', $action_path . 'userpreference.php', 'logged_in');
    elgg_register_action('avatara/grouppreference', $action_path . 'grouppreference.php', 'logged_in');
    
    elgg_register_library('avatara', dirname(__FILE__) . '/lib/avatara.php');
//    elgg_register_library('monsterid', dirname(__FILE__) . '/vendors/monsterid/monsterid.php');

    elgg_register_plugin_hook_handler('entity:icon:url', 'user', 'avatara_usericon_hook', 900);
    $avatara_useforgroups = elgg_get_plugin_setting('avatara_useforgroups', 'avatara');
    if($avatara_useforgroups == "yes") elgg_register_plugin_hook_handler('entity:icon:url', 'group', 'avatara_groupicon_hook', 900);

    
}


/**
 * Avatara page handler
 *
 * @param array $page Array of url segments
 * @return bool
 */
function avatara_page_handler($page) {

        if (!isset($page[0])) {
                return false;
        }

        $base = elgg_get_plugins_path() . 'avatara/pages/avatara';

        switch ($page[0]) {
                case "avatara_user_icon": // user avatar
                        set_input('user_guid', $page[1]);
                        set_input('size', elgg_extract(2, $page, 'medium'));
                        require "$base/avatara_user_icon.php";
                        break;
                case "avatara_group_icon": // group avatar
                        set_input('group_guid', $page[1]);
                        set_input('size', elgg_extract(2, $page, 'medium'));
                        require "$base/avatara_group_icon.php";
                        break;
                default:
                        return false;
        }

        return true;
}

/**
 * This hooks into the getIcon API
 *
 * @param unknown_type $hook
 * @param unknown_type $entity_type
 * @param unknown_type $returnvalue
 * @param unknown_type $params
 * @return unknown
 */
function avatara_usericon_hook($hook, $entity_type, $returnvalue, $params) {

    if (($hook == 'entity:icon:url') && ($params['entity'] instanceof ElggUser)) {
        $ent = $params['entity'];
        // if we don't have an icon or the user just prefers the avatar
        if ($ent->preferAvatara != '' || !($ent->icontime)) {
            return avatara_url($ent, $params['size']);
        }
    } else {
        return $returnvalue;
    }
}


function avatara_groupicon_hook($hook, $entity_type, $returnvalue, $params) {

    if (($hook == 'entity:icon:url') && ($params['entity'] instanceof ElggGroup)) {
        $ent = $params['entity'];
        // if we don't have an icon or the user just prefers the avatar
        if ($ent->preferGroupAvatara != '' || !($ent->icontime)) {
            return avatara_url($ent, $params['size']);
        }
    } else {
        return $returnvalue;
    }
}


function avatara_url($ent, $size, $avatar = '') {
    $status = false;
    
    if ($ent instanceof ElggUser) {
        if($avatar == ''){ //non preview mode
            $user = elgg_get_logged_in_user_entity();
            if(!is_null($user)) return FALSE;
        }
        $avatarsel = trim($user->preferAvatara);
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
    } else if ($ent instanceof ElggGroup) {
        if (identicon_check($ent)) {
            
        }
        if(status) return elgg_get_site_url() . 'avatara/avatara_group_icon/' . $ent->getGUID() . '/' . $size;
    }

    return false;
}
?>

