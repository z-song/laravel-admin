(function (global, factory, e, m) {
    typeof exports === 'object' && typeof module !== 'undefined' ? factory(exports, module,require('@jridgewell/gen-mapping'),require('@jridgewell/trace-mapping')) :
    typeof define === 'function' && define.amd ? define(['exports', 'module','@jridgewell/gen-mapping','@jridgewell/trace-mapping'], factory) :
    (global = typeof globalThis !== 'undefined' ? globalThis : global || self, factory(e = {}, m = { exports: e },global.genMapping,global.traceMapping), global.sourceMap = 'default' in m.exports ? m.exports.default : m.exports);
})(this, (function (exports, module,require_genMapping,require_traceMapping) {
"use strict";
if (!Object.getOwnPropertyDescriptors) Object.getOwnPropertyDescriptors = function(value) {
  return Reflect.ownKeys(value).reduce(function (acc, key) {
    Object.defineProperty(acc, key, Object.getOwnPropertyDescriptor(value, key))
    return acc;
  }, {});
}
"use strict";
var __defProp = Object.defineProperty;
var __getOwnPropDesc = Object.getOwnPropertyDescriptor;
var __getOwnPropNames = Object.getOwnPropertyNames;
var __hasOwnProp = Object.prototype.hasOwnProperty;
var __export = (target, all) => {
  for (var name in all)
    __defProp(target, name, { get: all[name], enumerable: true });
};
var __copyProps = (to, from, except, desc) => {
  if (from && typeof from === "object" || typeof from === "function") {
    for (let key of __getOwnPropNames(from))
      if (!__hasOwnProp.call(to, key) && key !== except)
        __defProp(to, key, { get: () => from[key], enumerable: !(desc = __getOwnPropDesc(from, key)) || desc.enumerable });
  }
  return to;
};
var __toCommonJS = (mod) => __copyProps(__defProp({}, "__esModule", { value: true }), mod);

// src/source-map.ts
var source_map_exports = {};
__export(source_map_exports, {
  SourceMapConsumer: () => SourceMapConsumer,
  SourceMapGenerator: () => SourceMapGenerator
});
module.exports = __toCommonJS(source_map_exports);
var import_trace_mapping = require_traceMapping;
var import_gen_mapping = require_genMapping;
var SourceMapConsumer = class _SourceMapConsumer {
  constructor(map, mapUrl) {
    const trace = this._map = new import_trace_mapping.AnyMap(map, mapUrl);
    this.file = trace.file;
    this.names = trace.names;
    this.sourceRoot = trace.sourceRoot;
    this.sources = trace.resolvedSources;
    this.sourcesContent = trace.sourcesContent;
    this.version = trace.version;
  }
  static fromSourceMap(map, mapUrl) {
    if (map.toDecodedMap) {
      return new _SourceMapConsumer(map.toDecodedMap(), mapUrl);
    }
    return new _SourceMapConsumer(map.toJSON(), mapUrl);
  }
  get mappings() {
    return (0, import_trace_mapping.encodedMappings)(this._map);
  }
  originalPositionFor(needle) {
    return (0, import_trace_mapping.originalPositionFor)(this._map, needle);
  }
  generatedPositionFor(originalPosition) {
    return (0, import_trace_mapping.generatedPositionFor)(this._map, originalPosition);
  }
  allGeneratedPositionsFor(originalPosition) {
    return (0, import_trace_mapping.allGeneratedPositionsFor)(this._map, originalPosition);
  }
  hasContentsOfAllSources() {
    if (!this.sourcesContent || this.sourcesContent.length !== this.sources.length) {
      return false;
    }
    for (const content of this.sourcesContent) {
      if (content == null) {
        return false;
      }
    }
    return true;
  }
  sourceContentFor(source, nullOnMissing) {
    const sourceContent = (0, import_trace_mapping.sourceContentFor)(this._map, source);
    if (sourceContent != null) {
      return sourceContent;
    }
    if (nullOnMissing) {
      return null;
    }
    throw new Error(`"${source}" is not in the SourceMap.`);
  }
  eachMapping(callback, context) {
    (0, import_trace_mapping.eachMapping)(this._map, context ? callback.bind(context) : callback);
  }
  destroy() {
  }
};
var SourceMapGenerator = class _SourceMapGenerator {
  constructor(opts) {
    this._map = opts instanceof import_gen_mapping.GenMapping ? opts : new import_gen_mapping.GenMapping(opts);
  }
  static fromSourceMap(consumer) {
    return new _SourceMapGenerator((0, import_gen_mapping.fromMap)(consumer));
  }
  addMapping(mapping) {
    (0, import_gen_mapping.maybeAddMapping)(this._map, mapping);
  }
  setSourceContent(source, content) {
    (0, import_gen_mapping.setSourceContent)(this._map, source, content);
  }
  toJSON() {
    return (0, import_gen_mapping.toEncodedMap)(this._map);
  }
  toString() {
    return JSON.stringify(this.toJSON());
  }
  toDecodedMap() {
    return (0, import_gen_mapping.toDecodedMap)(this._map);
  }
};
}));
//# sourceMappingURL=source-map.umd.js.map
