import Filters from '../../components/filters/script';

const blocks = document.querySelectorAll('.block-filter-top');
if (blocks.length > 0) {
	blocks.forEach((block) => {
		const postsWrapper = block.querySelector(
			'.block-filter-top .block-filter-top__posts'
		);
		if (postsWrapper) {
			new Filters(block, postsWrapper);
		}
	});
}
