<?php
/**
 * emo Plugin
 *
 * @package emo
 * @subpackage pluginfile
 *
 * @var modX $modx
 * @var array $scriptProperties
 */
$corePath = $modx->getOption('emo.core_path', null, $modx->getOption('core_path') . 'components/emo/');
/** @var Emo $emo */
$emo = $modx->getService('emo', 'Emo', $corePath . 'model/emo/', array(
    'core_path' => $corePath
));

// Get selection range and selection type system settings
$tplOnly = (bool)$emo->getOption('tpl_only', null, true);
$selectionType = $emo->getOption('selection_type', null, 'exclude');
$selectionRange = $emo->getOption('selection_range');

// Stop plugin on selection range and selection type
$selectionRange = explode(',', str_replace(' ', '', $selectionRange));
$emoFound = in_array($modx->resource->get('id'), $selectionRange);
if (($emoFound && ($selectionType == 'exclude')) || (!$emoFound && ($selectionType == 'include')) || ($tplOnly && ($modx->resource->get('template') == 0))) {
    return;
}

$className = 'Emo' . $modx->event->name;
$modx->loadClass('EmoPlugin', $emo->getOption('modelPath') . 'emo/events/', true, true);
$modx->loadClass($className, $emo->getOption('modelPath') . 'emo/events/', true, true);
if (class_exists($className)) {
    /** @var EmoPlugin $handler */
    $handler = new $className($modx, $scriptProperties);
    $handler->run();
}

return;