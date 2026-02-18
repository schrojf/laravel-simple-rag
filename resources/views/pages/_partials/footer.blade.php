<footer class="border-t border-zinc-200 mt-auto">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 h-10 flex items-center justify-between">
        <p class="text-xs text-zinc-400">
            {{ config('app.name') }} &middot; {{ config('app.env') }}
        </p>
        <p class="text-xs text-zinc-400 flex items-center gap-3">
            <span title="Page render time">{{ round((microtime(true) - LARAVEL_START) * 1000) }}ms</span>
            <span title="Peak memory usage">{{ round(memory_get_peak_usage(true) / 1024 / 1024, 1) }}MB</span>
            <span title="Laravel version">Laravel {{ app()->version() }}</span>
            <span title="PHP version">PHP {{ PHP_VERSION }}</span>
        </p>
    </div>
</footer>
