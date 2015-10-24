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

!function( $ ) {

	var WinReszier = (function(){
		var registered = [];
		var inited = false;
		var timer;
		var resize = function(ev) {
			clearTimeout(timer);
			timer = setTimeout(notify, 100);
		};
		var notify = function() {
			for(var i=0, cnt=registered.length; i<cnt; i++) {
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
				for(var i=0, cnt=registered.length; i<cnt; i++) {
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
		this.dropdown = $('<li class="dropdown hide pull-right tabdrop"><a class="dropdown-toggle" data-toggle="dropdown" href="#">'+options.text+' <b class="caret"></b></a><ul class="dropdown-menu"></ul></li>')
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
			var paddingTop = this.element.css('padding-top').replace('px', '');

			this.dropdown.removeClass('hide');


			this.element
				.append(this.dropdown.find('li'))
				.find('>li')
				.not('.tabdrop')
				.each(function(){
					if (this.offsetTop > paddingTop) {
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

	$.fn.tabdrop = function ( option ) {
		return this.each(function () {
			var $this = $(this),
				data = $this.data('tabdrop'),
				options = typeof option === 'object' && option;
			if (!data)  {
				$this.data('tabdrop', (data = new TabDrop(this, $.extend({}, $.fn.tabdrop.defaults,options))));
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

}( window.jQuery );

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
	$.fn.toggleCheck = function () {
		return this.each(function () {
			this.checked = !this.checked;
		})
	}
	$.fn.check = function () {
		return this.each(function () {
			this.checked = true
		})
	}
	$.fn.uncheck = function () {
		return this.each(function () {
			this.checked = false
		})
	};
	$.fn.checked = function () {
		return this.prop("checked")
	}

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
