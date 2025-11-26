import { InnerBlocks } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

export default function Save({ attributes }) {
	const { headerLabel } = attributes;

	return (
		<>
			<div className="block-navigation-submenu__header">
				<button
					type="button"
					className="block-navigation-submenu__back-button"
				>
					{__('All', 'catapult')}
				</button>

				{headerLabel && (
					<span className="block-navigation-submenu__header-label">
						{headerLabel}
					</span>
				)}
			</div>

			<div className="block-navigation-submenu">
				<InnerBlocks.Content />
			</div>
		</>
	);
}
