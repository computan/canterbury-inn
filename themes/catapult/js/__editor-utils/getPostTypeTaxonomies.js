import apiFetch from '@wordpress/api-fetch';

const getPostTypeTaxonomies = async (postType) => {
	try {
		const taxonomies = await apiFetch({ path: '/wp/v2/taxonomies' });
		return Object.values(taxonomies).filter((obj) =>
			obj.types.includes(postType)
		);
	} catch (error) {
		return [];
	}
};

export default getPostTypeTaxonomies;
