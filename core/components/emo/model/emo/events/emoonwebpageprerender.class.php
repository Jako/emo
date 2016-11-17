<?php

/**
 * @package emo
 * @subpackage plugin
 */
class EmoOnWebPagePrerender extends EmoPlugin
{
    public function run()
    {
        $noScriptMessage = $this->emo->getOption('no_script_message');
        $noScriptMessage = ($noScriptMessage !== '') ? $noScriptMessage : $this->modx->lexicon('emo.no_script_message');

        // Generate noScriptMessage as link if it is numeric and a resource with this ID exists
        if (is_numeric($noScriptMessage) && $noScriptResource = $this->modx->getObject('modResource', $noScriptMessage)) {
            $noScriptMessage = '<a href="' . $this->modx->makeUrl($noScriptMessage, '', '', 'abs') . '">' . $noScriptResource->get('pagetitle') . '</a>';
        }

        $this->emo->config['noScriptMessage'] = $noScriptMessage;
        $this->emo->config['show_debug'] = $this->emo->getOption('debug', null, false);
        $this->modx->resource->_output = $this->emo->obfuscateEmail($this->modx->resource->_output);

        $script = $this->emo->config['addrJs'] . $this->emo->config['debugString'];

        if ($this->emo->config['addrCount'] && strpos($this->modx->resource->_output, $script) === false) {
            $this->modx->setPlaceholder('emo_addresses', $this->emo->config['addrArray']);
            // regClientScript and replacing in resource output because regClientScript is not executed in OnWebPagePrerender, but another plugin could use it.
            $this->modx->regClientScript($script);
            $this->modx->resource->_output = preg_replace('~(</body[^>]*>)~i', $script . "\n" . '\1', $this->modx->resource->_output);
        }
    }
}