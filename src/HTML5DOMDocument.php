<?php

namespace KirbyExtended;

use DOMDocument;
use DOMNode;

class HTML5DOMDocument extends DOMDocument
{
    protected string $fakeRoot = 'main';

    public function __construct(string $version = '1.0', string $encoding = 'UTF-8')
    {
        // Disable HTML5 errors
        libxml_use_internal_errors(true);
        parent::__construct($version, $encoding);
    }

    public function loadHTML($source, $options = LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD)
    {
        // `loadHTML` will treat the string as being in ISO-8859-1 unless
        // you tell it otherwise
        // @see https://stackoverflow.com/questions/39148170/utf-8-with-php-domdocument-loadhtml/39148511
        $convertedSource = mb_convert_encoding($source, 'HTML-ENTITIES', 'UTF-8');

        // Add fake root element for XML parser because it assumes that the
        // first encountered tag is the root element
        // @see https://stackoverflow.com/questions/39479994/php-domdocument-savehtml-breaks-format
        parent::loadHTML("<{$this->fakeRoot}>" . $convertedSource . "</{$this->fakeRoot}>", $options);
    }

    private function unwrapFakeRoot(string $output)
    {
        if ($this->firstChild->nodeName === $this->fakeRoot) {
            return substr($output, strlen($this->fakeRoot) + 2, -strlen($this->fakeRoot) - 4);
        }

        return $output;
    }

    public function saveHTML(?DOMNode $node = null, bool $entities = false)
    {
        $html = parent::saveHTML($node);

        if ($entities === false) {
            $html = html_entity_decode($html);
        }

        if ($node === null) {
            $html = $this->unwrapFakeRoot($html);
        }

        return $html;
    }
}
