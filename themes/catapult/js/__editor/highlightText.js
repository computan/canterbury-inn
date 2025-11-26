import { registerFormatType } from '@wordpress/rich-text';
import { RichTextToolbarButton } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';

const highlightIcon = wp.element.createElement(
	'svg',
	{
		width: 20,
		height: 20,
	},
	wp.element.createElement('path', {
		d: 'M18.73 5.86l-3.59-3.59a1 1 0 0 0-1.41 0l-10 10a1 1 0 0 0 0 1.41L4 14l-3 4h5l1-1 .29.29a1 1 0 0 0 1.41 0l10-10a1 1 0 0 0 .03-1.43zM7 15l-2-2 9-9 2 2z',
	})
);

const FormatHighlightText = function (props) {
	const selectedBlock = useSelect((select) => {
		return select('core/block-editor').getSelectedBlock();
	}, []);

	if (selectedBlock && selectedBlock.name !== 'core/paragraph') {
		return null;
	}

	return wp.element.createElement(RichTextToolbarButton, {
		icon: highlightIcon,
		title: 'Highlight Text',
		onClick() {
			props.onChange(
				wp.richText.toggleFormat(props.value, {
					type: 'catapult-formats/highlight-text',
				})
			);
		},
		isActive: props.isActive,
	});
};

const highlightText = () => {
	registerFormatType('catapult-formats/highlight-text', {
		title: 'Highlight Text',
		tagName: 'span',
		className: 'wp-block-paragraph--highlight-text',
		edit: FormatHighlightText,
	});
};

export default highlightText;
