import apiFetch from '@wordpress/api-fetch';

const getPostTypes = async () => {
	try {
		return await apiFetch({
			path: '/catapult/v1/filter-post-types',
		});
	} catch (error) {
		return [];
	}
};

export default getPostTypes;
