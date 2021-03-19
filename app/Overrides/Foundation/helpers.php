<?php

use Illuminate\Contracts\Routing\UrlGenerator;

if (! function_exists('url')) {
    /**
     * Generate a url for the application.
     *
     * @param  string|null  $path
     * @param  mixed  $parameters
     * @param  bool|null  $secure
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    function url($path = null, $parameters = [], $secure = null)
    {
        if (is_null($path)) {
            return app(UrlGenerator::class);
        }

        return app(UrlGenerator::class)->to(
            (! empty(config('app.directory_path')) ? config('app.directory_path') . '/' : ''). $path,
            $parameters, $secure);
    }
}
