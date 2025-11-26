/* global catapult */
import DOMPurify from 'dompurify';
import { debounce } from 'lodash';
import { __ } from '@wordpress/i18n';

/**
 * An ObservableMap class that extends the Map object and provides a mechanism for observing changes to the map's contents.
 */
class ObservableMap extends Map {
	constructor() {
		super();
		this.changeHandler = null;
	}

	set(key, value) {
		super.set(key, value);
		if (this.changeHandler) {
			this.changeHandler(key, value);
		}
		return this;
	}

	delete(key) {
		const deleted = super.delete(key);
		if (deleted && this.changeHandler) {
			this.changeHandler(key);
		}
		return deleted;
	}

	clear() {
		super.clear();
		if (this.changeHandler) {
			this.changeHandler();
		}
		return this;
	}

	onChange(callback) {
		this.changeHandler = (key, value) => {
			callback(key, value);
		};
	}
}

/**
 * The Filters class provides functionality for managing filters, fetching posts, and handling user interactions in the Filter blocks.
 */
class Filters {
	/**
	 * Initializes the Filters class with provided block and postsWrapper elements.
	 *
	 * @param {HTMLElement} block        - The root element of the Filters block.
	 * @param {HTMLElement} postsWrapper - The wrapper element for displaying fetched posts.
	 * @return {boolean} Returns false if the block element is falsy.
	 */
	constructor(block, postsWrapper) {
		if (!catapult && (!catapult.siteUrl || !catapult.ajaxUrl)) return false;

		this.restBase = catapult.siteUrl + '/wp-json/wp/v2/';
		this.ajaxurl = catapult.ajaxUrl;
		this.block = block;

		this.jsonSettings = JSON.parse(block.dataset.jsonSettings);

		// Define DOM elements
		this.postsWrapper = postsWrapper;
		this.postsContainer = this.block.querySelector(
			'.block-filter__posts-container'
		);
		this.resultCountContainer = this.block.querySelector(
			'.filter-result-count'
		);
		this.selectedFiltersContainer =
			this.block.querySelector('.selected-filters');
		this.clearFiltersBtn = this.block.querySelector('.clear-filters');
		this.filterElements = this.block.querySelectorAll('.filter');
		this.filterTabs = this.block.querySelectorAll('.filter-tabs__tab');
		this.filterTabsDropdown = this.block.querySelectorAll(
			'.filter-tabs__select'
		);
		this.filterSelects = this.block.querySelectorAll(
			'.filter-select__current'
		);
		this.filterSelectOptions = this.block.querySelectorAll(
			'.filter-select__option'
		);
		this.filterMultiSelects = this.block.querySelectorAll(
			'.filter-multi-select__current'
		);
		this.filterMultiSelectCheckboxes = this.block.querySelectorAll(
			'.filter-multi-select__checkbox'
		);
		this.searchForm = this.block.querySelector('.filter-search');
		this.searchInput = this.searchForm?.querySelector(
			'.filter-search__input'
		);
		this.searchClear = this.searchForm?.querySelector(
			'.filter-search__clear'
		);
		this.loadMore = this.block.querySelector('.load-more');
		this.paginationContainer = this.block.querySelector(
			'.block-filter-pagination'
		);
		this.paginationLinksContainer = this.block.querySelector('.pagination');
		this.paginationLinks =
			this.paginationLinksContainer?.querySelectorAll('.page-numbers');
		this.modalContainer = this.block.querySelector(
			'.filters-container__modal'
		);
		this.modalButton = this.block.querySelector(
			'.filters-container__modal-button'
		);
		this.modalCloseButton = this.block.querySelector(
			'.filters-container__modal-close'
		);
		this.modalShowResultsButton = this.block.querySelector(
			'.filters-container__modal-show-results'
		);
		this.filterSort = this.block.querySelectorAll('.filter-sort');

		// Define settings
		this.isArchive = this.jsonSettings.isArchive ?? false;
		this.postType = this.jsonSettings.postType;
		this.loadType = this.jsonSettings.loadType;
		this.order = 'desc';
		this.orderby = 'date';
		this.currentPage = this.jsonSettings.currentPage ?? 1;
		this.appendedPages = [];
		this.perPage = this.jsonSettings.postsPerPage ?? '3';
		this.totalPosts = this.jsonSettings.totalPosts;
		this.maxPages = this.jsonSettings.maxPages ?? 1;
		this.filters = [...this.filterElements].map((filter) => {
			return {
				element: filter,
				taxonomy: filter.dataset.taxonomy,
				type: filter.dataset.type,
				defaultValue: filter.dataset.defaultValue,
			};
		});
		this.selectedFilters = new ObservableMap();
		this.searchTerm = '';
		this.sortType = 'newest';

		// Pre-select filter by taxonomy and term
		if (
			this.jsonSettings &&
			'object' === typeof this.jsonSettings &&
			Object.keys(this.jsonSettings).length &&
			this.jsonSettings.taxonomy &&
			this.jsonSettings.termID &&
			this.jsonSettings.termName
		) {
			this.selectedFilters.set(this.jsonSettings.taxonomy, [
				{
					id: this.jsonSettings.termID,
					name: this.jsonSettings.termName,
				},
			]);
		}

		this.init();
	}

	/**
	 * Initializes the Filters class, binding methods and setting up event listeners for filter interactions.
	 * Also handles rendering pre-selected filters and setting up event listeners for various filter elements.
	 */
	init() {
		// Bind methods
		this.handleBodyClickCloseSort =
			this.handleBodyClickCloseSort.bind(this);
		this.handleBodyClickCloseSelect =
			this.handleBodyClickCloseSelect.bind(this);
		this.handleBodyClickCloseMultiSelect =
			this.handleBodyClickCloseMultiSelect.bind(this);
		this.handleSearchSubmit = this.handleSearchSubmit.bind(this);

		// Render pre-selected filters
		this.renderSelectedFilters();

		// selectedFilters change handler
		if (this.selectedFilters) {
			this.selectedFilters.onChange((key, value = null) => {
				this.updateFilterUI(key, value);
			});
		}

		// Tabs filter click handler
		if (this.filterTabs.length > 0) {
			this.filterTabs.forEach((tab) => {
				tab.addEventListener('click', (e) => {
					this.handleTabClick(e.target);
				});
			});
		}

		// Tabs filter mobile dropdown click handler
		if (this.filterTabsDropdown.length > 0) {
			this.filterTabsDropdown.forEach((dropdown) => {
				dropdown.addEventListener('click', (e) => {
					this.handleTabDropdownClick(e.target);
				});
			});
		}

		// Select filter click handler
		if (this.filterSelects.length > 0) {
			this.filterSelects.forEach((select) => {
				select.addEventListener('click', (e) => {
					e.stopPropagation();
					this.handleSelectClick(e.target);
				});
			});
		}

		// Select options click handler
		if (this.filterSelectOptions.length > 0) {
			this.filterSelectOptions.forEach((option) => {
				option.addEventListener('click', (e) => {
					this.handleSelectOptionClick(e.target);
				});
			});
		}

		// Mulit-Select filter click handler
		if (this.filterMultiSelects.length > 0) {
			this.filterMultiSelects.forEach((multiSelect) => {
				multiSelect.addEventListener('click', (e) => {
					e.stopPropagation();
					this.handleMultiSelectClick(e.target);
				});
			});
		}

		// Multi-Select options click handler
		if (this.filterMultiSelectCheckboxes.length > 0) {
			this.filterMultiSelectCheckboxes.forEach((checkbox) => {
				checkbox.addEventListener('click', (e) => {
					this.handleMultiSelectCheckboxClick(e.target);
				});
			});
		}

		// Search change handler
		if (this.searchInput) {
			this.searchInput.addEventListener(
				'input',
				debounce(this.handleSearchSubmit, 300)
			);
		}

		// Search submit handler
		if (this.searchForm) {
			this.searchForm.addEventListener('submit', (e) => {
				e.preventDefault();
				this.handleSearchSubmit();
			});
		}

		// Search clear button click handler
		if (this.searchClear) {
			this.searchClear.addEventListener('click', (e) => {
				e.preventDefault();
				this.handleSearchClear();
			});
		}

		// Clear filters click handler
		if (this.clearFiltersBtn) {
			this.clearFiltersBtn.addEventListener('click', (e) => {
				e.preventDefault();
				this.clearFilters();
			});
		}

		// Selected filters click handler
		if (this.selectedFiltersContainer) {
			this.selectedFiltersContainer.addEventListener('click', (e) => {
				const button = e.target.closest('.selected-filter');
				if (!button) return false;
				this.handleSelectedFilterClick(button);
			});
		}

		// Load more click handler
		if (this.loadMore) {
			this.loadMore.addEventListener('click', () => {
				this.currentPage++;
				this.fetchPosts(true);
			});
		}

		// Pagination link click handler
		if (this.paginationLinksContainer) {
			this.paginationLinksContainer.addEventListener('click', (e) => {
				e.preventDefault();
				this.handlePaginationClick(e);
			});
		}

		// Sort filters click handler
		if (this.filterSort.length > 0) {
			this.filterSort.forEach((sort) => {
				const options = sort.querySelectorAll('.filter-sort__option');

				sort.addEventListener('click', (e) => {
					e.stopPropagation();
					this.handleSortClick(e);
				});

				options?.forEach((option) => {
					option.addEventListener('click', (e) => {
						e.preventDefault();
						this.handleSortOptionClick(e.target, sort);
					});
				});
			});
		}

		// Modal button click handler
		if (this.modalButton) {
			this.modalButton.addEventListener('click', (e) => {
				e.preventDefault();
				this.toggleModal(true);
			});
		}

		// Modal close button click handler
		if (this.modalCloseButton) {
			this.modalCloseButton.addEventListener('click', (e) => {
				e.preventDefault();
				this.toggleModal(false);
			});
		}

		// Modal show results button click handler
		if (this.modalShowResultsButton) {
			this.modalShowResultsButton.addEventListener('click', (e) => {
				e.preventDefault();
				this.toggleModal(false);
			});
		}

		// Reset search button click handler
		if (this.postsWrapper) {
			this.postsWrapper.addEventListener('click', (e) => {
				if (
					e.target.classList.contains(
						'filter-no-results__reset-search'
					)
				) {
					e.preventDefault();
					this.clearFilters();
				}
			});

			// Watch for the lightbox reaching the beginning and load more posts.
			window.addEventListener(
				'catapult-lightbox-reach-beginning',
				(e) => {
					if (
						!e?.detail?.lightboxComponent ||
						!e?.detail?.page ||
						e.detail.lightboxComponent !== this.postsWrapper ||
						e.detail.page < 1 ||
						this.appendedPages.includes(e.detail.page)
					) {
						const alreadyRenderedEvent = new CustomEvent(
							'catapult-filters-page-already-rendered',
							{
								detail: { postsWrapper: this.postsWrapper },
							}
						);
						window.dispatchEvent(alreadyRenderedEvent);

						return;
					}

					this.appendedPages.push(e.detail.page);
					this.currentPage = e.detail.page;
					this.fetchPosts('before');
				}
			);

			// Watch for the lightbox reaching the end and load more posts.
			window.addEventListener('catapult-lightbox-reach-end', (e) => {
				if (
					!e?.detail?.lightboxComponent ||
					!e?.detail?.page ||
					e.detail.lightboxComponent !== this.postsWrapper ||
					e.detail.page > this.maxPages ||
					this.appendedPages.includes(e.detail.page)
				) {
					const alreadyRenderedEvent = new CustomEvent(
						'catapult-filters-page-already-rendered',
						{
							detail: { postsWrapper: this.postsWrapper },
						}
					);
					window.dispatchEvent(alreadyRenderedEvent);

					return;
				}

				this.appendedPages.push(e.detail.page);
				this.currentPage = e.detail.page;
				this.fetchPosts(true);
			});
		}
	}

	/**
	 * Updates the user interface based on the selected filters.
	 *
	 * @param {string}             key   - The key of the filter to update. If undefined, updates all filters.
	 * @param {Array<Object>|null} value - The selected value(s) of the filter.
	 */
	updateFilterUI(key, value) {
		this.filters.forEach((filter) => {
			if (filter.taxonomy === key || key === undefined) {
				if ('select' === filter.type) {
					const filterCurrent = filter.element.querySelector(
						'.filter-select__current'
					);
					const filterOptions = filter.element.querySelectorAll(
						'.filter-select__option'
					);

					if (filterCurrent) {
						filterCurrent.innerText = value
							? value[0].name
							: filter.defaultValue;
					}

					if (filterOptions.length) {
						if (!value) {
							filterOptions.forEach((filterOption) =>
								filterOption.classList.remove('selected')
							);
						} else {
							// De-select all options.
							filterOptions?.forEach((filterOption) => {
								const filterID = filterOption.dataset.value;
								if (
									value.some(
										(obj) => obj.id === parseInt(filterID)
									)
								) {
									filterOption?.classList.add('selected');
								} else {
									filterOption?.classList.remove('selected');
								}
							});
						}
					}
				} else if ('multi-select' === filter.type) {
					const checkboxes = filter.element.querySelectorAll(
						'.filter-multi-select__checkbox'
					);
					const filterCurrent = filter.element.querySelector(
						'.filter-multi-select__current'
					);
					if (!checkboxes.length || !filterCurrent) return;
					const ids = value ? value.map((val) => val.id) : [];
					checkboxes.forEach((checkbox) => {
						checkbox.checked = ids.includes(
							parseInt(checkbox.dataset.value)
						);
					});
				} else if ('tabs' === filter.type) {
					const tabSelect = filter.element.querySelector(
						'.filter-tabs__select'
					);
					const tabSelectSelected = tabSelect.querySelector(
						'.filter-tabs__select-selected'
					);

					filter.element
						.querySelector('.selected')
						?.classList.remove('selected');

					if (!value) {
						filter.element
							.querySelector('.filter-tabs__tab[value=""]')
							?.classList.add('selected');
						if (tabSelectSelected) {
							tabSelectSelected.innerHTML = filter.defaultValue;
						}
					} else {
						filter.element
							.querySelector(
								'.filter-tabs__tab[value="' + value[0].id + '"]'
							)
							?.classList.add('selected');
						if (tabSelectSelected) {
							tabSelectSelected.innerHTML = value[0].name;
						}
					}
				}
			}
		});

		// Clear search input
		if (this.searchInput) {
			if (!this.selectedFilters.has('search')) {
				this.searchInput.value = '';
				this.searchClear.classList.remove('active');
			} else {
				this.searchClear.classList.add('active');
			}
		}

		// Toggle visibility of "Clear All" button
		if (this.clearFiltersBtn) {
			this.clearFiltersBtn.disabled = this.selectedFilters.size === 0;
		}

		this.resetPage();
		this.renderSelectedFilters();
		this.updateModalButtonCount();
		this.fetchPosts();
	}

	/**
	 * Retrieves the filter element(s) based on the taxonomy.
	 *
	 * @param {string} taxonomy - The taxonomy of the filter element(s) to retrieve.
	 * @return {Array} An array containing filter element(s) with the specified taxonomy.
	 */
	getFilterElementByTaxonomy(taxonomy) {
		return this.filters.filter((filter) => filter.taxonomy === taxonomy);
	}

	/**
	 * Handles the click event on a selected filter button, removing the filter from the selected filters.
	 *
	 * @param {HTMLElement} button - The selected filter button element to handle click event for.
	 */
	handleSelectedFilterClick(button) {
		const {
			dataset: { filter, taxonomy, value },
		} = button;

		if (filter) {
			if ('search' === filter) {
				this.selectedFilters.delete('search');
			} else {
				const selectedFilterValue = this.selectedFilters.get(taxonomy);
				const indexToRemove = selectedFilterValue.findIndex(
					(obj) => obj.id === parseInt(value)
				);

				if (indexToRemove !== -1) {
					selectedFilterValue.splice(indexToRemove, 1);
					if (selectedFilterValue.length === 0) {
						this.selectedFilters.delete(taxonomy);
					} else {
						this.selectedFilters.set(taxonomy, selectedFilterValue);
					}
				}
			}
		}

		button.remove();
	}

	/**
	 * Handles the click event on a tab filter, updating the selected filters and UI accordingly.
	 *
	 * @param {HTMLElement} tab - The tab element clicked.
	 */
	handleTabClick(tab) {
		const {
			value,
			dataset: { taxonomy },
			innerText,
		} = tab;

		if (!taxonomy) return false;

		const tabContainer = tab.closest('.filter-tabs');
		const selectedTab = tabContainer.querySelector(
			'.filter-tabs__tab.selected'
		);
		const tabSelect = tabContainer.querySelector('.filter-tabs__select');
		const tabSelectSelected = tabSelect.querySelector(
			'.filter-tabs__select-selected'
		);

		if (value) {
			this.selectedFilters.set(taxonomy, [
				{ id: parseInt(value), name: innerText },
			]);
		} else {
			this.selectedFilters.delete(taxonomy);
		}

		if (tabContainer && tabContainer.classList.contains('open')) {
			tabContainer.classList.remove('open');
			tabSelectSelected.innerText = innerText;
		}

		selectedTab?.classList.remove('selected');
		tab.classList.add('selected');
	}

	/**
	 * Handles the click event on the dropdown button of a tab filter, toggling the visibility of the tab dropdown menu.
	 *
	 * @param {HTMLElement} tab - The tab element whose dropdown button was clicked.
	 */
	handleTabDropdownClick(tab) {
		const tabContainer = tab.closest('.filter-tabs');

		tabContainer.classList.toggle('open');
	}

	/**
	 * Handles the click event on a select filter, toggling its active state and closing other select filters if needed.
	 *
	 * @param {HTMLElement} select - The select element clicked.
	 */
	handleSelectClick(select) {
		const filterSelect = select.closest('.filter-select');

		if (!filterSelect.classList.contains('active')) {
			this.closeAllSelects();
		}

		filterSelect.classList.toggle('active');

		if (filterSelect.classList.contains('active')) {
			document.body.addEventListener(
				'click',
				this.handleBodyClickCloseSelect
			);
		} else {
			document.body.removeEventListener(
				'click',
				this.handleBodyClickCloseSelect
			);
		}
	}

	/**
	 * Handles the body click event to close all select filters.
	 */
	handleBodyClickCloseSelect() {
		// Todo: Closing all selects is kinda gross but it works for now
		this.closeAllSelects();

		document.body.removeEventListener(
			'click',
			this.handleBodyClickCloseSelect
		);
	}

	/**
	 * Closes all select filters by removing the 'active' class from their respective container elements.
	 */
	closeAllSelects() {
		this.filterSelects.forEach((select) => {
			select.closest('.filter-select').classList.remove('active');
		});
	}

	/**
	 * Handles the click event on an option within a select filter, updating the selected filters accordingly.
	 *
	 * @param {HTMLElement} option - The option element clicked.
	 */
	handleSelectOptionClick(option) {
		const filterSelect = option.closest('.filter-select');
		const taxonomy = filterSelect?.dataset.taxonomy;

		if (!filterSelect || !taxonomy) return;

		const {
			dataset: { value },
			innerText,
		} = option;

		const parsedValue = !isNaN(value) ? parseInt(value) : value;

		if (parsedValue && innerText) {
			this.selectedFilters.set(taxonomy, [
				{ id: parsedValue, name: innerText },
			]);
		} else {
			this.selectedFilters.delete(taxonomy);
		}
	}

	/**
	 * Handles the click event on a multi-select filter, toggling its active state and closing other multi-select filters if needed.
	 *
	 * @param {HTMLElement} multiSelect - The multi-select element clicked.
	 */
	handleMultiSelectClick(multiSelect) {
		const filterMultiSelect = multiSelect.closest('.filter-multi-select');

		if (!filterMultiSelect.classList.contains('active')) {
			this.closeAllMultiSelects();
		}

		filterMultiSelect.classList.toggle('active');

		if (filterMultiSelect.classList.contains('active')) {
			document.body.addEventListener('click', (e) => {
				if (e.target.closest('.filter-multi-select__dropdown')) return;
				this.handleBodyClickCloseMultiSelect();
			});
		} else {
			document.body.removeEventListener(
				'click',
				this.handleBodyClickCloseMultiSelect
			);
		}
	}

	/**
	 * Handles the click event on a checkbox within a multi-select filter, updating the selected filters accordingly.
	 *
	 * @param {HTMLElement} checkbox - The checkbox element clicked.
	 */
	handleMultiSelectCheckboxClick(checkbox) {
		const filterMultiSelect = checkbox.closest('.filter-multi-select');
		const {
			dataset: { value, name },
			checked,
		} = checkbox;
		const taxonomy = filterMultiSelect.dataset.taxonomy;

		if (!taxonomy || !value) return false;

		if (checked) {
			let newFilterValue;
			if (this.selectedFilters.has(taxonomy)) {
				const filterValue = this.selectedFilters.get(taxonomy);
				filterValue.push({ id: parseInt(value), name });
				newFilterValue = filterValue;
			} else {
				newFilterValue = [{ id: parseInt(value), name }];
			}
			this.selectedFilters.set(taxonomy, newFilterValue);
		} else {
			const filterValue = this.selectedFilters.get(taxonomy);
			const indexToRemove = filterValue.findIndex(
				(obj) => obj.id === parseInt(value)
			);

			if (indexToRemove !== -1) {
				filterValue.splice(indexToRemove, 1);
				if (filterValue.length === 0) {
					this.selectedFilters.delete(taxonomy);
				} else {
					this.selectedFilters.set(taxonomy, filterValue);
				}
			}
		}
	}

	/**
	 * Handles the body click event to close all multi-select filters.
	 */
	handleBodyClickCloseMultiSelect() {
		// Todo: Closing all selects is kinda gross but it works for now
		this.closeAllMultiSelects();

		document.body.removeEventListener(
			'click',
			this.handleBodyClickCloseMultiSelect
		);
	}

	/**
	 * Closes all multi-select filters by removing the 'active' class from their respective container elements.
	 */
	closeAllMultiSelects() {
		this.filterMultiSelects.forEach((multiSelect) => {
			multiSelect
				.closest('.filter-multi-select')
				.classList.remove('active');
		});
	}

	/**
	 * Handles the submission of the search input, updating the selected filters with the search term and triggering UI and data updates.
	 */
	handleSearchSubmit() {
		const searchTerm = this.searchInput.value;

		if (searchTerm) {
			this.selectedFilters.set('search', {
				id: searchTerm,
				name: searchTerm,
			});
		} else {
			this.selectedFilters.delete('search');
		}

		this.resetPage();
		this.renderSelectedFilters();
		this.fetchPosts();
	}

	/**
	 * Handles the search clear click which clears the input and removes the search term from the selected filters.
	 */
	handleSearchClear() {
		this.selectedFilters.delete('search');

		this.resetPage();
		this.renderSelectedFilters();
		this.fetchPosts();
	}

	/**
	 * Handles the click event on a pagination link, extracting the page number and triggering the fetch of posts for the selected page.
	 *
	 * @param {Event} e - The click event.
	 */
	handlePaginationClick(e) {
		if (
			!e.target.classList.contains('page-numbers') ||
			e.target.classList.contains('current')
		) {
			return;
		}

		let pageNumber;
		const pageMatch = e.target.href.match(/\/page\/(\d+)/);

		if (!pageMatch || pageMatch.length <= 1) {
			pageNumber = 1;
		} else {
			pageNumber = pageMatch[1];
		}

		this.currentPage = parseInt(pageNumber);
		this.fetchPosts(false, true);
	}

	/**
	 * Handles the body click event to close the sort filter.
	 */
	handleBodyClickCloseSort() {
		this.filterSort.forEach((sort) => {
			sort.classList.remove('active');
		});
		document.body.removeEventListener(
			'click',
			this.handleBodyClickCloseSort
		);
	}

	/**
	 * Handles the click event on the sort filter, toggling its active state and setting up or removing event listener to close the sort filter.
	 *
	 * @param {Event} e - The click event.
	 */
	handleSortClick(e) {
		const sort = e.currentTarget;

		sort.classList.toggle('active');

		if (sort.classList.contains('active')) {
			document.body.addEventListener(
				'click',
				this.handleBodyClickCloseSort
			);
		} else {
			document.body.removeEventListener(
				'click',
				this.handleBodyClickCloseSort
			);
		}
	}

	/**
	 * Handles the click event on an option within the sort filter, updating the sorting criteria and triggering a fetch of posts accordingly.
	 *
	 * @param {HTMLElement} option - The option element clicked.
	 * @param {HTMLElement} sort   - The sort filter element.
	 */
	handleSortOptionClick(option, sort) {
		const { value } = option;
		const sortCurrent = sort.querySelector('.filter-sort__label-current');
		const options = sort.querySelectorAll('.filter-sort__option');

		// Update sort text
		if (sortCurrent) {
			sortCurrent.innerText = option.innerText;
		}

		if (options.length) {
			options.forEach((opt) => {
				if (opt.value === value) {
					opt.classList.add('selected');
				} else {
					opt.classList.remove('selected');
				}
			});
		}

		if ('newest' === value || 'reverse_alphabetical' === value) {
			this.order = 'desc';
		}

		if ('oldest' === value || 'alphabetical' === value) {
			this.order = 'asc';
		}

		if ('newest' === value || 'oldest' === value) {
			this.orderby = 'date';
		}

		if ('alphabetical' === value || 'reverse_alphabetical' === value) {
			this.orderby = 'title';
		}

		this.resetPage();
		this.fetchPosts();
	}

	/**
	 * Toggles the visibility of the modal.
	 *
	 * @param {boolean} active - A boolean indicating whether to make the modal active or inactive.
	 */
	toggleModal(active) {
		if (active) {
			document.body.classList.add('filter-modal-open');
			this.modalContainer.classList.add('active');
		} else {
			document.body.classList.remove('filter-modal-open');
			this.modalContainer.classList.remove('active');
		}
	}

	/**
	 * Clears all selected filters.
	 */
	clearFilters() {
		this.selectedFilters.clear();
	}

	/**
	 * Toggles the visibility of the "Load More" button based on the current page and total posts.
	 */
	toggleLoadMore() {
		if (!this.loadMore) return;
		if (this.currentPage === this.maxPages || 0 === this.totalPosts) {
			this.loadMore.disabled = true;
		} else {
			this.loadMore.disabled = false;
		}
	}

	/**
	 * Resets the current page to the first page.
	 */
	resetPage() {
		this.currentPage = 1;
	}

	/**
	 * Builds the API URL for fetching posts based on the selected filters, search term, sorting criteria, pagination, and other parameters.
	 *
	 * @return {string} The constructed API URL.
	 */
	buildApiURL() {
		const postType = this.postType === 'post' ? 'posts' : this.postType;
		let url = this.restBase + postType;

		if ('attachment' === postType) {
			url = this.restBase + 'media';
		}

		const params = new URLSearchParams();

		params.append('catapult_filters', 1);

		// Build taxonomy param(s)
		if (this.selectedFilters && this.selectedFilters.size > 0) {
			this.selectedFilters.forEach((value, key) => {
				let taxonomy = key;
				if ('category' === key) taxonomy = 'categories';
				if ('post_tag' === key) taxonomy = 'tags';
				if (Array.isArray(value)) {
					const values = value.map((obj) => obj.id).join(',');
					params.append(taxonomy, values);
				} else {
					params.append(taxonomy, value.id);
				}
			});
		}

		// Build search param
		if (this.searchTerm) {
			params.append('search', this.searchTerm);
		}

		// Build order param
		if (this.order) {
			params.append('order', this.order);
		}

		// Build orderby param
		if (this.orderby) {
			params.append('orderby', this.orderby);
		}

		// Build per page param
		if (this.perPage) {
			params.append('per_page', this.perPage);
		}

		// Build current page param
		if (this.currentPage) {
			params.append('page', this.currentPage);
		}

		// Build tax relation param
		params.append('tax_relation', 'AND');

		// Append params to url
		if ('' !== params.toString()) {
			url += '?' + params.toString();
		}

		// console.log(url);

		return url;
	}

	/**
	 * Updates the URL and browser history to reflect the current page state, if applicable.
	 */
	updateUrlAndHistory() {
		if (
			!this.isArchive ||
			!this.currentPage ||
			'load_more' === this.loadType
		) {
			return;
		}

		const page = this.currentPage;
		const url = new URL(window.location.href);
		const pageNumberRegex = new RegExp('/page/\\d+');

		if (pageNumberRegex.test(url.pathname)) {
			if (1 === page) {
				url.pathname = url.pathname.replace(pageNumberRegex, '');
			} else {
				url.pathname = url.pathname.replace(
					pageNumberRegex,
					'/page/' + page
				);
			}
		} else if (1 !== page) {
			url.pathname += 'page/' + page + '/';
		}

		history.pushState(null, '', url.href);
	}

	/**
	 * Fetches posts from the API based on the current filters, search term, sorting criteria, and pagination parameters.
	 *
	 * @param {boolean|string} append           - A boolean indicating whether to append the fetched posts to existing ones or replace them. Can also be a string with value "before" to append posts to the beginning of the container.
	 * @param {boolean}        scrollToBlockTop - A boolean indicating whether to scroll the viewport to the top of the block. Used after clicking a pagination link.
	 * @return {Promise<boolean>} A promise indicating whether the fetching operation was successful or not.
	 */
	async fetchPosts(append = false, scrollToBlockTop = false) {
		const url = this.buildApiURL();

		if (!url) return false;

		const selectedFiltersObject = {};
		this.selectedFilters.forEach((key, value) => {
			if (Array.isArray(key)) {
				let filters = '';
				key.forEach((k) => {
					filters += k.name;
				});
				selectedFiltersObject[value] = filters;
			} else {
				selectedFiltersObject[value] = key.name;
			}
		});

		try {
			this.postsWrapper.classList.add('is-loading');

			const response = await fetch(url, {
				headers: {
					'X-Filterblock': 'true',
					'X-Selectedfilters': JSON.stringify(selectedFiltersObject),
				},
			});

			if (!response.ok) {
				throw new Error('There was an error fetching posts');
			}

			const json = await response.json();

			this.postsWrapper.classList.remove('is-loading');

			if (null !== json) {
				if (json.no_results) {
					this.renderNoResults(json.no_results);
				} else {
					const jsonKeys = Object.keys(json);
					let postsHTML = '';

					if (jsonKeys.length) {
						jsonKeys.forEach((key) => {
							if (!isNaN(parseInt(key))) {
								postsHTML += json[key].card;
							}
						});
					}

					this.renderPosts(postsHTML, append);
				}
			}

			const totalPosts = response.headers.get('X-WP-Total');
			const totalPages = response.headers.get('X-WP-TotalPages');

			this.maxPages = parseInt(totalPages);
			this.totalPosts = parseInt(totalPosts);

			this.updateUrlAndHistory();
			this.renderPaginationLinks();
			this.renderResultCount();
			this.toggleLoadMore();
			if (scrollToBlockTop) {
				this.block.scrollIntoView({
					behavior: 'smooth',
					block: 'start',
				});
			}
		} catch (error) {
			this.postsWrapper.classList.remove('is-loading');

			console.log(error); // eslint-disable-line no-console
		}
	}

	/**
	 * Renders the HTML markup for displaying a message indicating no search results were found.
	 *
	 * @param {string} html - The HTML markup to render.
	 */
	renderNoResults(html) {
		if (null === html || !this.postsContainer) {
			return;
		}

		this.postsContainer.innerHTML = '';
		this.postsContainer.innerHTML = DOMPurify.sanitize(html);

		const noResultsEvent = new CustomEvent(
			'catapult-filters-render-no-results',
			{
				detail: { postsWrapper: this.postsWrapper },
			}
		);
		window.dispatchEvent(noResultsEvent);
	}

	/**
	 * Renders the HTML markup for displaying fetched posts either by appending them to the existing content or replacing it.
	 *
	 * @param {string}         html           - The HTML markup of the posts to render.
	 * @param {boolean|string} [append=false] - A boolean indicating whether to append the posts to existing content (default is false, meaning to replace existing content). Can also be a string with value "before" to append posts to the beginning of the container."
	 */
	renderPosts(html, append = false) {
		if (null === html || !this.postsContainer || !this.postsWrapper) {
			return;
		}

		if (append) {
			if ('before' === append) {
				this.postsContainer.innerHTML =
					DOMPurify.sanitize(html) + this.postsContainer.innerHTML;
			} else {
				this.postsContainer.innerHTML += DOMPurify.sanitize(html);
			}
		} else {
			this.appendedPages = [];
			this.postsContainer.innerHTML = DOMPurify.sanitize(html);
		}

		this.renderResultCount();

		if ('attachment' === this.postType) {
			const renderPostsEvent = new CustomEvent(
				'catapult-filters-render-posts',
				{
					detail: { postsWrapper: this.postsWrapper },
				}
			);
			window.dispatchEvent(renderPostsEvent);
		}
	}

	/**
	 * Renders the total count of fetched results.
	 */
	renderResultCount() {
		if (!this.resultCountContainer) return;

		const resultText =
			1 === this.totalPosts
				? __(' Result', 'catapult')
				: __(' Results', 'catapult');

		this.resultCountContainer.innerHTML = `${this.totalPosts} ${resultText}`;
	}

	/**
	 * Renders the selected filters as buttons in the designated container.
	 */
	renderSelectedFilters() {
		if (!this.selectedFiltersContainer) return;

		let selectedFiltersHTML = '';

		const createSelectedFilterButton = (filter, taxonomy, value, name) => {
			return (
				'<button class="selected-filter" data-filter="' +
				filter +
				'" data-taxonomy="' +
				taxonomy +
				'" data-value="' +
				value +
				'" data-name="' +
				name +
				'">' +
				name +
				'</button>'
			);
		};

		this.selectedFilters.forEach((term, taxonomy) => {
			if (Array.isArray(term)) {
				term.forEach((t) => {
					selectedFiltersHTML += createSelectedFilterButton(
						taxonomy,
						taxonomy,
						t.id,
						t.name
					);
				});
			} else {
				selectedFiltersHTML += createSelectedFilterButton(
					taxonomy,
					taxonomy,
					term.id,
					term.name
				);
			}
		});

		if (this.searchTerm) {
			selectedFiltersHTML += createSelectedFilterButton(
				'search',
				'search',
				this.searchTerm,
				this.searchTerm
			);
		}

		this.selectedFiltersContainer.innerHTML = selectedFiltersHTML;
	}

	/**
	 * Renders the pagination links for navigating between pages of fetched posts.
	 */
	renderPaginationLinks() {
		if ('pagination' !== this.loadType) {
			if (1 === this.maxPages || this.currentPage === this.maxPages) {
				this.paginationContainer.classList.add('disabled');
			} else {
				this.paginationContainer.classList.remove('disabled');
			}
			return;
		}

		const path = window.location.pathname.replace(/\/page\/\d+/, '');
		let paginationLinksHTML = '';

		if (this.maxPages === 1) {
			paginationLinksHTML = '';
		} else {
			if (this.currentPage > 1) {
				const prevHref =
					path + 'page/' + (parseInt(this.currentPage) - 1) + '/';

				paginationLinksHTML += `<li><a href="${prevHref}" class=prev page-numbers"></a><span class="sr-only">${__('Previous page', 'catapult')}</span></li>`;
			}

			for (let i = 1; i <= this.maxPages; i++) {
				if (
					i !== 1 &&
					i !== this.maxPages &&
					(i < this.currentPage - 2 || i > this.currentPage + 2)
				)
					continue;

				if (i > 2 && i === this.currentPage - 2) {
					paginationLinksHTML += `<li><span class="page-numbers dots">…</span></li>`;
				}

				if (i === this.currentPage) {
					paginationLinksHTML += `<li><span aria-current="page" class="page-numbers current">${i}</span></li>`;
				} else {
					paginationLinksHTML += `<li><a href="${path}page/${i}/" class="page-numbers">${i}</a></li>`;
				}

				if (i < this.maxPages - 1 && i === this.currentPage + 2) {
					paginationLinksHTML += `<li><span class="page-numbers dots">…</span></li>`;
				}
			}

			if (this.currentPage < this.maxPages) {
				const nextHref =
					path + 'page/' + (parseInt(this.currentPage) + 1) + '/';

				paginationLinksHTML += `<li><a href="${nextHref}" class="next page-numbers"><span class="sr-only">${__('Next page', 'catapult')}</span></a></li>`;
			}
		}

		if (this.paginationLinksContainer) {
			this.paginationLinksContainer.innerHTML = '';
			this.paginationLinksContainer.innerHTML = paginationLinksHTML;

			if ('' === paginationLinksHTML) {
				this.paginationContainer.classList.add('disabled');
			} else {
				this.paginationContainer.classList.remove('disabled');
			}
		}
	}

	/**
	 * Update selected count on modal button.
	 */
	updateModalButtonCount() {
		if (this.modalButton) {
			let totalCount = 0;
			for (const [key, value] of this.selectedFilters) {// eslint-disable-line
				// If the value is not an array we can just add one to the count because it should be the search term.
				if (Array.isArray(value)) {
					totalCount += value.length;
				} else {
					totalCount++;
				}
			}

			this.modalButton.setAttribute(
				'data-selected-count',
				totalCount === 0 ? '' : totalCount
			);
		}
	}
}

export default Filters;
