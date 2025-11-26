import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

export default function Save({ attributes }) {
	const blockProps = useBlockProps.save({
		className: 'block-navigation',
	});

	const { logoUrl, logoAlt, logoWidth, logoHeight, logoId } = attributes;

	return (
		<nav {...blockProps}>
			<div className="container block-navigation__container">
				<button
					type="button"
					className="block-navigation__hamburger"
					aria-label={__('Open Menu', 'catapult')}
				>
					<span className="block-navigation__hamburger-inner"></span>
				</button>

				{logoUrl && (
					<a
						href={window?.catapult?.siteUrl ?? ''}
						className="block-navigation__logo-link"
						aria-label={__('Go to homepage', 'catapult')}
					>
						<img
							src={logoUrl}
							alt={logoAlt}
							className={`block-navigation__logo block-navigation__logo--${logoId}`}
							width={logoWidth}
							height={logoHeight}
						/>
					</a>
				)}

				<div className="block-navigation__menu">
					<InnerBlocks.Content />
				</div>
			</div>
		</nav>
	);
}
