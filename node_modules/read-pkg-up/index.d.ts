import {Except} from 'type-fest';
import {readPackageAsync, readPackageSync, Options as ReadPackageOptions, NormalizeOptions as ReadPackageNormalizeOptions, PackageJson, NormalizedPackageJson} from 'read-pkg';

export type Options = {
	/**
	Directory to start looking for a package.json file.

	@default process.cwd()
	*/
	cwd?: string;
} & Except<ReadPackageOptions, 'cwd'>;

export type NormalizeOptions = {
	/**
	Directory to start looking for a package.json file.

	@default process.cwd()
	*/
	cwd?: string;
} & Except<ReadPackageNormalizeOptions, 'cwd'>;

export interface ReadResult {
	packageJson: PackageJson;
	path: string;
}

export interface NormalizedReadResult {
	packageJson: NormalizedPackageJson;
	path: string;
}

export {
	PackageJson,
	NormalizedPackageJson
};

/**
Read the closest `package.json` file.

@example
```
import {readPackageUpAsync} from 'read-pkg-up';

console.log(await readPackageUpAsync());
// {
// 	packageJson: {
// 		name: 'awesome-package',
// 		version: '1.0.0',
// 		…
// 	},
// 	path: '/Users/sindresorhus/dev/awesome-package/package.json'
// }
```
*/
export function readPackageUpAsync(options?: NormalizeOptions): Promise<NormalizedReadResult | undefined>;
export function readPackageUpAsync(options: Options): Promise<ReadResult | undefined>;

/**
Synchronously read the closest `package.json` file.

@example
```
import {readPackageUpSync} from 'read-pkg-up';

console.log(readPackageUpSync());
// {
// 	packageJson: {
// 		name: 'awesome-package',
// 		version: '1.0.0',
// 		…
// 	},
// 	path: '/Users/sindresorhus/dev/awesome-package/package.json'
// }
```
*/
export function readPackageUpSync(options?: NormalizeOptions): NormalizedReadResult | undefined;
export function readPackageUpSync(options: Options): ReadResult | undefined;
