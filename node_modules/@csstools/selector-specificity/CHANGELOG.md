# Changes to Selector Specificity

### 3.1.1

_May 13, 2024_

- Prevent mutation of selectors with An+B microsyntax (e.g. `:nth-child(2n+1 of .foo)`) during specificity calculation

### 3.1.0

_May 11, 2024_

- Add an option to `selectorSpecificity` and `specificityOfMostSpecificListItem` to customize the calculation
- Add `specificityOfMostSpecificListItem` as an exported function

### 3.0.3

_March 31, 2024_

- Add support for:
	- `:active-view-transition`
	- `:active-view-transition-type(foo)`

[Full CHANGELOG](https://github.com/csstools/postcss-plugins/tree/main/packages/selector-specificity/CHANGELOG.md)
