<?php

$user = $vars['entity'];

if ($user) {

    // call the hook directly to avoid overrides and other logic
    $wav = identicon_url($user, 'large');

    $img = '<img src="' . $wav . '" alt="identicon" />';

    $check = elgg_view('input/checkboxes', array('name' => 'preferIdenticon',
                                                 'options' => array(elgg_echo('identicon:preference_checkbox') => true),
                                                 'value' => ($user->preferIdenticon ? true : false)));

    $submit = elgg_view('input/submit', array('value' => elgg_echo('save')));

    $form = elgg_view('input/form', array('action' =>  elgg_get_site_url() . "action/identicon/userpreference?user_guid={$user->guid}", 'body' => $img . "\n" . $check . "\n<br>" . $submit));

?>

    <div id="avatar-croppingtool" class="mtl ptm">
        <label><?php echo elgg_echo('identicon:title'); ?></label>
        <p>
            <?php echo elgg_echo('identicon:explanation'); ?>
        </p>
        <?php echo elgg_view_layout('default', array('content' => $form)); ?>
    </div>

<?php
}
