<?php

namespace Encore\Admin\Middleware;

use Closure;
use DOMDocument;
use Encore\Admin\Facades\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Symfony\Component\HttpFoundation\Response;

class Pjax
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return Response
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (!$request->pjax() || $response->isRedirection() || Admin::guard()->guest()) {
            return $response;
        }

        if (!$response->isSuccessful()) {
            return $this->handleErrorResponse($response);
        }

        try {
            $this->filterResponse($response, $request->header('X-PJAX-CONTAINER'))
                ->setUriHeader($response, $request);
        } catch (\Exception $exception) {
        }

        return $response;
    }

    /**
     * Send a response through this middleware.
     *
     * @param Response $response
     */
    public static function respond(Response $response)
    {
        $next = function () use ($response) {
            return $response;
        };

        (new static())->handle(Request::capture(), $next)->send();

        exit;
    }

    /**
     * Handle Response with exceptions.
     *
     * @param Response $response
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function handleErrorResponse(Response $response)
    {
        $exception = $response->exception;

        $error = new MessageBag([
            'type'    => get_class($exception),
            'message' => $exception->getMessage(),
            'file'    => $exception->getFile(),
            'line'    => $exception->getLine(),
        ]);

        return back()->withInput()->withErrors($error, 'exception');
    }

    /**
     * Prepare the PJAX-specific response content.
     *
     * @param Response $response
     * @param string   $container
     *
     * @return $this
     */
    protected function filterResponse(Response $response, $container)
    {
        $dom = new DOMDocument();
        $dom->loadHTML($response->getContent(), LIBXML_NOERROR | LIBXML_SCHEMA_CREATE);

        $response->setContent(
            $this->makeTitle($dom) .
                $this->fetchContents($dom, $container)
        );

        return $this;
    }

    /**
     * Prepare an HTML title tag.
     *
     * @param DOMDocument $dom
     *
     * @return string
     */
    protected function makeTitle($dom)
    {
        $titleElm = $dom->getElementsByTagName('title');

        //get and display what you need:
        if ($titleElm->length) {
            return "<title>{$titleElm->item(0)->nodeValue}</title>";
        }

        return '';
    }

    /**
     * Fetch the PJAX-specific HTML from the response.
     *
     * @param DOMDocument $dom
     * @param string  $container
     *
     * @return string
     */
    protected function fetchContents($dom, $container)
    {
        $element = $dom->getElementById(substr($container, 1));
        $content = '';

        foreach (($element?->childNodes ?? []) as $node) {
            $content .= $dom->saveHTML($node);
        }

        if (empty($content)) {
            abort(422);
        }

        return $content;
    }

    /**
     * Decode utf-8 characters to html entities.
     *
     * @param string $html
     *
     * @return string
     */
    protected function decodeUtf8HtmlEntities($html)
    {
        return preg_replace_callback('/(&#[0-9]+;)/', function ($html) {
            return mb_convert_encoding($html[1], 'UTF-8', 'HTML-ENTITIES');
        }, $html);
    }

    /**
     * Set the PJAX-URL header to the current uri.
     *
     * @param Response $response
     * @param Request  $request
     */
    protected function setUriHeader(Response $response, Request $request)
    {
        $response->header(
            'X-PJAX-URL',
            $request->getRequestUri()
        );
    }
}
