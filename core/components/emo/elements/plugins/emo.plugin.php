<?php
/**
 * Emo Plugin
 *
 * @package emo
 * @subpackage plugin
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

$className = 'TreehillStudio\Emo\Plugins\Events\\' . $modx->event->name;

$corePath = $modx->getOption('emo.core_path', null, $modx->getOption('core_path') . 'components/emo/');
/** @var Emo $emo */
$emo = $modx->getService('emo', 'Emo', $corePath . 'model/emo/', [
    'core_path' => $corePath
]);

if ($emo) {
    if (class_exists($className)) {
        $handler = new $className($modx, $scriptProperties);
        if (get_class($handler) == $className) {
            $handler->run();
        } else {
            $modx->log(xPDO::LOG_LEVEL_ERROR, $className. ' could not be initialized!', '', 'Emo Plugin');
        }
    } else {
        $modx->log(xPDO::LOG_LEVEL_ERROR, $className. ' was not found!', '', 'Emo Plugin');
    }
}

return;