<?php
/**
 * @package emo
 * @subpackage plugin
 */

abstract class EmoPlugin
{
    /** @var modX $modx */
    protected $modx;
    /** @var Emo $emo */
    protected $emo;
    /** @var array $scriptProperties */
    protected $scriptProperties;

    public function __construct($modx, &$scriptProperties)
    {
        $this->scriptProperties =& $scriptProperties;
        $this->modx = &$modx;
        $corePath = $this->modx->getOption('emo.core_path', null, $this->modx->getOption('core_path') . 'components/emo/');
        $this->emo = $this->modx->getService('emo', 'Emo', $corePath . 'model/emo/', array(
            'core_path' => $corePath
        ));
    }

    abstract public function run();
}