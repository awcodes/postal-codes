<?php

declare(strict_types=1);

use Awcodes\Focus\Card;
use Awcodes\Focus\Enums\Size;
use Awcodes\Focus\ScreenshotSuite;

/*
 * Share cards for Postal Codes, generated with awcodes/focus. The package has no UI
 * to screenshot, so the card uses a screenshot-free template filled from
 * composer.json. Regenerate with `composer focus -- --cards-only`.
 */

return ScreenshotSuite::make()
    ->cardTemplates('https://github.com/awcodes/focus-templates/tree/v2.1.0/dist')
    ->cards([
        // Open Graph and the GitHub social preview share one 2400x1260 template; GitHub crops 30px top and bottom.
        Card::make('social')
            ->template('default-wide')
            ->title('Postal Codes')
            ->sizes([Size::OpenGraph, Size::GitHubSocial]),

        // Unbranded 16:9 image for aw.codes, which adds its own heading: a usage snippet and the install
        // command. code-plain shows up to 7 lines.
        Card::make('plain')
            ->template('code-plain')
            ->with(['code' => <<<'CODE'
                use Awcodes\PostalCodes\Models\PostalCode;

                $code = PostalCode::query()
                    ->where('postal_code', '90210')
                    ->first();

                $code->place_name; // Beverly Hills
                CODE])
            ->sizes([[2560, 1440]])
            ->scale(1),
    ]);
