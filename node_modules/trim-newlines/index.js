export default function trimNewlines(string) {
	let start = 0;
	let end = string.length;

	while (start < end && (string[start] === '\r' || string[start] === '\n')) {
		start++;
	}

	while (end > start && (string[end - 1] === '\r' || string[end - 1] === '\n')) {
		end--;
	}

	return (start > 0 || end < string.length) ? string.slice(start, end) : string;
}

trimNewlines.start = string => {
	const end = string.length;
	let start = 0;

	while (start < end && (string[start] === '\r' || string[start] === '\n')) {
		start++;
	}

	return start > 0 ? string.slice(start, end) : string;
};

trimNewlines.end = string => {
	let end = string.length;

	while (end > 0 && (string[end - 1] === '\r' || string[end - 1] === '\n')) {
		end--;
	}

	return end < string.length ? string.slice(0, end) : string;
};
