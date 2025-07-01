import { Plugin } from 'vite';

interface Options {
    /**
     * default: 'defaults'
     */
    targets?: string | string[] | Record<string, string>;
    /**
     * default: 'edge>=79, firefox>=67, chrome>=64, safari>=12, chromeAndroid>=64, iOS>=12'
     */
    modernTargets?: string | string[];
    /**
     * default: true
     */
    polyfills?: boolean | string[];
    additionalLegacyPolyfills?: string[];
    additionalModernPolyfills?: string[];
    /**
     * default: false
     */
    modernPolyfills?: boolean | string[];
    /**
     * default: true
     */
    renderLegacyChunks?: boolean;
    /**
     * default: false
     */
    externalSystemJS?: boolean;
    /**
     * default: true
     */
    renderModernChunks?: boolean;
}

declare function viteLegacyPlugin(options?: Options): Plugin[];
declare function detectPolyfills(code: string, targets: any, list: Set<string>): Promise<void>;
declare const cspHashes: string[];

export { type Options, cspHashes, viteLegacyPlugin as default, detectPolyfills };
