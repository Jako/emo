<?php

/**
 * emo - E-Mail Obfuscation with Javascript
 *
 * Copyright 2008-2011 by Florian Wobbe - www.eprofs.de
 * Copyright 2011-2016 by Thomas Jakobi <thomas.jakobi@partout.info>
 *
 * @package emo
 * @subpackage classfile
 */
class Emo
{

    /**
     * A reference to the modX instance
     * @var modX $modx
     */
    public $modx;

    /**
     * Address counter
     * @var int $addrCount
     */
    public $addrCount;

    /**
     * A string of debug informations
     * @var string $debug
     */
    public $debug;

    /**
     * A string for storing the javascript
     * @var string $addressesjs
     */
    public $addressesjs;

    /**
     * A configuration array
     * @var array $config
     */
    private $config;

    /**
     * The no script message
     * @var string $noScriptMessage
     */
    private $noScriptMessage;

    /**
     * An Array for storing recent links
     * @var array $recentLinks
     */
    private $recentLinks;

    /**
     * base 64 characters
     * @var string $tab
     */
    private $tab;

    /**
     * emo constructor
     *
     * @access public
     * @param modX $modx A reference to the modX instance.
     * @param array $params An array of configuration options.
     */
    public function __construct(&$modx, $params)
    {
        $this->modx = &$modx;
        $this->noScriptMessage = $this->modx->getOption('noScriptMessage', $params, 'Turn on Javascript!');
        $this->tab = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+.';
        $this->recentLinks = array();
        $this->config = array(
            'show_debug' => (bool)$this->modx->getOption('show_debug', $params, false)
        );

        $this->modx->lexicon->load('emo:default');
    }

    /**
     * Custom base 64 encoding
     * Original emo code by Florian Wobbe - www.eprofs.de
     *
     * @access public
     * @param string $data String to encode.
     * @return string Encoded data
     */
    private function encodeBase64($data)
    {
        $out = '';
        for ($i = 0; $i < strlen($data);) {
            $c1 = ord($data{$i++});
            $c2 = $c3 = null;
            if ($i < strlen($data))
                $c2 = ord($data{$i++});
            if ($i < strlen($data))
                $c3 = ord($data{$i++});
            $e1 = $c1 >> 2;
            $e2 = (($c1 & 3) << 4) + ($c2 >> 4);
            $e3 = (($c2 & 15) << 2) + ($c3 >> 6);
            $e4 = $c3 & 63;
            if (is_nan($c2))
                $e3 = $e4 = 64;
            else if (is_nan($c3))
                $e4 = 64;
            $out .= $this->tab{$e1} . $this->tab{$e2} . $this->tab{$e3} . $this->tab{$e4};
        }
        return $out;
    }

    /**
     * Encrypt the match or generate a link when linktext is missing
     * Modified original emo code by Florian Wobbe - www.eprofs.de
     *
     * @access public
     * @param string $matches String to encode.
     * @return string Encoded data
     */
    private function encodeLink($matches)
    {
        // Use global variables

        if (!$this->addrCount) {
            // Random generator seed
            mt_srand((double)microtime() * 1000000);
            // Make base 64 key
            $this->tab = str_shuffle($this->tab);
            // Set counter and add base 64 key to array
            $this->addrCount = 0;
            $this->addressesjs .= '      emo_addresses[' . $this->addrCount++ . '] = "' . $this->tab . '";' . "\n";
        }

        // Link without a linktext: insert email address as text part
        if (sizeof($matches) < 3) {
            $matches[2] = $matches[1];
        }

        // urlencode a possible subject
        $matches[1] = preg_replace('!(.*\?(subject|body)=)([^\?]*)!ieu', "'$1'.rawurldecode(rawurlencode('$3'))", $matches[1]);

        // Create html of the true link
        $trueLink = '<a class="emo_address" href="mailto:' . $matches[1] . '">' . htmlspecialchars_decode(htmlentities($matches[2], ENT_NOQUOTES, 'UTF-8'), ENT_NOQUOTES) . '</a>';

        // Did we use the same link before?
        $key = array_search($trueLink, $this->recentLinks);
        if ($key === false) {
            // Encrypt the complete link
            $crypted = '"' . $this->encodeBase64($trueLink) . '"';
        } else {
            // Use previously encrypted link
            $crypted = 'emo_addresses[' . ($key + 1) . ']';
        }

        // Add encrypted address to array
        $this->addressesjs .= '      emo_addresses[' . $this->addrCount . '] = ' . $crypted . ';' . "\n";

        // Create html of the fake link
        $replaceLink = '<span id="_emoaddrId' . $this->addrCount . '"><span class="emo_address">' . $this->noScriptMessage . '</span></span>';

        // Add link to recent links array
        array_push($this->recentLinks, $trueLink);

        // Debugging
        if ($this->config['show_debug']) {
            $this->debug .= '  ' . $this->addrCount . ' ' . $matches[0] . "\n" .
                '    ' . $matches[1] . "\n" .
                '    ' . $matches[2] . "\n" .
                '    ' . $crypted . "\n";
        }

        // Increase address counter
        $this->addrCount++;

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
        // Script block header
        $this->addressesjs = "\n" . '    <!-- This script block stores the encrypted //-->' . "\n" .
            '    <!-- email address(es) in an addresses array. //-->' . "\n" .
            '    <script type="text/javascript">' . "\n" . '    /* <![CDATA[ */' . "\n" .
            '      var emo_addresses = new Array();' . "\n";

        // Debugging
        if ($this->config['show_debug']) {
            $this->debug = "\n" . '<!-- Emo debugging' . "\n";
            $mtime = microtime();
            $mtime = explode(' ', $mtime);
            $mtime = $mtime[1] + $mtime[0];
            $starttime = $mtime;
        }

        // Plain email regular expession
        $atom = "[-!\#$%'*+/=?^_`{|}~0-9A-Za-z]+";
        $email_left = $atom . '(?:\\.' . $atom . ')*';
        $email_right = $atom . '(?:\\.' . $atom . ')+';
        $emailRegex = '#('.$email_left . '@' . $email_right .')#iu';

        // exclude form tags
        $splitEx = "#((?:<form).*?(?:</form>))#isu";
        $parts = preg_split($splitEx, $content, null, PREG_SPLIT_DELIM_CAPTURE);
        $output = '';
        foreach ($parts as $part) {
            if (substr($part, 0, 5) != '<form') {
                $part = preg_replace_callback('#<a[^>]*mailto:([^\'"]+)[\'"][^>]*>(.*?)</a>#iu', array($this, 'encodeLink'), $part);
                $part = preg_replace_callback($emailRegex, array($this, 'encodeLink'), $part);
            }
            $output .= $part;
        }

        // Finish encrypted addresses block
        $this->addressesjs .= '      addLoadEvent(emo_replace());' . "\n" .
            '    //-->' . "\n" .
            '    </script>' . "\n";

        // Maybe you want to use jQuery ...
        // $this->addressesjs .= '     $(window).load(function(){'."\n".'        emo_replace();'."\n".'      });'."\n".'    /* ]]> */'."\n".'    </script>'."\n";
        // Debugging
        if ($this->config['show_debug']) {
            $mtime = microtime();
            $mtime = explode(' ', $mtime);
            $mtime = $mtime[1] + $mtime[0];
            $endtime = $mtime;
            $totaltime = ($endtime - $starttime);
            $this->debug .= '  Email crypting took ' . $totaltime . ' seconds' . "\n\n" .
                '  ' . implode("\n  ", $this->recentLinks) . "\n" .
                '-->';
        }
        return $output;
    }

}

?>
