<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Symfony\Component\HttpFoundation\Cookie;
use Illuminate\Contracts\Support\Responsable;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    /**
     * Add the CSRF token to the response cookies.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function addCookieToResponse($request, $response)
    {
        $config = config('session');

        if ($response instanceof Responsable) {
            $response = $response->toResponse($request);
        }

        $response->headers->setCookie(
            new Cookie(
                'ppdb-XSRF-TOKEN', // Custom cookie name matching Axios config in bootstrap.js
                $request->session()->token(),
                $this->availableAt(60 * $config['lifetime']),
                $config['path'],
                $config['domain'],
                $config['secure'],
                false, // httpOnly
                false, // raw
                $config['same_site'] ?? null
            )
        );

        return $response;
    }
}
