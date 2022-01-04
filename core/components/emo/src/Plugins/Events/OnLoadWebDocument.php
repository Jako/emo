<?php
/**
 * @package emo
 * @subpackage plugin
 */

namespace TreehillStudio\Emo\Plugins\Events;

use TreehillStudio\Emo\Plugins\Plugin;

class OnLoadWebDocument extends Plugin
{
    public function process()
    {
        $assetsUrl = $this->emo->getOption('assetsUrl');
        $jsUrl = $this->emo->getOption('js_path');
        $jsUrl = ($jsUrl) ?: $assetsUrl . 'js/emo.min.js';
        $cssUrl = $this->emo->getOption('css_path');

        if ($this->emo->getOption('include_scripts', null, true)) {
            if ($this->emo->getOption('debug', null, false) && $assetsUrl != MODX_ASSETS_URL . 'components/emo/') {
                $this->modx->regClientScript($assetsUrl . '../../../source/js/emo.js?v=' . $this->emo->version);
            } else {
                $this->modx->regClientScript($jsUrl . '?v=' . $this->emo->version);
            }
        }
        if ($cssUrl != '') {
            $this->modx->regClientCSS($cssUrl);
        }
    }
}
