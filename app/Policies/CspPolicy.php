<?php

namespace App\Policies;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policies\Basic;

class CspPolicy extends Basic
{
    public function configure(): void
    {
        parent::configure();

        $this
            // Allow WebSocket connections for Vite
            ->addDirective(Directive::CONNECT, [Keyword::SELF, 'wss://transport.test:5173'])
            // Allow images from any source
            ->addDirective(Directive::IMG, '*')
            ->addDirective(Directive::SCRIPT, [Keyword::SELF, 'https://transport.test:5173'])
            // Allow styles from external fonts
            ->addDirective(Directive::STYLE, [Keyword::SELF, 'https://fonts.bunny.net'])
            // Allow external fonts
            ->addDirective(Directive::FONT, 'https://fonts.bunny.net')
            // Allow inline styles
            ->addDirective(Directive::STYLE, Keyword::UNSAFE_INLINE);
    }
}
