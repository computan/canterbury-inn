import Filters from '../../components/filters/script';

const blocks = document.querySelectorAll('.block-filter-side');
if (blocks.length > 0) {
	blocks.forEach((block) => {
		const postsWrapper = block.querySelector(
			'.block-filter-side .block-filter-side__posts'
		);
		if (postsWrapper) {
			new Filters(block, postsWrapper);
		}
	});
}
