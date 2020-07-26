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
<pre><code class="language-css">.foo {
    color: var(--bar);
}</code></pre>

<pre><code class="language-js">export const foo = 'bar'</code></pre>
EOD;

        $expectedHtml = <<<'EOD'
<pre class="hljs"><code><span class="hljs-selector-class">.foo</span> {
    <span class="hljs-attribute">color</span>: <span class="hljs-built_in">var</span>(--bar);
}</code></pre>

<pre class="hljs"><code><span class="hljs-keyword">export</span> <span class="hljs-keyword">const</span> foo = <span class="hljs-string">'bar'</span></code></pre>
EOD;

        $this->assertEquals($expectedHtml, HighlightAdapter::highlight($html));
    }

    public function testKirbytextExplicitHighlighting()
    {
        $text = <<<'EOD'
```css
.foo {
    color: var(--bar);
}
```

```js
export const foo = 'bar'
```
EOD;

        $expectedHtml = <<<'EOD'
<pre class="hljs"><code><span class="hljs-selector-class">.foo</span> {
    <span class="hljs-attribute">color</span>: <span class="hljs-built_in">var</span>(--bar);
}</code></pre>
<pre class="hljs"><code><span class="hljs-keyword">export</span> <span class="hljs-keyword">const</span> foo = <span class="hljs-string">'bar'</span></code></pre>
EOD;

        $this->assertEquals($expectedHtml, $this->kirby->kirbytext($text));
    }

    public function testKirbytextSkipHighlighting()
    {
        $text = <<<'EOD'
```
.foo {
    color: var(--bar);
}
```

```js
export const foo = 'bar'
```
EOD;
        $expectedHtml = <<<'EOD'
<pre><code>.foo {
    color: var(--bar);
}</code></pre>
<pre class="hljs"><code><span class="hljs-keyword">export</span> <span class="hljs-keyword">const</span> foo = <span class="hljs-string">'bar'</span></code></pre>
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
.foo {
    color: var(--bar);
}
```

```js
export const foo = 'bar'
```
EOD;

        $expectedHtml = <<<'EOD'
<pre class="hljs"><code><span class="hljs-selector-class">.foo</span> {
    <span class="hljs-attribute">color</span>: <span class="hljs-built_in">var</span>(--bar);
}</code></pre>
<pre class="hljs"><code><span class="hljs-keyword">export</span> <span class="hljs-keyword">const</span> foo = <span class="hljs-string">'bar'</span></code></pre>
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
