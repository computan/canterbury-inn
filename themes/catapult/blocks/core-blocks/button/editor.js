import { registerBlockStyle, unregisterBlockStyle } from '@wordpress/blocks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { Fragment, useState, useEffect } from '@wordpress/element';
import { InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	ComboboxControl,
	RadioControl,
	ToggleControl,
} from '@wordpress/components';
import getParentBlockNames from '../../../js/__editor-utils/getParentBlockNames';
import { __ } from '@wordpress/i18n';
const { cleanForSlug } = require('@wordpress/url');

const defaultButtonStyles = ['Primary', 'Secondary', 'Tertiary', 'Social'];
const registeredButtonStyles = [];

/**
 * Register button block styles and unregister unused styles.
 *
 * @param {Array} buttonStyles An array of button styles to register.
 */
const registerBlockStyles = (buttonStyles) => {
	if (registeredButtonStyles.length > 0) {
		registeredButtonStyles.forEach((registeredButtonStyle) => {
			unregisterBlockStyle(
				'core/button',
				registeredButtonStyle.replace(' ', '-').toLowerCase()
			);
		});
	}

	if (buttonStyles?.length > 0) {
		buttonStyles.forEach((buttonStyle, index) => {
			registerBlockStyle('core/button', {
				name: buttonStyle.replace(' ', '-').toLowerCase(),
				label: buttonStyle,
				isDefault: 0 === index ? true : false,
			});

			registeredButtonStyles.push(buttonStyle);
		});
	}
};

/**
 * Add button block additional options.
 *
 * @param {Object} settings Registered block settings.
 * @param {string} name     Block name.
 *
 * @return {Object} Filtered block settings.
 */
const registerButtonBlockOptions = (settings, name) => {
	if ('core/button' !== name) {
		return settings;
	}

	return Object.assign({}, settings, {
		attributes: Object.assign({}, settings.attributes, {
			buttonSize: { type: 'string', default: 'default' },
			buttonIcon: { type: 'string' },
			iconPosition: { type: 'string', default: 'right' },
			hideLabel: { type: 'boolean' },
		}),
	});
};
wp.hooks.addFilter(
	'blocks.registerBlockType',
	'catapult/register-button-block-options',
	registerButtonBlockOptions
);

/**
 * Add options to button block sidebar settings.
 *
 * @param {Function} BlockEdit Original component.
 *
 * @return {Function} Wrapped component.
 */
const buttonBlockOptions = createHigherOrderComponent((BlockEdit) => {
	return (props) => {
		if (
			'core/button' !== props.name ||
			!window.catapultIcons ||
			!props.isSelected
		) {
			return <BlockEdit {...props} />;
		}

		const { attributes, setAttributes, clientId } = props;
		const {
			buttonSize,
			buttonIcon,
			iconPosition,
			hideLabel,
			className,
			text,
		} = attributes;
		const [icons, setIcons] = useState([]);
		const [buttonStyles, setButtonStyles] = useState(
			attributes.buttonStyles
		);
		const parentBlocks = getParentBlockNames(clientId);

		useEffect(() => {
			if (!buttonStyles) {
				setButtonStyles(getButtonStyles(clientId));
			}

			setIcons(getIcons(className));

			if (registeredButtonStyles !== buttonStyles) {
				registerBlockStyles(buttonStyles);
			}
		}, [buttonStyles, clientId, icons.length, className]);

		if (!className && buttonStyles?.length > 0) {
			setAttributes({
				className: `is-style-${cleanForSlug(buttonStyles[0])}`,
			});
		}

		if (
			(!text || 'string' !== typeof text) &&
			className?.includes('is-style-social')
		) {
			setAttributes({
				text: 'placeholder',
			});
		}

		const renderIconOption = ({ item }) => {
			return (
				<span
					className="wp-block-image__video-preview"
					dangerouslySetInnerHTML={{
						__html: `<span class="icon ${item.value}"></span>${item.label}`,
					}}
				/>
			);
		};

		if (
			className &&
			!className.includes('is-style-primary') &&
			!className.includes('is-style-secondary') &&
			!className.includes('is-style-tertiary') &&
			!className.includes('is-style-social')
		) {
			return <BlockEdit {...props} />;
		}

		return (
			<Fragment>
				<BlockEdit {...props} />
				<InspectorControls>
					<PanelBody title="Button options">
						<RadioControl
							label={__('Button size', 'catapult')}
							selected={buttonSize}
							options={[
								{
									label: __('Default', 'catapult'),
									value: 'default',
								},
								{
									label: __('Small', 'catapult'),
									value: 'small',
								},
							]}
							onChange={(value) => {
								setAttributes({
									buttonSize: value,
								});
							}}
						/>

						<ComboboxControl
							label={__('Icon', 'catapult')}
							value={buttonIcon}
							options={icons}
							className="custom-icon-combobox"
							onChange={(value) => {
								setAttributes({
									buttonIcon: value,
								});
							}}
							__experimentalRenderItem={renderIconOption}
						/>

						{!parentBlocks.includes('acf/card-image-link') &&
							!parentBlocks.includes('acf/card-text-link') && (
								<RadioControl
									label={__('Icon Position', 'catapult')}
									selected={iconPosition}
									options={[
										{
											label: __('Right', 'catapult'),
											value: 'right',
										},
										{
											label: __('Left', 'catapult'),
											value: 'left',
										},
									]}
									onChange={(value) => {
										setAttributes({
											iconPosition: value,
										});
									}}
								/>
							)}

						<ToggleControl
							label={__('Hide Label', 'catapult')}
							checked={hideLabel}
							onChange={(value) => {
								setAttributes({
									hideLabel: value,
								});
							}}
							help={__(
								'Label is still needed even if hidden for accessibility.',
								'catapult'
							)}
						/>
					</PanelBody>
				</InspectorControls>
			</Fragment>
		);
	};
}, 'buttonBlockOptions');
wp.hooks.addFilter(
	'editor.BlockEdit',
	'catapult/button-block-options',
	buttonBlockOptions
);

/**
 * Get an array of icons.
 *
 * @param {string} className
 */
const getIcons = (className = '') => {
	const icons = [];

	let style = 'primary';

	if (className) {
		const matches = className.match(/(?<=is-style-)[^ ]*/gm);

		if (matches) {
			style = matches[0];
		}
	}

	window.catapultIcons.icons.forEach((icon) => {
		if (icon.category_slug) {
			if ('social' === style) {
				if ('social' !== icon.category_slug) {
					return;
				}
			} else if (
				undefined !== window.catapultIcons.buttonIconCategories &&
				!window.catapultIcons.buttonIconCategories.includes(
					icon.category_slug
				)
			) {
				return;
			}
		}

		icons.push({
			value: 'icon-' + icon.name,
			label: icon.name,
		});
	});

	return icons;
};

/**
 * Add wrapper to button block.
 *
 * @param {Function} BlockListBlock Original component.
 *
 * @return {Function} Wrapped component.
 */
const buttonBlockEditorWrapper = createHigherOrderComponent(
	(BlockListBlock) => {
		return (props) => {
			if ('core/button' !== props.name) {
				return <BlockListBlock {...props} />;
			}

			const { buttonSize, buttonIcon, iconPosition, hideLabel } =
				props.attributes;

			let buttonClass = 'wp-block-button';

			if (buttonSize && 'small' === buttonSize) {
				buttonClass += ' wp-block-button--small';
			}

			if (hideLabel) {
				buttonClass += ' wp-block-button--hidden-label';
			}

			if (buttonIcon) {
				if (iconPosition) {
					buttonClass += ' wp-block-button--icon-' + iconPosition;
				}

				return (
					<BlockListBlock
						{...props}
						className={buttonClass}
						wrapperProps={{
							style: {
								'--buttonIcon': 'var(--' + buttonIcon + ')',
							},
						}}
					/>
				);
			}
			return <BlockListBlock {...props} className={buttonClass} />;
		};
	},
	'buttonBlockEditorWrapper'
);
wp.hooks.addFilter(
	'editor.BlockListBlock',
	'catapult/button-block-editor-wrapper',
	buttonBlockEditorWrapper
);

/**
 * Save button block props to block.
 *
 * @param {Object} props      Additional props applied to save element.
 * @param {Object} blockType  Block type.
 * @param {Object} attributes Block attributes.
 *
 * @return {Object} Filtered props applied to save element.
 */
const saveButtonBlockProps = (props, blockType, attributes) => {
	if ('core/button' === blockType.name) {
		const { buttonSize, buttonIcon, iconPosition, hideLabel } = attributes;

		if (buttonIcon) {
			if (!props.style) {
				props.style = {};
			}

			let iconPositionClass = ' wp-block-button--icon-right';

			if (iconPosition) {
				iconPositionClass = ' wp-block-button--icon-' + iconPosition;
			}

			props.style['--buttonIcon'] = 'var(--' + buttonIcon + ')';
			props.className = props.className + iconPositionClass;
		}

		if (buttonSize && 'small' === buttonSize) {
			props.className = props.className + ' wp-block-button--small';
		}

		if (hideLabel) {
			props.className =
				props.className + ' wp-block-button--hidden-label';
		}
	}

	return props;
};
wp.hooks.addFilter(
	'blocks.getSaveContent.extraProps',
	'catapult/save-button-block-props',
	saveButtonBlockProps
);

/**
 * Get the allowed button styles for the current button block context. Can't use true context property since it only allows passing block attributes.
 *
 * @param {number} clientId The client ID of the block to check.
 *
 * @return {Array} An array of the allowed block styles.
 */
const getButtonStyles = (clientId) => {
	const { getBlockParents, getBlocksByClientId } =
		wp.data.select('core/block-editor');
	const parentIds = getBlockParents(clientId);
	const parents = getBlocksByClientId(parentIds);

	if (!parents || parents.length === 0) {
		return defaultButtonStyles;
	}

	let buttonStyles;

	for (let i = parents.length - 1; i >= 0; i--) {
		const parent = parents[i];

		if (!parent.name || buttonStyles) {
			continue;
		}

		const parentBlockData = wp.data
			.select('core/blocks')
			.getBlockType(parent.name);

		if (parentBlockData?.button_styles) {
			buttonStyles = parentBlockData.button_styles;
			break;
		}
	}

	return buttonStyles ?? defaultButtonStyles;
};
