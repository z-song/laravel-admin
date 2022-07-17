/*!
 * FilePondPluginImageResize 2.0.10
 * Licensed under MIT, https://opensource.org/licenses/MIT/
 * Please visit https://pqina.nl/filepond/ for details.
 */

/* eslint-disable */

!function(e,t){"object"==typeof exports&&"undefined"!=typeof module?module.exports=t():"function"==typeof define&&define.amd?define(t):(e=e||self).FilePondPluginImageResize=t()}(this,function(){"use strict";var e=function(e){var t=e.addFilter,i=e.utils.Type;return t("DID_LOAD_ITEM",function(e,t){var i=t.query;return new Promise(function(t,n){var r=e.file;if(!function(e){return/^image/.test(e.type)}(r)||!i("GET_ALLOW_IMAGE_RESIZE"))return t(e);var u=i("GET_IMAGE_RESIZE_MODE"),o=i("GET_IMAGE_RESIZE_TARGET_WIDTH"),a=i("GET_IMAGE_RESIZE_TARGET_HEIGHT"),l=i("GET_IMAGE_RESIZE_UPSCALE");if(null===o&&null===a)return t(e);var d,f,E,s=null===o?a:o,c=null===a?s:a,I=URL.createObjectURL(r);d=I,f=function(i){if(URL.revokeObjectURL(I),!i)return t(e);var n=i.width,r=i.height,o=(e.getMetadata("exif")||{}).orientation||-1;if(o>=5&&o<=8){var a=[r,n];n=a[0],r=a[1]}if(n===s&&r===c)return t(e);if(!l)if("cover"===u){if(n<=s||r<=c)return t(e)}else if(n<=s&&r<=s)return t(e);e.setMetadata("resize",{mode:u,upscale:l,size:{width:s,height:c}}),t(e)},(E=new Image).onload=function(){var e=E.naturalWidth,t=E.naturalHeight;E=null,f({width:e,height:t})},E.onerror=function(){return f(null)},E.src=d})}),{options:{allowImageResize:[!0,i.BOOLEAN],imageResizeMode:["cover",i.STRING],imageResizeUpscale:[!0,i.BOOLEAN],imageResizeTargetWidth:[null,i.INT],imageResizeTargetHeight:[null,i.INT]}}};return"undefined"!=typeof window&&void 0!==window.document&&document.dispatchEvent(new CustomEvent("FilePond:pluginloaded",{detail:e})),e});
