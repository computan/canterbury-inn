import { registerBlockStyle } from '@wordpress/blocks';

registerBlockStyle('core/table', {
	name: 'flip',
	label: 'Flip',
	isDefault: true,
});

registerBlockStyle('core/table', {
	name: 'scroll',
	label: 'Scroll',
});

registerBlockStyle('core/table', {
	name: 'stack',
	label: 'Stack',
});
