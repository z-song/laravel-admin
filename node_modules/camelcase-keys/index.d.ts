import {CamelCase, PascalCase} from 'type-fest';

// eslint-disable-next-line @typescript-eslint/ban-types
type EmptyTuple = [];

/**
Return a default type if input type is nil.

@template T - Input type.
@template U - Default type.
*/
type WithDefault<T, U extends T> = T extends undefined | void | null ? U : T;

/**
Check if an element is included in a tuple.

TODO: Remove this once https://github.com/sindresorhus/type-fest/pull/217 is merged.
*/
type IsInclude<List extends readonly unknown[], Target> = List extends undefined
	? false
	: List extends Readonly<EmptyTuple>
		? false
		: List extends readonly [infer First, ...infer Rest]
			? First extends Target
				? true
				: IsInclude<Rest, Target>
			: boolean;

/**
Append a segment to dot-notation path.
*/
type AppendPath<S extends string, Last extends string> = S extends ''
	? Last
	: `${S}.${Last}`;

/**
Convert keys of an object to camelcase strings.
*/
type CamelCaseKeys<
	T extends Record<string, any> | readonly any[],
	Deep extends boolean,
	IsPascalCase extends boolean,
	Exclude extends readonly unknown[],
	StopPaths extends readonly string[],
	Path extends string = ''
> = T extends readonly any[]
	// Handle arrays or tuples.
	? {
		[P in keyof T]: CamelCaseKeys<
		T[P],
		Deep,
		IsPascalCase,
		Exclude,
		StopPaths
		>;
	}
	: T extends Record<string, any>
		// Handle objects.
		? {
			[P in keyof T & string as [IsInclude<Exclude, P>] extends [true]
				? P
				: [IsPascalCase] extends [true]
					? PascalCase<P>
					: CamelCase<P>]: [IsInclude<StopPaths, AppendPath<Path, P>>] extends [
				true
			]
				? T[P]
				: [Deep] extends [true]
					? CamelCaseKeys<
					T[P],
					Deep,
					IsPascalCase,
					Exclude,
					StopPaths,
					AppendPath<Path, P>
					>
					: T[P];
		}
		// Return anything else as-is.
		: T;

declare namespace camelcaseKeys {
	interface Options {
		/**
		Recurse nested objects and objects in arrays.

		@default false
		*/
		readonly deep?: boolean;

		/**
		Exclude keys from being camel-cased.

		If this option can be statically determined, it's recommended to add `as const` to it.

		@default []
		*/
		readonly exclude?: ReadonlyArray<string | RegExp>;

		/**
		Exclude children at the given object paths in dot-notation from being camel-cased. For example, with an object like `{a: {b: '🦄'}}`, the object path to reach the unicorn is `'a.b'`.

		If this option can be statically determined, it's recommended to add `as const` to it.

		@default []

		@example
		```
		camelcaseKeys({
			a_b: 1,
			a_c: {
				c_d: 1,
				c_e: {
					e_f: 1
				}
			}
		}, {
			deep: true,
			stopPaths: [
				'a_c.c_e'
			]
		}),
		// {
		// 	aB: 1,
		// 	aC: {
		// 		cD: 1,
		// 		cE: {
		// 			e_f: 1
		// 		}
		// 	}
		// }
		```
		*/
		readonly stopPaths?: readonly string[];

		/**
		Uppercase the first character as in `bye-bye` → `ByeBye`.

		@default false
		*/
		readonly pascalCase?: boolean;
	}
}

/**
Convert object keys to camel case using [`camelcase`](https://github.com/sindresorhus/camelcase).

@param input - Object or array of objects to camel-case.

@example
```
import camelcaseKeys = require('camelcase-keys');

// Convert an object
camelcaseKeys({'foo-bar': true});
//=> {fooBar: true}

// Convert an array of objects
camelcaseKeys([{'foo-bar': true}, {'bar-foo': false}]);
//=> [{fooBar: true}, {barFoo: false}]

camelcaseKeys({'foo-bar': true, nested: {unicorn_rainbow: true}}, {deep: true});
//=> {fooBar: true, nested: {unicornRainbow: true}}

// Convert object keys to pascal case
camelcaseKeys({'foo-bar': true, nested: {unicorn_rainbow: true}}, {deep: true, pascalCase: true});
//=> {FooBar: true, Nested: {UnicornRainbow: true}}

import minimist = require('minimist');

const argv = minimist(process.argv.slice(2));
//=> {_: [], 'foo-bar': true}

camelcaseKeys(argv);
//=> {_: [], fooBar: true}
```
*/
declare function camelcaseKeys<
	T extends Record<string, any> | readonly any[],
	Options extends camelcaseKeys.Options = camelcaseKeys.Options
>(
	input: T,
	options?: Options
): CamelCaseKeys<
T,
WithDefault<Options['deep'], false>,
WithDefault<Options['pascalCase'], false>,
WithDefault<Options['exclude'], EmptyTuple>,
WithDefault<Options['stopPaths'], EmptyTuple>
>;

export = camelcaseKeys;
