import { unregisterFormatType } from '@wordpress/rich-text';
import { unregisterBlockStyle } from '@wordpress/blocks';

const removeFormats = () => {
	unregisterFormatType('core/strikethrough');
	unregisterFormatType('core/image');
	unregisterFormatType('core/subscript');
	unregisterFormatType('core/superscript');
	unregisterFormatType('core/text-color');

	unregisterBlockStyle('core/button', 'fill');
	unregisterBlockStyle('core/button', 'outline');
	unregisterBlockStyle('core/separator', 'wide');
	unregisterBlockStyle('core/separator', 'dots');
	unregisterBlockStyle('core/quote', 'plain');
	unregisterBlockStyle('core/quote', 'large');
	unregisterBlockStyle('core/image', 'rounded');
	unregisterBlockStyle('core/table', 'regular');
	unregisterBlockStyle('core/table', 'stripes');
};

export default removeFormats;
