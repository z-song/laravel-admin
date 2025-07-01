import path from 'node:path';
import crypto from 'node:crypto';
import { createRequire } from 'node:module';
import { fileURLToPath } from 'node:url';
import { build, normalizePath } from 'vite';
import MagicString from 'magic-string';
import browserslist from 'browserslist';

function getDefaultExportFromCjs (x) {
	return x && x.__esModule && Object.prototype.hasOwnProperty.call(x, 'default') ? x['default'] : x;
}

var picocolors = {exports: {}};

let p = process || {}, argv = p.argv || [], env = p.env || {};
let isColorSupported =
	!(!!env.NO_COLOR || argv.includes("--no-color")) &&
	(!!env.FORCE_COLOR || argv.includes("--color") || p.platform === "win32" || ((p.stdout || {}).isTTY && env.TERM !== "dumb") || !!env.CI);

let formatter = (open, close, replace = open) =>
	input => {
		let string = "" + input, index = string.indexOf(close, open.length);
		return ~index ? open + replaceClose(string, close, replace, index) + close : open + string + close
	};

let replaceClose = (string, close, replace, index) => {
	let result = "", cursor = 0;
	do {
		result += string.substring(cursor, index) + replace;
		cursor = index + close.length;
		index = string.indexOf(close, cursor);
	} while (~index)
	return result + string.substring(cursor)
};

let createColors = (enabled = isColorSupported) => {
	let f = enabled ? formatter : () => String;
	return {
		isColorSupported: enabled,
		reset: f("\x1b[0m", "\x1b[0m"),
		bold: f("\x1b[1m", "\x1b[22m", "\x1b[22m\x1b[1m"),
		dim: f("\x1b[2m", "\x1b[22m", "\x1b[22m\x1b[2m"),
		italic: f("\x1b[3m", "\x1b[23m"),
		underline: f("\x1b[4m", "\x1b[24m"),
		inverse: f("\x1b[7m", "\x1b[27m"),
		hidden: f("\x1b[8m", "\x1b[28m"),
		strikethrough: f("\x1b[9m", "\x1b[29m"),

		black: f("\x1b[30m", "\x1b[39m"),
		red: f("\x1b[31m", "\x1b[39m"),
		green: f("\x1b[32m", "\x1b[39m"),
		yellow: f("\x1b[33m", "\x1b[39m"),
		blue: f("\x1b[34m", "\x1b[39m"),
		magenta: f("\x1b[35m", "\x1b[39m"),
		cyan: f("\x1b[36m", "\x1b[39m"),
		white: f("\x1b[37m", "\x1b[39m"),
		gray: f("\x1b[90m", "\x1b[39m"),

		bgBlack: f("\x1b[40m", "\x1b[49m"),
		bgRed: f("\x1b[41m", "\x1b[49m"),
		bgGreen: f("\x1b[42m", "\x1b[49m"),
		bgYellow: f("\x1b[43m", "\x1b[49m"),
		bgBlue: f("\x1b[44m", "\x1b[49m"),
		bgMagenta: f("\x1b[45m", "\x1b[49m"),
		bgCyan: f("\x1b[46m", "\x1b[49m"),
		bgWhite: f("\x1b[47m", "\x1b[49m"),

		blackBright: f("\x1b[90m", "\x1b[39m"),
		redBright: f("\x1b[91m", "\x1b[39m"),
		greenBright: f("\x1b[92m", "\x1b[39m"),
		yellowBright: f("\x1b[93m", "\x1b[39m"),
		blueBright: f("\x1b[94m", "\x1b[39m"),
		magentaBright: f("\x1b[95m", "\x1b[39m"),
		cyanBright: f("\x1b[96m", "\x1b[39m"),
		whiteBright: f("\x1b[97m", "\x1b[39m"),

		bgBlackBright: f("\x1b[100m", "\x1b[49m"),
		bgRedBright: f("\x1b[101m", "\x1b[49m"),
		bgGreenBright: f("\x1b[102m", "\x1b[49m"),
		bgYellowBright: f("\x1b[103m", "\x1b[49m"),
		bgBlueBright: f("\x1b[104m", "\x1b[49m"),
		bgMagentaBright: f("\x1b[105m", "\x1b[49m"),
		bgCyanBright: f("\x1b[106m", "\x1b[49m"),
		bgWhiteBright: f("\x1b[107m", "\x1b[49m"),
	}
};

picocolors.exports = createColors();
picocolors.exports.createColors = createColors;

var picocolorsExports = picocolors.exports;
const colors = /*@__PURE__*/getDefaultExportFromCjs(picocolorsExports);

const safari10NoModuleFix = `!function(){var e=document,t=e.createElement("script");if(!("noModule"in t)&&"onbeforeload"in t){var n=!1;e.addEventListener("beforeload",(function(e){if(e.target===t)n=!0;else if(!e.target.hasAttribute("nomodule")||!n)return;e.preventDefault()}),!0),t.type="module",t.src=".",e.head.appendChild(t),t.remove()}}();`;
const legacyPolyfillId = "vite-legacy-polyfill";
const legacyEntryId = "vite-legacy-entry";
const systemJSInlineCode = `System.import(document.getElementById('${legacyEntryId}').getAttribute('data-src'))`;
const detectModernBrowserVarName = "__vite_is_modern_browser";
const detectModernBrowserDetector = `import.meta.url;import("_").catch(()=>1);(async function*(){})().next()`;
const detectModernBrowserCode = `${detectModernBrowserDetector};if(location.protocol!="file:"){window.${detectModernBrowserVarName}=true}`;
const dynamicFallbackInlineCode = `!function(){if(window.${detectModernBrowserVarName})return;console.warn("vite: loading legacy chunks, syntax error above and the same error below should be ignored");var e=document.getElementById("${legacyPolyfillId}"),n=document.createElement("script");n.src=e.src,n.onload=function(){${systemJSInlineCode}},document.body.appendChild(n)}();`;
const modernChunkLegacyGuard = `export function __vite_legacy_guard(){${detectModernBrowserDetector}};`;

let babel;
async function loadBabel() {
  if (!babel) {
    babel = await import('@babel/core');
  }
  return babel;
}
const { loadConfig: browserslistLoadConfig } = browserslist;
function toOutputFilePathInHtml(filename, type, hostId, hostType, config, toRelative) {
  const { renderBuiltUrl } = config.experimental;
  let relative = config.base === "" || config.base === "./";
  if (renderBuiltUrl) {
    const result = renderBuiltUrl(filename, {
      hostId,
      hostType,
      type,
      ssr: !!config.build.ssr
    });
    if (typeof result === "object") {
      if (result.runtime) {
        throw new Error(
          `{ runtime: "${result.runtime}" } is not supported for assets in ${hostType} files: ${filename}`
        );
      }
      if (typeof result.relative === "boolean") {
        relative = result.relative;
      }
    } else if (result) {
      return result;
    }
  }
  if (relative && !config.build.ssr) {
    return toRelative(filename, hostId);
  } else {
    return joinUrlSegments(config.decodedBase, filename);
  }
}
function getBaseInHTML(urlRelativePath, config) {
  return config.base === "./" || config.base === "" ? path.posix.join(
    path.posix.relative(urlRelativePath, "").slice(0, -2),
    "./"
  ) : config.base;
}
function joinUrlSegments(a, b) {
  if (!a || !b) {
    return a || b || "";
  }
  if (a[a.length - 1] === "/") {
    a = a.substring(0, a.length - 1);
  }
  if (b[0] !== "/") {
    b = "/" + b;
  }
  return a + b;
}
function toAssetPathFromHtml(filename, htmlPath, config) {
  const relativeUrlPath = normalizePath(path.relative(config.root, htmlPath));
  const toRelative = (filename2, _hostId) => getBaseInHTML(relativeUrlPath, config) + filename2;
  return toOutputFilePathInHtml(
    filename,
    "asset",
    htmlPath,
    "html",
    config,
    toRelative
  );
}
const legacyEnvVarMarker = `__VITE_IS_LEGACY__`;
const _require = createRequire(import.meta.url);
const nonLeadingHashInFileNameRE = /[^/]+\[hash(?::\d+)?\]/;
const prefixedHashInFileNameRE = /\W?\[hash(?::\d+)?\]/;
function viteLegacyPlugin(options = {}) {
  let config;
  let targets;
  let modernTargets;
  const modernTargetsEsbuild = [
    "es2020",
    "edge79",
    "firefox67",
    "chrome64",
    "safari12"
  ];
  const modernTargetsBabel = "edge>=79, firefox>=67, chrome>=64, safari>=12, chromeAndroid>=64, iOS>=12";
  const genLegacy = options.renderLegacyChunks !== false;
  const genModern = options.renderModernChunks !== false;
  if (!genLegacy && !genModern) {
    throw new Error(
      "`renderLegacyChunks` and `renderModernChunks` cannot be both false"
    );
  }
  const debugFlags = (process.env.DEBUG || "").split(",");
  const isDebug = debugFlags.includes("vite:*") || debugFlags.includes("vite:legacy");
  const facadeToLegacyChunkMap = /* @__PURE__ */ new Map();
  const facadeToLegacyPolyfillMap = /* @__PURE__ */ new Map();
  const facadeToModernPolyfillMap = /* @__PURE__ */ new Map();
  const modernPolyfills = /* @__PURE__ */ new Set();
  const legacyPolyfills = /* @__PURE__ */ new Set();
  const outputToChunkFileNameToPolyfills = /* @__PURE__ */ new WeakMap();
  if (Array.isArray(options.modernPolyfills) && genModern) {
    options.modernPolyfills.forEach((i) => {
      modernPolyfills.add(
        i.includes("/") ? `core-js/${i}` : `core-js/modules/${i}.js`
      );
    });
  }
  if (Array.isArray(options.additionalModernPolyfills)) {
    options.additionalModernPolyfills.forEach((i) => {
      modernPolyfills.add(i);
    });
  }
  if (Array.isArray(options.polyfills)) {
    options.polyfills.forEach((i) => {
      if (i.startsWith(`regenerator`)) {
        legacyPolyfills.add(`regenerator-runtime/runtime.js`);
      } else {
        legacyPolyfills.add(
          i.includes("/") ? `core-js/${i}` : `core-js/modules/${i}.js`
        );
      }
    });
  }
  if (Array.isArray(options.additionalLegacyPolyfills)) {
    options.additionalLegacyPolyfills.forEach((i) => {
      legacyPolyfills.add(i);
    });
  }
  let overriddenBuildTarget = false;
  let overriddenDefaultModernTargets = false;
  const legacyConfigPlugin = {
    name: "vite:legacy-config",
    async config(config2, env) {
      if (env.command === "build" && !config2.build?.ssr) {
        if (!config2.build) {
          config2.build = {};
        }
        if (!config2.build.cssTarget) {
          config2.build.cssTarget = "chrome61";
        }
        if (genLegacy) {
          overriddenBuildTarget = config2.build.target !== void 0;
          overriddenDefaultModernTargets = options.modernTargets !== void 0;
          if (options.modernTargets) {
            const { default: browserslistToEsbuild } = await import('browserslist-to-esbuild');
            config2.build.target = browserslistToEsbuild(options.modernTargets);
          } else {
            config2.build.target = modernTargetsEsbuild;
          }
        }
      }
      return {
        define: {
          "import.meta.env.LEGACY": env.command === "serve" || config2.build?.ssr ? false : legacyEnvVarMarker
        }
      };
    },
    configResolved(config2) {
      if (overriddenBuildTarget) {
        config2.logger.warn(
          colors.yellow(
            `plugin-legacy overrode 'build.target'. You should pass 'targets' as an option to this plugin with the list of legacy browsers to support instead.`
          )
        );
      }
      if (overriddenDefaultModernTargets) {
        config2.logger.warn(
          colors.yellow(
            `plugin-legacy 'modernTargets' option overrode the builtin targets of modern chunks. Some versions of browsers between legacy and modern may not be supported.`
          )
        );
      }
    }
  };
  const legacyGenerateBundlePlugin = {
    name: "vite:legacy-generate-polyfill-chunk",
    apply: "build",
    async generateBundle(opts, bundle) {
      if (config.build.ssr) {
        return;
      }
      const chunkFileNameToPolyfills = outputToChunkFileNameToPolyfills.get(opts);
      if (chunkFileNameToPolyfills == null) {
        throw new Error(
          "Internal @vitejs/plugin-legacy error: discovered polyfills should exist"
        );
      }
      if (!isLegacyBundle(bundle, opts)) {
        for (const { modern } of chunkFileNameToPolyfills.values()) {
          modern.forEach((p) => modernPolyfills.add(p));
        }
        if (!modernPolyfills.size) {
          return;
        }
        if (isDebug) {
          console.log(
            `[@vitejs/plugin-legacy] modern polyfills:`,
            modernPolyfills
          );
        }
        await buildPolyfillChunk(
          config.mode,
          modernPolyfills,
          bundle,
          facadeToModernPolyfillMap,
          config.build,
          "es",
          opts,
          true,
          genLegacy
        );
        return;
      }
      if (!genLegacy) {
        return;
      }
      for (const { legacy } of chunkFileNameToPolyfills.values()) {
        legacy.forEach((p) => legacyPolyfills.add(p));
      }
      if (options.polyfills !== false) {
        await detectPolyfills(
          `Promise.resolve(); Promise.all();`,
          targets,
          legacyPolyfills
        );
      }
      if (legacyPolyfills.size || !options.externalSystemJS) {
        if (isDebug) {
          console.log(
            `[@vitejs/plugin-legacy] legacy polyfills:`,
            legacyPolyfills
          );
        }
        await buildPolyfillChunk(
          config.mode,
          legacyPolyfills,
          bundle,
          facadeToLegacyPolyfillMap,
          // force using terser for legacy polyfill minification, since esbuild
          // isn't legacy-safe
          config.build,
          "iife",
          opts,
          options.externalSystemJS
        );
      }
    }
  };
  const legacyPostPlugin = {
    name: "vite:legacy-post-process",
    enforce: "post",
    apply: "build",
    renderStart(opts) {
      outputToChunkFileNameToPolyfills.set(opts, null);
    },
    configResolved(_config) {
      if (_config.build.lib) {
        throw new Error("@vitejs/plugin-legacy does not support library mode.");
      }
      config = _config;
      modernTargets = options.modernTargets || modernTargetsBabel;
      if (isDebug) {
        console.log(`[@vitejs/plugin-legacy] modernTargets:`, modernTargets);
      }
      if (!genLegacy || config.build.ssr) {
        return;
      }
      targets = options.targets || browserslistLoadConfig({ path: config.root }) || "last 2 versions and not dead, > 0.3%, Firefox ESR";
      if (isDebug) {
        console.log(`[@vitejs/plugin-legacy] targets:`, targets);
      }
      const getLegacyOutputFileName = (fileNames, defaultFileName = "[name]-legacy-[hash].js") => {
        if (!fileNames) {
          return path.posix.join(config.build.assetsDir, defaultFileName);
        }
        return (chunkInfo) => {
          let fileName = typeof fileNames === "function" ? fileNames(chunkInfo) : fileNames;
          if (fileName.includes("[name]")) {
            fileName = fileName.replace("[name]", "[name]-legacy");
          } else if (nonLeadingHashInFileNameRE.test(fileName)) {
            fileName = fileName.replace(prefixedHashInFileNameRE, "-legacy$&");
          } else {
            fileName = fileName.replace(/(.+?)\.(.+)/, "$1-legacy.$2");
          }
          return fileName;
        };
      };
      const createLegacyOutput = (options2 = {}) => {
        return {
          ...options2,
          format: "system",
          entryFileNames: getLegacyOutputFileName(options2.entryFileNames),
          chunkFileNames: getLegacyOutputFileName(options2.chunkFileNames)
        };
      };
      const { rollupOptions } = config.build;
      const { output } = rollupOptions;
      if (Array.isArray(output)) {
        rollupOptions.output = [
          ...output.map(createLegacyOutput),
          ...genModern ? output : []
        ];
      } else {
        rollupOptions.output = [
          createLegacyOutput(output),
          ...genModern ? [output || {}] : []
        ];
      }
    },
    async renderChunk(raw, chunk, opts, { chunks }) {
      if (config.build.ssr) {
        return null;
      }
      let chunkFileNameToPolyfills = outputToChunkFileNameToPolyfills.get(opts);
      if (chunkFileNameToPolyfills == null) {
        chunkFileNameToPolyfills = /* @__PURE__ */ new Map();
        for (const fileName in chunks) {
          chunkFileNameToPolyfills.set(fileName, {
            modern: /* @__PURE__ */ new Set(),
            legacy: /* @__PURE__ */ new Set()
          });
        }
        outputToChunkFileNameToPolyfills.set(opts, chunkFileNameToPolyfills);
      }
      const polyfillsDiscovered = chunkFileNameToPolyfills.get(chunk.fileName);
      if (polyfillsDiscovered == null) {
        throw new Error(
          `Internal @vitejs/plugin-legacy error: discovered polyfills for ${chunk.fileName} should exist`
        );
      }
      if (!isLegacyChunk(chunk, opts)) {
        if (options.modernPolyfills && !Array.isArray(options.modernPolyfills) && genModern) {
          await detectPolyfills(raw, modernTargets, polyfillsDiscovered.modern);
        }
        const ms = new MagicString(raw);
        if (genLegacy && chunk.isEntry) {
          ms.prepend(modernChunkLegacyGuard);
        }
        if (raw.includes(legacyEnvVarMarker)) {
          const re = new RegExp(legacyEnvVarMarker, "g");
          let match;
          while (match = re.exec(raw)) {
            ms.overwrite(
              match.index,
              match.index + legacyEnvVarMarker.length,
              `false`
            );
          }
        }
        if (config.build.sourcemap) {
          return {
            code: ms.toString(),
            map: ms.generateMap({ hires: "boundary" })
          };
        }
        return {
          code: ms.toString()
        };
      }
      if (!genLegacy) {
        return null;
      }
      opts.__vite_skip_esbuild__ = true;
      opts.__vite_force_terser__ = true;
      opts.__vite_skip_asset_emit__ = true;
      const needPolyfills = options.polyfills !== false && !Array.isArray(options.polyfills);
      const sourceMaps = !!config.build.sourcemap;
      const babel2 = await loadBabel();
      const result = babel2.transform(raw, {
        babelrc: false,
        configFile: false,
        compact: !!config.build.minify,
        sourceMaps,
        inputSourceMap: void 0,
        presets: [
          // forcing our plugin to run before preset-env by wrapping it in a
          // preset so we can catch the injected import statements...
          [
            () => ({
              plugins: [
                recordAndRemovePolyfillBabelPlugin(polyfillsDiscovered.legacy),
                replaceLegacyEnvBabelPlugin(),
                wrapIIFEBabelPlugin()
              ]
            })
          ],
          [
            (await import('@babel/preset-env')).default,
            createBabelPresetEnvOptions(targets, { needPolyfills })
          ]
        ]
      });
      if (result)
        return { code: result.code, map: result.map };
      return null;
    },
    transformIndexHtml(html, { chunk }) {
      if (config.build.ssr)
        return;
      if (!chunk)
        return;
      if (chunk.fileName.includes("-legacy")) {
        facadeToLegacyChunkMap.set(chunk.facadeModuleId, chunk.fileName);
        if (genModern) {
          return;
        }
      }
      if (!genModern) {
        html = html.replace(/<script type="module".*?<\/script>/g, "");
      }
      const tags = [];
      const htmlFilename = chunk.facadeModuleId?.replace(/\?.*$/, "");
      if (genModern) {
        const modernPolyfillFilename = facadeToModernPolyfillMap.get(
          chunk.facadeModuleId
        );
        if (modernPolyfillFilename) {
          tags.push({
            tag: "script",
            attrs: {
              type: "module",
              crossorigin: true,
              src: toAssetPathFromHtml(
                modernPolyfillFilename,
                chunk.facadeModuleId,
                config
              )
            }
          });
        } else if (modernPolyfills.size) {
          throw new Error(
            `No corresponding modern polyfill chunk found for ${htmlFilename}`
          );
        }
      }
      if (!genLegacy) {
        return { html, tags };
      }
      if (genModern) {
        tags.push({
          tag: "script",
          attrs: { nomodule: genModern },
          children: safari10NoModuleFix,
          injectTo: "body"
        });
      }
      const legacyPolyfillFilename = facadeToLegacyPolyfillMap.get(
        chunk.facadeModuleId
      );
      if (legacyPolyfillFilename) {
        tags.push({
          tag: "script",
          attrs: {
            nomodule: genModern,
            crossorigin: true,
            id: legacyPolyfillId,
            src: toAssetPathFromHtml(
              legacyPolyfillFilename,
              chunk.facadeModuleId,
              config
            )
          },
          injectTo: "body"
        });
      } else if (legacyPolyfills.size) {
        throw new Error(
          `No corresponding legacy polyfill chunk found for ${htmlFilename}`
        );
      }
      const legacyEntryFilename = facadeToLegacyChunkMap.get(
        chunk.facadeModuleId
      );
      if (legacyEntryFilename) {
        tags.push({
          tag: "script",
          attrs: {
            nomodule: genModern,
            crossorigin: true,
            // we set the entry path on the element as an attribute so that the
            // script content will stay consistent - which allows using a constant
            // hash value for CSP.
            id: legacyEntryId,
            "data-src": toAssetPathFromHtml(
              legacyEntryFilename,
              chunk.facadeModuleId,
              config
            )
          },
          children: systemJSInlineCode,
          injectTo: "body"
        });
      } else {
        throw new Error(
          `No corresponding legacy entry chunk found for ${htmlFilename}`
        );
      }
      if (legacyPolyfillFilename && legacyEntryFilename && genModern) {
        tags.push({
          tag: "script",
          attrs: { type: "module" },
          children: detectModernBrowserCode,
          injectTo: "head"
        });
        tags.push({
          tag: "script",
          attrs: { type: "module" },
          children: dynamicFallbackInlineCode,
          injectTo: "head"
        });
      }
      return {
        html,
        tags
      };
    },
    generateBundle(opts, bundle) {
      if (config.build.ssr) {
        return;
      }
      if (isLegacyBundle(bundle, opts) && genModern) {
        for (const name in bundle) {
          if (bundle[name].type === "asset" && !/.+\.map$/.test(name)) {
            delete bundle[name];
          }
        }
      }
    }
  };
  return [legacyConfigPlugin, legacyGenerateBundlePlugin, legacyPostPlugin];
}
async function detectPolyfills(code, targets, list) {
  const babel2 = await loadBabel();
  const result = babel2.transform(code, {
    ast: true,
    babelrc: false,
    configFile: false,
    compact: false,
    presets: [
      [
        (await import('@babel/preset-env')).default,
        createBabelPresetEnvOptions(targets, {})
      ]
    ]
  });
  for (const node of result.ast.program.body) {
    if (node.type === "ImportDeclaration") {
      const source = node.source.value;
      if (source.startsWith("core-js/") || source.startsWith("regenerator-runtime/")) {
        list.add(source);
      }
    }
  }
}
function createBabelPresetEnvOptions(targets, { needPolyfills = true }) {
  return {
    targets,
    bugfixes: true,
    loose: false,
    modules: false,
    useBuiltIns: needPolyfills ? "usage" : false,
    corejs: needPolyfills ? {
      version: _require("core-js/package.json").version,
      proposals: false
    } : void 0,
    shippedProposals: true,
    ignoreBrowserslistConfig: true
  };
}
async function buildPolyfillChunk(mode, imports, bundle, facadeToChunkMap, buildOptions, format, rollupOutputOptions, excludeSystemJS, prependModenChunkLegacyGuard) {
  let { minify, assetsDir, sourcemap } = buildOptions;
  minify = minify ? "terser" : false;
  const res = await build({
    mode,
    // so that everything is resolved from here
    root: path.dirname(fileURLToPath(import.meta.url)),
    configFile: false,
    logLevel: "error",
    plugins: [
      polyfillsPlugin(imports, excludeSystemJS),
      prependModenChunkLegacyGuard && prependModenChunkLegacyGuardPlugin()
    ],
    build: {
      write: false,
      minify,
      assetsDir,
      sourcemap,
      rollupOptions: {
        input: {
          polyfills: polyfillId
        },
        output: {
          format,
          entryFileNames: rollupOutputOptions.entryFileNames
        }
      }
    },
    // Don't run esbuild for transpilation or minification
    // because we don't want to transpile code.
    esbuild: false,
    optimizeDeps: {
      esbuildOptions: {
        // If a value above 'es5' is set, esbuild injects helper functions which uses es2015 features.
        // This limits the input code not to include es2015+ codes.
        // But core-js is the only dependency which includes commonjs code
        // and core-js doesn't include es2015+ codes.
        target: "es5"
      }
    }
  });
  const _polyfillChunk = Array.isArray(res) ? res[0] : res;
  if (!("output" in _polyfillChunk))
    return;
  const polyfillChunk = _polyfillChunk.output.find(
    (chunk) => chunk.type === "chunk" && chunk.isEntry
  );
  for (const key in bundle) {
    const chunk = bundle[key];
    if (chunk.type === "chunk" && chunk.facadeModuleId) {
      facadeToChunkMap.set(chunk.facadeModuleId, polyfillChunk.fileName);
    }
  }
  bundle[polyfillChunk.fileName] = polyfillChunk;
  if (polyfillChunk.sourcemapFileName) {
    const polyfillChunkMapAsset = _polyfillChunk.output.find(
      (chunk) => chunk.type === "asset" && chunk.fileName === polyfillChunk.sourcemapFileName
    );
    if (polyfillChunkMapAsset) {
      bundle[polyfillChunk.sourcemapFileName] = polyfillChunkMapAsset;
    }
  }
}
const polyfillId = "\0vite/legacy-polyfills";
function polyfillsPlugin(imports, excludeSystemJS) {
  return {
    name: "vite:legacy-polyfills",
    resolveId(id) {
      if (id === polyfillId) {
        return id;
      }
    },
    load(id) {
      if (id === polyfillId) {
        return [...imports].map((i) => `import ${JSON.stringify(i)};`).join("") + (excludeSystemJS ? "" : `import "systemjs/dist/s.min.js";`);
      }
    }
  };
}
function prependModenChunkLegacyGuardPlugin() {
  let sourceMapEnabled;
  return {
    name: "vite:legacy-prepend-moden-chunk-legacy-guard",
    configResolved(config) {
      sourceMapEnabled = !!config.build.sourcemap;
    },
    renderChunk(code) {
      if (!sourceMapEnabled) {
        return modernChunkLegacyGuard + code;
      }
      const ms = new MagicString(code);
      ms.prepend(modernChunkLegacyGuard);
      return {
        code: ms.toString(),
        map: ms.generateMap({ hires: "boundary" })
      };
    }
  };
}
function isLegacyChunk(chunk, options) {
  return options.format === "system" && chunk.fileName.includes("-legacy");
}
function isLegacyBundle(bundle, options) {
  if (options.format === "system") {
    const entryChunk = Object.values(bundle).find(
      (output) => output.type === "chunk" && output.isEntry
    );
    return !!entryChunk && entryChunk.fileName.includes("-legacy");
  }
  return false;
}
function recordAndRemovePolyfillBabelPlugin(polyfills) {
  return ({ types: t }) => ({
    name: "vite-remove-polyfill-import",
    post({ path: path2 }) {
      path2.get("body").forEach((p) => {
        if (t.isImportDeclaration(p.node)) {
          polyfills.add(p.node.source.value);
          p.remove();
        }
      });
    }
  });
}
function replaceLegacyEnvBabelPlugin() {
  return ({ types: t }) => ({
    name: "vite-replace-env-legacy",
    visitor: {
      Identifier(path2) {
        if (path2.node.name === legacyEnvVarMarker) {
          path2.replaceWith(t.booleanLiteral(true));
        }
      }
    }
  });
}
function wrapIIFEBabelPlugin() {
  return ({ types: t, template }) => {
    const buildIIFE = template(";(function(){%%body%%})();");
    return {
      name: "vite-wrap-iife",
      post({ path: path2 }) {
        if (!this.isWrapped) {
          this.isWrapped = true;
          path2.replaceWith(t.program(buildIIFE({ body: path2.node.body })));
        }
      }
    };
  };
}
const hash = (
  // eslint-disable-next-line n/no-unsupported-features/node-builtins -- crypto.hash is supported in Node 21.7.0+, 20.12.0+
  crypto.hash ?? ((algorithm, data, outputEncoding) => crypto.createHash(algorithm).update(data).digest(outputEncoding))
);
const cspHashes = [
  safari10NoModuleFix,
  systemJSInlineCode,
  detectModernBrowserCode,
  dynamicFallbackInlineCode
].map((i) => hash("sha256", i, "base64"));

export { cspHashes, viteLegacyPlugin as default, detectPolyfills };
