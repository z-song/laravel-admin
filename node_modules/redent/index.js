import stripIndent from 'strip-indent';
import indentString from 'indent-string';

export default function redent(string, count = 0, options = {}) {
	return indentString(stripIndent(string), count, options);
}
