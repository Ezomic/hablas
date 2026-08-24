<?php

namespace App\Services;

/**
 * The shared mail provider caps noreply@thijssensoftware.nl at ten messages
 * per five minutes, and that cap is shared by every app on the droplet sending
 * from that address. Exceeding it earns a hard 554 rejection:
 *
 *   554 5.7.1 <END-OF-MESSAGE>: End-of-data rejected: 10 emails per 5 minutes
 *
 * Bulk mail is throttled below the real cap on purpose. The remainder is
 * headroom for EmailCodeNotification, which is deliberately not queued and
 * sends inline during sign-in: if a digest run were allowed to consume the
 * whole allowance, a user trying to sign in during that window would be
 * rejected by the provider and simply could not get in.
 */
final class OutboundMailLimit
{
    /**
     * The provider's actual ceiling, recorded here so the reserve below is
     * obviously a deliberate margin rather than an arbitrary number.
     */
    public const PROVIDER_CAP = 10;

    public const WINDOW_MINUTES = 5;

    /**
     * Left free for sign-in codes and anything else that sends inline and
     * cannot simply be retried later.
     */
    public const INTERACTIVE_RESERVE = 2;

    public const RATE_LIMITER = 'outbound-bulk-mail';

    public static function bulkAllowance(): int
    {
        return self::PROVIDER_CAP - self::INTERACTIVE_RESERVE;
    }
}
