# decamelize

> Convert a camelized string into a lowercased one with a custom separator\
> Example: `unicornRainbow` → `unicorn_rainbow`

If you use this on untrusted user input, don't forget to limit the length to something reasonable.

## Install

```
$ npm install decamelize
```

## Usage

```js
const decamelize = require('decamelize');

decamelize('unicornRainbow');
//=> 'unicorn_rainbow'

decamelize('unicornRainbow', {separator: '-'});
//=> 'unicorn-rainbow'

decamelize('testGUILabel', {preserveConsecutiveUppercase: true});
//=> 'test_GUI_label'

decamelize('testGUILabel', {preserveConsecutiveUppercase: false});
//=> 'test_gui_label'
```

## API

### decamelize(input, options?)

#### input

Type: `string`

#### options

Type: `object`

##### separator

Type: `string`\
Default: `'_'`

Character or string inserted to separate words in `string`.

```js
cosnt decamelize = require('decamelize');

decamelize('unicornRainbow');
//=> 'unicorn_rainbow'

decamelize('unicornRainbow', {separator: '-'});
//=> 'unicorn-rainbow'
```

##### preserveConsecutiveUppercase

Type: `boolean`\
Default: `false`

Preserve sequences of uppercase characters.

```js
const decamelize = require('decamelize');

decamelize('testGUILabel');
//=> 'test_gui_label'

decamelize('testGUILabel', {preserveConsecutiveUppercase: true});
//=> 'test_GUI_label'
```

## Related

See [`camelcase`](https://github.com/sindresorhus/camelcase) for the inverse.

---

<div align="center">
	<b>
		<a href="https://tidelift.com/subscription/pkg/npm-decamelize?utm_source=npm-decamelize&utm_medium=referral&utm_campaign=readme">Get professional support for this package with a Tidelift subscription</a>
	</b>
	<br>
	<sub>
		Tidelift helps make open source sustainable for maintainers while giving companies<br>assurances about security, maintenance, and licensing for their dependencies.
	</sub>
</div>
