<?php

/** @var \Kirby\Cms\Block $block */
$highlighter = new \Highlight\Highlighter();
$language = $block->language()->value();
$code = $block->code()->fromBase64()->value();

if (empty($language) || !in_array($language, $highlighter->listRegisteredLanguages())) {
    $language = 'plaintext';
}

$highlightedCode = $highlighter->highlight($language, $code)->value;

// Handle line numbering
if (option('johannschopplich.highlighter.line-numbering', false)) {
    $lines = preg_split('/\R/', $highlightedCode);
    $lineClass = option('johannschopplich.highlighter.line-numbering-class', 'hljs-code-line');
    $highlightedCode = '<span class="' . $lineClass . '">' . implode("</span>\n<span class=\"$lineClass\">", $lines) . '</span>';
}

?>
<pre class="<?= option('johannschopplich.highlighter.class', 'hljs') ?>"><code data-language="<?= $language ?>"><?= $highlightedCode ?></code></pre>
