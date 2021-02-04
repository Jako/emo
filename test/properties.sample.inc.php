<?php
/**
 * Properties file for testing
 *
 * @package emo
 * @subpackage test
 */

$properties = array(
    xPDO::OPT_CACHE_PATH => MODX_CORE_PATH . 'cache/',
    xPDO::OPT_HYDRATE_FIELDS => true,
    xPDO::OPT_HYDRATE_RELATED_OBJECTS => true,
    xPDO::OPT_HYDRATE_ADHOC_FIELDS => true,
);

/* PHPUnit test config */
$properties['testPath'] = dirname(__FILE__) . '/';
$properties['logLevel'] = modX::LOG_LEVEL_INFO;
