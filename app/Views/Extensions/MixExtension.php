<?php

namespace App\Views\Extensions;

use Twig_Extension;
use Twig_SimpleFunction;
use Twig_Function;

class MixExtension extends Twig_Extension
{
    public function getFunctions()
    {
        return [
            new Twig_Function('mix', [$this, 'mix']),
        ];
    }

    public function mix($path, $manifestDirectory = '')
    {
        static $manifest;
        $publicFolder = '';
        $rootPath = base_path();
        $publicPath = $rootPath . $publicFolder;

        if ($manifestDirectory && ! starts_with($manifestDirectory, '/')) {
            $manifestDirectory = "/{$manifestDirectory}";
        }

        if (! $manifest) {
            if (! file_exists($manifestPath = ($rootPath . $manifestDirectory.'/public/mix-manifest.json') )) {
                throw new \Exception('The Mix manifest does not exist.');
            }
            
            $manifest = json_decode(file_get_contents($manifestPath), true);
        }

        if (! starts_with($path, '/')) {
            $path = "/{$path}";
        }

        $path = $publicFolder . $path;

        if (! array_key_exists($path, $manifest)) {
            throw new \Exception(
                "Unable to locate Mix file: {$path}. Please check your ".
                'webpack.mix.js output paths and try again.'
            );
        }
        
        return file_exists($publicPath . ($manifestDirectory.'/hot'))
            ? "http://localhost/framework/public{$manifest[$path]}"
            : $manifestDirectory.$manifest[$path];
    }
}
