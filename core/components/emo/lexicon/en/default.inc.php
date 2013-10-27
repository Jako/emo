<?php
/**
 * emo
 *
 * Copyright 2013 by Thomas Jakobi <thomas.jakobi@partout.info>
 *
 * emo is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free 
 * Software Foundation; either version 2 of the License, or (at your option) any 
 * later version.
 *
 * emo is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * emo; if not, write to the Free Software Foundation, Inc.,
 * 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package emo
 * @subpackage lexicon
 *
 * Default English Lexicon Entries for emo
 */

$_lang['emo'] = 'emo';

$_lang['setting_emo.tpl_only'] = 'Don\'t work on resources with blank template';
$_lang['setting_emo.tpl_only_desc'] = 'Enable this, if the plugin should work on resources with the \'(blank)\' template.';
$_lang['setting_emo.selection_type'] = 'Selection type for enabled/disabled resources for emo';
$_lang['setting_emo.selection_type_desc'] = 'If only some resources should worked by the plugin, change the value of this setting to \'include\'.';
$_lang['setting_emo.selection_range'] = 'Comma separated list of enabled/disabled resource IDs for emo';
$_lang['setting_emo.selection_range_desc'] = 'Insert a comma list with resource IDs, the plugin should (not) work on.';
$_lang['setting_emo.js_path'] = 'Path to emo javascript';
$_lang['setting_emo.js_path_desc'] = 'If this setting is set, a link to this js path is inserted at the end of the body area of the html code of the current resource.';
$_lang['setting_emo.css_path'] = 'Path to emo css';
$_lang['setting_emo.css_path_desc'] = 'If this setting is set, a link to this css path is inserted at the end of the head area of the html code of the current resource.';
$_lang['setting_emo.no_script_message'] = '\'No javascript\' message (Resource ID for internal link)';
$_lang['setting_emo.no_script_message_desc'] = 'All email addresses in the current document were replaced with the text in this setting. If the text is numeric, a link to the resource with that ID and the pagetitle of that resource is generated.';
