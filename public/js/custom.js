/*!
* screenfull
* v4.2.0 - 2019-04-01
* (c) Sindre Sorhus; MIT License
*/
(function () {
	'use strict';

	var document = typeof window !== 'undefined' && typeof window.document !== 'undefined' ? window.document : {};
	var isCommonjs = typeof module !== 'undefined' && module.exports;
	var keyboardAllowed = typeof Element !== 'undefined' && 'ALLOW_KEYBOARD_INPUT' in Element;

	var fn = (function () {
		var val;

		var fnMap = [
			[
				'requestFullscreen',
				'exitFullscreen',
				'fullscreenElement',
				'fullscreenEnabled',
				'fullscreenchange',
				'fullscreenerror'
			],
			// New WebKit
			[
				'webkitRequestFullscreen',
				'webkitExitFullscreen',
				'webkitFullscreenElement',
				'webkitFullscreenEnabled',
				'webkitfullscreenchange',
				'webkitfullscreenerror'

			],
			// Old WebKit (Safari 5.1)
			[
				'webkitRequestFullScreen',
				'webkitCancelFullScreen',
				'webkitCurrentFullScreenElement',
				'webkitCancelFullScreen',
				'webkitfullscreenchange',
				'webkitfullscreenerror'

			],
			[
				'mozRequestFullScreen',
				'mozCancelFullScreen',
				'mozFullScreenElement',
				'mozFullScreenEnabled',
				'mozfullscreenchange',
				'mozfullscreenerror'
			],
			[
				'msRequestFullscreen',
				'msExitFullscreen',
				'msFullscreenElement',
				'msFullscreenEnabled',
				'MSFullscreenChange',
				'MSFullscreenError'
			]
		];

		var i = 0;
		var l = fnMap.length;
		var ret = {};

		for (; i < l; i++) {
			val = fnMap[i];
			if (val && val[1] in document) {
				for (i = 0; i < val.length; i++) {
					ret[fnMap[0][i]] = val[i];
				}
				return ret;
			}
		}

		return false;
	})();

	var eventNameMap = {
		change: fn.fullscreenchange,
		error: fn.fullscreenerror
	};

	var screenfull = {
		request: function (elem) {
			return new Promise(function (resolve) {
				var request = fn.requestFullscreen;

				var onFullScreenEntered = function () {
					this.off('change', onFullScreenEntered);
					resolve();
				}.bind(this);

				elem = elem || document.documentElement;

				// Work around Safari 5.1 bug: reports support for
				// keyboard in fullscreen even though it doesn't.
				// Browser sniffing, since the alternative with
				// setTimeout is even worse.
				if (/ Version\/5\.1(?:\.\d+)? Safari\//.test(navigator.userAgent)) {
					elem[request]();
				} else {
					elem[request](keyboardAllowed ? Element.ALLOW_KEYBOARD_INPUT : {});
				}

				this.on('change', onFullScreenEntered);
			}.bind(this));
		},
		exit: function () {
			return new Promise(function (resolve) {
				if (!this.isFullscreen) {
					resolve();
					return;
				}

				var onFullScreenExit = function () {
					this.off('change', onFullScreenExit);
					resolve();
				}.bind(this);

				document[fn.exitFullscreen]();

				this.on('change', onFullScreenExit);
			}.bind(this));
		},
		toggle: function (elem) {
			return this.isFullscreen ? this.exit() : this.request(elem);
		},
		onchange: function (callback) {
			this.on('change', callback);
		},
		onerror: function (callback) {
			this.on('error', callback);
		},
		on: function (event, callback) {
			var eventName = eventNameMap[event];
			if (eventName) {
				document.addEventListener(eventName, callback, false);
			}
		},
		off: function (event, callback) {
			var eventName = eventNameMap[event];
			if (eventName) {
				document.removeEventListener(eventName, callback, false);
			}
		},
		raw: fn
	};

	if (!fn) {
		if (isCommonjs) {
			module.exports = false;
		} else {
			window.screenfull = false;
		}

		return;
	}

	Object.defineProperties(screenfull, {
		isFullscreen: {
			get: function () {
				return Boolean(document[fn.fullscreenElement]);
			}
		},
		element: {
			enumerable: true,
			get: function () {
				return document[fn.fullscreenElement];
			}
		},
		enabled: {
			enumerable: true,
			get: function () {
				// Coerce to boolean in case of old WebKit
				return Boolean(document[fn.fullscreenEnabled]);
			}
		}
	});

	if (isCommonjs) {
		module.exports = screenfull;
		// TODO: remove this in the next major version
		module.exports.default = screenfull;
	} else {
		window.screenfull = screenfull;
	}
})();

(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define("/Component", ["exports", "jquery"], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require("jquery"));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery);
    global.Component = mod.exports;
  }
})(this, function (_exports, _jquery) {
  "use strict";

  Object.defineProperty(_exports, "__esModule", {
    value: true
  });
  _exports.default = void 0;
  _jquery = babelHelpers.interopRequireDefault(_jquery);

  if (typeof Object.assign === 'undefined') {
    Object.assign = _jquery.default.extend;
  }

  var Component =
  /*#__PURE__*/
  function () {
    function Component() {
      var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
      babelHelpers.classCallCheck(this, Component);
      this.$el = options.$el ? options.$el : (0, _jquery.default)(document);
      this.el = this.$el[0];
      delete options.$el;
      this.options = options;
      this.isProcessed = false;
    }

    babelHelpers.createClass(Component, [{
      key: "initialize",
      value: function initialize() {// Initialize the Component
      }
    }, {
      key: "process",
      value: function process() {// Bind the Event on the Component
      }
    }, {
      key: "run",
      value: function run() {
        // run Component
        if (!this.isProcessed) {
          this.initialize();
          this.process();
        }

        this.isProcessed = true;
      }
    }, {
      key: "triggerResize",
      value: function triggerResize() {
        if (document.createEvent) {
          var ev = document.createEvent('Event');
          ev.initEvent('resize', true, true);
          window.dispatchEvent(ev);
        } else {
          element = document.documentElement;
          var event = document.createEventObject();
          element.fireEvent('onresize', event);
        }
      }
    }]);
    return Component;
  }();

  var _default = Component;
  _exports.default = _default;
});
(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define("/Plugin", ["exports", "jquery"], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require("jquery"));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery);
    global.Plugin = mod.exports;
  }
})(this, function (_exports, _jquery) {
  "use strict";

  Object.defineProperty(_exports, "__esModule", {
    value: true
  });
  _exports.getPluginAPI = getPluginAPI;
  _exports.getPlugin = getPlugin;
  _exports.getDefaults = getDefaults;
  _exports.pluginFactory = pluginFactory;
  _exports.default = _exports.Plugin = void 0;
  _jquery = babelHelpers.interopRequireDefault(_jquery);
  var plugins = {};
  var apis = {};

  var Plugin =
  /*#__PURE__*/
  function () {
    function Plugin($el) {
      var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
      babelHelpers.classCallCheck(this, Plugin);
      this.name = this.getName();
      this.$el = $el;
      this.options = options;
      this.isRendered = false;
    }

    babelHelpers.createClass(Plugin, [{
      key: "getName",
      value: function getName() {
        return 'plugin';
      }
    }, {
      key: "render",
      value: function render() {
        if (_jquery.default.fn[this.name]) {
          this.$el[this.name](this.options);
        } else {
          return false;
        }
      }
    }, {
      key: "initialize",
      value: function initialize() {
        if (this.isRendered) {
          return false;
        }

        this.render();
        this.isRendered = true;
      }
    }], [{
      key: "getDefaults",
      value: function getDefaults() {
        return {};
      }
    }, {
      key: "register",
      value: function register(name, obj) {
        if (typeof obj === 'undefined') {
          return;
        }

        plugins[name] = obj;

        if (typeof obj.api !== 'undefined') {
          Plugin.registerApi(name, obj);
        }
      }
    }, {
      key: "registerApi",
      value: function registerApi(name, obj) {
        var api = obj.api();

        if (typeof api === 'string') {
          var _api = obj.api().split('|');

          var event = "".concat(_api[0], ".plugin.").concat(name);
          var func = _api[1] || 'render';

          var callback = function callback(e) {
            var $el = (0, _jquery.default)(this);
            var plugin = $el.data('pluginInstance');

            if (!plugin) {
              plugin = new obj($el, _jquery.default.extend(true, {}, getDefaults(name), $el.data()));
              plugin.initialize();
              $el.data('pluginInstance', plugin);
            }

            plugin[func](e);
          };

          apis[name] = function (selector, context) {
            if (context) {
              (0, _jquery.default)(context).off(event);
              (0, _jquery.default)(context).on(event, selector, callback);
            } else {
              (0, _jquery.default)(selector).on(event, callback);
            }
          };
        } else if (typeof api === 'function') {
          apis[name] = api;
        }
      }
    }]);
    return Plugin;
  }();

  _exports.Plugin = Plugin;

  function getPluginAPI(name) {
    if (typeof name === 'undefined') {
      return apis;
    }

    return apis[name];
  }

  function getPlugin(name) {
    if (typeof plugins[name] !== 'undefined') {
      return plugins[name];
    }

    console.warn("Plugin:".concat(name, " has no warpped class."));
    return false;
  }

  function getDefaults(name) {
    var PluginClass = getPlugin(name);

    if (PluginClass) {
      return PluginClass.getDefaults();
    }

    return {};
  }

  function pluginFactory(name, $el) {
    var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
    var PluginClass = getPlugin(name);

    if (PluginClass && typeof PluginClass.api === 'undefined') {
      return new PluginClass($el, _jquery.default.extend(true, {}, getDefaults(name), options));
    } else if (_jquery.default.fn[name]) {
      var plugin = new Plugin($el, options);

      plugin.getName = function () {
        return name;
      };

      plugin.name = name;
      return plugin;
    } else if (typeof PluginClass.api !== 'undefined') {
      // console.log('Plugin:' + name + ' use api render.');
      return false;
    }

    console.warn("Plugin:".concat(name, " script is not loaded."));
    return false;
  }

  var _default = Plugin;
  _exports.default = _default;
});
(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define("/Base", ["exports", "jquery", "Component", "Plugin"], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require("jquery"), require("Component"), require("Plugin"));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Component, global.Plugin);
    global.Base = mod.exports;
  }
})(this, function (_exports, _jquery, _Component2, _Plugin) {
  "use strict";

  Object.defineProperty(_exports, "__esModule", {
    value: true
  });
  _exports.default = void 0;
  _jquery = babelHelpers.interopRequireDefault(_jquery);
  _Component2 = babelHelpers.interopRequireDefault(_Component2);

  var Base =
  /*#__PURE__*/
  function (_Component) {
    babelHelpers.inherits(Base, _Component);

    function Base() {
      babelHelpers.classCallCheck(this, Base);
      return babelHelpers.possibleConstructorReturn(this, babelHelpers.getPrototypeOf(Base).apply(this, arguments));
    }

    babelHelpers.createClass(Base, [{
      key: "initializePlugins",
      value: function initializePlugins() {
        var context = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
        (0, _jquery.default)('[data-plugin]', context || this.$el).each(function () {
          var $this = (0, _jquery.default)(this);
          var name = $this.data('plugin');
          var plugin = (0, _Plugin.pluginFactory)(name, $this, $this.data());

          if (plugin) {
            plugin.initialize();
          }
        });
      }
    }, {
      key: "initializePluginAPIs",
      value: function initializePluginAPIs() {
        var context = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : document;
        var apis = (0, _Plugin.getPluginAPI)();

        for (var name in apis) {
          (0, _Plugin.getPluginAPI)(name)("[data-plugin=".concat(name, "]"), context);
        }
      }
    }]);
    return Base;
  }(_Component2.default);

  var _default = Base;
  _exports.default = _default;
});
(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define("/Config", ["exports"], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports);
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports);
    global.Config = mod.exports;
  }
})(this, function (_exports) {
  "use strict";

  Object.defineProperty(_exports, "__esModule", {
    value: true
  });
  _exports.get = get;
  _exports.set = set;
  _exports.getColor = getColor;
  _exports.colors = colors;
  var values = {
    fontFamily: 'Noto Sans, sans-serif',
    primaryColor: 'blue',
    assets: '../assets'
  };

  function get() {
    var data = values;

    var callback = function callback(data, name) {
      return data[name];
    };

    for (var i = 0; i < arguments.length; i++) {
      var name = i < 0 || arguments.length <= i ? undefined : arguments[i];
      data = callback(data, name);
    }

    return data;
  }

  function set(name, value) {
    if (typeof name === 'string' && typeof value !== 'undefined') {
      values[name] = value;
    } else if (babelHelpers.typeof(name) === 'object') {
      values = $.extend(true, {}, values, name);
    }
  }

  function getColor(name, level) {
    if (name === 'primary') {
      name = get('primaryColor');

      if (!name) {
        name = 'red';
      }
    }

    if (typeof values.colors === 'undefined') {
      return null;
    }

    if (typeof values.colors[name] !== 'undefined') {
      if (level && typeof values.colors[name][level] !== 'undefined') {
        return values.colors[name][level];
      }

      if (typeof level === 'undefined') {
        return values.colors[name];
      }
    }

    return null;
  }

  function colors(name, level) {
    return getColor(name, level);
  }
});
(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define("/Section/Menubar", ["exports", "jquery", "Component"], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require("jquery"), require("Component"));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Component);
    global.SectionMenubar = mod.exports;
  }
})(this, function (_exports, _jquery, _Component2) {
  "use strict";

  Object.defineProperty(_exports, "__esModule", {
    value: true
  });
  _exports.default = void 0;
  _jquery = babelHelpers.interopRequireDefault(_jquery);
  _Component2 = babelHelpers.interopRequireDefault(_Component2);
  var $BODY = (0, _jquery.default)('body');
  var $HTML = (0, _jquery.default)('html');

  var Scrollable =
  /*#__PURE__*/
  function () {
    function Scrollable($el) {
      babelHelpers.classCallCheck(this, Scrollable);
      this.$el = $el;
      this.native = false;
      this.api = null;
      this.init();
    }

    babelHelpers.createClass(Scrollable, [{
      key: "init",
      value: function init() {
        if ($BODY.is('.site-menubar-native')) {
          this.native = true;
          return;
        }

        this.api = this.$el.asScrollable({
          namespace: 'scrollable',
          skin: 'scrollable-inverse',
          direction: 'vertical',
          contentSelector: '>',
          containerSelector: '>'
        }).data('asScrollable');
      }
    }, {
      key: "update",
      value: function update() {
        if (this.api) {
          this.api.update();
        }
      }
    }, {
      key: "enable",
      value: function enable() {
        if (this.native) {
          return;
        }

        if (!this.api) {
          this.init();
        }

        if (this.api) {
          this.api.enable();
        }
      }
    }, {
      key: "disable",
      value: function disable() {
        if (this.api) {
          this.api.disable();
        }
      }
    }]);
    return Scrollable;
  }();

  var Hoverscroll =
  /*#__PURE__*/
  function () {
    function Hoverscroll($el) {
      babelHelpers.classCallCheck(this, Hoverscroll);
      this.$el = $el;
      this.api = null;
      this.init();
    }

    babelHelpers.createClass(Hoverscroll, [{
      key: "init",
      value: function init() {
        this.api = this.$el.asHoverScroll({
          namespace: 'hoverscorll',
          direction: 'vertical',
          list: '.site-menu',
          item: '> li',
          exception: '.site-menu-sub',
          fixed: false,
          boundary: 100,
          onEnter: function onEnter() {// $(this).siblings().removeClass('hover'); $(this).addClass('hover');
          },
          onLeave: function onLeave() {// $(this).removeClass('hover');
          }
        }).data('asHoverScroll');
      }
    }, {
      key: "update",
      value: function update() {
        if (this.api) {
          this.api.update();
        }
      }
    }, {
      key: "enable",
      value: function enable() {
        if (!this.api) {
          this.init();
        }

        if (this.api) {
          this.api.enable();
        }
      }
    }, {
      key: "disable",
      value: function disable() {
        if (this.api) {
          this.api.disable();
        }
      }
    }]);
    return Hoverscroll;
  }();

  var Menubar =
  /*#__PURE__*/
  function (_Component) {
    babelHelpers.inherits(Menubar, _Component);

    function Menubar() {
      var _babelHelpers$getProt;

      var _this;

      babelHelpers.classCallCheck(this, Menubar);

      for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
        args[_key] = arguments[_key];
      }

      _this = babelHelpers.possibleConstructorReturn(this, (_babelHelpers$getProt = babelHelpers.getPrototypeOf(Menubar)).call.apply(_babelHelpers$getProt, [this].concat(args)));
      _this.top = false;
      _this.folded = false;
      _this.foldAlt = false;
      _this.$menuBody = _this.$el.children('.site-menubar-body');
      _this.$menu = _this.$el.find('[data-plugin=menu]');

      if ($BODY.data('autoMenubar') === false || $BODY.is('.site-menubar-keep')) {
        if ($BODY.hasClass('site-menubar-fold')) {
          _this.auto = 'fold';
        } else if ($BODY.hasClass('site-menubar-unfold')) {
          _this.auto = 'unfold';
        }
      } else {
        _this.auto = true;
      }

      var breakpoint = Breakpoints.current();

      if (_this.auto === true) {
        if (breakpoint) {
          switch (breakpoint.name) {
            case 'lg':
              _this.type = 'unfold';
              break;

            case 'md':
            case 'sm':
              _this.type = 'fold';
              break;

            case 'xs':
              _this.type = 'hide';
              break;
          }
        }
      } else {
        switch (_this.auto) {
          case 'fold':
            if (breakpoint.name === 'xs') {
              _this.type = 'hide';
            } else {
              _this.type = 'fold';
            }

            break;

          case 'unfold':
            if (breakpoint.name === 'xs') {
              _this.type = 'hide';
            } else {
              _this.type = 'unfold';
            }

            break;
        }
      }

      return _this;
    }

    babelHelpers.createClass(Menubar, [{
      key: "initialize",
      value: function initialize() {
        if (this.$menuBody.length > 0) {
          this.initialized = true;
        } else {
          this.initialized = false;
          return;
        }

        this.scrollable = new Scrollable(this.$menuBody);
        this.hoverscroll = new Hoverscroll(this.$menuBody);
        $HTML.removeClass('css-menubar').addClass('js-menubar');

        if ($BODY.is('.site-menubar-top')) {
          this.top = true;
        }

        if ($BODY.is('.site-menubar-fold-alt')) {
          this.foldAlt = true;
        }

        this.change(this.type);
      }
    }, {
      key: "process",
      value: function process() {
        (0, _jquery.default)('.site-menu-sub').on('touchstart', function (e) {
          e.stopPropagation();
        }).on('ponitstart', function (e) {
          e.stopPropagation();
        });
      }
    }, {
      key: "getMenuApi",
      value: function getMenuApi() {
        return this.$menu.data('menuApi');
      }
    }, {
      key: "setMenuData",
      value: function setMenuData() {
        var api = this.getMenuApi();

        if (api) {
          api.folded = this.folded;
          api.foldAlt = this.foldAlt;
          api.outerHeight = this.$el.outerHeight();
        }
      }
    }, {
      key: "update",
      value: function update() {
        this.scrollable.update();
        this.hoverscroll.update();
      }
    }, {
      key: "change",
      value: function change(type) {
        if (this.initialized) {
          this.reset();
          this[type]();
          this.setMenuData();
        }
      }
    }, {
      key: "animate",
      value: function animate(doing) {
        var _this2 = this;

        var callback = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : function () {};
        $BODY.addClass('site-menubar-changing');
        doing.call(this);
        this.$el.trigger('changing.site.menubar');
        var menuApi = this.getMenuApi();

        if (menuApi) {
          menuApi.refresh();
        }

        setTimeout(function () {
          callback.call(_this2);
          $BODY.removeClass('site-menubar-changing');

          _this2.update();

          _this2.$el.trigger('changed.site.menubar');
        }, 500);
      }
    }, {
      key: "reset",
      value: function reset() {
        $BODY.removeClass('site-menubar-hide site-menubar-open site-menubar-fold site-menubar-unfold');
        $HTML.removeClass('disable-scrolling');
      }
    }, {
      key: "open",
      value: function open() {
        this.animate(function () {
          $BODY.addClass('site-menubar-open site-menubar-unfold');
          $HTML.addClass('disable-scrolling');
        }, function () {
          this.scrollable.enable();
        });
        this.type = 'open';
      }
    }, {
      key: "hide",
      value: function hide() {
        this.hoverscroll.disable();
        this.animate(function () {
          $BODY.addClass('site-menubar-hide site-menubar-unfold');
        }, function () {
          this.scrollable.enable();
        });
        this.type = 'hide';
      }
    }, {
      key: "unfold",
      value: function unfold() {
        this.hoverscroll.disable();
        this.animate(function () {
          $BODY.addClass('site-menubar-unfold');
          this.folded = false;
        }, function () {
          this.scrollable.enable();
          this.triggerResize();
        });
        this.type = 'unfold';
      }
    }, {
      key: "fold",
      value: function fold() {
        this.scrollable.disable();
        this.animate(function () {
          $BODY.addClass('site-menubar-fold');
          this.folded = true;
        }, function () {
          this.hoverscroll.enable();
          this.triggerResize();
        });
        this.type = 'fold';
      }
    }]);
    return Menubar;
  }(_Component2.default);

  var _default = Menubar;
  _exports.default = _default;
});
(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define("/Section/GridMenu", ["exports", "jquery", "Component"], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require("jquery"), require("Component"));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Component);
    global.SectionGridMenu = mod.exports;
  }
})(this, function (_exports, _jquery, _Component2) {
  "use strict";

  Object.defineProperty(_exports, "__esModule", {
    value: true
  });
  _exports.default = void 0;
  _jquery = babelHelpers.interopRequireDefault(_jquery);
  _Component2 = babelHelpers.interopRequireDefault(_Component2);
  var $BODY = (0, _jquery.default)('body');
  var $HTML = (0, _jquery.default)('html');

  var Scrollable =
  /*#__PURE__*/
  function () {
    function Scrollable($el) {
      babelHelpers.classCallCheck(this, Scrollable);
      this.$el = $el;
      this.api = null;
      this.init();
    }

    babelHelpers.createClass(Scrollable, [{
      key: "init",
      value: function init() {
        this.api = this.$el.asScrollable({
          namespace: 'scrollable',
          skin: 'scrollable-inverse',
          direction: 'vertical',
          contentSelector: '>',
          containerSelector: '>'
        }).data('asScrollable');
      }
    }, {
      key: "update",
      value: function update() {
        if (this.api) {
          this.api.update();
        }
      }
    }, {
      key: "enable",
      value: function enable() {
        if (!this.api) {
          this.init();
        }

        if (this.api) {
          this.api.enable();
        }
      }
    }, {
      key: "disable",
      value: function disable() {
        if (this.api) {
          this.api.disable();
        }
      }
    }]);
    return Scrollable;
  }();

  var GridMenu =
  /*#__PURE__*/
  function (_Component) {
    babelHelpers.inherits(GridMenu, _Component);

    function GridMenu() {
      var _babelHelpers$getProt;

      var _this;

      babelHelpers.classCallCheck(this, GridMenu);

      for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
        args[_key] = arguments[_key];
      }

      _this = babelHelpers.possibleConstructorReturn(this, (_babelHelpers$getProt = babelHelpers.getPrototypeOf(GridMenu)).call.apply(_babelHelpers$getProt, [this].concat(args)));
      _this.isOpened = false;
      _this.scrollable = new Scrollable(_this.$el);
      return _this;
    }

    babelHelpers.createClass(GridMenu, [{
      key: "open",
      value: function open() {
        this.animate(function () {
          this.$el.addClass('active');
          (0, _jquery.default)('[data-toggle="gridmenu"]').addClass('active').attr('aria-expanded', true);
          $BODY.addClass('site-gridmenu-active');
          $HTML.addClass('disable-scrolling');
        }, function () {
          this.scrollable.enable();
        });
        this.isOpened = true;
      }
    }, {
      key: "close",
      value: function close() {
        this.animate(function () {
          this.$el.removeClass('active');
          (0, _jquery.default)('[data-toggle="gridmenu"]').addClass('active').attr('aria-expanded', true);
          $BODY.removeClass('site-gridmenu-active');
          $HTML.removeClass('disable-scrolling');
        }, function () {
          this.scrollable.disable();
        });
        this.isOpened = false;
      }
    }, {
      key: "toggle",
      value: function toggle(opened) {
        if (opened) {
          this.open();
        } else {
          this.close();
        }
      }
    }, {
      key: "animate",
      value: function animate(doing, callback) {
        var _this2 = this;

        doing.call(this);
        this.$el.trigger('changing.site.gridmenu');
        setTimeout(function () {
          callback.call(_this2);

          _this2.$el.trigger('changed.site.gridmenu');
        }, 500);
      }
    }]);
    return GridMenu;
  }(_Component2.default);

  var _default = GridMenu;
  _exports.default = _default;
});
(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define("/Section/Sidebar", ["exports", "jquery", "Base", "Plugin"], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require("jquery"), require("Base"), require("Plugin"));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Base, global.Plugin);
    global.SectionSidebar = mod.exports;
  }
})(this, function (_exports, _jquery, _Base2, _Plugin) {
  "use strict";

  Object.defineProperty(_exports, "__esModule", {
    value: true
  });
  _exports.default = void 0;
  _jquery = babelHelpers.interopRequireDefault(_jquery);
  _Base2 = babelHelpers.interopRequireDefault(_Base2);

  var Sidebar =
  /*#__PURE__*/
  function (_Base) {
    babelHelpers.inherits(Sidebar, _Base);

    function Sidebar() {
      babelHelpers.classCallCheck(this, Sidebar);
      return babelHelpers.possibleConstructorReturn(this, babelHelpers.getPrototypeOf(Sidebar).apply(this, arguments));
    }

    babelHelpers.createClass(Sidebar, [{
      key: "process",
      value: function process() {
        if (typeof _jquery.default.slidePanel === 'undefined') {
          return;
        }

        var sidebar = this;
        (0, _jquery.default)(document).on('click', '[data-toggle="site-sidebar"]', function () {
          var $this = (0, _jquery.default)(this);
          var direction = 'right';

          if ((0, _jquery.default)('body').hasClass('site-menubar-flipped')) {
            direction = 'left';
          }

          var options = _jquery.default.extend({}, (0, _Plugin.getDefaults)('slidePanel'), {
            direction: direction,
            skin: 'site-sidebar',
            dragTolerance: 80,
            template: function template(options) {
              return "<div class=\"".concat(options.classes.base, " ").concat(options.classes.base, "-").concat(options.direction, "\">\n      <div class=\"").concat(options.classes.content, " site-sidebar-content\"></div>\n      <div class=\"slidePanel-handler\"></div>\n      </div>");
            },
            afterLoad: function afterLoad() {
              var self = this;
              this.$panel.find('.tab-pane').asScrollable({
                namespace: 'scrollable',
                contentSelector: '> div',
                containerSelector: '> div'
              });
              sidebar.initializePlugins(self.$panel);
              this.$panel.on('shown.bs.tab', function () {
                self.$panel.find('.tab-pane.active').asScrollable('update');
              });
            },
            beforeShow: function beforeShow() {
              if (!$this.hasClass('active')) {
                $this.addClass('active');
              }
            },
            afterHide: function afterHide() {
              if ($this.hasClass('active')) {
                $this.removeClass('active');
              }
            }
          });

          if ($this.hasClass('active')) {
            _jquery.default.slidePanel.hide();
          } else {
            var url = $this.data('url');

            if (!url) {
              url = $this.attr('href');
              url = url && url.replace(/.*(?=#[^\s]*$)/, '');
            }

            _jquery.default.slidePanel.show({
              url: url
            }, options);
          }
        });
        (0, _jquery.default)(document).on('click', '[data-toggle="show-chat"]', function () {
          (0, _jquery.default)('#conversation').addClass('active');
        });
        (0, _jquery.default)(document).on('click', '[data-toggle="close-chat"]', function () {
          (0, _jquery.default)('#conversation').removeClass('active');
        });
      }
    }]);
    return Sidebar;
  }(_Base2.default);

  var _default = Sidebar;
  _exports.default = _default;
});
(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define("/Section/PageAside", ["exports", "jquery", "Component"], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require("jquery"), require("Component"));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Component);
    global.SectionPageAside = mod.exports;
  }
})(this, function (_exports, _jquery, _Component2) {
  "use strict";

  Object.defineProperty(_exports, "__esModule", {
    value: true
  });
  _exports.default = void 0;
  _jquery = babelHelpers.interopRequireDefault(_jquery);
  _Component2 = babelHelpers.interopRequireDefault(_Component2);
  var $BODY = (0, _jquery.default)('body');

  var PageAside =
  /*#__PURE__*/
  function (_Component) {
    babelHelpers.inherits(PageAside, _Component);

    function PageAside() {
      var _babelHelpers$getProt;

      var _this;

      babelHelpers.classCallCheck(this, PageAside);

      for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
        args[_key] = arguments[_key];
      }

      _this = babelHelpers.possibleConstructorReturn(this, (_babelHelpers$getProt = babelHelpers.getPrototypeOf(PageAside)).call.apply(_babelHelpers$getProt, [this].concat(args)));
      _this.$scroll = _this.$el.find('.page-aside-scroll');
      _this.scrollable = _this.$scroll.asScrollable({
        namespace: 'scrollable',
        contentSelector: '> [data-role=\'content\']',
        containerSelector: '> [data-role=\'container\']'
      }).data('asScrollable');
      return _this;
    }

    babelHelpers.createClass(PageAside, [{
      key: "process",
      value: function process() {
        var _this2 = this;

        if ($BODY.is('.page-aside-fixed') || $BODY.is('.page-aside-scroll')) {
          this.$el.on('transitionend', function () {
            _this2.scrollable.update();
          });
        }

        Breakpoints.on('change', function () {
          var current = Breakpoints.current().name;

          if (!$BODY.is('.page-aside-fixed') && !$BODY.is('.page-aside-scroll')) {
            if (current === 'xs') {
              _this2.scrollable.enable();

              _this2.$el.on('transitionend', function () {
                _this2.scrollable.update();
              });
            } else {
              _this2.$el.off('transitionend');

              _this2.scrollable.update();
            }
          }
        });
        (0, _jquery.default)(document).on('click.pageAsideScroll', '.page-aside-switch', function () {
          var isOpen = _this2.$el.hasClass('open');

          if (isOpen) {
            _this2.$el.removeClass('open');
          } else {
            _this2.scrollable.update();

            _this2.$el.addClass('open');
          }
        });
        (0, _jquery.default)(document).on('click.pageAsideScroll', '[data-toggle="collapse"]', function (e) {
          var $trigger = (0, _jquery.default)(e.target);

          if (!$trigger.is('[data-toggle="collapse"]')) {
            $trigger = $trigger.parents('[data-toggle="collapse"]');
          }

          var href;
          var target = $trigger.attr('data-target') || (href = $trigger.attr('href')) && href.replace(/.*(?=#[^\s]+$)/, '');
          var $target = (0, _jquery.default)(target);

          if ($target.attr('id') === 'site-navbar-collapse') {
            _this2.scrollable.update();
          }
        });
      }
    }]);
    return PageAside;
  }(_Component2.default);

  var _default = PageAside;
  _exports.default = _default;
});
(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define("/Plugin/menu", ["exports", "Plugin"], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require("Plugin"));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginMenu = mod.exports;
  }
})(this, function (_exports, _Plugin2) {
  "use strict";

  Object.defineProperty(_exports, "__esModule", {
    value: true
  });
  _exports.default = void 0;
  _Plugin2 = babelHelpers.interopRequireDefault(_Plugin2);
  var NAME = 'menu';

  var Menu =
  /*#__PURE__*/
  function (_Plugin) {
    babelHelpers.inherits(Menu, _Plugin);

    function Menu() {
      var _babelHelpers$getProt;

      var _this;

      babelHelpers.classCallCheck(this, Menu);

      for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
        args[_key] = arguments[_key];
      }

      _this = babelHelpers.possibleConstructorReturn(this, (_babelHelpers$getProt = babelHelpers.getPrototypeOf(Menu)).call.apply(_babelHelpers$getProt, [this].concat(args)));
      _this.folded = true;
      _this.foldAlt = true;
      _this.outerHeight = 0;
      return _this;
    }

    babelHelpers.createClass(Menu, [{
      key: "getName",
      value: function getName() {
        return NAME;
      }
    }, {
      key: "render",
      value: function render() {
        this.bindEvents();
        this.$el.data('menuApi', this);
      }
    }, {
      key: "bindEvents",
      value: function bindEvents() {
        var self = this;
        this.$el.on('mouseenter.site.menu', '.site-menu-item', function () {
          var $item = $(this);

          if (self.folded === true && $item.is('.has-sub') && $item.parent('.site-menu').length > 0) {
            var $sub = $item.children('.site-menu-sub');
            self.position($item, $sub);
          }

          $item.addClass('hover');
        }).on('mouseleave.site.menu', '.site-menu-item', function () {
          var $item = $(this);

          if (self.folded === true && $item.is('.has-sub') && $item.parent('.site-menu').length > 0) {
            $item.children('.site-menu-sub').css('max-height', '');
            $item.removeClass('open');
          }

          $item.removeClass('hover');
        }).on('deactive.site.menu', '.site-menu-item.active', function (e) {
          $(this).removeClass('active');
          e.stopPropagation();
        }).on('active.site.menu', '.site-menu-item', function (e) {
          $(this).addClass('active');
          e.stopPropagation();
        }).on('open.site.menu', '.site-menu-item', function (e) {
          var $item = $(this);
          self.expand($item, function () {
            $item.addClass('open');
          });

          if (self.options.accordion) {
            $item.siblings('.open').trigger('close.site.menu');
          }

          e.stopPropagation();
        }).on('close.site.menu', '.site-menu-item.open', function (e) {
          var $item = $(this);
          self.collapse($item, function () {
            $item.removeClass('open');
          });
          e.stopPropagation();
        }).on('click.site.menu ', '.site-menu-item', function (e) {
          var $item = $(this);

          if ($item.is('.has-sub') && $(e.target).closest('.site-menu-item').is(this)) {
            if ($item.is('.open')) {
              $item.trigger('close.site.menu');
            } else {
              $item.trigger('open.site.menu');
            }
          } else if (!$item.is('.active')) {
            $item.siblings('.active').trigger('deactive.site.menu');
            $item.trigger('active.site.menu');
          }

          e.stopPropagation();
        }).on('tap.site.menu', '> .site-menu-item > a', function () {
          var link = $(this).attr('href');

          if (link) {
            window.location = link;
          }
        }).on('touchend.site.menu', '> .site-menu-item > a', function () {
          var $item = $(this).parent('.site-menu-item');

          if (self.folded === true) {
            if ($item.is('.has-sub') && $item.parent('.site-menu').length > 0) {
              $item.siblings('.hover').removeClass('hover');

              if ($item.is('.hover')) {
                $item.removeClass('hover');
              } else {
                $item.addClass('hover');
              }
            }
          }
        }).on('scroll.site.menu', '.site-menu-sub', function (e) {
          e.stopPropagation();
        });
      }
    }, {
      key: "collapse",
      value: function collapse($item, callback) {
        var self = this;
        var $sub = $item.children('.site-menu-sub');
        $sub.show().slideUp(this.options.speed, function () {
          $(this).css('display', '');
          $(this).find('> .site-menu-item').removeClass('is-shown');

          if (callback) {
            callback();
          }

          self.$el.trigger('collapsed.site.menu');
        });
      }
    }, {
      key: "expand",
      value: function expand($item, callback) {
        var self = this;
        var $sub = $item.children('.site-menu-sub');
        var $children = $sub.children('.site-menu-item').addClass('is-hidden');
        $sub.hide().slideDown(this.options.speed, function () {
          $(this).css('display', '');

          if (callback) {
            callback();
          }

          self.$el.trigger('expanded.site.menu');
        });
        setTimeout(function () {
          $children.addClass('is-shown');
          $children.removeClass('is-hidden');
        }, 0);
      }
    }, {
      key: "refresh",
      value: function refresh() {
        this.$el.find('.open').filter(':not(.active)').removeClass('open');
      }
    }, {
      key: "position",
      value: function position($item, $dropdown) {
        var itemHeight = $item.find('> a').outerHeight();
        var menubarHeight = this.outerHeight;
        var offsetTop = $item.position().top;
        $dropdown.removeClass('site-menu-sub-up').css('max-height', '');

        if (offsetTop > menubarHeight / 2) {
          $dropdown.addClass('site-menu-sub-up');

          if (this.foldAlt) {
            offsetTop -= itemHeight;
          }

          $dropdown.css('max-height', offsetTop + itemHeight);
        } else {
          if (this.foldAlt) {
            offsetTop += itemHeight;
          }

          $dropdown.removeClass('site-menu-sub-up');
          $dropdown.css('max-height', menubarHeight - offsetTop);
        }
      }
    }], [{
      key: "getDefaults",
      value: function getDefaults() {
        return {
          speed: 250,
          accordion: true
        };
      }
    }]);
    return Menu;
  }(_Plugin2.default);

  _Plugin2.default.register(NAME, Menu);

  var _default = Menu;
  _exports.default = _default;
});
(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define("/config/colors", ["Config"], factory);
  } else if (typeof exports !== "undefined") {
    factory(require("Config"));
  } else {
    var mod = {
      exports: {}
    };
    factory(global.Config);
    global.configColors = mod.exports;
  }
})(this, function (_Config) {
  "use strict";

  (0, _Config.set)('colors', {
    red: {
      100: '#ffdbdc',
      200: '#ffbfc1',
      300: '#ffbfc1',
      400: '#ff8589',
      500: '#ff666b',
      600: '#ff4c52',
      700: '#f2353c',
      800: '#e62020',
      900: '#d60b0b'
    },
    pink: {
      100: '#ffd9e6',
      200: '#ffbad2',
      300: '#ff9ec0',
      400: '#ff7daa',
      500: '#ff5e97',
      600: '#f74584',
      700: '#eb2f71',
      800: '#e6155e',
      900: '#d10049'
    },
    purple: {
      100: '#eae1fc',
      200: '#d9c7fc',
      300: '#c8aefc',
      400: '#b693fa',
      500: '#a57afa',
      600: '#9463f7',
      700: '#8349f5',
      800: '#7231f5',
      900: '#6118f2'
    },
    indigo: {
      100: '#e1e4fc',
      200: '#c7cffc',
      300: '#afb9fa',
      400: '#96a3fa',
      500: '#7d8efa',
      600: '#667afa',
      700: '#4d64fa',
      800: '#364ff5',
      900: '#1f3aed'
    },
    blue: {
      100: '#d9e9ff',
      200: '#b8d7ff',
      300: '#99c5ff',
      400: '#79b2fc',
      500: '#589ffc',
      600: '#3e8ef7',
      700: '#247cf0',
      800: '#0b69e3',
      900: '#0053bf'
    },
    cyan: {
      100: '#c2f5ff',
      200: '#9de6f5',
      300: '#77d9ed',
      400: '#54cbe3',
      500: '#28c0de',
      600: '#0bb2d4',
      700: '#0099b8',
      800: '#007d96',
      900: '#006275'
    },
    teal: {
      100: '#c3f7f2',
      200: '#92f0e6',
      300: '#6be3d7',
      400: '#45d6c8',
      500: '#28c7b7',
      600: '#17b3a3',
      700: '#089e8f',
      800: '#008577',
      900: '#00665c'
    },
    green: {
      100: '#c2fadc',
      200: '#99f2c2',
      300: '#72e8ab',
      400: '#49de94',
      500: '#28d17c',
      600: '#11c26d',
      700: '#05a85c',
      800: '#008c4d',
      900: '#006e3c'
    },
    'light-green': {
      100: '#dcf7b0',
      200: '#c3e887',
      300: '#add966',
      400: '#94cc39',
      500: '#7eb524',
      600: '#6da611',
      700: '#5a9101',
      800: '#4a7800',
      900: '#3a5e00'
    },
    yellow: {
      100: '#fff6b5',
      200: '#fff39c',
      300: '#ffed78',
      400: '#ffe54f',
      500: '#ffdc2e',
      600: '#ffcd17',
      700: '#fcb900',
      800: '#faa700',
      900: '#fa9600'
    },
    orange: {
      100: '#ffe1c4',
      200: '#ffc894',
      300: '#fab06b',
      400: '#fa983c',
      500: '#f57d1b',
      600: '#eb6709',
      700: '#de4e00',
      800: '#b53f00',
      900: '#962d00'
    },
    brown: {
      100: '#f5e2da',
      200: '#e0cdc5',
      300: '#cfb8b0',
      400: '#bda299',
      500: '#ab8c82',
      600: '#997b71',
      700: '#82675f',
      800: '#6b534c',
      900: '#57403a'
    },
    grey: {
      100: '#fafafa',
      200: '#eeeeee',
      300: '#e0e0e0',
      400: '#bdbdbd',
      500: '#9e9e9e',
      600: '#757575',
      700: '#616161',
      800: '#424242'
    },
    'blue-grey': {
      100: '#f3f7f9',
      200: '#e4eaec',
      300: '#ccd5db',
      400: '#a3afb7',
      500: '#76838f',
      600: '#526069',
      700: '#37474f',
      800: '#263238'
    }
  });
});
(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define("/config/tour", ["Config"], factory);
  } else if (typeof exports !== "undefined") {
    factory(require("Config"));
  } else {
    var mod = {
      exports: {}
    };
    factory(global.Config);
    global.configTour = mod.exports;
  }
})(this, function (_Config) {
  "use strict";

  (0, _Config.set)('tour', {
    steps: [{
      element: '#toggleMenubar',
      position: 'right',
      intro: 'Offcanvas Menu <p class=\'content\'>It is nice custom navigation for desktop users and a seek off-canvas menu for tablet and mobile users</p>'
    }, {
      element: '#toggleFullscreen',
      intro: 'Full Screen <p class=\'content\'>Click this button you can view the admin template in full screen</p>'
    }, {
      element: '#toggleChat',
      position: 'left',
      intro: 'Quick Conversations <p class=\'content\'>This is a sidebar dialog box for user conversations list, you can even create a quick conversation with other users</p>'
    }],
    skipLabel: '<i class=\'wb-close\'></i>',
    doneLabel: '<i class=\'wb-close\'></i>',
    nextLabel: 'Next <i class=\'wb-chevron-right-mini\'></i>',
    prevLabel: '<i class=\'wb-chevron-left-mini\'></i>Prev',
    showBullets: false
  });
});
/**
 * breakpoints-js v1.0.6
 * https://github.com/amazingSurge/breakpoints-js
 *
 * Copyright (c) amazingSurge
 * Released under the LGPL-3.0 license
 */
(function(global, factory) {
  if (typeof define === 'function' && define.amd) {
    define(['exports'], factory);
  } else if (typeof exports !== 'undefined') {
    factory(exports);
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports);
    global.breakpointsEs = mod.exports;
  }
})(this, function(exports) {
  'use strict';

  Object.defineProperty(exports, '__esModule', {
    value: true
  });

  function _possibleConstructorReturn(self, call) {
    if (!self) {
      throw new ReferenceError(
        "this hasn't been initialised - super() hasn't been called"
      );
    }

    return call && (typeof call === 'object' || typeof call === 'function')
      ? call
      : self;
  }

  function _inherits(subClass, superClass) {
    if (typeof superClass !== 'function' && superClass !== null) {
      throw new TypeError(
        'Super expression must either be null or a function, not ' +
          typeof superClass
      );
    }

    subClass.prototype = Object.create(superClass && superClass.prototype, {
      constructor: {
        value: subClass,
        enumerable: false,
        writable: true,
        configurable: true
      }
    });
    if (superClass)
      Object.setPrototypeOf
        ? Object.setPrototypeOf(subClass, superClass)
        : (subClass.__proto__ = superClass);
  }

  function _classCallCheck(instance, Constructor) {
    if (!(instance instanceof Constructor)) {
      throw new TypeError('Cannot call a class as a function');
    }
  }

  var _createClass = (function() {
    function defineProperties(target, props) {
      for (var i = 0; i < props.length; i++) {
        var descriptor = props[i];
        descriptor.enumerable = descriptor.enumerable || false;
        descriptor.configurable = true;
        if ('value' in descriptor) descriptor.writable = true;
        Object.defineProperty(target, descriptor.key, descriptor);
      }
    }

    return function(Constructor, protoProps, staticProps) {
      if (protoProps) defineProperties(Constructor.prototype, protoProps);
      if (staticProps) defineProperties(Constructor, staticProps);
      return Constructor;
    };
  })();

  var _typeof =
    typeof Symbol === 'function' && typeof Symbol.iterator === 'symbol'
      ? function(obj) {
          return typeof obj;
        }
      : function(obj) {
          return obj &&
            typeof Symbol === 'function' &&
            obj.constructor === Symbol &&
            obj !== Symbol.prototype
            ? 'symbol'
            : typeof obj;
        };

  /**
   * breakpoints-js v1.0.6
   * https://github.com/amazingSurge/breakpoints-js
   *
   * Copyright (c) amazingSurge
   * Released under the LGPL-3.0 license
   */
  var defaults = {
    // Extra small devices (phones)
    xs: {
      min: 0,
      max: 767
    },
    // Small devices (tablets)
    sm: {
      min: 768,
      max: 991
    },
    // Medium devices (desktops)
    md: {
      min: 992,
      max: 1199
    },
    // Large devices (large desktops)
    lg: {
      min: 1200,
      max: Infinity
    }
  };

  var util = {
    each: function each(obj, fn) {
      var continues = void 0;

      for (var i in obj) {
        if (
          (typeof obj === 'undefined' ? 'undefined' : _typeof(obj)) !==
            'object' ||
          obj.hasOwnProperty(i)
        ) {
          continues = fn(i, obj[i]);
          if (continues === false) {
            break; //allow early exit
          }
        }
      }
    },

    isFunction: function isFunction(obj) {
      return typeof obj === 'function' || false;
    },

    extend: function extend(obj, source) {
      for (var property in source) {
        obj[property] = source[property];
      }
      return obj;
    }
  };

  var Callbacks = (function() {
    function Callbacks() {
      _classCallCheck(this, Callbacks);

      this.length = 0;
      this.list = [];
    }

    _createClass(Callbacks, [
      {
        key: 'add',
        value: function add(fn, data) {
          var one =
            arguments.length > 2 && arguments[2] !== undefined
              ? arguments[2]
              : false;

          this.list.push({
            fn: fn,
            data: data,
            one: one
          });

          this.length++;
        }
      },
      {
        key: 'remove',
        value: function remove(fn) {
          for (var i = 0; i < this.list.length; i++) {
            if (this.list[i].fn === fn) {
              this.list.splice(i, 1);
              this.length--;
              i--;
            }
          }
        }
      },
      {
        key: 'empty',
        value: function empty() {
          this.list = [];
          this.length = 0;
        }
      },
      {
        key: 'call',
        value: function call(caller, i) {
          var fn =
            arguments.length > 2 && arguments[2] !== undefined
              ? arguments[2]
              : null;

          if (!i) {
            i = this.length - 1;
          }
          var callback = this.list[i];

          if (util.isFunction(fn)) {
            fn.call(this, caller, callback, i);
          } else if (util.isFunction(callback.fn)) {
            callback.fn.call(caller || window, callback.data);
          }

          if (callback.one) {
            delete this.list[i];
            this.length--;
          }
        }
      },
      {
        key: 'fire',
        value: function fire(caller) {
          var fn =
            arguments.length > 1 && arguments[1] !== undefined
              ? arguments[1]
              : null;

          for (var i in this.list) {
            if (this.list.hasOwnProperty(i)) {
              this.call(caller, i, fn);
            }
          }
        }
      }
    ]);

    return Callbacks;
  })();

  var ChangeEvent = {
    current: null,
    callbacks: new Callbacks(),
    trigger: function trigger(size) {
      var previous = this.current;
      this.current = size;
      this.callbacks.fire(size, function(caller, callback) {
        if (util.isFunction(callback.fn)) {
          callback.fn.call(
            {
              current: size,
              previous: previous
            },
            callback.data
          );
        }
      });
    },
    one: function one(data, fn) {
      return this.on(data, fn, true);
    },
    on: function on(data, fn) {
      var one =
        arguments.length > 2 && arguments[2] !== undefined
          ? arguments[2]
          : false;

      if (typeof fn === 'undefined' && util.isFunction(data)) {
        fn = data;
        data = undefined;
      }
      if (util.isFunction(fn)) {
        this.callbacks.add(fn, data, one);
      }
    },
    off: function off(fn) {
      if (typeof fn === 'undefined') {
        this.callbacks.empty();
      }
    }
  };

  var MediaQuery = (function() {
    function MediaQuery(name, media) {
      _classCallCheck(this, MediaQuery);

      this.name = name;
      this.media = media;

      this.initialize();
    }

    _createClass(MediaQuery, [
      {
        key: 'initialize',
        value: function initialize() {
          this.callbacks = {
            enter: new Callbacks(),
            leave: new Callbacks()
          };

          this.mql = (window.matchMedia && window.matchMedia(this.media)) || {
            matches: false,
            media: this.media,
            addListener: function addListener() {
              // do nothing
            },
            removeListener: function removeListener() {
              // do nothing
            }
          };

          var that = this;
          this.mqlListener = function(mql) {
            var type = (mql.matches && 'enter') || 'leave';

            that.callbacks[type].fire(that);
          };
          this.mql.addListener(this.mqlListener);
        }
      },
      {
        key: 'on',
        value: function on(types, data, fn) {
          var one =
            arguments.length > 3 && arguments[3] !== undefined
              ? arguments[3]
              : false;

          if (
            (typeof types === 'undefined' ? 'undefined' : _typeof(types)) ===
            'object'
          ) {
            for (var type in types) {
              if (types.hasOwnProperty(type)) {
                this.on(type, data, types[type], one);
              }
            }
            return this;
          }

          if (typeof fn === 'undefined' && util.isFunction(data)) {
            fn = data;
            data = undefined;
          }

          if (!util.isFunction(fn)) {
            return this;
          }

          if (typeof this.callbacks[types] !== 'undefined') {
            this.callbacks[types].add(fn, data, one);

            if (types === 'enter' && this.isMatched()) {
              this.callbacks[types].call(this);
            }
          }

          return this;
        }
      },
      {
        key: 'one',
        value: function one(types, data, fn) {
          return this.on(types, data, fn, true);
        }
      },
      {
        key: 'off',
        value: function off(types, fn) {
          var type = void 0;

          if (
            (typeof types === 'undefined' ? 'undefined' : _typeof(types)) ===
            'object'
          ) {
            for (type in types) {
              if (types.hasOwnProperty(type)) {
                this.off(type, types[type]);
              }
            }
            return this;
          }

          if (typeof types === 'undefined') {
            this.callbacks.enter.empty();
            this.callbacks.leave.empty();
          } else if (types in this.callbacks) {
            if (fn) {
              this.callbacks[types].remove(fn);
            } else {
              this.callbacks[types].empty();
            }
          }

          return this;
        }
      },
      {
        key: 'isMatched',
        value: function isMatched() {
          return this.mql.matches;
        }
      },
      {
        key: 'destroy',
        value: function destroy() {
          this.off();
        }
      }
    ]);

    return MediaQuery;
  })();

  var MediaBuilder = {
    min: function min(_min) {
      var unit =
        arguments.length > 1 && arguments[1] !== undefined
          ? arguments[1]
          : 'px';

      return '(min-width: ' + _min + unit + ')';
    },
    max: function max(_max) {
      var unit =
        arguments.length > 1 && arguments[1] !== undefined
          ? arguments[1]
          : 'px';

      return '(max-width: ' + _max + unit + ')';
    },
    between: function between(min, max) {
      var unit =
        arguments.length > 2 && arguments[2] !== undefined
          ? arguments[2]
          : 'px';

      return (
        '(min-width: ' + min + unit + ') and (max-width: ' + max + unit + ')'
      );
    },
    get: function get(min, max) {
      var unit =
        arguments.length > 2 && arguments[2] !== undefined
          ? arguments[2]
          : 'px';

      if (min === 0) {
        return this.max(max, unit);
      }
      if (max === Infinity) {
        return this.min(min, unit);
      }
      return this.between(min, max, unit);
    }
  };

  var Size = (function(_MediaQuery) {
    _inherits(Size, _MediaQuery);

    function Size(name) {
      var min =
        arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;
      var max =
        arguments.length > 2 && arguments[2] !== undefined
          ? arguments[2]
          : Infinity;
      var unit =
        arguments.length > 3 && arguments[3] !== undefined
          ? arguments[3]
          : 'px';

      _classCallCheck(this, Size);

      var media = MediaBuilder.get(min, max, unit);

      var _this = _possibleConstructorReturn(
        this,
        (Size.__proto__ || Object.getPrototypeOf(Size)).call(this, name, media)
      );

      _this.min = min;
      _this.max = max;
      _this.unit = unit;

      var that = _this;
      _this.changeListener = function() {
        if (that.isMatched()) {
          ChangeEvent.trigger(that);
        }
      };
      if (_this.isMatched()) {
        ChangeEvent.current = _this;
      }

      _this.mql.addListener(_this.changeListener);
      return _this;
    }

    _createClass(Size, [
      {
        key: 'destroy',
        value: function destroy() {
          this.off();
          this.mql.removeListener(this.changeListener);
        }
      }
    ]);

    return Size;
  })(MediaQuery);

  var UnionSize = (function(_MediaQuery2) {
    _inherits(UnionSize, _MediaQuery2);

    function UnionSize(names) {
      _classCallCheck(this, UnionSize);

      var sizes = [];
      var media = [];

      util.each(names.split(' '), function(i, name) {
        var size = Breakpoints$1.get(name);
        if (size) {
          sizes.push(size);
          media.push(size.media);
        }
      });

      return _possibleConstructorReturn(
        this,
        (UnionSize.__proto__ || Object.getPrototypeOf(UnionSize)).call(
          this,
          names,
          media.join(',')
        )
      );
    }

    return UnionSize;
  })(MediaQuery);

  var info = {
    version: '1.0.6'
  };

  var sizes = {};
  var unionSizes = {};

  var Breakpoints = (window.Breakpoints = function() {
    for (
      var _len = arguments.length, args = Array(_len), _key = 0;
      _key < _len;
      _key++
    ) {
      args[_key] = arguments[_key];
    }

    Breakpoints.define.apply(Breakpoints, args);
  });

  Breakpoints.defaults = defaults;

  Breakpoints = util.extend(Breakpoints, {
    version: info.version,
    defined: false,
    define: function define(values) {
      var options =
        arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};

      if (this.defined) {
        this.destroy();
      }

      if (!values) {
        values = Breakpoints.defaults;
      }

      this.options = util.extend(options, {
        unit: 'px'
      });

      for (var size in values) {
        if (values.hasOwnProperty(size)) {
          this.set(size, values[size].min, values[size].max, this.options.unit);
        }
      }

      this.defined = true;
    },
    destroy: function destroy() {
      util.each(sizes, function(name, size) {
        size.destroy();
      });
      sizes = {};
      ChangeEvent.current = null;
    },
    is: function is(size) {
      var breakpoint = this.get(size);
      if (!breakpoint) {
        return null;
      }

      return breakpoint.isMatched();
    },
    all: function all() {
      var names = [];
      util.each(sizes, function(name) {
        names.push(name);
      });
      return names;
    },

    set: function set(name) {
      var min =
        arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;
      var max =
        arguments.length > 2 && arguments[2] !== undefined
          ? arguments[2]
          : Infinity;
      var unit =
        arguments.length > 3 && arguments[3] !== undefined
          ? arguments[3]
          : 'px';

      var size = this.get(name);
      if (size) {
        size.destroy();
      }

      sizes[name] = new Size(name, min, max, unit);
      return sizes[name];
    },

    get: function get(size) {
      if (sizes.hasOwnProperty(size)) {
        return sizes[size];
      }

      return null;
    },

    getUnion: function getUnion(sizes) {
      if (unionSizes.hasOwnProperty(sizes)) {
        return unionSizes[sizes];
      }

      unionSizes[sizes] = new UnionSize(sizes);

      return unionSizes[sizes];
    },
    getMin: function getMin(size) {
      var obj = this.get(size);
      if (obj) {
        return obj.min;
      }
      return null;
    },
    getMax: function getMax(size) {
      var obj = this.get(size);
      if (obj) {
        return obj.max;
      }
      return null;
    },
    current: function current() {
      return ChangeEvent.current;
    },
    getMedia: function getMedia(size) {
      var obj = this.get(size);
      if (obj) {
        return obj.media;
      }
      return null;
    },
    on: function on(sizes, types, data, fn) {
      var one =
        arguments.length > 4 && arguments[4] !== undefined
          ? arguments[4]
          : false;

      sizes = sizes.trim();

      if (sizes === 'change') {
        fn = data;
        data = types;
        return ChangeEvent.on(data, fn, one);
      }
      if (sizes.includes(' ')) {
        var union = this.getUnion(sizes);

        if (union) {
          union.on(types, data, fn, one);
        }
      } else {
        var size = this.get(sizes);

        if (size) {
          size.on(types, data, fn, one);
        }
      }

      return this;
    },
    one: function one(sizes, types, data, fn) {
      return this.on(sizes, types, data, fn, true);
    },
    off: function off(sizes, types, fn) {
      sizes = sizes.trim();

      if (sizes === 'change') {
        return ChangeEvent.off(types);
      }

      if (sizes.includes(' ')) {
        var union = this.getUnion(sizes);

        if (union) {
          union.off(types, fn);
        }
      } else {
        var size = this.get(sizes);

        if (size) {
          size.off(types, fn);
        }
      }

      return this;
    }
  });

  var Breakpoints$1 = Breakpoints;

  exports.default = Breakpoints$1;
});

(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define("/Site", ["exports", "jquery", "Config", "Base", "Menubar", "GridMenu", "Sidebar", "PageAside"], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require("jquery"), require("Config"), require("Base"), require("Menubar"), require("GridMenu"), require("Sidebar"), require("PageAside"));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Config, global.Base, global.SectionMenubar, global.SectionGridMenu, global.SectionSidebar, global.SectionPageAside);
    global.Site = mod.exports;
  }
})(this, function (_exports, _jquery, _Config, _Base2, _Menubar, _GridMenu, _Sidebar, _PageAside) {
  "use strict";

  Object.defineProperty(_exports, "__esModule", {
    value: true
  });
  _exports.run = run;
  _exports.getInstance = getInstance;
  _exports.default = _exports.Site = void 0;
  _jquery = babelHelpers.interopRequireDefault(_jquery);
  _Base2 = babelHelpers.interopRequireDefault(_Base2);
  _Menubar = babelHelpers.interopRequireDefault(_Menubar);
  _GridMenu = babelHelpers.interopRequireDefault(_GridMenu);
  _Sidebar = babelHelpers.interopRequireDefault(_Sidebar);
  _PageAside = babelHelpers.interopRequireDefault(_PageAside);
  var DOC = document;
  var $DOC = (0, _jquery.default)(document);
  var $BODY = (0, _jquery.default)('body');

  var Site =
  /*#__PURE__*/
  function (_Base) {
    babelHelpers.inherits(Site, _Base);

    function Site() {
      babelHelpers.classCallCheck(this, Site);
      return babelHelpers.possibleConstructorReturn(this, babelHelpers.getPrototypeOf(Site).apply(this, arguments));
    }

    babelHelpers.createClass(Site, [{
      key: "initialize",
      value: function initialize() {
        var _this = this;

        this.startLoading();
        this.initializePluginAPIs();
        this.initializePlugins();
        this.initComponents();
        setTimeout(function () {
          _this.setDefaultState();
        }, 500);
      }
    }, {
      key: "process",
      value: function process() {
        this.polyfillIEWidth();
        this.initBootstrap();
        this.setupMenubar();
        this.setupGridMenu();
        this.setupFullScreen();
        this.setupMegaNavbar();
        this.setupTour();
        this.setupNavbarCollpase(); // Dropdown menu setup ===================

        this.$el.on('click', '.dropdown-menu-media', function (e) {
          e.stopPropagation();
        });
      }
    }, {
      key: "_getDefaultMeunbarType",
      value: function _getDefaultMeunbarType() {
        var breakpoint = this.getCurrentBreakpoint();
        var type = false;

        if ($BODY.data('autoMenubar') === false || $BODY.is('.site-menubar-keep')) {
          if ($BODY.hasClass('site-menubar-fold')) {
            type = 'fold';
          } else if ($BODY.hasClass('site-menubar-unfold')) {
            type = 'unfold';
          }
        }

        switch (breakpoint) {
          case 'lg':
            type = type || 'unfold';
            break;

          case 'md':
          case 'sm':
            type = type || 'fold';
            break;

          case 'xs':
            type = 'hide';
            break;
          // no default
        }

        return type;
      }
    }, {
      key: "setDefaultState",
      value: function setDefaultState() {
        var defaultState = this.getDefaultState(); // menubar

        this.menubar.change(defaultState.menubarType); // gridmenu

        this.gridmenu.toggle(defaultState.gridmenu);
      }
    }, {
      key: "getDefaultState",
      value: function getDefaultState() {
        var menubarType = this._getDefaultMeunbarType();

        return {
          menubarType: menubarType,
          gridmenu: false
        };
      }
    }, {
      key: "menubarType",
      value: function menubarType(type) {
        var toggle = function toggle($el) {
          $el.toggleClass('hided', !(type === 'open'));
          $el.toggleClass('unfolded', !(type === 'fold'));
        };

        (0, _jquery.default)('[data-toggle="menubar"]').each(function () {
          var $this = (0, _jquery.default)(this);
          var $hamburger = (0, _jquery.default)(this).find('.hamburger');

          if ($hamburger.length > 0) {
            toggle($hamburger);
          } else {
            toggle($this);
          }
        });
      }
    }, {
      key: "initComponents",
      value: function initComponents() {
        var callback = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : undefined;
        this.menubar = new _Menubar.default({
          $el: (0, _jquery.default)('.site-menubar')
        });
        this.gridmenu = new _GridMenu.default({
          $el: (0, _jquery.default)('.site-gridmenu')
        });
        this.sidebar = new _Sidebar.default();
        var $aside = (0, _jquery.default)('.page-aside');

        if ($aside.length > 0) {
          this.aside = new _PageAside.default({
            $el: $aside
          });
          this.aside.run();
        }

        this.menubar.run();
        this.sidebar.run();
      }
    }, {
      key: "getCurrentBreakpoint",
      value: function getCurrentBreakpoint() {
        var bp = Breakpoints.current();
        return bp ? bp.name : 'lg';
      }
    }, {
      key: "initBootstrap",
      value: function initBootstrap() {
        // Tooltip setup =============
        $DOC.tooltip({
          selector: '[data-tooltip=true]',
          container: 'body'
        });
        (0, _jquery.default)('[data-toggle="tooltip"]').tooltip();
        (0, _jquery.default)('[data-toggle="popover"]').popover();
      }
    }, {
      key: "polyfillIEWidth",
      value: function polyfillIEWidth() {
        if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
          var msViewportStyle = DOC.createElement('style');
          msViewportStyle.appendChild(DOC.createTextNode('@-ms-viewport{width:auto!important}'));
          DOC.querySelector('head').appendChild(msViewportStyle);
        }
      }
    }, {
      key: "setupFullScreen",
      value: function setupFullScreen() {
        if (typeof screenfull !== 'undefined') {
          $DOC.on('click', '[data-toggle="fullscreen"]', function () {
            if (screenfull.enabled) {
              screenfull.toggle();
            }

            return false;
          });

          if (screenfull.enabled) {
            DOC.addEventListener(screenfull.raw.fullscreenchange, function () {
              (0, _jquery.default)('[data-toggle="fullscreen"]').toggleClass('active', screenfull.isFullscreen);
            });
          }
        }
      }
    }, {
      key: "setupGridMenu",
      value: function setupGridMenu() {
        var self = this;
        $DOC.on('click', '[data-toggle="gridmenu"]', function () {
          var $this = (0, _jquery.default)(this);
          var isOpened = self.gridmenu.isOpened;

          if (isOpened) {
            $this.addClass('active').attr('aria-expanded', true);
          } else {
            $this.removeClass('active').attr('aria-expanded', false);
          }

          self.gridmenu.toggle(!isOpened);
        });
      }
    }, {
      key: "setupMegaNavbar",
      value: function setupMegaNavbar() {
        $DOC.on('click', '.navbar-mega .dropdown-menu', function (e) {
          e.stopPropagation();
        }).on('show.bs.dropdown', function (e) {
          var $target = (0, _jquery.default)(e.target);
          var $trigger = e.relatedTarget ? (0, _jquery.default)(e.relatedTarget) : $target.children('[data-toggle="dropdown"]');
          var animation = $trigger.data('animation');

          if (animation) {
            var $menu = $target.children('.dropdown-menu');
            $menu.addClass("animation-".concat(animation)).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
              $menu.removeClass("animation-".concat(animation));
            });
          }
        }).on('shown.bs.dropdown', function (e) {
          var $menu = (0, _jquery.default)(e.target).find('.dropdown-menu-media > .list-group');

          if ($menu.length > 0) {
            var api = $menu.data('asScrollable');

            if (api) {
              api.update();
            } else {
              $menu.asScrollable({
                namespace: 'scrollable',
                contentSelector: '> [data-role=\'content\']',
                containerSelector: '> [data-role=\'container\']'
              });
            }
          }
        });
      }
    }, {
      key: "setupMenubar",
      value: function setupMenubar() {
        var _this2 = this;

        (0, _jquery.default)(document).on('click', '[data-toggle="menubar"]:visible', function () {
          var type = _this2.menubar.type;

          switch (type) {
            case 'fold':
              type = 'unfold';
              break;

            case 'unfold':
              type = 'fold';
              break;

            case 'open':
              type = 'hide';
              break;

            case 'hide':
              type = 'open';
              break;
            // no default
          }

          _this2.menubar.change(type);

          _this2.menubarType(type);

          return false;
        });
        Breakpoints.on('change', function () {
          _this2.menubar.type = _this2._getDefaultMeunbarType();

          _this2.menubar.change(_this2.menubar.type);
        });
      }
    }, {
      key: "setupNavbarCollpase",
      value: function setupNavbarCollpase() {
        (0, _jquery.default)(document).on('click', '[data-target=\'#site-navbar-collapse\']', function (e) {
          var $trigger = (0, _jquery.default)(this);
          var isClose = $trigger.hasClass('collapsed');
          $BODY.addClass('site-navbar-collapsing');
          $BODY.toggleClass('site-navbar-collapse-show', !isClose);
          setTimeout(function () {
            $BODY.removeClass('site-navbar-collapsing');
          }, 350);
        });
      }
    }, {
      key: "startLoading",
      value: function startLoading() {
        if (typeof _jquery.default.fn.animsition === 'undefined') {
          return false;
        } // let loadingType = 'default';


        var assets = (0, _Config.get)('assets');
        $BODY.animsition({
          inClass: 'fade-in',
          outClass: 'fade-out',
          inDuration: 800,
          outDuration: 500,
          loading: true,
          loadingClass: 'loader-overlay',
          loadingParentElement: 'html',
          loadingInner: "\n      <div class=\"loader-content\">\n        <div class=\"loader-index\">\n          <div></div>\n          <div></div>\n          <div></div>\n          <div></div>\n          <div></div>\n          <div></div>\n        </div>\n      </div>",
          onLoadEvent: true
        });
      }
    }, {
      key: "setupTour",
      value: function setupTour(flag) {
        if (typeof this.tour === 'undefined') {
          if (typeof introJs === 'undefined') {
            return;
          }

          var overflow = (0, _jquery.default)('body').css('overflow');
          var self = this;
          var tourOptions = (0, _Config.get)('tour');
          this.tour = introJs();
          this.tour.onbeforechange(function () {
            (0, _jquery.default)('body').css('overflow', 'hidden');
          });
          this.tour.oncomplete(function () {
            (0, _jquery.default)('body').css('overflow', overflow);
          });
          this.tour.onexit(function () {
            (0, _jquery.default)('body').css('overflow', overflow);
          });
          this.tour.setOptions(tourOptions);
          (0, _jquery.default)('.site-tour-trigger').on('click', function () {
            self.tour.start();
          });
        }
      }
    }]);
    return Site;
  }(_Base2.default);

  _exports.Site = Site;
  var instance = null;

  function getInstance() {
    if (!instance) {
      instance = new Site();
    }

    return instance;
  }

  function run() {
    var site = getInstance();
    site.run();
  }

  var _default = Site;
  _exports.default = _default;
});
(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define("/Plugin/asscrollable", ["exports", "Plugin"], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require("Plugin"));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginAsscrollable = mod.exports;
  }
})(this, function (_exports, _Plugin2) {
  "use strict";

  Object.defineProperty(_exports, "__esModule", {
    value: true
  });
  _exports.default = void 0;
  _Plugin2 = babelHelpers.interopRequireDefault(_Plugin2);
  var NAME = 'scrollable';

  var Scrollable =
  /*#__PURE__*/
  function (_Plugin) {
    babelHelpers.inherits(Scrollable, _Plugin);

    function Scrollable() {
      babelHelpers.classCallCheck(this, Scrollable);
      return babelHelpers.possibleConstructorReturn(this, babelHelpers.getPrototypeOf(Scrollable).apply(this, arguments));
    }

    babelHelpers.createClass(Scrollable, [{
      key: "getName",
      value: function getName() {
        return NAME;
      }
    }, {
      key: "render",
      value: function render() {
        var $el = this.$el;
        $el.asScrollable(this.options);
      }
    }], [{
      key: "getDefaults",
      value: function getDefaults() {
        return {
          namespace: 'scrollable',
          contentSelector: '> [data-role=\'content\']',
          containerSelector: '> [data-role=\'container\']'
        };
      }
    }]);
    return Scrollable;
  }(_Plugin2.default);

  _Plugin2.default.register(NAME, Scrollable);

  var _default = Scrollable;
  _exports.default = _default;
});
(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define("/Plugin/slidepanel", ["exports", "jquery", "Plugin"], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require("jquery"), require("Plugin"));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Plugin);
    global.PluginSlidepanel = mod.exports;
  }
})(this, function (_exports, _jquery, _Plugin2) {
  "use strict";

  Object.defineProperty(_exports, "__esModule", {
    value: true
  });
  _exports.default = void 0;
  _jquery = babelHelpers.interopRequireDefault(_jquery);
  _Plugin2 = babelHelpers.interopRequireDefault(_Plugin2);
  var NAME = 'slidePanel';

  var SlidePanel =
  /*#__PURE__*/
  function (_Plugin) {
    babelHelpers.inherits(SlidePanel, _Plugin);

    function SlidePanel() {
      babelHelpers.classCallCheck(this, SlidePanel);
      return babelHelpers.possibleConstructorReturn(this, babelHelpers.getPrototypeOf(SlidePanel).apply(this, arguments));
    }

    babelHelpers.createClass(SlidePanel, [{
      key: "getName",
      value: function getName() {
        return NAME;
      }
    }, {
      key: "render",
      value: function render() {
        if (typeof _jquery.default.slidePanel === 'undefined') {
          return;
        }

        if (!this.options.url) {
          this.options.url = this.$el.attr('href');
          this.options.url = this.options.url && this.options.url.replace(/.*(?=#[^\s]*$)/, '');
        }

        this.$el.data('slidePanelWrapAPI', this);
      }
    }, {
      key: "show",
      value: function show() {
        var options = this.options;

        _jquery.default.slidePanel.show({
          url: options.url
        }, options);
      }
    }], [{
      key: "getDefaults",
      value: function getDefaults() {
        return {
          closeSelector: '.slidePanel-close',
          mouseDragHandler: '.slidePanel-handler',
          loading: {
            template: function template(options) {
              return "<div class=\"".concat(options.classes.loading, "\">\n                    <div class=\"loader loader-default\"></div>\n                  </div>");
            },
            showCallback: function showCallback(options) {
              this.$el.addClass("".concat(options.classes.loading, "-show"));
            },
            hideCallback: function hideCallback(options) {
              this.$el.removeClass("".concat(options.classes.loading, "-show"));
            }
          }
        };
      }
    }, {
      key: "api",
      value: function api() {
        return 'click|show';
      }
    }]);
    return SlidePanel;
  }(_Plugin2.default);

  _Plugin2.default.register(NAME, SlidePanel);

  var _default = SlidePanel;
  _exports.default = _default;
});
(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define("/Plugin/switchery", ["exports", "Plugin", "Config"], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require("Plugin"), require("Config"));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin, global.Config);
    global.PluginSwitchery = mod.exports;
  }
})(this, function (_exports, _Plugin2, _Config) {
  "use strict";

  Object.defineProperty(_exports, "__esModule", {
    value: true
  });
  _exports.default = void 0;
  _Plugin2 = babelHelpers.interopRequireDefault(_Plugin2);
  var NAME = 'switchery';

  var SwitcheryPlugin =
  /*#__PURE__*/
  function (_Plugin) {
    babelHelpers.inherits(SwitcheryPlugin, _Plugin);

    function SwitcheryPlugin() {
      babelHelpers.classCallCheck(this, SwitcheryPlugin);
      return babelHelpers.possibleConstructorReturn(this, babelHelpers.getPrototypeOf(SwitcheryPlugin).apply(this, arguments));
    }

    babelHelpers.createClass(SwitcheryPlugin, [{
      key: "getName",
      value: function getName() {
        return NAME;
      }
    }, {
      key: "render",
      value: function render() {
        if (typeof Switchery === 'undefined') {
          return;
        }

        new Switchery(this.$el[0], this.options);
      }
    }], [{
      key: "getDefaults",
      value: function getDefaults() {
        return {
          color: (0, _Config.colors)('primary', 600)
        };
      }
    }]);
    return SwitcheryPlugin;
  }(_Plugin2.default);

  _Plugin2.default.register(NAME, SwitcheryPlugin);

  var _default = SwitcheryPlugin;
  _exports.default = _default;
});
/**
 * Intro.js v2.9.3
 * https://github.com/usablica/intro.js
 *
 * Copyright (C) 2017 Afshin Mehrabani (@afshinmeh)
 */

(function(f) {
    if (typeof exports === "object" && typeof module !== "undefined") {
        module.exports = f();
        // deprecated function
        // @since 2.8.0
        module.exports.introJs = function () {
          console.warn('Deprecated: please use require("intro.js") directly, instead of the introJs method of the function');
          // introJs()
          return f().apply(this, arguments);
        };
    } else if (typeof define === "function" && define.amd) {
        define([], f);
    } else {
        var g;
        if (typeof window !== "undefined") {
            g = window;
        } else if (typeof global !== "undefined") {
            g = global;
        } else if (typeof self !== "undefined") {
            g = self;
        } else {
            g = this;
        }
        g.introJs = f();
    }
})(function () {
  //Default config/variables
  var VERSION = '2.9.3';

  /**
   * IntroJs main class
   *
   * @class IntroJs
   */
  function IntroJs(obj) {
    this._targetElement = obj;
    this._introItems = [];

    this._options = {
      /* Next button label in tooltip box */
      nextLabel: 'Next &rarr;',
      /* Previous button label in tooltip box */
      prevLabel: '&larr; Back',
      /* Skip button label in tooltip box */
      skipLabel: 'Skip',
      /* Done button label in tooltip box */
      doneLabel: 'Done',
      /* Hide previous button in the first step? Otherwise, it will be disabled button. */
      hidePrev: false,
      /* Hide next button in the last step? Otherwise, it will be disabled button. */
      hideNext: false,
      /* Default tooltip box position */
      tooltipPosition: 'bottom',
      /* Next CSS class for tooltip boxes */
      tooltipClass: '',
      /* CSS class that is added to the helperLayer */
      highlightClass: '',
      /* Close introduction when pressing Escape button? */
      exitOnEsc: true,
      /* Close introduction when clicking on overlay layer? */
      exitOnOverlayClick: true,
      /* Show step numbers in introduction? */
      showStepNumbers: true,
      /* Let user use keyboard to navigate the tour? */
      keyboardNavigation: true,
      /* Show tour control buttons? */
      showButtons: true,
      /* Show tour bullets? */
      showBullets: true,
      /* Show tour progress? */
      showProgress: false,
      /* Scroll to highlighted element? */
      scrollToElement: true,
      /*
       * Should we scroll the tooltip or target element?
       *
       * Options are: 'element' or 'tooltip'
       */
      scrollTo: 'element',
      /* Padding to add after scrolling when element is not in the viewport (in pixels) */
      scrollPadding: 30,
      /* Set the overlay opacity */
      overlayOpacity: 0.8,
      /* Precedence of positions, when auto is enabled */
      positionPrecedence: ["bottom", "top", "right", "left"],
      /* Disable an interaction with element? */
      disableInteraction: false,
      /* Set how much padding to be used around helper element */
      helperElementPadding: 10,
      /* Default hint position */
      hintPosition: 'top-middle',
      /* Hint button label */
      hintButtonLabel: 'Got it',
      /* Adding animation to hints? */
      hintAnimation: true,
      /* additional classes to put on the buttons */
      buttonClass: "introjs-button"
    };
  }

  /**
   * Initiate a new introduction/guide from an element in the page
   *
   * @api private
   * @method _introForElement
   * @param {Object} targetElm
   * @param {String} group
   * @returns {Boolean} Success or not?
   */
  function _introForElement(targetElm, group) {
    var allIntroSteps = targetElm.querySelectorAll("*[data-intro]"),
        introItems = [];

    if (this._options.steps) {
      //use steps passed programmatically
      _forEach(this._options.steps, function (step) {
        var currentItem = _cloneObject(step);

        //set the step
        currentItem.step = introItems.length + 1;

        //use querySelector function only when developer used CSS selector
        if (typeof (currentItem.element) === 'string') {
          //grab the element with given selector from the page
          currentItem.element = document.querySelector(currentItem.element);
        }

        //intro without element
        if (typeof (currentItem.element) === 'undefined' || currentItem.element === null) {
          var floatingElementQuery = document.querySelector(".introjsFloatingElement");

          if (floatingElementQuery === null) {
            floatingElementQuery = document.createElement('div');
            floatingElementQuery.className = 'introjsFloatingElement';

            document.body.appendChild(floatingElementQuery);
          }

          currentItem.element  = floatingElementQuery;
          currentItem.position = 'floating';
        }

        currentItem.scrollTo = currentItem.scrollTo || this._options.scrollTo;

        if (typeof (currentItem.disableInteraction) === 'undefined') {
          currentItem.disableInteraction = this._options.disableInteraction;
        }

        if (currentItem.element !== null) {
          introItems.push(currentItem);
        }        
      }.bind(this));

    } else {
      //use steps from data-* annotations
      var elmsLength = allIntroSteps.length;
      var disableInteraction;
      
      //if there's no element to intro
      if (elmsLength < 1) {
        return false;
      }

      _forEach(allIntroSteps, function (currentElement) {
        
        // PR #80
        // start intro for groups of elements
        if (group && (currentElement.getAttribute("data-intro-group") !== group)) {
          return;
        }

        // skip hidden elements
        if (currentElement.style.display === 'none') {
          return;
        }

        var step = parseInt(currentElement.getAttribute('data-step'), 10);

        if (typeof (currentElement.getAttribute('data-disable-interaction')) !== 'undefined') {
          disableInteraction = !!currentElement.getAttribute('data-disable-interaction');
        } else {
          disableInteraction = this._options.disableInteraction;
        }

        if (step > 0) {
          introItems[step - 1] = {
            element: currentElement,
            intro: currentElement.getAttribute('data-intro'),
            step: parseInt(currentElement.getAttribute('data-step'), 10),
            tooltipClass: currentElement.getAttribute('data-tooltipclass'),
            highlightClass: currentElement.getAttribute('data-highlightclass'),
            position: currentElement.getAttribute('data-position') || this._options.tooltipPosition,
            scrollTo: currentElement.getAttribute('data-scrollto') || this._options.scrollTo,
            disableInteraction: disableInteraction
          };
        }
      }.bind(this));

      //next add intro items without data-step
      //todo: we need a cleanup here, two loops are redundant
      var nextStep = 0;

      _forEach(allIntroSteps, function (currentElement) {
        
        // PR #80
        // start intro for groups of elements
        if (group && (currentElement.getAttribute("data-intro-group") !== group)) {
          return;
        }
        
        if (currentElement.getAttribute('data-step') === null) {

          while (true) {
            if (typeof introItems[nextStep] === 'undefined') {
              break;
            } else {
              nextStep++;
            }
          } 

          if (typeof (currentElement.getAttribute('data-disable-interaction')) !== 'undefined') {
            disableInteraction = !!currentElement.getAttribute('data-disable-interaction');
          } else {
            disableInteraction = this._options.disableInteraction;
          }

          introItems[nextStep] = {
            element: currentElement,
            intro: currentElement.getAttribute('data-intro'),
            step: nextStep + 1,
            tooltipClass: currentElement.getAttribute('data-tooltipclass'),
            highlightClass: currentElement.getAttribute('data-highlightclass'),
            position: currentElement.getAttribute('data-position') || this._options.tooltipPosition,
            scrollTo: currentElement.getAttribute('data-scrollto') || this._options.scrollTo,
            disableInteraction: disableInteraction
          };
        }
      }.bind(this));
    }

    //removing undefined/null elements
    var tempIntroItems = [];
    for (var z = 0; z < introItems.length; z++) {
      if (introItems[z]) {
        // copy non-falsy values to the end of the array
        tempIntroItems.push(introItems[z]);  
      } 
    }

    introItems = tempIntroItems;

    //Ok, sort all items with given steps
    introItems.sort(function (a, b) {
      return a.step - b.step;
    });

    //set it to the introJs object
    this._introItems = introItems;

    //add overlay layer to the page
    if(_addOverlayLayer.call(this, targetElm)) {
      //then, start the show
      _nextStep.call(this);

      if (this._options.keyboardNavigation) {
        DOMEvent.on(window, 'keydown', _onKeyDown, this, true);
      }
      //for window resize
      DOMEvent.on(window, 'resize', _onResize, this, true);
    }
    return false;
  }

  function _onResize () {
    this.refresh.call(this);
  }

  /**
  * on keyCode:
  * https://developer.mozilla.org/en-US/docs/Web/API/KeyboardEvent/keyCode
  * This feature has been removed from the Web standards.
  * Though some browsers may still support it, it is in
  * the process of being dropped.
  * Instead, you should use KeyboardEvent.code,
  * if it's implemented.
  *
  * jQuery's approach is to test for
  *   (1) e.which, then
  *   (2) e.charCode, then
  *   (3) e.keyCode
  * https://github.com/jquery/jquery/blob/a6b0705294d336ae2f63f7276de0da1195495363/src/event.js#L638
  *
  * @param type var
  * @return type
  */
  function _onKeyDown (e) {
    var code = (e.code === null) ? e.which : e.code;

    // if code/e.which is null
    if (code === null) {
      code = (e.charCode === null) ? e.keyCode : e.charCode;
    }
    
    if ((code === 'Escape' || code === 27) && this._options.exitOnEsc === true) {
      //escape key pressed, exit the intro
      //check if exit callback is defined
      _exitIntro.call(this, this._targetElement);
    } else if (code === 'ArrowLeft' || code === 37) {
      //left arrow
      _previousStep.call(this);
    } else if (code === 'ArrowRight' || code === 39) {
      //right arrow
      _nextStep.call(this);
    } else if (code === 'Enter' || code === 13) {
      //srcElement === ie
      var target = e.target || e.srcElement;
      if (target && target.className.match('introjs-prevbutton')) {
        //user hit enter while focusing on previous button
        _previousStep.call(this);
      } else if (target && target.className.match('introjs-skipbutton')) {
        //user hit enter while focusing on skip button
        if (this._introItems.length - 1 === this._currentStep && typeof (this._introCompleteCallback) === 'function') {
            this._introCompleteCallback.call(this);
        }

        _exitIntro.call(this, this._targetElement);
      } else if (target && target.getAttribute('data-stepnumber')) {
        // user hit enter while focusing on step bullet
        target.click();
      } else {
        //default behavior for responding to enter
        _nextStep.call(this);
      }

      //prevent default behaviour on hitting Enter, to prevent steps being skipped in some browsers
      if(e.preventDefault) {
        e.preventDefault();
      } else {
        e.returnValue = false;
      }
    }
  }

 /*
   * makes a copy of the object
   * @api private
   * @method _cloneObject
  */
  function _cloneObject(object) {
      if (object === null || typeof (object) !== 'object' || typeof (object.nodeType) !== 'undefined') {
        return object;
      }
      var temp = {};
      for (var key in object) {
        if (typeof(window.jQuery) !== 'undefined' && object[key] instanceof window.jQuery) {
          temp[key] = object[key];
        } else {
          temp[key] = _cloneObject(object[key]);
        }
      }
      return temp;
  }
  /**
   * Go to specific step of introduction
   *
   * @api private
   * @method _goToStep
   */
  function _goToStep(step) {
    //because steps starts with zero
    this._currentStep = step - 2;
    if (typeof (this._introItems) !== 'undefined') {
      _nextStep.call(this);
    }
  }

  /**
   * Go to the specific step of introduction with the explicit [data-step] number
   *
   * @api private
   * @method _goToStepNumber
   */
  function _goToStepNumber(step) {
    this._currentStepNumber = step;
    if (typeof (this._introItems) !== 'undefined') {
      _nextStep.call(this);
    }
  }

  /**
   * Go to next step on intro
   *
   * @api private
   * @method _nextStep
   */
  function _nextStep() {
    this._direction = 'forward';

    if (typeof (this._currentStepNumber) !== 'undefined') {
      _forEach(this._introItems, function (item, i) {
        if( item.step === this._currentStepNumber ) {
          this._currentStep = i - 1;
          this._currentStepNumber = undefined;
        }
      }.bind(this));
    }

    if (typeof (this._currentStep) === 'undefined') {
      this._currentStep = 0;
    } else {
      ++this._currentStep;
    }

    var nextStep = this._introItems[this._currentStep];
    var continueStep = true;

    if (typeof (this._introBeforeChangeCallback) !== 'undefined') {
      continueStep = this._introBeforeChangeCallback.call(this, nextStep.element);
    }

    // if `onbeforechange` returned `false`, stop displaying the element
    if (continueStep === false) {
      --this._currentStep;
      return false;
    }

    if ((this._introItems.length) <= this._currentStep) {
      //end of the intro
      //check if any callback is defined
      if (typeof (this._introCompleteCallback) === 'function') {
        this._introCompleteCallback.call(this);
      }
      _exitIntro.call(this, this._targetElement);
      return;
    }

    _showElement.call(this, nextStep);
  }

  /**
   * Go to previous step on intro
   *
   * @api private
   * @method _previousStep
   */
  function _previousStep() {
    this._direction = 'backward';

    if (this._currentStep === 0) {
      return false;
    }

    --this._currentStep;

    var nextStep = this._introItems[this._currentStep];
    var continueStep = true;

    if (typeof (this._introBeforeChangeCallback) !== 'undefined') {
      continueStep = this._introBeforeChangeCallback.call(this, nextStep.element);
    }

    // if `onbeforechange` returned `false`, stop displaying the element
    if (continueStep === false) {
      ++this._currentStep;
      return false;
    }

    _showElement.call(this, nextStep);
  }

  /**
   * Update placement of the intro objects on the screen
   * @api private
   */
  function _refresh() {
    // re-align intros
    _setHelperLayerPosition.call(this, document.querySelector('.introjs-helperLayer'));
    _setHelperLayerPosition.call(this, document.querySelector('.introjs-tooltipReferenceLayer'));
    _setHelperLayerPosition.call(this, document.querySelector('.introjs-disableInteraction'));

    // re-align tooltip
    if(this._currentStep !== undefined && this._currentStep !== null) {
      var oldHelperNumberLayer = document.querySelector('.introjs-helperNumberLayer'),
        oldArrowLayer        = document.querySelector('.introjs-arrow'),
        oldtooltipContainer  = document.querySelector('.introjs-tooltip');
      _placeTooltip.call(this, this._introItems[this._currentStep].element, oldtooltipContainer, oldArrowLayer, oldHelperNumberLayer);
    }

    //re-align hints
    _reAlignHints.call(this);
    return this;
  }

  /**
   * Exit from intro
   *
   * @api private
   * @method _exitIntro
   * @param {Object} targetElement
   * @param {Boolean} force - Setting to `true` will skip the result of beforeExit callback
   */
  function _exitIntro(targetElement, force) {
    var continueExit = true;

    // calling onbeforeexit callback
    //
    // If this callback return `false`, it would halt the process
    if (this._introBeforeExitCallback !== undefined) {
      continueExit = this._introBeforeExitCallback.call(this);
    }

    // skip this check if `force` parameter is `true`
    // otherwise, if `onbeforeexit` returned `false`, don't exit the intro
    if (!force && continueExit === false) return;

    //remove overlay layers from the page
    var overlayLayers = targetElement.querySelectorAll('.introjs-overlay');

    if (overlayLayers && overlayLayers.length) {
      _forEach(overlayLayers, function (overlayLayer) {
        overlayLayer.style.opacity = 0;
        window.setTimeout(function () {
          if (this.parentNode) {
            this.parentNode.removeChild(this);
          }
        }.bind(overlayLayer), 500);
      }.bind(this));
    }

    //remove all helper layers
    var helperLayer = targetElement.querySelector('.introjs-helperLayer');
    if (helperLayer) {
      helperLayer.parentNode.removeChild(helperLayer);
    }

    var referenceLayer = targetElement.querySelector('.introjs-tooltipReferenceLayer');
    if (referenceLayer) {
      referenceLayer.parentNode.removeChild(referenceLayer);
    }

    //remove disableInteractionLayer
    var disableInteractionLayer = targetElement.querySelector('.introjs-disableInteraction');
    if (disableInteractionLayer) {
      disableInteractionLayer.parentNode.removeChild(disableInteractionLayer);
    }

    //remove intro floating element
    var floatingElement = document.querySelector('.introjsFloatingElement');
    if (floatingElement) {
      floatingElement.parentNode.removeChild(floatingElement);
    }

    _removeShowElement();

    //remove `introjs-fixParent` class from the elements
    var fixParents = document.querySelectorAll('.introjs-fixParent');
    _forEach(fixParents, function (parent) {
      _removeClass(parent, /introjs-fixParent/g);
    });

    //clean listeners
    DOMEvent.off(window, 'keydown', _onKeyDown, this, true);
    DOMEvent.off(window, 'resize', _onResize, this, true);

    //check if any callback is defined
    if (this._introExitCallback !== undefined) {
      this._introExitCallback.call(this);
    }

    //set the step to zero
    this._currentStep = undefined;
  }

  /**
   * Render tooltip box in the page
   *
   * @api private
   * @method _placeTooltip
   * @param {HTMLElement} targetElement
   * @param {HTMLElement} tooltipLayer
   * @param {HTMLElement} arrowLayer
   * @param {HTMLElement} helperNumberLayer
   * @param {Boolean} hintMode
   */
  function _placeTooltip(targetElement, tooltipLayer, arrowLayer, helperNumberLayer, hintMode) {
    var tooltipCssClass = '',
        currentStepObj,
        tooltipOffset,
        targetOffset,
        windowSize,
        currentTooltipPosition;

    hintMode = hintMode || false;

    //reset the old style
    tooltipLayer.style.top        = null;
    tooltipLayer.style.right      = null;
    tooltipLayer.style.bottom     = null;
    tooltipLayer.style.left       = null;
    tooltipLayer.style.marginLeft = null;
    tooltipLayer.style.marginTop  = null;

    arrowLayer.style.display = 'inherit';

    if (typeof(helperNumberLayer) !== 'undefined' && helperNumberLayer !== null) {
      helperNumberLayer.style.top  = null;
      helperNumberLayer.style.left = null;
    }

    //prevent error when `this._currentStep` is undefined
    if (!this._introItems[this._currentStep]) return;

    //if we have a custom css class for each step
    currentStepObj = this._introItems[this._currentStep];
    if (typeof (currentStepObj.tooltipClass) === 'string') {
      tooltipCssClass = currentStepObj.tooltipClass;
    } else {
      tooltipCssClass = this._options.tooltipClass;
    }

    tooltipLayer.className = ('introjs-tooltip ' + tooltipCssClass).replace(/^\s+|\s+$/g, '');
    tooltipLayer.setAttribute('role', 'dialog');

    currentTooltipPosition = this._introItems[this._currentStep].position;

    // Floating is always valid, no point in calculating
    if (currentTooltipPosition !== "floating") { 
      currentTooltipPosition = _determineAutoPosition.call(this, targetElement, tooltipLayer, currentTooltipPosition);
    }

    var tooltipLayerStyleLeft;
    targetOffset  = _getOffset(targetElement);
    tooltipOffset = _getOffset(tooltipLayer);
    windowSize    = _getWinSize();

    _addClass(tooltipLayer, 'introjs-' + currentTooltipPosition);

    switch (currentTooltipPosition) {
      case 'top-right-aligned':
        arrowLayer.className      = 'introjs-arrow bottom-right';

        var tooltipLayerStyleRight = 0;
        _checkLeft(targetOffset, tooltipLayerStyleRight, tooltipOffset, tooltipLayer);
        tooltipLayer.style.bottom    = (targetOffset.height +  20) + 'px';
        break;

      case 'top-middle-aligned':
        arrowLayer.className      = 'introjs-arrow bottom-middle';

        var tooltipLayerStyleLeftRight = targetOffset.width / 2 - tooltipOffset.width / 2;

        // a fix for middle aligned hints
        if (hintMode) {
          tooltipLayerStyleLeftRight += 5;
        }

        if (_checkLeft(targetOffset, tooltipLayerStyleLeftRight, tooltipOffset, tooltipLayer)) {
          tooltipLayer.style.right = null;
          _checkRight(targetOffset, tooltipLayerStyleLeftRight, tooltipOffset, windowSize, tooltipLayer);
        }
        tooltipLayer.style.bottom = (targetOffset.height + 20) + 'px';
        break;

      case 'top-left-aligned':
      // top-left-aligned is the same as the default top
      case 'top':
        arrowLayer.className = 'introjs-arrow bottom';

        tooltipLayerStyleLeft = (hintMode) ? 0 : 15;

        _checkRight(targetOffset, tooltipLayerStyleLeft, tooltipOffset, windowSize, tooltipLayer);
        tooltipLayer.style.bottom = (targetOffset.height +  20) + 'px';
        break;
      case 'right':
        tooltipLayer.style.left = (targetOffset.width + 20) + 'px';
        if (targetOffset.top + tooltipOffset.height > windowSize.height) {
          // In this case, right would have fallen below the bottom of the screen.
          // Modify so that the bottom of the tooltip connects with the target
          arrowLayer.className = "introjs-arrow left-bottom";
          tooltipLayer.style.top = "-" + (tooltipOffset.height - targetOffset.height - 20) + "px";
        } else {
          arrowLayer.className = 'introjs-arrow left';
        }
        break;
      case 'left':
        if (!hintMode && this._options.showStepNumbers === true) {
          tooltipLayer.style.top = '15px';
        }

        if (targetOffset.top + tooltipOffset.height > windowSize.height) {
          // In this case, left would have fallen below the bottom of the screen.
          // Modify so that the bottom of the tooltip connects with the target
          tooltipLayer.style.top = "-" + (tooltipOffset.height - targetOffset.height - 20) + "px";
          arrowLayer.className = 'introjs-arrow right-bottom';
        } else {
          arrowLayer.className = 'introjs-arrow right';
        }
        tooltipLayer.style.right = (targetOffset.width + 20) + 'px';

        break;
      case 'floating':
        arrowLayer.style.display = 'none';

        //we have to adjust the top and left of layer manually for intro items without element
        tooltipLayer.style.left   = '50%';
        tooltipLayer.style.top    = '50%';
        tooltipLayer.style.marginLeft = '-' + (tooltipOffset.width / 2)  + 'px';
        tooltipLayer.style.marginTop  = '-' + (tooltipOffset.height / 2) + 'px';

        if (typeof(helperNumberLayer) !== 'undefined' && helperNumberLayer !== null) {
          helperNumberLayer.style.left = '-' + ((tooltipOffset.width / 2) + 18) + 'px';
          helperNumberLayer.style.top  = '-' + ((tooltipOffset.height / 2) + 18) + 'px';
        }

        break;
      case 'bottom-right-aligned':
        arrowLayer.className      = 'introjs-arrow top-right';

        tooltipLayerStyleRight = 0;
        _checkLeft(targetOffset, tooltipLayerStyleRight, tooltipOffset, tooltipLayer);
        tooltipLayer.style.top    = (targetOffset.height +  20) + 'px';
        break;

      case 'bottom-middle-aligned':
        arrowLayer.className      = 'introjs-arrow top-middle';

        tooltipLayerStyleLeftRight = targetOffset.width / 2 - tooltipOffset.width / 2;

        // a fix for middle aligned hints
        if (hintMode) {
          tooltipLayerStyleLeftRight += 5;
        }

        if (_checkLeft(targetOffset, tooltipLayerStyleLeftRight, tooltipOffset, tooltipLayer)) {
          tooltipLayer.style.right = null;
          _checkRight(targetOffset, tooltipLayerStyleLeftRight, tooltipOffset, windowSize, tooltipLayer);
        }
        tooltipLayer.style.top = (targetOffset.height + 20) + 'px';
        break;

      // case 'bottom-left-aligned':
      // Bottom-left-aligned is the same as the default bottom
      // case 'bottom':
      // Bottom going to follow the default behavior
      default:
        arrowLayer.className = 'introjs-arrow top';

        tooltipLayerStyleLeft = 0;
        _checkRight(targetOffset, tooltipLayerStyleLeft, tooltipOffset, windowSize, tooltipLayer);
        tooltipLayer.style.top    = (targetOffset.height +  20) + 'px';
    }
  }

  /**
   * Set tooltip left so it doesn't go off the right side of the window
   *
   * @return boolean true, if tooltipLayerStyleLeft is ok.  false, otherwise.
   */
  function _checkRight(targetOffset, tooltipLayerStyleLeft, tooltipOffset, windowSize, tooltipLayer) {
    if (targetOffset.left + tooltipLayerStyleLeft + tooltipOffset.width > windowSize.width) {
      // off the right side of the window
      tooltipLayer.style.left = (windowSize.width - tooltipOffset.width - targetOffset.left) + 'px';
      return false;
    }
    tooltipLayer.style.left = tooltipLayerStyleLeft + 'px';
    return true;
  }

  /**
   * Set tooltip right so it doesn't go off the left side of the window
   *
   * @return boolean true, if tooltipLayerStyleRight is ok.  false, otherwise.
   */
  function _checkLeft(targetOffset, tooltipLayerStyleRight, tooltipOffset, tooltipLayer) {
    if (targetOffset.left + targetOffset.width - tooltipLayerStyleRight - tooltipOffset.width < 0) {
      // off the left side of the window
      tooltipLayer.style.left = (-targetOffset.left) + 'px';
      return false;
    }
    tooltipLayer.style.right = tooltipLayerStyleRight + 'px';
    return true;
  }

  /**
   * Determines the position of the tooltip based on the position precedence and availability
   * of screen space.
   *
   * @param {Object}    targetElement
   * @param {Object}    tooltipLayer
   * @param {String}    desiredTooltipPosition
   * @return {String}   calculatedPosition
   */
  function _determineAutoPosition(targetElement, tooltipLayer, desiredTooltipPosition) {

    // Take a clone of position precedence. These will be the available
    var possiblePositions = this._options.positionPrecedence.slice();

    var windowSize = _getWinSize();
    var tooltipHeight = _getOffset(tooltipLayer).height + 10;
    var tooltipWidth = _getOffset(tooltipLayer).width + 20;
    var targetElementRect = targetElement.getBoundingClientRect();

    // If we check all the possible areas, and there are no valid places for the tooltip, the element
    // must take up most of the screen real estate. Show the tooltip floating in the middle of the screen.
    var calculatedPosition = "floating";

    /*
    * auto determine position 
    */

    // Check for space below
    if (targetElementRect.bottom + tooltipHeight + tooltipHeight > windowSize.height) {
      _removeEntry(possiblePositions, "bottom");
    }

    // Check for space above
    if (targetElementRect.top - tooltipHeight < 0) {
      _removeEntry(possiblePositions, "top");
    }

    // Check for space to the right
    if (targetElementRect.right + tooltipWidth > windowSize.width) {
      _removeEntry(possiblePositions, "right");
    }

    // Check for space to the left
    if (targetElementRect.left - tooltipWidth < 0) {
      _removeEntry(possiblePositions, "left");
    }

    // @var {String}  ex: 'right-aligned'
    var desiredAlignment = (function (pos) {
      var hyphenIndex = pos.indexOf('-');
      if (hyphenIndex !== -1) {
        // has alignment
        return pos.substr(hyphenIndex);
      }
      return '';
    })(desiredTooltipPosition || '');

    // strip alignment from position
    if (desiredTooltipPosition) {
      // ex: "bottom-right-aligned"
      // should return 'bottom'
      desiredTooltipPosition = desiredTooltipPosition.split('-')[0];
    }

    if (possiblePositions.length) {
      if (desiredTooltipPosition !== "auto" &&
          possiblePositions.indexOf(desiredTooltipPosition) > -1) {
        // If the requested position is in the list, choose that
        calculatedPosition = desiredTooltipPosition;
      } else {
        // Pick the first valid position, in order
        calculatedPosition = possiblePositions[0];
      }
    }

    // only top and bottom positions have optional alignments
    if (['top', 'bottom'].indexOf(calculatedPosition) !== -1) {
      calculatedPosition += _determineAutoAlignment(targetElementRect.left, tooltipWidth, windowSize, desiredAlignment);
    }

    return calculatedPosition;
  }

  /**
  * auto-determine alignment
  * @param {Integer}  offsetLeft
  * @param {Integer}  tooltipWidth
  * @param {Object}   windowSize
  * @param {String}   desiredAlignment
  * @return {String}  calculatedAlignment
  */
  function _determineAutoAlignment (offsetLeft, tooltipWidth, windowSize, desiredAlignment) {
    var halfTooltipWidth = tooltipWidth / 2,
      winWidth = Math.min(windowSize.width, window.screen.width),
      possibleAlignments = ['-left-aligned', '-middle-aligned', '-right-aligned'],
      calculatedAlignment = '';
    
    // valid left must be at least a tooltipWidth
    // away from right side
    if (winWidth - offsetLeft < tooltipWidth) {
      _removeEntry(possibleAlignments, '-left-aligned');
    }

    // valid middle must be at least half 
    // width away from both sides
    if (offsetLeft < halfTooltipWidth || 
      winWidth - offsetLeft < halfTooltipWidth) {
      _removeEntry(possibleAlignments, '-middle-aligned');
    }

    // valid right must be at least a tooltipWidth
    // width away from left side
    if (offsetLeft < tooltipWidth) {
      _removeEntry(possibleAlignments, '-right-aligned');
    }

    if (possibleAlignments.length) {
      if (possibleAlignments.indexOf(desiredAlignment) !== -1) {
        // the desired alignment is valid
        calculatedAlignment = desiredAlignment;
      } else {
        // pick the first valid position, in order
        calculatedAlignment = possibleAlignments[0];
      }
    } else {
      // if screen width is too small 
      // for ANY alignment, middle is 
      // probably the best for visibility
      calculatedAlignment = '-middle-aligned';
    }

    return calculatedAlignment;
  }

  /**
   * Remove an entry from a string array if it's there, does nothing if it isn't there.
   *
   * @param {Array} stringArray
   * @param {String} stringToRemove
   */
  function _removeEntry(stringArray, stringToRemove) {
    if (stringArray.indexOf(stringToRemove) > -1) {
      stringArray.splice(stringArray.indexOf(stringToRemove), 1);
    }
  }

  /**
   * Update the position of the helper layer on the screen
   *
   * @api private
   * @method _setHelperLayerPosition
   * @param {Object} helperLayer
   */
  function _setHelperLayerPosition(helperLayer) {
    if (helperLayer) {
      //prevent error when `this._currentStep` in undefined
      if (!this._introItems[this._currentStep]) return;

      var currentElement  = this._introItems[this._currentStep],
          elementPosition = _getOffset(currentElement.element),
          widthHeightPadding = this._options.helperElementPadding;

      // If the target element is fixed, the tooltip should be fixed as well.
      // Otherwise, remove a fixed class that may be left over from the previous
      // step.
      if (_isFixed(currentElement.element)) {
        _addClass(helperLayer, 'introjs-fixedTooltip');
      } else {
        _removeClass(helperLayer, 'introjs-fixedTooltip');
      }

      if (currentElement.position === 'floating') {
        widthHeightPadding = 0;
      }

      //set new position to helper layer
      helperLayer.style.cssText = 'width: ' + (elementPosition.width  + widthHeightPadding)  + 'px; ' +
                                        'height:' + (elementPosition.height + widthHeightPadding)  + 'px; ' +
                                        'top:'    + (elementPosition.top    - widthHeightPadding / 2)   + 'px;' +
                                        'left: '  + (elementPosition.left   - widthHeightPadding / 2)   + 'px;';

    }
  }

  /**
   * Add disableinteraction layer and adjust the size and position of the layer
   *
   * @api private
   * @method _disableInteraction
   */
  function _disableInteraction() {
    var disableInteractionLayer = document.querySelector('.introjs-disableInteraction');

    if (disableInteractionLayer === null) {
      disableInteractionLayer = document.createElement('div');
      disableInteractionLayer.className = 'introjs-disableInteraction';
      this._targetElement.appendChild(disableInteractionLayer);
    }

    _setHelperLayerPosition.call(this, disableInteractionLayer);
  }

  /**
   * Setting anchors to behave like buttons
   *
   * @api private
   * @method _setAnchorAsButton
   */
  function _setAnchorAsButton(anchor){
    anchor.setAttribute('role', 'button');
    anchor.tabIndex = 0;
  }

  /**
   * Show an element on the page
   *
   * @api private
   * @method _showElement
   * @param {Object} targetElement
   */
  function _showElement(targetElement) {
    if (typeof (this._introChangeCallback) !== 'undefined') {
      this._introChangeCallback.call(this, targetElement.element);
    }

    var self = this,
        oldHelperLayer = document.querySelector('.introjs-helperLayer'),
        oldReferenceLayer = document.querySelector('.introjs-tooltipReferenceLayer'),
        highlightClass = 'introjs-helperLayer',
        nextTooltipButton,
        prevTooltipButton,
        skipTooltipButton,
        scrollParent;

    //check for a current step highlight class
    if (typeof (targetElement.highlightClass) === 'string') {
      highlightClass += (' ' + targetElement.highlightClass);
    }
    //check for options highlight class
    if (typeof (this._options.highlightClass) === 'string') {
      highlightClass += (' ' + this._options.highlightClass);
    }

    if (oldHelperLayer !== null) {
      var oldHelperNumberLayer = oldReferenceLayer.querySelector('.introjs-helperNumberLayer'),
          oldtooltipLayer      = oldReferenceLayer.querySelector('.introjs-tooltiptext'),
          oldArrowLayer        = oldReferenceLayer.querySelector('.introjs-arrow'),
          oldtooltipContainer  = oldReferenceLayer.querySelector('.introjs-tooltip');
          
      skipTooltipButton    = oldReferenceLayer.querySelector('.introjs-skipbutton');
      prevTooltipButton    = oldReferenceLayer.querySelector('.introjs-prevbutton');
      nextTooltipButton    = oldReferenceLayer.querySelector('.introjs-nextbutton');

      //update or reset the helper highlight class
      oldHelperLayer.className = highlightClass;
      //hide the tooltip
      oldtooltipContainer.style.opacity = 0;
      oldtooltipContainer.style.display = "none";

      if (oldHelperNumberLayer !== null) {
        var lastIntroItem = this._introItems[(targetElement.step - 2 >= 0 ? targetElement.step - 2 : 0)];

        if (lastIntroItem !== null && (this._direction === 'forward' && lastIntroItem.position === 'floating') || (this._direction === 'backward' && targetElement.position === 'floating')) {
          oldHelperNumberLayer.style.opacity = 0;
        }
      }

      // scroll to element
      scrollParent = _getScrollParent( targetElement.element );

      if (scrollParent !== document.body) {
        // target is within a scrollable element
        _scrollParentToElement(scrollParent, targetElement.element);
      }

      // set new position to helper layer
      _setHelperLayerPosition.call(self, oldHelperLayer);
      _setHelperLayerPosition.call(self, oldReferenceLayer);

      //remove `introjs-fixParent` class from the elements
      var fixParents = document.querySelectorAll('.introjs-fixParent');
      _forEach(fixParents, function (parent) {
        _removeClass(parent, /introjs-fixParent/g);
      });
      
      //remove old classes if the element still exist
      _removeShowElement();

      //we should wait until the CSS3 transition is competed (it's 0.3 sec) to prevent incorrect `height` and `width` calculation
      if (self._lastShowElementTimer) {
        window.clearTimeout(self._lastShowElementTimer);
      }

      self._lastShowElementTimer = window.setTimeout(function() {
        //set current step to the label
        if (oldHelperNumberLayer !== null) {
          oldHelperNumberLayer.innerHTML = targetElement.step;
        }
        //set current tooltip text
        oldtooltipLayer.innerHTML = targetElement.intro;
        //set the tooltip position
        oldtooltipContainer.style.display = "block";
        _placeTooltip.call(self, targetElement.element, oldtooltipContainer, oldArrowLayer, oldHelperNumberLayer);

        //change active bullet
        if (self._options.showBullets) {
            oldReferenceLayer.querySelector('.introjs-bullets li > a.active').className = '';
            oldReferenceLayer.querySelector('.introjs-bullets li > a[data-stepnumber="' + targetElement.step + '"]').className = 'active';
        }
        oldReferenceLayer.querySelector('.introjs-progress .introjs-progressbar').style.cssText = 'width:' + _getProgress.call(self) + '%;';
        oldReferenceLayer.querySelector('.introjs-progress .introjs-progressbar').setAttribute('aria-valuenow', _getProgress.call(self));

        //show the tooltip
        oldtooltipContainer.style.opacity = 1;
        if (oldHelperNumberLayer) oldHelperNumberLayer.style.opacity = 1;

        //reset button focus
        if (typeof skipTooltipButton !== "undefined" && skipTooltipButton !== null && /introjs-donebutton/gi.test(skipTooltipButton.className)) {
          // skip button is now "done" button
          skipTooltipButton.focus();
        } else if (typeof nextTooltipButton !== "undefined" && nextTooltipButton !== null) {
          //still in the tour, focus on next
          nextTooltipButton.focus();
        }

        // change the scroll of the window, if needed
        _scrollTo.call(self, targetElement.scrollTo, targetElement, oldtooltipLayer);
      }, 350);

      // end of old element if-else condition
    } else {
      var helperLayer       = document.createElement('div'),
          referenceLayer    = document.createElement('div'),
          arrowLayer        = document.createElement('div'),
          tooltipLayer      = document.createElement('div'),
          tooltipTextLayer  = document.createElement('div'),
          bulletsLayer      = document.createElement('div'),
          progressLayer     = document.createElement('div'),
          buttonsLayer      = document.createElement('div');

      helperLayer.className = highlightClass;
      referenceLayer.className = 'introjs-tooltipReferenceLayer';

      // scroll to element
      scrollParent = _getScrollParent( targetElement.element );

      if (scrollParent !== document.body) {
        // target is within a scrollable element
        _scrollParentToElement(scrollParent, targetElement.element);
      }

      //set new position to helper layer
      _setHelperLayerPosition.call(self, helperLayer);
      _setHelperLayerPosition.call(self, referenceLayer);

      //add helper layer to target element
      this._targetElement.appendChild(helperLayer);
      this._targetElement.appendChild(referenceLayer);

      arrowLayer.className = 'introjs-arrow';

      tooltipTextLayer.className = 'introjs-tooltiptext';
      tooltipTextLayer.innerHTML = targetElement.intro;

      bulletsLayer.className = 'introjs-bullets';

      if (this._options.showBullets === false) {
        bulletsLayer.style.display = 'none';
      }

      var ulContainer = document.createElement('ul');
      ulContainer.setAttribute('role', 'tablist');

      var anchorClick = function () {
          self.goToStep(this.getAttribute('data-stepnumber'));
      };

      _forEach(this._introItems, function (item, i) {
        var innerLi    = document.createElement('li');
        var anchorLink = document.createElement('a');
        
        innerLi.setAttribute('role', 'presentation');
        anchorLink.setAttribute('role', 'tab');

        anchorLink.onclick = anchorClick;

        if (i === (targetElement.step-1)) {
          anchorLink.className = 'active';
        } 

        _setAnchorAsButton(anchorLink);
        anchorLink.innerHTML = "&nbsp;";
        anchorLink.setAttribute('data-stepnumber', item.step);

        innerLi.appendChild(anchorLink);
        ulContainer.appendChild(innerLi);
      });

      bulletsLayer.appendChild(ulContainer);

      progressLayer.className = 'introjs-progress';

      if (this._options.showProgress === false) {
        progressLayer.style.display = 'none';
      }
      var progressBar = document.createElement('div');
      progressBar.className = 'introjs-progressbar';
      progressBar.setAttribute('role', 'progress');
      progressBar.setAttribute('aria-valuemin', 0);
      progressBar.setAttribute('aria-valuemax', 100);
      progressBar.setAttribute('aria-valuenow', _getProgress.call(this));
      progressBar.style.cssText = 'width:' + _getProgress.call(this) + '%;';

      progressLayer.appendChild(progressBar);

      buttonsLayer.className = 'introjs-tooltipbuttons';
      if (this._options.showButtons === false) {
        buttonsLayer.style.display = 'none';
      }

      tooltipLayer.className = 'introjs-tooltip';
      tooltipLayer.appendChild(tooltipTextLayer);
      tooltipLayer.appendChild(bulletsLayer);
      tooltipLayer.appendChild(progressLayer);

      //add helper layer number
      var helperNumberLayer = document.createElement('span');
      if (this._options.showStepNumbers === true) {
        helperNumberLayer.className = 'introjs-helperNumberLayer';
        helperNumberLayer.innerHTML = targetElement.step;
        referenceLayer.appendChild(helperNumberLayer);
      }

      tooltipLayer.appendChild(arrowLayer);
      referenceLayer.appendChild(tooltipLayer);

      //next button
      nextTooltipButton = document.createElement('a');

      nextTooltipButton.onclick = function() {
        if (self._introItems.length - 1 !== self._currentStep) {
          _nextStep.call(self);
        }
      };

      _setAnchorAsButton(nextTooltipButton);
      nextTooltipButton.innerHTML = this._options.nextLabel;

      //previous button
      prevTooltipButton = document.createElement('a');

      prevTooltipButton.onclick = function() {
        if (self._currentStep !== 0) {
          _previousStep.call(self);
        }
      };

      _setAnchorAsButton(prevTooltipButton);
      prevTooltipButton.innerHTML = this._options.prevLabel;

      //skip button
      skipTooltipButton = document.createElement('a');
      skipTooltipButton.className = this._options.buttonClass + ' introjs-skipbutton ';
      _setAnchorAsButton(skipTooltipButton);
      skipTooltipButton.innerHTML = this._options.skipLabel;

      skipTooltipButton.onclick = function() {
        if (self._introItems.length - 1 === self._currentStep && typeof (self._introCompleteCallback) === 'function') {
          self._introCompleteCallback.call(self);
        }

        if (self._introItems.length - 1 !== self._currentStep && typeof (self._introExitCallback) === 'function') {
          self._introExitCallback.call(self);
        }

        if (typeof(self._introSkipCallback) === 'function') {
          self._introSkipCallback.call(self);
        }

        _exitIntro.call(self, self._targetElement);
      };

      buttonsLayer.appendChild(skipTooltipButton);

      //in order to prevent displaying next/previous button always
      if (this._introItems.length > 1) {
        buttonsLayer.appendChild(prevTooltipButton);
        buttonsLayer.appendChild(nextTooltipButton);
      }

      tooltipLayer.appendChild(buttonsLayer);

      //set proper position
      _placeTooltip.call(self, targetElement.element, tooltipLayer, arrowLayer, helperNumberLayer);

      // change the scroll of the window, if needed
      _scrollTo.call(this, targetElement.scrollTo, targetElement, tooltipLayer);

      //end of new element if-else condition
    }

    // removing previous disable interaction layer
    var disableInteractionLayer = self._targetElement.querySelector('.introjs-disableInteraction');
    if (disableInteractionLayer) {
      disableInteractionLayer.parentNode.removeChild(disableInteractionLayer);
    }

    //disable interaction
    if (targetElement.disableInteraction) {
      _disableInteraction.call(self);
    }

    // when it's the first step of tour
    if (this._currentStep === 0 && this._introItems.length > 1) {
      if (typeof skipTooltipButton !== "undefined" && skipTooltipButton !== null) {
        skipTooltipButton.className = this._options.buttonClass + ' introjs-skipbutton';
      }
      if (typeof nextTooltipButton !== "undefined" && nextTooltipButton !== null) {
        nextTooltipButton.className = this._options.buttonClass + ' introjs-nextbutton';
      }

      if (this._options.hidePrev === true) {
        if (typeof prevTooltipButton !== "undefined" && prevTooltipButton !== null) {
          prevTooltipButton.className = this._options.buttonClass + ' introjs-prevbutton introjs-hidden';
        }
        if (typeof nextTooltipButton !== "undefined" && nextTooltipButton !== null) {
          _addClass(nextTooltipButton, 'introjs-fullbutton');
        }
      } else {
        if (typeof prevTooltipButton !== "undefined" && prevTooltipButton !== null) {
          prevTooltipButton.className = this._options.buttonClass + ' introjs-prevbutton introjs-disabled';
        }
      }

      if (typeof skipTooltipButton !== "undefined" && skipTooltipButton !== null) {
        skipTooltipButton.innerHTML = this._options.skipLabel;
      }
    } else if (this._introItems.length - 1 === this._currentStep || this._introItems.length === 1) {
      // last step of tour
      if (typeof skipTooltipButton !== "undefined" && skipTooltipButton !== null) {
        skipTooltipButton.innerHTML = this._options.doneLabel;
        // adding donebutton class in addition to skipbutton
        _addClass(skipTooltipButton, 'introjs-donebutton');
      }
      if (typeof prevTooltipButton !== "undefined" && prevTooltipButton !== null) {
        prevTooltipButton.className = this._options.buttonClass + ' introjs-prevbutton';
      }

      if (this._options.hideNext === true) {
        if (typeof nextTooltipButton !== "undefined" && nextTooltipButton !== null) {
          nextTooltipButton.className = this._options.buttonClass + ' introjs-nextbutton introjs-hidden';
        }
        if (typeof prevTooltipButton !== "undefined" && prevTooltipButton !== null) {
          _addClass(prevTooltipButton, 'introjs-fullbutton');
        }
      } else {
        if (typeof nextTooltipButton !== "undefined" && nextTooltipButton !== null) {
          nextTooltipButton.className = this._options.buttonClass + ' introjs-nextbutton introjs-disabled';
        }
      }
    } else {
      // steps between start and end
      if (typeof skipTooltipButton !== "undefined" && skipTooltipButton !== null) {
        skipTooltipButton.className = this._options.buttonClass + ' introjs-skipbutton';
      }
      if (typeof prevTooltipButton !== "undefined" && prevTooltipButton !== null) {
        prevTooltipButton.className = this._options.buttonClass + ' introjs-prevbutton';
      }
      if (typeof nextTooltipButton !== "undefined" && nextTooltipButton !== null) {
        nextTooltipButton.className = this._options.buttonClass + ' introjs-nextbutton';
      }
      if (typeof skipTooltipButton !== "undefined" && skipTooltipButton !== null) {
        skipTooltipButton.innerHTML = this._options.skipLabel;
      }
    }

    prevTooltipButton.setAttribute('role', 'button');
    nextTooltipButton.setAttribute('role', 'button');
    skipTooltipButton.setAttribute('role', 'button');

    //Set focus on "next" button, so that hitting Enter always moves you onto the next step
    if (typeof nextTooltipButton !== "undefined" && nextTooltipButton !== null) {
      nextTooltipButton.focus();
    }

    _setShowElement(targetElement);

    if (typeof (this._introAfterChangeCallback) !== 'undefined') {
      this._introAfterChangeCallback.call(this, targetElement.element);
    }
  }

  /**
   * To change the scroll of `window` after highlighting an element
   *
   * @api private
   * @method _scrollTo
   * @param {String} scrollTo
   * @param {Object} targetElement
   * @param {Object} tooltipLayer
   */
  function _scrollTo(scrollTo, targetElement, tooltipLayer) {
    if (scrollTo === 'off') return;  
    var rect;

    if (!this._options.scrollToElement) return;

    if (scrollTo === 'tooltip') {
      rect = tooltipLayer.getBoundingClientRect();
    } else {
      rect = targetElement.element.getBoundingClientRect();
    }

    if (!_elementInViewport(targetElement.element)) {
      var winHeight = _getWinSize().height;
      var top = rect.bottom - (rect.bottom - rect.top);

      // TODO (afshinm): do we need scroll padding now?
      // I have changed the scroll option and now it scrolls the window to
      // the center of the target element or tooltip.

      if (top < 0 || targetElement.element.clientHeight > winHeight) {
        window.scrollBy(0, rect.top - ((winHeight / 2) -  (rect.height / 2)) - this._options.scrollPadding); // 30px padding from edge to look nice

      //Scroll down
      } else {
        window.scrollBy(0, rect.top - ((winHeight / 2) -  (rect.height / 2)) + this._options.scrollPadding); // 30px padding from edge to look nice
      }
    }
  }

  /**
   * To remove all show element(s)
   *
   * @api private
   * @method _removeShowElement
   */
  function _removeShowElement() {
    var elms = document.querySelectorAll('.introjs-showElement');

    _forEach(elms, function (elm) {
      _removeClass(elm, /introjs-[a-zA-Z]+/g);
    });
  }

  /**
   * To set the show element
   * This function set a relative (in most cases) position and changes the z-index
   *
   * @api private
   * @method _setShowElement
   * @param {Object} targetElement
   */
  function _setShowElement(targetElement) {
    var parentElm;
    // we need to add this show element class to the parent of SVG elements
    // because the SVG elements can't have independent z-index
    if (targetElement.element instanceof SVGElement) {
      parentElm = targetElement.element.parentNode;

      while (targetElement.element.parentNode !== null) {
        if (!parentElm.tagName || parentElm.tagName.toLowerCase() === 'body') break;

        if (parentElm.tagName.toLowerCase() === 'svg') {
          _addClass(parentElm, 'introjs-showElement introjs-relativePosition');
        }

        parentElm = parentElm.parentNode;
      }
    }

    _addClass(targetElement.element, 'introjs-showElement');

    var currentElementPosition = _getPropValue(targetElement.element, 'position');
    if (currentElementPosition !== 'absolute' &&
        currentElementPosition !== 'relative' &&
        currentElementPosition !== 'fixed') {
      //change to new intro item
      _addClass(targetElement.element, 'introjs-relativePosition');
    }

    parentElm = targetElement.element.parentNode;
    while (parentElm !== null) {
      if (!parentElm.tagName || parentElm.tagName.toLowerCase() === 'body') break;

      //fix The Stacking Context problem.
      //More detail: https://developer.mozilla.org/en-US/docs/Web/Guide/CSS/Understanding_z_index/The_stacking_context
      var zIndex = _getPropValue(parentElm, 'z-index');
      var opacity = parseFloat(_getPropValue(parentElm, 'opacity'));
      var transform = _getPropValue(parentElm, 'transform') || _getPropValue(parentElm, '-webkit-transform') || _getPropValue(parentElm, '-moz-transform') || _getPropValue(parentElm, '-ms-transform') || _getPropValue(parentElm, '-o-transform');
      if (/[0-9]+/.test(zIndex) || opacity < 1 || (transform !== 'none' && transform !== undefined)) {
        _addClass(parentElm, 'introjs-fixParent');
      }

      parentElm = parentElm.parentNode;
    }
  }

  /**
  * Iterates arrays
  *
  * @param {Array} arr
  * @param {Function} forEachFnc
  * @param {Function} completeFnc
  * @return {Null}
  */
  function _forEach(arr, forEachFnc, completeFnc) {
    // in case arr is an empty query selector node list
    if (arr) {
      for (var i = 0, len = arr.length; i < len; i++) {
        forEachFnc(arr[i], i);
      }
    }

    if (typeof(completeFnc) === 'function') {
      completeFnc();
    }
  }

  /**
  * Mark any object with an incrementing number
  * used for keeping track of objects
  *
  * @param Object obj   Any object or DOM Element
  * @param String key
  * @return Object
  */
  var _stamp = (function () {
    var keys = {};
    return function stamp (obj, key) {
      
      // get group key
      key = key || 'introjs-stamp';

      // each group increments from 0
      keys[key] = keys[key] || 0;

      // stamp only once per object
      if (obj[key] === undefined) {
        // increment key for each new object
        obj[key] = keys[key]++;
      }

      return obj[key];
    };
  })();

  /**
  * DOMEvent Handles all DOM events
  *
  * methods:
  *
  * on - add event handler
  * off - remove event
  */
  var DOMEvent = (function () {
    function DOMEvent () {
      var events_key = 'introjs_event';
      
      /**
      * Gets a unique ID for an event listener
      *
      * @param Object obj
      * @param String type        event type
      * @param Function listener
      * @param Object context
      * @return String
      */
      this._id = function (obj, type, listener, context) {
        return type + _stamp(listener) + (context ? '_' + _stamp(context) : '');
      };

      /**
      * Adds event listener
      *
      * @param Object obj
      * @param String type        event type
      * @param Function listener
      * @param Object context
      * @param Boolean useCapture
      * @return null
      */
      this.on = function (obj, type, listener, context, useCapture) {
        var id = this._id.apply(this, arguments),
            handler = function (e) {
              return listener.call(context || obj, e || window.event);
            };

        if ('addEventListener' in obj) {
          obj.addEventListener(type, handler, useCapture);
        } else if ('attachEvent' in obj) {
          obj.attachEvent('on' + type, handler);
        }

        obj[events_key] = obj[events_key] || {};
        obj[events_key][id] = handler;
      };

      /**
      * Removes event listener
      *
      * @param Object obj
      * @param String type        event type
      * @param Function listener
      * @param Object context
      * @param Boolean useCapture
      * @return null
      */
      this.off = function (obj, type, listener, context, useCapture) {
        var id = this._id.apply(this, arguments),
            handler = obj[events_key] && obj[events_key][id];

        if (!handler) {
          return;
        }

        if ('removeEventListener' in obj) {
          obj.removeEventListener(type, handler, useCapture);
        } else if ('detachEvent' in obj) {
          obj.detachEvent('on' + type, handler);
        }

        obj[events_key][id] = null;
      };
    }

    return new DOMEvent();
  })();

  /**
   * Append a class to an element
   *
   * @api private
   * @method _addClass
   * @param {Object} element
   * @param {String} className
   * @returns null
   */
  function _addClass(element, className) {
    if (element instanceof SVGElement) {
      // svg
      var pre = element.getAttribute('class') || '';

      element.setAttribute('class', pre + ' ' + className);
    } else {
      if (element.classList !== undefined) {
        // check for modern classList property
        var classes = className.split(' ');
        _forEach(classes, function (cls) {
          element.classList.add( cls );
        });
      } else if (!element.className.match( className )) {
        // check if element doesn't already have className
        element.className += ' ' + className;
      }
    }
  }

  /**
   * Remove a class from an element
   *
   * @api private
   * @method _removeClass
   * @param {Object} element
   * @param {RegExp|String} classNameRegex can be regex or string
   * @returns null
   */
  function _removeClass(element, classNameRegex) {
    if (element instanceof SVGElement) {
      var pre = element.getAttribute('class') || '';

      element.setAttribute('class', pre.replace(classNameRegex, '').replace(/^\s+|\s+$/g, ''));
    } else {
      element.className = element.className.replace(classNameRegex, '').replace(/^\s+|\s+$/g, '');
    }
  }

  /**
   * Get an element CSS property on the page
   * Thanks to JavaScript Kit: http://www.javascriptkit.com/dhtmltutors/dhtmlcascade4.shtml
   *
   * @api private
   * @method _getPropValue
   * @param {Object} element
   * @param {String} propName
   * @returns Element's property value
   */
  function _getPropValue (element, propName) {
    var propValue = '';
    if (element.currentStyle) { //IE
      propValue = element.currentStyle[propName];
    } else if (document.defaultView && document.defaultView.getComputedStyle) { //Others
      propValue = document.defaultView.getComputedStyle(element, null).getPropertyValue(propName);
    }

    //Prevent exception in IE
    if (propValue && propValue.toLowerCase) {
      return propValue.toLowerCase();
    } else {
      return propValue;
    }
  }

  /**
   * Checks to see if target element (or parents) position is fixed or not
   *
   * @api private
   * @method _isFixed
   * @param {Object} element
   * @returns Boolean
   */
  function _isFixed (element) {
    var p = element.parentNode;

    if (!p || p.nodeName === 'HTML') {
      return false;
    }

    if (_getPropValue(element, 'position') === 'fixed') {
      return true;
    }

    return _isFixed(p);
  }

  /**
   * Provides a cross-browser way to get the screen dimensions
   * via: http://stackoverflow.com/questions/5864467/internet-explorer-innerheight
   *
   * @api private
   * @method _getWinSize
   * @returns {Object} width and height attributes
   */
  function _getWinSize() {
    if (window.innerWidth !== undefined) {
      return { width: window.innerWidth, height: window.innerHeight };
    } else {
      var D = document.documentElement;
      return { width: D.clientWidth, height: D.clientHeight };
    }
  }

  /**
   * Check to see if the element is in the viewport or not
   * http://stackoverflow.com/questions/123999/how-to-tell-if-a-dom-element-is-visible-in-the-current-viewport
   *
   * @api private
   * @method _elementInViewport
   * @param {Object} el
   */
  function _elementInViewport(el) {
    var rect = el.getBoundingClientRect();

    return (
      rect.top >= 0 &&
      rect.left >= 0 &&
      (rect.bottom+80) <= window.innerHeight && // add 80 to get the text right
      rect.right <= window.innerWidth
    );
  }

  /**
   * Add overlay layer to the page
   *
   * @api private
   * @method _addOverlayLayer
   * @param {Object} targetElm
   */
  function _addOverlayLayer(targetElm) {
    var overlayLayer = document.createElement('div'),
        styleText = '',
        self = this;

    //set css class name
    overlayLayer.className = 'introjs-overlay';

    //check if the target element is body, we should calculate the size of overlay layer in a better way
    if (!targetElm.tagName || targetElm.tagName.toLowerCase() === 'body') {
      styleText += 'top: 0;bottom: 0; left: 0;right: 0;position: fixed;';
      overlayLayer.style.cssText = styleText;
    } else {
      //set overlay layer position
      var elementPosition = _getOffset(targetElm);
      if (elementPosition) {
        styleText += 'width: ' + elementPosition.width + 'px; height:' + elementPosition.height + 'px; top:' + elementPosition.top + 'px;left: ' + elementPosition.left + 'px;';
        overlayLayer.style.cssText = styleText;
      }
    }

    targetElm.appendChild(overlayLayer);

    overlayLayer.onclick = function() {
      if (self._options.exitOnOverlayClick === true) {
        _exitIntro.call(self, targetElm);
      }
    };

    window.setTimeout(function() {
      styleText += 'opacity: ' + self._options.overlayOpacity.toString() + ';';
      overlayLayer.style.cssText = styleText;
    }, 10);

    return true;
  }

  /**
   * Removes open hint (tooltip hint)
   *
   * @api private
   * @method _removeHintTooltip
   */
  function _removeHintTooltip() {
    var tooltip = document.querySelector('.introjs-hintReference');

    if (tooltip) {
      var step = tooltip.getAttribute('data-step');
      tooltip.parentNode.removeChild(tooltip);
      return step;
    }
  }

  /**
   * Start parsing hint items
   *
   * @api private
   * @param {Object} targetElm
   * @method _startHint
   */
  function _populateHints(targetElm) {

    this._introItems = [];

    if (this._options.hints) {
      _forEach(this._options.hints, function (hint) {
        var currentItem = _cloneObject(hint);

        if (typeof(currentItem.element) === 'string') {
          //grab the element with given selector from the page
          currentItem.element = document.querySelector(currentItem.element);
        }

        currentItem.hintPosition = currentItem.hintPosition || this._options.hintPosition;
        currentItem.hintAnimation = currentItem.hintAnimation || this._options.hintAnimation;

        if (currentItem.element !== null) {
          this._introItems.push(currentItem);
        }
      }.bind(this));
    } else {
      var hints = targetElm.querySelectorAll('*[data-hint]');

      if (!hints || !hints.length) {
        return false;
      }

      //first add intro items with data-step
      _forEach(hints, function (currentElement) {
        // hint animation
        var hintAnimation = currentElement.getAttribute('data-hintanimation');

        if (hintAnimation) {
          hintAnimation = (hintAnimation === 'true');
        } else {
          hintAnimation = this._options.hintAnimation;
        }

        this._introItems.push({
          element: currentElement,
          hint: currentElement.getAttribute('data-hint'),
          hintPosition: currentElement.getAttribute('data-hintposition') || this._options.hintPosition,
          hintAnimation: hintAnimation,
          tooltipClass: currentElement.getAttribute('data-tooltipclass'),
          position: currentElement.getAttribute('data-position') || this._options.tooltipPosition
        });
      }.bind(this));
    }

    _addHints.call(this);

    /* 
    todo:
    these events should be removed at some point 
    */
    DOMEvent.on(document, 'click', _removeHintTooltip, this, false);
    DOMEvent.on(window, 'resize', _reAlignHints, this, true);
  }

  /**
   * Re-aligns all hint elements
   *
   * @api private
   * @method _reAlignHints
   */
  function _reAlignHints() {
    _forEach(this._introItems, function (item) {
      if (typeof(item.targetElement) === 'undefined') {
        return;
      }

      _alignHintPosition.call(this, item.hintPosition, item.element, item.targetElement);
    }.bind(this));
  }

  /**
  * Get a queryselector within the hint wrapper
  *
  * @param {String} selector
  * @return {NodeList|Array}
  */
  function _hintQuerySelectorAll(selector) {
    var hintsWrapper = document.querySelector('.introjs-hints');
    return (hintsWrapper) ? hintsWrapper.querySelectorAll(selector) : [];
  }

  /**
   * Hide a hint
   *
   * @api private
   * @method _hideHint
   */
  function _hideHint(stepId) {
    var hint = _hintQuerySelectorAll('.introjs-hint[data-step="' + stepId + '"]')[0];
    
    _removeHintTooltip.call(this);

    if (hint) {
      _addClass(hint, 'introjs-hidehint');
    }

    // call the callback function (if any)
    if (typeof (this._hintCloseCallback) !== 'undefined') {
      this._hintCloseCallback.call(this, stepId);
    }
  }

  /**
   * Hide all hints
   *
   * @api private
   * @method _hideHints
   */
  function _hideHints() {
    var hints = _hintQuerySelectorAll('.introjs-hint');

    _forEach(hints, function (hint) {
      _hideHint.call(this, hint.getAttribute('data-step'));
    }.bind(this));
  }

  /**
   * Show all hints
   *
   * @api private
   * @method _showHints
   */
  function _showHints() {
    var hints = _hintQuerySelectorAll('.introjs-hint');

    if (hints && hints.length) {
      _forEach(hints, function (hint) {
        _showHint.call(this, hint.getAttribute('data-step'));
      }.bind(this));
    } else {
      _populateHints.call(this, this._targetElement);
    }
  }

  /**
   * Show a hint
   *
   * @api private
   * @method _showHint
   */
  function _showHint(stepId) {
    var hint = _hintQuerySelectorAll('.introjs-hint[data-step="' + stepId + '"]')[0];

    if (hint) {
      _removeClass(hint, /introjs-hidehint/g);
    }
  }

  /**
   * Removes all hint elements on the page
   * Useful when you want to destroy the elements and add them again (e.g. a modal or popup)
   *
   * @api private
   * @method _removeHints
   */
  function _removeHints() {
    var hints = _hintQuerySelectorAll('.introjs-hint');

    _forEach(hints, function (hint) {
      _removeHint.call(this, hint.getAttribute('data-step'));
    }.bind(this));
  }

  /**
   * Remove one single hint element from the page
   * Useful when you want to destroy the element and add them again (e.g. a modal or popup)
   * Use removeHints if you want to remove all elements.
   *
   * @api private
   * @method _removeHint
   */
  function _removeHint(stepId) {
    var hint = _hintQuerySelectorAll('.introjs-hint[data-step="' + stepId + '"]')[0];

    if (hint) {
      hint.parentNode.removeChild(hint);
    }
  }

  /**
   * Add all available hints to the page
   *
   * @api private
   * @method _addHints
   */
  function _addHints() {
    var self = this;

    var hintsWrapper = document.querySelector('.introjs-hints');

    if (hintsWrapper === null) {
      hintsWrapper = document.createElement('div');
      hintsWrapper.className = 'introjs-hints';
    }

    /**
    * Returns an event handler unique to the hint iteration
    * 
    * @param {Integer} i
    * @return {Function}
    */
    var getHintClick = function (i) {
      return function(e) {
        var evt = e ? e : window.event;
        
        if (evt.stopPropagation) {
          evt.stopPropagation();
        }

        if (evt.cancelBubble !== null) {
          evt.cancelBubble = true;
        }

        _showHintDialog.call(self, i);
      };
    };

    _forEach(this._introItems, function(item, i) {
      // avoid append a hint twice
      if (document.querySelector('.introjs-hint[data-step="' + i + '"]')) {
        return;
      }

      var hint = document.createElement('a');
      _setAnchorAsButton(hint);

      hint.onclick = getHintClick(i);

      hint.className = 'introjs-hint';

      if (!item.hintAnimation) {
        _addClass(hint, 'introjs-hint-no-anim');
      }

      // hint's position should be fixed if the target element's position is fixed
      if (_isFixed(item.element)) {
        _addClass(hint, 'introjs-fixedhint');
      }

      var hintDot = document.createElement('div');
      hintDot.className = 'introjs-hint-dot';
      var hintPulse = document.createElement('div');
      hintPulse.className = 'introjs-hint-pulse';

      hint.appendChild(hintDot);
      hint.appendChild(hintPulse);
      hint.setAttribute('data-step', i);

      // we swap the hint element with target element
      // because _setHelperLayerPosition uses `element` property
      item.targetElement = item.element;
      item.element = hint;

      // align the hint position
      _alignHintPosition.call(this, item.hintPosition, hint, item.targetElement);

      hintsWrapper.appendChild(hint);
    }.bind(this));

    // adding the hints wrapper
    document.body.appendChild(hintsWrapper);

    // call the callback function (if any)
    if (typeof (this._hintsAddedCallback) !== 'undefined') {
      this._hintsAddedCallback.call(this);
    }
  }

  /**
   * Aligns hint position
   *
   * @api private
   * @method _alignHintPosition
   * @param {String} position
   * @param {Object} hint
   * @param {Object} element
   */
  function _alignHintPosition(position, hint, element) {
    // get/calculate offset of target element
    var offset = _getOffset.call(this, element);
    var iconWidth = 20;
    var iconHeight = 20;

    // align the hint element
    switch (position) {
      default:
      case 'top-left':
        hint.style.left = offset.left + 'px';
        hint.style.top = offset.top + 'px';
        break;
      case 'top-right':
        hint.style.left = (offset.left + offset.width - iconWidth) + 'px';
        hint.style.top = offset.top + 'px';
        break;
      case 'bottom-left':
        hint.style.left = offset.left + 'px';
        hint.style.top = (offset.top + offset.height - iconHeight) + 'px';
        break;
      case 'bottom-right':
        hint.style.left = (offset.left + offset.width - iconWidth) + 'px';
        hint.style.top = (offset.top + offset.height - iconHeight) + 'px';
        break;
      case 'middle-left':
        hint.style.left = offset.left + 'px';
        hint.style.top = (offset.top + (offset.height - iconHeight) / 2) + 'px';
        break;
      case 'middle-right':
        hint.style.left = (offset.left + offset.width - iconWidth) + 'px';
        hint.style.top = (offset.top + (offset.height - iconHeight) / 2) + 'px';
        break;
      case 'middle-middle':
        hint.style.left = (offset.left + (offset.width - iconWidth) / 2) + 'px';
        hint.style.top = (offset.top + (offset.height - iconHeight) / 2) + 'px';
        break;
      case 'bottom-middle':
        hint.style.left = (offset.left + (offset.width - iconWidth) / 2) + 'px';
        hint.style.top = (offset.top + offset.height - iconHeight) + 'px';
        break;
      case 'top-middle':
        hint.style.left = (offset.left + (offset.width - iconWidth) / 2) + 'px';
        hint.style.top = offset.top + 'px';
        break;
    }
  }

  /**
   * Triggers when user clicks on the hint element
   *
   * @api private
   * @method _showHintDialog
   * @param {Number} stepId
   */
  function _showHintDialog(stepId) {
    var hintElement = document.querySelector('.introjs-hint[data-step="' + stepId + '"]');
    var item = this._introItems[stepId];

    // call the callback function (if any)
    if (typeof (this._hintClickCallback) !== 'undefined') {
      this._hintClickCallback.call(this, hintElement, item, stepId);
    }

    // remove all open tooltips
    var removedStep = _removeHintTooltip.call(this);

    // to toggle the tooltip
    if (parseInt(removedStep, 10) === stepId) {
      return;
    }

    var tooltipLayer = document.createElement('div');
    var tooltipTextLayer = document.createElement('div');
    var arrowLayer = document.createElement('div');
    var referenceLayer = document.createElement('div');

    tooltipLayer.className = 'introjs-tooltip';

    tooltipLayer.onclick = function (e) {
      //IE9 & Other Browsers
      if (e.stopPropagation) {
        e.stopPropagation();
      }
      //IE8 and Lower
      else {
        e.cancelBubble = true;
      }
    };

    tooltipTextLayer.className = 'introjs-tooltiptext';

    var tooltipWrapper = document.createElement('p');
    tooltipWrapper.innerHTML = item.hint;

    var closeButton = document.createElement('a');
    closeButton.className = this._options.buttonClass;
    closeButton.setAttribute('role', 'button');
    closeButton.innerHTML = this._options.hintButtonLabel;
    closeButton.onclick = _hideHint.bind(this, stepId);

    tooltipTextLayer.appendChild(tooltipWrapper);
    tooltipTextLayer.appendChild(closeButton);

    arrowLayer.className = 'introjs-arrow';
    tooltipLayer.appendChild(arrowLayer);

    tooltipLayer.appendChild(tooltipTextLayer);

    // set current step for _placeTooltip function
    this._currentStep = hintElement.getAttribute('data-step');

    // align reference layer position
    referenceLayer.className = 'introjs-tooltipReferenceLayer introjs-hintReference';
    referenceLayer.setAttribute('data-step', hintElement.getAttribute('data-step'));
    _setHelperLayerPosition.call(this, referenceLayer);

    referenceLayer.appendChild(tooltipLayer);
    document.body.appendChild(referenceLayer);

    //set proper position
    _placeTooltip.call(this, hintElement, tooltipLayer, arrowLayer, null, true);
  }

  /**
   * Get an element position on the page
   * Thanks to `meouw`: http://stackoverflow.com/a/442474/375966
   *
   * @api private
   * @method _getOffset
   * @param {Object} element
   * @returns Element's position info
   */
  function _getOffset(element) {
    var body = document.body;
    var docEl = document.documentElement;
    var scrollTop = window.pageYOffset || docEl.scrollTop || body.scrollTop;
    var scrollLeft = window.pageXOffset || docEl.scrollLeft || body.scrollLeft;
    var x = element.getBoundingClientRect();
    return {
      top: x.top + scrollTop,
      width: x.width,
      height: x.height,
      left: x.left + scrollLeft
    };
  }

  /**
  * Find the nearest scrollable parent
  * copied from https://stackoverflow.com/questions/35939886/find-first-scrollable-parent
  *
  * @param Element element
  * @return Element
  */
  function _getScrollParent(element) {
    var style = window.getComputedStyle(element);
    var excludeStaticParent = (style.position === "absolute");
    var overflowRegex = /(auto|scroll)/;

    if (style.position === "fixed") return document.body;
    
    for (var parent = element; (parent = parent.parentElement);) {
      style = window.getComputedStyle(parent);
      if (excludeStaticParent && style.position === "static") {
        continue;
      }
      if (overflowRegex.test(style.overflow + style.overflowY + style.overflowX)) return parent;
    }

    return document.body;
  }

  /**
  * scroll a scrollable element to a child element
  *
  * @param Element parent
  * @param Element element
  * @return Null
  */
  function _scrollParentToElement (parent, element) {
    parent.scrollTop = element.offsetTop - parent.offsetTop;
  }

  /**
   * Gets the current progress percentage
   *
   * @api private
   * @method _getProgress
   * @returns current progress percentage
   */
  function _getProgress() {
    // Steps are 0 indexed
    var currentStep = parseInt((this._currentStep + 1), 10);
    return ((currentStep / this._introItems.length) * 100);
  }

  /**
   * Overwrites obj1's values with obj2's and adds obj2's if non existent in obj1
   * via: http://stackoverflow.com/questions/171251/how-can-i-merge-properties-of-two-javascript-objects-dynamically
   *
   * @param obj1
   * @param obj2
   * @returns obj3 a new object based on obj1 and obj2
   */
  function _mergeOptions(obj1,obj2) {
    var obj3 = {},
      attrname;
    for (attrname in obj1) { obj3[attrname] = obj1[attrname]; }
    for (attrname in obj2) { obj3[attrname] = obj2[attrname]; }
    return obj3;
  }

  var introJs = function (targetElm) {
    var instance;

    if (typeof (targetElm) === 'object') {
      //Ok, create a new instance
      instance = new IntroJs(targetElm);

    } else if (typeof (targetElm) === 'string') {
      //select the target element with query selector
      var targetElement = document.querySelector(targetElm);

      if (targetElement) {
        instance = new IntroJs(targetElement);
      } else {
        throw new Error('There is no element with given selector.');
      }
    } else {
      instance = new IntroJs(document.body);
    }
    // add instance to list of _instances
    // passing group to _stamp to increment
    // from 0 onward somewhat reliably
    introJs.instances[ _stamp(instance, 'introjs-instance') ] = instance;

    return instance;
  };

  /**
   * Current IntroJs version
   *
   * @property version
   * @type String
   */
  introJs.version = VERSION;

  /**
  * key-val object helper for introJs instances
  *
  * @property instances
  * @type Object
  */
  introJs.instances = {};

  //Prototype
  introJs.fn = IntroJs.prototype = {
    clone: function () {
      return new IntroJs(this);
    },
    setOption: function(option, value) {
      this._options[option] = value;
      return this;
    },
    setOptions: function(options) {
      this._options = _mergeOptions(this._options, options);
      return this;
    },
    start: function (group) {
      _introForElement.call(this, this._targetElement, group);
      return this;
    },
    goToStep: function(step) {
      _goToStep.call(this, step);
      return this;
    },
    addStep: function(options) {
      if (!this._options.steps) {
        this._options.steps = [];
      }

      this._options.steps.push(options);

      return this;
    },
    addSteps: function(steps) {
      if (!steps.length) return;

      for(var index = 0; index < steps.length; index++) {
        this.addStep(steps[index]);
      }

      return this;
    },
    goToStepNumber: function(step) {
      _goToStepNumber.call(this, step);

      return this;
    },
    nextStep: function() {
      _nextStep.call(this);
      return this;
    },
    previousStep: function() {
      _previousStep.call(this);
      return this;
    },
    exit: function(force) {
      _exitIntro.call(this, this._targetElement, force);
      return this;
    },
    refresh: function() {
      _refresh.call(this);
      return this;
    },
    onbeforechange: function(providedCallback) {
      if (typeof (providedCallback) === 'function') {
        this._introBeforeChangeCallback = providedCallback;
      } else {
        throw new Error('Provided callback for onbeforechange was not a function');
      }
      return this;
    },
    onchange: function(providedCallback) {
      if (typeof (providedCallback) === 'function') {
        this._introChangeCallback = providedCallback;
      } else {
        throw new Error('Provided callback for onchange was not a function.');
      }
      return this;
    },
    onafterchange: function(providedCallback) {
      if (typeof (providedCallback) === 'function') {
        this._introAfterChangeCallback = providedCallback;
      } else {
        throw new Error('Provided callback for onafterchange was not a function');
      }
      return this;
    },
    oncomplete: function(providedCallback) {
      if (typeof (providedCallback) === 'function') {
        this._introCompleteCallback = providedCallback;
      } else {
        throw new Error('Provided callback for oncomplete was not a function.');
      }
      return this;
    },
    onhintsadded: function(providedCallback) {
      if (typeof (providedCallback) === 'function') {
        this._hintsAddedCallback = providedCallback;
      } else {
        throw new Error('Provided callback for onhintsadded was not a function.');
      }
      return this;
    },
    onhintclick: function(providedCallback) {
      if (typeof (providedCallback) === 'function') {
        this._hintClickCallback = providedCallback;
      } else {
        throw new Error('Provided callback for onhintclick was not a function.');
      }
      return this;
    },
    onhintclose: function(providedCallback) {
      if (typeof (providedCallback) === 'function') {
        this._hintCloseCallback = providedCallback;
      } else {
        throw new Error('Provided callback for onhintclose was not a function.');
      }
      return this;
    },
    onexit: function(providedCallback) {
      if (typeof (providedCallback) === 'function') {
        this._introExitCallback = providedCallback;
      } else {
        throw new Error('Provided callback for onexit was not a function.');
      }
      return this;
    },
    onskip: function(providedCallback) {
      if (typeof (providedCallback) === 'function') {
        this._introSkipCallback = providedCallback;
      } else {
        throw new Error('Provided callback for onskip was not a function.');
      }
      return this;
    },
    onbeforeexit: function(providedCallback) {
      if (typeof (providedCallback) === 'function') {
        this._introBeforeExitCallback = providedCallback;
      } else {
        throw new Error('Provided callback for onbeforeexit was not a function.');
      }
      return this;
    },
    addHints: function() {
      _populateHints.call(this, this._targetElement);
      return this;
    },
    hideHint: function (stepId) {
      _hideHint.call(this, stepId);
      return this;
    },
    hideHints: function () {
      _hideHints.call(this);
      return this;
    },
    showHint: function (stepId) {
      _showHint.call(this, stepId);
      return this;
    },
    showHints: function () {
      _showHints.call(this);
      return this;
    },
    removeHints: function () {
      _removeHints.call(this);
      return this;
    },
    removeHint: function (stepId) {
      _removeHint.call(this, stepId);
      return this;
    },
    showHintDialog: function (stepId) {
      _showHintDialog.call(this, stepId);
      return this;
    }
  };

  return introJs;
});

/**
* jQuery slidePanel v0.3.5
* https://github.com/amazingSurge/jquery-slidePanel
*
* Copyright (c) amazingSurge
* Released under the LGPL-3.0 license
*/
(function(global, factory) {
  if (typeof define === 'function' && define.amd) {
    define(['jquery'], factory);
  } else if (typeof exports !== 'undefined') {
    factory(require('jquery'));
  } else {
    var mod = {
      exports: {}
    };
    factory(global.jQuery);
    global.jquerySlidePanelEs = mod.exports;
  }
})(this, function(_jquery) {
  'use strict';

  var _jquery2 = _interopRequireDefault(_jquery);

  function _interopRequireDefault(obj) {
    return obj && obj.__esModule
      ? obj
      : {
          default: obj
        };
  }

  function _classCallCheck(instance, Constructor) {
    if (!(instance instanceof Constructor)) {
      throw new TypeError('Cannot call a class as a function');
    }
  }

  var _createClass = (function() {
    function defineProperties(target, props) {
      for (var i = 0; i < props.length; i++) {
        var descriptor = props[i];
        descriptor.enumerable = descriptor.enumerable || false;
        descriptor.configurable = true;
        if ('value' in descriptor) descriptor.writable = true;
        Object.defineProperty(target, descriptor.key, descriptor);
      }
    }

    return function(Constructor, protoProps, staticProps) {
      if (protoProps) defineProperties(Constructor.prototype, protoProps);
      if (staticProps) defineProperties(Constructor, staticProps);
      return Constructor;
    };
  })();

  var info = {
    version: '0.3.5'
  };

  function convertMatrixToArray(value) {
    if (value && value.substr(0, 6) === 'matrix') {
      return value
        .replace(/^.*\((.*)\)$/g, '$1')
        .replace(/px/g, '')
        .split(/, +/);
    }
    return false;
  }

  function getHashCode(object) {
    /* eslint no-bitwise: "off" */
    if (typeof object !== 'string') {
      object = JSON.stringify(object);
    }

    var chr = void 0,
      hash = 0,
      i = void 0,
      len = void 0;
    if (object.length === 0) {
      return hash;
    }
    for (i = 0, len = object.length; i < len; i++) {
      chr = object.charCodeAt(i);
      hash = (hash << 5) - hash + chr;
      hash |= 0; // Convert to 32bit integer
    }

    return hash;
  }

  function getTime() {
    if (typeof window.performance !== 'undefined' && window.performance.now) {
      return window.performance.now();
    }
    return Date.now();
  }

  function isPercentage(n) {
    return typeof n === 'string' && n.indexOf('%') !== -1;
  }

  function isPx(n) {
    return typeof n === 'string' && n.indexOf('px') !== -1;
  }

  /* eslint no-unused-vars: "off" */
  var DEFAULTS = {
    skin: null,

    classes: {
      base: 'slidePanel',
      show: 'slidePanel-show',
      loading: 'slidePanel-loading',
      content: 'slidePanel-content',
      dragging: 'slidePanel-dragging',
      willClose: 'slidePanel-will-close'
    },

    closeSelector: null,

    template: function template(options) {
      return (
        '<div class="' +
        options.classes.base +
        ' ' +
        options.classes.base +
        '-' +
        options.direction +
        '"><div class="' +
        options.classes.content +
        '"></div></div>'
      );
    },

    loading: {
      appendTo: 'panel',
      template: function template(options) {
        return '<div class="' + options.classes.loading + '"></div>';
      },
      showCallback: function showCallback(options) {
        this.$el.addClass(options.classes.loading + '-show');
      },
      hideCallback: function hideCallback(options) {
        this.$el.removeClass(options.classes.loading + '-show');
      }
    },

    contentFilter: function contentFilter(content, object) {
      return content;
    },

    useCssTransforms3d: true,
    useCssTransforms: true,
    useCssTransitions: true,

    dragTolerance: 150,

    mouseDragHandler: null,
    mouseDrag: true,
    touchDrag: true,
    pointerDrag: true,

    direction: 'right', // top, bottom, left, right
    duration: '500',
    easing: 'ease', // linear, ease-in, ease-out, ease-in-out

    // callbacks
    beforeLoad: $.noop, // Before loading
    afterLoad: $.noop, // After loading
    beforeShow: $.noop, // Before opening
    afterShow: $.noop, // After opening
    onChange: $.noop, // On changing
    beforeHide: $.noop, // Before closing
    afterHide: $.noop, // After closing
    beforeDrag: $.noop, // Before drag
    afterDrag: $.noop // After drag
  };

  var Instance = (function() {
    function Instance(object) {
      _classCallCheck(this, Instance);

      for (
        var _len = arguments.length,
          args = Array(_len > 1 ? _len - 1 : 0),
          _key = 1;
        _key < _len;
        _key++
      ) {
        args[_key - 1] = arguments[_key];
      }

      this.initialize.apply(this, [object].concat(args));
    }

    _createClass(Instance, [
      {
        key: 'initialize',
        value: function initialize(object) {
          var options =
            (arguments.length <= 1 ? undefined : arguments[1]) || {};

          if (typeof object === 'string') {
            object = {
              url: object
            };
          } else if (object && object.nodeType === 1) {
            var $element = (0, _jquery2.default)(object);

            object = {
              url: $element.attr('href'),
              settings: $element.data('settings') || {},
              options: $element.data() || {}
            };
          }

          if (object && object.options) {
            object.options = _jquery2.default.extend(
              true,
              options,
              object.options
            );
          } else {
            object.options = options;
          }

          object.options = _jquery2.default.extend(
            true,
            {},
            DEFAULTS,
            object.options
          );

          _jquery2.default.extend(this, object);

          return this;
        }
      }
    ]);

    return Instance;
  })();

  /**
   * Css features detect
   **/
  var Support = {};

  (function(support) {
    /**
     * Borrowed from Owl carousel
     **/
    'use strict';

    var events = {
        transition: {
          end: {
            WebkitTransition: 'webkitTransitionEnd',
            MozTransition: 'transitionend',
            OTransition: 'oTransitionEnd',
            transition: 'transitionend'
          }
        },
        animation: {
          end: {
            WebkitAnimation: 'webkitAnimationEnd',
            MozAnimation: 'animationend',
            OAnimation: 'oAnimationEnd',
            animation: 'animationend'
          }
        }
      },
      prefixes = ['webkit', 'Moz', 'O', 'ms'],
      style = (0, _jquery2.default)('<support>').get(0).style,
      tests = {
        csstransforms: function csstransforms() {
          return Boolean(test('transform'));
        },
        csstransforms3d: function csstransforms3d() {
          return Boolean(test('perspective'));
        },
        csstransitions: function csstransitions() {
          return Boolean(test('transition'));
        },
        cssanimations: function cssanimations() {
          return Boolean(test('animation'));
        }
      };

    var test = function test(property, prefixed) {
      var result = false,
        upper = property.charAt(0).toUpperCase() + property.slice(1);

      if (style[property] !== undefined) {
        result = property;
      }
      if (!result) {
        _jquery2.default.each(prefixes, function(i, prefix) {
          if (style[prefix + upper] !== undefined) {
            result = '-' + prefix.toLowerCase() + '-' + upper;
            return false;
          }
          return true;
        });
      }

      if (prefixed) {
        return result;
      }
      if (result) {
        return true;
      }
      return false;
    };

    var prefixed = function prefixed(property) {
      return test(property, true);
    };

    if (tests.csstransitions()) {
      /*eslint no-new-wrappers: "off"*/
      support.transition = new String(prefixed('transition'));
      support.transition.end = events.transition.end[support.transition];
    }

    if (tests.cssanimations()) {
      /*eslint no-new-wrappers: "off"*/
      support.animation = new String(prefixed('animation'));
      support.animation.end = events.animation.end[support.animation];
    }

    if (tests.csstransforms()) {
      /*eslint no-new-wrappers: "off"*/
      support.transform = new String(prefixed('transform'));
      support.transform3d = tests.csstransforms3d();
    }

    if (
      'ontouchstart' in window ||
      (window.DocumentTouch && document instanceof window.DocumentTouch)
    ) {
      support.touch = true;
    } else {
      support.touch = false;
    }

    if (window.PointerEvent || window.MSPointerEvent) {
      support.pointer = true;
    } else {
      support.pointer = false;
    }

    support.prefixPointerEvent = function(pointerEvent) {
      return window.MSPointerEvent
        ? 'MSPointer' +
          pointerEvent.charAt(9).toUpperCase() +
          pointerEvent.substr(10)
        : pointerEvent;
    };
  })(Support);

  function easingBezier(mX1, mY1, mX2, mY2) {
    'use strict';

    function a(aA1, aA2) {
      return 1.0 - 3.0 * aA2 + 3.0 * aA1;
    }

    function b(aA1, aA2) {
      return 3.0 * aA2 - 6.0 * aA1;
    }

    function c(aA1) {
      return 3.0 * aA1;
    }

    // Returns x(t) given t, x1, and x2, or y(t) given t, y1, and y2.
    function calcBezier(aT, aA1, aA2) {
      return ((a(aA1, aA2) * aT + b(aA1, aA2)) * aT + c(aA1)) * aT;
    }

    // Returns dx/dt given t, x1, and x2, or dy/dt given t, y1, and y2.
    function getSlope(aT, aA1, aA2) {
      return 3.0 * a(aA1, aA2) * aT * aT + 2.0 * b(aA1, aA2) * aT + c(aA1);
    }

    function getTForX(aX) {
      // Newton raphson iteration
      var aGuessT = aX;
      for (var i = 0; i < 4; ++i) {
        var currentSlope = getSlope(aGuessT, mX1, mX2);
        if (currentSlope === 0.0) {
          return aGuessT;
        }
        var currentX = calcBezier(aGuessT, mX1, mX2) - aX;
        aGuessT -= currentX / currentSlope;
      }
      return aGuessT;
    }

    if (mX1 === mY1 && mX2 === mY2) {
      return {
        css: 'linear',
        fn: function fn(aX) {
          return aX;
        }
      };
    }
    return {
      css: 'cubic-bezier(' + mX1 + ',' + mY1 + ',' + mX2 + ',' + mY2 + ')',
      fn: function fn(aX) {
        return calcBezier(getTForX(aX), mY1, mY2);
      }
    };
  }

  var Easings = {
    ease: easingBezier(0.25, 0.1, 0.25, 1.0),
    linear: easingBezier(0.0, 0.0, 1.0, 1.0),
    'ease-in': easingBezier(0.42, 0.0, 1.0, 1.0),
    'ease-out': easingBezier(0.0, 0.0, 0.58, 1.0),
    'ease-in-out': easingBezier(0.42, 0.0, 0.58, 1.0)
  };

  var Animate = {
    prepareTransition: function prepareTransition(
      $el,
      property,
      duration,
      easing,
      delay
    ) {
      var temp = [];
      if (property) {
        temp.push(property);
      }
      if (duration) {
        if (_jquery2.default.isNumeric(duration)) {
          duration = duration + 'ms';
        }
        temp.push(duration);
      }
      if (easing) {
        temp.push(easing);
      } else {
        temp.push(this.easing.css);
      }
      if (delay) {
        temp.push(delay);
      }
      $el.css(Support.transition, temp.join(' '));
    },
    do: function _do(view, value, callback) {
      SlidePanel.enter('animating');

      var duration = view.options.duration,
        easing = view.options.easing || 'ease';

      var that = this;
      var style = view.makePositionStyle(value);
      var property = null;

      for (property in style) {
        if ({}.hasOwnProperty.call(style, property)) {
          break;
        }
      }

      if (view.options.useCssTransitions && Support.transition) {
        setTimeout(function() {
          that.prepareTransition(view.$panel, property, duration, easing);
        }, 20);

        view.$panel.one(Support.transition.end, function() {
          if (_jquery2.default.isFunction(callback)) {
            callback();
          }

          view.$panel.css(Support.transition, '');

          SlidePanel.leave('animating');
        });
        setTimeout(function() {
          view.setPosition(value);
        }, 20);
      } else {
        var startTime = getTime();
        var start = view.getPosition();
        var end = value;

        var run = function run(time) {
          var percent = (time - startTime) / view.options.duration;

          if (percent > 1) {
            percent = 1;
          }

          percent = Easings[easing].fn(percent);

          var current = parseFloat(start + percent * (end - start), 10);

          view.setPosition(current);

          if (percent === 1) {
            window.cancelAnimationFrame(that._frameId);
            that._frameId = null;

            if (_jquery2.default.isFunction(callback)) {
              callback();
            }

            SlidePanel.leave('animating');
          } else {
            that._frameId = window.requestAnimationFrame(run);
          }
        };

        that._frameId = window.requestAnimationFrame(run);
      }
    }
  };

  var Loading = (function() {
    function Loading(view) {
      _classCallCheck(this, Loading);

      this.initialize(view);
    }

    _createClass(Loading, [
      {
        key: 'initialize',
        value: function initialize(view) {
          this._view = view;
          this.build();
        }
      },
      {
        key: 'build',
        value: function build() {
          if (this._builded) {
            return;
          }

          var options = this._view.options;
          var html = options.loading.template.call(this, options);
          this.$el = (0, _jquery2.default)(html);

          switch (options.loading.appendTo) {
            case 'panel':
              this.$el.appendTo(this._view.$panel);
              break;
            case 'body':
              this.$el.appendTo('body');
              break;
            default:
              this.$el.appendTo(options.loading.appendTo);
          }

          this._builded = true;
        }
      },
      {
        key: 'show',
        value: function show(callback) {
          this.build();
          var options = this._view.options;
          options.loading.showCallback.call(this, options);

          if (_jquery2.default.isFunction(callback)) {
            callback.call(this);
          }
        }
      },
      {
        key: 'hide',
        value: function hide(callback) {
          var options = this._view.options;
          options.loading.hideCallback.call(this, options);

          if (_jquery2.default.isFunction(callback)) {
            callback.call(this);
          }
        }
      }
    ]);

    return Loading;
  })();

  var Drag = (function() {
    function Drag() {
      _classCallCheck(this, Drag);

      this.initialize.apply(this, arguments);
    }

    _createClass(Drag, [
      {
        key: 'initialize',
        value: function initialize(view) {
          this._view = view;
          this.options = view.options;
          this._drag = {
            time: null,
            pointer: null
          };

          this.bindEvents();
        }
      },
      {
        key: 'bindEvents',
        value: function bindEvents() {
          var $panel = this._view.$panel,
            options = this.options;

          if (options.mouseDrag) {
            $panel.on(
              SlidePanel.eventName('mousedown'),
              _jquery2.default.proxy(this.onDragStart, this)
            );
            $panel.on(SlidePanel.eventName('dragstart selectstart'), function(
              event
            ) {
              /* eslint consistent-return: "off" */
              if (options.mouseDragHandler) {
                if (
                  !(0, _jquery2.default)(event.target).is(
                    options.mouseDragHandler
                  ) &&
                  !(
                    (0, _jquery2.default)(event.target).parents(
                      options.mouseDragHandler
                    ).length > 0
                  )
                ) {
                  return;
                }
              }
              return false;
            });
          }

          if (options.touchDrag && Support.touch) {
            $panel.on(
              SlidePanel.eventName('touchstart'),
              _jquery2.default.proxy(this.onDragStart, this)
            );
            $panel.on(
              SlidePanel.eventName('touchcancel'),
              _jquery2.default.proxy(this.onDragEnd, this)
            );
          }

          if (options.pointerDrag && Support.pointer) {
            $panel.on(
              SlidePanel.eventName(Support.prefixPointerEvent('pointerdown')),
              _jquery2.default.proxy(this.onDragStart, this)
            );
            $panel.on(
              SlidePanel.eventName(Support.prefixPointerEvent('pointercancel')),
              _jquery2.default.proxy(this.onDragEnd, this)
            );
          }
        }
      },
      {
        key: 'onDragStart',
        value: function onDragStart(event) {
          var that = this;

          if (event.which === 3) {
            return;
          }

          var options = this.options;

          this._view.$panel.addClass(this.options.classes.dragging);

          this._position = this._view.getPosition(true);

          this._drag.time = new Date().getTime();
          this._drag.pointer = this.pointer(event);

          var callback = function callback() {
            SlidePanel.enter('dragging');
            SlidePanel.trigger(that._view, 'beforeDrag');
          };

          if (options.mouseDrag) {
            if (options.mouseDragHandler) {
              if (
                !(0, _jquery2.default)(event.target).is(
                  options.mouseDragHandler
                ) &&
                !(
                  (0, _jquery2.default)(event.target).parents(
                    options.mouseDragHandler
                  ).length > 0
                )
              ) {
                return;
              }
            }

            (0, _jquery2.default)(document).on(
              SlidePanel.eventName('mouseup'),
              _jquery2.default.proxy(this.onDragEnd, this)
            );

            (0, _jquery2.default)(document).one(
              SlidePanel.eventName('mousemove'),
              _jquery2.default.proxy(function() {
                (0, _jquery2.default)(document).on(
                  SlidePanel.eventName('mousemove'),
                  _jquery2.default.proxy(this.onDragMove, this)
                );

                callback();
              }, this)
            );
          }

          if (options.touchDrag && Support.touch) {
            (0, _jquery2.default)(document).on(
              SlidePanel.eventName('touchend'),
              _jquery2.default.proxy(this.onDragEnd, this)
            );

            (0, _jquery2.default)(document).one(
              SlidePanel.eventName('touchmove'),
              _jquery2.default.proxy(function() {
                (0, _jquery2.default)(document).on(
                  SlidePanel.eventName('touchmove'),
                  _jquery2.default.proxy(this.onDragMove, this)
                );

                callback();
              }, this)
            );
          }

          if (options.pointerDrag && Support.pointer) {
            (0, _jquery2.default)(document).on(
              SlidePanel.eventName(Support.prefixPointerEvent('pointerup')),
              _jquery2.default.proxy(this.onDragEnd, this)
            );

            (0, _jquery2.default)(document).one(
              SlidePanel.eventName(Support.prefixPointerEvent('pointermove')),
              _jquery2.default.proxy(function() {
                (0, _jquery2.default)(document).on(
                  SlidePanel.eventName(
                    Support.prefixPointerEvent('pointermove')
                  ),
                  _jquery2.default.proxy(this.onDragMove, this)
                );

                callback();
              }, this)
            );
          }

          (0, _jquery2.default)(document).on(
            SlidePanel.eventName('blur'),
            _jquery2.default.proxy(this.onDragEnd, this)
          );

          event.preventDefault();
        }
      },
      {
        key: 'onDragMove',
        value: function onDragMove(event) {
          var distance = this.distance(this._drag.pointer, this.pointer(event));

          if (!SlidePanel.is('dragging')) {
            return;
          }

          if (Math.abs(distance) > this.options.dragTolerance) {
            if (this._willClose !== true) {
              this._willClose = true;
              this._view.$panel.addClass(this.options.classes.willClose);
            }
          } else if (this._willClose !== false) {
            this._willClose = false;
            this._view.$panel.removeClass(this.options.classes.willClose);
          }

          if (!SlidePanel.is('dragging')) {
            return;
          }

          event.preventDefault();
          this.move(distance);
        }
      },
      {
        key: 'onDragEnd',
        value: function onDragEnd(event) {
          var distance = this.distance(this._drag.pointer, this.pointer(event));

          (0, _jquery2.default)(document).off(
            SlidePanel.eventName(
              'mousemove mouseup touchmove touchend pointermove pointerup MSPointerMove MSPointerUp blur'
            )
          );

          this._view.$panel.removeClass(this.options.classes.dragging);

          if (this._willClose === true) {
            this._willClose = false;
            this._view.$panel.removeClass(this.options.classes.willClose);
          }

          if (!SlidePanel.is('dragging')) {
            return;
          }

          SlidePanel.leave('dragging');

          SlidePanel.trigger(this._view, 'afterDrag');

          if (Math.abs(distance) < this.options.dragTolerance) {
            this._view.revert();
          } else {
            this._view.hide();
            // SlidePanel.hide();
          }
        }
      },
      {
        key: 'pointer',
        value: function pointer(event) {
          var result = {
            x: null,
            y: null
          };

          event = event.originalEvent || event || window.event;

          event =
            event.touches && event.touches.length
              ? event.touches[0]
              : event.changedTouches && event.changedTouches.length
                ? event.changedTouches[0]
                : event;

          if (event.pageX) {
            result.x = event.pageX;
            result.y = event.pageY;
          } else {
            result.x = event.clientX;
            result.y = event.clientY;
          }

          return result;
        }
      },
      {
        key: 'distance',
        value: function distance(first, second) {
          var d = this.options.direction;
          if (d === 'left' || d === 'right') {
            return second.x - first.x;
          }
          return second.y - first.y;
        }
      },
      {
        key: 'move',
        value: function move(value) {
          var position = this._position + value;

          if (
            this.options.direction === 'right' ||
            this.options.direction === 'bottom'
          ) {
            if (position < 0) {
              return;
            }
          } else if (position > 0) {
            return;
          }

          if (
            !this.options.useCssTransforms &&
            !this.options.useCssTransforms3d
          ) {
            if (
              this.options.direction === 'right' ||
              this.options.direction === 'bottom'
            ) {
              position = -position;
            }
          }

          this._view.setPosition(position + 'px');
        }
      }
    ]);

    return Drag;
  })();

  var View = (function() {
    function View(options) {
      _classCallCheck(this, View);

      this.initialize(options);
    }

    _createClass(View, [
      {
        key: 'initialize',
        value: function initialize(options) {
          this.options = options;
          this._instance = null;
          this._showed = false;
          this._isLoading = false;

          this.build();
        }
      },
      {
        key: 'setLength',
        value: function setLength() {
          switch (this.options.direction) {
            case 'top':
            case 'bottom':
              this._length = this.$panel.outerHeight();
              break;
            case 'left':
            case 'right':
              this._length = this.$panel.outerWidth();
              break;
            // no default
          }
        }
      },
      {
        key: 'build',
        value: function build() {
          if (this._builded) {
            return;
          }

          var options = this.options;

          var html = options.template.call(this, options);
          var that = this;

          this.$panel = (0, _jquery2.default)(html).appendTo('body');
          if (options.skin) {
            this.$panel.addClass(options.skin);
          }
          this.$content = this.$panel.find('.' + this.options.classes.content);

          if (options.closeSelector) {
            this.$panel.on(
              'click touchstart',
              options.closeSelector,
              function() {
                that.hide();
                return false;
              }
            );
          }
          this.loading = new Loading(this);

          this.setLength();
          this.setPosition(this.getHidePosition());

          if (options.mouseDrag || options.touchDrag || options.pointerDrag) {
            this.drag = new Drag(this);
          }

          this._builded = true;
        }
      },
      {
        key: 'getHidePosition',
        value: function getHidePosition() {
          /* eslint consistent-return: "off" */
          var options = this.options;

          if (options.useCssTransforms || options.useCssTransforms3d) {
            switch (options.direction) {
              case 'top':
              case 'left':
                return '-100';
              case 'bottom':
              case 'right':
                return '100';
              // no default
            }
          }
          switch (options.direction) {
            case 'top':
            case 'bottom':
              return parseFloat(
                -(this._length / (0, _jquery2.default)(window).height()) * 100,
                10
              );
            case 'left':
            case 'right':
              return parseFloat(
                -(this._length / (0, _jquery2.default)(window).width()) * 100,
                10
              );
            // no default
          }
        }
      },
      {
        key: 'empty',
        value: function empty() {
          this._instance = null;
          this.$content.empty();
        }
      },
      {
        key: 'load',
        value: function load(object) {
          var that = this;
          var options = object.options;

          SlidePanel.trigger(this, 'beforeLoad', object);
          this.empty();

          function setContent(content) {
            content = options.contentFilter.call(this, content, object);
            that.$content.html(content);
            that.hideLoading();

            that._instance = object;

            SlidePanel.trigger(that, 'afterLoad', object);
          }

          if (object.content) {
            setContent(object.content);
          } else if (object.url) {
            this.showLoading();

            _jquery2.default
              .ajax(object.url, object.settings || {})
              .done(function(data) {
                setContent(data);
              });
          } else {
            setContent('');
          }
        }
      },
      {
        key: 'showLoading',
        value: function showLoading() {
          var that = this;
          this.loading.show(function() {
            that._isLoading = true;
          });
        }
      },
      {
        key: 'hideLoading',
        value: function hideLoading() {
          var that = this;
          this.loading.hide(function() {
            that._isLoading = false;
          });
        }
      },
      {
        key: 'show',
        value: function show(callback) {
          this.build();

          SlidePanel.enter('show');
          SlidePanel.trigger(this, 'beforeShow');

          (0, _jquery2.default)('html').addClass(
            this.options.classes.base + '-html'
          );
          this.$panel.addClass(this.options.classes.show);

          var that = this;
          Animate.do(this, 0, function() {
            that._showed = true;
            SlidePanel.trigger(that, 'afterShow');

            if (_jquery2.default.isFunction(callback)) {
              callback.call(that);
            }
          });
        }
      },
      {
        key: 'change',
        value: function change(object) {
          SlidePanel.trigger(this, 'beforeShow');

          SlidePanel.trigger(this, 'onChange', object, this._instance);

          this.load(object);

          SlidePanel.trigger(this, 'afterShow');
        }
      },
      {
        key: 'revert',
        value: function revert(callback) {
          var that = this;
          Animate.do(this, 0, function() {
            if (_jquery2.default.isFunction(callback)) {
              callback.call(that);
            }
          });
        }
      },
      {
        key: 'hide',
        value: function hide(callback) {
          SlidePanel.leave('show');
          SlidePanel.trigger(this, 'beforeHide');

          var that = this;

          Animate.do(this, this.getHidePosition(), function() {
            that.$panel.removeClass(that.options.classes.show);
            that._showed = false;
            that._instance = null;

            if (SlidePanel._current === that) {
              SlidePanel._current = null;
            }

            if (!SlidePanel.is('show')) {
              (0, _jquery2.default)('html').removeClass(
                that.options.classes.base + '-html'
              );
            }

            if (_jquery2.default.isFunction(callback)) {
              callback.call(that);
            }

            SlidePanel.trigger(that, 'afterHide');
          });
        }
      },
      {
        key: 'makePositionStyle',
        value: function makePositionStyle(value) {
          var property = void 0,
            x = '0',
            y = '0';

          if (!isPercentage(value) && !isPx(value)) {
            value = value + '%';
          }

          if (this.options.useCssTransforms && Support.transform) {
            if (
              this.options.direction === 'left' ||
              this.options.direction === 'right'
            ) {
              x = value;
            } else {
              y = value;
            }

            property = Support.transform.toString();

            if (this.options.useCssTransforms3d && Support.transform3d) {
              value = 'translate3d(' + x + ',' + y + ',0)';
            } else {
              value = 'translate(' + x + ',' + y + ')';
            }
          } else {
            property = this.options.direction;
          }
          var temp = {};
          temp[property] = value;
          return temp;
        }
      },
      {
        key: 'getPosition',
        value: function getPosition(px) {
          var value = void 0;

          if (this.options.useCssTransforms && Support.transform) {
            value = convertMatrixToArray(this.$panel.css(Support.transform));
            if (!value) {
              return 0;
            }

            if (
              this.options.direction === 'left' ||
              this.options.direction === 'right'
            ) {
              value = value[12] || value[4];
            } else {
              value = value[13] || value[5];
            }
          } else {
            value = this.$panel.css(this.options.direction);

            value = parseFloat(value.replace('px', ''));
          }

          if (px !== true) {
            value = value / this._length * 100;
          }

          return parseFloat(value, 10);
        }
      },
      {
        key: 'setPosition',
        value: function setPosition(value) {
          var style = this.makePositionStyle(value);
          this.$panel.css(style);
        }
      }
    ]);

    return View;
  })();

  var SlidePanel = {
    // Current state information.
    _states: {},
    _views: {},
    _current: null,

    is: function is(state) {
      return this._states[state] && this._states[state] > 0;
    },
    enter: function enter(state) {
      if (this._states[state] === undefined) {
        this._states[state] = 0;
      }

      this._states[state]++;
    },
    leave: function leave(state) {
      this._states[state]--;
    },
    trigger: function trigger(view, event) {
      for (
        var _len2 = arguments.length,
          args = Array(_len2 > 2 ? _len2 - 2 : 0),
          _key2 = 2;
        _key2 < _len2;
        _key2++
      ) {
        args[_key2 - 2] = arguments[_key2];
      }

      var data = [view].concat(args);

      // event
      (0, _jquery2.default)(document).trigger('slidePanel::' + event, data);
      if (_jquery2.default.isFunction(view.options[event])) {
        view.options[event].apply(view, args);
      }
    },
    eventName: function eventName(events) {
      if (typeof events !== 'string' || events === '') {
        return '.slidepanel';
      }
      events = events.split(' ');

      var length = events.length;
      for (var i = 0; i < length; i++) {
        events[i] = events[i] + '.slidepanel';
      }
      return events.join(' ');
    },
    show: function show(object, options) {
      var _this = this;

      if (!(object instanceof Instance)) {
        switch (arguments.length) {
          case 0:
            object = new Instance();
            break;
          case 1:
            object = new Instance(object);
            break;
          case 2:
            object = new Instance(object, options);
            break;
          // no default
        }
      }

      var view = this.getView(object.options);

      var callback = function callback() {
        view.show();
        view.load(object);
        _this._current = view;
      };
      if (this._current !== null) {
        if (view === this._current) {
          this._current.change(object);
        } else {
          this._current.hide(callback);
        }
      } else {
        callback();
      }
    },
    getView: function getView(options) {
      var code = getHashCode(options);

      if (this._views.hasOwnProperty(code)) {
        return this._views[code];
      }

      return (this._views[code] = new View(options));
    },
    hide: function hide(object) {
      if (
        typeof object !== 'undefined' &&
        typeof object.options !== 'undefined'
      ) {
        var view = this.getView(object.options);
        view.hide();
      } else if (this._current !== null) {
        this._current.hide();
      }
    }
  };

  var api = {
    is: function is(state) {
      return SlidePanel.is(state);
    },
    show: function show(object, options) {
      SlidePanel.show(object, options);
      return this;
    },
    hide: function hide() {
      for (
        var _len3 = arguments.length, args = Array(_len3), _key3 = 0;
        _key3 < _len3;
        _key3++
      ) {
        args[_key3] = arguments[_key3];
      }

      SlidePanel.hide(args);
      return this;
    }
  };

  if (!Date.now) {
    Date.now = function() {
      return new Date().getTime();
    };
  }

  var vendors = ['webkit', 'moz'];
  for (var i = 0; i < vendors.length && !window.requestAnimationFrame; ++i) {
    var vp = vendors[i];
    window.requestAnimationFrame = window[vp + 'RequestAnimationFrame'];
    window.cancelAnimationFrame =
      window[vp + 'CancelAnimationFrame'] ||
      window[vp + 'CancelRequestAnimationFrame'];
  }

  if (
    /iP(ad|hone|od).*OS (6|7|8)/.test(window.navigator.userAgent) ||
    !window.requestAnimationFrame ||
    !window.cancelAnimationFrame
  ) {
    var lastTime = 0;
    window.requestAnimationFrame = function(callback) {
      var now = getTime();
      var nextTime = Math.max(lastTime + 16, now);
      return setTimeout(function() {
        callback((lastTime = nextTime));
      }, nextTime - now);
    };
    window.cancelAnimationFrame = clearTimeout;
  }

  var OtherSlidePanel = _jquery2.default.fn.slidePanel;

  var jQuerySlidePanel = function jQuerySlidePanel(options) {
    for (
      var _len4 = arguments.length,
        args = Array(_len4 > 1 ? _len4 - 1 : 0),
        _key4 = 1;
      _key4 < _len4;
      _key4++
    ) {
      args[_key4 - 1] = arguments[_key4];
    }

    var method = options;

    if (typeof options === 'string') {
      return this.each(function() {
        var instance = _jquery2.default.data(this, 'slidePanel');

        if (!(instance instanceof Instance)) {
          instance = new Instance(this, args);
          _jquery2.default.data(this, 'slidePanel', instance);
        }

        switch (method) {
          case 'hide':
            SlidePanel.hide(instance);
            break;
          case 'show':
            SlidePanel.show(instance);
            break;
          // no default
        }
      });
    }
    return this.each(function() {
      if (!_jquery2.default.data(this, 'slidePanel')) {
        _jquery2.default.data(this, 'slidePanel', new Instance(this, options));

        (0, _jquery2.default)(this).on('click', function(e) {
          var instance = _jquery2.default.data(this, 'slidePanel');
          SlidePanel.show(instance);

          e.preventDefault();
          e.stopPropagation();
        });
      }
    });
  };

  _jquery2.default.fn.slidePanel = jQuerySlidePanel;

  _jquery2.default.slidePanel = function() {
    SlidePanel.show.apply(SlidePanel, arguments);
  };

  _jquery2.default.extend(
    _jquery2.default.slidePanel,
    {
      setDefaults: function setDefaults(options) {
        _jquery2.default.extend(
          true,
          DEFAULTS,
          _jquery2.default.isPlainObject(options) && options
        );
      },
      noConflict: function noConflict() {
        _jquery2.default.fn.slidePanel = OtherSlidePanel;
        return jQuerySlidePanel;
      }
    },
    info,
    api
  );
});

/*
 * Toastr
 * Copyright 2012-2015
 * Authors: John Papa, Hans FjÃ¤llemark, and Tim Ferrell.
 * All Rights Reserved.
 * Use, reproduction, distribution, and modification of this code is subject to the terms and
 * conditions of the MIT license, available at http://www.opensource.org/licenses/mit-license.php
 *
 * ARIA Support: Greta Krafsig
 *
 * Project: https://github.com/CodeSeven/toastr
 */
/* global define */
(function (define) {
    define(['jquery'], function ($) {
        return (function () {
            var $container;
            var listener;
            var toastId = 0;
            var toastType = {
                error: 'error',
                info: 'info',
                success: 'success',
                warning: 'warning'
            };

            var toastr = {
                clear: clear,
                remove: remove,
                error: error,
                getContainer: getContainer,
                info: info,
                options: {},
                subscribe: subscribe,
                success: success,
                version: '2.1.4',
                warning: warning
            };

            var previousToast;

            return toastr;

            ////////////////

            function error(message, title, optionsOverride) {
                return notify({
                    type: toastType.error,
                    iconClass: getOptions().iconClasses.error,
                    message: message,
                    optionsOverride: optionsOverride,
                    title: title
                });
            }

            function getContainer(options, create) {
                if (!options) { options = getOptions(); }
                $container = $('#' + options.containerId);
                if ($container.length) {
                    return $container;
                }
                if (create) {
                    $container = createContainer(options);
                }
                return $container;
            }

            function info(message, title, optionsOverride) {
                return notify({
                    type: toastType.info,
                    iconClass: getOptions().iconClasses.info,
                    message: message,
                    optionsOverride: optionsOverride,
                    title: title
                });
            }

            function subscribe(callback) {
                listener = callback;
            }

            function success(message, title, optionsOverride) {
                return notify({
                    type: toastType.success,
                    iconClass: getOptions().iconClasses.success,
                    message: message,
                    optionsOverride: optionsOverride,
                    title: title
                });
            }

            function warning(message, title, optionsOverride) {
                return notify({
                    type: toastType.warning,
                    iconClass: getOptions().iconClasses.warning,
                    message: message,
                    optionsOverride: optionsOverride,
                    title: title
                });
            }

            function clear($toastElement, clearOptions) {
                var options = getOptions();
                if (!$container) { getContainer(options); }
                if (!clearToast($toastElement, options, clearOptions)) {
                    clearContainer(options);
                }
            }

            function remove($toastElement) {
                var options = getOptions();
                if (!$container) { getContainer(options); }
                if ($toastElement && $(':focus', $toastElement).length === 0) {
                    removeToast($toastElement);
                    return;
                }
                if ($container.children().length) {
                    $container.remove();
                }
            }

            // internal functions

            function clearContainer (options) {
                var toastsToClear = $container.children();
                for (var i = toastsToClear.length - 1; i >= 0; i--) {
                    clearToast($(toastsToClear[i]), options);
                }
            }

            function clearToast ($toastElement, options, clearOptions) {
                var force = clearOptions && clearOptions.force ? clearOptions.force : false;
                if ($toastElement && (force || $(':focus', $toastElement).length === 0)) {
                    $toastElement[options.hideMethod]({
                        duration: options.hideDuration,
                        easing: options.hideEasing,
                        complete: function () { removeToast($toastElement); }
                    });
                    return true;
                }
                return false;
            }

            function createContainer(options) {
                $container = $('<div/>')
                    .attr('id', options.containerId)
                    .addClass(options.positionClass);

                $container.appendTo($(options.target));
                return $container;
            }

            function getDefaults() {
                return {
                    tapToDismiss: true,
                    toastClass: 'toast',
                    containerId: 'toast-container',
                    debug: false,

                    showMethod: 'fadeIn', //fadeIn, slideDown, and show are built into jQuery
                    showDuration: 300,
                    showEasing: 'swing', //swing and linear are built into jQuery
                    onShown: undefined,
                    hideMethod: 'fadeOut',
                    hideDuration: 1000,
                    hideEasing: 'swing',
                    onHidden: undefined,
                    closeMethod: false,
                    closeDuration: false,
                    closeEasing: false,
                    closeOnHover: true,

                    extendedTimeOut: 1000,
                    iconClasses: {
                        error: 'toast-error',
                        info: 'toast-info',
                        success: 'toast-success',
                        warning: 'toast-warning'
                    },
                    iconClass: 'toast-info',
                    positionClass: 'toast-top-right',
                    timeOut: 5000, // Set timeOut and extendedTimeOut to 0 to make it sticky
                    titleClass: 'toast-title',
                    messageClass: 'toast-message',
                    escapeHtml: false,
                    target: 'body',
                    closeHtml: '<button type="button">&times;</button>',
                    closeClass: 'toast-close-button',
                    newestOnTop: true,
                    preventDuplicates: false,
                    progressBar: false,
                    progressClass: 'toast-progress',
                    rtl: false
                };
            }

            function publish(args) {
                if (!listener) { return; }
                listener(args);
            }

            function notify(map) {
                var options = getOptions();
                var iconClass = map.iconClass || options.iconClass;

                if (typeof (map.optionsOverride) !== 'undefined') {
                    options = $.extend(options, map.optionsOverride);
                    iconClass = map.optionsOverride.iconClass || iconClass;
                }

                if (shouldExit(options, map)) { return; }

                toastId++;

                $container = getContainer(options, true);

                var intervalId = null;
                var $toastElement = $('<div/>');
                var $titleElement = $('<div/>');
                var $messageElement = $('<div/>');
                var $progressElement = $('<div/>');
                var $closeElement = $(options.closeHtml);
                var progressBar = {
                    intervalId: null,
                    hideEta: null,
                    maxHideTime: null
                };
                var response = {
                    toastId: toastId,
                    state: 'visible',
                    startTime: new Date(),
                    options: options,
                    map: map
                };

                personalizeToast();

                displayToast();

                handleEvents();

                publish(response);

                if (options.debug && console) {
                    console.log(response);
                }

                return $toastElement;

                function escapeHtml(source) {
                    if (source == null) {
                        source = '';
                    }

                    return source
                        .replace(/&/g, '&amp;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#39;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;');
                }

                function personalizeToast() {
                    setIcon();
                    setTitle();
                    setMessage();
                    setCloseButton();
                    setProgressBar();
                    setRTL();
                    setSequence();
                    setAria();
                }

                function setAria() {
                    var ariaValue = '';
                    switch (map.iconClass) {
                        case 'toast-success':
                        case 'toast-info':
                            ariaValue =  'polite';
                            break;
                        default:
                            ariaValue = 'assertive';
                    }
                    $toastElement.attr('aria-live', ariaValue);
                }

                function handleEvents() {
                    if (options.closeOnHover) {
                        $toastElement.hover(stickAround, delayedHideToast);
                    }

                    if (!options.onclick && options.tapToDismiss) {
                        $toastElement.click(hideToast);
                    }

                    if (options.closeButton && $closeElement) {
                        $closeElement.click(function (event) {
                            if (event.stopPropagation) {
                                event.stopPropagation();
                            } else if (event.cancelBubble !== undefined && event.cancelBubble !== true) {
                                event.cancelBubble = true;
                            }

                            if (options.onCloseClick) {
                                options.onCloseClick(event);
                            }

                            hideToast(true);
                        });
                    }

                    if (options.onclick) {
                        $toastElement.click(function (event) {
                            options.onclick(event);
                            hideToast();
                        });
                    }
                }

                function displayToast() {
                    $toastElement.hide();

                    $toastElement[options.showMethod](
                        {duration: options.showDuration, easing: options.showEasing, complete: options.onShown}
                    );

                    if (options.timeOut > 0) {
                        intervalId = setTimeout(hideToast, options.timeOut);
                        progressBar.maxHideTime = parseFloat(options.timeOut);
                        progressBar.hideEta = new Date().getTime() + progressBar.maxHideTime;
                        if (options.progressBar) {
                            progressBar.intervalId = setInterval(updateProgress, 10);
                        }
                    }
                }

                function setIcon() {
                    if (map.iconClass) {
                        $toastElement.addClass(options.toastClass).addClass(iconClass);
                    }
                }

                function setSequence() {
                    if (options.newestOnTop) {
                        $container.prepend($toastElement);
                    } else {
                        $container.append($toastElement);
                    }
                }

                function setTitle() {
                    if (map.title) {
                        var suffix = map.title;
                        if (options.escapeHtml) {
                            suffix = escapeHtml(map.title);
                        }
                        $titleElement.append(suffix).addClass(options.titleClass);
                        $toastElement.append($titleElement);
                    }
                }

                function setMessage() {
                    if (map.message) {
                        var suffix = map.message;
                        if (options.escapeHtml) {
                            suffix = escapeHtml(map.message);
                        }
                        $messageElement.append(suffix).addClass(options.messageClass);
                        $toastElement.append($messageElement);
                    }
                }

                function setCloseButton() {
                    if (options.closeButton) {
                        $closeElement.addClass(options.closeClass).attr('role', 'button');
                        $toastElement.prepend($closeElement);
                    }
                }

                function setProgressBar() {
                    if (options.progressBar) {
                        $progressElement.addClass(options.progressClass);
                        $toastElement.prepend($progressElement);
                    }
                }

                function setRTL() {
                    if (options.rtl) {
                        $toastElement.addClass('rtl');
                    }
                }

                function shouldExit(options, map) {
                    if (options.preventDuplicates) {
                        if (map.message === previousToast) {
                            return true;
                        } else {
                            previousToast = map.message;
                        }
                    }
                    return false;
                }

                function hideToast(override) {
                    var method = override && options.closeMethod !== false ? options.closeMethod : options.hideMethod;
                    var duration = override && options.closeDuration !== false ?
                        options.closeDuration : options.hideDuration;
                    var easing = override && options.closeEasing !== false ? options.closeEasing : options.hideEasing;
                    if ($(':focus', $toastElement).length && !override) {
                        return;
                    }
                    clearTimeout(progressBar.intervalId);
                    return $toastElement[method]({
                        duration: duration,
                        easing: easing,
                        complete: function () {
                            removeToast($toastElement);
                            clearTimeout(intervalId);
                            if (options.onHidden && response.state !== 'hidden') {
                                options.onHidden();
                            }
                            response.state = 'hidden';
                            response.endTime = new Date();
                            publish(response);
                        }
                    });
                }

                function delayedHideToast() {
                    if (options.timeOut > 0 || options.extendedTimeOut > 0) {
                        intervalId = setTimeout(hideToast, options.extendedTimeOut);
                        progressBar.maxHideTime = parseFloat(options.extendedTimeOut);
                        progressBar.hideEta = new Date().getTime() + progressBar.maxHideTime;
                    }
                }

                function stickAround() {
                    clearTimeout(intervalId);
                    progressBar.hideEta = 0;
                    $toastElement.stop(true, true)[options.showMethod](
                        {duration: options.showDuration, easing: options.showEasing}
                    );
                }

                function updateProgress() {
                    var percentage = ((progressBar.hideEta - (new Date().getTime())) / progressBar.maxHideTime) * 100;
                    $progressElement.width(percentage + '%');
                }
            }

            function getOptions() {
                return $.extend({}, getDefaults(), toastr.options);
            }

            function removeToast($toastElement) {
                if (!$container) { $container = getContainer(); }
                if ($toastElement.is(':visible')) {
                    return;
                }
                $toastElement.remove();
                $toastElement = null;
                if ($container.children().length === 0) {
                    $container.remove();
                    previousToast = undefined;
                }
            }

        })();
    });
}(typeof define === 'function' && define.amd ? define : function (deps, factory) {
    if (typeof module !== 'undefined' && module.exports) { //Node
        module.exports = factory(require('jquery'));
    } else {
        window.toastr = factory(window.jQuery);
    }
}));