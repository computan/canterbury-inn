import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import isParentBlockSelected from '../../../js/__editor-utils/isParentBlockSelected';
import { useState, useEffect, useRef } from '@wordpress/element';

export default function Edit({ setAttributes, context, clientId }) {
	const blockProps = useBlockProps({
		className: 'block-navigation-submenu',
	});

	const [submenuOffset, setSubmenuOffset] = useState(0);

	const navigationStyle = context?.['catapult/navigation/style'] ?? '';
	const headerLabel = context?.['catapult/navigation-link/title'] ?? '';
	const isActive = context?.['catapult/navigation-link/isActive'] ?? '';

	let allowedBlocks = [];

	if ('hamburger' === navigationStyle) {
		allowedBlocks = ['acf/navigation-simple-links'];
	} else {
		allowedBlocks = [
			'acf/navigation-columns',
			'acf/navigation-simple-links',
		];
	}

	setAttributes({ headerLabel });

	const isParentSelected = isParentBlockSelected(clientId);

	const innerBlocksRef = useRef(null);

	useEffect(() => {
		setSubmenuOffset(0);
		const innerBlocksElement = innerBlocksRef.current;

		if (!innerBlocksElement) return;

		const navigationSubMenu = innerBlocksElement.closest(
			'.block-navigation-submenu'
		);

		if (!navigationSubMenu) return;

		const navigationSubMenuRect = navigationSubMenu.getBoundingClientRect();

		if (0 === navigationSubMenuRect.width || !navigationSubMenuRect.x) {
			return;
		}

		const editorElement = document.querySelector('.editor-styles-wrapper');

		if (!editorElement) {
			return;
		}

		const editorElementRect = editorElement.getBoundingClientRect();
		const containerLeft = editorElementRect.x + 20;
		const containerRight =
			editorElementRect.x + editorElementRect.width - 20;
		const navigationSubMenuLeft = navigationSubMenuRect.x;
		const navigationSubMenuRight =
			navigationSubMenuRect.x + navigationSubMenuRect.width;

		let offset = 0;

		if (navigationSubMenuLeft < containerLeft) {
			offset = containerLeft - navigationSubMenuLeft;
		} else if (navigationSubMenuRight > containerRight) {
			offset = containerRight - navigationSubMenuRight;
		}

		setSubmenuOffset(offset / 16);
	}, [isParentSelected, isActive]);

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

			<div
				{...blockProps}
				style={{ '--submenuOffset': `${submenuOffset}rem` }}
			>
				<InnerBlocks
					allowedBlocks={allowedBlocks}
					templateLock={false}
					ref={innerBlocksRef}
				/>
			</div>
		</>
	);
}
