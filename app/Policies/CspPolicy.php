<?php

namespace App\Policies;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policies\Policy;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CspPolicy extends Policy
{
    public function configure(): void
    {
        $this
            // Base directive
            ->addDirective(Directive::BASE, Keyword::SELF)
            // Allow WebSocket connections for Vite
            ->addDirective(Directive::CONNECT, [Keyword::SELF, 'wss://transport.test:5173'])
            // Default policy for unspecified directives
            ->addDirective(Directive::DEFAULT, Keyword::SELF)
            // Form actions
            ->addDirective(Directive::FORM_ACTION, Keyword::SELF)
            // Allow images from any source and data URIs
            ->addDirective(Directive::IMG, [Keyword::SELF, '*', 'data:'])
            // Media
            ->addDirective(Directive::MEDIA, Keyword::SELF)
            // Block object tags
            ->addDirective(Directive::OBJECT, Keyword::NONE)
            // Allow scripts from self and Vite
            ->addDirective(Directive::SCRIPT, [Keyword::SELF, 'https://transport.test:5173'])
            // Allow styles from self and external fonts
            ->addDirective(Directive::STYLE, [Keyword::SELF, 'https://fonts.bunny.net', Keyword::UNSAFE_INLINE])
            // Allow external fonts
            ->addDirective(Directive::FONT, 'https://fonts.bunny.net')
            // Add nonce for scripts
            ->addNonceForDirective(Directive::SCRIPT);
    }

    public function shouldBeApplied(Request $request, Response $response): bool
    {
        if (config('app.debug') && ($response->isClientError() || $response->isServerError())) {
            return false;
        }

        return parent::shouldBeApplied($request, $response);
    }
}
