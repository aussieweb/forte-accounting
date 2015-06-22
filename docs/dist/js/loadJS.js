/**
 * forte-accounting v3.0.0
 * WordPress theme for Forte Accounting, by Chris Ferdinandi.
 * http://github.com/cferdinandi/forte-accounting
 * 
 * Free to use under the MIT License.
 * http://gomakethings.com/mit/
 */

/*! loadJS: load a JS file asynchronously. [c]2014 @scottjehl, Filament Group, Inc. (Based on http://goo.gl/REQGQ by Paul Irish). Licensed MIT */
function loadJS( src, cb ){
	"use strict";
	var ref = window.document.getElementsByTagName( "script" )[ 0 ];
	var script = window.document.createElement( "script" );
	script.src = src;
	script.async = true;
	ref.parentNode.insertBefore( script, ref );
	if (cb && typeof(cb) === "function") {
		script.onload = cb;
	}
	return script;
}