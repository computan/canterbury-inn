import SmoothScrollIntoView from '../__utils/smoothScrollIntoView';
import setHeaderHeight from '../__utils/setHeaderHeight';
import Forms from '../__utils/forms';
import viewportUnits from '../__utils/viewportUnits';
import delayedScripts from '../__utils/delayedScripts';
import searchClear from '../__page/searchClear';

// GLOBAL APP CONTROLLER
const controller = {
	init() {
		document.querySelector('html').classList.remove('no-js');
		SmoothScrollIntoView();
		setHeaderHeight();
		viewportUnits();
		delayedScripts();
		searchClear();
	},
	loaded() {
		// Only add functions here that are okay to run after the entire page has loaded (include media/images).
		Forms();
		viewportUnits();
	},
	resized() {
		viewportUnits();
		setHeaderHeight();
	},
	scrolled() {},
	keyDown() {},
	mouseUp() {},
};
export default controller;
