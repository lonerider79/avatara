<?php

if(!empty($vars["entity"])) {
    $group = $vars['entity'];
    $group_guid = $group->guid;
    $avatara_useIdenticon = (trim(elgg_get_plugin_setting('avatara_useIdenticon', 'avatara')) =='') ? "no" : elgg_get_plugin_setting('avatara_useIdenticon', 'avatara');
    $avatara_usemonsterId = (trim(elgg_get_plugin_setting('avatara_usemonsterId', 'avatara')) == '') ? "no" : elgg_get_plugin_setting('avatara_usemonsterId', 'avatara');
    $avatara_usewavatar = (trim(elgg_get_plugin_setting('avatara_usewavatar', 'avatara')) == '') ? "no" : elgg_get_plugin_setting('avatara_usewavatar', 'avatara');


   
   

    $img = "<table border=0 cellpadding=0 cellspacing=0><tr>";
    if ($avatara_useIdenticon == "yes") $img .= '<td><img src="' . avatara_url($group, 'large','Identicon') . '" alt="AvatarA-Identicon" /></td>';
    if ($avatara_usemonsterId == "yes") $img .= '<td><img src="' . avatara_url($group, 'large','MonsterId') . '" alt="AvatarA-MonsterId" /></td>';
    if ($avatara_usewavatar == "yes") $img .= '<td><img src="' . avatara_url($group, 'large','Wavatar') . '" alt="AvatarA-Wavatar" /></td>';
    
    $img .= '</tr></table>';

    $check = elgg_view('input/radio', array('name' => 'preferGroupAvatara','align' => 'horizontal',
                                                 'options' => array('Identicon','MonsterId','Wavatar','Gravatar','Elgg Default'),
                                                 'value' => $user->preferGroupAvatara));

    $submit = elgg_view('input/submit', array('value' => elgg_echo('save')));

    $form = elgg_view('input/form', array('action' =>  elgg_get_site_url() . "action/avatara/grouppreference?group_guid=$group_guid", 'body' => $img . "\n" . $check . "\n<br>" . $submit));

?>

    <div id="avatar-croppingtool" class="mtl ptm">
        <label><?php echo elgg_echo('avatara:title'); ?></label>
        <p>
            <?php echo elgg_echo('avatara:group_explanation'); ?>
        </p>
        <?php echo elgg_view_layout('default', array('content' => $form)); ?>
    </div>

<?php
}
