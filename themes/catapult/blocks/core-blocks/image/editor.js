import { createHigherOrderComponent } from '@wordpress/compose';
import { Fragment, useState, useEffect } from '@wordpress/element';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, Spinner } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';

/**
 * Add image block additional attributes.
 *
 * @param {Object} settings Registered block settings.
 * @param {string} name     Block name.
 *
 * @return {Object} Filtered block settings.
 */
const registerImageCoreBlockOptions = (settings, name) => {
	if ('core/image' !== name) {
		return settings;
	}

	return Object.assign({}, settings, {
		attributes: Object.assign({}, settings.attributes, {
			videoURL: {
				type: 'string',
				default: '',
			},
		}),
	});
};

/**
 * Add options to image block sidebar settings.
 *
 * @param {Function} BlockEdit Original component.
 *
 * @return {Function} Wrapped component.
 */
const imageCoreBlockOptions = createHigherOrderComponent((BlockEdit) => {
	return (props) => {
		if ('core/image' !== props.name) {
			return <BlockEdit {...props} />;
		}
		const { attributes, setAttributes } = props;
		const { videoURL } = attributes;
		const [videoInput, setVideoInput] = useState('');

		useEffect(() => {
			if (videoURL) {
				setVideoInput(videoURL);
			}
		}, [videoURL]);

		const { preview, fetching } = useSelect(
			(select) => {
				const { getEmbedPreview, isRequestingEmbedPreview } =
					select('core');
				if (!videoURL) {
					return { fetching: false, cannotEmbed: false };
				}

				const embedPreview = getEmbedPreview(videoURL);

				// The external oEmbed provider does not exist. We got no type info and no html.
				const badEmbedProvider =
					embedPreview?.html === false &&
					embedPreview?.type === undefined;
				// Some WordPress URLs that can't be embedded will cause the API to return
				// a valid JSON response with no HTML and `data.status` set to 404, rather
				// than generating a fallback response as other embeds do.
				const wordpressCantEmbed = embedPreview?.data?.status === 404;
				const validPreview =
					!!embedPreview && !badEmbedProvider && !wordpressCantEmbed;
				return {
					preview: validPreview ? embedPreview : undefined,
					fetching: isRequestingEmbedPreview(videoURL),
				};
			},
			[videoURL]
		);

		return (
			<Fragment>
				<BlockEdit {...props} />
				<InspectorControls>
					<PanelBody title="Video">
						<TextControl
							label={__('Video URL', 'catapult')}
							value={videoInput}
							type="string"
							help={__(
								'Please ensure you upload an image when using this field.',
								'catapult'
							)}
							onChange={(value) => {
								setVideoInput(value);
								setAttributes({
									videoURL: value,
								});
							}}
						/>

						{videoURL && fetching && (
							<div>
								<Spinner />
							</div>
						)}

						{videoURL && !fetching && preview?.html && (
							<div
								className="wp-block-image__video-preview"
								dangerouslySetInnerHTML={{
									__html: preview.html,
								}}
							/>
						)}

						{videoURL && !fetching && !preview?.html && (
							<div>
								{__(
									'Error loading video. Check URL.',
									'catapult'
								)}
							</div>
						)}
					</PanelBody>
				</InspectorControls>
			</Fragment>
		);
	};
}, 'imageCoreBlockOptions');

/**
 * Add column classes to image and embed blocks and make sure the correct image size is used.
 *
 * @param {Function} BlockEdit Original component.
 *
 * @return {Function} Wrapped component.
 */
const imageCoreBlockEditorWrapper = createHigherOrderComponent(
	(BlockListBlock) => {
		return (props) => {
			if ('core/image' !== props.name) {
				return <BlockListBlock {...props} />;
			}

			const { attributes, setAttributes } = props;

			const { videoURL } = attributes;
			let figureClass = '';

			setAttributes({
				videoURL,
			});
			if ('' !== videoURL) {
				figureClass = 'component-video';
			}

			return (
				<BlockListBlock
					{...props}
					className={figureClass}
					wrapperProps={{
						style: {
							'--videoIcon': 'var(--icon-play)',
						},
					}}
				/>
			);
		};
	},
	'imageCoreBlockEditorWrapper'
);
wp.hooks.addFilter(
	'blocks.registerBlockType',
	'core/image',
	registerImageCoreBlockOptions
);
wp.hooks.addFilter(
	'editor.BlockListBlock',
	'core/image',
	imageCoreBlockEditorWrapper
);
wp.hooks.addFilter('editor.BlockEdit', 'core/image', imageCoreBlockOptions);
