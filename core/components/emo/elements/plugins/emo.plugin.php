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

$debug = $modx->getOption('emo.debug', null, false);

// Get system settings
$tplOnly = (bool)$modx->getOption('emo.tplOnly', null, true);
$selectionType = $modx->getOption('emo.selection', null, 'exclude', true);
$selectionRange = $modx->getOption('emo.selection_range', null, '', true);
$includeScripts = $modx->getOption('emo.include_scripts', null, true, true);
$jsPath = $modx->getOption('emo.js_path', null, $assetsUrl . 'js/emo.min.js', true);
$cssPath = $modx->getOption('emo.css_path', null, '', true);
$noScriptMessage = $modx->getOption('emo.no_script_message', null, $modx->lexicon('emo.no_script_message'), true);

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
        if ($includeScripts && $jsPath != '') {
            $modx->regClientScript($jsPath);
        }
        if ($cssPath != '') {
            $modx->regClientCSS($cssPath);
        }
        break;
    }
    case 'OnWebPagePrerender': {
        $emo->config['noScriptMessage'] = $noScriptMessage;
        $emo->config['show_debug'] = $debug;
        $modx->resource->_output = $emo->obfuscateEmail($modx->resource->_output);
        $script = $emo->config['addressesjs'] . $emo->config['debug'];
        if ($emo->config['addrCount'] && strpos($modx->resource->_output, $script) === false) {
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
