<?php

use Illuminate\Support\Str;
use Illuminate\Support\Optional;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Laminas\Diactoros\Response\RedirectResponse;

if (!function_exists('array_only')) {
    function array_only($array, $keys) {
        return array_intersect_key($array, array_flip($keys));
    }
}

if (!function_exists('get_mime_type')) {
    function get_mime_type($file) {
        $mtype = false;
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mtype = finfo_file($finfo, $file);
            finfo_close($finfo);
        } elseif (function_exists('mime_content_type')) {
            $mtype = mime_content_type($file);
        } 
        return $mtype;
    }
}

if (!function_exists('redirect')) {
    function redirect($path) {
        return new RedirectResponse($path);
    }
}

if (!function_exists('base_path')) {
    function base_path($path = '') {
        return __DIR__ . '/..//' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('uploads_path')) {
    function uploads_path($path = '') {
        return base_path('public/uploads/' . $path);
    }
}

if (!function_exists('env')) {
    function env($key, $default = null) {
        $value = getenv($key);

        if ($value === false) {
            return $default;
        }

        switch (strtolower($value)) {
            case $value === 'true';
                return true;
            case $value === 'false';
                return false;
            default:
                return $value;
        }
    }
}

if (!function_exists('str_slug')) {
     /**
      * Generate a URL friendly "slug" from a given string.
      *
      * @param  string  $title
      * @param  string  $separator
      * @return string
      */
     function str_slug($title, $separator = '-')
     {
         return Str::slug($title, $separator);
     }
}

if (! function_exists('str_random')) {
    /**
     * Generate a more truly "random" alpha-numeric string.
     *
     * @param  int  $length
     * @return string
     *
     * @throws \RuntimeException
     *
     * @deprecated Str::random() should be used directly instead. Will be removed in Laravel 5.9.
     */
    function str_random($length = 16)
    {
        return Str::random($length);
    }
}

if (! function_exists('optional')) {
    /**
     * Provide access to optional objects.
     *
     * @param  mixed  $value
     * @param  callable|null  $callback
     * @return mixed
     */
    function optional($value = null, callable $callback = null)
    {
        if (is_null($callback)) {
            return new Optional($value);
        } elseif (! is_null($value)) {
            return $callback($value);
        }
    }
}

if (!function_exists('get_file_extension')) {
    function get_file_extension($file_name) {
      return substr(strrchr($file_name,'.'),1);
  }
}

if (!function_exists('paginate_collection')) {
  /**
   * Gera paginação de resultados para uma Collection do Eloquent
   */
  function paginate_collection($items, $perPage = 10, $page = null, $options = [])
    {
        $pageName = 'page';
        $page     = $page ?: (Paginator::resolveCurrentPage($pageName) ?: 1);
        $items    = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator(
            $items->forPage($page, $perPage)->values(),
            $items->count(),
            $perPage,
            $page,
            [
                'path'     => Paginator::resolveCurrentPath(),
                'pageName' => $pageName,
            ]
        );
    }
}

if (! function_exists('starts_with')) {
    /**
     * Determine if a given string starts with a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
     * @return bool
     *
     * @deprecated Str::startsWith() should be used directly instead. Will be removed in Laravel 6.0.
     */
    function starts_with($haystack, $needles)
    {
        return Str::startsWith($haystack, $needles);
    }
}
