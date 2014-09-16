<div class="plugin-panel">
<?php

    /**
     * AvatarA Administration Page. Uses default action submitter
     * @package avatara
     * 
     */
$noyes_options = array(
	"no" => elgg_echo("option:no"),
	"yes" => elgg_echo("option:yes")
);
    $avatara_useIdenticon = elgg_get_plugin_setting('avatara_useIdenticon', 'avatara');
    $avatara_usemonsterId = elgg_get_plugin_setting('avatara_usemonsterId', 'avatara');
    $avatara_usewavatar = elgg_get_plugin_setting('avatara_usewavatar', 'avatara');
    $avatara_useforgroups = elgg_get_plugin_setting('avatara_useforgroups', 'avatara');
    
    echo "<h4>";
    echo elgg_echo('avatara:admin:title:avatara');
    echo "</h4><br/>";
    echo '<label>' . elgg_echo('avatara:admin:useidenticon') . ':' . '</label>';
    echo "<br />";
    echo elgg_view("input/dropdown", array("name" => "params[avatara_useIdenticon]", "options_values" =>$noyes_options, "value" => $avatara_useIdenticon, "class" => "mls"));
    echo "<div class='elgg-subtext'>" . elgg_echo("avatara:admin:useidenticon:description") . "</div>";
    echo "<br />";
    echo '<label>' . elgg_echo('avatara:admin:usemonsterid') . ':' . '</label>';
    echo "<br />";
    echo elgg_view("input/dropdown", array("name" => "params[avatara_usemonsterId]", "options_values" =>$noyes_options, "value" => $avatara_usemonsterId, "class" => "mls"));
    echo "<div class='elgg-subtext'>" . elgg_echo("avatara:admin:usemonsterid:description") . "</div>";
    echo "<br />";
    echo '<label>' . elgg_echo('avatara:admin:usewavatar') . ':' . '</label>';
    echo "<br />";
    echo elgg_view("input/dropdown", array("name" => "params[avatara_usewavatar]", "options_values" =>$noyes_options, "value" => $avatara_usewavatar, "class" => "mls"));
    echo "<div class='elgg-subtext'>" . elgg_echo("avatara:admin:usewavatar:description") . "</div>";
    echo "<br />";
    echo '<label>' . elgg_echo('avatara:admin:useforgroups') . ':' . '</label>';
    echo "<br />";
    echo elgg_view("input/dropdown", array("name" => "params[avatara_useforgroups]", "options_values" =>$noyes_options, "value" => $avatara_useforgroups, "class" => "mls"));
    echo "<div class='elgg-subtext'>" . elgg_echo("avatara:admin:useforgroups:description") . "</div>";
    echo "<br />";

?>
</div>
