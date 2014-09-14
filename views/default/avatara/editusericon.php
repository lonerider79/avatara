<?php

$user = $vars['entity'];

if ($user) {

    // call the hook directly to avoid overrides and other logic
    $wav = avatara_url($user, 'large');

    $img = '<img src="' . $wav . '" alt="AvatarA" />';

    $check = elgg_view('input/radio', array('name' => 'preferAvatara',
                                                'align' => 'horizontal',
                                                 'options' => array('Identicon','MonsterId','Wavatar','Gravatar','Elgg Default'),
                                                 'value' => $user->preferAvatara));

    $submit = elgg_view('input/submit', array('value' => elgg_echo('save')));

    $form = elgg_view('input/form', array('action' =>  elgg_get_site_url() . "action/avatara/userpreference?user_guid={$user->guid}", 'body' => $img . "\n" . $check . "\n<br>" . $submit));

?>

    <div id="avatar-croppingtool" class="mtl ptm">
        <label><?php echo elgg_echo('avatara:title'); ?></label>
        <p>
            <?php echo elgg_echo('avatara:explanation'); ?>
        </p>
        <?php echo elgg_view_layout('default', array('content' => $form)); ?>
    </div>

<?php
}
