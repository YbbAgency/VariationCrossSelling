"use strict";
(self["webpackChunkVariationCrossSelling"] = self["webpackChunkVariationCrossSelling"] || []).push([[1],{

/***/ "./node_modules/babel-loader/lib/index.js!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/src/variationcrossselling.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/src/variationcrossselling.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = ({
  name: 'variationcrossselling',
  props: {
    showheadline: {
      type: Boolean,
      default: function _default() {
        return false;
      }
    },
    roundedbtns: {
      type: Boolean,
      default: function _default() {
        return false;
      }
    },
    crossseller: {
      type: Array,
      default: function _default() {
        return [];
      }
    }
  },
  inject: {
    itemId: {
      default: null
    }
  },
  data: function data() {
    return {
      addItemToBasketLoading: false,
      currentCrossSeller: []
    };
  },
  computed: {
    currentVariation: function currentVariation() {
      return this.$store.getters["".concat(this.itemId, "/currentItemVariation")];
    } //current var
    //watch current var to get crossseller

  },
  created: function created() {
    this.currentCrossSeller = this.crossseller;
    console.log("created this.crossseller", this.currentCrossSeller);
  },
  watch: {
    currentVariation: {
      handler: function handler(currentVariation) {
        this.getCurrentCrossSeller(currentVariation.variation.id);
      }
    }
  },
  methods: {
    getCurrentCrossSeller: function getCurrentCrossSeller(varId) {
      var _self = this;

      $.ajax({
        type: 'get',
        url: '/variationCrossSeller/getCrossSellerForVar/' + varId + '/' + App.language + '/' + 1,
        success: function success(response) {
          if (response.success == true) {
            _self.currentCrossSeller = response.vars;
          }
        },
        error: function error(jqXHR, textStatus, errorThrown) {
          console.log(jqXHR);
        }
      });
    },
    addItemToBasket: function addItemToBasket(items) {
      var variations = [];

      var _self = this;

      _self.addItemToBasketLoading = true;

      for (var i = 0; i < items.length; i++) {
        var intervalOrderQuantity = items[i].data.variation.intervalOrderQuantity;

        if (intervalOrderQuantity == undefined || intervalOrderQuantity == null) {
          intervalOrderQuantity = 1;
        }

        variations.push({
          variationId: items[i].id,
          quantity: intervalOrderQuantity
        });
      }

      var data = {
        "items": variations
      };
      $.ajax({
        type: 'POST',
        url: '/variationCrossSeller/frontend/addToBasket/',
        data: data,
        success: function success(data) {
          for (var _i = 0; _i < data.length; _i++) {
            if (data[_i].error) {
              var varName = data[_i].error.variationName;

              for (var a = 0; a < _self.currentCrossSeller; a++) {
                if (_self.currentCrossSeller[a].data.variation.id == error.placeholder.variationId) {
                  varName = _self.currentCrossSeller[a].data.texts.name1;
                }
              }

              CeresNotification.error(ceresTranslate("VariationCrossSelling::Template.translateNotEnoughStock", {
                variationName: varName,
                stock: data[_i].error.placeholder.stock
              }));
            } else {
              CeresNotification.success(ceresTranslate("VariationCrossSelling::Template.translateAddedToBasketSuccess")).closeAfter(3000);
            }

            if (_i === data.length - 1) {
              vueApp.$store.commit("setBasketItems", data[_i].basketItems);
              vueApp.$store.commit("setBasket", data[_i].basket);
            }
          }

          _self.addItemToBasketLoading = false;
        },
        error: function error(jqXHR, textStatus, errorThrown) {
          _self.addItemToBasketLoading = false;
        }
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/src/variationcrossselling.vue?vue&type=template&id=96907bac&":
/*!********************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/src/variationcrossselling.vue?vue&type=template&id=96907bac& ***!
  \********************************************************************************************************************************************************************************************************************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": function() { return /* binding */ render; },
/* harmony export */   "staticRenderFns": function() { return /* binding */ staticRenderFns; }
/* harmony export */ });
var render = function render() {
  var _vm = this,
      _c = _vm._self._c;

  return _vm.currentCrossSeller.length > 0 ? _c("div", {
    staticClass: "widget widget-filter-base"
  }, [_c("div", {
    staticClass: "crosslist"
  }, [_vm.showheadline === true ? [_c("div", {
    staticClass: "h3 mb-3"
  }, [_vm._v("\n        " + _vm._s(_vm.$translate("VariationCrossSelling::Template.translateAddonItemsHeadline")) + "\n      ")])] : _vm._e(), _vm._v(" "), _c("ul", {
    staticClass: "list-unstyled",
    attrs: {
      id: "single-crosslist"
    }
  }, _vm._l(_vm.currentCrossSeller, function (item) {
    return _c("li", {
      staticClass: "mb-1"
    }, [item.data && item.data.variation && item.data.variation.id > 0 ? [_c("label", {
      staticClass: "cursor d-flex align-items-center mb-0",
      attrs: {
        for: "var_" + item.data.variation.id
      }
    }, [_c("div", {
      staticClass: "d-flex flex-fill"
    }, [item.data.images.variation && item.data.images.variation.length > 0 ? [_c("div", [_c("img", {
      staticClass: "img-fluid mx-4 my-2",
      staticStyle: {
        height: "70px"
      },
      attrs: {
        src: item.data.images.variation[0].urlPreview,
        alt: item.data.texts.name1
      }
    })])] : item.data.images.all && item.data.images.all.length > 0 ? [_c("div", [_c("img", {
      staticClass: "img-fluid mx-4 my-2",
      staticStyle: {
        height: "70px"
      },
      attrs: {
        src: item.data.images.all[0].urlPreview,
        alt: item.data.texts.name1
      }
    })])] : [_c("div", {
      staticClass: "mr-3",
      staticStyle: {
        height: "70px",
        width: "70px"
      }
    })], _vm._v(" "), _c("div", {
      staticClass: "flex-fill my-2"
    }, [_c("div", {
      staticClass: "crosslisttitle"
    }, [_c("a", {
      attrs: {
        href: _vm._f("itemURL")(item.data)
      }
    }, [_c("b", [_vm._v(_vm._s(item.data.texts.name1))])])]), _vm._v(" "), _c("div", {
      staticClass: "crosslistadditional"
    }, [_c("div", {
      staticClass: "d-flex"
    }, [_c("div", [_c("span", {
      staticClass: "vat small text-muted"
    }, [_vm._v("\n                           " + _vm._s(item.data.prices.default.price.formatted) + "\n                      ")])])])])]), _vm._v(" "), _c("div", {
      staticClass: "crossSellerAddToCartBtnWrapper"
    }, [_c("button", {
      staticClass: "btn btn-primary btn-appearance",
      class: {
        crossSellerAddToCartBtnRounded: _vm.roundedbtns == true
      },
      attrs: {
        disabled: _vm.addItemToBasketLoading
      },
      on: {
        click: function click($event) {
          return _vm.addItemToBasket([item]);
        }
      }
    }, [_vm.addItemToBasketLoading == true ? [_c("i", {
      staticClass: "fa fa-refresh fa-spin",
      attrs: {
        "aria-hidden": "true"
      }
    })] : [_c("i", {
      staticClass: "fa fa-cart-plus",
      attrs: {
        "aria-hidden": "true"
      }
    })]], 2)])], 2)])] : _vm._e()], 2);
  }), 0)], 2)]) : _vm._e();
};

var staticRenderFns = [];
render._withStripped = true;


/***/ }),

/***/ "./resources/js/src/variationcrossselling.vue":
/*!****************************************************!*\
  !*** ./resources/js/src/variationcrossselling.vue ***!
  \****************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _variationcrossselling_vue_vue_type_template_id_96907bac___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./variationcrossselling.vue?vue&type=template&id=96907bac& */ "./resources/js/src/variationcrossselling.vue?vue&type=template&id=96907bac&");
/* harmony import */ var _variationcrossselling_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./variationcrossselling.vue?vue&type=script&lang=js& */ "./resources/js/src/variationcrossselling.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _variationcrossselling_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _variationcrossselling_vue_vue_type_template_id_96907bac___WEBPACK_IMPORTED_MODULE_0__.render,
  _variationcrossselling_vue_vue_type_template_id_96907bac___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/src/variationcrossselling.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/src/variationcrossselling.vue?vue&type=script&lang=js&":
/*!*****************************************************************************!*\
  !*** ./resources/js/src/variationcrossselling.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_node_modules_vue_loader_lib_index_js_vue_loader_options_variationcrossselling_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./variationcrossselling.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/src/variationcrossselling.vue?vue&type=script&lang=js&");
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_node_modules_vue_loader_lib_index_js_vue_loader_options_variationcrossselling_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/src/variationcrossselling.vue?vue&type=template&id=96907bac&":
/*!***********************************************************************************!*\
  !*** ./resources/js/src/variationcrossselling.vue?vue&type=template&id=96907bac& ***!
  \***********************************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": function() { return /* reexport safe */ _node_modules_babel_loader_lib_index_js_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_variationcrossselling_vue_vue_type_template_id_96907bac___WEBPACK_IMPORTED_MODULE_0__.render; },
/* harmony export */   "staticRenderFns": function() { return /* reexport safe */ _node_modules_babel_loader_lib_index_js_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_variationcrossselling_vue_vue_type_template_id_96907bac___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns; }
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_variationcrossselling_vue_vue_type_template_id_96907bac___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./variationcrossselling.vue?vue&type=template&id=96907bac& */ "./node_modules/babel-loader/lib/index.js!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/src/variationcrossselling.vue?vue&type=template&id=96907bac&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js":
/*!********************************************************************!*\
  !*** ./node_modules/vue-loader/lib/runtime/componentNormalizer.js ***!
  \********************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ normalizeComponent; }
/* harmony export */ });
/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file (except for modules).
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

function normalizeComponent(
  scriptExports,
  render,
  staticRenderFns,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier /* server only */,
  shadowMode /* vue-cli only */
) {
  // Vue.extend constructor export interop
  var options =
    typeof scriptExports === 'function' ? scriptExports.options : scriptExports

  // render functions
  if (render) {
    options.render = render
    options.staticRenderFns = staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = 'data-v-' + scopeId
  }

  var hook
  if (moduleIdentifier) {
    // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = shadowMode
      ? function () {
          injectStyles.call(
            this,
            (options.functional ? this.parent : this).$root.$options.shadowRoot
          )
        }
      : injectStyles
  }

  if (hook) {
    if (options.functional) {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functional component in vue file
      var originalRender = options.render
      options.render = function renderWithStyleInjection(h, context) {
        hook.call(context)
        return originalRender(h, context)
      }
    } else {
      // inject component registration as beforeCreate hook
      var existing = options.beforeCreate
      options.beforeCreate = existing ? [].concat(existing, hook) : [hook]
    }
  }

  return {
    exports: scriptExports,
    options: options
  }
}


/***/ })

}]);
//# sourceMappingURL=VariationCrossSelling-1.js.map