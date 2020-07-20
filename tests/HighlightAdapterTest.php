<?php

use Kirby\Cms\App as Kirby;
use KirbyExtended\HighlightAdapter;
use PHPUnit\Framework\TestCase;

class HighlightAdapterTest extends TestCase
{
    protected $fixtures;
    protected $kirby;

    public function setUp(): void
    {
        $this->kirby = new Kirby([
            'roots' => [
                'index' => $this->fixtures = __DIR__ . '/fixtures'
            ]
        ]);

        // Dir::make($this->fixtures . '/site');
    }

    public function tearDown(): void
    {
        // Dir::remove($this->fixtures . '/site');
    }

    public function testHighlightAdapter()
    {
        $html = <<<'EOD'
<pre><code class="language-css">.currentColor {
    color: currentColor;
}</code></pre>
EOD;

        $expectedHtml = <<<'EOD'
<pre class="hljs"><code><span class="hljs-selector-class">.currentColor</span> {
    <span class="hljs-attribute">color</span>: currentColor;
}</code></pre>
EOD;

        $this->assertEquals($expectedHtml, HighlightAdapter::highlight($html));
    }

    public function testKirbytextExplicitHighlighting()
    {
        $text = <<<'EOD'
```css
.currentColor {
    color: currentColor;
}
```
EOD;

        $expectedHtml = <<<'EOD'
<pre class="hljs"><code><span class="hljs-selector-class">.currentColor</span> {
    <span class="hljs-attribute">color</span>: currentColor;
}</code></pre>
EOD;

        $this->assertEquals($expectedHtml, $this->kirby->kirbytext($text));
    }

    public function testKirbytextSkipHighlighting()
    {
        $text = <<<'EOD'
```
.currentColor {
    color: currentColor;
}
```
EOD;
        $expectedHtml = <<<'EOD'
<pre><code>.currentColor {
    color: currentColor;
}</code></pre>
EOD;

        $this->assertEquals($expectedHtml, $this->kirby->kirbytext($text));
    }

    public function testKirbytextAutoHighlighting()
    {
        $app = $this->kirby->clone([
            'options' => [
                'kirby-extended.highlight.autodetect' => true
            ]
        ]);

        $text = <<<'EOD'
```
<!DOCTYPE html>
<main>Content</main>
```
EOD;

        $expectedHtml = <<<'EOD'
<pre class="hljs"><code><span class="hljs-meta">&lt;!DOCTYPE <span class="hljs-meta-keyword">html</span>&gt;</span>
<span class="hljs-tag">&lt;<span class="hljs-name">main</span>&gt;</span>Content<span class="hljs-tag">&lt;/<span class="hljs-name">main</span>&gt;</span></code></pre>
EOD;

        $this->assertEquals($expectedHtml, $app->kirbytext($text));
    }

    public function testUmlauts()
    {
        $text = 'Äöü';
        $expectedHtml = '<p>&Auml;&ouml;&uuml;</p>';

        $this->assertEquals($expectedHtml, $this->kirby->kirbytext($text));
    }
}
