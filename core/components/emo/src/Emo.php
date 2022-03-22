<?php
/**
 * emo
 *
 * Copyright 2008-2011 by Florian Wobbe - www.eprofs.de
 * Copyright 2011-2022 by Thomas Jakobi <office@treehillstudio.com>
 *
 * @package emo
 * @subpackage classfile
 */

namespace TreehillStudio\Emo;

use modX;
use xPDO;

/**
 * class Emo
 */
class Emo
{
    /**
     * A reference to the modX instance
     * @var modX $modx
     */
    public $modx;

    /**
     * The namespace
     * @var string $namespace
     */
    public $namespace = 'emo';

    /**
     * The package name
     * @var string $packageName
     */
    public $packageName = 'emo';

    /**
     * The version
     * @var string $version
     */
    public $version = '1.8.7';

    /**
     * The class options
     * @var array $options
     */
    public $options = [];

    /**
     * Emo constructor
     *
     * @param modX $modx A reference to the modX instance.
     * @param array $options An array of options. Optional.
     */
    public function __construct(modX &$modx, $options = [])
    {
        $this->modx =& $modx;
        $this->namespace = $this->getOption('namespace', $options, $this->namespace);

        $corePath = $this->getOption('core_path', $options, $this->modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/' . $this->namespace . '/');
        $assetsPath = $this->getOption('assets_path', $options, $this->modx->getOption('assets_path', null, MODX_ASSETS_PATH) . 'components/' . $this->namespace . '/');
        $assetsUrl = $this->getOption('assets_url', $options, $this->modx->getOption('assets_url', null, MODX_ASSETS_URL) . 'components/' . $this->namespace . '/');

        // Load some default paths for easier management
        $this->options = array_merge([
            'namespace' => $this->namespace,
            'version' => $this->version,
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'vendorPath' => $corePath . 'vendor/',
            'chunksPath' => $corePath . 'elements/chunks/',
            'pagesPath' => $corePath . 'elements/pages/',
            'snippetsPath' => $corePath . 'elements/snippets/',
            'pluginsPath' => $corePath . 'elements/plugins/',
            'controllersPath' => $corePath . 'controllers/',
            'processorsPath' => $corePath . 'processors/',
            'templatesPath' => $corePath . 'templates/',
            'assetsPath' => $assetsPath,
            'assetsUrl' => $assetsUrl,
            'jsUrl' => $assetsUrl . 'js/',
            'cssUrl' => $assetsUrl . 'css/',
            'imagesUrl' => $assetsUrl . 'images/',
            'connectorUrl' => $assetsUrl . 'connector.php'
        ], $options);

        // Add default options
        $this->options = array_merge($this->options, [
            'noScriptMessage' => $this->getOption('noScriptMessage', $options, 'Turn on Javascript!'),
            'show_debug' => (bool)$this->getOption('show_debug', $options, false),
            'tab' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+.',
            'addrCount' => 0,
            'debugString' => '',
            'recentLinks' => []
        ]);

        $lexicon = $this->modx->getService('lexicon', 'modLexicon');
        $lexicon->load($this->namespace . ':default');
    }

    /**
     * Get a local configuration option or a namespaced system setting by key.
     *
     * @param string $key The option key to search for.
     * @param array $options An array of options that override local options.
     * @param mixed $default The default value returned if the option is not found locally or as a
     * namespaced system setting; by default this value is null.
     * @return mixed The option value or the default value specified.
     */
    public function getOption($key, $options = [], $default = null)
    {
        $option = $default;
        if (!empty($key) && is_string($key)) {
            if ($options != null && array_key_exists($key, $options)) {
                $option = $options[$key];
            } elseif (array_key_exists($key, $this->options)) {
                $option = $this->options[$key];
            } elseif (array_key_exists("$this->namespace.$key", $this->modx->config)) {
                $option = $this->modx->getOption("$this->namespace.$key");
            }
        }
        return $option;
    }

    /**
     * Custom base 64 encoding
     * Original emo code by Florian Wobbe - www.eprofs.de
     *
     * @param string $data String to encode.
     * @return string Encoded data
     */
    private function encodeBase64($data)
    {
        $out = '';
        for ($i = 0; $i < strlen($data);) {
            $c1 = ord($data[$i++]);
            $c2 = $c3 = null;
            if ($i < strlen($data)) {
                $c2 = ord($data[$i++]);
            }
            if ($i < strlen($data)) {
                $c3 = ord($data[$i++]);
            }
            $e1 = $c1 >> 2;
            $e2 = (($c1 & 3) << 4) + ($c2 >> 4);
            $e3 = (($c2 & 15) << 2) + ($c3 >> 6);
            $e4 = $c3 & 63;
            if (is_nan($c2)) {
                $e3 = $e4 = 64;
            } else {
                if (is_nan($c3)) {
                    $e4 = 64;
                }
            }
            $out .= $this->getOption('tab')[$e1] . $this->getOption('tab')[$e2] . $this->getOption('tab')[$e3] . $this->getOption('tab')[$e4];
        }
        return $out;
    }

    /**
     * Encrypt the match or generate a link when linktext is missing
     * Modified original emo code by Florian Wobbe - www.eprofs.de
     *
     * @access public
     * @param array $matches String to encode.
     * @return string Encoded data
     */
    private function encodeLink($matches)
    {
        if (!$this->getOption('addrCount')) {
            // Random generator seed
            mt_srand((double)microtime() * 1000000);
            // Make base 64 key
            $this->options['tab'] = str_shuffle($this->getOption('tab'));
            // Set counter and add base 64 key to array
            $this->options['addrCount'] = 1;
            $this->options['addrArray'][] = $this->getOption('tab');
        }

        // Link without a linktext: insert email address as text part
        if (sizeof($matches) < 3) {
            $matches[2] = $matches[1];
        }

        // rawurlencode/rawurldecode a possible subject/body
        $matches[1] = preg_replace_callback(
            '!(.*\?(subject|body)=)([^\?]*)!iu',
            function ($m) {
                return $m[1] . rawurldecode(rawurlencode($m[3]));
            }, $matches[1]
        );

        // Extract existing classes
        $classes = '';
        if (preg_match('/<a[^>]+class=\"([^"]+)/iu', $matches[0], $class_match)) {
            $classes = ' ' . $class_match[1];
        }

        // Create html of the true link
        $trueLink = '<a class="emo_address' . $classes . '" href="mailto:' . $matches[1] . '">' .
            htmlspecialchars_decode(htmlentities($matches[2], ENT_NOQUOTES, 'UTF-8'), ENT_NOQUOTES) .
            '</a>';

        // Did we use the same link before?
        $key = array_search($trueLink, $this->getOption('recentLinks'));

        // Encrypt the complete link or use previously encrypted link
        $crypted = ($key === false) ? $this->encodeBase64($trueLink) : $this->getOption('addrArray')[$key + 1];

        // Add encrypted address to array
        $this->options['addrArray'][] = $crypted;

        // Create html of the fake link
        $replaceLink = '<span id="_emoaddrId' . $this->options['addrCount'] . '"><span class="emo_address">' . $this->options['noScriptMessage'] . '</span></span>';

        // Add link to recent links array
        $this->options['recentLinks'][] = $trueLink;

        // Debugging
        if ($this->getOption('show_debug')) {
            $this->options['debugString'] .= '  ' . $this->getOption('addrCount') . ' ' . $matches[0] . "\n" .
                '    ' . $matches[1] . "\n" .
                '    ' . $matches[2] . "\n" .
                '    ' . $crypted . "\n";
        }

        // Increase address counter
        $this->options['addrCount']++;

        return $replaceLink;
    }

    /**
     * Replace the found email strings and generate the javascript
     * Modified original emo code by Florian Wobbe - www.eprofs.de
     *
     * @access public
     * @param string $content String to encode.
     * @return string Encoded data
     */
    public function obfuscateEmail($content)
    {
        $this->options['addrArray'] = [];

        // Debugging
        if ($this->getOption('show_debug')) {
            $this->options['debugString'] = "\n" . '<!-- Emo debugging' . "\n";
            $mtime = microtime();
            $mtime = explode(' ', $mtime);
            $mtime = $mtime[1] + $mtime[0];
            $starttime = $mtime;
        }

        // exclude form and emo-exclude tags
        $splitEx = "#((?:<form|<!-- emo-exclude -->).*?(?:</form>|<!-- /emo-exclude -->))#ius";
        $parts = preg_split($splitEx, $content, null, PREG_SPLIT_DELIM_CAPTURE);
        $output = '';
        foreach ($parts as $part) {
            $result = $part;
            if (substr($part, 0, strlen('<form')) != '<form' && substr($part, 0, strlen('<!-- emo-exclude -->')) != '<!-- emo-exclude -->') {
                $result = preg_replace_callback('#<a[^>]*mailto:([^\'"]+)[\'"][^>]*>(.*?)</a>#ius', [
                    $this, 'encodeLink'
                ], $part);
                if (preg_last_error()) {
                    $result = $part;
                    $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'The rendered resource content contains invalid characters.', '', 'obfuscateEmail');
                }
                $result = preg_replace_callback('#([a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,})#iu', [
                    $this, 'encodeLink'
                ], $result);
                if (preg_last_error()) {
                    $result = $part;
                    $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'The rendered resource content contains invalid characters: ', '', 'obfuscateEmail');
                }
            }
            $output .= $result;
        }
        $output = str_replace(['<!-- emo-exclude -->', '<!-- /emo-exclude -->'], '', $output);

        // Script block
        $this->options['addrJs'] = "\n" . '    <!-- This script block stores the encrypted -->' . "\n" .
            '    <!-- email address(es) in an addresses array. -->' . "\n" .
            '    <script type="text/javascript">' . "\n" .
            '    //<![CDATA[' . "\n" .
            '      var emo_addresses = ' . json_encode($this->getOption('addrArray')) . "\n" .
            '      addLoadEvent(emo_replace());' . "\n" .
            '    //]]>' . "\n" .
            '    </script>' . "\n";

        // Debugging
        if ($this->getOption('show_debug')) {
            $mtime = microtime();
            $mtime = explode(' ', $mtime);
            $mtime = $mtime[1] + $mtime[0];
            $endtime = $mtime;
            $totaltime = ($endtime - $starttime);
            $this->options['debugString'] .= '  Email crypting took ' . $totaltime . ' seconds' . "\n\n" .
                '  ' . implode("\n  ", $this->getOption('recentLinks')) . "\n" .
                '-->';
        }
        return $output;
    }
}
