<?php

$user_guid = (int)get_input('user_guid');
$user = get_entity($user_guid);

    $avatara_useIdenticon = elgg_get_plugin_setting('avatara_useIdenticon', 'avatara');
    $avatara_usemonsterId = elgg_get_plugin_setting('avatara_usemonsterId', 'avatara');
    $avatara_usewavatar = elgg_get_plugin_setting('avatara_usewavatar', 'avatara');
    $avatara_useforgroups = elgg_get_plugin_setting('avatara_useforgroups', 'avatara');

$pref = get_input('preferAvatara', false);
if (is_array($pref)){
        $pref = $pref[0];
}

if ($pref) {
  $user->preferAvatara = $pref;
  unset($user->icontime);
  system_message(elgg_echo('identicon:identicon_yes'));
} else {
  $user->preferAvatara = 'Elgg Default';

  $filehandler = new ElggFile();
  $filehandler->owner_guid = $user->guid;
  $filehandler->setFilename("profile/{$user->guid}master.jpg");

  if ($filehandler->open("read")) {
    if ($contents = $filehandler->read($filehandler->size())) {
      $user->icontime = time();
    } else {
      unset($user->icontime);
    }
  }

  system_message(elgg_echo('avatara:identicon_no'));
}

forward(REFERER);