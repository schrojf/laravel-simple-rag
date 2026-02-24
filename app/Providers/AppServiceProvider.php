<?php

namespace App\Providers;

use App\Support\Icons;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Passport::authorizationView(function ($parameters) {
            return view('mcp.authorize', $parameters);
        });

        Blade::directive('icon', function (string $expression): string {
            return "<?php echo \App\Support\Icons::render({$expression}); ?>";
        });

        foreach ([
            // Content
            'document', 'document-text', 'folder', 'folder-open', 'photo', 'video-camera', 'code-bracket',
            // Organisation
            'tag', 'bookmark', 'star', 'link', 'information-circle',
            // Actions
            'pencil', 'pencil-square', 'trash', 'plus', 'plus-circle', 'x-mark', 'magnifying-glass', 'check', 'check-circle',
            // Navigation
            'arrow-right', 'arrow-left', 'chevron-right', 'chevron-down',
        ] as $icon) {
            Icons::register($icon, source: 'public', inline: true);
        }
    }
}
