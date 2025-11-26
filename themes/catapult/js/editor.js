import editorSettings from './__editor/editorSettings';
import acfBlocks from './__editor/acfBlocks';
import filterBlocks from './__editor/filterBlocks';
import mediaBlocks from './__editor/mediaBlocks';
import blockBackgroundVideo from './__editor/blockBackgroundVideo';
import colors from './__editor/colors';
import highlightText from './__editor/highlightText';
import removeFormats from './__editor/removeFormats';
import unregisterGutenbergEmbeds from './__editor/unregisterGutenbergEmbeds';
import textWidthStyles from './__editor/textWidthStyles';
import blockInstructions from './__editor/blockInstructions';

mediaBlocks();
blockInstructions();
filterBlocks();

wp.domReady(function () {
	editorSettings();
	acfBlocks();
	blockBackgroundVideo();
	colors();
	highlightText();
	removeFormats();
	unregisterGutenbergEmbeds();
	textWidthStyles();
});
