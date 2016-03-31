<?php

namespace Encore\Admin\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\DomCrawler\Crawler;

class PjaxMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param  Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (! $request->pjax() || $response->isRedirection()) {
            return $response;
        }

        $this->filterResponse($response, $request->header('X-PJAX-CONTAINER'))
            ->setUriHeader($response, $request);

        return $response;
    }

    /**
     * Prepare the PJAX-specific response content.
     *
     * @param  Response $response
     * @param  string   $container
     * @return $this
     */
    protected function filterResponse(Response $response, $container)
    {
        $crawler = new Crawler($response->getContent());

        $response->setContent(
            $this->makeTitle($crawler) .
            $this->fetchContents($crawler, $container)
        );

        return $this;
    }

    /**
     * Prepare an HTML title tag.
     *
     * @param  Crawler $crawler
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
     * @param  Crawler $crawler
     * @param  string  $container
     * @return string
     */
    protected function fetchContents($crawler, $container)
    {
        $content = $crawler->filter($container);

        if (! $content->count()) {
            abort(422);
        }

        return $content->html();
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
            'X-PJAX-URL', $request->getRequestUri()
        );
    }
}