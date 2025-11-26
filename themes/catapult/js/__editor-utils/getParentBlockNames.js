import { useSelect } from '@wordpress/data';

/**
 * Gets the names of the parent blocks.
 *
 * @param {number} clientId The client ID of the block to check.
 *
 * @return {Array} An array of the parent block names.
 */
const getParentBlockNames = (clientId) => {
	// eslint-disable-next-line react-hooks/rules-of-hooks
	return useSelect((select) => {
		const { getBlockParents, getBlocksByClientId } =
			select('core/block-editor');

		const parentIds = getBlockParents(clientId);
		const parents = getBlocksByClientId(parentIds);

		if (!parents || 0 === parents.length) {
			return [];
		}

		const parentBlockNames = parents
			.reduce((acc, obj) => {
				acc.push(obj.name);
				return acc;
			}, [])
			.reverse();

		return parentBlockNames;
	});
};

export default getParentBlockNames;
