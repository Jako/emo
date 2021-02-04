<?php
/**
 * emo Core Tests
 *
 * @package emo
 * @subpackage test
 */

class emoCoreTest extends emoTestCase
{
    public function testAddemo()
    {
        $source = file_get_contents($this->modx->config['testPath'] . 'Data/Page/source.page.tpl');
        $source = $this->emo->obfuscateEmail($source);
        $result = file_get_contents($this->modx->config['testPath'] . 'Data/Page/result.page.tpl');

        $this->assertEquals($source, $result);
    }
}
