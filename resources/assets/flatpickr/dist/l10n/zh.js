(function (global, factory) {
  typeof exports === 'object' && typeof module !== 'undefined' ? factory(exports) :
  typeof define === 'function' && define.amd ? define(['exports'], factory) :
  (global = global || self, factory(global.zh = {}));
}(this, (function (exports) { 'use strict';

  var fp = typeof window !== "undefined" && window.flatpickr !== undefined
      ? window.flatpickr
      : {
          l10ns: {},
      };
  var Mandarin = {
      weekdays: {
          shorthand: ["周日", "周一", "周二", "周三", "周四", "周五", "周六"],
          longhand: [
              "星期日",
              "星期一",
              "星期二",
              "星期三",
              "星期四",
              "星期五",
              "星期六",
          ],
      },
      months: {
          shorthand: [
              "一月",
              "二月",
              "三月",
              "四月",
              "五月",
              "六月",
              "七月",
              "八月",
              "九月",
              "十月",
              "十一月",
              "十二月",
          ],
          longhand: [
              "一月",
              "二月",
              "三月",
              "四月",
              "五月",
              "六月",
              "七月",
              "八月",
              "九月",
              "十月",
              "十一月",
              "十二月",
          ],
      },
      rangeSeparator: " 至 ",
      weekAbbreviation: "周",
      scrollTitle: "滚动切换",
      toggleTitle: "点击切换 12/24 小时时制",
  };
  fp.l10ns.zh = Mandarin;
  var zh = fp.l10ns;

  exports.Mandarin = Mandarin;
  exports.default = zh;

  Object.defineProperty(exports, '__esModule', { value: true });

})));
