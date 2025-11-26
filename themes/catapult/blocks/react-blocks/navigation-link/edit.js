import classnames from 'classnames';
import {
	BlockControls,
	useBlockProps,
	InnerBlocks,
	InspectorControls,
	RichText,
	JustifyContentControl,
} from '@wordpress/block-editor';
import {
	ToolbarButton,
	ToolbarGroup,
	PanelBody,
	TextControl,
	CheckboxControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import {
	addSubmenu as addSubmenuIcon,
	removeSubmenu as removeSubmenuIcon,
} from '@wordpress/icons';
import { safeDecodeURI } from '@wordpress/url';
import { useEffect } from '@wordpress/element';

export default function Edit({ attributes, setAttributes, isSelected }) {
	const blockProps = useBlockProps({
		className: 'block-navigation-link',
	});

	const { title, url, hasSubmenu, opensInNewTab, isActive, justification } =
		attributes;

	if (!justification) {
		setAttributes({ justification: 'right' });
	}

	const addSubmenu = () => {
		setAttributes({ hasSubmenu: !hasSubmenu });
	};

	let titleComponentWrapper;

	const titleComponent = (
		<RichText
			aria-label={__('Nav button text', 'catapult')}
			placeholder={__('Add nav text…', 'catapult')}
			value={title}
			onChange={(value) =>
				setAttributes({
					title: value,
				})
			}
			withoutInteractiveFormatting
			allowedFormats={[]}
			identifier="title"
		/>
	);

	if (url && !hasSubmenu) {
		titleComponentWrapper = (
			<a
				href={safeDecodeURI(url)}
				onClick={(e) => e.preventDefault()}
				className={`block-navigation-link__button block-navigation-link__button--link`}
				{...(opensInNewTab
					? { target: '_blank', rel: 'noopener noreferrer' }
					: {})}
			>
				{titleComponent}
			</a>
		);
	} else if (hasSubmenu) {
		titleComponentWrapper = (
			<button
				type="button"
				className="block-navigation-link__button block-navigation-link__button--with-submenu"
				onClick={() => handleClick()}
			>
				{titleComponent}
			</button>
		);
	} else {
		titleComponentWrapper = (
			<span className="block-navigation-link__button block-navigation-link__button--text">
				{titleComponent}
			</span>
		);
	}

	useEffect(() => {
		if (
			!isSelected &&
			!blockProps.className.includes('has-child-selected')
		) {
			setAttributes({ isActive: false });
		}
	}, [isSelected, blockProps.className, setAttributes]);

	const handleClick = () => {
		setAttributes({ isActive: !isActive });
	};

	return (
		<>
			<BlockControls>
				<JustifyContentControl
					value={justification}
					onChange={(newJustificationValue) => {
						setAttributes({
							justification: newJustificationValue ?? 'right',
						});
					}}
					allowedControls={['left', 'center', 'right']}
				/>

				<ToolbarGroup>
					<ToolbarButton
						name="submenu"
						icon={hasSubmenu ? removeSubmenuIcon : addSubmenuIcon}
						title={
							hasSubmenu
								? __('Remove Submenu', 'catapult')
								: __('Add Submenu', 'catapult')
						}
						onClick={addSubmenu}
					/>
				</ToolbarGroup>
			</BlockControls>

			{!hasSubmenu && (
				<InspectorControls>
					<PanelBody title={__('Settings', 'catapult')}>
						<TextControl
							label={__('URL', 'catapult')}
							__nextHasNoMarginBottom
							value={url ? safeDecodeURI(url) : ''}
							onChange={(urlValue) => {
								setAttributes({ url: urlValue });
							}}
						/>

						<CheckboxControl
							label={__('Opens in new tab', 'catapult')}
							__nextHasNoMarginBottom
							checked={opensInNewTab ? true : false}
							onChange={(opensInNewTabvalue) => {
								setAttributes({
									opensInNewTab: opensInNewTabvalue,
								});
							}}
						/>
					</PanelBody>
				</InspectorControls>
			)}

			<div
				{...blockProps}
				className={classnames(blockProps.className, {
					[`active`]:
						isActive &&
						(isSelected ||
							blockProps.className.includes(
								'has-child-selected'
							)),
					[`block-navigation-link--${justification}`]: justification,
				})}
			>
				{titleComponentWrapper}

				{hasSubmenu && (
					<InnerBlocks
						allowedBlocks={['catapult/navigation-submenu']}
						template={[['catapult/navigation-submenu']]}
						templateLock="insert"
					/>
				)}
			</div>
		</>
	);
}
