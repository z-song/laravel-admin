# redent

> [Strip redundant indentation](https://github.com/sindresorhus/strip-indent) and [indent the string](https://github.com/sindresorhus/indent-string)

## Install

```
$ npm install redent
```

## Usage

```js
import redent from 'redent';

redent('\n  foo\n    bar\n', 1);
//=> '\n foo\n   bar\n'
```

## API

### redent(string, count?, options?)

#### string

Type: `string`

The string to normalize indentation.

#### count

Type: `number`\
Default: `0`

How many times you want `options.indent` repeated.

#### options

Type: `object`

##### indent

Type: `string`\
Default: `' '`

The string to use for the indent.

##### includeEmptyLines

Type: `boolean`\
Default: `false`

Also indent empty lines.

---

<div align="center">
	<b>
		<a href="https://tidelift.com/subscription/pkg/npm-redent?utm_source=npm-redent&utm_medium=referral&utm_campaign=readme">Get professional support for this package with a Tidelift subscription</a>
	</b>
	<br>
	<sub>
		Tidelift helps make open source sustainable for maintainers while giving companies<br>assurances about security, maintenance, and licensing for their dependencies.
	</sub>
</div>
