<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\OutboundMailLimit;
use App\Speech\AudioEncoder;
use App\Speech\Mp3Encoder;
use Carbon\CarbonImmutable;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AudioEncoder::class, Mp3Encoder::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->configureOutboundMailLimit();
    }

    /**
     * Queued bulk mail shares one provider allowance with the inline sign-in
     * code mail, so it is throttled below the provider's real cap to leave the
     * sign-in path room to send. See OutboundMailLimit.
     */
    protected function configureOutboundMailLimit(): void
    {
        RateLimiter::for(
            OutboundMailLimit::RATE_LIMITER,
            fn (): Limit => Limit::perMinutes(
                OutboundMailLimit::WINDOW_MINUTES,
                OutboundMailLimit::bulkAllowance(),
            )->by('global'),
        );
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );
    }
}
