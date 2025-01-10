<?php

namespace App\Policies;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policies\Basic;

class CspPolicy extends Basic
{
    public function configure(): void
    {
        // parent::configure();

        $this
            ->addDirective(Directive::BASE, Keyword::SELF)
            ->addDirective(Directive::DEFAULT, Keyword::SELF)
            ->addDirective(Directive::FORM_ACTION, Keyword::SELF)
            ->addDirective(Directive::MEDIA, Keyword::SELF)
            ->addDirective(Directive::OBJECT, Keyword::NONE)
            // Allow WebSocket connections for Vite
            ->addDirective(Directive::CONNECT, [Keyword::SELF,'wss://transport.test:5173'])
            // Allow images from any source
            ->addDirective(Directive::IMG, [Keyword::SELF,'*'])
            // Allow Vite scripts from local domain
            ->addDirective(Directive::SCRIPT, [Keyword::SELF,'https://transport.test:5173'])
            // Allow styles from external fonts
            ->addDirective(Directive::STYLE, [Keyword::SELF,'https://fonts.bunny.net'])
            // Allow external fonts
            ->addDirective(Directive::FONT, 'https://fonts.bunny.net');

        if (app()->isLocal()) {
            $this
                ->addDirective(Directive::SCRIPT, Keyword::UNSAFE_INLINE)
                ->addDirective(Directive::STYLE, Keyword::UNSAFE_INLINE);
        } else {
            $this
                // Add nonces for scripts and styles
                ->addNonceForDirective(Directive::SCRIPT)
                ->addNonceForDirective(Directive::STYLE);
        }
    }
}
