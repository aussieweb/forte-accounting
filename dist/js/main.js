/**
 * forte-accounting v3.1.0
 * WordPress theme for Forte Accounting, by Chris Ferdinandi.
 * http://github.com/cferdinandi/forte-accounting
 * 
 * Free to use under the MIT License.
 * http://gomakethings.com/mit/
 */

(function (root, factory) {
	if ( typeof define === 'function' && define.amd ) {
		define([], factory(root));
	} else if ( typeof exports === 'object' ) {
		module.exports = factory(root);
	} else {
		root.buoy = factory(root);
	}
})(typeof global !== 'undefined' ? global : this.window || this.global, function (root) {

	'use strict';

	// Object for public APIs
	var buoy = {};


	//
	// Methods
	//

	/**
	 * A simple forEach() implementation for Arrays, Objects and NodeLists.
	 * @author Todd Motto
	 * @link   https://github.com/toddmotto/foreach
	 * @param {Array|Object|NodeList} collection Collection of items to iterate
	 * @param {Function}              callback   Callback function for each iteration
	 * @param {Array|Object|NodeList} scope      Object/NodeList/Array that forEach is iterating over (aka `this`)
	 */
	buoy.forEach = function ( collection, callback, scope ) {
		if ( Object.prototype.toString.call( collection ) === '[object Object]' ) {
			for ( var prop in collection ) {
				if ( Object.prototype.hasOwnProperty.call( collection, prop ) ) {
					callback.call( scope, collection[prop], prop, collection );
				}
			}
		} else {
			for ( var i = 0, len = collection.length; i < len; i++ ) {
				callback.call( scope, collection[i], i, collection );
			}
		}
	};

	/**
	 * Merge two or more objects. Returns a new object.
	 * @param {Boolean}  deep     If true, do a deep (or recursive) merge [optional]
	 * @param {Object}   objects  The objects to merge together
	 * @returns {Object}          Merged values of defaults and options
	 */
	buoy.extend = function () {

		// Variables
		var extended = {};
		var deep = false;
		var i = 0;
		var length = arguments.length;

		// Check if a deep merge
		if ( Object.prototype.toString.call( arguments[0] ) === '[object Boolean]' ) {
			deep = arguments[0];
			i++;
		}

		// Merge the object into the extended object
		var merge = function (obj) {
			for ( var prop in obj ) {
				if ( Object.prototype.hasOwnProperty.call( obj, prop ) ) {
					// If deep merge and property is an object, merge properties
					if ( deep && Object.prototype.toString.call(obj[prop]) === '[object Object]' ) {
						extended[prop] = buoy.extend( true, extended[prop], obj[prop] );
					} else {
						extended[prop] = obj[prop];
					}
				}
			}
		};

		// Loop through each object and conduct a merge
		for ( ; i < length; i++ ) {
			var obj = arguments[i];
			merge(obj);
		}

		return extended;

	};

	/**
	 * Get the height of an element.
	 * @param  {Node} elem The element to get the height of
	 * @return {Number}    The element's height in pixels
	 */
	buoy.getHeight = function ( elem ) {
		return Math.max( elem.scrollHeight, elem.offsetHeight, elem.clientHeight );
	};

	/**
	 * Get an element's distance from the top of the Document.
	 * @param  {Node} elem The element
	 * @return {Number}    Distance from the top in pixels
	 */
	buoy.getOffsetTop = function ( elem ) {
		var location = 0;
		if (elem.offsetParent) {
			do {
				location += elem.offsetTop;
				elem = elem.offsetParent;
			} while (elem);
		}
		return location >= 0 ? location : 0;
	};

	/**
	 * Get the closest matching element up the DOM tree.
	 * @param  {Element} elem     Starting element
	 * @param  {String}  selector Selector to match against (class, ID, data attribute, or tag)
	 * @return {Boolean|Element}  Returns null if not match found
	 */
	buoy.getClosest = function ( elem, selector ) {

		// Variables
		var firstChar = selector.charAt(0);
		var supports = 'classList' in document.documentElement;
		var attribute, value;

		// If selector is a data attribute, split attribute from value
		if ( firstChar === '[' ) {
			selector = selector.substr(1, selector.length - 2);
			attribute = selector.split( '=' );

			if ( attribute.length > 1 ) {
				value = true;
				attribute[1] = attribute[1].replace( /"/g, '' ).replace( /'/g, '' );
			}
		}

		// Get closest match
		for ( ; elem && elem !== document; elem = elem.parentNode ) {

			// If selector is a class
			if ( firstChar === '.' ) {
				if ( supports ) {
					if ( elem.classList.contains( selector.substr(1) ) ) {
						return elem;
					}
				} else {
					if ( new RegExp('(^|\\s)' + selector.substr(1) + '(\\s|$)').test( elem.className ) ) {
						return elem;
					}
				}
			}

			// If selector is an ID
			if ( firstChar === '#' ) {
				if ( elem.id === selector.substr(1) ) {
					return elem;
				}
			}

			// If selector is a data attribute
			if ( firstChar === '[' ) {
				if ( elem.hasAttribute( attribute[0] ) ) {
					if ( value ) {
						if ( elem.getAttribute( attribute[0] ) === attribute[1] ) {
							return elem;
						}
					} else {
						return elem;
					}
				}
			}

			// If selector is a tag
			if ( elem.tagName.toLowerCase() === selector ) {
				return elem;
			}

		}

		return null;

	};

	/**
	 * Get an element's parents.
	 * @param  {Node}   elem     The element
	 * @param  {String} selector Selector to match against (class, ID, data attribute, or tag)
	 * @return {Array}           An array of matching nodes
	 */
	buoy.getParents = function ( elem, selector ) {

		// Variables
		var parents = [];
		var supports = 'classList' in document.documentElement;
		var firstChar, attribute, value;

		// If selector is a data attribute, split attribute from value
		if ( selector ) {
			firstChar = selector.charAt(0);
			if ( firstChar === '[' ) {
				selector = selector.substr(1, selector.length - 2);
				attribute = selector.split( '=' );

				if ( attribute.length > 1 ) {
					value = true;
					attribute[1] = attribute[1].replace( /"/g, '' ).replace( /'/g, '' );
				}
			}
		}

		// Get matches
		for ( ; elem && elem !== document; elem = elem.parentNode ) {
			if ( selector ) {

				// If selector is a class
				if ( firstChar === '.' ) {
					if ( supports ) {
						if ( elem.classList.contains( selector.substr(1) ) ) {
							parents.push( elem );
						}
					} else {
						if ( new RegExp('(^|\\s)' + selector.substr(1) + '(\\s|$)').test( elem.className ) ) {
							parents.push( elem );
						}
					}
				}

				// If selector is an ID
				if ( firstChar === '#' ) {
					if ( elem.id === selector.substr(1) ) {
						parents.push( elem );
					}
				}

				// If selector is a data attribute
				if ( firstChar === '[' ) {
					if ( elem.hasAttribute( attribute[0] ) ) {
						if ( value ) {
							if ( elem.getAttribute( attribute[0] ) === attribute[1] ) {
								parents.push( elem );
							}
						} else {
							parents.push( elem );
						}
					}
				}

				// If selector is a tag
				if ( elem.tagName.toLowerCase() === selector ) {
					parents.push( elem );
				}

			} else {
				parents.push( elem );
			}

		}

		// Return parents if any exist
		if ( parents.length === 0 ) {
			return null;
		} else {
			return parents;
		}

	};

	/**
	 * Get an element's siblings.
	 * @param  {Node} elem The element
	 * @return {Array}     An array of sibling nodes
	 */
	buoy.getSiblings = function ( elem ) {

		// Variables
		var siblings = [];
		var sibling = elem.parentNode.firstChild;

		// Loop through all sibling nodes
		for ( ; sibling; sibling = sibling.nextSibling ) {
			if ( sibling.nodeType === 1 && sibling !== elem ) {
				siblings.push( sibling );
			}
		}

		return siblings;

	};

	/**
	 * Get data from a URL query string.
	 * @param  {String} field The field to get from the URL
	 * @param  {String} url   The URL to parse
	 * @return {String}       The field value
	 */
	buoy.getQueryString = function ( field, url ) {
		var href = url ? url : window.location.href;
		var reg = new RegExp( '[?&]' + field + '=([^&#]*)', 'i' );
		var string = reg.exec(href);
		return string ? string[1] : null;
	};


	//
	// Public APIs
	//

	return buoy;

});
/*
 * classList.js: Cross-browser full element.classList implementation.
 * 2014-01-31
 *
 * By Eli Grey, http://eligrey.com
 * Public Domain.
 * NO WARRANTY EXPRESSED OR IMPLIED. USE AT YOUR OWN RISK.
 */

/*global self, document, DOMException */

/*! @source http://purl.eligrey.com/github/classList.js/blob/master/classList.js*/

if ("document" in self && !("classList" in document.createElement("_"))) {

	(function (view) {

		"use strict";

		if (!('Element' in view)) return;

		var
			classListProp = "classList",
			protoProp = "prototype",
			elemCtrProto = view.Element[protoProp],
			objCtr = Object,
			strTrim = String[protoProp].trim || function () {
				return this.replace(/^\s+|\s+$/g, "");
			},
			arrIndexOf = Array[protoProp].indexOf || function (item) {
				var
					i = 0,
					len = this.length;
				for (; i < len; i++) {
					if (i in this && this[i] === item) {
						return i;
					}
				}
				return -1;
			},
			// Vendors: please allow content code to instantiate DOMExceptions
			DOMEx = function (type, message) {
				this.name = type;
				this.code = DOMException[type];
				this.message = message;
			},
			checkTokenAndGetIndex = function (classList, token) {
				if (token === "") {
					throw new DOMEx(
						"SYNTAX_ERR",
						"An invalid or illegal string was specified"
					);
				}
				if (/\s/.test(token)) {
					throw new DOMEx(
						"INVALID_CHARACTER_ERR",
						"String contains an invalid character"
					);
				}
				return arrIndexOf.call(classList, token);
			},
			ClassList = function (elem) {
				var
					trimmedClasses = strTrim.call(elem.getAttribute("class") || ""),
					classes = trimmedClasses ? trimmedClasses.split(/\s+/) : [],
					i = 0,
					len = classes.length;
				for (; i < len; i++) {
					this.push(classes[i]);
				}
				this._updateClassName = function () {
					elem.setAttribute("class", this.toString());
				};
			},
			classListProto = ClassList[protoProp] = [],
			classListGetter = function () {
				return new ClassList(this);
			};
		// Most DOMException implementations don't allow calling DOMException's toString()
		// on non-DOMExceptions. Error's toString() is sufficient here.
		DOMEx[protoProp] = Error[protoProp];
		classListProto.item = function (i) {
			return this[i] || null;
		};
		classListProto.contains = function (token) {
			token += "";
			return checkTokenAndGetIndex(this, token) !== -1;
		};
		classListProto.add = function () {
			var
				tokens = arguments,
				i = 0,
				l = tokens.length,
				token,
				updated = false;
			do {
				token = tokens[i] + "";
				if (checkTokenAndGetIndex(this, token) === -1) {
					this.push(token);
					updated = true;
				}
			}
			while (++i < l);

			if (updated) {
				this._updateClassName();
			}
		};
		classListProto.remove = function () {
			var
				tokens = arguments,
				i = 0,
				l = tokens.length,
				token,
				updated = false;
			do {
				token = tokens[i] + "";
				var index = checkTokenAndGetIndex(this, token);
				if (index !== -1) {
					this.splice(index, 1);
					updated = true;
				}
			}
			while (++i < l);

			if (updated) {
				this._updateClassName();
			}
		};
		classListProto.toggle = function (token, force) {
			token += "";

			var
				result = this.contains(token),
				method = result ? force !== true && "remove" : force !== false && "add";

			if (method) {
				this[method](token);
			}

			return !result;
		};
		classListProto.toString = function () {
			return this.join(" ");
		};

		if (objCtr.defineProperty) {
			var classListPropDesc = {
				get: classListGetter,
				enumerable: true,
				configurable: true
			};
			try {
				objCtr.defineProperty(elemCtrProto, classListProp, classListPropDesc);
			} catch (ex) { // IE 8 doesn't support enumerable:true
				if (ex.number === -0x7FF5EC54) {
					classListPropDesc.enumerable = false;
					objCtr.defineProperty(elemCtrProto, classListProp, classListPropDesc);
				}
			}
		} else if (objCtr[protoProp].__defineGetter__) {
			elemCtrProto.__defineGetter__(classListProp, classListGetter);
		}

	}(self));

}
(function (root, factory) {
	if ( typeof define === 'function' && define.amd ) {
		define(['buoy'], factory(root));
	} else if ( typeof exports === 'object' ) {
		module.exports = factory(require('buoy'));
	} else {
		root.astro = factory(root, root.buoy);
	}
})(typeof global !== "undefined" ? global : this.window || this.global, function (root) {

	'use strict';

	//
	// Variables
	//

	var astro = {}; // Object for public APIs
	var supports = !!document.querySelector && !!root.addEventListener; // Feature test
	var settings;

	// Default settings
	var defaults = {
		toggleActiveClass: 'active',
		navActiveClass: 'active',
		initClass: 'js-astro',
		callbackBefore: function () {},
		callbackAfter: function () {}
	};


	//
	// Methods
	//

	/**
	 * Show and hide navigation menu
	 * @public
	 * @param  {Element} toggle Element that triggered the toggle
	 * @param  {String} navID The ID of the navigation element to toggle
	 * @param  {Object} settings
	 * @param  {Event} event
	 */
	astro.toggleNav = function ( toggle, navID, options, event ) {

		// Selectors and variables
		var settings = buoy.extend( settings || defaults, options || {} );  // Merge user options with defaults
		var nav = document.querySelector(navID);

		settings.callbackBefore( toggle, navID ); // Run callbacks before toggling nav
		toggle.classList.toggle( settings.toggleActiveClass ); // Toggle the '.active' class on the toggle element
		nav.classList.toggle( settings.navActiveClass ); // Toggle the '.active' class on the menu
		settings.callbackAfter( toggle, navID ); // Run callbacks after toggling nav

	};

	/**
	 * Handle click event methods
	 * @private
	 */
	var eventHandler = function (event) {
		var toggle = buoy.getClosest(event.target, '[data-nav-toggle]');
		if ( toggle ) {
			// Prevent default click event
			if ( toggle.tagName.toLowerCase() === 'a') {
				event.preventDefault();
			}
			// Toggle nav
			astro.toggleNav( toggle, toggle.getAttribute('data-nav-toggle'), settings );
		}
	};

	/**
	 * Destroy the current initialization.
	 * @public
	 */
	astro.destroy = function () {
		if ( !settings ) return;
		document.documentElement.classList.remove( settings.initClass );
		document.removeEventListener('click', eventHandler, false);
		settings = null;
	};

	/**
	 * Initialize Astro
	 * @public
	 * @param {Object} options User settings
	 */
	astro.init = function ( options ) {

		// feature test
		if ( !supports ) return;

		// Destroy any existing initializations
		astro.destroy();

		// Selectors and variables
		settings = buoy.extend( defaults, options || {} ); // Merge user options with defaults

		// Listeners and methods
		document.documentElement.classList.add( settings.initClass ); // Add class to HTML element to activate conditional CSS
		document.addEventListener('click', eventHandler, false); // Listen for click events and run event handler

	};


	//
	// Public APIs
	//

	return astro;

});
(function (root, factory) {
	if ( typeof define === 'function' && define.amd ) {
		define(['buoy'], factory(root));
	} else if ( typeof exports === 'object' ) {
		module.exports = factory(require('buoy'));
	} else {
		root.stickyFooter = factory(root, root.buoy);
	}
})(typeof global !== "undefined" ? global : this.window || this.global, function (root) {

	'use strict';

	//
	// Variables
	//

	var stickyFooter = {}; // Object for public APIs
	var supports = !!document.querySelector && !!root.addEventListener; // Feature test
	var settings, wrap, footer, eventTimeout;

	// Default settings
	var defaults = {
		callbackBefore: function () {},
		callbackAfter: function () {}
	};


	//
	// Methods
	//

	/**
	 * Get height of the viewport
	 * @private
	 * @return {Number} Height of the viewport in pixels
	 */
	var getViewportHeight = function () {
		return Math.max( document.documentElement.clientHeight, window.innerHeight || 0 );
	};

	/**
	 * Set page wrapper height to fill viewport (minus footer height)
	 * @private
	 * @param {Element} wrap Page wrapper
	 * @param {Element} footer Page footer
	 * @param {Object} settings
	 */
	var setWrapHeight = function ( wrap, footer, settings ) {
		settings.callbackBefore(); // Run callbacks before...
		wrap.style.minHeight = ( getViewportHeight() - footer.offsetHeight ) + 'px';
		settings.callbackAfter(); // Run callbacks after...
	};

	/**
	 * Destroy the current initialization.
	 * @public
	 */
	stickyFooter.destroy = function () {

		if ( !settings ) return;

		// Unset styles
		document.documentElement.style.minHeight = '';
		document.body.style.minHeight = '';
		wrap.style.minHeight = '';
		window.removeEventListener( 'resize', eventThrottler, false );

		// Reset variables
		settings = null;
		wrap = null;
		footer = null;
		eventTimeout = null;

	};

	/**
	 * On window scroll and resize, only run events at a rate of 15fps for better performance
	 * @private
	 * @param  {Function} eventTimeout Timeout function
	 * @param  {NodeList} wrap The content wrapper for the page
	 * @param  {NodeList} footer The footer for the page
	 * @param  {Object} settings
	 */
	var eventThrottler = function () {
		if ( !eventTimeout ) {
			eventTimeout = setTimeout(function() {
				eventTimeout = null;
				setWrapHeight( wrap, footer, settings );
			}, 66);
		}
	};

	/**
	 * Initialize Plugin
	 * @public
	 * @param {Object} options User settings
	 */
	stickyFooter.init = function ( options ) {

		// feature test
		if ( !supports ) return;

		// Destroy any existing initializations
		stickyFooter.destroy();

		// Selectors and variables
		settings = buoy.extend( defaults, options || {} ); // Merge user options with defaults
		wrap = document.querySelector( '[data-sticky-wrap]' );
		footer = document.querySelector( '[data-sticky-footer]' );

		// Stick footer
		document.documentElement.style.minHeight = '100%';
		document.body.style.minHeight = '100%';
		setWrapHeight( wrap, footer, settings );
		window.addEventListener( 'resize', eventThrottler, false); // Run Sticky Footer on window resize

	};


	//
	// Public APIs
	//

	return stickyFooter;

});
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