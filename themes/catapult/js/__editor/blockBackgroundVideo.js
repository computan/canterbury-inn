import backgroundVideo from '../../blocks/components/background-video/script.js';

const blockBackgroundVideo = () => {
	if (window.acf) {
		window.acf.addAction('render_block_preview', backgroundVideo);
	}
};

export default blockBackgroundVideo;
