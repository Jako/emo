<?php
/**
 * Abstract plugin
 *
 * @package emo
 * @subpackage plugin
 */

namespace TreehillStudio\Emo\Plugins;

use modX;
use Emo;

/**
 * Class Plugin
 */
abstract class Plugin
{
    /** @var modX $modx */
    protected $modx;
    /** @var Emo $emo */
    protected $emo;
    /** @var array $scriptProperties */
    protected $scriptProperties;

    /**
     * Plugin constructor.
     *
     * @param $modx
     * @param $scriptProperties
     */
    public function __construct($modx, &$scriptProperties)
    {
        $this->scriptProperties = &$scriptProperties;
        $this->modx =& $modx;
        $corePath = $this->modx->getOption('emo.core_path', null, $this->modx->getOption('core_path') . 'components/emo/');
        $this->emo = $this->modx->getService('emo', 'Emo', $corePath . 'model/emo/', [
            'core_path' => $corePath
        ]);
    }

    /**
     * Run the plugin event.
     */
    public function run()
    {
        $init = $this->init();
        if ($init !== true) {
            return;
        }

        $this->process();
    }

    /**
     * Initialize the plugin event.
     *
     * @return bool
     */
    public function init()
    {
        return true;
    }

    /**
     * Process the plugin event code.
     *
     * @return mixed
     */
    abstract public function process();
}