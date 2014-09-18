<?php

$group_guid = get_input('group_guid');

    $avatara_useIdenticon = elgg_get_plugin_setting('avatara_useIdenticon', 'avatara');
    $avatara_usemonsterId = elgg_get_plugin_setting('avatara_usemonsterId', 'avatara');
    $avatara_usewavatar = elgg_get_plugin_setting('avatara_usewavatar', 'avatara');
    $avatara_useforgroups = elgg_get_plugin_setting('avatara_useforgroups', 'avatara');

if($group_guid && $avatara_useforgroups == "yes") {
    $group = get_entity($group_guid);

    $pref = get_input('preferGroupAvatara', false);
    if (is_array($pref)){
        $pref = $pref[0];
    }

    if ($pref) {
        $group->preferGroupAvatara = $pref;
        unset($group->icontime);
        system_message(elgg_echo('identicon:group_identicon_yes'));
    } else {
        $group->preferGroupAvatara = '';
        $group->icontime = time();
        system_message(elgg_echo('identicon:group_identicon_no'));
    }
}

forward(REFERER);