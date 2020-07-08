<?php

namespace Encore\Admin\Traits;

use DOMDocument;
use DOMElement;

trait RenderView
{
    /**
     * @param string $view
     * @param array $data
     * @return string
     * @throws \Throwable
     */
    public static function view(string $view, $data = []) : string
    {
        list($head, $body) = static::getDOMDocument($view, $data);

        $rendered = '';

        foreach ($head->childNodes as $child) {
            if ($child instanceof DOMElement && in_array($child->tagName, ['style', 'script', 'link', 'template'])) {
                static::resolve($child);
                continue;
            }

            $rendered .= trim($head->ownerDocument->saveHTML($child));
        }

        foreach ($body->childNodes as $child) {
            if ($child instanceof \DOMElement && in_array($child->tagName, ['style', 'script', 'template', 'link'])) {
                static::resolve($child);
                continue;
            }

            $rendered .= trim($body->ownerDocument->saveHTML($child));
        }

        return $rendered;
    }

    /**
     * @param string $view
     * @param array $data
     * @return \DOMDocument
     * @throws \Throwable
     */
    protected static function getDOMDocument(string $view, $data = [])
    {
        $data['__id'] = uniqid();
        $content = view($view, $data)->render();

        $dom = new DOMDocument();

        libxml_use_internal_errors(true);

        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $content);

        libxml_use_internal_errors(false);

        $null = new DOMElement('null');

        return [
            $dom->getElementsByTagName('head')->item(0) ?: $null,
            $dom->getElementsByTagName('body')->item(0) ?: $null,
        ];
    }

    /**
     * @param DOMElement $element
     * @return void
     */
    protected static function resolve(DOMElement $element)
    {
        $method = 'resolve' . ucfirst($element->tagName);

        return static::{$method}($element);
    }

    /**
     * @param DOMElement $element
     * @return void
     */
    protected static function resolveScript(DOMElement $element)
    {
        if ($element->hasAttribute('src')) {
            static::js(admin_asset($element->getAttribute('src')));
        } elseif (!empty(trim($element->nodeValue))) {
            if ($require = $element->getAttribute('require')) {
                admin_assets(explode(',', $require));
            }

            static::script(';(function () {' . $element->nodeValue . '})();');
        }
    }

    /**
     * @param DOMElement $element
     * @return void
     */
    protected static function resolveStyle(DOMElement $element)
    {
        if (!empty(trim($element->nodeValue))) {
            static::style($element->nodeValue);
        }
    }

    /**
     * @param DOMElement $element
     * @return void
     */
    protected static function resolveLink(DOMElement $element)
    {
        if ($element->getAttribute('rel') == 'stylesheet' && $href = $element->getAttribute('href')) {
            static::css(admin_asset($href));
        }
    }

    /**
     * @param DOMElement $element
     * @return void
     */
    protected static function resolveTemplate(DOMElement $element)
    {
        $html = '';
        foreach ($element->childNodes as $childNode) {
            $html .= $element->ownerDocument->saveHTML($childNode);
        }

        $html && static::html($html);
    }
}
