const colors = () => {
	if (window.acf) {
		// Customize the Color picker field modal
		// eslint-disable-next-line no-undef
		window.acf.add_filter('color_picker_args', function (args) {
			args.palettes = ['#ffffff', '#6200ee', '#3700b3', '#000000'];
			return args;
		});
	}
};

export default colors;
