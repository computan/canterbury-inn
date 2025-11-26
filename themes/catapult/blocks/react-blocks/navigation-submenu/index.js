import { registerBlockType } from '@wordpress/blocks';
import { navigation } from '@wordpress/icons';

import metadata from './block.json';
import edit from './edit';
import save from './save';

registerBlockType(metadata.name, {
	icon: navigation,
	edit,
	save,
});
