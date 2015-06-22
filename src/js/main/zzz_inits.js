astro.init();
tabby.init({
	callbackAfter: function ( toggle, tabID ) {
		if ( history.pushState ) {
			history.pushState( null, null, [window.location.protocol, '//', window.location.host, window.location.pathname, window.location.search, tabID].join('') );
		}
	}
});
stickyFooter.init();

;(function (window, document, undefined) {

	'use strict';

	// Feature test
	if ( !document.querySelector ) return;

	// Variables
	var hash = window.location.hash;
	var isTabby = document.querySelector( '[data-tab]' );

	// Check if is tabby page and hash exists
	if ( !hash || !isTabby ) return;

	// Load tab based on hash
	var toggle = document.querySelector('[data-tab="' + hash + '"]');
	tabby.toggleTab( toggle, hash );
	setTimeout(function() {
		window.scrollTo(0, 0);
	}, 1);

})(window, document);