import {
	registerBlockStyle,
	unregisterBlockStyle,
	store,
} from '@wordpress/blocks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { useSelect } from '@wordpress/data';

let textWidthStylesRegistered = false;

/**
 * Add text width block styles.
 */
const registerTextWidthStyles = () => {
	registerBlockStyle('core/heading', {
		name: 'narrow',
		label: 'Narrow Width',
	});

	registerBlockStyle('core/heading', {
		name: 'wide',
		label: 'Wide Width',
	});

	registerBlockStyle('core/heading', {
		name: 'full',
		label: 'Full Width',
	});

	registerBlockStyle('core/paragraph', {
		name: 'narrow',
		label: 'Narrow Width',
	});

	registerBlockStyle('core/paragraph', {
		name: 'wide',
		label: 'Wide Width',
	});

	registerBlockStyle('core/paragraph', {
		name: 'full',
		label: 'Full Width',
	});

	textWidthStylesRegistered = true;
};

/**
 * Add options to blocks with text width styles sidebar settings.
 *
 * @param {Function} BlockEdit Original component.
 *
 * @return {Function} Wrapped component.
 */
const textWidthStylesFilter = createHigherOrderComponent((BlockEdit) => {
	return (props) => {
		if ('core/paragraph' !== props.name && 'core/heading' !== props.name) {
			return <BlockEdit {...props} />;
		}

		if (props.isSelected) {
			if (isBlockWithoutStyles(props.clientId)) {
				if (true === textWidthStylesRegistered) {
					unregisterBlockStyle('core/paragraph', 'narrow');
					unregisterBlockStyle('core/paragraph', 'wide');
					unregisterBlockStyle('core/paragraph', 'full');
					unregisterBlockStyle('core/heading', 'narrow');
					unregisterBlockStyle('core/heading', 'wide');
					unregisterBlockStyle('core/heading', 'full');
					textWidthStylesRegistered = false;
				}
			} else if (false === textWidthStylesRegistered) {
				registerTextWidthStyles();
			}
		}

		return <BlockEdit {...props} />;
	};
}, 'textWidthStyles');

/**
 * Checks to see if the blocks should have the custom styles.
 *
 * @param {number} clientId The client ID of the block to check.
 *
 * @return {boolean} Whether or not the media block has custom styles.
 */
const isBlockWithoutStyles = (clientId) => {
	// eslint-disable-next-line react-hooks/rules-of-hooks
	return useSelect((select) => {
		const { getBlockParents, getBlocksByClientId } =
			select('core/block-editor');

		const parentIds = getBlockParents(clientId);
		const parents = getBlocksByClientId(parentIds);

		if (!parents || 0 === parents.length) {
			return false;
		}

		const firstParentName = parents[parents.length - 1].name;

		const firstParentBlockType =
			select(store).getBlockType(firstParentName);

		if (
			firstParentBlockType &&
			firstParentBlockType.provides_context &&
			firstParentBlockType.provides_context.text_width_styles
		) {
			return false;
		}

		return true;
	});
};

const textWidthStyles = () => {
	registerTextWidthStyles();

	wp.hooks.addFilter(
		'editor.BlockEdit',
		'catapult/text-width-styles',
		textWidthStylesFilter
	);
};
export default textWidthStyles;
