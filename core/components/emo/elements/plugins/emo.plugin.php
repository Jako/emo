<?php
/**
 * emo Plugin
 *
 * @package emo
 * @subpackage pluginfile
 *
 * @var modX $modx
 */
$corePath = $modx->getOption('emo.core_path', null, $modx->getOption('core_path') . 'components/emo/');
$assetsUrl = $modx->getOption('emo.assets_url', null, $modx->getOption('assets_url') . 'components/emo/');

/** @var Emo $emo */
$emo = $modx->getService('emo', 'Emo', $corePath . 'model/emo/', array(
    'core_path' => $corePath
));

// Get system settings
$tplOnly = (bool)$emo->getOption('tplOnly', null, true);
$selectionType = $emo->getOption('selection', null, 'exclude');
$selectionRange = $emo->getOption('selection_range');
$includeScripts = $emo->getOption('include_scripts', null, true);
$jsUrl = $emo->getOption('js_path');
$jsUrl = ($jsUrl) ? $jsUrl : $assetsUrl . 'js/emo.min.js';
$cssUrl = $emo->getOption('css_path');
$debug = $emo->getOption('debug', null, false);
$noScriptMessage = $emo->getOption('no_script_message');
$noScriptMessage = ($noScriptMessage !== '') ? $noScriptMessage : $modx->lexicon('emo.no_script_message');

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
        if ($includeScripts) {
            if ($debug && $emo->getOption('assetsUrl') != MODX_ASSETS_URL . 'components/emo/') {
                $modx->regClientScript($emo->getOption('assetsUrl') . '../../../source/js/emo.js');
            } else {
                $modx->regClientScript($jsUrl);
            }
        }
        if ($cssUrl != '') {
            $modx->regClientCSS($cssUrl);
        }
        break;
    }
    case 'OnWebPagePrerender': {
        $emo->config['noScriptMessage'] = $noScriptMessage;
        $emo->config['show_debug'] = $debug;
        $modx->resource->_output = $emo->obfuscateEmail($modx->resource->_output);
        $script = $emo->config['addrJs'] . $emo->config['debugString'];
        if ($emo->config['addrCount'] && strpos($modx->resource->_output, $script) === false) {
            // regClientScript and replacing in resource output because regClientScript is not executed in OnWebPagePrerender, but another plugin could use it.
            $modx->setPlaceholder('emo_addresses', $emo->config['addrArray']);
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
