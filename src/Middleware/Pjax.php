<?php

namespace Encore\Admin\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Symfony\Component\DomCrawler\Crawler;

class Pjax
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (!$request->pjax() || $response->isRedirection() || Auth::guard('admin')->guest()) {
            return $response;
        }

        if (!$response->isSuccessful()) {
            return $this->handleErrorResponse($response);
        }

        $this->filterResponse($response, $request->header('X-PJAX-CONTAINER'))
            ->setUriHeader($response, $request);

        return $response;
    }

    /**
     * Send a response through this middleware.
     *
     * @param \Symfony\Component\HttpFoundation\Response $response
     */
    public static function respond(\Symfony\Component\HttpFoundation\Response $response)
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
            'type'      => get_class($exception),
            'message'   => $exception->getMessage(),
            'file'      => $exception->getFile(),
            'line'      => $exception->getLine(),
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
        $crawler = new Crawler($response->getContent());

        $response->setContent(
            $this->makeTitle($crawler).
            $this->fetchContents($crawler, $container)
        );

        return $this;
    }

    /**
     * Prepare an HTML title tag.
     *
     * @param Crawler $crawler
     *
     * @return string
     */
    protected function makeTitle($crawler)
    {
        $pageTitle = $crawler->filter('head > title')->html();

        return "<title>{$pageTitle}</title>";
    }

    /**
     * Fetch the PJAX-specific HTML from the response.
     *
     * @param Crawler $crawler
     * @param string  $container
     *
     * @return string
     */
    protected function fetchContents($crawler, $container)
    {
        $content = $crawler->filter($container);

        if (!$content->count()) {
            abort(422);
        }

        return $this->decodeUtf8HtmlEntities($content->html());
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
