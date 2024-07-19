<?php

declare(strict_types = 1);

use Kirby\Cms\App;
use PHPUnit\Framework\TestCase;

class HighlightAdapterTest extends TestCase
{
    protected App $kirby;
    protected function tearDown(): void
    {
        restore_error_handler();
        restore_exception_handler();
    }

    public function setUp(): void
    {
        $this->kirby = new App([]);
    }

    public function testKirbyTextExplicitHighlighting()
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
            <pre class="hljs"><code data-language="css"><span class="hljs-selector-class">.foo</span> {
                <span class="hljs-attribute">color</span>: <span class="hljs-built_in">var</span>(--bar);
            }</code></pre>
            <pre class="hljs"><code data-language="js"><span class="hljs-keyword">export</span> <span class="hljs-keyword">const</span> foo = <span class="hljs-string">'bar'</span></code></pre>
            EOD;

        $this->assertEquals($expectedHtml, $this->kirby->kirbytext($text));
    }

    public function testKirbyTextSkipHighlighting()
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
            <pre class="hljs"><code data-language="js"><span class="hljs-keyword">export</span> <span class="hljs-keyword">const</span> foo = <span class="hljs-string">'bar'</span></code></pre>
            EOD;

        $this->assertEquals($expectedHtml, $this->kirby->kirbytext($text));
    }

    public function testKirbyTextAutoHighlighting()
    {
        $app = $this->kirby->clone([
            'options' => [
                'johannschopplich.highlighter.autodetect' => true
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
            <pre class="hljs"><code data-language=""><span class="hljs-selector-class">.foo</span> {
                <span class="hljs-attribute">color</span>: <span class="hljs-built_in">var</span>(--bar);
            }</code></pre>
            <pre class="hljs"><code data-language="js"><span class="hljs-keyword">export</span> <span class="hljs-keyword">const</span> foo = <span class="hljs-string">'bar'</span></code></pre>
            EOD;

        $this->assertEquals($expectedHtml, $app->kirbytext($text));
    }

    public function testCodeBlockHighlighting()
    {
        $code = <<<'EOD'
            .foo {
                color: var(--bar);
            }
            EOD;

        $expectedHtml = <<<'EOD'
            <pre class="hljs"><code data-language="css"><span class="hljs-selector-class">.foo</span> {
                <span class="hljs-attribute">color</span>: <span class="hljs-built_in">var</span>(--bar);
            }</code></pre>

            EOD;

        $block = new \Kirby\Cms\Block([
            'type' => 'code',
            'content' => [
                'language' => 'css',
                'code' => $code
            ]
        ]);

        $this->assertEquals(
            $expectedHtml,
            $block->toHtml()
        );
    }

    public function testCodeBlockHighlightingWithFallback()
    {
        $code = <<<'EOD'
            .foo {
                color: var(--bar);
            }
            EOD;

        $expectedHtml = <<<'EOD'
            <pre class="hljs"><code data-language="plaintext">.foo {
                color: var(--bar);
            }</code></pre>

            EOD;

        $block = new \Kirby\Cms\Block([
            'type' => 'code',
            'content' => [
                'language' => 'not-a-language',
                'code' => $code
            ]
        ]);

        $this->assertEquals(
            $expectedHtml,
            $block->toHtml()
        );
    }

    public function testCodeBlockWithBase64EncodedString()
    {
        $code = <<<'EOD'
            LmZvbyB7CiAgICBjb2xvcjogdmFyKC0tYmFyKTsKfQ==
            EOD;

        $expectedHtml = <<<'EOD'
            <pre class="hljs"><code data-language="css"><span class="hljs-selector-class">.foo</span> {
                <span class="hljs-attribute">color</span>: <span class="hljs-built_in">var</span>(--bar);
            }</code></pre>

            EOD;

        $block = new \Kirby\Cms\Block([
            'type' => 'code',
            'content' => [
                'language' => 'css',
                'code' => $code
            ]
        ]);

        $this->assertEquals(
            $expectedHtml,
            $block->toHtml()
        );
    }

    public function testCodeKirbyTag()
    {
        $code = <<<'EOD'
            (code: LmZvbyB7CiAgICBjb2xvcjogdmFyKC0tYmFyKTsKfQ== lang: css)
            EOD;

        $expectedHtml = <<<'EOD'
            <pre class="hljs"><code data-language="css"><span class="hljs-selector-class">.foo</span> {
                <span class="hljs-attribute">color</span>: <span class="hljs-built_in">var</span>(--bar);
            }</code></pre>
            EOD;

        $this->assertEquals(
            $expectedHtml,
            kirbytext($code)
        );
    }

    public function testCodeKirbyTagWithoutProperBase64EncodedString()
    {
        $code = <<<'EOD'
            (code: LmZvbyB7CiAgICBjb2___BROKEN___xvcjogdmFyKC0tYmFyKTsKfQ== lang: css)
            EOD;

        $expectedHtml = <<<'EOD'
            <pre class="hljs"><code data-language="css"><span class="hljs-selector-tag">LmZvbyB7CiAgICBjb2___BROKEN___xvcjogdmFyKC0tYmFyKTsKfQ</span>==</code></pre>
            EOD;

        $this->assertEquals(
            $expectedHtml,
            kirbytext($code)
        );
    }
}
