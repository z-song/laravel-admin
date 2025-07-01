'use strict';

const handlePreserveConsecutiveUppercase = (decamelized, separator) => {
	// Lowercase all single uppercase characters. As we
	// want to preserve uppercase sequences, we cannot
	// simply lowercase the separated string at the end.
	// `data_For_USACounties` → `data_for_USACounties`
	decamelized = decamelized.replace(
		/((?<![\p{Uppercase_Letter}\d])[\p{Uppercase_Letter}\d](?![\p{Uppercase_Letter}\d]))/gu,
		$0 => {
			return $0.toLowerCase();
		}
	);

	// Remaining uppercase sequences will be separated from lowercase sequences.
	// `data_For_USACounties` → `data_for_USA_counties`
	return decamelized.replace(
		/(\p{Uppercase_Letter}+)(\p{Uppercase_Letter}\p{Lowercase_Letter}+)/gu,
		(_, $1, $2) => {
			return $1 + separator + $2.toLowerCase();
		}
	);
};

module.exports = (
	text,
	{
		separator = '_',
		preserveConsecutiveUppercase = false
	} = {}
) => {
	if (!(typeof text === 'string' && typeof separator === 'string')) {
		throw new TypeError(
			'The `text` and `separator` arguments should be of type `string`'
		);
	}

	// Checking the second character is done later on. Therefore process shorter strings here.
	if (text.length < 2) {
		return preserveConsecutiveUppercase ? text : text.toLowerCase();
	}

	const replacement = `$1${separator}$2`;

	// Split lowercase sequences followed by uppercase character.
	// `dataForUSACounties` → `data_For_USACounties`
	// `myURLstring → `my_URLstring`
	const decamelized = text.replace(
		/([\p{Lowercase_Letter}\d])(\p{Uppercase_Letter})/gu,
		replacement
	);

	if (preserveConsecutiveUppercase) {
		return handlePreserveConsecutiveUppercase(decamelized, separator);
	}

	// Split multiple uppercase characters followed by one or more lowercase characters.
	// `my_URLstring` → `my_url_string`
	return decamelized
		.replace(
			/(\p{Uppercase_Letter})(\p{Uppercase_Letter}\p{Lowercase_Letter}+)/gu,
			replacement
		)
		.toLowerCase();
};
