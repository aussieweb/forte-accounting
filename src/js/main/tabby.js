(function (root, factory) {
	if ( typeof define === 'function' && define.amd ) {
		define(['buoy'], factory(root));
	} else if ( typeof exports === 'object' ) {
		module.exports = factory(require('buoy'));
	} else {
		root.tabby = factory(root, root.buoy);
	}
})(typeof global !== "undefined" ? global : this.window || this.global, function (root) {

	'use strict';

	//
	// Variables
	//

	var tabby = {}; // Object for public APIs
	var supports = !!document.querySelector && !!root.addEventListener; // Feature test
	var settings;

	// Default settings
	var defaults = {
		toggleActiveClass: 'active',
		contentActiveClass: 'active',
		initClass: 'js-tabby',
		callbackBefore: function () {},
		callbackAfter: function () {}
	};


	//
	// Methods
	//

	/**
	 * Stop YouTube, Vimeo, and HTML5 videos from playing when leaving the slide
	 * @private
	 * @param  {Element} content The content container the video is in
	 * @param  {String} activeClass The class asigned to expanded content areas
	 */
	var stopVideos = function ( content, activeClass ) {
		if ( !content.classList.contains( activeClass ) ) {
			var iframe = content.querySelector( 'iframe');
			var video = content.querySelector( 'video' );
			if ( iframe ) {
				var iframeSrc = iframe.src;
				iframe.src = iframeSrc;
			}
			if ( video ) {
				video.pause();
			}
		}
	};

	/**
	 * Hide all other tabs and content
	 * @param  {Element} toggle The element that toggled the tab content
	 * @param  {Element} tab The tab to show
	 * @param  {Object} settings
	 */
	var hideOtherTabs = function ( toggle, tab, settings ) {

		// Variables
		var isLinkList = toggle.parentNode.tagName.toLowerCase() === 'li' ? true : false;
		var toggleSiblings = isLinkList ? buoy.getSiblings(toggle.parentNode) : buoy.getSiblings(toggle);
		var tabSiblings = buoy.getSiblings(tab);

		// Hide toggles
		buoy.forEach(toggleSiblings, function (sibling) {
			sibling.classList.remove( settings.toggleActiveClass );
			if ( isLinkList ) {
				sibling.querySelector('[data-tab]').classList.remove( settings.toggleActiveClass );
			}
		});

		// Hide tabs
		buoy.forEach(tabSiblings, function (tab) {
			if ( tab.classList.contains( settings.contentActiveClass ) ) {
				stopVideos(tab);
				tab.classList.remove( settings.contentActiveClass );
			}
		});

	};

	/**
	 * Show target tabs
	 * @private
	 * @param  {NodeList} tabs A nodelist of tabs to close
	 * @param  {Object} settings
	 */
	// var showTargetTabs = function ( tabs, settings ) {
	var showTargetTabs = function ( toggle, tabs, settings ) {
		var toggleParent = toggle.parentNode;
		toggle.classList.add( settings.toggleActiveClass );
		if ( toggleParent && toggleParent.tagName.toLowerCase() === 'li' ) {
			toggleParent.classList.add( settings.toggleActiveClass );
		}
		buoy.forEach(tabs, function (tab) {
			tab.classList.add( settings.contentActiveClass );
		});
	};

	/**
	 * Show a tab and hide all others
	 * @public
	 * @param  {Element} toggle The element that toggled the show tab event
	 * @param  {String} tabID The ID of the tab to show
	 * @param  {Object} options
	 * @param  {Event} event
	 */
	tabby.toggleTab = function ( toggle, tabID, options, event ) {

		// Selectors and variables
		var settings = buoy.extend( settings || defaults, options || {} );  // Merge user options with defaults
		var tabs = document.querySelectorAll(tabID); // Get tab content

		settings.callbackBefore( toggle, tabID ); // Run callbacks before toggling tab

		// Set clicked toggle to active. Deactivate others.
		hideOtherTabs( toggle, tabs[0], settings );
		showTargetTabs( toggle, tabs, settings );

		settings.callbackAfter( toggle, tabID ); // Run callbacks after toggling tab

	};

	/**
	 * Handle toggle click events
	 * @private
	 */
	var eventHandler = function (event) {
		var toggle = buoy.getClosest(event.target, '[data-tab]');
		if ( toggle ) {
			event.preventDefault();
			tabby.toggleTab(toggle, toggle.getAttribute('data-tab'), settings);
		}
	};

	/**
	 * Destroy the current initialization.
	 * @public
	 */
	tabby.destroy = function () {
		if ( !settings ) return;
		document.documentElement.classList.remove( settings.initClass );
		document.removeEventListener('click', eventHandler, false);
		settings = null;
	};

	/**
	 * Initialize Tabby
	 * @public
	 * @param {Object} options User settings
	 */
	tabby.init = function ( options ) {

		// feature test
		if ( !supports ) return;

		// Destroy any existing initializations
		tabby.destroy();

		// Merge user options with defaults
		settings = buoy.extend( defaults, options || {} );

		// Add class to HTML element to activate conditional CSS
		document.documentElement.classList.add( settings.initClass );

		// Listen for all click events
		document.addEventListener('click', eventHandler, false);

	};


	//
	// Public APIs
	//

	return tabby;

});