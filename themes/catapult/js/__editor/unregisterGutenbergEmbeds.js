const unregisterGutenbergEmbeds = () => {
	const allowedEmbedBlocks = [
		//'amazon-kindle',
		//'animoto',
		//'cloudup',
		//'collegehumor',
		//'crowdsignal',
		//'dailymotion',
		//'facebook',
		//'flickr',
		//'imgur',
		//'instagram',
		//'issuu',
		//'kickstarter',
		//'meetup-com',
		//'mixcloud',
		//'reddit',
		//'reverbnation',
		//'screencast',
		//'scribd',
		//'slideshare',
		//'smugmug',
		//'soundcloud',
		//'speaker-deck',
		//'spotify',
		//'ted',
		//'tiktok',
		//'tumblr',
		// 'twitter',
		//'videopress',
		// 'vimeo',
		//'wordpress',
		//'wordpress-tv',
		// 'youtube',
	];
	wp.blocks
		.getBlockVariations('core/embed')
		.forEach(function (blockVariation) {
			if (-1 === allowedEmbedBlocks.indexOf(blockVariation.name)) {
				wp.blocks.unregisterBlockVariation(
					'core/embed',
					blockVariation.name
				);
			}
		});
};
export default unregisterGutenbergEmbeds;
