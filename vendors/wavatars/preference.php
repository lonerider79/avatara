<?php

  // must be logged in
gatekeeper();

// Get the logged in user
$user = get_loggedin_user();

$pref = get_input('preferWavatar');

if (in_array('pref', $pref)) {
  $user->preferWavatar = true;
  system_message('Your account will now use the Wavatar image (as long as nothing else overrides it).');
} else {
  $user->preferWavatar = false;
  system_message('Your account will no longer use the Wavatar image (unless nothing else overrides it).');
}


forward($_SERVER['HTTP_REFERER']); // send us back

?>