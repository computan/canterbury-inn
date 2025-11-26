import classnames from 'classnames';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

export default function Save({ attributes }) {
	const blockProps = useBlockProps.save({
		className: 'block-navigation-link',
	});

	const { title, url, hasSubmenu, opensInNewTab, justification } = attributes;

	let titleComponentWrapper;

	if (url && !hasSubmenu) {
		titleComponentWrapper = (
			<a
				href={url}
				className={`block-navigation-link__button block-navigation-link__button--link`}
				{...(opensInNewTab
					? { target: '_blank', rel: 'noopener noreferrer' }
					: {})}
			>
				{title}
			</a>
		);
	} else if (hasSubmenu) {
		titleComponentWrapper = (
			<button
				type="button"
				className="block-navigation-link__button block-navigation-link__button--with-submenu"
			>
				{title}
			</button>
		);
	} else {
		titleComponentWrapper = (
			<span className="block-navigation-link__button block-navigation-link__button--text">
				{title}
			</span>
		);
	}

	return (
		<div
			{...blockProps}
			className={classnames(blockProps.className, {
				[`block-navigation-link--${justification}`]: justification,
			})}
		>
			{titleComponentWrapper}

			{hasSubmenu && <InnerBlocks.Content />}
		</div>
	);
}
