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
 * @subpackage build
 *
 * System settings for the minify package.
 */
$settings = array();

$settings['emo.tpl_only'] = $modx->newObject('modSystemSetting');
$settings['emo.tpl_only']->fromArray(array(
	'key' => 'emo.tpl_only',
	'value' => '1',
	'xtype' => 'combo-boolean',
	'namespace' => 'emo',
	'area' => 'system',
		), '', true, true);
$settings['emo.selection_type'] = $modx->newObject('modSystemSetting');
$settings['emo.selection_type']->fromArray(array(
	'key' => 'emo.selection_type',
	'type' => 'list',
	'options' => array(
		array('text' => 'Exclude Resources', 'value' => 'exclude'),
		array('text' => 'Include Resources', 'value' => 'include')
	),
	'value' => 'exclude',
	'namespace' => 'emo',
	'area' => 'system',
		), '', true, true);
$settings['emo.selection_range'] = $modx->newObject('modSystemSetting');
$settings['emo.selection_range']->fromArray(array(
	'key' => 'emo.selection_range',
	'value' => '',
	'namespace' => 'emo',
	'area' => 'system',
		), '', true, true);
$settings['emo.js_path'] = $modx->newObject('modSystemSetting');
$settings['emo.js_path']->fromArray(array(
	'key' => 'emo.js_path',
	'value' => $modx->getOption('assets_url') . 'components/emo/js/emo.min.js',
	'namespace' => 'emo',
	'area' => 'system',
		), '', true, true);
$settings['emo.css_path'] = $modx->newObject('modSystemSetting');
$settings['emo.css_path']->fromArray(array(
	'key' => 'emo.css_path',
	'value' => '',
	'namespace' => 'emo',
	'area' => 'system',
		), '', true, true);
$settings['emo.no_script_message'] = $modx->newObject('modSystemSetting');
$settings['emo.no_script_message']->fromArray(array(
	'key' => 'emo.no_script_message',
	'value' => 'Turn on Javascript!',
	'namespace' => 'emo',
	'area' => 'system',
		), '', true, true);

return $settings;
