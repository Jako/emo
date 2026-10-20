<?php
/**
 * emo
 *
 * Copyright 2008-2011 by Florian Wobbe - www.eprofs.de
 * Copyright 2011-2026 by Thomas Jakobi <office@treehillstudio.com>
 *
 * @package emo
 * @subpackage classfile
 */

namespace TreehillStudio\Emo;

use Arubacao\TldChecker\Validator;
use modX;
use Normalizer;
use xPDO;

/**
 * class Emo
 */
class Emo
{
    /**
     * The characters of the custom base 64 key
     */
    private const TAB = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+.';

    /**
     * The characters of the standard base 64 encoding
     */
    private const BASE64_ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';

    /**
     * The markers of an excluded content part
     */
    private const EXCLUDE_START = '<!-- emo-exclude -->';
    private const EXCLUDE_END = '<!-- /emo-exclude -->';

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
    public $version = '1.9.3';

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
    public function __construct(modX &$modx, array $options = [])
    {
        $this->modx =& $modx;

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
            'noScriptMessage' => $this->modx->getOption($this->namespace . '.noScriptMessage', $options, 'Turn on Javascript!'),
            'show_debug' => (bool)$this->modx->getOption($this->namespace . '.show_debug', $options, false),
            'adresses_tpl' => $this->modx->getOption($this->namespace . '.adresses_tpl', $options, 'tplEmoAdresses'),
            'tab' => self::TAB,
            'addrCount' => 0,
            'addrArray' => [],
            'addrJs' => '',
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
    public function getOption(string $key, array $options = [], $default = null)
    {
        $option = $default;
        if (!empty($key)) {
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
     * Base 64 encoding with the shuffled custom alphabet and without padding
     *
     * @param string $data String to encode.
     * @return string Encoded data
     */
    private function encodeBase64(string $data): string
    {
        return strtr(rtrim(base64_encode($data), '='), self::BASE64_ALPHABET, $this->options['tab']);
    }

    /**
     * Encrypt the match or generate a link when linktext is missing
     * Modified original emo code by Florian Wobbe - www.eprofs.de
     *
     * @param array $matches The regular expression matches.
     * @return string The replacement for the match
     */
    private function encodeLink(array $matches): string
    {
        $isEmail = Validator::endsWithTld($matches[1]) || (!empty($matches[2]) && Validator::endsWithTld($matches[2]));
        if (!$isEmail) {
            return $matches[0];
        }

        if (!$this->options['addrCount']) {
            // Make base 64 key, set counter and add base 64 key to array in the first run
            $this->options['tab'] = str_shuffle(self::TAB);
            $this->options['addrCount'] = 1;
            $this->options['addrArray'][] = $this->options['tab'];
        }

        // Link without a linktext: insert email address as text part
        $address = $matches[1];
        $text = $matches[2] ?? $address;

        // Extract existing classes
        $classes = '';
        if (preg_match('/<a[^>]+class="([^"]+)/iu', $matches[0], $classMatch)) {
            $classes = ' ' . $classMatch[1];
        }

        // Create html of the true link
        $trueLink = '<a class="emo_address' . $classes . '" href="mailto:' . $address . '">' .
            htmlspecialchars_decode(htmlentities($text, ENT_NOQUOTES, 'UTF-8'), ENT_NOQUOTES) .
            '</a>';

        // Encrypt the complete link or use previously encrypted link (the first entry in addrArray is the key)
        $key = array_search($trueLink, $this->options['recentLinks']);
        $crypted = ($key === false) ? $this->encodeBase64($trueLink) : $this->options['addrArray'][$key + 1];

        $this->options['addrArray'][] = $crypted;
        $this->options['recentLinks'][] = $trueLink;

        // Debugging
        if ($this->options['show_debug']) {
            $this->options['debugString'] .= '  ' . $this->options['addrCount'] . ' ' . $matches[0] . "\n" .
                '    ' . $address . "\n" .
                '    ' . $text . "\n" .
                '    ' . $crypted . "\n";
        }

        // Create html of the fake link and increase address counter
        return '<span id="_emoaddrId' . $this->options['addrCount']++ . '"><span class="emo_address">' . $this->options['noScriptMessage'] . '</span></span>';
    }

    /**
     * Replace the email strings in a content part
     *
     * @param string $part The content part.
     * @return string The content part with encrypted emails
     */
    private function encodePart(string $part): string
    {
        $patterns = [
            '#<a[^>]*mailto:([^\'"]+)[\'"][^>]*>(.*?)</a>#ius',
            '#([a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,})#iu'
        ];
        $result = $part;
        foreach ($patterns as $pattern) {
            $result = preg_replace_callback($pattern, [$this, 'encodeLink'], $result);
            if (preg_last_error()) {
                $result = $part;
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'The rendered resource content contains invalid characters.', '', 'obfuscateEmail');
            }
        }
        return $result;
    }

    /**
     * Replace the found email strings and generate the JavaScript
     * Modified original emo code by Florian Wobbe - www.eprofs.de
     *
     * @param string $content String to encode.
     * @return string Encoded data
     */
    public function obfuscateEmail(string $content): string
    {
        $debug = $this->options['show_debug'];

        $this->options['tab'] = self::TAB;
        $this->options['addrCount'] = 0;
        $this->options['addrArray'] = [];
        $this->options['recentLinks'] = [];

        if (class_exists(Normalizer::class, false)) {
            $normalized = Normalizer::normalize($content, Normalizer::FORM_C);
            if ($normalized !== false) {
                $content = $normalized;
            } else {
                if ($debug) {
                    $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'The content contains malformed UTF-8 code');
                }
                return $content;
            }
        } else {
            if ($debug) {
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'The Normalizer class was not found and the content can create a blank page if malformed UTF-8 code occurs.');
            }
        }

        // Debugging
        if ($debug) {
            $this->options['debugString'] = "\n" . '<!-- Emo debugging' . "\n";
            $starttime = microtime(true);
        }

        // Exclude form and emo-exclude tags
        $splitEx = '#((?:<form|' . self::EXCLUDE_START . ').*?(?:</form>|' . self::EXCLUDE_END . '))#ius';
        $parts = preg_split($splitEx, $content, -1, PREG_SPLIT_DELIM_CAPTURE);
        $output = '';
        foreach ($parts as $part) {
            $excluded = strpos($part, '<form') === 0 || strpos($part, self::EXCLUDE_START) === 0;
            $output .= $excluded ? $part : $this->encodePart($part);
        }
        $output = str_replace([self::EXCLUDE_START, self::EXCLUDE_END], '', $output);

        // Script block
        $this->options['addrJs'] = "\n" . $this->modx->getChunk($this->options['adresses_tpl'], [
                'addr_array' => json_encode($this->options['addrArray'])
            ]);

        // Debugging
        if ($debug) {
            $totaltime = microtime(true) - $starttime;
            $this->options['debugString'] .= '  Email crypting took ' . round($totaltime, 4) . ' seconds' . "\n\n" .
                '  ' . implode("\n  ", $this->options['recentLinks']) . "\n" .
                '-->';
        }
        return $output;
    }
}
