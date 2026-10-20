<?php
/**
 * Setting Lexicon Entries for emo
 *
 * @package emo
 * @subpackage lexicon
 */

$_lang['setting_emo.adresses_tpl'] = 'Addresses Chunk';
$_lang['setting_emo.adresses_tpl_desc'] = 'Name of a chunk that contains the JavaScript for the encrypted addresses at the end of the body.';
$_lang['setting_emo.css_path'] = 'Path to emo CSS';
$_lang['setting_emo.css_path_desc'] = 'If this setting is set, a link to this CSS path is inserted at the end of the head area of the HTML code of the current resource.';
$_lang['setting_emo.debug'] = 'Debug';
$_lang['setting_emo.debug_desc'] = 'Add debug information as an HTML comment at the end of the page source and load the uncompressed emo JavaScript.';
$_lang['setting_emo.include_scripts'] = 'Include emo JavaScript';
$_lang['setting_emo.include_scripts_desc'] = 'If this setting is enabled, a link to the emo JavaScript (see emo.js_path) is inserted at the end of the body section.';
$_lang['setting_emo.js_path'] = 'Path to emo JavaScript';
$_lang['setting_emo.js_path_desc'] = 'With this setting a different location of the emo JavaScript than {assets_url}components/emo/js/emo.min.js can be provided.';
$_lang['setting_emo.no_script_message'] = '‘No JavaScript’ message (Resource ID for internal link)';
$_lang['setting_emo.no_script_message_desc'] = 'All email addresses in the current document are replaced with the text in this setting. If the text is numeric, a link to the resource with that ID and the pagetitle of that resource is generated. If the setting is empty, the default lexicon entry is used.';
$_lang['setting_emo.selection_range'] = 'Comma-separated list of enabled/disabled resource IDs for emo';
$_lang['setting_emo.selection_range_desc'] = 'Insert a comma-separated list of resource IDs the plugin should (not) work on.';
$_lang['setting_emo.selection_type'] = 'Selection type for enabled/disabled resources for emo';
$_lang['setting_emo.selection_type_desc'] = 'If only some resources should be processed by the plugin, change the value of this setting to `include`.';
$_lang['setting_emo.tpl_only'] = 'Don’t work on resources with blank template';
$_lang['setting_emo.tpl_only_desc'] = 'Enable this, if the plugin should not work on resources with the `(blank)` template.';
