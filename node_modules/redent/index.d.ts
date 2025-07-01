import {Options} from 'indent-string';

/**
[Strip redundant indentation](https://github.com/sindresorhus/strip-indent) and [indent the string](https://github.com/sindresorhus/indent-string).

@param string - The string to normalize indentation.
@param count - How many times you want `options.indent` repeated. Default: `0`.

@example
```
import redent from 'redent';

redent('\n  foo\n    bar\n', 1);
//=> '\n foo\n   bar\n'
```
*/
export default function redent(
	string: string,
	count?: number,
	options?: Options
): string;

export {Options};
