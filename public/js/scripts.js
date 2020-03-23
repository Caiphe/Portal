/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/scripts.js":
/*!*********************************!*\
  !*** ./resources/js/scripts.js ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

document.getElementById('profile').addEventListener('click', toggleProfileMenu);
document.getElementById('profile-menu-background').addEventListener('click', toggleProfileMenu);

function toggleProfileMenu() {
  document.body.classList.toggle('show-profile-menu');
}

/***/ }),

/***/ "./resources/sass/components/card-link.scss":
/*!**************************************************!*\
  !*** ./resources/sass/components/card-link.scss ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./resources/sass/components/card-product.scss":
/*!*****************************************************!*\
  !*** ./resources/sass/components/card-product.scss ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./resources/sass/components/card.scss":
/*!*********************************************!*\
  !*** ./resources/sass/components/card.scss ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./resources/sass/components/carousel.scss":
/*!*************************************************!*\
  !*** ./resources/sass/components/carousel.scss ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./resources/sass/components/key-feature.scss":
/*!****************************************************!*\
  !*** ./resources/sass/components/key-feature.scss ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./resources/sass/components/multiselect.scss":
/*!****************************************************!*\
  !*** ./resources/sass/components/multiselect.scss ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./resources/sass/components/sidebar-accordion.scss":
/*!**********************************************************!*\
  !*** ./resources/sass/components/sidebar-accordion.scss ***!
  \**********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./resources/sass/styles.scss":
/*!************************************!*\
  !*** ./resources/sass/styles.scss ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 0:
/*!******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** multi ./resources/js/scripts.js ./resources/sass/components/carousel.scss ./resources/sass/components/multiselect.scss ./resources/sass/components/card.scss ./resources/sass/components/card-link.scss ./resources/sass/components/card-product.scss ./resources/sass/components/key-feature.scss ./resources/sass/components/sidebar-accordion.scss ./resources/sass/styles.scss ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! /Users/wesleymartin/www/developer-portal.test/resources/js/scripts.js */"./resources/js/scripts.js");
__webpack_require__(/*! /Users/wesleymartin/www/developer-portal.test/resources/sass/components/carousel.scss */"./resources/sass/components/carousel.scss");
__webpack_require__(/*! /Users/wesleymartin/www/developer-portal.test/resources/sass/components/multiselect.scss */"./resources/sass/components/multiselect.scss");
__webpack_require__(/*! /Users/wesleymartin/www/developer-portal.test/resources/sass/components/card.scss */"./resources/sass/components/card.scss");
__webpack_require__(/*! /Users/wesleymartin/www/developer-portal.test/resources/sass/components/card-link.scss */"./resources/sass/components/card-link.scss");
__webpack_require__(/*! /Users/wesleymartin/www/developer-portal.test/resources/sass/components/card-product.scss */"./resources/sass/components/card-product.scss");
__webpack_require__(/*! /Users/wesleymartin/www/developer-portal.test/resources/sass/components/key-feature.scss */"./resources/sass/components/key-feature.scss");
__webpack_require__(/*! /Users/wesleymartin/www/developer-portal.test/resources/sass/components/sidebar-accordion.scss */"./resources/sass/components/sidebar-accordion.scss");
module.exports = __webpack_require__(/*! /Users/wesleymartin/www/developer-portal.test/resources/sass/styles.scss */"./resources/sass/styles.scss");


/***/ })

/******/ });