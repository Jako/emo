<?php
/**
 * emo Plugin
 *
 * @package emo
 * @subpackage pluginfile
 */
$corePath = $modx->getOption('emo.core_path', null, $modx->getOption('core_path') . 'components/emo/');
$assetsUrl = $modx->getOption('emo.assets_url', null, $modx->getOption('assets_url') . 'components/emo/');
$debug = $modx->getOption('emo.debug', null, false);
if (!$modx->loadClass('emo', $corePath . 'model/emo/', true, true)) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[emo] Could not load emo class.');
    if ($debug) {
        return 'Could not load emo class.';
    } else {
        return '';
    }
}
$modx->lexicon->load('emo:default');

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
