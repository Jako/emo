<?php
/**
 * emo Test Case
 *
 * @package emo
 * @subpackage test
 */

class emoTestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var modX $modx
     */
    protected $modx = null;
    /**
     * @var emo $emo
     */
    protected $emo = null;

    /**
     * Ensure all tests have a reference to the MODX and Quip objects
     */
    public function setUp()
    {
        $this->modx = emoTestHarness::_getConnection();

        $corePath = $this->modx->getOption('emo.core_path', null, $this->modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/emo/');
        require_once $corePath . 'model/emo/emo.class.php';

        $this->emo = new emo($this->modx);
        $this->emo->options['debug'] = true;

        $this->modx->placeholders = array();
        $this->modx->emo = &$this->emo;

        error_reporting(E_ALL);
    }

    /**
     * Remove reference at end of test case
     */
    public function tearDown()
    {
        $this->modx = null;
        $this->emo = null;
    }
}
