<?php

use Highlight\Highlighter;
use JohannSchopplich\HTML5DOMDocument;
use Kirby\Cms\App;

return [
    'kirbytext:after' => function (string|null $text) {
        $kirby = App::instance();

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
            if (str_starts_with($language, 'language-')) {
                $language = preg_replace('/^language-/', '', $language);
            }

            // Bail highlighting if language isn't set and auto detection is disabled
            if (empty($language) && !$kirby->option('johannschopplich.highlighter.autodetect', false)) {
                continue;
            }

            // Add `hljs` class to `pre` block
            $preNode->setAttribute('class', $kirby->option('johannschopplich.highlighter.class', 'hljs'));

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
            } elseif ($kirby->option('johannschopplich.highlighter.autodetect', false)) {
                $languageSubset = $kirby->option('johannschopplich.highlighter.languages', []);
                if (!empty($languageSubset)) {
                    $highlighter->setAutodetectLanguages($languageSubset);
                }

                $highlightedCode = $highlighter->highlightAuto($code);
            }

            // Line numbering
            if ($kirby->option('johannschopplich.highlighter.line-numbering', false)) {
                $lines = preg_split('/\R/', $highlightedCode->value);
                $lineClass = $kirby->option('johannschopplich.highlighter.line-numbering-class', 'hljs-code-line');
                $highlightedCode->value = '<span class="' . $lineClass . '">' . implode("</span>\n<span class=\"$lineClass\">", $lines) . '</span>';
            }

            // Append highlighted wrapped in `code` block to parent `pre`
            $codeNode = $dom->createDocumentFragment();
            $codeNode->appendXML('<code data-language="' . $language . '">' . $highlightedCode->value . '</code>');
            $preNode->appendChild($codeNode);
        }

        // Save all changes
        $text = $dom->saveHTML(null, true);
        return $text;
    }
];
