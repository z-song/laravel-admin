# read-pkg

> Read a package.json file

## Why

- [Throws more helpful JSON errors](https://github.com/sindresorhus/parse-json)
- [Normalizes the data](https://github.com/npm/normalize-package-data#what-normalization-currently-entails)

## Install

```
$ npm install read-pkg
```

## Usage

```js
import {readPackageAsync} from 'read-pkg';

console.log(await readPkg());
//=> {name: 'read-pkg', …}

console.log(await readPkg({cwd: 'some-other-directory'}));
//=> {name: 'unicorn', …}
```

## API

### readPackageAsync(options?)

Returns a `Promise<object>` with the parsed JSON.

### readPackageSync(options?)

Returns the parsed JSON.

#### options

Type: `object`

##### cwd

Type: `string`\
Default: `process.cwd()`

Current working directory.

##### normalize

Type: `boolean`\
Default: `true`

[Normalize](https://github.com/npm/normalize-package-data#what-normalization-currently-entails) the package data.

## Related

- [read-pkg-up](https://github.com/sindresorhus/read-pkg-up) - Read the closest package.json file
- [write-pkg](https://github.com/sindresorhus/write-pkg) - Write a `package.json` file
- [load-json-file](https://github.com/sindresorhus/load-json-file) - Read and parse a JSON file

---

<div align="center">
	<b>
		<a href="https://tidelift.com/subscription/pkg/npm-read-pkg?utm_source=npm-read-pkg&utm_medium=referral&utm_campaign=readme">Get professional support for this package with a Tidelift subscription</a>
	</b>
	<br>
	<sub>
		Tidelift helps make open source sustainable for maintainers while giving companies<br>assurances about security, maintenance, and licensing for their dependencies.
	</sub>
</div>
