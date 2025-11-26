const { propertyOrdering, selectorOrdering } = require('stylelint-semantic-groups');

let customSelectorOrdering = [{type: 'at-rule', name: 'include', hasBlock: false}];

customSelectorOrdering = customSelectorOrdering.concat(selectorOrdering);

const customPropertyOrdering = propertyOrdering.map((groups) => {
	return groups.map((group) => {
		return {
			...group,
			emptyLineBefore: 'never',
		}
	});
});

module.exports = {
	plugins: ['stylelint-order'],
	extends: '@wordpress/stylelint-config/scss',
	rules: {
		'indentation': 'tab',
		'max-nesting-depth': null,
		'selector-max-compound-selectors': null,
		'selector-no-qualifying-type': [true, {
			'ignore': ['attribute', 'class', 'id']}
		],
		'selector-max-id': 2,
		'at-rule-empty-line-before': ['always', {
			'except': ['first-nested'],
			'ignore': ['blockless-after-same-name-blockless', 'after-comment'],
			'ignoreAtRules': ['else']
		}],
		'rule-empty-line-before': ['always', {
			'except': ['first-nested', 'after-single-line-comment']
		}],
		'declaration-empty-line-before': ['always', {
			'except': ['first-nested', 'after-declaration']
		}],
		'selector-class-pattern': null,
		'max-line-length': null,
		'max-empty-lines': 1,
		'no-descending-specificity': null,
		'no-invalid-position-at-import-rule': null,
		'order/order': customSelectorOrdering,
		'order/properties-order': customPropertyOrdering,
		'color-named': ['never', {
			'ignore': ['inside-function']
		}],
		'function-url-quotes': null,
		'block-no-empty': null,
		"unit-disallowed-list": ["px"],
	},
};
