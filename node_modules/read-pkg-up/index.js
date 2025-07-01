import path from 'path';
import findUp from 'find-up';
import {readPackageAsync, readPackageSync} from 'read-pkg';

export async function readPackageUpAsync(options) {
	const filePath = await findUp('package.json', options);
	if (!filePath) {
		return;
	}

	return {
		packageJson: await readPackageAsync({...options, cwd: path.dirname(filePath)}),
		path: filePath
	};
}

export function readPackageUpSync(options) {
	const filePath = findUp.sync('package.json', options);
	if (!filePath) {
		return;
	}

	return {
		packageJson: readPackageSync({...options, cwd: path.dirname(filePath)}),
		path: filePath
	};
}
