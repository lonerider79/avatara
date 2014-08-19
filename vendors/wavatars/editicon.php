<?php


$user = get_loggedin_user();

// call the hook directly to avoid overrides and other logic
$wav = wavatar_url($user, 'large');

$img = '<img src="' . $wav . '" alt="wavatar" />';

$check = elgg_view('input/checkboxes', array('internalname' => 'preferWavatar',
					     'options' => array('Prefer your Wavatar image over the default profile photo.' => 'pref'),
					     'value' => ($user->preferWavatar ? 'pref' : '')));

$submit = elgg_view('input/submit', array('value' => 'Save'));

$form = elgg_view('input/form', array('action' =>  $CONFIG->wwwroot . 'action/wavatar/preference', 
				      'body' => $img . "\n" . $check . "\n" . $submit));

echo elgg_view_title('Wavatar');
$explanation = '<p>A Wavatar is an automatically generated user icon based off of your email address. It is randomly generated to be unique for each user.</p>';

echo elgg_view('page_elements/contentwrapper', array('body' => $explanation . $form));

?>
