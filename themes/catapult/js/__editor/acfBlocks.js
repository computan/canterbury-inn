import classnames from 'classnames';
import { createHigherOrderComponent } from '@wordpress/compose';
import { getBlockType } from '@wordpress/blocks';

const acfBlocks = () => {
	function setBlockCustomClassName(className) {
		if (className.includes('-acf')) {
			className = className.replace('wp-', '').replace('-acf', '');
		}

		if (className.includes('-catapult')) {
			className = className.replace('wp-', '').replace('-catapult', '');
		}

		return className;
	}

	wp.hooks.addFilter(
		'blocks.getBlockDefaultClassName',
		'catapult/set-block-custom-class-name',
		setBlockCustomClassName
	);

	if (window.acf) {
		const addWrapperClasses = createHigherOrderComponent(
			(BlockListBlock) => {
				return (props) => {
					return (
						<BlockListBlock
							{...props}
							className={getWrapperClass(props)}
							wrapperProps={{
								style: getWrapperStyle(props),
							}}
						/>
					);
				};
			},
			'withClientIdClassName'
		);

		wp.hooks.addFilter(
			'editor.BlockListBlock',
			'catapult/add-wrapper-classes',
			addWrapperClasses,
			99
		);

		const getWrapperClass = (props) => {
			const { clientId, className, name } = props;

			let newClassName = className;

			if (name.includes('acf/')) {
				const blockElement = document.getElementById(
					`block-${clientId}`
				);

				if (blockElement?.firstElementChild) {
					if (blockElement.firstElementChild.classList) {
						newClassName = classnames(
							className,
							...blockElement.firstElementChild.classList
						);
					}
				}
			}

			return newClassName;
		};

		const getWrapperStyle = (props) => {
			const { name, wrapperProps, attributes } = props;

			const newStyle = wrapperProps?.style ?? {};

			if (name.includes('acf/')) {
				const blockType = getBlockType(name);

				const missingCssCustomProps = [];

				if (blockType?.css_custom_props?.length > 0) {
					blockType.css_custom_props.forEach((cssCustomProp) => {
						if (!cssCustomProp.name) {
							return;
						}

						if (attributes?.data?.[cssCustomProp.name]) {
							newStyle[`--${cssCustomProp.name}`] =
								attributes.data[cssCustomProp.name];
						} else {
							missingCssCustomProps.push(cssCustomProp);
						}
					});
				}

				if (missingCssCustomProps.length > 0 && attributes?.data) {
					Object.keys(attributes.data).forEach((attribute) => {
						if (attribute.startsWith('_')) {
							return;
						}

						if (attribute.startsWith('field')) {
							const fieldObject = window.acf.getField(attribute);

							if (
								fieldObject?.data?.name &&
								missingCssCustomProps.some(
									(missingCssCustomProp) =>
										missingCssCustomProp.name ===
										fieldObject.data.name
								)
							) {
								newStyle[`--${fieldObject.data.name}`] =
									attributes.data[attribute];
							}
						} else {
							newStyle[`--${attribute}`] =
								attributes.data[attribute];
						}
					});
				}

				if (
					blockType?.css_custom_props?.length > 0 &&
					missingCssCustomProps.length > 0
				) {
					blockType.css_custom_props.forEach((cssCustomProp) => {
						if (
							!cssCustomProp.name ||
							!cssCustomProp.default ||
							newStyle[`--${cssCustomProp.name}`]
						) {
							return;
						}

						newStyle[`--${cssCustomProp.name}`] =
							cssCustomProp.default;
					});
				}
			}

			return newStyle;
		};

		const addWrapperBlockClasses = ($block) => {
			let blockWrapper;

			if ($block.length) {
				blockWrapper = $block[0].closest('.wp-block');
			} else {
				blockWrapper = $block;
			}

			if (
				blockWrapper &&
				blockWrapper.classList &&
				blockWrapper.firstElementChild
			) {
				let firstElementClassList = [];

				if (blockWrapper.firstElementChild.classList.length > 0) {
					firstElementClassList =
						blockWrapper.firstElementChild.classList;
				} else if (
					blockWrapper.firstElementChild.firstElementChild &&
					blockWrapper.firstElementChild.firstElementChild.classList
						.length > 0
				) {
					firstElementClassList =
						blockWrapper.firstElementChild.firstElementChild
							.classList;
				}

				if (firstElementClassList.length > 0) {
					if (blockWrapper.hasAttribute('data-class')) {
						const previousClasses = blockWrapper
							.getAttribute('data-class')
							.split(' ')
							.filter((previousClass) => previousClass !== '');

						if (previousClasses.length) {
							blockWrapper.classList.remove(...previousClasses);
						}
					}

					blockWrapper.classList.add(...firstElementClassList);

					blockWrapper.setAttribute(
						'data-class',
						firstElementClassList.value
					);
				}
			}
		};

		window.acf.addAction('render_block_preview', addWrapperBlockClasses);
	}
};

export default acfBlocks;
