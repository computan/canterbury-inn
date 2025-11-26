const editorSettings = () => {
	const isFullscreenMode = wp.data
		.select('core/edit-post')
		.isFeatureActive('fullscreenMode');

	if (isFullscreenMode) {
		wp.data.dispatch('core/edit-post').toggleFeature('fullscreenMode');
	}
};

export default editorSettings;
