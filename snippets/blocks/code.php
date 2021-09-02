<?php

/** @var \Kirby\Cms\Block $block */
$language = $block->language()->or('text')->value();
$code = $block->code()->value();

$highlightedCode = (new \Highlight\Highlighter())->highlight($language, $code)->value;

// Handle line numbering
if (option('kirby-extended.highlighter.line-numbering', false)) {
    $lines = preg_split('/\R/', $highlightedCode);
    $lineClass = option('kirby-extended.highlighter.line-numbering-class', 'hljs-code-line');
    $highlightedCode = '<span class="' . $lineClass . '">' . implode("</span>\n<span class=\"$lineClass\">", $lines) . '</span>';
}

?>
<pre class="<?= option('kirby-extended.highlighter.class', 'hljs') ?>"><code data-language="<?= $language ?>"><?= $highlightedCode ?></code></pre>
