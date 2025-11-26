import { createHigherOrderComponent } from '@wordpress/compose';
import { Fragment } from '@wordpress/element';
import { InspectorControls } from '@wordpress/block-editor';
import { getBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';

/**
 * Add instructions to the editor sidebar for blocks that have instructions.
 *
 * @param {Function} BlockEdit Original component.
 *
 * @return {Function} Wrapped component.
 */
const blockInstructionSidebar = createHigherOrderComponent((BlockEdit) => {
	return (props) => {
		const { name } = props;

		if (!name.includes('acf/')) {
			return <BlockEdit {...props} />;
		}

		const blockType = getBlockType(props.name);

		if (!blockType.instructions) {
			return <BlockEdit {...props} />;
		}

		return (
			<Fragment>
				<InspectorControls>
					<div className="block-editor-block-card block-editor-block-card--instructions">
						<div className="block-editor-block-card__content">
							<h2 className="block-editor-block-card__title">
								{__('Instructions', 'catapult')}
							</h2>

							<span
								className="block-editor-block-card__description"
								dangerouslySetInnerHTML={{
									__html: blockType.instructions,
								}}
							></span>
						</div>
					</div>
				</InspectorControls>

				<BlockEdit {...props} />
			</Fragment>
		);
	};
}, 'blockInstructionSidebar');

const blockInstructions = () => {
	wp.hooks.addFilter(
		'editor.BlockEdit',
		'catapult/block-instructions',
		blockInstructionSidebar
	);
};

export default blockInstructions;
