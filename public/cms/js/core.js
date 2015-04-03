(function() {
	if (!String.prototype.endsWith) {

		/*
		 * Determines whether a string ends with the specified suffix.
		 * 
		 * @param  {String} suffix
		 * @return Boolean
		 */
		String.prototype.endsWith = function(suffix) {
			return this.indexOf(suffix, this.length - suffix.length) !== -1;
		};
	}

	if (!String.prototype.trim) {

		/*
		 * Removes whitespace from both sides of a string.
		 * 
		 * @return {String}
		 */
		String.prototype.trim = function() {
			return this.replace(/^\s+|\s+$/g, '');
		};
	}

	if (!Array.prototype.indexOf) {

		/*
		 * The indexOf() method returns the first index at which a given element can be found in the array, or -1 if it is not present.
		 * 
		 * @param  {Variant} searchElement
		 * @param  {Integer} fromIndex
		 * @return {Integer}
		 */
		Array.prototype.indexOf = function(searchElement, fromIndex) {
			var i, length, _i;
			if (this === void 0 || this === null) {
				throw new TypeError('"this" is null or not defined');
			}
			length = this.length >>> 0;
			fromIndex = +fromIndex || 0;
			if (Math.abs(fromIndex) === Infinity) {
				fromIndex = 0;
			}
			if (fromIndex < 0) {
				fromIndex += length;
				if (fromIndex < 0) {
					fromIndex = 0;
				}
			}
			for (i = _i = fromIndex; fromIndex <= length ? _i < length : _i > length; i = fromIndex <= length ? ++_i : --_i) {
				if (this[i] === searchElement) {
					return i;
				}
			}
			return -1;
		};
	}

	if (!Function.prototype.bind) {
		Function.prototype.bind = function(oThis) {
			var aArgs, fBound, fNOP, fToBind;
			if (typeof this !== "function") {
				throw new TypeError("Function.prototype.bind - what is trying to be bound is not callable");
			}
			aArgs = Array.prototype.slice.call(arguments, 1);
			fToBind = this;
			fNOP = function() {
			};
			fBound = function() {
				return fToBind.apply((this instanceof fNOP && oThis ? this : oThis), aArgs.concat(Array.prototype.slice.call(arguments)));
			};
			fNOP.prototype = this.prototype;
			fBound.prototype = new fNOP();
			return fBound;
		};
	}

	if (!Object.keys) {
		Object.keys = (function() {
			'use strict';
			var dontEnums, hasDontEnumBug, hasOwnProperty;
			hasOwnProperty = Object.prototype.hasOwnProperty;
			hasDontEnumBug = {
				toString: null
			}.propertyIsEnumerable('toString') ? false : true;
			dontEnums = ['toString', 'toLocaleString', 'valueOf', 'hasOwnProperty', 'isPrototypeOf', 'propertyIsEnumerable', 'constructor'];
			return function(obj) {
				var dontEnum, prop, result, _i, _j, _len, _len1;
				if (typeof obj !== 'object' && (typeof obj !== 'function' || obj === null)) {
					throw new TypeError('Object.keys called on non-object');
				}
				result = [];
				for (_i = 0, _len = obj.length; _i < _len; _i++) {
					prop = obj[_i];
					if (hasOwnProperty.call(obj, prop)) {
						result.push(prop);
					}
				}
				if (hasDontEnumBug) {
					for (_j = 0, _len1 = dontEnums.length; _j < _len1; _j++) {
						dontEnum = dontEnums[_j];
						if (hasOwnProperty.call(obj, dontEnum)) {
							result.push(dontEnum);
						}
					}
				}
				return result;
			};
		}).call(this);
	}


	/*
	 * Detect screen size.
	 * 
	 * @param  {jQuery Object} $ssw_point
	 * @param  {jQuery Object} $tsw_point
	 * @return {String}
	 */

	window.getScreenSize = function($ssw_point, $tsw_point) {
		if ($ssw_point.is(':visible')) {
			return 'small';
		} else if ($tsw_point.is(':visible')) {
			return 'tablet';
		} else {
			return 'desktop';
		}
	};

	window.elHasClass = function(el, selector) {
		return (" " + el.className + " ").indexOf(" " + selector + " ") > -1;
	};

	window.elRemoveClass = function(el, selector) {
		return el.className = (" " + el.className + " ").replace(" " + selector + " ", ' ').trim();
	};

}).call(this);

(function() {
	var KodiCMSApp, SETTINGS_DEFAULTS;

	SETTINGS_DEFAULTS = {
		is_mobile: false,
		resize_delay: 400,
		stored_values_prefix: 'cms_',
		consts: {
			COLORS: ['#71c73e', '#77b7c5', '#d54848', '#6c42e5', '#e8e64e', '#dd56e6', '#ecad3f', '#618b9d', '#b68b68', '#36a766', '#3156be', '#00b3ff', '#646464', '#a946e8', '#9d9d9d']
		}
	};


	/*
	 * @class KodiCMSApp
	 */
	KodiCMSApp = function() {
		this.init = [];
		this.plugins = {};
		this.settings = {};
		this.localStorageSupported = typeof window.Storage !== "undefined" ? true : false;
		return this;
	};

	/*
	 * Start application. Method takes an array of initializers and a settings object(that overrides default settings).
	 * 
	 * @param  {Array} suffix
	 * @param  {Object} settings
	 * @return this
	 */

	KodiCMSApp.prototype.start = function(init, settings) {
		if (init == null) {
			init = [];
		}
		if (settings == null) {
			settings = {};
		}
		(function(_this) {
			var initilizer, _i, _len, _ref;
			$('html').addClass('pxajs');
			if (init.length > 0) {
				$.merge(_this.init, init);
			}
			_this.settings = $.extend(true, {}, SETTINGS_DEFAULTS, settings || {});
			_this.settings.is_mobile = /iphone|ipad|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase());
			if (_this.settings.is_mobile) {
				if (FastClick) {
					FastClick.attach(document.body);
				}
			}
			_ref = _this.init;
			for (_i = 0, _len = _ref.length; _i < _len; _i++) {
				initilizer = _ref[_i];
				$.proxy(initilizer, _this)();
			}
			$(window).trigger("pa.loaded");
			return $(window).resize();
		})(this);
		return this;
	};


	/*
	 * Add initializer to the stack.
	 * 
	 * @param  {Function} callback
	 */

	KodiCMSApp.prototype.addInitializer = function(callback) {
		return this.init.push(callback);
	};


	/*
	 * Initialize plugin and add it to the plugins list.
	 * 
	 * @param  {String} plugin_name
	 * @param  {Instance} plugin
	 */
	KodiCMSApp.prototype.initPlugin = function(plugin_name, plugin, settings) {
		if (settings != null) {
			this.settings = $.extend(true, {}, this.settings, settings || {});
		}
		
		this.plugins[plugin_name] = plugin;
		if (plugin.init) {
			return plugin.init();
		}
	};


	/*
	 * Save value in the localStorage/Cookies.
	 * 
	 * @param  {String}  key
	 * @param  {String}  value
	 * @param  {Boolean} use_cookies
	 */
	KodiCMSApp.prototype.storeValue = function(key, value, use_cookies) {
		var e;
		
		if (use_cookies == null) {
			use_cookies = false;
		}
		if (this.localStorageSupported && !use_cookies) {
			try {
				window.localStorage.setItem(this.settings.stored_values_prefix + key, value);
				return;
			} catch (_error) {
				e = _error;
				1;
			}
		}
		return document.cookie = this.settings.stored_values_prefix + key + '=' + escape(value);
	};

	/*
	 * Get value from the localStorage/Cookies.
	 * 
	 * @param  {String} key
	 * @param  {Boolean} use_cookies
	 */
	KodiCMSApp.prototype.getStoredValue = function(key, use_cookies, deflt) {
		
		var cookie, cookies, e, k, pos, r, v, _i, _len;
		if (use_cookies == null) {
			use_cookies = false;
		}
		if (deflt == null) {
			deflt = null;
		}
		if (this.localStorageSupported && !use_cookies) {
			try {
				r = window.localStorage.getItem(this.settings.stored_values_prefix + key);
				return (r ? r : deflt);
			} catch (_error) {
				e = _error;
				1;
			}
		}
		cookies = document.cookie.split(';');
		for (_i = 0, _len = cookies.length; _i < _len; _i++) {
			cookie = cookies[_i];
			pos = cookie.indexOf('=');
			k = cookie.substr(0, pos).replace(/^\s+|\s+$/g, '');
			v = cookie.substr(pos + 1).replace(/^\s+|\s+$/g, '');
			if (k === (this.settings.stored_values_prefix + key)) {
				return v;
			}
		}
		return deflt;
	};

	KodiCMSApp.Constructor = KodiCMSApp;

	window.KodiCMS = new KodiCMSApp;

}).call(this);

(function() {
	var delayedResizeHandler;

	delayedResizeHandler = function(callback) {
		var resizeTimer;
		resizeTimer = null;
		return function() {
			if (resizeTimer) {
				clearTimeout(resizeTimer);
			}
			return resizeTimer = setTimeout(function() {
				resizeTimer = null;
				return callback.call(this);
			}, KodiCMS.settings.resize_delay);
		};
	};

	KodiCMS.addInitializer(function() {
		var $ssw_point, $tsw_point, $window, _last_screen;
		_last_screen = null;
		$window = $(window);
		$ssw_point = $('<div id="small-screen-width-point" style="position:absolute;top:-10000px;width:10px;height:10px;background:#fff;"></div>');
		$tsw_point = $('<div id="tablet-screen-width-point" style="position:absolute;top:-10000px;width:10px;height:10px;background:#fff;"></div>');
		$('body').append($ssw_point).append($tsw_point);
		return $window.on('resize', delayedResizeHandler(function() {
			$window.trigger("pa.resize");
			if ($ssw_point.is(':visible')) {
				if (_last_screen !== 'small') {
					$window.trigger("pa.screen.small");
				}
				return _last_screen = 'small';
			} else if ($tsw_point.is(':visible')) {
				if (_last_screen !== 'tablet') {
					$window.trigger("pa.screen.tablet");
				}
				return _last_screen = 'tablet';
			} else {
				if (_last_screen !== 'desktop') {
					$window.trigger("pa.screen.desktop");
				}
				return _last_screen = 'desktop';
			}
		}));
	});

}).call(this);

/**
 * @preserve FastClick: polyfill to remove click delays on browsers with touch UIs.
 *
 * @version 0.6.11
 * @codingstandard ftlabs-jsv2
 * @copyright The Financial Times Limited [All Rights Reserved]
 * @license MIT License (see LICENSE.txt)
 */

/*jslint browser:true, node:true*/
/*global define, Event, Node*/


/**
 * Instantiate fast-clicking listeners on the specificed layer.
 *
 * @constructor
 * @param {Element} layer The layer to listen on
 */
function FastClick(layer) {
	'use strict';
	var oldOnClick, self = this;


	/**
	 * Whether a click is currently being tracked.
	 *
	 * @type boolean
	 */
	this.trackingClick = false;


	/**
	 * Timestamp for when when click tracking started.
	 *
	 * @type number
	 */
	this.trackingClickStart = 0;


	/**
	 * The element being tracked for a click.
	 *
	 * @type EventTarget
	 */
	this.targetElement = null;


	/**
	 * X-coordinate of touch start event.
	 *
	 * @type number
	 */
	this.touchStartX = 0;


	/**
	 * Y-coordinate of touch start event.
	 *
	 * @type number
	 */
	this.touchStartY = 0;


	/**
	 * ID of the last touch, retrieved from Touch.identifier.
	 *
	 * @type number
	 */
	this.lastTouchIdentifier = 0;


	/**
	 * Touchmove boundary, beyond which a click will be cancelled.
	 *
	 * @type number
	 */
	this.touchBoundary = 10;


	/**
	 * The FastClick layer.
	 *
	 * @type Element
	 */
	this.layer = layer;

	if (!layer || !layer.nodeType) {
		throw new TypeError('Layer must be a document node');
	}

	/** @type function() */
	this.onClick = function() {
		return FastClick.prototype.onClick.apply(self, arguments);
	};

	/** @type function() */
	this.onMouse = function() {
		return FastClick.prototype.onMouse.apply(self, arguments);
	};

	/** @type function() */
	this.onTouchStart = function() {
		return FastClick.prototype.onTouchStart.apply(self, arguments);
	};

	/** @type function() */
	this.onTouchMove = function() {
		return FastClick.prototype.onTouchMove.apply(self, arguments);
	};

	/** @type function() */
	this.onTouchEnd = function() {
		return FastClick.prototype.onTouchEnd.apply(self, arguments);
	};

	/** @type function() */
	this.onTouchCancel = function() {
		return FastClick.prototype.onTouchCancel.apply(self, arguments);
	};

	if (FastClick.notNeeded(layer)) {
		return;
	}

	// Set up event handlers as required
	if (this.deviceIsAndroid) {
		layer.addEventListener('mouseover', this.onMouse, true);
		layer.addEventListener('mousedown', this.onMouse, true);
		layer.addEventListener('mouseup', this.onMouse, true);
	}

	layer.addEventListener('click', this.onClick, true);
	layer.addEventListener('touchstart', this.onTouchStart, false);
	layer.addEventListener('touchmove', this.onTouchMove, false);
	layer.addEventListener('touchend', this.onTouchEnd, false);
	layer.addEventListener('touchcancel', this.onTouchCancel, false);

	// Hack is required for browsers that don't support Event#stopImmediatePropagation (e.g. Android 2)
	// which is how FastClick normally stops click events bubbling to callbacks registered on the FastClick
	// layer when they are cancelled.
	if (!Event.prototype.stopImmediatePropagation) {
		layer.removeEventListener = function(type, callback, capture) {
			var rmv = Node.prototype.removeEventListener;
			if (type === 'click') {
				rmv.call(layer, type, callback.hijacked || callback, capture);
			} else {
				rmv.call(layer, type, callback, capture);
			}
		};

		layer.addEventListener = function(type, callback, capture) {
			var adv = Node.prototype.addEventListener;
			if (type === 'click') {
				adv.call(layer, type, callback.hijacked || (callback.hijacked = function(event) {
					if (!event.propagationStopped) {
						callback(event);
					}
				}), capture);
			} else {
				adv.call(layer, type, callback, capture);
			}
		};
	}

	// If a handler is already declared in the element's onclick attribute, it will be fired before
	// FastClick's onClick handler. Fix this by pulling out the user-defined handler function and
	// adding it as listener.
	if (typeof layer.onclick === 'function') {

		// Android browser on at least 3.2 requires a new reference to the function in layer.onclick
		// - the old one won't work if passed to addEventListener directly.
		oldOnClick = layer.onclick;
		layer.addEventListener('click', function(event) {
			oldOnClick(event);
		}, false);
		layer.onclick = null;
	}
}


/**
 * Android requires exceptions.
 *
 * @type boolean
 */
FastClick.prototype.deviceIsAndroid = navigator.userAgent.indexOf('Android') > 0;


/**
 * iOS requires exceptions.
 *
 * @type boolean
 */
FastClick.prototype.deviceIsIOS = /iP(ad|hone|od)/.test(navigator.userAgent);


/**
 * iOS 4 requires an exception for select elements.
 *
 * @type boolean
 */
FastClick.prototype.deviceIsIOS4 = FastClick.prototype.deviceIsIOS && (/OS 4_\d(_\d)?/).test(navigator.userAgent);


/**
 * iOS 6.0(+?) requires the target element to be manually derived
 *
 * @type boolean
 */
FastClick.prototype.deviceIsIOSWithBadTarget = FastClick.prototype.deviceIsIOS && (/OS ([6-9]|\d{2})_\d/).test(navigator.userAgent);


/**
 * Determine whether a given element requires a native click.
 *
 * @param {EventTarget|Element} target Target DOM element
 * @returns {boolean} Returns true if the element needs a native click
 */
FastClick.prototype.needsClick = function(target) {
	'use strict';
	switch (target.nodeName.toLowerCase()) {

		// Don't send a synthetic click to disabled inputs (issue #62)
		case 'button':
		case 'select':
		case 'textarea':
			if (target.disabled) {
				return true;
			}

			break;
		case 'input':

			// File inputs need real clicks on iOS 6 due to a browser bug (issue #68)
			if ((this.deviceIsIOS && target.type === 'file') || target.disabled) {
				return true;
			}

			break;
		case 'label':
		case 'video':
			return true;
	}

	return (/\bneedsclick\b/).test(target.className);
};


/**
 * Determine whether a given element requires a call to focus to simulate click into element.
 *
 * @param {EventTarget|Element} target Target DOM element
 * @returns {boolean} Returns true if the element requires a call to focus to simulate native click.
 */
FastClick.prototype.needsFocus = function(target) {
	'use strict';
	switch (target.nodeName.toLowerCase()) {
		case 'textarea':
			return true;
		case 'select':
			return !this.deviceIsAndroid;
		case 'input':
			switch (target.type) {
				case 'button':
				case 'checkbox':
				case 'file':
				case 'image':
				case 'radio':
				case 'submit':
					return false;
			}

			// No point in attempting to focus disabled inputs
			return !target.disabled && !target.readOnly;
		default:
			return (/\bneedsfocus\b/).test(target.className);
	}
};


/**
 * Send a click event to the specified element.
 *
 * @param {EventTarget|Element} targetElement
 * @param {Event} event
 */
FastClick.prototype.sendClick = function(targetElement, event) {
	'use strict';
	var clickEvent, touch;

	// On some Android devices activeElement needs to be blurred otherwise the synthetic click will have no effect (#24)
	if (document.activeElement && document.activeElement !== targetElement) {
		document.activeElement.blur();
	}

	touch = event.changedTouches[0];

	// Synthesise a click event, with an extra attribute so it can be tracked
	clickEvent = document.createEvent('MouseEvents');
	clickEvent.initMouseEvent(this.determineEventType(targetElement), true, true, window, 1, touch.screenX, touch.screenY, touch.clientX, touch.clientY, false, false, false, false, 0, null);
	clickEvent.forwardedTouchEvent = true;
	targetElement.dispatchEvent(clickEvent);
};

FastClick.prototype.determineEventType = function(targetElement) {
	'use strict';

	//Issue #159: Android Chrome Select Box does not open with a synthetic click event
	if (this.deviceIsAndroid && targetElement.tagName.toLowerCase() === 'select') {
		return 'mousedown';
	}

	return 'click';
};


/**
 * @param {EventTarget|Element} targetElement
 */
FastClick.prototype.focus = function(targetElement) {
	'use strict';
	var length;

	// Issue #160: on iOS 7, some input elements (e.g. date datetime) throw a vague TypeError on setSelectionRange. These elements don't have an integer value for the selectionStart and selectionEnd properties, but unfortunately that can't be used for detection because accessing the properties also throws a TypeError. Just check the type instead. Filed as Apple bug #15122724.
	if (this.deviceIsIOS && targetElement.setSelectionRange && targetElement.type.indexOf('date') !== 0 && targetElement.type !== 'time') {
		length = targetElement.value.length;
		targetElement.setSelectionRange(length, length);
	} else {
		targetElement.focus();
	}
};


/**
 * Check whether the given target element is a child of a scrollable layer and if so, set a flag on it.
 *
 * @param {EventTarget|Element} targetElement
 */
FastClick.prototype.updateScrollParent = function(targetElement) {
	'use strict';
	var scrollParent, parentElement;

	scrollParent = targetElement.fastClickScrollParent;

	// Attempt to discover whether the target element is contained within a scrollable layer. Re-check if the
	// target element was moved to another parent.
	if (!scrollParent || !scrollParent.contains(targetElement)) {
		parentElement = targetElement;
		do {
			if (parentElement.scrollHeight > parentElement.offsetHeight) {
				scrollParent = parentElement;
				targetElement.fastClickScrollParent = parentElement;
				break;
			}

			parentElement = parentElement.parentElement;
		} while (parentElement);
	}

	// Always update the scroll top tracker if possible.
	if (scrollParent) {
		scrollParent.fastClickLastScrollTop = scrollParent.scrollTop;
	}
};


/**
 * @param {EventTarget} targetElement
 * @returns {Element|EventTarget}
 */
FastClick.prototype.getTargetElementFromEventTarget = function(eventTarget) {
	'use strict';

	// On some older browsers (notably Safari on iOS 4.1 - see issue #56) the event target may be a text node.
	if (eventTarget.nodeType === Node.TEXT_NODE) {
		return eventTarget.parentNode;
	}

	return eventTarget;
};


/**
 * On touch start, record the position and scroll offset.
 *
 * @param {Event} event
 * @returns {boolean}
 */
FastClick.prototype.onTouchStart = function(event) {
	'use strict';
	var targetElement, touch, selection;

	// Ignore multiple touches, otherwise pinch-to-zoom is prevented if both fingers are on the FastClick element (issue #111).
	if (event.targetTouches.length > 1) {
		return true;
	}

	targetElement = this.getTargetElementFromEventTarget(event.target);
	touch = event.targetTouches[0];

	if (this.deviceIsIOS) {

		// Only trusted events will deselect text on iOS (issue #49)
		selection = window.getSelection();
		if (selection.rangeCount && !selection.isCollapsed) {
			return true;
		}

		if (!this.deviceIsIOS4) {

			// Weird things happen on iOS when an alert or confirm dialog is opened from a click event callback (issue #23):
			// when the user next taps anywhere else on the page, new touchstart and touchend events are dispatched
			// with the same identifier as the touch event that previously triggered the click that triggered the alert.
			// Sadly, there is an issue on iOS 4 that causes some normal touch events to have the same identifier as an
			// immediately preceeding touch event (issue #52), so this fix is unavailable on that platform.
			if (touch.identifier === this.lastTouchIdentifier) {
				event.preventDefault();
				return false;
			}

			this.lastTouchIdentifier = touch.identifier;

			// If the target element is a child of a scrollable layer (using -webkit-overflow-scrolling: touch) and:
			// 1) the user does a fling scroll on the scrollable layer
			// 2) the user stops the fling scroll with another tap
			// then the event.target of the last 'touchend' event will be the element that was under the user's finger
			// when the fling scroll was started, causing FastClick to send a click event to that layer - unless a check
			// is made to ensure that a parent layer was not scrolled before sending a synthetic click (issue #42).
			this.updateScrollParent(targetElement);
		}
	}

	this.trackingClick = true;
	this.trackingClickStart = event.timeStamp;
	this.targetElement = targetElement;

	this.touchStartX = touch.pageX;
	this.touchStartY = touch.pageY;

	// Prevent phantom clicks on fast double-tap (issue #36)
	if ((event.timeStamp - this.lastClickTime) < 200) {
		event.preventDefault();
	}

	return true;
};


/**
 * Based on a touchmove event object, check whether the touch has moved past a boundary since it started.
 *
 * @param {Event} event
 * @returns {boolean}
 */
FastClick.prototype.touchHasMoved = function(event) {
	'use strict';
	var touch = event.changedTouches[0], boundary = this.touchBoundary;

	if (Math.abs(touch.pageX - this.touchStartX) > boundary || Math.abs(touch.pageY - this.touchStartY) > boundary) {
		return true;
	}

	return false;
};


/**
 * Update the last position.
 *
 * @param {Event} event
 * @returns {boolean}
 */
FastClick.prototype.onTouchMove = function(event) {
	'use strict';
	if (!this.trackingClick) {
		return true;
	}

	// If the touch has moved, cancel the click tracking
	if (this.targetElement !== this.getTargetElementFromEventTarget(event.target) || this.touchHasMoved(event)) {
		this.trackingClick = false;
		this.targetElement = null;
	}

	return true;
};


/**
 * Attempt to find the labelled control for the given label element.
 *
 * @param {EventTarget|HTMLLabelElement} labelElement
 * @returns {Element|null}
 */
FastClick.prototype.findControl = function(labelElement) {
	'use strict';

	// Fast path for newer browsers supporting the HTML5 control attribute
	if (labelElement.control !== undefined) {
		return labelElement.control;
	}

	// All browsers under test that support touch events also support the HTML5 htmlFor attribute
	if (labelElement.htmlFor) {
		return document.getElementById(labelElement.htmlFor);
	}

	// If no for attribute exists, attempt to retrieve the first labellable descendant element
	// the list of which is defined here: http://www.w3.org/TR/html5/forms.html#category-label
	return labelElement.querySelector('button, input:not([type=hidden]), keygen, meter, output, progress, select, textarea');
};


/**
 * On touch end, determine whether to send a click event at once.
 *
 * @param {Event} event
 * @returns {boolean}
 */
FastClick.prototype.onTouchEnd = function(event) {
	'use strict';
	var forElement, trackingClickStart, targetTagName, scrollParent, touch, targetElement = this.targetElement;

	if (!this.trackingClick) {
		return true;
	}

	// Prevent phantom clicks on fast double-tap (issue #36)
	if ((event.timeStamp - this.lastClickTime) < 200) {
		this.cancelNextClick = true;
		return true;
	}

	// Reset to prevent wrong click cancel on input (issue #156).
	this.cancelNextClick = false;

	this.lastClickTime = event.timeStamp;

	trackingClickStart = this.trackingClickStart;
	this.trackingClick = false;
	this.trackingClickStart = 0;

	// On some iOS devices, the targetElement supplied with the event is invalid if the layer
	// is performing a transition or scroll, and has to be re-detected manually. Note that
	// for this to function correctly, it must be called *after* the event target is checked!
	// See issue #57; also filed as rdar://13048589 .
	if (this.deviceIsIOSWithBadTarget) {
		touch = event.changedTouches[0];

		// In certain cases arguments of elementFromPoint can be negative, so prevent setting targetElement to null
		targetElement = document.elementFromPoint(touch.pageX - window.pageXOffset, touch.pageY - window.pageYOffset) || targetElement;
		targetElement.fastClickScrollParent = this.targetElement.fastClickScrollParent;
	}

	targetTagName = targetElement.tagName.toLowerCase();
	if (targetTagName === 'label') {
		forElement = this.findControl(targetElement);
		if (forElement) {
			this.focus(targetElement);
			if (this.deviceIsAndroid) {
				return false;
			}

			targetElement = forElement;
		}
	} else if (this.needsFocus(targetElement)) {

		// Case 1: If the touch started a while ago (best guess is 100ms based on tests for issue #36) then focus will be triggered anyway. Return early and unset the target element reference so that the subsequent click will be allowed through.
		// Case 2: Without this exception for input elements tapped when the document is contained in an iframe, then any inputted text won't be visible even though the value attribute is updated as the user types (issue #37).
		if ((event.timeStamp - trackingClickStart) > 100 || (this.deviceIsIOS && window.top !== window && targetTagName === 'input')) {
			this.targetElement = null;
			return false;
		}

		this.focus(targetElement);

		// Select elements need the event to go through on iOS 4, otherwise the selector menu won't open.
		if (!this.deviceIsIOS4 || targetTagName !== 'select') {
			this.targetElement = null;
			event.preventDefault();
		}

		return false;
	}

	if (this.deviceIsIOS && !this.deviceIsIOS4) {

		// Don't send a synthetic click event if the target element is contained within a parent layer that was scrolled
		// and this tap is being used to stop the scrolling (usually initiated by a fling - issue #42).
		scrollParent = targetElement.fastClickScrollParent;
		if (scrollParent && scrollParent.fastClickLastScrollTop !== scrollParent.scrollTop) {
			return true;
		}
	}

	// Prevent the actual click from going though - unless the target node is marked as requiring
	// real clicks or if it is in the whitelist in which case only non-programmatic clicks are permitted.
	if (!this.needsClick(targetElement)) {
		event.preventDefault();
		this.sendClick(targetElement, event);
	}

	return false;
};


/**
 * On touch cancel, stop tracking the click.
 *
 * @returns {void}
 */
FastClick.prototype.onTouchCancel = function() {
	'use strict';
	this.trackingClick = false;
	this.targetElement = null;
};


/**
 * Determine mouse events which should be permitted.
 *
 * @param {Event} event
 * @returns {boolean}
 */
FastClick.prototype.onMouse = function(event) {
	'use strict';

	// If a target element was never set (because a touch event was never fired) allow the event
	if (!this.targetElement) {
		return true;
	}

	if (event.forwardedTouchEvent) {
		return true;
	}

	// Programmatically generated events targeting a specific element should be permitted
	if (!event.cancelable) {
		return true;
	}

	// Derive and check the target element to see whether the mouse event needs to be permitted;
	// unless explicitly enabled, prevent non-touch click events from triggering actions,
	// to prevent ghost/doubleclicks.
	if (!this.needsClick(this.targetElement) || this.cancelNextClick) {

		// Prevent any user-added listeners declared on FastClick element from being fired.
		if (event.stopImmediatePropagation) {
			event.stopImmediatePropagation();
		} else {

			// Part of the hack for browsers that don't support Event#stopImmediatePropagation (e.g. Android 2)
			event.propagationStopped = true;
		}

		// Cancel the event
		event.stopPropagation();
		event.preventDefault();

		return false;
	}

	// If the mouse event is permitted, return true for the action to go through.
	return true;
};


/**
 * On actual clicks, determine whether this is a touch-generated click, a click action occurring
 * naturally after a delay after a touch (which needs to be cancelled to avoid duplication), or
 * an actual click which should be permitted.
 *
 * @param {Event} event
 * @returns {boolean}
 */
FastClick.prototype.onClick = function(event) {
	'use strict';
	var permitted;

	// It's possible for another FastClick-like library delivered with third-party code to fire a click event before FastClick does (issue #44). In that case, set the click-tracking flag back to false and return early. This will cause onTouchEnd to return early.
	if (this.trackingClick) {
		this.targetElement = null;
		this.trackingClick = false;
		return true;
	}

	// Very odd behaviour on iOS (issue #18): if a submit element is present inside a form and the user hits enter in the iOS simulator or clicks the Go button on the pop-up OS keyboard the a kind of 'fake' click event will be triggered with the submit-type input element as the target.
	if (event.target.type === 'submit' && event.detail === 0) {
		return true;
	}

	permitted = this.onMouse(event);

	// Only unset targetElement if the click is not permitted. This will ensure that the check for !targetElement in onMouse fails and the browser's click doesn't go through.
	if (!permitted) {
		this.targetElement = null;
	}

	// If clicks are permitted, return true for the action to go through.
	return permitted;
};


/**
 * Remove all FastClick's event listeners.
 *
 * @returns {void}
 */
FastClick.prototype.destroy = function() {
	'use strict';
	var layer = this.layer;

	if (this.deviceIsAndroid) {
		layer.removeEventListener('mouseover', this.onMouse, true);
		layer.removeEventListener('mousedown', this.onMouse, true);
		layer.removeEventListener('mouseup', this.onMouse, true);
	}

	layer.removeEventListener('click', this.onClick, true);
	layer.removeEventListener('touchstart', this.onTouchStart, false);
	layer.removeEventListener('touchmove', this.onTouchMove, false);
	layer.removeEventListener('touchend', this.onTouchEnd, false);
	layer.removeEventListener('touchcancel', this.onTouchCancel, false);
};


/**
 * Check whether FastClick is needed.
 *
 * @param {Element} layer The layer to listen on
 */
FastClick.notNeeded = function(layer) {
	'use strict';
	var metaViewport;
	var chromeVersion;

	// Devices that don't support touch don't need FastClick
	if (typeof window.ontouchstart === 'undefined') {
		return true;
	}

	// Chrome version - zero for other browsers
	chromeVersion = +(/Chrome\/([0-9]+)/.exec(navigator.userAgent) || [, 0])[1];

	if (chromeVersion) {

		if (FastClick.prototype.deviceIsAndroid) {
			metaViewport = document.querySelector('meta[name=viewport]');

			if (metaViewport) {
				// Chrome on Android with user-scalable="no" doesn't need FastClick (issue #89)
				if (metaViewport.content.indexOf('user-scalable=no') !== -1) {
					return true;
				}
				// Chrome 32 and above with width=device-width or less don't need FastClick
				if (chromeVersion > 31 && window.innerWidth <= window.screen.width) {
					return true;
				}
			}

			// Chrome desktop doesn't need FastClick (issue #15)
		} else {
			return true;
		}
	}

	// IE10 with -ms-touch-action: none, which disables double-tap-to-zoom (issue #97)
	if (layer.style.msTouchAction === 'none') {
		return true;
	}

	return false;
};


/**
 * Factory method for creating a FastClick object
 *
 * @param {Element} layer The layer to listen on
 */
FastClick.attach = function(layer) {
	'use strict';
	return new FastClick(layer);
};


if (typeof define !== 'undefined' && define.amd) {

	// AMD. Register as an anonymous module.
	define(function() {
		'use strict';
		return FastClick;
	});
} else if (typeof module !== 'undefined' && module.exports) {
	module.exports = FastClick.attach;
	module.exports.FastClick = FastClick;
} else {
	window.FastClick = FastClick;
}
;

/*
 * Class that provides the top navbar functionality.
 *
 * @class MainNavbar
 */

(function() {
	KodiCMS.MainNavbar = function() {
		this._scroller = false;
		this._wheight = null;
		this.scroll_pos = 0;
		return this;
	};


	/*
	 * Initialize plugin.
	 */

	KodiCMS.MainNavbar.prototype.init = function() {
		var is_mobile;
		this.$navbar = $('#main-navbar');
		this.$header = this.$navbar.find('.navbar-header');
		this.$toggle = this.$navbar.find('.navbar-toggle:first');
		this.$collapse = $('#main-navbar-collapse');
		this.$collapse_div = this.$collapse.find('> div');
		is_mobile = false;
		$(window).on('pa.screen.small pa.screen.tablet', (function(_this) {
			return function() {
				if (_this.$navbar.css('position') === 'fixed') {
					_this._setupScroller();
				}
				return is_mobile = true;
			};
		})(this)).on('pa.screen.desktop', (function(_this) {
			return function() {
				_this._removeScroller();
				return is_mobile = false;
			};
		})(this));
		return this.$navbar.on('click', '.nav-icon-btn.dropdown > .dropdown-toggle', function(e) {
			if (is_mobile) {
				e.preventDefault();
				e.stopPropagation();
				document.location.href = $(this).attr('href');
				return false;
			}
		});
	};


	/*
	 * Attach scroller to navbar collapse.
	 */

	KodiCMS.MainNavbar.prototype._setupScroller = function() {
		if (this._scroller) {
			return;
		}
		this._scroller = true;
		this.$collapse_div.pixelSlimScroll({});
		this.$navbar.on('shown.bs.collapse.mn_collapse', $.proxy(((function(_this) {
			return function() {
				_this._updateCollapseHeight();
				return _this._watchWindowHeight();
			};
		})(this)), this)).on('hidden.bs.collapse.mn_collapse', $.proxy(((function(_this) {
			return function() {
				_this._wheight = null;
				return _this.$collapse_div.pixelSlimScroll({
					scrollTo: '0px'
				});
			};
		})(this)), this)).on('shown.bs.dropdown.mn_collapse', $.proxy(this._updateCollapseHeight, this)).on('hidden.bs.dropdown.mn_collapse', $.proxy(this._updateCollapseHeight, this));
		return this._updateCollapseHeight();
	};


	/*
	 * Detach scroller from navbar collapse.
	 */

	KodiCMS.MainNavbar.prototype._removeScroller = function() {
		if (!this._scroller) {
			return;
		}
		this._wheight = null;
		this._scroller = false;
		this.$collapse_div.pixelSlimScroll({
			destroy: 'destroy'
		});
		this.$navbar.off('shown.bs.collapse.mn_collapse');
		this.$navbar.off('hidden.bs.collapse.mn_collapse');
		this.$navbar.off('shown.bs.dropdown.mn_collapse');
		this.$navbar.off('hidden.bs.dropdown.mn_collapse');
		return this.$collapse.attr('style', '');
	};


	/*
	 * Update navbar collapse height.
	 */

	KodiCMS.MainNavbar.prototype._updateCollapseHeight = function() {
		var h_height, scrollTop, w_height;
		if (!this._scroller) {
			return;
		}
		w_height = $(window).innerHeight();
		h_height = this.$header.outerHeight();
		scrollTop = this.$collapse_div.scrollTop();
		if ((h_height + this.$collapse_div.css({
			'max-height': 'none'
		}).outerHeight()) > w_height) {
			this.$collapse_div.css({
				'max-height': w_height - h_height
			});
		} else {
			this.$collapse_div.css({
				'max-height': 'none'
			});
		}
		return this.$collapse_div.pixelSlimScroll({
			scrollTo: scrollTop + 'px'
		});
	};


	/*
	 * Detecting a change of the window height.
	 */

	KodiCMS.MainNavbar.prototype._watchWindowHeight = function() {
		var checkWindowInnerHeight;
		this._wheight = $(window).innerHeight();
		checkWindowInnerHeight = (function(_this) {
			return function() {
				if (_this._wheight === null) {
					return;
				}
				if (_this._wheight !== $(window).innerHeight()) {
					_this._updateCollapseHeight();
				}
				_this._wheight = $(window).innerHeight();
				return setTimeout(checkWindowInnerHeight, 100);
			};
		})(this);
		return window.setTimeout(checkWindowInnerHeight, 100);
	};

	KodiCMS.MainNavbar.Constructor = KodiCMS.MainNavbar;

	KodiCMS.addInitializer(function() {
		return KodiCMS.initPlugin('main_navbar', new KodiCMS.MainNavbar);
	});

}).call(this);

/*
 * Class that provides the main menu functionality.
 *
 * @class MainMenu
 */

(function() {
	KodiCMS.MainMenu = function() {
		this._screen = null;
		this._last_screen = null;
		this._animate = false;
		this._close_timer = null;
		this._dropdown_li = null;
		this._dropdown = null;
		
		return this;
	};

	/*
	 * Initialize plugin.
	 */

	KodiCMS.MainMenu.prototype.init = function() {
		var self, state;
		this.$menu = $('#main-menu');
		if (!this.$menu.length) {
			return;
		}
		this.$body = $('body');
		this.menu = this.$menu[0];
		this.$ssw_point = $('#small-screen-width-point');
		this.$tsw_point = $('#tablet-screen-width-point');
		self = this;
		if (KodiCMS.settings.main_menu.store_state) {
			state = this._getMenuState();
			document.body.className += ' disable-mm-animation';
			if (state !== null) {
				this.$body[state === 'collapsed' ? 'addClass' : 'removeClass']('mmc');
			}
			setTimeout((function(_this) {
				return function() {
					return elRemoveClass(document.body, 'disable-mm-animation');
				};
			})(this), 20);
		}
		
		$('.navigation > li:has(ul)', this.$menu).each(function() {
			if($('> ul > li', this).size() < 1)
				$(this).remove();
		});
		this.setupAnimation();
		$(window).on('resize.pa.mm', $.proxy(this.onResize, this));
		this.onResize();
		this.$menu.find('.navigation > .mm-dropdown').addClass('mm-dropdown-root');
		if (KodiCMS.settings.main_menu.detect_active) {
			this.detectActiveItem();
		}
		if ($.support.transition) {
			this.$menu.on('transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd', $.proxy(this._onAnimationEnd, this));
		}
		$('#main-menu-toggle').on('click', $.proxy(this.toggle, this));
		$('#main-menu-inner').slimScroll({
			height: '100%'
		}).on('slimscrolling', (function(_this) {
			return function() {
				return _this.closeCurrentDropdown(true);
			};
		})(this));
		this.$menu.on('click', '.mm-dropdown > a', function() {
			var li;
			li = this.parentNode;
			if (elHasClass(li, 'mm-dropdown-root') && self._collapsed()) {
				if (elHasClass(li, 'mmc-dropdown-open')) {
					if (elHasClass(li, 'freeze')) {
						self.closeCurrentDropdown(true);
					} else {
						self.freezeDropdown(li);
					}
				} else {
					self.openDropdown(li, true);
				}
			} else {
				self.toggleSubmenu(li);
			}
			return false;
		});
		this.$menu.find('.navigation').on('mouseenter.pa.mm-dropdown', '.mm-dropdown-root', function() {
			self.clearCloseTimer();
			if (self._dropdown_li === this) {
				return;
			}
			if (self._collapsed() && (!self._dropdown_li || !elHasClass(self._dropdown_li, 'freeze'))) {
				return self.openDropdown(this);
			}
		}).on('mouseleave.pa.mm-dropdown', '.mm-dropdown-root', function() {
			return self._close_timer = setTimeout(function() {
				return self.closeCurrentDropdown();
			}, KodiCMS.settings.main_menu.dropdown_close_delay);
		});
		return this;
	};

	KodiCMS.MainMenu.prototype._collapsed = function() {
		return (this._screen === 'desktop' && elHasClass(document.body, 'mmc')) || (this._screen !== 'desktop' && !elHasClass(document.body, 'mme'));
	};

	KodiCMS.MainMenu.prototype.onResize = function() {
		this._screen = getScreenSize(this.$ssw_point, this.$tsw_point);
		this._animate = KodiCMS.settings.main_menu.disable_animation_on.indexOf(screen) === -1;
		if (this._dropdown_li) {
			this.closeCurrentDropdown(true);
		}
		if ((this._screen === 'small' && this._last_screen !== this._screen) || (this._screen === 'tablet' && this._last_screen === 'small')) {
			document.body.className += ' disable-mm-animation';
			setTimeout((function(_this) {
				return function() {
					return elRemoveClass(document.body, 'disable-mm-animation');
				};
			})(this), 20);
		}
		return this._last_screen = this._screen;
	};

	KodiCMS.MainMenu.prototype.clearCloseTimer = function() {
		if (this._close_timer) {
			clearTimeout(this._close_timer);
			return this._close_timer = null;
		}
	};

	KodiCMS.MainMenu.prototype._onAnimationEnd = function(e) {
		if (this._screen !== 'desktop' || e.target.id !== 'main-menu') {
			return;
		}
		return $(window).trigger('resize');
	};

	KodiCMS.MainMenu.prototype.toggle = function() {
		var cls, collapse;
		cls = this._screen === 'small' || this._screen === 'tablet' ? 'mme' : 'mmc';
		if (elHasClass(document.body, cls)) {
			elRemoveClass(document.body, cls);
		} else {
			document.body.className += ' ' + cls;
		}
		if (cls === 'mmc') {
			if (KodiCMS.settings.main_menu.store_state) {
				this._storeMenuState(elHasClass(document.body, 'mmc'));
			}
			if (!$.support.transition) {
				return $(window).trigger('resize');
			}
		} else {
			collapse = document.getElementById('');
			$('#main-navbar-collapse').stop().removeClass('in collapsing').addClass('collapse')[0].style.height = '0px';
			return $('#main-navbar .navbar-toggle').addClass('collapsed');
		}
	};

	KodiCMS.MainMenu.prototype.toggleSubmenu = function(li) {
		this[elHasClass(li, 'open') ? 'collapseSubmenu' : 'expandSubmenu'](li);
		return false;
	};

	KodiCMS.MainMenu.prototype.collapseSubmenu = function(li) {
		var $li, $ul;
		$li = $(li);
		$ul = $li.find('> ul');
		if (this._animate) {
			$ul.animate({
				height: 0
			}, KodiCMS.settings.main_menu.animation_speed, (function(_this) {
				return function() {
					elRemoveClass(li, 'open');
					$ul.attr('style', '');
					return $li.find('.mm-dropdown.open').removeClass('open').find('> ul').attr('style', '');
				};
			})(this));
		} else {
			elRemoveClass(li, 'open');
		}
		return false;
	};

	KodiCMS.MainMenu.prototype.expandSubmenu = function(li) {
		var $li, $ul, h, ul;
		$li = $(li);
		if (KodiCMS.settings.main_menu.accordion) {
			this.collapseAllSubmenus(li);
		}
		if (this._animate) {
			$ul = $li.find('> ul');
			ul = $ul[0];
			ul.className += ' get-height';
			h = $ul.height();
			elRemoveClass(ul, 'get-height');
			ul.style.display = 'block';
			ul.style.height = '0px';
			li.className += ' open';
			return $ul.animate({
				height: h
			}, KodiCMS.settings.main_menu.animation_speed, (function(_this) {
				return function() {
					return $ul.attr('style', '');
				};
			})(this));
		} else {
			return li.className += ' open';
		}
	};

	KodiCMS.MainMenu.prototype.collapseAllSubmenus = function(li) {
		var self;
		self = this;
		return $(li).parent().find('> .mm-dropdown.open').each(function() {
			return self.collapseSubmenu(this);
		});
	};

	KodiCMS.MainMenu.prototype.openDropdown = function(li, freeze) {
		var $li, $title, $ul, $wrapper, max_height, min_height, title_h, top, ul, w_height, wrapper;
		if (freeze == null) {
			freeze = false;
		}
		if (this._dropdown_li) {
			this.closeCurrentDropdown(freeze);
		}
		$li = $(li);
		$ul = $li.find('> ul');
		ul = $ul[0];
		this._dropdown_li = li;
		this._dropdown = ul;
		$title = $ul.find('> .mmc-title');
		if (!$title.length) {
			$title = $('<div class="mmc-title"></div>').text($li.find('> a > .mm-text').text());
			ul.insertBefore($title[0], ul.firstChild);
		}
		li.className += ' mmc-dropdown-open';
		ul.className += ' mmc-dropdown-open-ul';
		top = $li.position().top;
		if (elHasClass(document.body, 'main-menu-fixed')) {
			$wrapper = $ul.find('.mmc-wrapper');
			if (!$wrapper.length) {
				wrapper = document.createElement('div');
				wrapper.className = 'mmc-wrapper';
				wrapper.style.overflow = 'hidden';
				wrapper.style.position = 'relative';
				$wrapper = $(wrapper);
				$wrapper.append($ul.find('> li'));
				ul.appendChild(wrapper);
			}
			w_height = $(window).innerHeight();
			title_h = $title.outerHeight();
			min_height = title_h + $ul.find('.mmc-wrapper > li').first().outerHeight() * 3;
			if ((top + min_height) > w_height) {
				max_height = top - $('#main-navbar').outerHeight();
				ul.className += ' top';
				ul.style.bottom = (w_height - top - title_h) + 'px';
			} else {
				max_height = w_height - top - title_h;
				ul.style.top = top + 'px';
			}
			if (elHasClass(ul, 'top')) {
				ul.appendChild($title[0]);
			} else {
				ul.insertBefore($title[0], ul.firstChild);
			}
			li.className += ' slimscroll-attached';
			$wrapper[0].style.maxHeight = (max_height - 10) + 'px';
			$wrapper.pixelSlimScroll({});
		} else {
			ul.style.top = top + 'px';
		}
		if (freeze) {
			this.freezeDropdown(li);
		}
		if (!freeze) {
			$ul.on('mouseenter', (function(_this) {
				return function() {
					return _this.clearCloseTimer();
				};
			})(this)).on('mouseleave', (function(_this) {
				return function() {
					return _this._close_timer = setTimeout(function() {
						return _this.closeCurrentDropdown();
					}, KodiCMS.settings.main_menu.dropdown_close_delay);
				};
			})(this));
			this;
		}
		return this.menu.appendChild(ul);
	};

	KodiCMS.MainMenu.prototype.closeCurrentDropdown = function(force) {
		var $dropdown, $wrapper;
		if (force == null) {
			force = false;
		}
		if (!this._dropdown_li || (elHasClass(this._dropdown_li, 'freeze') && !force)) {
			return;
		}
		this.clearCloseTimer();
		$dropdown = $(this._dropdown);
		if (elHasClass(this._dropdown_li, 'slimscroll-attached')) {
			elRemoveClass(this._dropdown_li, 'slimscroll-attached');
			$wrapper = $dropdown.find('.mmc-wrapper');
			$wrapper.pixelSlimScroll({
				destroy: 'destroy'
			}).find('> *').appendTo($dropdown);
			$wrapper.remove();
		}
		this._dropdown_li.appendChild(this._dropdown);
		elRemoveClass(this._dropdown, 'mmc-dropdown-open-ul');
		elRemoveClass(this._dropdown, 'top');
		elRemoveClass(this._dropdown_li, 'mmc-dropdown-open');
		elRemoveClass(this._dropdown_li, 'freeze');
		$(this._dropdown_li).attr('style', '');
		$dropdown.attr('style', '').off('mouseenter').off('mouseleave');
		this._dropdown = null;
		return this._dropdown_li = null;
	};

	KodiCMS.MainMenu.prototype.freezeDropdown = function(li) {
		return li.className += ' freeze';
	};

	KodiCMS.MainMenu.prototype.setupAnimation = function() {
		var $mm, $mm_nav, d_body, dsbl_animation_on;
		d_body = document.body;
		dsbl_animation_on = KodiCMS.settings.main_menu.disable_animation_on;
		d_body.className += ' dont-animate-mm-content';
		$mm = $('#main-menu');
		$mm_nav = $mm.find('.navigation');
		$mm_nav.find('> .mm-dropdown > ul').addClass('mmc-dropdown-delay animated');
		$mm_nav.find('> li > a > .mm-text').addClass('mmc-dropdown-delay animated fadeIn');
		$mm.find('.menu-content').addClass('animated fadeIn');
		if (elHasClass(d_body, 'main-menu-right') || (elHasClass(d_body, 'right-to-left') && !elHasClass(d_body, 'main-menu-right'))) {
			$mm_nav.find('> .mm-dropdown > ul').addClass('fadeInRight');
		} else {
			$mm_nav.find('> .mm-dropdown > ul').addClass('fadeInLeft');
		}
		d_body.className += dsbl_animation_on.indexOf('small') === -1 ? ' animate-mm-sm' : ' dont-animate-mm-content-sm';
		d_body.className += dsbl_animation_on.indexOf('tablet') === -1 ? ' animate-mm-md' : ' dont-animate-mm-content-md';
		d_body.className += dsbl_animation_on.indexOf('desktop') === -1 ? ' animate-mm-lg' : ' dont-animate-mm-content-lg';
		return window.setTimeout(function() {
			return elRemoveClass(d_body, 'dont-animate-mm-content');
		}, 500);
	};

	KodiCMS.MainMenu.prototype.detectActiveItem = function() {
		var a, bubble, links, nav, predicate, url, _i, _len, _results;
		url = (document.location + '').replace(/\#.*?$/, '');
		predicate = KodiCMS.settings.main_menu.detect_active_predicate;
		nav = $('#main-menu .navigation');
		nav.find('li').removeClass('open active');
		links = nav[0].getElementsByTagName('a');
		bubble = (function(_this) {
			return function(li) {
				li.className += ' active';
				if (!elHasClass(li.parentNode, 'navigation')) {
					li = li.parentNode.parentNode;
					li.className += ' open';
					return bubble(li);
				}
			};
		})(this);
		_results = [];
		for (_i = 0, _len = links.length; _i < _len; _i++) {
			a = links[_i];
			if (a.href.indexOf('#') === -1 && predicate(a.href, url)) {
				bubble(a.parentNode);
				break;
			} else {
				_results.push(void 0);
			}
		}
		return _results;
	};


	/*
	 * Load menu state.
	 */
	KodiCMS.MainMenu.prototype._getMenuState = function() {
		return KodiCMS.getStoredValue(KodiCMS.settings.main_menu.store_state_key);
	};


	/*
	 * Store menu state.
	 */
	KodiCMS.MainMenu.prototype._storeMenuState = function(is_collapsed) {
		if (!KodiCMS.settings.main_menu.store_state) {
			return;
		}
		return KodiCMS.storeValue(KodiCMS.settings.main_menu.store_state_key, is_collapsed ? 'collapsed' : 'expanded');
	};

	KodiCMS.MainMenu.Constructor = KodiCMS.MainMenu;

	KodiCMS.addInitializer(function() {
		return KodiCMS.initPlugin('main_menu', new KodiCMS.MainMenu, {
			main_menu: {
				accordion: true,
				animation_speed: 250,
				store_state: true,
				store_state_key: 'mmstate',
				disable_animation_on: ['small'],
				dropdown_close_delay: 300,
				detect_active: true,
				detect_active_predicate: function(href, url) {
					if(href == url)
						return true;
					else if(BASE_URL == href && href == url)
						return true;
					else if(BASE_URL != href && url.indexOf(href) != -1)
						return true;
		//			else if(url.indexOf(href) != -1)
		//				return true;

					return false;
				}
			}
		});
	});

}).call(this);

(function($) {

	jQuery.fn.extend({
		pixelSlimScroll: function(options) {

			var defaults = {
				// width in pixels of the visible scroll area
				width: 'auto',
				// width in pixels of the scrollbar and rail
				size: '2px',
				// scrollbar color, accepts any hex/color value
				color: '#000',
				// distance in pixels between the side edge and the scrollbar
				distance: '1px',
				// default scroll position on load - top / bottom / $('selector')
				start: 'top',
				// sets scrollbar opacity
				opacity: .4,
				// sets rail color
				railColor: '#333',
				// sets rail opacity
				railOpacity: .2,
				// defautlt CSS class of the slimscroll rail
				railClass: 'slimScrollRail',
				// defautlt CSS class of the slimscroll bar
				barClass: 'slimScrollBar',
				// defautlt CSS class of the slimscroll wrapper
				wrapperClass: 'slimScrollDiv',
				// check if mousewheel should scroll the window if we reach top/bottom
				allowPageScroll: false,
				// scroll amount applied to each mouse wheel step
				wheelStep: 20,
				// scroll amount applied when user is using gestures
				touchScrollStep: 200,
				// sets border radius
				borderRadius: '0px',
				// sets border radius of the rail
				railBorderRadius: '0px'
			};

			var o = $.extend(defaults, options);

			// do it for every element that matches selector
			this.each(function() {

				var isOverPanel, isOverBar, isDragg, queueHide, touchDif,
						barHeight, percentScroll, lastScroll,
						divS = '<div></div>',
						minBarHeight = 30,
						releaseScroll = false;

				// used in event handlers and for better minification
				var me = $(this);

				// ensure we are not binding it again
				if (me.parent().hasClass(o.wrapperClass))
				{
					// start from last bar position
					var offset = me.scrollTop();

					// find bar and rail
					bar = me.parent().find('.' + o.barClass);
					rail = me.parent().find('.' + o.railClass);

					getBarHeight();

					// check if we should scroll existing instance
					if ($.isPlainObject(options))
					{
						if ('scrollTo' in options)
						{
							// jump to a static point
							offset = parseInt(o.scrollTo);
						}
						else if ('scrollBy' in options)
						{
							// jump by value pixels
							offset += parseInt(o.scrollBy);
						}
						else if ('destroy' in options)
						{
							// remove slimscroll elements
							bar.remove();
							rail.remove();
							me.unwrap();
							return;
						}

						// scroll content by the given offset
						scrollContent(offset, false, true);
					}

					return;
				}

				// wrap content
				var wrapper = $(divS)
						.addClass(o.wrapperClass)
						.css({
							position: 'relative',
							overflow: 'hidden',
							width: o.width
						});

				// update style for the div
				me.css({
					overflow: 'hidden',
					width: o.width
				});

				// create scrollbar rail
				var rail = $(divS)
						.addClass(o.railClass)
						.css({
							width: o.size,
							height: '100%',
							position: 'absolute',
							top: 0,
							display: 'none',
							'border-radius': o.railBorderRadius,
							background: o.railColor,
							opacity: o.railOpacity,
							zIndex: 90
						});

				// create scrollbar
				var bar = $(divS)
						.addClass(o.barClass)
						.css({
							background: o.color,
							width: o.size,
							position: 'absolute',
							top: 0,
							opacity: o.opacity,
							display: 'block',
							'border-radius': o.borderRadius,
							BorderRadius: o.borderRadius,
							MozBorderRadius: o.borderRadius,
							WebkitBorderRadius: o.borderRadius,
							zIndex: 99
						});

				// set position
				rail.css({right: o.distance});
				bar.css({right: o.distance});

				// wrap it
				me.wrap(wrapper);

				// append to parent div
				me.parent().append(bar);
				me.parent().append(rail);

				// make it draggable and no longer dependent on the jqueryUI
				bar.bind("mousedown", function(e) {
					var $doc = $(document);
					isDragg = true;
					t = parseFloat(bar.css('top'));
					pageY = e.pageY;

					$doc.bind("mousemove.slimscroll", function(e) {
						currTop = t + e.pageY - pageY;
						bar.css('top', currTop);
						scrollContent(0, bar.position().top, false);// scroll content
					});

					$doc.bind("mouseup.slimscroll", function(e) {
						isDragg = false;
						hideBar();
						$doc.unbind('.slimscroll');
					});
					return false;
				}).bind("selectstart.slimscroll", function(e) {
					e.stopPropagation();
					e.preventDefault();
					return false;
				});

				// on rail over
				rail.hover(function() {
					showBar();
				}, function() {
					hideBar();
				});

				// on bar over
				bar.hover(function() {
					isOverBar = true;
				}, function() {
					isOverBar = false;
				});

				// show on parent mouseover
				me.hover(function() {
					isOverPanel = true;
					showBar();
					hideBar();
				}, function() {
					isOverPanel = false;
					hideBar();
				});

				// support for mobile
				me.bind('touchstart', function(e, b) {
					if (e.originalEvent.touches.length)
					{
						// record where touch started
						touchDif = e.originalEvent.touches[0].pageY;
					}
				});

				me.bind('touchmove', function(e) {
					// prevent scrolling the page if necessary
					if (!releaseScroll)
					{
						e.originalEvent.preventDefault();
					}
					if (e.originalEvent.touches.length)
					{
						// see how far user swiped
						var diff = (touchDif - e.originalEvent.touches[0].pageY) / o.touchScrollStep;
						// scroll content
						scrollContent(diff, true);
						touchDif = e.originalEvent.touches[0].pageY;
					}
				});

				// set up initial height
				getBarHeight();

				// attach scroll events
				attachWheel();

				function _onWheel(e)
				{
					// use mouse wheel only when mouse is over
					if (!isOverPanel) {
						return;
					}

					var e = e || window.event;

					var delta = 0;
					if (e.wheelDelta) {
						delta = -e.wheelDelta / 120;
					}
					if (e.detail) {
						delta = e.detail / 3;
					}

					var target = e.target || e.srcTarget || e.srcElement;
					if ($(target).closest('.' + o.wrapperClass).is(me.parent())) {
						// scroll content
						scrollContent(delta, true);
					}

					// stop window scroll
					if (e.preventDefault && !releaseScroll) {
						e.preventDefault();
					}
					if (!releaseScroll) {
						e.returnValue = false;
					}
				}

				function scrollContent(y, isWheel, isJump)
				{
					releaseScroll = false;
					var delta = y;
					var maxTop = me.outerHeight() - bar.outerHeight();

					if (isWheel)
					{
						// move bar with mouse wheel
						delta = parseInt(bar.css('top')) + y * parseInt(o.wheelStep) / 100 * bar.outerHeight();

						// move bar, make sure it doesn't go out
						delta = Math.min(Math.max(delta, 0), maxTop);

						// if scrolling down, make sure a fractional change to the
						// scroll position isn't rounded away when the scrollbar's CSS is set
						// this flooring of delta would happened automatically when
						// bar.css is set below, but we floor here for clarity
						delta = (y > 0) ? Math.ceil(delta) : Math.floor(delta);

						// scroll the scrollbar
						bar.css({top: delta + 'px'});
					}

					// calculate actual scroll amount
					percentScroll = parseInt(bar.css('top')) / (me.outerHeight() - bar.outerHeight());
					delta = percentScroll * (me[0].scrollHeight - me.outerHeight());

					if (isJump)
					{
						delta = y;
						var offsetTop = delta / me[0].scrollHeight * me.outerHeight();
						offsetTop = Math.min(Math.max(offsetTop, 0), maxTop);
						bar.css({top: offsetTop + 'px'});
					}

					// scroll content
					me.scrollTop(delta);

					// fire scrolling event
					me.trigger('slimscrolling', ~~delta);

					// ensure bar is visible
					showBar();

					// trigger hide when scroll is stopped
					hideBar();
				}

				function attachWheel()
				{
					if (window.addEventListener)
					{
						this.addEventListener('DOMMouseScroll', _onWheel, false);
						this.addEventListener('mousewheel', _onWheel, false);
					}
					else
					{
						document.attachEvent("onmousewheel", _onWheel)
					}
				}

				function getBarHeight()
				{
					// calculate scrollbar height and make sure it is not too small
					barHeight = Math.max((me.outerHeight() / me[0].scrollHeight) * me.outerHeight(), minBarHeight);
					bar.css({height: barHeight + 'px'});

					// hide scrollbar if content is not long enough
					var display = barHeight == me.outerHeight() ? 'none' : 'block';
					bar.css({display: display});
				}

				function showBar()
				{
					// recalculate bar height
					getBarHeight();
					clearTimeout(queueHide);

					// when bar reached top or bottom
					if (percentScroll == ~~percentScroll)
					{
						//release wheel
						releaseScroll = o.allowPageScroll;

						// publish approporiate event
						if (lastScroll != percentScroll)
						{
							var msg = (~~percentScroll == 0) ? 'top' : 'bottom';
							me.trigger('slimscroll', msg);
						}
					}
					else
					{
						releaseScroll = false;
					}
					lastScroll = percentScroll;

					// show only when required
					if (barHeight >= me.outerHeight()) {
						//allow window scroll
						releaseScroll = true;
						return;
					}
					bar.stop(true, true).fadeIn('fast');
				}

				function hideBar() {
				}

			});

			// maintain chainability
			return this;
		}
	});

	jQuery.fn.extend({
		pixelslimscroll: jQuery.fn.pixelSlimScroll
	});

})(jQuery);

/* =========================================================
 * bootstrap-tabdrop.js 
 * http://www.eyecon.ro/bootstrap-tabdrop
 * =========================================================
 * Copyright 2012 Stefan Petre
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================= */

!function($) {

	var WinReszier = (function() {
		var registered = [];
		var inited = false;
		var timer;
		var resize = function(ev) {
			clearTimeout(timer);
			timer = setTimeout(notify, 100);
		};
		var notify = function() {
			for (var i = 0, cnt = registered.length; i < cnt; i++) {
				registered[i].apply();
			}
		};
		return {
			register: function(fn) {
				registered.push(fn);
				if (inited === false) {
					$(window).bind('resize', resize);
					inited = true;
				}
			},
			unregister: function(fn) {
				for (var i = 0, cnt = registered.length; i < cnt; i++) {
					if (registered[i] == fn) {
						delete registered[i];
						break;
					}
				}
			}
		}
	}());

	var TabDrop = function(element, options) {
		this.element = $(element);
		this.dropdown = $('<li class="dropdown hide pull-right tabdrop"><a class="dropdown-toggle" data-toggle="dropdown" href="#">' + options.text + ' <b class="caret"></b></a><ul class="dropdown-menu"></ul></li>')
				.prependTo(this.element);
		if (this.element.parent().is('.tabs-below')) {
			this.dropdown.addClass('dropup');
		}
		WinReszier.register($.proxy(this.layout, this));
		this.layout();
	};

	TabDrop.prototype = {
		constructor: TabDrop,
		layout: function() {
			var collection = [];
			this.dropdown.removeClass('hide');
			this.element
					.append(this.dropdown.find('li'))
					.find('>li')
					.not('.tabdrop')
					.each(function() {
						if (this.offsetTop > 0) {
							collection.push(this);
						}
					});
			if (collection.length > 0) {
				collection = $(collection);
				this.dropdown
						.find('ul')
						.empty()
						.append(collection);
				if (this.dropdown.find('.active').length == 1) {
					this.dropdown.addClass('active');
				} else {
					this.dropdown.removeClass('active');
				}
			} else {
				this.dropdown.addClass('hide');
			}
		}
	}

	$.fn.tabdrop = function(option) {
		return this.each(function() {
			var $this = $(this),
					data = $this.data('tabdrop'),
					options = typeof option === 'object' && option;
			if (!data) {
				$this.data('tabdrop', (data = new TabDrop(this, $.extend({}, $.fn.tabdrop.defaults, options))));
			}
			if (typeof option == 'string') {
				data[option]();
			}
		})
	};

	$.fn.tabdrop.defaults = {
		text: '<i class="icon-align-justify"></i>'
	};

	$.fn.tabdrop.Constructor = TabDrop;

}(window.jQuery);


/*! Copyright (c) 2011 Piotr Rochala (http://rocha.la)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * Version: 1.3.2
 *
 */
(function($) {

	jQuery.fn.extend({
		slimScroll: function(options) {

			var defaults = {
				// width in pixels of the visible scroll area
				width: 'auto',
				// height in pixels of the visible scroll area
				height: '250px',
				// width in pixels of the scrollbar and rail
				size: '7px',
				// scrollbar color, accepts any hex/color value
				color: '#000',
				// scrollbar position - left/right
				position: 'right',
				// distance in pixels between the side edge and the scrollbar
				distance: '1px',
				// default scroll position on load - top / bottom / $('selector')
				start: 'top',
				// sets scrollbar opacity
				opacity: .4,
				// enables always-on mode for the scrollbar
				alwaysVisible: false,
				// check if we should hide the scrollbar when user is hovering over
				disableFadeOut: false,
				// sets visibility of the rail
				railVisible: false,
				// sets rail color
				railColor: '#333',
				// sets rail opacity
				railOpacity: .2,
				// whether  we should use jQuery UI Draggable to enable bar dragging
				railDraggable: true,
				// defautlt CSS class of the slimscroll rail
				railClass: 'slimScrollRail',
				// defautlt CSS class of the slimscroll bar
				barClass: 'slimScrollBar',
				// defautlt CSS class of the slimscroll wrapper
				wrapperClass: 'slimScrollDiv',
				// check if mousewheel should scroll the window if we reach top/bottom
				allowPageScroll: false,
				// scroll amount applied to each mouse wheel step
				wheelStep: 20,
				// scroll amount applied when user is using gestures
				touchScrollStep: 200,
				// sets border radius
				borderRadius: '7px',
				// sets border radius of the rail
				railBorderRadius: '7px'
			};

			var o = $.extend(defaults, options);

			// do it for every element that matches selector
			this.each(function() {

				var isOverPanel, isOverBar, isDragg, queueHide, touchDif,
						barHeight, percentScroll, lastScroll,
						divS = '<div></div>',
						minBarHeight = 30,
						releaseScroll = false;

				// used in event handlers and for better minification
				var me = $(this);

				// ensure we are not binding it again
				if (me.parent().hasClass(o.wrapperClass))
				{
					// start from last bar position
					var offset = me.scrollTop();

					// find bar and rail
					bar = me.parent().find('.' + o.barClass);
					rail = me.parent().find('.' + o.railClass);

					getBarHeight();

					// check if we should scroll existing instance
					if ($.isPlainObject(options))
					{
						// Pass height: auto to an existing slimscroll object to force a resize after contents have changed
						if ('height' in options && options.height == 'auto') {
							me.parent().css('height', 'auto');
							me.css('height', 'auto');
							var height = me.parent().parent().height();
							me.parent().css('height', height);
							me.css('height', height);
						}

						if ('scrollTo' in options)
						{
							// jump to a static point
							offset = parseInt(o.scrollTo);
						}
						else if ('scrollBy' in options)
						{
							// jump by value pixels
							offset += parseInt(o.scrollBy);
						}
						else if ('destroy' in options)
						{
							// remove slimscroll elements
							bar.remove();
							rail.remove();
							me.unwrap();
							return;
						}

						// scroll content by the given offset
						scrollContent(offset, false, true);
					}

					return;
				}

				// optionally set height to the parent's height
				o.height = (options.height == 'auto') ? me.parent().height() : options.height;

				// wrap content
				var wrapper = $(divS)
						.addClass(o.wrapperClass)
						.css({
							position: 'relative',
							overflow: 'hidden',
							width: o.width,
							height: o.height
						});

				// update style for the div
				me.css({
					overflow: 'hidden',
					width: o.width,
					height: o.height
				});

				// create scrollbar rail
				var rail = $(divS)
						.addClass(o.railClass)
						.css({
							width: o.size,
							height: '100%',
							position: 'absolute',
							top: 0,
							display: (o.alwaysVisible && o.railVisible) ? 'block' : 'none',
							'border-radius': o.railBorderRadius,
							background: o.railColor,
							opacity: o.railOpacity,
							zIndex: 90
						});

				// create scrollbar
				var bar = $(divS)
						.addClass(o.barClass)
						.css({
							background: o.color,
							width: o.size,
							position: 'absolute',
							top: 0,
							opacity: o.opacity,
							display: o.alwaysVisible ? 'block' : 'none',
							'border-radius': o.borderRadius,
							BorderRadius: o.borderRadius,
							MozBorderRadius: o.borderRadius,
							WebkitBorderRadius: o.borderRadius,
							zIndex: 99
						});

				// set position
				var posCss = (o.position == 'right') ? {right: o.distance} : {left: o.distance};
				rail.css(posCss);
				bar.css(posCss);

				// wrap it
				me.wrap(wrapper);

				// append to parent div
				me.parent().append(bar);
				me.parent().append(rail);

				// make it draggable and no longer dependent on the jqueryUI
				if (o.railDraggable) {
					bar.bind("mousedown", function(e) {
						var $doc = $(document);
						isDragg = true;
						t = parseFloat(bar.css('top'));
						pageY = e.pageY;

						$doc.bind("mousemove.slimscroll", function(e) {
							currTop = t + e.pageY - pageY;
							bar.css('top', currTop);
							scrollContent(0, bar.position().top, false);// scroll content
						});

						$doc.bind("mouseup.slimscroll", function(e) {
							isDragg = false;
							hideBar();
							$doc.unbind('.slimscroll');
						});
						return false;
					}).bind("selectstart.slimscroll", function(e) {
						e.stopPropagation();
						e.preventDefault();
						return false;
					});
				}

				// on rail over
				rail.hover(function() {
					showBar();
				}, function() {
					hideBar();
				});

				// on bar over
				bar.hover(function() {
					isOverBar = true;
				}, function() {
					isOverBar = false;
				});

				// show on parent mouseover
				me.hover(function() {
					isOverPanel = true;
					showBar();
					hideBar();
				}, function() {
					isOverPanel = false;
					hideBar();
				});

				// support for mobile
				me.bind('touchstart', function(e, b) {
					if (e.originalEvent.touches.length)
					{
						// record where touch started
						touchDif = e.originalEvent.touches[0].pageY;
					}
				});

				me.bind('touchmove', function(e) {
					// prevent scrolling the page if necessary
					if (!releaseScroll)
					{
						e.originalEvent.preventDefault();
					}
					if (e.originalEvent.touches.length)
					{
						// see how far user swiped
						var diff = (touchDif - e.originalEvent.touches[0].pageY) / o.touchScrollStep;
						// scroll content
						scrollContent(diff, true);
						touchDif = e.originalEvent.touches[0].pageY;
					}
				});

				// set up initial height
				getBarHeight();

				// check start position
				if (o.start === 'bottom')
				{
					// scroll content to bottom
					bar.css({top: me.outerHeight() - bar.outerHeight()});
					scrollContent(0, true);
				}
				else if (o.start !== 'top')
				{
					// assume jQuery selector
					scrollContent($(o.start).position().top, null, true);

					// make sure bar stays hidden
					if (!o.alwaysVisible) {
						bar.hide();
					}
				}

				// attach scroll events
				attachWheel();

				function _onWheel(e)
				{
					// use mouse wheel only when mouse is over
					if (!isOverPanel) {
						return;
					}

					var e = e || window.event;

					var delta = 0;
					if (e.wheelDelta) {
						delta = -e.wheelDelta / 120;
					}
					if (e.detail) {
						delta = e.detail / 3;
					}

					var target = e.target || e.srcTarget || e.srcElement;
					if ($(target).closest('.' + o.wrapperClass).is(me.parent())) {
						// scroll content
						scrollContent(delta, true);
					}

					// stop window scroll
					if (e.preventDefault && !releaseScroll) {
						e.preventDefault();
					}
					if (!releaseScroll) {
						e.returnValue = false;
					}
				}

				function scrollContent(y, isWheel, isJump)
				{
					releaseScroll = false;
					var delta = y;
					var maxTop = me.outerHeight() - bar.outerHeight();

					if (isWheel)
					{
						// move bar with mouse wheel
						delta = parseInt(bar.css('top')) + y * parseInt(o.wheelStep) / 100 * bar.outerHeight();

						// move bar, make sure it doesn't go out
						delta = Math.min(Math.max(delta, 0), maxTop);

						// if scrolling down, make sure a fractional change to the
						// scroll position isn't rounded away when the scrollbar's CSS is set
						// this flooring of delta would happened automatically when
						// bar.css is set below, but we floor here for clarity
						delta = (y > 0) ? Math.ceil(delta) : Math.floor(delta);

						// scroll the scrollbar
						bar.css({top: delta + 'px'});
					}

					// calculate actual scroll amount
					percentScroll = parseInt(bar.css('top')) / (me.outerHeight() - bar.outerHeight());
					delta = percentScroll * (me[0].scrollHeight - me.outerHeight());

					if (isJump)
					{
						delta = y;
						var offsetTop = delta / me[0].scrollHeight * me.outerHeight();
						offsetTop = Math.min(Math.max(offsetTop, 0), maxTop);
						bar.css({top: offsetTop + 'px'});
					}

					// scroll content
					me.scrollTop(delta);

					// fire scrolling event
					me.trigger('slimscrolling', ~~delta);

					// ensure bar is visible
					showBar();

					// trigger hide when scroll is stopped
					hideBar();
				}

				function attachWheel()
				{
					if (window.addEventListener)
					{
						this.addEventListener('DOMMouseScroll', _onWheel, false);
						this.addEventListener('mousewheel', _onWheel, false);
					}
					else
					{
						document.attachEvent("onmousewheel", _onWheel)
					}
				}

				function getBarHeight()
				{
					// calculate scrollbar height and make sure it is not too small
					barHeight = Math.max((me.outerHeight() / me[0].scrollHeight) * me.outerHeight(), minBarHeight);
					bar.css({height: barHeight + 'px'});

					// hide scrollbar if content is not long enough
					var display = barHeight == me.outerHeight() ? 'none' : 'block';
					bar.css({display: display});
				}

				function showBar()
				{
					// recalculate bar height
					getBarHeight();
					clearTimeout(queueHide);

					// when bar reached top or bottom
					if (percentScroll == ~~percentScroll)
					{
						//release wheel
						releaseScroll = o.allowPageScroll;

						// publish approporiate event
						if (lastScroll != percentScroll)
						{
							var msg = (~~percentScroll == 0) ? 'top' : 'bottom';
							me.trigger('slimscroll', msg);
						}
					}
					else
					{
						releaseScroll = false;
					}
					lastScroll = percentScroll;

					// show only when required
					if (barHeight >= me.outerHeight()) {
						//allow window scroll
						releaseScroll = true;
						return;
					}
					bar.stop(true, true).fadeIn('fast');
					if (o.railVisible) {
						rail.stop(true, true).fadeIn('fast');
					}
				}

				function hideBar()
				{
					// only hide when options allow it
					if (!o.alwaysVisible)
					{
						queueHide = setTimeout(function() {
							if (!(o.disableFadeOut && isOverPanel) && !isOverBar && !isDragg)
							{
								bar.fadeOut('slow');
								rail.fadeOut('slow');
							}
						}, 1000);
					}
				}

			});

			// maintain chainability
			return this;
		}
	});

	jQuery.fn.extend({
		slimscroll: jQuery.fn.slimScroll
	});

})(jQuery);

(function() {
	$.fn.serializeObject = function() {
		var e = {};
		var t = this.serializeArray();
		$.each(t, function() {
			if (e[this.name] !== undefined) {
				if (!e[this.name].push) {
					e[this.name] = [e[this.name]]
				}
				e[this.name].push(this.value || "")
			} else {
				e[this.name] = this.value || ""
			}
		});
		return e
	};
}).call(this);

(function() {
	$.fn.scrollTo = function(e, t, n) {
		if (typeof t == "function" && arguments.length == 2) {
			n = t;
			t = e
		}
		var r = $.extend({scrollTarget: e, offsetTop: 50, duration: 500, easing: "swing"}, t);
		return this.each(function() {
			var e = $(this);
			var t = typeof r.scrollTarget == "number" ? r.scrollTarget : $(r.scrollTarget);
			var i = typeof t == "number" ? t : t.offset().top + e.scrollTop() - parseInt(r.offsetTop);
			e.animate({scrollTop: i}, parseInt(r.duration), r.easing, function() {
				if (typeof n == "function") {
					n.call(this)
				}
			})
		})
	};
}).call(this);


(function() {
	var tabdrop;

	if (!$.fn.tabdrop) {
		throw new Error('bootstrap-tabdrop.js required');
	}

	tabdrop = $.fn.tabdrop;

	$.fn.tabdrop = function(options) {
		options = $.extend({}, $.fn.tabdrop.defaults, options);
		return this.each(function() {
			var $this, data;
			$this = $(this);
			tabdrop.call($this, options);
			data = $this.data('tabdrop');
			if (data) {
				data.dropdown.on("click", "li", function() {
					$(this).parent().parent().find("a.dropdown-toggle").empty().html('<span class="display-tab"> ' + $(this).text() + ' </span><b class="caret"></b>');
					return data.layout();
				});
				return data.element.on('click', '> li', function() {
					if ($(this).hasClass('tabdrop')) {
						return;
					}
					data.element.find("> .tabdrop > a.dropdown-toggle").empty().html(options.text + ' <b class="caret"></b>');
					return data.layout();
				});
			}
		});
	};

	$.fn.tabdrop.defaults = {
		text: '<i class="fa fa-bars"></i>'
	};
}).call(this);


(function() {
	$.fn.check=function(){return this.each(function(){this.checked=true})}
	$.fn.uncheck=function(){return this.each(function(){this.checked=false})};
	$.fn.checked=function(){return this.prop("checked")}

	$.fn.tabs = function () {
		return $('li a', this).on('click', function() {
			$(this)
				.parent()
				.addClass('active')
				.siblings()
				.removeClass('active');

			$('div.tab-pane').removeClass('active');
			$($(this).attr('href')).addClass('active');

			return false;
		});
	};
	
	jQuery.browser = {};
	jQuery.browser.mozilla = /mozilla/.test(navigator.userAgent.toLowerCase()) && !/webkit/.test(navigator.userAgent.toLowerCase());
	jQuery.browser.webkit = /webkit/.test(navigator.userAgent.toLowerCase());
	jQuery.browser.opera = /opera/.test(navigator.userAgent.toLowerCase());
	jQuery.browser.msie = /msie/.test(navigator.userAgent.toLowerCase());


	(function(e) {
		function t(t) {
			if (typeof t.data === "string") {
				t.data = {keys: t.data}
			}
			if (!t.data || !t.data.keys || typeof t.data.keys !== "string") {
				return
			}
			var n = t.handler, r = t.data.keys.toLowerCase().split(" "), i = ["text", "password", "number", "email", "url", "range", "date", "month", "week", "time", "datetime", "datetime-local", "search", "color", "tel"];
			t.handler = function(t) {
				if (this !== t.target && (/textarea|select/i.test(t.target.nodeName) || e.inArray(t.target.type, i) > -1)) {
					return
				}
				var s = e.hotkeys.specialKeys[t.keyCode], o = String.fromCharCode(t.which).toLowerCase(), u = "", a = {};
				e.each(["alt", "ctrl", "meta", "shift"], function(e, n) {
					if (t[n + "Key"] && s !== n) {
						u += n + "+"
					}
				});
				u = u.replace("alt+ctrl+meta+shift", "hyper");
				if (s) {
					a[u + s] = true
				}
				if (o) {
					a[u + o] = true;
					a[u + e.hotkeys.shiftNums[o]] = true;
					if (u === "shift+") {
						a[e.hotkeys.shiftNums[o]] = true
					}
				}
				for (var f = 0, l = r.length; f < l; f++) {
					if (a[r[f]]) {
						return n.apply(this, arguments)
					}
				}
			}
		}
		e.hotkeys = {version: "0.8", specialKeys: {8: "backspace", 9: "tab", 10: "return", 13: "return", 16: "shift", 17: "ctrl", 18: "alt", 19: "pause", 20: "capslock", 27: "esc", 32: "space", 33: "pageup", 34: "pagedown", 35: "end", 36: "home", 37: "left", 38: "up", 39: "right", 40: "down", 45: "insert", 46: "del", 59: ";", 61: "=", 96: "0", 97: "1", 98: "2", 99: "3", 100: "4", 101: "5", 102: "6", 103: "7", 104: "8", 105: "9", 106: "*", 107: "+", 109: "-", 110: ".", 111: "/", 112: "f1", 113: "f2", 114: "f3", 115: "f4", 116: "f5", 117: "f6", 118: "f7", 119: "f8", 120: "f9", 121: "f10", 122: "f11", 123: "f12", 144: "numlock", 145: "scroll", 173: "-", 186: ";", 187: "=", 188: ",", 189: "-", 190: ".", 191: "/", 192: "`", 219: "[", 220: "\\", 221: "]", 222: "'"}, shiftNums: {"`": "~", 1: "!", 2: "@", 3: "#", 4: "$", 5: "%", 6: "^", 7: "&", 8: "*", 9: "(", 0: ")", "-": "_", "=": "+", ";": ": ", "'": '"', ",": "<", ".": ">", "/": "?", "\\": "|"}};
		e.each(["keydown", "keyup", "keypress"], function() {
			e.event.special[this] = {add: t}
		})
	})(this.jQuery)

	!function() {
		"use strict";
		var a = function(a, b) {
			var f, g, h, i, j, k = "object" == typeof b && b.maintainCase || !1, l = "object" == typeof b && b.titleCase ? b.titleCase : !1, m = "object" == typeof b && "object" == typeof b.custom && b.custom ? b.custom : {}, n = "object" == typeof b && b.separator || "-", o = "object" == typeof b && +b.truncate > 1 && b.truncate || !1, p = "object" == typeof b && b.uric || !1, q = "object" == typeof b && b.uricNoSlash || !1, r = "object" == typeof b && b.mark || !1, s = "object" == typeof b && b.lang && e[b.lang] ? e[b.lang] : "object" != typeof b || b.lang !== !1 && b.lang !== !0 ? e.en : {}, t = [";", "?", ":", "@", "&", "=", "+", "$", ",", "/"], u = [";", "?", ":", "@", "&", "=", "+", "$", ","], v = [".", "!", "~", "*", "'", "(", ")"], w = "", x = n;
			if (l && "number" == typeof l.length && Array.prototype.toString.call(l) && l.forEach(function(a) {
				m[a + ""] = a + ""
			}), "string" != typeof a)
				return"";
			for ("string" == typeof b?n = b:"object" == typeof b && (p && (x += t.join("")), q && (x += u.join("")), r && (x += v.join(""))), Object.keys(m).forEach(function(b) {
				var d;
				d = b.length > 1 ? new RegExp("\\b" + c(b) + "\\b", "gi") : new RegExp(c(b), "gi"), a = a.replace(d, m[b])
			}), l && (a = a.replace(/(\w)(\S*)/g, function(a, b, c) {
				var d = b.toUpperCase() + (null !== c ? c : "");
				return Object.keys(m).indexOf(d.toLowerCase()) < 0 ? d : d.toLowerCase()
			})), x = c(x), a = a.replace(/(^\s+|\s+$)/g, ""), j = !1, g = 0, i = a.length; i > g; g++)
				h = a[g], d[h] ? (h = j && d[h].match(/[A-Za-z0-9]/) ? " " + d[h] : d[h], j = !1) : !s[h] || p && -1 !== t.join("").indexOf(h) || q && -1 !== u.join("").indexOf(h) || r && -1 !== v.join("").indexOf(h) ? (j && (/[A-Za-z0-9]/.test(h) || w.substr(-1).match(/A-Za-z0-9]/)) && (h = " " + h), j = !1) : (h = j || w.substr(-1).match(/[A-Za-z0-9]/) ? n + s[h] : s[h], h += void 0 !== a[g + 1] && a[g + 1].match(/[A-Za-z0-9]/) ? n : "", j = !0), w += h.replace(new RegExp("[^\\w\\s" + x + "_-]", "g"), n);
			return w = w.replace(/\s+/g, n).replace(new RegExp("\\" + n + "+", "g"), n).replace(new RegExp("(^\\" + n + "+|\\" + n + "+$)", "g"), ""), o && w.length > o && (f = w.charAt(o) === n, w = w.slice(0, o), f || (w = w.slice(0, w.lastIndexOf(n)))), k || l || l.length || (w = w.toLowerCase()), w
		}, b = function(b) {
			return function(c) {
				return a(c, b)
			}
		}, c = function(a) {
			return a.replace(/[-\\^$*+?.()|[\]{}\/]/g, "\\$&")
		}, d = {"À": "A", "Á": "A", "Â": "A", "Ã": "A", "Ä": "Ae", "Å": "A", "Æ": "AE", "Ç": "C", "È": "E", "É": "E", "Ê": "E", "Ë": "E", "Ì": "I", "Í": "I", "Î": "I", "Ï": "I", "Ð": "D", "Ñ": "N", "Ò": "O", "Ó": "O", "Ô": "O", "Õ": "O", "Ö": "Oe", "Ő": "O", "Ø": "O", "Ù": "U", "Ú": "U", "Û": "U", "Ü": "Ue", "Ű": "U", "Ý": "Y", "Þ": "TH", "ß": "ss", "à": "a", "á": "a", "â": "a", "ã": "a", "ä": "ae", "å": "a", "æ": "ae", "ç": "c", "è": "e", "é": "e", "ê": "e", "ë": "e", "ì": "i", "í": "i", "î": "i", "ï": "i", "ð": "d", "ñ": "n", "ò": "o", "ó": "o", "ô": "o", "õ": "o", "ö": "oe", "ő": "o", "ø": "o", "ù": "u", "ú": "u", "û": "u", "ü": "ue", "ű": "u", "ý": "y", "þ": "th", "ÿ": "y", "ẞ": "SS", "α": "a", "β": "v", "γ": "g", "δ": "d", "ε": "e", "ζ": "z", "η": "i", "θ": "th", "ι": "i", "κ": "k", "λ": "l", "μ": "m", "ν": "n", "ξ": "ks", "ο": "o", "π": "p", "ρ": "r", "σ": "s", "τ": "t", "υ": "y", "φ": "f", "χ": "x", "ψ": "ps", "ω": "o", "ά": "a", "έ": "e", "ί": "i", "ό": "o", "ύ": "y", "ή": "i", "ώ": "o", "ς": "s", "ϊ": "i", "ΰ": "y", "ϋ": "y", "ΐ": "i", "Α": "A", "Β": "B", "Γ": "G", "Δ": "D", "Ε": "E", "Ζ": "Z", "Η": "I", "Θ": "TH", "Ι": "I", "Κ": "K", "Λ": "L", "Μ": "M", "Ν": "N", "Ξ": "KS", "Ο": "O", "Π": "P", "Ρ": "R", "Σ": "S", "Τ": "T", "Υ": "Y", "Φ": "F", "Χ": "X", "Ψ": "PS", "Ω": "W", "Ά": "A", "Έ": "E", "Ί": "I", "Ό": "O", "Ύ": "Y", "Ή": "I", "Ώ": "O", "Ϊ": "I", "Ϋ": "Y", "ş": "s", "Ş": "S", "ı": "i", "İ": "I", "ğ": "g", "Ğ": "G", "Ќ": "Kj", "ќ": "kj", "Љ": "Lj", "љ": "lj", "Њ": "Nj", "њ": "nj", "Тс": "Ts", "тс": "ts", "а": "a", "б": "b", "в": "v", "г": "g", "д": "d", "е": "e", "ё": "yo", "ж": "zh", "з": "z", "и": "i", "й": "j", "к": "k", "л": "l", "м": "m", "н": "n", "о": "o", "п": "p", "р": "r", "с": "s", "т": "t", "у": "u", "ф": "f", "х": "h", "ц": "c", "ч": "ch", "ш": "sh", "щ": "sh", "ъ": "", "ы": "y", "ь": "", "э": "e", "ю": "yu", "я": "ya", "А": "A", "Б": "B", "В": "V", "Г": "G", "Д": "D", "Е": "E", "Ё": "Yo", "Ж": "Zh", "З": "Z", "И": "I", "Й": "J", "К": "K", "Л": "L", "М": "M", "Н": "N", "О": "O", "П": "P", "Р": "R", "С": "S", "Т": "T", "У": "U", "Ф": "F", "Х": "H", "Ц": "C", "Ч": "Ch", "Ш": "Sh", "Щ": "Sh", "Ъ": "", "Ы": "Y", "Ь": "", "Э": "E", "Ю": "Yu", "Я": "Ya", "Є": "Ye", "І": "I", "Ї": "Yi", "Ґ": "G", "є": "ye", "і": "i", "ї": "yi", "ґ": "g", "č": "c", "ď": "d", "ě": "e", "ň": "n", "ř": "r", "š": "s", "ť": "t", "ů": "u", "ž": "z", "Č": "C", "Ď": "D", "Ě": "E", "Ň": "N", "Ř": "R", "Š": "S", "Ť": "T", "Ů": "U", "Ž": "Z", "ą": "a", "ć": "c", "ę": "e", "ł": "l", "ń": "n", "ś": "s", "ź": "z", "ż": "z", "Ą": "A", "Ć": "C", "Ę": "E", "Ł": "L", "Ń": "N", "Ś": "S", "Ź": "Z", "Ż": "Z", "ā": "a", "ē": "e", "ģ": "g", "ī": "i", "ķ": "k", "ļ": "l", "ņ": "n", "ū": "u", "Ā": "A", "Ē": "E", "Ģ": "G", "Ī": "I", "Ķ": "k", "Ļ": "L", "Ņ": "N", "Ū": "U", "ا": "a", "أ": "a", "إ": "i", "آ": "aa", "ؤ": "u", "ئ": "e", "ء": "a", "ب": "b", "ت": "t", "ث": "th", "ج": "j", "ح": "h", "خ": "kh", "د": "d", "ذ": "th", "ر": "r", "ز": "z", "س": "s", "ش": "sh", "ص": "s", "ض": "dh", "ط": "t", "ظ": "z", "ع": "a", "غ": "gh", "ف": "f", "ق": "q", "ك": "k", "ل": "l", "م": "m", "ن": "n", "ه": "h", "و": "w", "ي": "y", "ى": "a", "ة": "h", "ﻻ": "la", "ﻷ": "laa", "ﻹ": "lai", "ﻵ": "laa", "َ": "a", "ً": "an", "ِ": "e", "ٍ": "en", "ُ": "u", "ٌ": "on", "ْ": "", "٠": "0", "١": "1", "٢": "2", "٣": "3", "٤": "4", "٥": "5", "٦": "6", "٧": "7", "٨": "8", "٩": "9", "“": '"', "”": '"', "‘": "'", "’": "'", "∂": "d", "ƒ": "f", "™": "(TM)", "©": "(C)", "œ": "oe", "Œ": "OE", "®": "(R)", "†": "+", "℠": "(SM)", "…": "...", "˚": "o", "º": "o", "ª": "a", "•": "*", $: "USD", "€": "EUR", "₢": "BRN", "₣": "FRF", "£": "GBP", "₤": "ITL", "₦": "NGN", "₧": "ESP", "₩": "KRW", "₪": "ILS", "₫": "VND", "₭": "LAK", "₮": "MNT", "₯": "GRD", "₱": "ARS", "₲": "PYG", "₳": "ARA", "₴": "UAH", "₵": "GHS", "¢": "cent", "¥": "CNY", "元": "CNY", "円": "YEN", "﷼": "IRR", "₠": "EWE", "฿": "THB", "₨": "INR", "₹": "INR", "₰": "PF", "đ": "d", "Đ": "D", "ẹ": "e", "Ẹ": "E", "ẽ": "e", "Ẽ": "E", "ế": "e", "Ế": "E", "ề": "e", "Ề": "E", "ệ": "e", "Ệ": "E", "ễ": "e", "Ễ": "E", "ọ": "o", "Ọ": "o", "ố": "o", "Ố": "O", "ồ": "o", "Ồ": "O", "ộ": "o", "Ộ": "O", "ỗ": "o", "Ỗ": "O", "ơ": "o", "Ơ": "O", "ớ": "o", "Ớ": "O", "ờ": "o", "Ờ": "O", "ợ": "o", "Ợ": "O", "ỡ": "o", "Ỡ": "O", "ị": "i", "Ị": "I", "ĩ": "i", "Ĩ": "I", "ụ": "u", "Ụ": "U", "ũ": "u", "Ũ": "U", "ư": "u", "Ư": "U", "ứ": "u", "Ứ": "U", "ừ": "u", "Ừ": "U", "ự": "u", "Ự": "U", "ữ": "u", "Ữ": "U", "ỳ": "y", "Ỳ": "Y", "ỵ": "y", "Ỵ": "Y", "ỹ": "y", "Ỹ": "Y", "ạ": "a", "Ạ": "A", "ấ": "a", "Ấ": "A", "ầ": "a", "Ầ": "A", "ậ": "a", "Ậ": "A", "ẫ": "a", "Ẫ": "A", "ă": "a", "Ă": "A", "ắ": "a", "Ắ": "A", "ằ": "a", "Ằ": "A", "ặ": "a", "Ặ": "A", "ẵ": "a", "Ẵ": "A"}, e = {ar: {"∆": "delta", "∞": "la-nihaya", "♥": "hob", "&": "wa", "|": "aw", "<": "aqal-men", ">": "akbar-men", "∑": "majmou", "¤": "omla"}, de: {"∆": "delta", "∞": "unendlich", "♥": "Liebe", "&": "und", "|": "oder", "<": "kleiner als", ">": "groesser als", "∑": "Summe von", "¤": "Waehrung"}, nl: {"∆": "delta", "∞": "oneindig", "♥": "liefde", "&": "en", "|": "of", "<": "kleiner dan", ">": "groter dan", "∑": "som", "¤": "valuta"}, en: {"∆": "delta", "∞": "infinity", "♥": "love", "&": "and", "|": "or", "<": "less than", ">": "greater than", "∑": "sum", "¤": "currency"}, es: {"∆": "delta", "∞": "infinito", "♥": "amor", "&": "y", "|": "u", "<": "menos que", ">": "mas que", "∑": "suma de los", "¤": "moneda"}, fr: {"∆": "delta", "∞": "infiniment", "♥": "Amour", "&": "et", "|": "ou", "<": "moins que", ">": "superieure a", "∑": "somme des", "¤": "monnaie"}, pt: {"∆": "delta", "∞": "infinito", "♥": "amor", "&": "e", "|": "ou", "<": "menor que", ">": "maior que", "∑": "soma", "¤": "moeda"}, ru: {"∆": "delta", "∞": "beskonechno", "♥": "lubov", "&": "i", "|": "ili", "<": "menshe", ">": "bolshe", "∑": "summa", "¤": "valjuta"}, vn: {"∆": "delta", "∞": "vo cuc", "♥": "yeu", "&": "va", "|": "hoac", "<": "nho hon", ">": "lon hon", "∑": "tong", "¤": "tien te"}};
		if ("undefined" != typeof module && module.exports)
			module.exports = a, module.exports.createSlug = b;
		else if ("undefined" != typeof define && define.amd)
			define([], function() {
				return a
			});
		else
			try {
				if (window.getSlug || window.createSlug)
					throw"speakingurl: globals exists /(getSlug|createSlug)/";
				window.getSlug = a, window.createSlug = b
			} catch (f) {
			}
	}();


	if ($.validator) {
		$.validator.setDefaults({
			highlight: function(e) {
				return $(e).closest(".form-group").addClass("has-error")
			},
			unhighlight: function(e) {
				return $(e).closest(".form-group").removeClass("has-error").find("help-block-hidden").removeClass("help-block-hidden").addClass("help-block").show()
			},
			errorElement: "div",
			errorClass: "jquery-validate-error",
			errorPlacement: function(e, t) {
				var n, r, i;
				i = t.is('input[type="checkbox"]') || t.is('input[type="radio"]');
				r = t.closest(".form-group").find(".jquery-validate-error").length;
				if (!i || !r) {
					if (!r) {
						t.closest(".form-group").find(".help-block").removeClass("help-block").addClass("help-block-hidden").hide()
					}
					e.addClass("help-block");
					if (i) {
						return t.closest('[class*="col-"]').append(e)
					} else {
						n = t.parent();
						if (n.is(".input-group")) {
							return n.parent().append(e)
						} else {
							return n.append(e)
						}
					}
				}
			}}
		)
	}
}).call(this);

/*
 * @class FileInput
 */

(function() {
	var FileInput;

	FileInput = function($input, options) {
		if (options == null) {
			options = {};
		}
		this.options = $.extend({}, FileInput.DEFAULTS, options || {});
		this.$input = $input;
		this.$el = $('<div class="pixel-file-input"><span class="pfi-filename"></span><div class="pfi-actions"></div></div>').insertAfter($input).append($input);
		this.$filename = $('.pfi-filename', this.$el);
		this.$clear_btn = $(this.options.clear_btn_tmpl).addClass('pfi-clear').appendTo($('.pfi-actions', this.$el));
		this.$choose_btn = $(this.options.choose_btn_tmpl).addClass('pfi-choose').appendTo($('.pfi-actions', this.$el));
		this.onChange();
		$input.on('change', (function(_this) {
			return function() {
				return $.proxy(_this.onChange, _this)();
			};
		})(this)).on('click', function(e) {
			return e.stopPropagation();
		});
		this.$el.on('click', function() {
			return $input.click();
		});
		this.$choose_btn.on('click', function(e) {
			return e.preventDefault();
		});
		return this.$clear_btn.on('click', (function(_this) {
			return function(e) {
				$input.wrap('<form>').parent('form').trigger('reset');
				$input.unwrap();
				$.proxy(_this.onChange, _this)();
				e.stopPropagation();
				return e.preventDefault();
			};
		})(this));
	};

	FileInput.DEFAULTS = {
		choose_btn_tmpl: '<a href="#" class="btn btn-xs btn-primary">Choose</a>',
		clear_btn_tmpl: '<a href="#" class="btn btn-xs"><i class="fa fa-times"></i> Clear</a>',
		placeholder: null
	};

	FileInput.prototype.onChange = function() {
		var value;
		value = this.$input.val().replace(/\\/g, '/');
		if (value !== '') {
			this.$clear_btn.css('display', 'inline-block');
			this.$filename.removeClass('pfi-placeholder');
			return this.$filename.text(value.split('/').pop());
		} else {
			this.$clear_btn.css('display', 'none');
			if (this.options.placeholder) {
				this.$filename.addClass('pfi-placeholder');
				return this.$filename.text(this.options.placeholder);
			} else {
				return this.$filename.text('');
			}
		}
	};

	$.fn.FileInput = function(options) {
		return this.each(function() {
			if (!$.data(this, 'FileInput')) {
				return $.data(this, 'FileInput', new FileInput($(this), options));
			}
		});
	};

	$.fn.FileInput.Constructor = FileInput;

}).call(this);

//! moment.js
//! version : 2.8.1
//! authors : Tim Wood, Iskren Chernev, Moment.js contributors
//! license : MIT
//! momentjs.com

(function (undefined) {
    /************************************
        Constants
    ************************************/

    var moment,
        VERSION = '2.8.1',
        // the global-scope this is NOT the global object in Node.js
        globalScope = typeof global !== 'undefined' ? global : this,
        oldGlobalMoment,
        round = Math.round,
        i,

        YEAR = 0,
        MONTH = 1,
        DATE = 2,
        HOUR = 3,
        MINUTE = 4,
        SECOND = 5,
        MILLISECOND = 6,

        // internal storage for locale config files
        locales = {},

        // extra moment internal properties (plugins register props here)
        momentProperties = [],

        // check for nodeJS
        hasModule = (typeof module !== 'undefined' && module.exports),

        // ASP.NET json date format regex
        aspNetJsonRegex = /^\/?Date\((\-?\d+)/i,
        aspNetTimeSpanJsonRegex = /(\-)?(?:(\d*)\.)?(\d+)\:(\d+)(?:\:(\d+)\.?(\d{3})?)?/,

        // from http://docs.closure-library.googlecode.com/git/closure_goog_date_date.js.source.html
        // somewhat more in line with 4.4.3.2 2004 spec, but allows decimal anywhere
        isoDurationRegex = /^(-)?P(?:(?:([0-9,.]*)Y)?(?:([0-9,.]*)M)?(?:([0-9,.]*)D)?(?:T(?:([0-9,.]*)H)?(?:([0-9,.]*)M)?(?:([0-9,.]*)S)?)?|([0-9,.]*)W)$/,

        // format tokens
        formattingTokens = /(\[[^\[]*\])|(\\)?(Mo|MM?M?M?|Do|DDDo|DD?D?D?|ddd?d?|do?|w[o|w]?|W[o|W]?|Q|YYYYYY|YYYYY|YYYY|YY|gg(ggg?)?|GG(GGG?)?|e|E|a|A|hh?|HH?|mm?|ss?|S{1,4}|X|zz?|ZZ?|.)/g,
        localFormattingTokens = /(\[[^\[]*\])|(\\)?(LT|LL?L?L?|l{1,4})/g,

        // parsing token regexes
        parseTokenOneOrTwoDigits = /\d\d?/, // 0 - 99
        parseTokenOneToThreeDigits = /\d{1,3}/, // 0 - 999
        parseTokenOneToFourDigits = /\d{1,4}/, // 0 - 9999
        parseTokenOneToSixDigits = /[+\-]?\d{1,6}/, // -999,999 - 999,999
        parseTokenDigits = /\d+/, // nonzero number of digits
        parseTokenWord = /[0-9]*['a-z\u00A0-\u05FF\u0700-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+|[\u0600-\u06FF\/]+(\s*?[\u0600-\u06FF]+){1,2}/i, // any word (or two) characters or numbers including two/three word month in arabic.
        parseTokenTimezone = /Z|[\+\-]\d\d:?\d\d/gi, // +00:00 -00:00 +0000 -0000 or Z
        parseTokenT = /T/i, // T (ISO separator)
        parseTokenTimestampMs = /[\+\-]?\d+(\.\d{1,3})?/, // 123456789 123456789.123
        parseTokenOrdinal = /\d{1,2}/,

        //strict parsing regexes
        parseTokenOneDigit = /\d/, // 0 - 9
        parseTokenTwoDigits = /\d\d/, // 00 - 99
        parseTokenThreeDigits = /\d{3}/, // 000 - 999
        parseTokenFourDigits = /\d{4}/, // 0000 - 9999
        parseTokenSixDigits = /[+-]?\d{6}/, // -999,999 - 999,999
        parseTokenSignedNumber = /[+-]?\d+/, // -inf - inf

        // iso 8601 regex
        // 0000-00-00 0000-W00 or 0000-W00-0 + T + 00 or 00:00 or 00:00:00 or 00:00:00.000 + +00:00 or +0000 or +00)
        isoRegex = /^\s*(?:[+-]\d{6}|\d{4})-(?:(\d\d-\d\d)|(W\d\d$)|(W\d\d-\d)|(\d\d\d))((T| )(\d\d(:\d\d(:\d\d(\.\d+)?)?)?)?([\+\-]\d\d(?::?\d\d)?|\s*Z)?)?$/,

        isoFormat = 'YYYY-MM-DDTHH:mm:ssZ',

        isoDates = [
            ['YYYYYY-MM-DD', /[+-]\d{6}-\d{2}-\d{2}/],
            ['YYYY-MM-DD', /\d{4}-\d{2}-\d{2}/],
            ['GGGG-[W]WW-E', /\d{4}-W\d{2}-\d/],
            ['GGGG-[W]WW', /\d{4}-W\d{2}/],
            ['YYYY-DDD', /\d{4}-\d{3}/]
        ],

        // iso time formats and regexes
        isoTimes = [
            ['HH:mm:ss.SSSS', /(T| )\d\d:\d\d:\d\d\.\d+/],
            ['HH:mm:ss', /(T| )\d\d:\d\d:\d\d/],
            ['HH:mm', /(T| )\d\d:\d\d/],
            ['HH', /(T| )\d\d/]
        ],

        // timezone chunker "+10:00" > ["10", "00"] or "-1530" > ["-15", "30"]
        parseTimezoneChunker = /([\+\-]|\d\d)/gi,

        // getter and setter names
        proxyGettersAndSetters = 'Date|Hours|Minutes|Seconds|Milliseconds'.split('|'),
        unitMillisecondFactors = {
            'Milliseconds' : 1,
            'Seconds' : 1e3,
            'Minutes' : 6e4,
            'Hours' : 36e5,
            'Days' : 864e5,
            'Months' : 2592e6,
            'Years' : 31536e6
        },

        unitAliases = {
            ms : 'millisecond',
            s : 'second',
            m : 'minute',
            h : 'hour',
            d : 'day',
            D : 'date',
            w : 'week',
            W : 'isoWeek',
            M : 'month',
            Q : 'quarter',
            y : 'year',
            DDD : 'dayOfYear',
            e : 'weekday',
            E : 'isoWeekday',
            gg: 'weekYear',
            GG: 'isoWeekYear'
        },

        camelFunctions = {
            dayofyear : 'dayOfYear',
            isoweekday : 'isoWeekday',
            isoweek : 'isoWeek',
            weekyear : 'weekYear',
            isoweekyear : 'isoWeekYear'
        },

        // format function strings
        formatFunctions = {},

        // default relative time thresholds
        relativeTimeThresholds = {
            s: 45,  // seconds to minute
            m: 45,  // minutes to hour
            h: 22,  // hours to day
            d: 26,  // days to month
            M: 11   // months to year
        },

        // tokens to ordinalize and pad
        ordinalizeTokens = 'DDD w W M D d'.split(' '),
        paddedTokens = 'M D H h m s w W'.split(' '),

        formatTokenFunctions = {
            M    : function () {
                return this.month() + 1;
            },
            MMM  : function (format) {
                return this.localeData().monthsShort(this, format);
            },
            MMMM : function (format) {
                return this.localeData().months(this, format);
            },
            D    : function () {
                return this.date();
            },
            DDD  : function () {
                return this.dayOfYear();
            },
            d    : function () {
                return this.day();
            },
            dd   : function (format) {
                return this.localeData().weekdaysMin(this, format);
            },
            ddd  : function (format) {
                return this.localeData().weekdaysShort(this, format);
            },
            dddd : function (format) {
                return this.localeData().weekdays(this, format);
            },
            w    : function () {
                return this.week();
            },
            W    : function () {
                return this.isoWeek();
            },
            YY   : function () {
                return leftZeroFill(this.year() % 100, 2);
            },
            YYYY : function () {
                return leftZeroFill(this.year(), 4);
            },
            YYYYY : function () {
                return leftZeroFill(this.year(), 5);
            },
            YYYYYY : function () {
                var y = this.year(), sign = y >= 0 ? '+' : '-';
                return sign + leftZeroFill(Math.abs(y), 6);
            },
            gg   : function () {
                return leftZeroFill(this.weekYear() % 100, 2);
            },
            gggg : function () {
                return leftZeroFill(this.weekYear(), 4);
            },
            ggggg : function () {
                return leftZeroFill(this.weekYear(), 5);
            },
            GG   : function () {
                return leftZeroFill(this.isoWeekYear() % 100, 2);
            },
            GGGG : function () {
                return leftZeroFill(this.isoWeekYear(), 4);
            },
            GGGGG : function () {
                return leftZeroFill(this.isoWeekYear(), 5);
            },
            e : function () {
                return this.weekday();
            },
            E : function () {
                return this.isoWeekday();
            },
            a    : function () {
                return this.localeData().meridiem(this.hours(), this.minutes(), true);
            },
            A    : function () {
                return this.localeData().meridiem(this.hours(), this.minutes(), false);
            },
            H    : function () {
                return this.hours();
            },
            h    : function () {
                return this.hours() % 12 || 12;
            },
            m    : function () {
                return this.minutes();
            },
            s    : function () {
                return this.seconds();
            },
            S    : function () {
                return toInt(this.milliseconds() / 100);
            },
            SS   : function () {
                return leftZeroFill(toInt(this.milliseconds() / 10), 2);
            },
            SSS  : function () {
                return leftZeroFill(this.milliseconds(), 3);
            },
            SSSS : function () {
                return leftZeroFill(this.milliseconds(), 3);
            },
            Z    : function () {
                var a = -this.zone(),
                    b = '+';
                if (a < 0) {
                    a = -a;
                    b = '-';
                }
                return b + leftZeroFill(toInt(a / 60), 2) + ':' + leftZeroFill(toInt(a) % 60, 2);
            },
            ZZ   : function () {
                var a = -this.zone(),
                    b = '+';
                if (a < 0) {
                    a = -a;
                    b = '-';
                }
                return b + leftZeroFill(toInt(a / 60), 2) + leftZeroFill(toInt(a) % 60, 2);
            },
            z : function () {
                return this.zoneAbbr();
            },
            zz : function () {
                return this.zoneName();
            },
            X    : function () {
                return this.unix();
            },
            Q : function () {
                return this.quarter();
            }
        },

        deprecations = {},

        lists = ['months', 'monthsShort', 'weekdays', 'weekdaysShort', 'weekdaysMin'];

    // Pick the first defined of two or three arguments. dfl comes from
    // default.
    function dfl(a, b, c) {
        switch (arguments.length) {
            case 2: return a != null ? a : b;
            case 3: return a != null ? a : b != null ? b : c;
            default: throw new Error('Implement me');
        }
    }

    function defaultParsingFlags() {
        // We need to deep clone this object, and es5 standard is not very
        // helpful.
        return {
            empty : false,
            unusedTokens : [],
            unusedInput : [],
            overflow : -2,
            charsLeftOver : 0,
            nullInput : false,
            invalidMonth : null,
            invalidFormat : false,
            userInvalidated : false,
            iso: false
        };
    }

    function printMsg(msg) {
        if (moment.suppressDeprecationWarnings === false &&
                typeof console !== 'undefined' && console.warn) {
            console.warn("Deprecation warning: " + msg);
        }
    }

    function deprecate(msg, fn) {
        var firstTime = true;
        return extend(function () {
            if (firstTime) {
                printMsg(msg);
                firstTime = false;
            }
            return fn.apply(this, arguments);
        }, fn);
    }

    function deprecateSimple(name, msg) {
        if (!deprecations[name]) {
            printMsg(msg);
            deprecations[name] = true;
        }
    }

    function padToken(func, count) {
        return function (a) {
            return leftZeroFill(func.call(this, a), count);
        };
    }
    function ordinalizeToken(func, period) {
        return function (a) {
            return this.localeData().ordinal(func.call(this, a), period);
        };
    }

    while (ordinalizeTokens.length) {
        i = ordinalizeTokens.pop();
        formatTokenFunctions[i + 'o'] = ordinalizeToken(formatTokenFunctions[i], i);
    }
    while (paddedTokens.length) {
        i = paddedTokens.pop();
        formatTokenFunctions[i + i] = padToken(formatTokenFunctions[i], 2);
    }
    formatTokenFunctions.DDDD = padToken(formatTokenFunctions.DDD, 3);


    /************************************
        Constructors
    ************************************/

    function Locale() {
    }

    // Moment prototype object
    function Moment(config, skipOverflow) {
        if (skipOverflow !== false) {
            checkOverflow(config);
        }
        copyConfig(this, config);
        this._d = new Date(+config._d);
    }

    // Duration Constructor
    function Duration(duration) {
        var normalizedInput = normalizeObjectUnits(duration),
            years = normalizedInput.year || 0,
            quarters = normalizedInput.quarter || 0,
            months = normalizedInput.month || 0,
            weeks = normalizedInput.week || 0,
            days = normalizedInput.day || 0,
            hours = normalizedInput.hour || 0,
            minutes = normalizedInput.minute || 0,
            seconds = normalizedInput.second || 0,
            milliseconds = normalizedInput.millisecond || 0;

        // representation for dateAddRemove
        this._milliseconds = +milliseconds +
            seconds * 1e3 + // 1000
            minutes * 6e4 + // 1000 * 60
            hours * 36e5; // 1000 * 60 * 60
        // Because of dateAddRemove treats 24 hours as different from a
        // day when working around DST, we need to store them separately
        this._days = +days +
            weeks * 7;
        // It is impossible translate months into days without knowing
        // which months you are are talking about, so we have to store
        // it separately.
        this._months = +months +
            quarters * 3 +
            years * 12;

        this._data = {};

        this._locale = moment.localeData();

        this._bubble();
    }

    /************************************
        Helpers
    ************************************/


    function extend(a, b) {
        for (var i in b) {
            if (b.hasOwnProperty(i)) {
                a[i] = b[i];
            }
        }

        if (b.hasOwnProperty('toString')) {
            a.toString = b.toString;
        }

        if (b.hasOwnProperty('valueOf')) {
            a.valueOf = b.valueOf;
        }

        return a;
    }

    function copyConfig(to, from) {
        var i, prop, val;

        if (typeof from._isAMomentObject !== 'undefined') {
            to._isAMomentObject = from._isAMomentObject;
        }
        if (typeof from._i !== 'undefined') {
            to._i = from._i;
        }
        if (typeof from._f !== 'undefined') {
            to._f = from._f;
        }
        if (typeof from._l !== 'undefined') {
            to._l = from._l;
        }
        if (typeof from._strict !== 'undefined') {
            to._strict = from._strict;
        }
        if (typeof from._tzm !== 'undefined') {
            to._tzm = from._tzm;
        }
        if (typeof from._isUTC !== 'undefined') {
            to._isUTC = from._isUTC;
        }
        if (typeof from._offset !== 'undefined') {
            to._offset = from._offset;
        }
        if (typeof from._pf !== 'undefined') {
            to._pf = from._pf;
        }
        if (typeof from._locale !== 'undefined') {
            to._locale = from._locale;
        }

        if (momentProperties.length > 0) {
            for (i in momentProperties) {
                prop = momentProperties[i];
                val = from[prop];
                if (typeof val !== 'undefined') {
                    to[prop] = val;
                }
            }
        }

        return to;
    }

    function absRound(number) {
        if (number < 0) {
            return Math.ceil(number);
        } else {
            return Math.floor(number);
        }
    }

    // left zero fill a number
    // see http://jsperf.com/left-zero-filling for performance comparison
    function leftZeroFill(number, targetLength, forceSign) {
        var output = '' + Math.abs(number),
            sign = number >= 0;

        while (output.length < targetLength) {
            output = '0' + output;
        }
        return (sign ? (forceSign ? '+' : '') : '-') + output;
    }

    function positiveMomentsDifference(base, other) {
        var res = {milliseconds: 0, months: 0};

        res.months = other.month() - base.month() +
            (other.year() - base.year()) * 12;
        if (base.clone().add(res.months, 'M').isAfter(other)) {
            --res.months;
        }

        res.milliseconds = +other - +(base.clone().add(res.months, 'M'));

        return res;
    }

    function momentsDifference(base, other) {
        var res;
        other = makeAs(other, base);
        if (base.isBefore(other)) {
            res = positiveMomentsDifference(base, other);
        } else {
            res = positiveMomentsDifference(other, base);
            res.milliseconds = -res.milliseconds;
            res.months = -res.months;
        }

        return res;
    }

    // TODO: remove 'name' arg after deprecation is removed
    function createAdder(direction, name) {
        return function (val, period) {
            var dur, tmp;
            //invert the arguments, but complain about it
            if (period !== null && !isNaN(+period)) {
                deprecateSimple(name, "moment()." + name  + "(period, number) is deprecated. Please use moment()." + name + "(number, period).");
                tmp = val; val = period; period = tmp;
            }

            val = typeof val === 'string' ? +val : val;
            dur = moment.duration(val, period);
            addOrSubtractDurationFromMoment(this, dur, direction);
            return this;
        };
    }

    function addOrSubtractDurationFromMoment(mom, duration, isAdding, updateOffset) {
        var milliseconds = duration._milliseconds,
            days = duration._days,
            months = duration._months;
        updateOffset = updateOffset == null ? true : updateOffset;

        if (milliseconds) {
            mom._d.setTime(+mom._d + milliseconds * isAdding);
        }
        if (days) {
            rawSetter(mom, 'Date', rawGetter(mom, 'Date') + days * isAdding);
        }
        if (months) {
            rawMonthSetter(mom, rawGetter(mom, 'Month') + months * isAdding);
        }
        if (updateOffset) {
            moment.updateOffset(mom, days || months);
        }
    }

    // check if is an array
    function isArray(input) {
        return Object.prototype.toString.call(input) === '[object Array]';
    }

    function isDate(input) {
        return Object.prototype.toString.call(input) === '[object Date]' ||
            input instanceof Date;
    }

    // compare two arrays, return the number of differences
    function compareArrays(array1, array2, dontConvert) {
        var len = Math.min(array1.length, array2.length),
            lengthDiff = Math.abs(array1.length - array2.length),
            diffs = 0,
            i;
        for (i = 0; i < len; i++) {
            if ((dontConvert && array1[i] !== array2[i]) ||
                (!dontConvert && toInt(array1[i]) !== toInt(array2[i]))) {
                diffs++;
            }
        }
        return diffs + lengthDiff;
    }

    function normalizeUnits(units) {
        if (units) {
            var lowered = units.toLowerCase().replace(/(.)s$/, '$1');
            units = unitAliases[units] || camelFunctions[lowered] || lowered;
        }
        return units;
    }

    function normalizeObjectUnits(inputObject) {
        var normalizedInput = {},
            normalizedProp,
            prop;

        for (prop in inputObject) {
            if (inputObject.hasOwnProperty(prop)) {
                normalizedProp = normalizeUnits(prop);
                if (normalizedProp) {
                    normalizedInput[normalizedProp] = inputObject[prop];
                }
            }
        }

        return normalizedInput;
    }

    function makeList(field) {
        var count, setter;

        if (field.indexOf('week') === 0) {
            count = 7;
            setter = 'day';
        }
        else if (field.indexOf('month') === 0) {
            count = 12;
            setter = 'month';
        }
        else {
            return;
        }

        moment[field] = function (format, index) {
            var i, getter,
                method = moment._locale[field],
                results = [];

            if (typeof format === 'number') {
                index = format;
                format = undefined;
            }

            getter = function (i) {
                var m = moment().utc().set(setter, i);
                return method.call(moment._locale, m, format || '');
            };

            if (index != null) {
                return getter(index);
            }
            else {
                for (i = 0; i < count; i++) {
                    results.push(getter(i));
                }
                return results;
            }
        };
    }

    function toInt(argumentForCoercion) {
        var coercedNumber = +argumentForCoercion,
            value = 0;

        if (coercedNumber !== 0 && isFinite(coercedNumber)) {
            if (coercedNumber >= 0) {
                value = Math.floor(coercedNumber);
            } else {
                value = Math.ceil(coercedNumber);
            }
        }

        return value;
    }

    function daysInMonth(year, month) {
        return new Date(Date.UTC(year, month + 1, 0)).getUTCDate();
    }

    function weeksInYear(year, dow, doy) {
        return weekOfYear(moment([year, 11, 31 + dow - doy]), dow, doy).week;
    }

    function daysInYear(year) {
        return isLeapYear(year) ? 366 : 365;
    }

    function isLeapYear(year) {
        return (year % 4 === 0 && year % 100 !== 0) || year % 400 === 0;
    }

    function checkOverflow(m) {
        var overflow;
        if (m._a && m._pf.overflow === -2) {
            overflow =
                m._a[MONTH] < 0 || m._a[MONTH] > 11 ? MONTH :
                m._a[DATE] < 1 || m._a[DATE] > daysInMonth(m._a[YEAR], m._a[MONTH]) ? DATE :
                m._a[HOUR] < 0 || m._a[HOUR] > 23 ? HOUR :
                m._a[MINUTE] < 0 || m._a[MINUTE] > 59 ? MINUTE :
                m._a[SECOND] < 0 || m._a[SECOND] > 59 ? SECOND :
                m._a[MILLISECOND] < 0 || m._a[MILLISECOND] > 999 ? MILLISECOND :
                -1;

            if (m._pf._overflowDayOfYear && (overflow < YEAR || overflow > DATE)) {
                overflow = DATE;
            }

            m._pf.overflow = overflow;
        }
    }

    function isValid(m) {
        if (m._isValid == null) {
            m._isValid = !isNaN(m._d.getTime()) &&
                m._pf.overflow < 0 &&
                !m._pf.empty &&
                !m._pf.invalidMonth &&
                !m._pf.nullInput &&
                !m._pf.invalidFormat &&
                !m._pf.userInvalidated;

            if (m._strict) {
                m._isValid = m._isValid &&
                    m._pf.charsLeftOver === 0 &&
                    m._pf.unusedTokens.length === 0;
            }
        }
        return m._isValid;
    }

    function normalizeLocale(key) {
        return key ? key.toLowerCase().replace('_', '-') : key;
    }

    // pick the locale from the array
    // try ['en-au', 'en-gb'] as 'en-au', 'en-gb', 'en', as in move through the list trying each
    // substring from most specific to least, but move to the next array item if it's a more specific variant than the current root
    function chooseLocale(names) {
        var i = 0, j, next, locale, split;

        while (i < names.length) {
            split = normalizeLocale(names[i]).split('-');
            j = split.length;
            next = normalizeLocale(names[i + 1]);
            next = next ? next.split('-') : null;
            while (j > 0) {
                locale = loadLocale(split.slice(0, j).join('-'));
                if (locale) {
                    return locale;
                }
                if (next && next.length >= j && compareArrays(split, next, true) >= j - 1) {
                    //the next array item is better than a shallower substring of this one
                    break;
                }
                j--;
            }
            i++;
        }
        return null;
    }

    function loadLocale(name) {
        var oldLocale = null;
        if (!locales[name] && hasModule) {
            try {
                oldLocale = moment.locale();
                require('./locale/' + name);
                // because defineLocale currently also sets the global locale, we want to undo that for lazy loaded locales
                moment.locale(oldLocale);
            } catch (e) { }
        }
        return locales[name];
    }

    // Return a moment from input, that is local/utc/zone equivalent to model.
    function makeAs(input, model) {
        return model._isUTC ? moment(input).zone(model._offset || 0) :
            moment(input).local();
    }

    /************************************
        Locale
    ************************************/


    extend(Locale.prototype, {

        set : function (config) {
            var prop, i;
            for (i in config) {
                prop = config[i];
                if (typeof prop === 'function') {
                    this[i] = prop;
                } else {
                    this['_' + i] = prop;
                }
            }
        },

        _months : 'January_February_March_April_May_June_July_August_September_October_November_December'.split('_'),
        months : function (m) {
            return this._months[m.month()];
        },

        _monthsShort : 'Jan_Feb_Mar_Apr_May_Jun_Jul_Aug_Sep_Oct_Nov_Dec'.split('_'),
        monthsShort : function (m) {
            return this._monthsShort[m.month()];
        },

        monthsParse : function (monthName) {
            var i, mom, regex;

            if (!this._monthsParse) {
                this._monthsParse = [];
            }

            for (i = 0; i < 12; i++) {
                // make the regex if we don't have it already
                if (!this._monthsParse[i]) {
                    mom = moment.utc([2000, i]);
                    regex = '^' + this.months(mom, '') + '|^' + this.monthsShort(mom, '');
                    this._monthsParse[i] = new RegExp(regex.replace('.', ''), 'i');
                }
                // test the regex
                if (this._monthsParse[i].test(monthName)) {
                    return i;
                }
            }
        },

        _weekdays : 'Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday'.split('_'),
        weekdays : function (m) {
            return this._weekdays[m.day()];
        },

        _weekdaysShort : 'Sun_Mon_Tue_Wed_Thu_Fri_Sat'.split('_'),
        weekdaysShort : function (m) {
            return this._weekdaysShort[m.day()];
        },

        _weekdaysMin : 'Su_Mo_Tu_We_Th_Fr_Sa'.split('_'),
        weekdaysMin : function (m) {
            return this._weekdaysMin[m.day()];
        },

        weekdaysParse : function (weekdayName) {
            var i, mom, regex;

            if (!this._weekdaysParse) {
                this._weekdaysParse = [];
            }

            for (i = 0; i < 7; i++) {
                // make the regex if we don't have it already
                if (!this._weekdaysParse[i]) {
                    mom = moment([2000, 1]).day(i);
                    regex = '^' + this.weekdays(mom, '') + '|^' + this.weekdaysShort(mom, '') + '|^' + this.weekdaysMin(mom, '');
                    this._weekdaysParse[i] = new RegExp(regex.replace('.', ''), 'i');
                }
                // test the regex
                if (this._weekdaysParse[i].test(weekdayName)) {
                    return i;
                }
            }
        },

        _longDateFormat : {
            LT : 'h:mm A',
            L : 'MM/DD/YYYY',
            LL : 'MMMM D, YYYY',
            LLL : 'MMMM D, YYYY LT',
            LLLL : 'dddd, MMMM D, YYYY LT'
        },
        longDateFormat : function (key) {
            var output = this._longDateFormat[key];
            if (!output && this._longDateFormat[key.toUpperCase()]) {
                output = this._longDateFormat[key.toUpperCase()].replace(/MMMM|MM|DD|dddd/g, function (val) {
                    return val.slice(1);
                });
                this._longDateFormat[key] = output;
            }
            return output;
        },

        isPM : function (input) {
            // IE8 Quirks Mode & IE7 Standards Mode do not allow accessing strings like arrays
            // Using charAt should be more compatible.
            return ((input + '').toLowerCase().charAt(0) === 'p');
        },

        _meridiemParse : /[ap]\.?m?\.?/i,
        meridiem : function (hours, minutes, isLower) {
            if (hours > 11) {
                return isLower ? 'pm' : 'PM';
            } else {
                return isLower ? 'am' : 'AM';
            }
        },

        _calendar : {
            sameDay : '[Today at] LT',
            nextDay : '[Tomorrow at] LT',
            nextWeek : 'dddd [at] LT',
            lastDay : '[Yesterday at] LT',
            lastWeek : '[Last] dddd [at] LT',
            sameElse : 'L'
        },
        calendar : function (key, mom) {
            var output = this._calendar[key];
            return typeof output === 'function' ? output.apply(mom) : output;
        },

        _relativeTime : {
            future : 'in %s',
            past : '%s ago',
            s : 'a few seconds',
            m : 'a minute',
            mm : '%d minutes',
            h : 'an hour',
            hh : '%d hours',
            d : 'a day',
            dd : '%d days',
            M : 'a month',
            MM : '%d months',
            y : 'a year',
            yy : '%d years'
        },

        relativeTime : function (number, withoutSuffix, string, isFuture) {
            var output = this._relativeTime[string];
            return (typeof output === 'function') ?
                output(number, withoutSuffix, string, isFuture) :
                output.replace(/%d/i, number);
        },

        pastFuture : function (diff, output) {
            var format = this._relativeTime[diff > 0 ? 'future' : 'past'];
            return typeof format === 'function' ? format(output) : format.replace(/%s/i, output);
        },

        ordinal : function (number) {
            return this._ordinal.replace('%d', number);
        },
        _ordinal : '%d',

        preparse : function (string) {
            return string;
        },

        postformat : function (string) {
            return string;
        },

        week : function (mom) {
            return weekOfYear(mom, this._week.dow, this._week.doy).week;
        },

        _week : {
            dow : 0, // Sunday is the first day of the week.
            doy : 6  // The week that contains Jan 1st is the first week of the year.
        },

        _invalidDate: 'Invalid date',
        invalidDate: function () {
            return this._invalidDate;
        }
    });

    /************************************
        Formatting
    ************************************/


    function removeFormattingTokens(input) {
        if (input.match(/\[[\s\S]/)) {
            return input.replace(/^\[|\]$/g, '');
        }
        return input.replace(/\\/g, '');
    }

    function makeFormatFunction(format) {
        var array = format.match(formattingTokens), i, length;

        for (i = 0, length = array.length; i < length; i++) {
            if (formatTokenFunctions[array[i]]) {
                array[i] = formatTokenFunctions[array[i]];
            } else {
                array[i] = removeFormattingTokens(array[i]);
            }
        }

        return function (mom) {
            var output = '';
            for (i = 0; i < length; i++) {
                output += array[i] instanceof Function ? array[i].call(mom, format) : array[i];
            }
            return output;
        };
    }

    // format date using native date object
    function formatMoment(m, format) {
        if (!m.isValid()) {
            return m.localeData().invalidDate();
        }

        format = expandFormat(format, m.localeData());

        if (!formatFunctions[format]) {
            formatFunctions[format] = makeFormatFunction(format);
        }

        return formatFunctions[format](m);
    }

    function expandFormat(format, locale) {
        var i = 5;

        function replaceLongDateFormatTokens(input) {
            return locale.longDateFormat(input) || input;
        }

        localFormattingTokens.lastIndex = 0;
        while (i >= 0 && localFormattingTokens.test(format)) {
            format = format.replace(localFormattingTokens, replaceLongDateFormatTokens);
            localFormattingTokens.lastIndex = 0;
            i -= 1;
        }

        return format;
    }


    /************************************
        Parsing
    ************************************/


    // get the regex to find the next token
    function getParseRegexForToken(token, config) {
        var a, strict = config._strict;
        switch (token) {
        case 'Q':
            return parseTokenOneDigit;
        case 'DDDD':
            return parseTokenThreeDigits;
        case 'YYYY':
        case 'GGGG':
        case 'gggg':
            return strict ? parseTokenFourDigits : parseTokenOneToFourDigits;
        case 'Y':
        case 'G':
        case 'g':
            return parseTokenSignedNumber;
        case 'YYYYYY':
        case 'YYYYY':
        case 'GGGGG':
        case 'ggggg':
            return strict ? parseTokenSixDigits : parseTokenOneToSixDigits;
        case 'S':
            if (strict) {
                return parseTokenOneDigit;
            }
            /* falls through */
        case 'SS':
            if (strict) {
                return parseTokenTwoDigits;
            }
            /* falls through */
        case 'SSS':
            if (strict) {
                return parseTokenThreeDigits;
            }
            /* falls through */
        case 'DDD':
            return parseTokenOneToThreeDigits;
        case 'MMM':
        case 'MMMM':
        case 'dd':
        case 'ddd':
        case 'dddd':
            return parseTokenWord;
        case 'a':
        case 'A':
            return config._locale._meridiemParse;
        case 'X':
            return parseTokenTimestampMs;
        case 'Z':
        case 'ZZ':
            return parseTokenTimezone;
        case 'T':
            return parseTokenT;
        case 'SSSS':
            return parseTokenDigits;
        case 'MM':
        case 'DD':
        case 'YY':
        case 'GG':
        case 'gg':
        case 'HH':
        case 'hh':
        case 'mm':
        case 'ss':
        case 'ww':
        case 'WW':
            return strict ? parseTokenTwoDigits : parseTokenOneOrTwoDigits;
        case 'M':
        case 'D':
        case 'd':
        case 'H':
        case 'h':
        case 'm':
        case 's':
        case 'w':
        case 'W':
        case 'e':
        case 'E':
            return parseTokenOneOrTwoDigits;
        case 'Do':
            return parseTokenOrdinal;
        default :
            a = new RegExp(regexpEscape(unescapeFormat(token.replace('\\', '')), 'i'));
            return a;
        }
    }

    function timezoneMinutesFromString(string) {
        string = string || '';
        var possibleTzMatches = (string.match(parseTokenTimezone) || []),
            tzChunk = possibleTzMatches[possibleTzMatches.length - 1] || [],
            parts = (tzChunk + '').match(parseTimezoneChunker) || ['-', 0, 0],
            minutes = +(parts[1] * 60) + toInt(parts[2]);

        return parts[0] === '+' ? -minutes : minutes;
    }

    // function to convert string input to date
    function addTimeToArrayFromToken(token, input, config) {
        var a, datePartArray = config._a;

        switch (token) {
        // QUARTER
        case 'Q':
            if (input != null) {
                datePartArray[MONTH] = (toInt(input) - 1) * 3;
            }
            break;
        // MONTH
        case 'M' : // fall through to MM
        case 'MM' :
            if (input != null) {
                datePartArray[MONTH] = toInt(input) - 1;
            }
            break;
        case 'MMM' : // fall through to MMMM
        case 'MMMM' :
            a = config._locale.monthsParse(input);
            // if we didn't find a month name, mark the date as invalid.
            if (a != null) {
                datePartArray[MONTH] = a;
            } else {
                config._pf.invalidMonth = input;
            }
            break;
        // DAY OF MONTH
        case 'D' : // fall through to DD
        case 'DD' :
            if (input != null) {
                datePartArray[DATE] = toInt(input);
            }
            break;
        case 'Do' :
            if (input != null) {
                datePartArray[DATE] = toInt(parseInt(input, 10));
            }
            break;
        // DAY OF YEAR
        case 'DDD' : // fall through to DDDD
        case 'DDDD' :
            if (input != null) {
                config._dayOfYear = toInt(input);
            }

            break;
        // YEAR
        case 'YY' :
            datePartArray[YEAR] = moment.parseTwoDigitYear(input);
            break;
        case 'YYYY' :
        case 'YYYYY' :
        case 'YYYYYY' :
            datePartArray[YEAR] = toInt(input);
            break;
        // AM / PM
        case 'a' : // fall through to A
        case 'A' :
            config._isPm = config._locale.isPM(input);
            break;
        // 24 HOUR
        case 'H' : // fall through to hh
        case 'HH' : // fall through to hh
        case 'h' : // fall through to hh
        case 'hh' :
            datePartArray[HOUR] = toInt(input);
            break;
        // MINUTE
        case 'm' : // fall through to mm
        case 'mm' :
            datePartArray[MINUTE] = toInt(input);
            break;
        // SECOND
        case 's' : // fall through to ss
        case 'ss' :
            datePartArray[SECOND] = toInt(input);
            break;
        // MILLISECOND
        case 'S' :
        case 'SS' :
        case 'SSS' :
        case 'SSSS' :
            datePartArray[MILLISECOND] = toInt(('0.' + input) * 1000);
            break;
        // UNIX TIMESTAMP WITH MS
        case 'X':
            config._d = new Date(parseFloat(input) * 1000);
            break;
        // TIMEZONE
        case 'Z' : // fall through to ZZ
        case 'ZZ' :
            config._useUTC = true;
            config._tzm = timezoneMinutesFromString(input);
            break;
        // WEEKDAY - human
        case 'dd':
        case 'ddd':
        case 'dddd':
            a = config._locale.weekdaysParse(input);
            // if we didn't get a weekday name, mark the date as invalid
            if (a != null) {
                config._w = config._w || {};
                config._w['d'] = a;
            } else {
                config._pf.invalidWeekday = input;
            }
            break;
        // WEEK, WEEK DAY - numeric
        case 'w':
        case 'ww':
        case 'W':
        case 'WW':
        case 'd':
        case 'e':
        case 'E':
            token = token.substr(0, 1);
            /* falls through */
        case 'gggg':
        case 'GGGG':
        case 'GGGGG':
            token = token.substr(0, 2);
            if (input) {
                config._w = config._w || {};
                config._w[token] = toInt(input);
            }
            break;
        case 'gg':
        case 'GG':
            config._w = config._w || {};
            config._w[token] = moment.parseTwoDigitYear(input);
        }
    }

    function dayOfYearFromWeekInfo(config) {
        var w, weekYear, week, weekday, dow, doy, temp;

        w = config._w;
        if (w.GG != null || w.W != null || w.E != null) {
            dow = 1;
            doy = 4;

            // TODO: We need to take the current isoWeekYear, but that depends on
            // how we interpret now (local, utc, fixed offset). So create
            // a now version of current config (take local/utc/offset flags, and
            // create now).
            weekYear = dfl(w.GG, config._a[YEAR], weekOfYear(moment(), 1, 4).year);
            week = dfl(w.W, 1);
            weekday = dfl(w.E, 1);
        } else {
            dow = config._locale._week.dow;
            doy = config._locale._week.doy;

            weekYear = dfl(w.gg, config._a[YEAR], weekOfYear(moment(), dow, doy).year);
            week = dfl(w.w, 1);

            if (w.d != null) {
                // weekday -- low day numbers are considered next week
                weekday = w.d;
                if (weekday < dow) {
                    ++week;
                }
            } else if (w.e != null) {
                // local weekday -- counting starts from begining of week
                weekday = w.e + dow;
            } else {
                // default to begining of week
                weekday = dow;
            }
        }
        temp = dayOfYearFromWeeks(weekYear, week, weekday, doy, dow);

        config._a[YEAR] = temp.year;
        config._dayOfYear = temp.dayOfYear;
    }

    // convert an array to a date.
    // the array should mirror the parameters below
    // note: all values past the year are optional and will default to the lowest possible value.
    // [year, month, day , hour, minute, second, millisecond]
    function dateFromConfig(config) {
        var i, date, input = [], currentDate, yearToUse;

        if (config._d) {
            return;
        }

        currentDate = currentDateArray(config);

        //compute day of the year from weeks and weekdays
        if (config._w && config._a[DATE] == null && config._a[MONTH] == null) {
            dayOfYearFromWeekInfo(config);
        }

        //if the day of the year is set, figure out what it is
        if (config._dayOfYear) {
            yearToUse = dfl(config._a[YEAR], currentDate[YEAR]);

            if (config._dayOfYear > daysInYear(yearToUse)) {
                config._pf._overflowDayOfYear = true;
            }

            date = makeUTCDate(yearToUse, 0, config._dayOfYear);
            config._a[MONTH] = date.getUTCMonth();
            config._a[DATE] = date.getUTCDate();
        }

        // Default to current date.
        // * if no year, month, day of month are given, default to today
        // * if day of month is given, default month and year
        // * if month is given, default only year
        // * if year is given, don't default anything
        for (i = 0; i < 3 && config._a[i] == null; ++i) {
            config._a[i] = input[i] = currentDate[i];
        }

        // Zero out whatever was not defaulted, including time
        for (; i < 7; i++) {
            config._a[i] = input[i] = (config._a[i] == null) ? (i === 2 ? 1 : 0) : config._a[i];
        }

        config._d = (config._useUTC ? makeUTCDate : makeDate).apply(null, input);
        // Apply timezone offset from input. The actual zone can be changed
        // with parseZone.
        if (config._tzm != null) {
            config._d.setUTCMinutes(config._d.getUTCMinutes() + config._tzm);
        }
    }

    function dateFromObject(config) {
        var normalizedInput;

        if (config._d) {
            return;
        }

        normalizedInput = normalizeObjectUnits(config._i);
        config._a = [
            normalizedInput.year,
            normalizedInput.month,
            normalizedInput.day,
            normalizedInput.hour,
            normalizedInput.minute,
            normalizedInput.second,
            normalizedInput.millisecond
        ];

        dateFromConfig(config);
    }

    function currentDateArray(config) {
        var now = new Date();
        if (config._useUTC) {
            return [
                now.getUTCFullYear(),
                now.getUTCMonth(),
                now.getUTCDate()
            ];
        } else {
            return [now.getFullYear(), now.getMonth(), now.getDate()];
        }
    }

    // date from string and format string
    function makeDateFromStringAndFormat(config) {
        if (config._f === moment.ISO_8601) {
            parseISO(config);
            return;
        }

        config._a = [];
        config._pf.empty = true;

        // This array is used to make a Date, either with `new Date` or `Date.UTC`
        var string = '' + config._i,
            i, parsedInput, tokens, token, skipped,
            stringLength = string.length,
            totalParsedInputLength = 0;

        tokens = expandFormat(config._f, config._locale).match(formattingTokens) || [];

        for (i = 0; i < tokens.length; i++) {
            token = tokens[i];
            parsedInput = (string.match(getParseRegexForToken(token, config)) || [])[0];
            if (parsedInput) {
                skipped = string.substr(0, string.indexOf(parsedInput));
                if (skipped.length > 0) {
                    config._pf.unusedInput.push(skipped);
                }
                string = string.slice(string.indexOf(parsedInput) + parsedInput.length);
                totalParsedInputLength += parsedInput.length;
            }
            // don't parse if it's not a known token
            if (formatTokenFunctions[token]) {
                if (parsedInput) {
                    config._pf.empty = false;
                }
                else {
                    config._pf.unusedTokens.push(token);
                }
                addTimeToArrayFromToken(token, parsedInput, config);
            }
            else if (config._strict && !parsedInput) {
                config._pf.unusedTokens.push(token);
            }
        }

        // add remaining unparsed input length to the string
        config._pf.charsLeftOver = stringLength - totalParsedInputLength;
        if (string.length > 0) {
            config._pf.unusedInput.push(string);
        }

        // handle am pm
        if (config._isPm && config._a[HOUR] < 12) {
            config._a[HOUR] += 12;
        }
        // if is 12 am, change hours to 0
        if (config._isPm === false && config._a[HOUR] === 12) {
            config._a[HOUR] = 0;
        }

        dateFromConfig(config);
        checkOverflow(config);
    }

    function unescapeFormat(s) {
        return s.replace(/\\(\[)|\\(\])|\[([^\]\[]*)\]|\\(.)/g, function (matched, p1, p2, p3, p4) {
            return p1 || p2 || p3 || p4;
        });
    }

    // Code from http://stackoverflow.com/questions/3561493/is-there-a-regexp-escape-function-in-javascript
    function regexpEscape(s) {
        return s.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
    }

    // date from string and array of format strings
    function makeDateFromStringAndArray(config) {
        var tempConfig,
            bestMoment,

            scoreToBeat,
            i,
            currentScore;

        if (config._f.length === 0) {
            config._pf.invalidFormat = true;
            config._d = new Date(NaN);
            return;
        }

        for (i = 0; i < config._f.length; i++) {
            currentScore = 0;
            tempConfig = copyConfig({}, config);
            tempConfig._pf = defaultParsingFlags();
            tempConfig._f = config._f[i];
            makeDateFromStringAndFormat(tempConfig);

            if (!isValid(tempConfig)) {
                continue;
            }

            // if there is any input that was not parsed add a penalty for that format
            currentScore += tempConfig._pf.charsLeftOver;

            //or tokens
            currentScore += tempConfig._pf.unusedTokens.length * 10;

            tempConfig._pf.score = currentScore;

            if (scoreToBeat == null || currentScore < scoreToBeat) {
                scoreToBeat = currentScore;
                bestMoment = tempConfig;
            }
        }

        extend(config, bestMoment || tempConfig);
    }

    // date from iso format
    function parseISO(config) {
        var i, l,
            string = config._i,
            match = isoRegex.exec(string);

        if (match) {
            config._pf.iso = true;
            for (i = 0, l = isoDates.length; i < l; i++) {
                if (isoDates[i][1].exec(string)) {
                    // match[5] should be "T" or undefined
                    config._f = isoDates[i][0] + (match[6] || ' ');
                    break;
                }
            }
            for (i = 0, l = isoTimes.length; i < l; i++) {
                if (isoTimes[i][1].exec(string)) {
                    config._f += isoTimes[i][0];
                    break;
                }
            }
            if (string.match(parseTokenTimezone)) {
                config._f += 'Z';
            }
            makeDateFromStringAndFormat(config);
        } else {
            config._isValid = false;
        }
    }

    // date from iso format or fallback
    function makeDateFromString(config) {
        parseISO(config);
        if (config._isValid === false) {
            delete config._isValid;
            moment.createFromInputFallback(config);
        }
    }

    function makeDateFromInput(config) {
        var input = config._i, matched;
        if (input === undefined) {
            config._d = new Date();
        } else if (isDate(input)) {
            config._d = new Date(+input);
        } else if ((matched = aspNetJsonRegex.exec(input)) !== null) {
            config._d = new Date(+matched[1]);
        } else if (typeof input === 'string') {
            makeDateFromString(config);
        } else if (isArray(input)) {
            config._a = input.slice(0);
            dateFromConfig(config);
        } else if (typeof(input) === 'object') {
            dateFromObject(config);
        } else if (typeof(input) === 'number') {
            // from milliseconds
            config._d = new Date(input);
        } else {
            moment.createFromInputFallback(config);
        }
    }

    function makeDate(y, m, d, h, M, s, ms) {
        //can't just apply() to create a date:
        //http://stackoverflow.com/questions/181348/instantiating-a-javascript-object-by-calling-prototype-constructor-apply
        var date = new Date(y, m, d, h, M, s, ms);

        //the date constructor doesn't accept years < 1970
        if (y < 1970) {
            date.setFullYear(y);
        }
        return date;
    }

    function makeUTCDate(y) {
        var date = new Date(Date.UTC.apply(null, arguments));
        if (y < 1970) {
            date.setUTCFullYear(y);
        }
        return date;
    }

    function parseWeekday(input, locale) {
        if (typeof input === 'string') {
            if (!isNaN(input)) {
                input = parseInt(input, 10);
            }
            else {
                input = locale.weekdaysParse(input);
                if (typeof input !== 'number') {
                    return null;
                }
            }
        }
        return input;
    }

    /************************************
        Relative Time
    ************************************/


    // helper function for moment.fn.from, moment.fn.fromNow, and moment.duration.fn.humanize
    function substituteTimeAgo(string, number, withoutSuffix, isFuture, locale) {
        return locale.relativeTime(number || 1, !!withoutSuffix, string, isFuture);
    }

    function relativeTime(posNegDuration, withoutSuffix, locale) {
        var duration = moment.duration(posNegDuration).abs(),
            seconds = round(duration.as('s')),
            minutes = round(duration.as('m')),
            hours = round(duration.as('h')),
            days = round(duration.as('d')),
            months = round(duration.as('M')),
            years = round(duration.as('y')),

            args = seconds < relativeTimeThresholds.s && ['s', seconds] ||
                minutes === 1 && ['m'] ||
                minutes < relativeTimeThresholds.m && ['mm', minutes] ||
                hours === 1 && ['h'] ||
                hours < relativeTimeThresholds.h && ['hh', hours] ||
                days === 1 && ['d'] ||
                days < relativeTimeThresholds.d && ['dd', days] ||
                months === 1 && ['M'] ||
                months < relativeTimeThresholds.M && ['MM', months] ||
                years === 1 && ['y'] || ['yy', years];

        args[2] = withoutSuffix;
        args[3] = +posNegDuration > 0;
        args[4] = locale;
        return substituteTimeAgo.apply({}, args);
    }


    /************************************
        Week of Year
    ************************************/


    // firstDayOfWeek       0 = sun, 6 = sat
    //                      the day of the week that starts the week
    //                      (usually sunday or monday)
    // firstDayOfWeekOfYear 0 = sun, 6 = sat
    //                      the first week is the week that contains the first
    //                      of this day of the week
    //                      (eg. ISO weeks use thursday (4))
    function weekOfYear(mom, firstDayOfWeek, firstDayOfWeekOfYear) {
        var end = firstDayOfWeekOfYear - firstDayOfWeek,
            daysToDayOfWeek = firstDayOfWeekOfYear - mom.day(),
            adjustedMoment;


        if (daysToDayOfWeek > end) {
            daysToDayOfWeek -= 7;
        }

        if (daysToDayOfWeek < end - 7) {
            daysToDayOfWeek += 7;
        }

        adjustedMoment = moment(mom).add(daysToDayOfWeek, 'd');
        return {
            week: Math.ceil(adjustedMoment.dayOfYear() / 7),
            year: adjustedMoment.year()
        };
    }

    //http://en.wikipedia.org/wiki/ISO_week_date#Calculating_a_date_given_the_year.2C_week_number_and_weekday
    function dayOfYearFromWeeks(year, week, weekday, firstDayOfWeekOfYear, firstDayOfWeek) {
        var d = makeUTCDate(year, 0, 1).getUTCDay(), daysToAdd, dayOfYear;

        d = d === 0 ? 7 : d;
        weekday = weekday != null ? weekday : firstDayOfWeek;
        daysToAdd = firstDayOfWeek - d + (d > firstDayOfWeekOfYear ? 7 : 0) - (d < firstDayOfWeek ? 7 : 0);
        dayOfYear = 7 * (week - 1) + (weekday - firstDayOfWeek) + daysToAdd + 1;

        return {
            year: dayOfYear > 0 ? year : year - 1,
            dayOfYear: dayOfYear > 0 ?  dayOfYear : daysInYear(year - 1) + dayOfYear
        };
    }

    /************************************
        Top Level Functions
    ************************************/

    function makeMoment(config) {
        var input = config._i,
            format = config._f;

        config._locale = config._locale || moment.localeData(config._l);

        if (input === null || (format === undefined && input === '')) {
            return moment.invalid({nullInput: true});
        }

        if (typeof input === 'string') {
            config._i = input = config._locale.preparse(input);
        }

        if (moment.isMoment(input)) {
            return new Moment(input, true);
        } else if (format) {
            if (isArray(format)) {
                makeDateFromStringAndArray(config);
            } else {
                makeDateFromStringAndFormat(config);
            }
        } else {
            makeDateFromInput(config);
        }

        return new Moment(config);
    }

    moment = function (input, format, locale, strict) {
        var c;

        if (typeof(locale) === "boolean") {
            strict = locale;
            locale = undefined;
        }
        // object construction must be done this way.
        // https://github.com/moment/moment/issues/1423
        c = {};
        c._isAMomentObject = true;
        c._i = input;
        c._f = format;
        c._l = locale;
        c._strict = strict;
        c._isUTC = false;
        c._pf = defaultParsingFlags();

        return makeMoment(c);
    };

    moment.suppressDeprecationWarnings = false;

    moment.createFromInputFallback = deprecate(
        'moment construction falls back to js Date. This is ' +
        'discouraged and will be removed in upcoming major ' +
        'release. Please refer to ' +
        'https://github.com/moment/moment/issues/1407 for more info.',
        function (config) {
            config._d = new Date(config._i);
        }
    );

    // Pick a moment m from moments so that m[fn](other) is true for all
    // other. This relies on the function fn to be transitive.
    //
    // moments should either be an array of moment objects or an array, whose
    // first element is an array of moment objects.
    function pickBy(fn, moments) {
        var res, i;
        if (moments.length === 1 && isArray(moments[0])) {
            moments = moments[0];
        }
        if (!moments.length) {
            return moment();
        }
        res = moments[0];
        for (i = 1; i < moments.length; ++i) {
            if (moments[i][fn](res)) {
                res = moments[i];
            }
        }
        return res;
    }

    moment.min = function () {
        var args = [].slice.call(arguments, 0);

        return pickBy('isBefore', args);
    };

    moment.max = function () {
        var args = [].slice.call(arguments, 0);

        return pickBy('isAfter', args);
    };

    // creating with utc
    moment.utc = function (input, format, locale, strict) {
        var c;

        if (typeof(locale) === "boolean") {
            strict = locale;
            locale = undefined;
        }
        // object construction must be done this way.
        // https://github.com/moment/moment/issues/1423
        c = {};
        c._isAMomentObject = true;
        c._useUTC = true;
        c._isUTC = true;
        c._l = locale;
        c._i = input;
        c._f = format;
        c._strict = strict;
        c._pf = defaultParsingFlags();

        return makeMoment(c).utc();
    };

    // creating with unix timestamp (in seconds)
    moment.unix = function (input) {
        return moment(input * 1000);
    };

    // duration
    moment.duration = function (input, key) {
        var duration = input,
            // matching against regexp is expensive, do it on demand
            match = null,
            sign,
            ret,
            parseIso,
            diffRes;

        if (moment.isDuration(input)) {
            duration = {
                ms: input._milliseconds,
                d: input._days,
                M: input._months
            };
        } else if (typeof input === 'number') {
            duration = {};
            if (key) {
                duration[key] = input;
            } else {
                duration.milliseconds = input;
            }
        } else if (!!(match = aspNetTimeSpanJsonRegex.exec(input))) {
            sign = (match[1] === '-') ? -1 : 1;
            duration = {
                y: 0,
                d: toInt(match[DATE]) * sign,
                h: toInt(match[HOUR]) * sign,
                m: toInt(match[MINUTE]) * sign,
                s: toInt(match[SECOND]) * sign,
                ms: toInt(match[MILLISECOND]) * sign
            };
        } else if (!!(match = isoDurationRegex.exec(input))) {
            sign = (match[1] === '-') ? -1 : 1;
            parseIso = function (inp) {
                // We'd normally use ~~inp for this, but unfortunately it also
                // converts floats to ints.
                // inp may be undefined, so careful calling replace on it.
                var res = inp && parseFloat(inp.replace(',', '.'));
                // apply sign while we're at it
                return (isNaN(res) ? 0 : res) * sign;
            };
            duration = {
                y: parseIso(match[2]),
                M: parseIso(match[3]),
                d: parseIso(match[4]),
                h: parseIso(match[5]),
                m: parseIso(match[6]),
                s: parseIso(match[7]),
                w: parseIso(match[8])
            };
        } else if (typeof duration === 'object' &&
                ('from' in duration || 'to' in duration)) {
            diffRes = momentsDifference(moment(duration.from), moment(duration.to));

            duration = {};
            duration.ms = diffRes.milliseconds;
            duration.M = diffRes.months;
        }

        ret = new Duration(duration);

        if (moment.isDuration(input) && input.hasOwnProperty('_locale')) {
            ret._locale = input._locale;
        }

        return ret;
    };

    // version number
    moment.version = VERSION;

    // default format
    moment.defaultFormat = isoFormat;

    // constant that refers to the ISO standard
    moment.ISO_8601 = function () {};

    // Plugins that add properties should also add the key here (null value),
    // so we can properly clone ourselves.
    moment.momentProperties = momentProperties;

    // This function will be called whenever a moment is mutated.
    // It is intended to keep the offset in sync with the timezone.
    moment.updateOffset = function () {};

    // This function allows you to set a threshold for relative time strings
    moment.relativeTimeThreshold = function (threshold, limit) {
        if (relativeTimeThresholds[threshold] === undefined) {
            return false;
        }
        if (limit === undefined) {
            return relativeTimeThresholds[threshold];
        }
        relativeTimeThresholds[threshold] = limit;
        return true;
    };

    moment.lang = deprecate(
        "moment.lang is deprecated. Use moment.locale instead.",
        function (key, value) {
            return moment.locale(key, value);
        }
    );

    // This function will load locale and then set the global locale.  If
    // no arguments are passed in, it will simply return the current global
    // locale key.
    moment.locale = function (key, values) {
        var data;
        if (key) {
            if (typeof(values) !== "undefined") {
                data = moment.defineLocale(key, values);
            }
            else {
                data = moment.localeData(key);
            }

            if (data) {
                moment.duration._locale = moment._locale = data;
            }
        }

        return moment._locale._abbr;
    };

    moment.defineLocale = function (name, values) {
        if (values !== null) {
            values.abbr = name;
            if (!locales[name]) {
                locales[name] = new Locale();
            }
            locales[name].set(values);

            // backwards compat for now: also set the locale
            moment.locale(name);

            return locales[name];
        } else {
            // useful for testing
            delete locales[name];
            return null;
        }
    };

    moment.langData = deprecate(
        "moment.langData is deprecated. Use moment.localeData instead.",
        function (key) {
            return moment.localeData(key);
        }
    );

    // returns locale data
    moment.localeData = function (key) {
        var locale;

        if (key && key._locale && key._locale._abbr) {
            key = key._locale._abbr;
        }

        if (!key) {
            return moment._locale;
        }

        if (!isArray(key)) {
            //short-circuit everything else
            locale = loadLocale(key);
            if (locale) {
                return locale;
            }
            key = [key];
        }

        return chooseLocale(key);
    };

    // compare moment object
    moment.isMoment = function (obj) {
        return obj instanceof Moment ||
            (obj != null &&  obj.hasOwnProperty('_isAMomentObject'));
    };

    // for typechecking Duration objects
    moment.isDuration = function (obj) {
        return obj instanceof Duration;
    };

    for (i = lists.length - 1; i >= 0; --i) {
        makeList(lists[i]);
    }

    moment.normalizeUnits = function (units) {
        return normalizeUnits(units);
    };

    moment.invalid = function (flags) {
        var m = moment.utc(NaN);
        if (flags != null) {
            extend(m._pf, flags);
        }
        else {
            m._pf.userInvalidated = true;
        }

        return m;
    };

    moment.parseZone = function () {
        return moment.apply(null, arguments).parseZone();
    };

    moment.parseTwoDigitYear = function (input) {
        return toInt(input) + (toInt(input) > 68 ? 1900 : 2000);
    };

    /************************************
        Moment Prototype
    ************************************/


    extend(moment.fn = Moment.prototype, {

        clone : function () {
            return moment(this);
        },

        valueOf : function () {
            return +this._d + ((this._offset || 0) * 60000);
        },

        unix : function () {
            return Math.floor(+this / 1000);
        },

        toString : function () {
            return this.clone().locale('en').format("ddd MMM DD YYYY HH:mm:ss [GMT]ZZ");
        },

        toDate : function () {
            return this._offset ? new Date(+this) : this._d;
        },

        toISOString : function () {
            var m = moment(this).utc();
            if (0 < m.year() && m.year() <= 9999) {
                return formatMoment(m, 'YYYY-MM-DD[T]HH:mm:ss.SSS[Z]');
            } else {
                return formatMoment(m, 'YYYYYY-MM-DD[T]HH:mm:ss.SSS[Z]');
            }
        },

        toArray : function () {
            var m = this;
            return [
                m.year(),
                m.month(),
                m.date(),
                m.hours(),
                m.minutes(),
                m.seconds(),
                m.milliseconds()
            ];
        },

        isValid : function () {
            return isValid(this);
        },

        isDSTShifted : function () {
            if (this._a) {
                return this.isValid() && compareArrays(this._a, (this._isUTC ? moment.utc(this._a) : moment(this._a)).toArray()) > 0;
            }

            return false;
        },

        parsingFlags : function () {
            return extend({}, this._pf);
        },

        invalidAt: function () {
            return this._pf.overflow;
        },

        utc : function (keepLocalTime) {
            return this.zone(0, keepLocalTime);
        },

        local : function (keepLocalTime) {
            if (this._isUTC) {
                this.zone(0, keepLocalTime);
                this._isUTC = false;

                if (keepLocalTime) {
                    this.add(this._d.getTimezoneOffset(), 'm');
                }
            }
            return this;
        },

        format : function (inputString) {
            var output = formatMoment(this, inputString || moment.defaultFormat);
            return this.localeData().postformat(output);
        },

        add : createAdder(1, 'add'),

        subtract : createAdder(-1, 'subtract'),

        diff : function (input, units, asFloat) {
            var that = makeAs(input, this),
                zoneDiff = (this.zone() - that.zone()) * 6e4,
                diff, output;

            units = normalizeUnits(units);

            if (units === 'year' || units === 'month') {
                // average number of days in the months in the given dates
                diff = (this.daysInMonth() + that.daysInMonth()) * 432e5; // 24 * 60 * 60 * 1000 / 2
                // difference in months
                output = ((this.year() - that.year()) * 12) + (this.month() - that.month());
                // adjust by taking difference in days, average number of days
                // and dst in the given months.
                output += ((this - moment(this).startOf('month')) -
                        (that - moment(that).startOf('month'))) / diff;
                // same as above but with zones, to negate all dst
                output -= ((this.zone() - moment(this).startOf('month').zone()) -
                        (that.zone() - moment(that).startOf('month').zone())) * 6e4 / diff;
                if (units === 'year') {
                    output = output / 12;
                }
            } else {
                diff = (this - that);
                output = units === 'second' ? diff / 1e3 : // 1000
                    units === 'minute' ? diff / 6e4 : // 1000 * 60
                    units === 'hour' ? diff / 36e5 : // 1000 * 60 * 60
                    units === 'day' ? (diff - zoneDiff) / 864e5 : // 1000 * 60 * 60 * 24, negate dst
                    units === 'week' ? (diff - zoneDiff) / 6048e5 : // 1000 * 60 * 60 * 24 * 7, negate dst
                    diff;
            }
            return asFloat ? output : absRound(output);
        },

        from : function (time, withoutSuffix) {
            return moment.duration({to: this, from: time}).locale(this.locale()).humanize(!withoutSuffix);
        },

        fromNow : function (withoutSuffix) {
            return this.from(moment(), withoutSuffix);
        },

        calendar : function (time) {
            // We want to compare the start of today, vs this.
            // Getting start-of-today depends on whether we're zone'd or not.
            var now = time || moment(),
                sod = makeAs(now, this).startOf('day'),
                diff = this.diff(sod, 'days', true),
                format = diff < -6 ? 'sameElse' :
                    diff < -1 ? 'lastWeek' :
                    diff < 0 ? 'lastDay' :
                    diff < 1 ? 'sameDay' :
                    diff < 2 ? 'nextDay' :
                    diff < 7 ? 'nextWeek' : 'sameElse';
            return this.format(this.localeData().calendar(format, this));
        },

        isLeapYear : function () {
            return isLeapYear(this.year());
        },

        isDST : function () {
            return (this.zone() < this.clone().month(0).zone() ||
                this.zone() < this.clone().month(5).zone());
        },

        day : function (input) {
            var day = this._isUTC ? this._d.getUTCDay() : this._d.getDay();
            if (input != null) {
                input = parseWeekday(input, this.localeData());
                return this.add(input - day, 'd');
            } else {
                return day;
            }
        },

        month : makeAccessor('Month', true),

        startOf : function (units) {
            units = normalizeUnits(units);
            // the following switch intentionally omits break keywords
            // to utilize falling through the cases.
            switch (units) {
            case 'year':
                this.month(0);
                /* falls through */
            case 'quarter':
            case 'month':
                this.date(1);
                /* falls through */
            case 'week':
            case 'isoWeek':
            case 'day':
                this.hours(0);
                /* falls through */
            case 'hour':
                this.minutes(0);
                /* falls through */
            case 'minute':
                this.seconds(0);
                /* falls through */
            case 'second':
                this.milliseconds(0);
                /* falls through */
            }

            // weeks are a special case
            if (units === 'week') {
                this.weekday(0);
            } else if (units === 'isoWeek') {
                this.isoWeekday(1);
            }

            // quarters are also special
            if (units === 'quarter') {
                this.month(Math.floor(this.month() / 3) * 3);
            }

            return this;
        },

        endOf: function (units) {
            units = normalizeUnits(units);
            return this.startOf(units).add(1, (units === 'isoWeek' ? 'week' : units)).subtract(1, 'ms');
        },

        isAfter: function (input, units) {
            units = typeof units !== 'undefined' ? units : 'millisecond';
            return +this.clone().startOf(units) > +moment(input).startOf(units);
        },

        isBefore: function (input, units) {
            units = typeof units !== 'undefined' ? units : 'millisecond';
            return +this.clone().startOf(units) < +moment(input).startOf(units);
        },

        isSame: function (input, units) {
            units = units || 'ms';
            return +this.clone().startOf(units) === +makeAs(input, this).startOf(units);
        },

        min: deprecate(
                 'moment().min is deprecated, use moment.min instead. https://github.com/moment/moment/issues/1548',
                 function (other) {
                     other = moment.apply(null, arguments);
                     return other < this ? this : other;
                 }
         ),

        max: deprecate(
                'moment().max is deprecated, use moment.max instead. https://github.com/moment/moment/issues/1548',
                function (other) {
                    other = moment.apply(null, arguments);
                    return other > this ? this : other;
                }
        ),

        // keepLocalTime = true means only change the timezone, without
        // affecting the local hour. So 5:31:26 +0300 --[zone(2, true)]-->
        // 5:31:26 +0200 It is possible that 5:31:26 doesn't exist int zone
        // +0200, so we adjust the time as needed, to be valid.
        //
        // Keeping the time actually adds/subtracts (one hour)
        // from the actual represented time. That is why we call updateOffset
        // a second time. In case it wants us to change the offset again
        // _changeInProgress == true case, then we have to adjust, because
        // there is no such time in the given timezone.
        zone : function (input, keepLocalTime) {
            var offset = this._offset || 0,
                localAdjust;
            if (input != null) {
                if (typeof input === 'string') {
                    input = timezoneMinutesFromString(input);
                }
                if (Math.abs(input) < 16) {
                    input = input * 60;
                }
                if (!this._isUTC && keepLocalTime) {
                    localAdjust = this._d.getTimezoneOffset();
                }
                this._offset = input;
                this._isUTC = true;
                if (localAdjust != null) {
                    this.subtract(localAdjust, 'm');
                }
                if (offset !== input) {
                    if (!keepLocalTime || this._changeInProgress) {
                        addOrSubtractDurationFromMoment(this,
                                moment.duration(offset - input, 'm'), 1, false);
                    } else if (!this._changeInProgress) {
                        this._changeInProgress = true;
                        moment.updateOffset(this, true);
                        this._changeInProgress = null;
                    }
                }
            } else {
                return this._isUTC ? offset : this._d.getTimezoneOffset();
            }
            return this;
        },

        zoneAbbr : function () {
            return this._isUTC ? 'UTC' : '';
        },

        zoneName : function () {
            return this._isUTC ? 'Coordinated Universal Time' : '';
        },

        parseZone : function () {
            if (this._tzm) {
                this.zone(this._tzm);
            } else if (typeof this._i === 'string') {
                this.zone(this._i);
            }
            return this;
        },

        hasAlignedHourOffset : function (input) {
            if (!input) {
                input = 0;
            }
            else {
                input = moment(input).zone();
            }

            return (this.zone() - input) % 60 === 0;
        },

        daysInMonth : function () {
            return daysInMonth(this.year(), this.month());
        },

        dayOfYear : function (input) {
            var dayOfYear = round((moment(this).startOf('day') - moment(this).startOf('year')) / 864e5) + 1;
            return input == null ? dayOfYear : this.add((input - dayOfYear), 'd');
        },

        quarter : function (input) {
            return input == null ? Math.ceil((this.month() + 1) / 3) : this.month((input - 1) * 3 + this.month() % 3);
        },

        weekYear : function (input) {
            var year = weekOfYear(this, this.localeData()._week.dow, this.localeData()._week.doy).year;
            return input == null ? year : this.add((input - year), 'y');
        },

        isoWeekYear : function (input) {
            var year = weekOfYear(this, 1, 4).year;
            return input == null ? year : this.add((input - year), 'y');
        },

        week : function (input) {
            var week = this.localeData().week(this);
            return input == null ? week : this.add((input - week) * 7, 'd');
        },

        isoWeek : function (input) {
            var week = weekOfYear(this, 1, 4).week;
            return input == null ? week : this.add((input - week) * 7, 'd');
        },

        weekday : function (input) {
            var weekday = (this.day() + 7 - this.localeData()._week.dow) % 7;
            return input == null ? weekday : this.add(input - weekday, 'd');
        },

        isoWeekday : function (input) {
            // behaves the same as moment#day except
            // as a getter, returns 7 instead of 0 (1-7 range instead of 0-6)
            // as a setter, sunday should belong to the previous week.
            return input == null ? this.day() || 7 : this.day(this.day() % 7 ? input : input - 7);
        },

        isoWeeksInYear : function () {
            return weeksInYear(this.year(), 1, 4);
        },

        weeksInYear : function () {
            var weekInfo = this.localeData()._week;
            return weeksInYear(this.year(), weekInfo.dow, weekInfo.doy);
        },

        get : function (units) {
            units = normalizeUnits(units);
            return this[units]();
        },

        set : function (units, value) {
            units = normalizeUnits(units);
            if (typeof this[units] === 'function') {
                this[units](value);
            }
            return this;
        },

        // If passed a locale key, it will set the locale for this
        // instance.  Otherwise, it will return the locale configuration
        // variables for this instance.
        locale : function (key) {
            if (key === undefined) {
                return this._locale._abbr;
            } else {
                this._locale = moment.localeData(key);
                return this;
            }
        },

        lang : deprecate(
            "moment().lang() is deprecated. Use moment().localeData() instead.",
            function (key) {
                if (key === undefined) {
                    return this.localeData();
                } else {
                    this._locale = moment.localeData(key);
                    return this;
                }
            }
        ),

        localeData : function () {
            return this._locale;
        }
    });

    function rawMonthSetter(mom, value) {
        var dayOfMonth;

        // TODO: Move this out of here!
        if (typeof value === 'string') {
            value = mom.localeData().monthsParse(value);
            // TODO: Another silent failure?
            if (typeof value !== 'number') {
                return mom;
            }
        }

        dayOfMonth = Math.min(mom.date(),
                daysInMonth(mom.year(), value));
        mom._d['set' + (mom._isUTC ? 'UTC' : '') + 'Month'](value, dayOfMonth);
        return mom;
    }

    function rawGetter(mom, unit) {
        return mom._d['get' + (mom._isUTC ? 'UTC' : '') + unit]();
    }

    function rawSetter(mom, unit, value) {
        if (unit === 'Month') {
            return rawMonthSetter(mom, value);
        } else {
            return mom._d['set' + (mom._isUTC ? 'UTC' : '') + unit](value);
        }
    }

    function makeAccessor(unit, keepTime) {
        return function (value) {
            if (value != null) {
                rawSetter(this, unit, value);
                moment.updateOffset(this, keepTime);
                return this;
            } else {
                return rawGetter(this, unit);
            }
        };
    }

    moment.fn.millisecond = moment.fn.milliseconds = makeAccessor('Milliseconds', false);
    moment.fn.second = moment.fn.seconds = makeAccessor('Seconds', false);
    moment.fn.minute = moment.fn.minutes = makeAccessor('Minutes', false);
    // Setting the hour should keep the time, because the user explicitly
    // specified which hour he wants. So trying to maintain the same hour (in
    // a new timezone) makes sense. Adding/subtracting hours does not follow
    // this rule.
    moment.fn.hour = moment.fn.hours = makeAccessor('Hours', true);
    // moment.fn.month is defined separately
    moment.fn.date = makeAccessor('Date', true);
    moment.fn.dates = deprecate('dates accessor is deprecated. Use date instead.', makeAccessor('Date', true));
    moment.fn.year = makeAccessor('FullYear', true);
    moment.fn.years = deprecate('years accessor is deprecated. Use year instead.', makeAccessor('FullYear', true));

    // add plural methods
    moment.fn.days = moment.fn.day;
    moment.fn.months = moment.fn.month;
    moment.fn.weeks = moment.fn.week;
    moment.fn.isoWeeks = moment.fn.isoWeek;
    moment.fn.quarters = moment.fn.quarter;

    // add aliased format methods
    moment.fn.toJSON = moment.fn.toISOString;

    /************************************
        Duration Prototype
    ************************************/


    function daysToYears (days) {
        // 400 years have 146097 days (taking into account leap year rules)
        return days * 400 / 146097;
    }

    function yearsToDays (years) {
        // years * 365 + absRound(years / 4) -
        //     absRound(years / 100) + absRound(years / 400);
        return years * 146097 / 400;
    }

    extend(moment.duration.fn = Duration.prototype, {

        _bubble : function () {
            var milliseconds = this._milliseconds,
                days = this._days,
                months = this._months,
                data = this._data,
                seconds, minutes, hours, years = 0;

            // The following code bubbles up values, see the tests for
            // examples of what that means.
            data.milliseconds = milliseconds % 1000;

            seconds = absRound(milliseconds / 1000);
            data.seconds = seconds % 60;

            minutes = absRound(seconds / 60);
            data.minutes = minutes % 60;

            hours = absRound(minutes / 60);
            data.hours = hours % 24;

            days += absRound(hours / 24);

            // Accurately convert days to years, assume start from year 0.
            years = absRound(daysToYears(days));
            days -= absRound(yearsToDays(years));

            // 30 days to a month
            // TODO (iskren): Use anchor date (like 1st Jan) to compute this.
            months += absRound(days / 30);
            days %= 30;

            // 12 months -> 1 year
            years += absRound(months / 12);
            months %= 12;

            data.days = days;
            data.months = months;
            data.years = years;
        },

        abs : function () {
            this._milliseconds = Math.abs(this._milliseconds);
            this._days = Math.abs(this._days);
            this._months = Math.abs(this._months);

            this._data.milliseconds = Math.abs(this._data.milliseconds);
            this._data.seconds = Math.abs(this._data.seconds);
            this._data.minutes = Math.abs(this._data.minutes);
            this._data.hours = Math.abs(this._data.hours);
            this._data.months = Math.abs(this._data.months);
            this._data.years = Math.abs(this._data.years);

            return this;
        },

        weeks : function () {
            return absRound(this.days() / 7);
        },

        valueOf : function () {
            return this._milliseconds +
              this._days * 864e5 +
              (this._months % 12) * 2592e6 +
              toInt(this._months / 12) * 31536e6;
        },

        humanize : function (withSuffix) {
            var output = relativeTime(this, !withSuffix, this.localeData());

            if (withSuffix) {
                output = this.localeData().pastFuture(+this, output);
            }

            return this.localeData().postformat(output);
        },

        add : function (input, val) {
            // supports only 2.0-style add(1, 's') or add(moment)
            var dur = moment.duration(input, val);

            this._milliseconds += dur._milliseconds;
            this._days += dur._days;
            this._months += dur._months;

            this._bubble();

            return this;
        },

        subtract : function (input, val) {
            var dur = moment.duration(input, val);

            this._milliseconds -= dur._milliseconds;
            this._days -= dur._days;
            this._months -= dur._months;

            this._bubble();

            return this;
        },

        get : function (units) {
            units = normalizeUnits(units);
            return this[units.toLowerCase() + 's']();
        },

        as : function (units) {
            var days, months;
            units = normalizeUnits(units);

            days = this._days + this._milliseconds / 864e5;
            if (units === 'month' || units === 'year') {
                months = this._months + daysToYears(days) * 12;
                return units === 'month' ? months : months / 12;
            } else {
                days += yearsToDays(this._months / 12);
                switch (units) {
                    case 'week': return days / 7;
                    case 'day': return days;
                    case 'hour': return days * 24;
                    case 'minute': return days * 24 * 60;
                    case 'second': return days * 24 * 60 * 60;
                    case 'millisecond': return days * 24 * 60 * 60 * 1000;
                    default: throw new Error('Unknown unit ' + units);
                }
            }
        },

        lang : moment.fn.lang,
        locale : moment.fn.locale,

        toIsoString : deprecate(
            "toIsoString() is deprecated. Please use toISOString() instead " +
            "(notice the capitals)",
            function () {
                return this.toISOString();
            }
        ),

        toISOString : function () {
            // inspired by https://github.com/dordille/moment-isoduration/blob/master/moment.isoduration.js
            var years = Math.abs(this.years()),
                months = Math.abs(this.months()),
                days = Math.abs(this.days()),
                hours = Math.abs(this.hours()),
                minutes = Math.abs(this.minutes()),
                seconds = Math.abs(this.seconds() + this.milliseconds() / 1000);

            if (!this.asSeconds()) {
                // this is the same as C#'s (Noda) and python (isodate)...
                // but not other JS (goog.date)
                return 'P0D';
            }

            return (this.asSeconds() < 0 ? '-' : '') +
                'P' +
                (years ? years + 'Y' : '') +
                (months ? months + 'M' : '') +
                (days ? days + 'D' : '') +
                ((hours || minutes || seconds) ? 'T' : '') +
                (hours ? hours + 'H' : '') +
                (minutes ? minutes + 'M' : '') +
                (seconds ? seconds + 'S' : '');
        },

        localeData : function () {
            return this._locale;
        }
    });

    function makeDurationGetter(name) {
        moment.duration.fn[name] = function () {
            return this._data[name];
        };
    }

    for (i in unitMillisecondFactors) {
        if (unitMillisecondFactors.hasOwnProperty(i)) {
            makeDurationGetter(i.toLowerCase());
        }
    }

    moment.duration.fn.asMilliseconds = function () {
        return this.as('ms');
    };
    moment.duration.fn.asSeconds = function () {
        return this.as('s');
    };
    moment.duration.fn.asMinutes = function () {
        return this.as('m');
    };
    moment.duration.fn.asHours = function () {
        return this.as('h');
    };
    moment.duration.fn.asDays = function () {
        return this.as('d');
    };
    moment.duration.fn.asWeeks = function () {
        return this.as('weeks');
    };
    moment.duration.fn.asMonths = function () {
        return this.as('M');
    };
    moment.duration.fn.asYears = function () {
        return this.as('y');
    };

    /************************************
        Default Locale
    ************************************/


    // Set default locale, other locale will inherit from English.
    moment.locale('en', {
        ordinal : function (number) {
            var b = number % 10,
                output = (toInt(number % 100 / 10) === 1) ? 'th' :
                (b === 1) ? 'st' :
                (b === 2) ? 'nd' :
                (b === 3) ? 'rd' : 'th';
            return number + output;
        }
    });

// moment.js locale configuration
// locale : russian (ru)
// author : Viktorminator : https://github.com/Viktorminator
// Author : Menelion Elensúle : https://github.com/Oire

(function (factory) {
    factory(moment);
}(function (moment) {
    function plural(word, num) {
        var forms = word.split('_');
        return num % 10 === 1 && num % 100 !== 11 ? forms[0] : (num % 10 >= 2 && num % 10 <= 4 && (num % 100 < 10 || num % 100 >= 20) ? forms[1] : forms[2]);
    }

    function relativeTimeWithPlural(number, withoutSuffix, key) {
        var format = {
            'mm': withoutSuffix ? 'минута_минуты_минут' : 'минуту_минуты_минут',
            'hh': 'час_часа_часов',
            'dd': 'день_дня_дней',
            'MM': 'месяц_месяца_месяцев',
            'yy': 'год_года_лет'
        };
        if (key === 'm') {
            return withoutSuffix ? 'минута' : 'минуту';
        }
        else {
            return number + ' ' + plural(format[key], +number);
        }
    }

    function monthsCaseReplace(m, format) {
        var months = {
            'nominative': 'январь_февраль_март_апрель_май_июнь_июль_август_сентябрь_октябрь_ноябрь_декабрь'.split('_'),
            'accusative': 'января_февраля_марта_апреля_мая_июня_июля_августа_сентября_октября_ноября_декабря'.split('_')
        },

        nounCase = (/D[oD]?(\[[^\[\]]*\]|\s+)+MMMM?/).test(format) ?
            'accusative' :
            'nominative';

        return months[nounCase][m.month()];
    }

    function monthsShortCaseReplace(m, format) {
        var monthsShort = {
            'nominative': 'янв_фев_мар_апр_май_июнь_июль_авг_сен_окт_ноя_дек'.split('_'),
            'accusative': 'янв_фев_мар_апр_мая_июня_июля_авг_сен_окт_ноя_дек'.split('_')
        },

        nounCase = (/D[oD]?(\[[^\[\]]*\]|\s+)+MMMM?/).test(format) ?
            'accusative' :
            'nominative';

        return monthsShort[nounCase][m.month()];
    }

    function weekdaysCaseReplace(m, format) {
        var weekdays = {
            'nominative': 'воскресенье_понедельник_вторник_среда_четверг_пятница_суббота'.split('_'),
            'accusative': 'воскресенье_понедельник_вторник_среду_четверг_пятницу_субботу'.split('_')
        },

        nounCase = (/\[ ?[Вв] ?(?:прошлую|следующую)? ?\] ?dddd/).test(format) ?
            'accusative' :
            'nominative';

        return weekdays[nounCase][m.day()];
    }

    return moment.defineLocale('ru-ru', {
        months : monthsCaseReplace,
        monthsShort : monthsShortCaseReplace,
        weekdays : weekdaysCaseReplace,
        weekdaysShort : 'вс_пн_вт_ср_чт_пт_сб'.split('_'),
        weekdaysMin : 'вс_пн_вт_ср_чт_пт_сб'.split('_'),
        monthsParse : [/^янв/i, /^фев/i, /^мар/i, /^апр/i, /^ма[й|я]/i, /^июн/i, /^июл/i, /^авг/i, /^сен/i, /^окт/i, /^ноя/i, /^дек/i],
        longDateFormat : {
            LT : 'HH:mm',
            L : 'DD.MM.YYYY',
            LL : 'D MMMM YYYY г.',
            LLL : 'D MMMM YYYY г., LT',
            LLLL : 'dddd, D MMMM YYYY г., LT'
        },
        calendar : {
            sameDay: '[Сегодня в] LT',
            nextDay: '[Завтра в] LT',
            lastDay: '[Вчера в] LT',
            nextWeek: function () {
                return this.day() === 2 ? '[Во] dddd [в] LT' : '[В] dddd [в] LT';
            },
            lastWeek: function () {
                switch (this.day()) {
                case 0:
                    return '[В прошлое] dddd [в] LT';
                case 1:
                case 2:
                case 4:
                    return '[В прошлый] dddd [в] LT';
                case 3:
                case 5:
                case 6:
                    return '[В прошлую] dddd [в] LT';
                }
            },
            sameElse: 'L'
        },
        relativeTime : {
            future : 'через %s',
            past : '%s назад',
            s : 'несколько секунд',
            m : relativeTimeWithPlural,
            mm : relativeTimeWithPlural,
            h : 'час',
            hh : relativeTimeWithPlural,
            d : 'день',
            dd : relativeTimeWithPlural,
            M : 'месяц',
            MM : relativeTimeWithPlural,
            y : 'год',
            yy : relativeTimeWithPlural
        },

        meridiemParse: /ночи|утра|дня|вечера/i,
        isPM : function (input) {
            return /^(дня|вечера)$/.test(input);
        },

        meridiem : function (hour, minute, isLower) {
            if (hour < 4) {
                return 'ночи';
            } else if (hour < 12) {
                return 'утра';
            } else if (hour < 17) {
                return 'дня';
            } else {
                return 'вечера';
            }
        },

        ordinal: function (number, period) {
            switch (period) {
            case 'M':
            case 'd':
            case 'DDD':
                return number + '-й';
            case 'D':
                return number + '-го';
            case 'w':
            case 'W':
                return number + '-я';
            default:
                return number;
            }
        },

        week : {
            dow : 1, // Monday is the first day of the week.
            doy : 7  // The week that contains Jan 1st is the first week of the year.
        }
    });
}));

    moment.locale(LOCALE);


    /************************************
        Exposing Moment
    ************************************/

    function makeGlobal(shouldDeprecate) {
        /*global ender:false */
        if (typeof ender !== 'undefined') {
            return;
        }
        oldGlobalMoment = globalScope.moment;
        if (shouldDeprecate) {
            globalScope.moment = deprecate(
                    'Accessing Moment through the global scope is ' +
                    'deprecated, and will be removed in an upcoming ' +
                    'release.',
                    moment);
        } else {
            globalScope.moment = moment;
        }
    }

    // CommonJS module is defined
    if (hasModule) {
        module.exports = moment;
    } else if (typeof define === 'function' && define.amd) {
        define('moment', function (require, exports, module) {
            if (module.config && module.config() && module.config().noGlobal === true) {
                // release the global variable
                globalScope.moment = oldGlobalMoment;
            }

            return moment;
        });
        makeGlobal(true);
    } else {
        makeGlobal();
    }
}).call(this);


/*
 * @class ExpandingInput
 */
(function() {
	var ExpandingInput;

	ExpandingInput = function($container, options) {
		if (options == null) {
			options = {};
		}
		this.options = $.extend({}, ExpandingInput.DEFAULTS, options || {});
		this.$container = $container;
		this.$target = this.options.target && this.$container.find(this.options.target).length ? this.$container.find(this.options.target) : null;
		this.$content = this.options.hidden_content && this.$container.find(this.options.hidden_content).length ? this.$container.find(this.options.hidden_content) : null;
		this.$container.addClass('expanding-input');
		if (this.$target) {
			this.$target.addClass('expanding-input-target');
			if (this.$target.hasClass('input-sm')) {
				this.$container.addClass('expanding-input-sm');
			}
			if (this.$target.hasClass('input-lg')) {
				this.$container.addClass('expanding-input-lg');
			}
		}
		if (this.$content) {
			this.$content.addClass('expanding-input-content');
		}
		this.$overlay = $('<div class="expanding-input-overlay"></div>').appendTo(this.$container);
		if (this.$target && this.$target.attr('placeholder')) {
			if (!this.options.placeholder) {
				this.options.placeholder = this.$target.attr('placeholder');
			}
			this.$target.attr('placeholder', '');
		}
		if (this.options.placeholder) {
			this.$overlay.append($('<div class="expanding-input-placeholder"></div>').html(this.options.placeholder));
		}
		if (this.$target) {
			this.$target.on('focus', $.proxy(this.expand, this));
		}
		return this.$overlay.on('click.expanding_input', $.proxy(this.expand, this));
	};

	ExpandingInput.prototype.expand = function() {
		if (this.$container.hasClass('expanded')) {
			return;
		}
		if (this.options.onBeforeExpand) {
			this.options.onBeforeExpand.call(this);
		}
		this.$overlay.remove();
		this.$container.addClass('expanded');
		if (this.$target) {
			setTimeout((function(_this) {
				return function() {
					return _this.$target.focus();
				};
			})(this), 1);
		}
		if (this.$target && this.options.placeholder) {
			this.$target.attr('placeholder', $('<div>' + this.options.placeholder + '</div>').text());
		}
		if (this.options.onAfterExpand) {
			return this.options.onAfterExpand.call(this);
		}
	};

	ExpandingInput.DEFAULTS = {
		target: null,
		hidden_content: null,
		placeholder: null,
		onBeforeExpand: null,
		onAfterExpand: null
	};

	$.fn.expandingInput = function(options) {
		return this.each(function() {
			var $this;
			$this = $(this);
			if (!$this.attr('data-expandingInput')) {
				return $.data($this, 'expandingInput', new ExpandingInput($this, options));
			}
		});
	};

	$.fn.expandingInput.Constructor = ExpandingInput;

}).call(this);


/**
 * jQuery.query - Query String Modification and Creation for jQuery
 * Written by Blair Mitchelmore (blair DOT mitchelmore AT gmail DOT com)
 * Licensed under the WTFPL (http://sam.zoy.org/wtfpl/).
 * Date: 2009/8/13
 *
 * @author Blair Mitchelmore
 * @version 2.1.7
 *
 **/
(function() {
	new function(settings) {
		// Various Settings
		var $separator = settings.separator || '&';
		var $spaces = settings.spaces === false ? false : true;
		var $suffix = settings.suffix === false ? '' : '[]';
		var $prefix = settings.prefix === false ? false : true;
		var $hash = $prefix ? settings.hash === true ? "#" : "?" : "";
		var $numbers = settings.numbers === false ? false : true;

		jQuery.query = new function() {
			var is = function(o, t) {
				return o != undefined && o !== null && (!!t ? o.constructor == t : true);
			};
			var parse = function(path) {
				var m, rx = /\[([^[]*)\]/g, match = /^([^[]+)(\[.*\])?$/.exec(path), base = match[1], tokens = [];
				while (m = rx.exec(match[2]))
					tokens.push(m[1]);
				return [base, tokens];
			};
			var set = function(target, tokens, value) {
				var o, token = tokens.shift();
				if (typeof target != 'object')
					target = null;
				if (token === "") {
					if (!target)
						target = [];
					if (is(target, Array)) {
						target.push(tokens.length == 0 ? value : set(null, tokens.slice(0), value));
					} else if (is(target, Object)) {
						var i = 0;
						while (target[i++] != null)
							;
						target[--i] = tokens.length == 0 ? value : set(target[i], tokens.slice(0), value);
					} else {
						target = [];
						target.push(tokens.length == 0 ? value : set(null, tokens.slice(0), value));
					}
				} else if (token && token.match(/^\s*[0-9]+\s*$/)) {
					var index = parseInt(token, 10);
					if (!target)
						target = [];
					target[index] = tokens.length == 0 ? value : set(target[index], tokens.slice(0), value);
				} else if (token) {
					var index = token.replace(/^\s*|\s*$/g, "");
					if (!target)
						target = {};
					if (is(target, Array)) {
						var temp = {};
						for (var i = 0; i < target.length; ++i) {
							temp[i] = target[i];
						}
						target = temp;
					}
					target[index] = tokens.length == 0 ? value : set(target[index], tokens.slice(0), value);
				} else {
					return value;
				}
				return target;
			};

			var queryObject = function(a) {
				var self = this;
				self.keys = {};

				if (a.queryObject) {
					jQuery.each(a.get(), function(key, val) {
						self.SET(key, val);
					});
				} else {
					self.parseNew.apply(self, arguments);
				}
				return self;
			};

			queryObject.prototype = {
				queryObject: true,
				parseNew: function() {
					var self = this;
					self.keys = {};
					jQuery.each(arguments, function() {
						var q = "" + this;
						q = q.replace(/^[?#]/, ''); // remove any leading ? || #
						q = q.replace(/[;&]$/, ''); // remove any trailing & || ;
						if ($spaces)
							q = q.replace(/[+]/g, ' '); // replace +'s with spaces

						jQuery.each(q.split(/[&;]/), function() {
							var key = decodeURIComponent(this.split('=')[0] || "");
							var val = decodeURIComponent(this.split('=')[1] || "");

							if (!key)
								return;

							if ($numbers) {
								if (/^[+-]?[0-9]+\.[0-9]*$/.test(val)) // simple float regex
									val = parseFloat(val);
								else if (/^[+-]?[0-9]+$/.test(val)) // simple int regex
									val = parseInt(val, 10);
							}

							val = (!val && val !== 0) ? true : val;

							self.SET(key, val);
						});
					});
					return self;
				},
				has: function(key, type) {
					var value = this.get(key);
					return is(value, type);
				},
				GET: function(key) {
					if (!is(key))
						return this.keys;
					var parsed = parse(key), base = parsed[0], tokens = parsed[1];
					var target = this.keys[base];
					while (target != null && tokens.length != 0) {
						target = target[tokens.shift()];
					}
					return typeof target == 'number' ? target : target || "";
				},
				get: function(key) {
					var target = this.GET(key);
					if (is(target, Object))
						return jQuery.extend(true, {}, target);
					else if (is(target, Array))
						return target.slice(0);
					return target;
				},
				SET: function(key, val) {
					var value = !is(val) ? null : val;
					var parsed = parse(key), base = parsed[0], tokens = parsed[1];
					var target = this.keys[base];
					this.keys[base] = set(target, tokens.slice(0), value);
					return this;
				},
				set: function(key, val) {
					return this.copy().SET(key, val);
				},
				REMOVE: function(key) {
					return this.SET(key, null).COMPACT();
				},
				remove: function(key) {
					return this.copy().REMOVE(key);
				},
				EMPTY: function() {
					var self = this;
					jQuery.each(self.keys, function(key, value) {
						delete self.keys[key];
					});
					return self;
				},
				load: function(url) {
					var search = url.replace(/^.*?[?](.+?)(?:#.+)?$/, "$1");
					return new queryObject(url.length == search.length ? '' : search);
				},
				empty: function() {
					return this.copy().EMPTY();
				},
				copy: function() {
					return new queryObject(this);
				},
				COMPACT: function() {
					function build(orig) {
						var obj = typeof orig == "object" ? is(orig, Array) ? [] : {} : orig;
						if (typeof orig == 'object') {
							function add(o, key, value) {
								if (is(o, Array))
									o.push(value);
								else
									o[key] = value;
							}
							jQuery.each(orig, function(key, value) {
								if (!is(value))
									return true;
								add(obj, key, build(value));
							});
						}
						return obj;
					}
					this.keys = build(this.keys);
					return this;
				},
				compact: function() {
					return this.copy().COMPACT();
				},
				toString: function() {
					var i = 0, queryString = [], chunks = [], self = this;
					var encode = function(str) {
						str = str + "";
						if ($spaces)
							str = str.replace(/ /g, "+");
						return encodeURIComponent(str);
					};
					var addFields = function(arr, key, value) {
						if (!is(value) || value === false)
							return;
						var o = [encode(key)];
						if (value !== true) {
							o.push("=");
							o.push(encode(value));
						}
						arr.push(o.join(""));
					};
					var build = function(obj, base) {
						var newKey = function(key) {
							return !base || base == "" ? [key].join("") : [base, "[", key, "]"].join("");
						};
						jQuery.each(obj, function(key, value) {
							if (typeof value == 'object')
								build(value, newKey(key));
							else
								addFields(chunks, newKey(key), value);
						});
					};

					build(this.keys);

					if (chunks.length > 0)
						queryString.push($hash);
					queryString.push(chunks.join($separator));

					return queryString.join("");
				}
			};

			return new queryObject(location.search, location.hash);
		};
	}(jQuery.query || {}); // Pass in jQuery.query as settings object
}).call(this);

function strtr(e, t, n) {
	if (typeof t === "object") {
		var r = "";
		for (var i = 0; i < e.length; i++) {
			r += "0"
		}
		var s = 0;
		var o = -1;
		var u = "";
		for (fr in t) {
			s = 0;
			while ((o = e.indexOf(fr, s)) != -1) {
				if (parseInt(r.substr(o, fr.length)) != 0) {
					s = o + 1;
					continue
				}
				for (var a = 0; a < t[fr].length; a++) {
					u += "1"
				}
				r = r.substr(0, o) + u + r.substr(o + fr.length, r.length - (o + fr.length));
				e = e.substr(0, o) + t[fr] + e.substr(o + fr.length, e.length - (o + fr.length));
				s = o + t[fr].length + 1;
				u = ""
			}
		}
		return e
	}
	for (var f = 0; f < t.length; f++) {
		e = e.replace(new RegExp(t.charAt(f), "g"), n.charAt(f))
	}
	return e
}

function __(e, t) {
	if (cms.translations[e] !== undefined) {
		var e = cms.translations[e]
	}
	return t == undefined ? e : strtr(e, t)
}

function readImage(input, target) {
	if (input.files && input.files[0] && target) {
		var FR = new FileReader();
		FR.onload = function(e) {
			var img = new Image();
			img.src = e.target.result;

			var ratio = img.width / img.height;

			var canvas = document.createElement("canvas");
			canvas.width = 100 * ratio;
			canvas.height = 100;

			var ctx = canvas.getContext("2d");
			ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
			target.attr("src", canvas.toDataURL("image/jpeg", 1));
		};
		FR.readAsDataURL(input.files[0]);
	}
}