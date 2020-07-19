<?php

namespace KirbyExtended;

use Highlight\Highlighter;
use Kirby\Toolkit\Str;

class HighlightAdapter
{
    protected static string $namespace = 'kirby-extended.highlight.';

    /**
     * Highlight all code blocks inside a given HTML snippet
     *
     * @param string|null $text
     * @return string
     */
    public static function highlight(?string $text)
    {
        // Parse KirbyText input as HTML document
        // @see https://github.com/ivopetkov/html5-dom-document-php
        $dom = new \DOMDocument();
        $dom->loadHTML($text, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        // Retrieve all `pre` elements inside newly created HTML document
        // @see https://www.php.net/manual/en/class.domxpath.php
        $query = new \DOMXPath($dom);
        $elements = $query->evaluate('//pre');

        // Loop through all `pre` elements
        foreach ($elements as $element) {
            // Select direct `code` child element of `pre` block
            $codeElement = $query->evaluate('//code', $element)->item(0);

            // Get language code if present
            $language = $codeElement->getAttribute('class');
            if (Str::contains($language, '-')) {
                $language = Str::split($language, '-')[1];
            }

            // Bail highlighting if language isn't set or auto detection is disabled
            if (empty($language) && !option(static::$namespace . 'autodetect', false)) {
                continue;
            }

            // Add `hljs` class to `pre` block
            $element->setAttribute('class', option(static::$namespace . 'class', 'hljs'));

            // Get raw code data to highlight
            $code = $codeElement->nodeValue;

            // Remove code element afterwards
            $element->removeChild($codeElement);

            // Initiate `Highlighter` and use pre-defined language code, fall
            // back to language auto detection if enabled
            $highlighter = new Highlighter();

            // Highlight code
            if (!empty($language)) {
                $highlightedCode = $highlighter->highlight($language, $code);
            } else if (option(static::$namespace . 'autodetect', false)) {
                $languageSubset = option(static::$namespace . 'languages', null);
                if (!empty($languageSubset)) {
                    $highlighter->setAutodetectLanguages($languageSubset);
                }

                $highlightedCode = $highlighter->highlightAuto($code);
            }

            // Append highlighted wrapped in `code` block to parent `pre`
            $codeElement = $dom->createDocumentFragment();
            $codeElement->appendXML('<code>' . $highlightedCode->value . '</code>');
            $element->appendChild($codeElement);
        }

        // Save all changes
        $text = $dom->saveHTML();
        return $text;
    }
}
