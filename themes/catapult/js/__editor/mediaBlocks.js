import { createHigherOrderComponent } from '@wordpress/compose';
import { Fragment, useEffect } from '@wordpress/element';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, RangeControl } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';

/**
 * Add options to image block sidebar settings.
 *
 * @param {Function} BlockEdit Original component.
 *
 * @return {Function} Wrapped component.
 */
const mediaStandardBlockOptions = createHigherOrderComponent((BlockEdit) => {
	return (props) => {
		if (
			'core/image' !== props.name ||
			!isCustomMediaBlock(props.clientId)
		) {
			return <BlockEdit {...props} />;
		}

		const { attributes, setAttributes } = props;
		const { width, url, id, sizeSlug } = attributes;

		let columnSize = 6;

		if (width) {
			columnSize = Math.round((parseInt(width, 10) / 1312) * 12);
		}

		const media = getMediaById(id);

		useEffect(() => {
			let newUrl = '';

			if (media?.media_details?.sizes?.[sizeSlug]?.source_url) {
				newUrl = media.media_details.sizes[sizeSlug].source_url;
			} else if (media?.media_details?.sizes?.thumbnail?.source_url) {
				newUrl = media.media_details.sizes.thumbnail.source_url;
			}

			if (newUrl && newUrl !== url) {
				setAttributes({
					url: newUrl,
				});
			}
		}, [id, sizeSlug, media, url, setAttributes]);

		return (
			<Fragment>
				<BlockEdit {...props} />
				<InspectorControls>
					<PanelBody title="Column options">
						<RangeControl
							label="Column size"
							value={columnSize}
							initialPosition={12}
							min={4}
							max={12}
							onChange={(value) => {
								value = roundToClosestValue(
									value,
									[4, 6, 8, 12]
								);

								setAttributes({
									width: `${Math.round(
										(value / 12) * (1312 - 32 * 11) +
											(value - 1) * 32
									)}px`,
									sizeSlug: 'col-' + value,
								});
							}}
						/>
					</PanelBody>
				</InspectorControls>
			</Fragment>
		);
	};
}, 'mediaStandardBlockOptions');

/**
 * Add column classes to image and embed blocks and make sure the correct image size is used.
 *
 * @param {Function} BlockEdit Original component.
 *
 * @return {Function} Wrapped component.
 */
const mediaStandardBlockEditorWrapper = createHigherOrderComponent(
	(BlockListBlock) => {
		return (props) => {
			if (
				'core/image' !== props.name ||
				!isCustomMediaBlock(props.clientId)
			) {
				return <BlockListBlock {...props} />;
			}

			const { attributes, setAttributes } = props;
			const { width, className, url } = attributes;
			let newClassName = '';

			let value = Math.round((parseInt(width, 10) / 1312) * 12);

			value = roundToClosestValue(value, [4, 6, 8, 12]);

			if (className) {
				newClassName = className
					.replace(/col-[0-9]*/gm, '')
					.replace(/\s+/g, ' ');
			}

			newClassName = 'col-' + value + ' ' + newClassName;

			setAttributes({
				width: `${Math.round(
					(value / 12) * (1312 - 32 * 11) + (value - 1) * 32
				)}px`,
				className: newClassName,
				sizeSlug: 'col-' + value,
			});

			if (
				'library_block' ===
				wp.data.select('core/editor').getCurrentPostType()
			) {
				if (
					url &&
					window.catapult &&
					window.catapult.stylesheetUrl &&
					url.includes('default-image' || url.includes('placeholder'))
				) {
					setAttributes({
						url:
							window.catapult.stylesheetUrl +
							'/images/block-library/placeholder.png',
					});
				}
			}

			return <BlockListBlock {...props} />;
		};
	},
	'columnsBlockEditorWrapper'
);

/**
 * Rounds to the the closest value out of an array of values.
 *
 * @param {number} value  The value to round.
 * @param {Array}  values The array of values to round to.
 *
 * @return {number} The closest value.
 */
const roundToClosestValue = (value, values) => {
	let closest = values[0];

	for (const item of values) {
		if (Math.abs(item - value) < Math.abs(closest - value)) {
			closest = item;
		}
	}

	return closest;
};

/**
 * Check to see if the media block should have the custom settings.
 *
 * @param {number} clientId The client ID of the block to check.
 *
 * @return {boolean} Whether or not the media block is within a block that uses the custom settings.
 */
const isCustomMediaBlock = (clientId) => {
	// eslint-disable-next-line react-hooks/rules-of-hooks
	return useSelect((select) => {
		const { getBlockParents, getBlocksByClientId } =
			select('core/block-editor');

		const parentIds = getBlockParents(clientId);
		const parents = getBlocksByClientId(parentIds);

		if (!parents || 0 === parents.length) {
			return true;
		}

		return parents.some((block) => {
			if (
				'acf/media-standard' === block.name ||
				'acf/content-section' === block.name
			) {
				return true;
			}

			return false;
		});
	});
};

const getMediaById = (imageId) => {
	// eslint-disable-next-line react-hooks/rules-of-hooks
	return useSelect((select) => {
		const { getMedia } = select(coreStore);

		return getMedia(imageId);
	});
};

const mediaBlocks = () => {
	wp.hooks.addFilter(
		'editor.BlockEdit',
		'catapult/media-standard-block',
		mediaStandardBlockOptions
	);

	wp.hooks.addFilter(
		'editor.BlockListBlock',
		'catapult/media-standard-block',
		mediaStandardBlockEditorWrapper
	);
};

export default mediaBlocks;
