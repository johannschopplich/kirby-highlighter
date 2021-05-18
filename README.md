# Kirby Highlighter

> Server-side code highlighting for KirbyText.

Built upon [highlight.php](http://www.highlightjs.org) which itself is a port of [highlight.js](http://www.highlightjs.org). Features include:
- 🏳️‍🌈 Supports 189 languages
- 💫 94 styles available
- ⛳️ Automatic language detection

## Requirements

- Kirby 3
- PHP 7.4+

## Installation

### Download

Download and copy this repository to `/site/plugins/kirby-highlighter`.

### Git submodule

```
git submodule add https://github.com/johannschopplich/kirby-highlighter.git site/plugins/kirby-highlighter
```

### Composer

```
composer require johannschopplich/kirby-highlighter
```

## Usage

Create a code block in your KirbyText field and optionally set the code language:

<pre lang="no-highlight"><code>```css
.currentColor {
  color: currentColor;
}
```
</code></pre>

Which outputs:

```html
<pre class="hljs"><code><span class="hljs-selector-class">.currentColor</span> {
    <span class="hljs-attribute">color</span>: currentColor;
}</code></pre>
```

The syntax highlighting functionality can be changed. You can choose between two highlighting modes:
1. Explicit mode (default)
2. Automatic language detection mode (opt-in)

### Explicit mode

In explicit mode, you have to define which language the code block is. Otherwise highlighting will be skipped.

### Automatic language detection mode

Alternatively you can use the automatic detection mode, which highlights your code with the language the library thinks is best. It is highly recommended you explicitly choose the language or limit the number of languages to automatically detect from. This reduces the number of inaccuracies and skips this extremely inefficient selection process.

To enable automatic language detection, set:
- `kirby-extended.highlighter.autodetect` to `true`
- `kirby-extended.highlighter.languages` to an array of names from which languages should be chosen

## Options

| Option | Default | Description |
| --- | --- | --- |
| `kirby-extended.highlighter.class` | `hljs` | Style class for Highlight to be added to the `pre` element. |
| `kirby-extended.highlighter.autodetect` | `false` | Indicates if the library should define which language thinks is best. Only applies when no language was set on the KirbyText code block. |
| `kirby-extended.highlighter.languages` | `[]` | Array of language names to be auto-detected. If empty, every language will be auto-detectable. |
| `kirby-extended.highlighter.line-numbering` | `false` | Indicates if the library should split up the highlighted code on each new line and wrap it in a `<span>` element. |
| `kirby-extended.highlighter.line-numbering-class` | `hljs-code-line` | CSS class applied to highlighted code lines, respectively `<span>` elements. |

## Styling in the frontend

Since this plugin handles highlighting code only and thus just wraps span's around code, you have to link styles in your frontend yourself. I recommend choosing one of the available themes directly from the highlight.js project: [highlight.js/src/styles/](https://github.com/highlightjs/highlight.js/tree/master/src/styles)

The CSS files over at the repository are maintained and new ones arrive from time to time, therefore it would be redundant to include a copy in this repository.

One of my favorite themes is [Night Owl by Sarah Drasner](https://github.com/highlightjs/highlight.js/blob/master/src/styles/night-owl.css).
For example you could download the CSS file and save it in your Kirby project under `assets/css/hljs-night-owl.css`. Now you just have to include it in your template `<?= css('assets/css/hljs-night-owl.css') ?>`. Alternatively, use a CSS bundler of your choice.

### Line Numbering

If you choose to activate the line numbering option, you will need to include additional CSS style to display line numbering.

A basic example using [pseudo-elements](https://developer.mozilla.org/en-US/docs/Web/CSS/Pseudo-elements) :
```css
pre.hljs .hljs-code-line {
  counter-increment: line;
}

pre.hljs .hljs-code-line::before {
  content: counter(line);
  display: inline-block;
  margin-right: 1em;
  opacity: 0.5;
}
```

## Credits

- Geert Bergman and contributors for the awesome [highlight.php](https://github.com/scrivo/highlight.php) port
- Martin Folkers for his [Kirby Highlight](https://github.com/S1SYPHOS/kirby3-highlight) plugin which built the base of this package

## License

[MIT](https://opensource.org/licenses/MIT)
