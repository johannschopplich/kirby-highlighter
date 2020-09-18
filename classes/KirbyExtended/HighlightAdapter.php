<?php

namespace KirbyExtended;

use Highlight\Highlighter;
use Kirby\Toolkit\Str;

class HighlightAdapter
{
    /**
     * Highlight all code blocks inside a given HTML snippet
     *
     * @param string|null $text
     * @return string
     */
    public static function highlight(?string $text)
    {
        // Parse KirbyText input as HTML document
        $dom = new HTML5DOMDocument();
        $dom->loadHTML($text);

        // Retrieve all `pre` elements inside newly created HTML document
        $preNodes = $dom->getElementsByTagName('pre');

        // Bail if no `pre` elements have been found
        if ($preNodes->length === 0) {
            return $text;
        }

        // Loop through all `pre` elements
        foreach ($preNodes as $preNode) {
            // Ensure nothing but the `code` element exists
            if ($preNode->childNodes->length !== 1) {
                continue;
            }

            // Select direct `code` child element of `pre` block
            $codeNode = $preNode->firstChild;

            // Get language code if present
            $language = $codeNode->getAttribute('class');
            if (Str::contains($language, '-')) {
                $language = Str::split($language, '-')[1];
            }

            // Bail highlighting if language isn't set and auto detection is disabled
            if (empty($language) && !option('kirby-extended.highlighter.autodetect')) {
                continue;
            }

            // Add `hljs` class to `pre` block
            $preNode->setAttribute('class', option('kirby-extended.highlighter.class', 'hljs'));

            // Get raw code data to highlight
            $code = $codeNode->nodeValue;

            // Remove code element afterwards
            $preNode->removeChild($codeNode);

            // Initiate `Highlighter` and use pre-defined language code, fall
            // back to language auto detection if enabled
            $highlighter = new Highlighter();

            // Highlight code
            if (!empty($language)) {
                $highlightedCode = $highlighter->highlight($language, $code);
            } elseif (option('kirby-extended.highlighter.autodetect')) {
                $languageSubset = option('kirby-extended.highlighter.languages');
                if (!empty($languageSubset)) {
                    $highlighter->setAutodetectLanguages($languageSubset);
                }

                $highlightedCode = $highlighter->highlightAuto($code);
            }

            // Append highlighted wrapped in `code` block to parent `pre`
            $codeNode = $dom->createDocumentFragment();
            $codeNode->appendXML('<code>' . $highlightedCode->value . '</code>');
            $preNode->appendChild($codeNode);
        }

        // Save all changes
        $text = $dom->saveHTML(null, true);
        return $text;
    }
}
