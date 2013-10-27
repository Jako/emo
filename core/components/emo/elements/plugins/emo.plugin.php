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
 * @subpackage pluginfile
 */
$emoCorePath = $modx->getOption('emo.core_path', NULL, $modx->getOption('core_path') . 'components/emo/');
$emoAssetsUrl = $modx->getOption('emo.assets_url', NULL, $modx->getOption('assets_url') . 'components/emo/');
$debug = $modx->getOption('emo.debug', NULL, FALSE);
if (!$modx->loadClass('emo', $emoCorePath . 'model/emo/', TRUE, TRUE)) {
	$modx->log(modX::LOG_LEVEL_ERROR, '[emo] Could not load emo class.');
	if ($debug) {
		return 'Could not load emo class.';
	} else {
		return '';
	}
}

// Get system settings
$tplOnly = (bool) $modx->getOption('emo.tplOnly', NULL, TRUE);
$selectionType = $modx->getOption('emo.selection', NULL, 'exclude');
$selectionRange = $modx->getOption('emo.selection_range', NULL, '');
$jsPath = $modx->getOption('emo.js_path', NULL, $emoAssetsUrl . 'js/emo.min.js');
$cssPath = $modx->getOption('emo.css_path', NULL, '');
$noScriptMessage = $modx->getOption('emo.no_script_message', NULL, 'Turn on Javascript!');

// Generate noScriptMessage as link if it is
if (is_numeric($noScriptMessage)) {
	$noScriptResource = $modx->getObject('modResource', $noScriptMessage);
	$noScriptMessage = '<a href="' . $modx->makeUrl($noScriptMessage, '', '', 'abs') . '">' . $noScriptResource->get('pagetitle') . '</a>';
}

// Stop plugin on selection range and selection type
$selectionRange = explode(',', str_replace(' ', '', $selectionRange));
$emoFound = in_array($modx->resource->get('id'), $selectionRange);
if (($emoFound && ($selectionType != 'include')) || ($tplOnly && ($modx->resource->get('template') == 0))) {
	return;
}

switch ($modx->event->name) {
	case 'OnLoadWebDocument': {
			if ($jsPath != '') {
				$modx->regClientScript($jsPath);
			}
			if ($cssPath != '') {
				$modx->regClientCSS($cssPath);
			}
			break;
		}
	case 'OnWebPagePrerender': {
			$params = array(
				'noScriptMessage' => $noScriptMessage,
				'show_debug' => $debug
			);
			$emo = new Emo($modx, $params);
			$modx->resource->_output = $emo->obfuscateEmail($modx->resource->_output);
			$script = $emo->addressesjs . $emo->debug;
			if ($emo->addrCount && strpos($modx->resource->_output, $script) === false) {
				// regClientScript and replacing in resource output because regClientScript is not executed in OnWebPagePrerender, but another plugin could use it.
				$modx->regClientScript($script);
				$modx->resource->_output = preg_replace('~(</body[^>]*>)~i', $script . "\n" . '\1', $modx->resource->_output);
			}
			break;
		}
	default: {
			break;
		}
}
return;
?>
