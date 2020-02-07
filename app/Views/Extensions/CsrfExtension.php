<?php

namespace App\Views\Extensions;

use App\Security\Csrf;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

class CsrfExtension extends AbstractExtension
{
    protected $csrf;

    public function __construct(Csrf $csrf)
    {
        $this->csrf = $csrf;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'csrf';
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('csrf', [$this, 'csrf']),
        ];
    }

    public function csrf()
    {
        return '
            <input type="hidden" name="' . $this->csrf->key() . '" value="' . $this->csrf->token() . '">
        ';
    }
}
