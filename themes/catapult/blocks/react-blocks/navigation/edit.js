import classnames from 'classnames';
import {
	useBlockProps,
	InnerBlocks,
	MediaUpload,
	MediaUploadCheck,
} from '@wordpress/block-editor';
import { Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { plusCircleFilled, cancelCircleFilled } from '@wordpress/icons';
import { useState, useEffect } from '@wordpress/element';

export default function Edit({ attributes, setAttributes }) {
	const blockProps = useBlockProps({
		className: 'block-navigation block-navigation--initialized',
	});

	const { logoUrl, logoAlt, logoWidth, logoHeight, logoId } = attributes;

	const [isActive, setIsActive] = useState(false);

	if (!blockProps.className.includes('is-style')) {
		blockProps.className = `${blockProps.className} is-style-full-width-dropdown`;
	}

	let allowedBlocks = [];

	if (blockProps.className.includes('hamburger')) {
		allowedBlocks = [
			'catapult/navigation-link',
			'acf/navigation-quick-links',
		];
	} else {
		allowedBlocks = [
			'catapult/navigation-link',
			'core/button',
			'acf/navigation-search-button',
		];
	}

	useEffect(() => {
		let styleValue = 'full-width-dropdown';

		const styleMatch = Array.from(
			blockProps.className.matchAll(/is-style-([^ ]*)/gm),
			(match) => match[1]
		);

		if (styleMatch.length > 0) {
			styleValue = styleMatch[0];
		}

		setAttributes({
			style: styleValue,
		});
	}, [blockProps.className, setAttributes]);

	return (
		<nav {...blockProps}>
			<div className="container block-navigation__container">
				<button
					type="button"
					aria-label={__('Open Menu', 'catapult')}
					onClick={() => setIsActive(!isActive)}
					className={classnames('block-navigation__hamburger', {
						[`active`]: isActive,
					})}
				>
					<span className="block-navigation__hamburger-inner"></span>
				</button>

				<MediaUploadCheck>
					<MediaUpload
						onSelect={(media) => {
							setAttributes({
								logoAlt: media?.alt ?? '',
								logoUrl:
									media?.sizes?.['main-logo']?.url ??
									media?.url ??
									'',
								logoWidth: media?.width ?? '',
								logoHeight: media?.height ?? '',
								logoId: media?.id ?? '',
							});
						}}
						multiple={false}
						value={logoUrl}
						render={({ open }) => (
							<>
								{!logoUrl && (
									<Button
										onClick={open}
										icon={plusCircleFilled}
									>
										{__('Add logo', 'catapult')}
									</Button>
								)}

								{logoUrl && (
									<>
										<a
											href={
												window?.catapult?.siteUrl ?? ''
											}
											className="block-navigation__logo-link"
											onClick={(e) => e.preventDefault()}
											aria-label={__(
												'Go to homepage',
												'catapult'
											)}
										>
											<img
												src={logoUrl}
												alt={logoAlt}
												className={`block-navigation__logo block-navigation__logo--${logoId}`}
												width={logoWidth}
												height={logoHeight}
											/>

											<Button
												onClick={() => {
													setAttributes({
														logoAlt: '',
														logoUrl: '',
														logoWidth: '',
														logoHeight: '',
														logoId: '',
													});
												}}
												icon={cancelCircleFilled}
												showTooltip={true}
												label={__(
													'Remove logo',
													'catapult'
												)}
												className="block-navigation__remove-logo-button"
											></Button>
										</a>
									</>
								)}
							</>
						)}
					/>
				</MediaUploadCheck>

				<div className="block-navigation__menu">
					<InnerBlocks allowedBlocks={allowedBlocks} />
				</div>
			</div>
		</nav>
	);
}
