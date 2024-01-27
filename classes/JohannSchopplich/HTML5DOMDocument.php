<?php

namespace JohannSchopplich;

use DOMDocument;
use DOMNode;

class HTML5DOMDocument extends DOMDocument
{
    /**
     * Name of temporary root element for the XML parser
     */
    protected string $tempRoot = 'main';

    /**
     * Create a new HTML5-compatible document parser
     */
    public function __construct(string $version = '1.0', string $encoding = 'UTF-8')
    {
        // Silence libxml errors with HTML5 elements
        libxml_use_internal_errors(true);

        // Call parent class
        parent::__construct($version, $encoding);
    }

    /**
     * Load HTML from string, make UTF-8 compatible and add temporary root element
     */
    public function loadHTML(string $source, int $options = LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD): bool
    {
        // `loadHTML` will treat the string as being in ISO-8859-1 unless
        // told otherwise, so translate anything above the ASCII range into
        // its html entity equivalent
        // @see https://stackoverflow.com/questions/39148170/utf-8-with-php-domdocument-loadhtml
        $convertedSource = htmlspecialchars_decode(htmlentities($source, ENT_COMPAT, 'UTF-8'), ENT_QUOTES);

        // Add fake root element for XML parser because it assumes that the
        // first encountered tag is the root element
        // @see https://stackoverflow.com/questions/39479994/php-domdocument-savehtml-breaks-format
        return parent::loadHTML("<{$this->tempRoot}>" . $convertedSource . "</{$this->tempRoot}>", $options);
    }

    /**
     * Strip the temporarily added root element
     */
    private function unwrapTempRoot(string $output): string
    {
        if ($this->firstChild->nodeName === $this->tempRoot) {
            return substr($output, strlen($this->tempRoot) + 2, -strlen($this->tempRoot) - 4);
        }

        return $output;
    }

    /**
     * Dump the internal document into a HTML string
     */
    #[\ReturnTypeWillChange]
    public function saveHTML(DOMNode|null $node = null, bool $entities = false): string|false
    {
        $html = parent::saveHTML($node);

        if ($entities === false) {
            $html = html_entity_decode($html);
        }

        if ($node === null) {
            $html = $this->unwrapTempRoot($html);
        }

        return $html;
    }
}
