/*
  @license
	Rollup.js v4.44.1
	Thu, 26 Jun 2025 04:33:05 GMT - commit 5a7f9e215a11de165b85dafd64350474847ec6db

	https://github.com/rollup/rollup

	Released under the MIT License.
*/
export { version as VERSION, defineConfig, rollup, watch } from './shared/node-entry.js';
import './shared/parseAst.js';
import '../native.js';
import 'node:path';
import 'path';
import 'node:process';
import 'node:perf_hooks';
import 'node:fs/promises';
