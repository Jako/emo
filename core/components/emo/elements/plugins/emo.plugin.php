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

$className = 'Emo' . $modx->event->name;
$modx->loadClass('EmoPlugin', $emo->getOption('modelPath') . 'emo/events/', true, true);
$modx->loadClass($className, $emo->getOption('modelPath') . 'emo/events/', true, true);
if (class_exists($className)) {
    /** @var EmoPlugin $handler */
    $handler = new $className($modx, $scriptProperties);
    $handler->run();
}

return;