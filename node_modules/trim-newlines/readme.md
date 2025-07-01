# trim-newlines

> Trim [newlines](https://en.wikipedia.org/wiki/Newline) from the start and/or end of a string

## Install

```
$ npm install trim-newlines
```

## Usage

```js
import trimNewlines from 'trim-newlines';

trimNewlines('\n🦄\r\n');
//=> '🦄'

trimNewlines.start('\n🦄\r\n');
//=> '🦄\r\n'

trimNewlines.end('\n🦄\r\n');
//=> '\n🦄'
```

## API

### trimNewlines(string)

Trim from the start and end of a string.

### trimNewlines.start(string)

Trim from the start of a string.

### trimNewlines.end(string)

Trim from the end of a string.

## Related

- [trim-left](https://github.com/sindresorhus/trim-left) - Similar to `String#trim()` but removes only whitespace on the left
- [trim-right](https://github.com/sindresorhus/trim-right) - Similar to `String#trim()` but removes only whitespace on the right.

---

<div align="center">
	<b>
		<a href="https://tidelift.com/subscription/pkg/npm-trim-newlines?utm_source=npm-trim-newlines&utm_medium=referral&utm_campaign=readme">Get professional support for this package with a Tidelift subscription</a>
	</b>
	<br>
	<sub>
		Tidelift helps make open source sustainable for maintainers while giving companies<br>assurances about security, maintenance, and licensing for their dependencies.
	</sub>
</div>
