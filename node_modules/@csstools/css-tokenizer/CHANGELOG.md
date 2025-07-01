# Changes to CSS Tokenizer

### 2.4.1

_July 5, 2024_

- Remove `astNode` that was erroneously added to the `ParseError` base class.

### 2.4.0

_July 5, 2024_

- Expose `ParseErrorMessage`, the list of known parser error messages object to facilitate detection of specific cases
- Add a specific `ParseErrorWithToken` subclass. This contains the associated token.

### 2.3.3

_July 3, 2024_

- Fix tokenization of `string-token` containing a backslash followed by CRLF

[Full CHANGELOG](https://github.com/csstools/postcss-plugins/tree/main/packages/css-tokenizer/CHANGELOG.md)
