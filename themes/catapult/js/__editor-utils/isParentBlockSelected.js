import { useSelect } from '@wordpress/data';

/**
 * Checks to see if the parent block is selected.
 *
 * @param {number} clientId The client ID of the block to check.
 *
 * @return {boolean} Whether or not the media block has custom styles.
 */
const isParentBlockSelected = (clientId) => {
	// eslint-disable-next-line react-hooks/rules-of-hooks
	return useSelect((select) => {
		const { getBlockParents, getBlocksByClientId, isBlockSelected } =
			select('core/block-editor');

		const parentIds = getBlockParents(clientId);
		const parents = getBlocksByClientId(parentIds);

		if (!parents || 0 === parents.length) {
			return false;
		}

		const firstParent = parents[parents.length - 1];

		return isBlockSelected(firstParent.clientId);
	});
};

export default isParentBlockSelected;
