<?php

declare(strict_types=1);

use App\Actions\Auth\SendEmailCode;
use App\Enums\EmailCodePurpose;
use App\Enums\LessonStage;
use App\Enums\Skill;
use App\Models\User;
use App\Notifications\DailyDigestNotification;
use App\Notifications\DueReviewsReminder;
use App\Notifications\EmailCodeNotification;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Mime\Email;

beforeEach(function (): void {
    config(['app.supported_locales' => ['en', 'nl']]);
});

function sentMail(): Email
{
    $messages = Mail::mailer('array')->getSymfonyTransport()->messages();

    $original = $messages->last()->getOriginalMessage();
    assert($original instanceof Email);

    return $original;
}

function renderedIn(string $locale, Closure $callback): mixed
{
    $previous = App::getLocale();
    App::setLocale($locale);

    try {
        return $callback();
    } finally {
        App::setLocale($previous);
    }
}

it('sends the sign-in code mail in the recipient locale with the same layout', function (string $locale, array $expected): void {
    $user = User::factory()->create(['name' => 'Sam', 'interface_locale' => $locale]);

    app(SendEmailCode::class)->handle($user, EmailCodePurpose::Login);

    $mail = sentMail();
    $html = (string) $mail->getHtmlBody();
    $text = (string) $mail->getTextBody();

    expect($mail->getSubject())->toBe($expected['subject']);

    foreach ($expected['lines'] as $line) {
        expect($html)->toContain($line)->and($text)->toContain($line);
    }

    expect($html)->toContain('<table class="wrapper"')
        ->and($html)->toContain('<a href="'.config('app.url').'"')
        ->and($html)->toContain('class="footer"');
})->with([
    'english' => ['en', [
        'subject' => 'Your Hablas sign-in code',
        'lines' => ['Hi Sam,', 'Use this code to sign in to Hablas.', 'Your code is:', 'It expires in 10 minutes and can only be used once.', "If you didn't request this, you can safely ignore this email.", 'Regards,', 'All rights reserved.'],
    ]],
    'dutch' => ['nl', [
        'subject' => 'Je inlogcode voor Hablas',
        'lines' => ['Hoi Sam,', 'Gebruik deze code om in te loggen bij Hablas.', 'Je code is:', 'De code verloopt over 10 minuten en kan maar één keer worden gebruikt.', 'Heb je dit niet aangevraagd? Dan kun je deze e-mail gerust negeren.', 'Met vriendelijke groet,', 'Alle rechten voorbehouden.'],
    ]],
]);

it('renders the confirm purpose subject in Dutch', function (): void {
    $user = User::factory()->create(['interface_locale' => 'nl']);

    app(SendEmailCode::class)->handle($user, EmailCodePurpose::Confirm);

    expect(sentMail()->getSubject())->toBe('Bevestig dat jij het bent op Hablas')
        ->and((string) sentMail()->getTextBody())->toContain('Gebruik deze code om deze actie te bevestigen.');
});

it('translates the action button hint under the mail button', function (): void {
    $user = User::factory()->create(['interface_locale' => 'nl']);

    $user->notify(new DailyDigestNotification('Spanish', 1, 1, false));

    expect((string) sentMail()->getTextBody())->toContain('Lukt het klikken op de knop "Open Hablas" niet?');
});

it('keeps the sign-in code mail in English for a user with no stored locale', function (): void {
    $user = User::factory()->create(['interface_locale' => null]);

    app(SendEmailCode::class)->handle($user, EmailCodePurpose::Login);

    expect(sentMail()->getSubject())->toBe('Your Hablas sign-in code');
});

it('uses the request locale for the inline sign-in code mail when the user has none stored', function (): void {
    $user = User::factory()->create(['interface_locale' => null]);

    App::setLocale('nl');
    app(SendEmailCode::class)->handle($user, EmailCodePurpose::Login);

    expect(sentMail()->getSubject())->toBe('Je inlogcode voor Hablas');
});

it('queues the daily digest and renders it in the learner locale', function (): void {
    $user = User::factory()->create(['name' => 'Sam', 'interface_locale' => 'nl']);

    Notification::send($user, new DailyDigestNotification('Spanish', 3, 5, true));

    $mail = sentMail();

    expect($mail->getSubject())->toBe('Je leeroverzicht voor Spanish');

    foreach (['Hoi Sam,', 'Je hebt 3 herhaalkaarten open staan.', 'Huidige reeks: 5 dagen.', 'Je hebt de reflectie van deze week nog niet ingediend.'] as $line) {
        expect((string) $mail->getHtmlBody())->toContain($line);
    }
});

it('passes the learner locale to the notification sender', function (): void {
    Notification::fake();
    $dutch = User::factory()->create(['interface_locale' => 'nl']);

    $dutch->notify(new DailyDigestNotification('Spanish', 1, 1, false));

    Notification::assertSentTo($dutch, DailyDigestNotification::class, fn ($notification, $channels, $notifiable, $locale): bool => $locale === 'nl');
});

it('renders the digest mail in English byte for byte', function (int $due, int $streak, string $intro): void {
    $user = User::factory()->create(['name' => 'Sam', 'interface_locale' => 'en']);

    $mail = (new DailyDigestNotification('Spanish', $due, $streak, true))->toMail($user);

    expect($mail->subject)->toBe('Your Spanish learning digest')
        ->and($mail->greeting)->toBe('Hi Sam,')
        ->and(implode('|', $mail->introLines))->toBe($intro)
        ->and($mail->actionText)->toBe('Open Hablas');
})->with([
    'one of each' => [1, 1, "You have 1 review card due.|Current streak: 1 day.|You haven't submitted this week's reflection yet."],
    'many' => [12, 30, "You have 12 review cards due.|Current streak: 30 days.|You haven't submitted this week's reflection yet."],
    'none due' => [0, 0, "Current streak: 0 days.|You haven't submitted this week's reflection yet."],
]);

it('renders the digest push body in both locales with plurals', function (string $locale, int $due, int $streak, string $title, string $body): void {
    $user = User::factory()->create();

    $message = renderedIn($locale, fn () => (new DailyDigestNotification('Spanish', $due, $streak, false))->toWebPush($user));

    expect($message->toArray()['title'])->toBe($title)
        ->and($message->toArray()['body'])->toBe($body);
})->with([
    ['en', 1, 1, 'Your Spanish learning digest', '1 review card due · 1 day streak'],
    ['en', 3, 0, 'Your Spanish learning digest', '3 review cards due · 0 days streak'],
    ['en', 0, 4, 'Your Spanish learning digest', '4 days streak'],
    ['nl', 1, 1, 'Je leeroverzicht voor Spanish', '1 herhaalkaart open · reeks van 1 dag'],
    ['nl', 3, 2, 'Je leeroverzicht voor Spanish', '3 herhaalkaarten open · reeks van 2 dagen'],
    ['nl', 0, 4, 'Je leeroverzicht voor Spanish', 'Reeks van 4 dagen'],
]);

it('renders the due reviews reminder push in both locales', function (string $locale, int $count, string $title, string $body): void {
    $user = User::factory()->create();

    $message = renderedIn($locale, fn () => (new DueReviewsReminder('Spanish', $count))->toWebPush($user));

    expect($message->toArray()['title'])->toBe($title)
        ->and($message->toArray()['body'])->toBe($body);
})->with([
    ['en', 12, 'Reviews are due', '12 Spanish cards are ready to review'],
    ['en', 1, 'Reviews are due', '1 Spanish cards are ready to review'],
    ['nl', 12, 'Herhalingen staan klaar', 'Er staan 12 kaarten klaar om te herhalen (Spanish).'],
    ['nl', 1, 'Herhalingen staan klaar', 'Er staat 1 kaart klaar om te herhalen (Spanish).'],
]);

it('renders the sign-in mail subject for both purposes in English', function (EmailCodePurpose $purpose, string $subject): void {
    $user = User::factory()->create();

    $mail = (new EmailCodeNotification('123456', $purpose, 10))->toMail($user);

    expect($mail->subject)->toBe($subject);
})->with([
    [EmailCodePurpose::Login, 'Your Hablas sign-in code'],
    [EmailCodePurpose::Confirm, "Confirm it's you on Hablas"],
]);

it('words the sign-in request flash in the visitor locale', function (?string $cookie, string $status): void {
    User::factory()->create(['email' => 'sam@example.com']);

    $request = $cookie === null ? $this : $this->withUnencryptedCookie('interface_locale', $cookie);

    $request->from('/login')
        ->post(route('login.code.store'), ['email' => 'sam@example.com'])
        ->assertSessionHas('status', $status);
})->with([
    'dutch' => ['nl', 'Als dat e-mailadres bij een account hoort, hebben we er een inlogcode naartoe gestuurd.'],
    'english' => [null, "If that email has an account, we've sent it a sign-in code."],
]);

it('titles the lesson stages in the learner locale but stores English', function (): void {
    expect(LessonStage::Meet->title())->toBe('Meet the words');

    App::setLocale('nl');

    expect(LessonStage::Meet->label())->toBe('Maak kennis met de woorden')
        ->and(LessonStage::Check->label())->toBe('Eenheidstoets')
        ->and(LessonStage::Meet->title())->toBe('Meet the words')
        ->and(App::getLocale())->toBe('nl');
});

it('labels each skill in Dutch', function (): void {
    App::setLocale('nl');

    expect(array_map(fn (Skill $skill): string => $skill->label(), Skill::cases()))->toBe(['lezen', 'luisteren', 'spreken', 'schrijven']);
});

it('validates in Dutch with Dutch attribute names', function (): void {
    App::setLocale('nl');

    $validator = Validator::make(['email' => 'nope', 'code' => ''], ['email' => ['email'], 'code' => ['required']]);

    expect($validator->errors()->first('email'))->toBe('E-mailadres moet een geldig e-mailadres zijn.')
        ->and($validator->errors()->first('code'))->toBe('Code is verplicht.');
});

it('keeps English validation messages unchanged', function (): void {
    $validator = Validator::make(['email' => 'nope'], ['email' => ['email']]);

    expect($validator->errors()->first('email'))->toBe('The email field must be a valid email address.');
});

it('has the same top-level keys in every Dutch framework language file as the English one', function (string $file): void {
    $english = require base_path("vendor/laravel/framework/src/Illuminate/Translation/lang/en/{$file}.php");
    $dutch = require lang_path("nl/{$file}.php");

    $keys = fn (array $lines): array => array_keys($lines);

    expect($keys($dutch))->toEqualCanonicalizing($keys($english));
})->with(['auth', 'passwords', 'pagination', 'validation']);

it('uses Dutch month names in the placement retake message', function (): void {
    App::setLocale('nl');

    expect(CarbonImmutable::parse('2026-10-05')->translatedFormat('j F'))->toBe('5 oktober');
});
