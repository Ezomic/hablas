<?php

namespace App\Providers;

use App\Listeners\UnlockSpanishForNewUser;
use App\Services\OutboundMailLimit;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Events\Registered;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

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
        $this->configureDefaults();
        $this->configureOutboundMailLimit();

        Event::listen(Registered::class, UnlockSpanishForNewUser::class);
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
