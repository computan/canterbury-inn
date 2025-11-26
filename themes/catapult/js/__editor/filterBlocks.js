import { addFilter } from '@wordpress/hooks';
import { InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	PanelRow,
	SelectControl,
	TextControl,
	CheckboxControl,
} from '@wordpress/components';
import { createHigherOrderComponent } from '@wordpress/compose';
import { __ } from '@wordpress/i18n';
import { useEffect, useState, useCallback } from 'react';
import getPostTypes from '../__editor-utils/getPostTypes';
import getPostTypeTaxonomies from '../__editor-utils/getPostTypeTaxonomies';

const filterRegisterAttributes = (settings, name) => {
	const filterBlocks = ['acf/filter-top', 'acf/filter-side'];
	const filtersWrapper = 'acf/filters';
	const filters = [
		'acf/filter-select',
		'acf/filter-multi-select',
		'acf/filter-tabs',
	];

	if (
		!filterBlocks.includes(name) &&
		!filters.includes(name) &&
		name !== filtersWrapper
	) {
		return settings;
	}

	if (filterBlocks.includes(name)) {
		return {
			...settings,
			attributes: {
				...settings.attributes,
				filterPostType: {
					type: 'string',
					default: 'post',
				},
				showSearch: {
					type: 'boolean',
					default: false,
				},
				showResultsCount: {
					type: 'boolean',
					default: false,
				},
				showSort: {
					type: 'boolean',
					default: false,
				},
			},
			providesContext: {
				...settings.providesContext,
				'catapult/filter-post-type': 'filterPostType',
				'catapult/show-search': 'showSearch',
				'catapult/show-sort': 'showSort',
			},
		};
	}

	if (filtersWrapper === name) {
		return {
			...settings,
			attributes: {
				...settings.attributes,
				filterPostType: {
					type: 'string',
					default: 'post',
				},
				showSearch: {
					type: 'boolean',
					default: false,
				},
				showSort: {
					type: 'boolean',
					default: false,
				},
			},
		};
	}

	if (filters.includes(name)) {
		return {
			...settings,
			attributes: {
				...settings.attributes,
				filterTaxonomy: {
					type: 'string',
					default: '',
				},
				filterTaxonomyLabel: {
					type: 'string',
					default: 'All',
				},
			},
		};
	}
};

function FilterBlocksEdit(props) {
	const [options, setOptions] = useState([]);

	useEffect(() => {
		async function fetchPostTypes() {
			const postTypes = await getPostTypes();
			const opts = Object.values(postTypes).map((obj) => {
				return { label: obj.name, value: obj.slug };
			});
			setOptions(opts);
		}

		fetchPostTypes();
	}, []);

	return (
		<InspectorControls>
			<PanelBody title={__('Settings', 'catapult')}>
				<PanelRow>
					<SelectControl
						label={__('Post Type', 'catapult')}
						value={props.attributes.filterPostType}
						options={options}
						onChange={(postTypeValue) => {
							props.setAttributes({
								filterPostType: postTypeValue,
							});
						}}
						help={__(
							'Select the post type you would like to display.',
							'catapult'
						)}
					/>
				</PanelRow>
				<PanelRow>
					<CheckboxControl
						label={__('Show Search', 'catapult')}
						checked={props.attributes.showSearch}
						onChange={(value) => {
							props.setAttributes({
								showSearch: value,
							});
						}}
					/>
				</PanelRow>
				<PanelRow>
					<CheckboxControl
						label={__('Show Results Count', 'catapult')}
						checked={props.attributes.showResultsCount}
						onChange={(value) => {
							props.setAttributes({
								showResultsCount: value,
							});
						}}
					/>
				</PanelRow>
				<PanelRow>
					<CheckboxControl
						label={__('Show Sort', 'catapult')}
						checked={props.attributes.showSort}
						onChange={(value) => {
							props.setAttributes({
								showSort: value,
							});
						}}
					/>
				</PanelRow>
			</PanelBody>
		</InspectorControls>
	);
}

function FiltersEdit({ context, setAttributes, attributes }) {
	const [options, setOptions] = useState([]);
	const selectedPostType = context['catapult/filter-post-type'];

	const setTaxonomy = useCallback(
		(value) => {
			setAttributes({
				filterTaxonomy: value,
			});
		},
		[setAttributes]
	);

	const setLabel = useCallback(
		(value) => {
			setAttributes({
				filterTaxonomyLabel: value,
			});
		},
		[setAttributes]
	);

	useEffect(() => {
		async function fetchTaxonomies(postType) {
			const taxonomies = await getPostTypeTaxonomies(postType);
			const opts = Object.values(taxonomies).map((obj) => {
				return { label: obj.name, value: obj.slug };
			});
			setOptions(opts);
			if (opts.length) {
				if (
					!opts.some((obj) => obj.value === attributes.filterTaxonomy)
				) {
					setTaxonomy(opts[0]?.value);
					setLabel(`All ${opts[0]?.label}`);
				}
			}
		}

		fetchTaxonomies(selectedPostType);
	}, [setTaxonomy, setLabel, selectedPostType, attributes.filterTaxonomy]);

	return (
		<InspectorControls>
			<PanelBody title={__('Settings', 'catapult')}>
				<PanelRow>
					<SelectControl
						label={__('Taxonomy', 'catapult')}
						value={attributes.filterTaxonomy}
						options={options}
						onChange={setTaxonomy}
						help={__(
							'Select the taxonomy you would like to create a filter for.',
							'catapult'
						)}
					/>
				</PanelRow>
				<PanelRow>
					<TextControl
						label={__('All Terms Label', 'catapult')}
						onChange={setLabel}
						value={attributes.filterTaxonomyLabel}
					/>
				</PanelRow>
			</PanelBody>
		</InspectorControls>
	);
}

const filterAttributes = createHigherOrderComponent((BlockEdit) => {
	return (props) => {
		const filterBlocks = ['acf/filter-top', 'acf/filter-side'];
		const filtersWrapper = 'acf/filters';
		const filters = [
			'acf/filter-select',
			'acf/filter-multi-select',
			'acf/filter-tabs',
		];

		if (
			!filterBlocks.includes(props.name) &&
			!filters.includes(props.name) &&
			filtersWrapper !== props.name
		) {
			return <BlockEdit {...props} />;
		}

		if (filterBlocks.includes(props.name)) {
			return (
				<>
					<FilterBlocksEdit {...props} />
					<BlockEdit {...props} />
				</>
			);
		}

		if (filtersWrapper === props.name) {
			const selectedPostType = props.context['catapult/filter-post-type'];
			const showSearch = props.context['catapult/show-search'];
			const showSort = props.context['catapult/show-sort'];

			useEffect(() => {
				props.setAttributes({
					filterPostType: selectedPostType,
					showSearch,
					showSort,
				});
			}, [
				props,
				props.setAttributes,
				selectedPostType,
				showSearch,
				showSort,
			]);

			return (
				<>
					<BlockEdit {...props} />
				</>
			);
		}

		if (filters.includes(props.name)) {
			return (
				<>
					<FiltersEdit {...props} />
					<BlockEdit {...props} />
				</>
			);
		}
	};
});

const filterBlocks = () => {
	addFilter(
		'blocks.registerBlockType',
		'catapult/filterBlocks',
		filterRegisterAttributes
	);

	addFilter('editor.BlockEdit', 'catapult/filterBlocks', filterAttributes);
};

export default filterBlocks;
