<?php

declare(strict_types=1);

use Tests\Support\LocaleCatalogs;

it('has no literal user-facing text outside __() in the converted PHP paths', function (): void {
    $offences = [];

    foreach (LocaleCatalogs::serverTextFiles() as $path) {
        $source = (string) file_get_contents($path);

        foreach (LocaleCatalogs::rawTextPatterns() as $what => $pattern) {
            if (preg_match($pattern, $source, $match) === 1) {
                $offences[] = str_replace(base_path().'/', '', $path)." has {$what} not wrapped in __(): {$match[0]}";
            }
        }
    }

    expect($offences)->toBe([]);
});

it('catches each kind of raw string the guard exists for', function (string $line, string $kind): void {
    expect(preg_match(LocaleCatalogs::rawTextPatterns()[$kind], $line))->toBe(1);
})->with([
    ["->subject('Hello')", 'a notification line'],
    ['->line("Hello")', 'a notification line'],
    ["->greeting('Hi')", 'a notification line'],
    ["->title('Reviews')", 'a notification line'],
    ["ValidationException::withMessages(['lesson' => ['Nope.']])", 'a validation message'],
    ["ValidationException::withMessages(['lesson' => 'Nope.'])", 'a validation message'],
    ["\$this->refuse('Nope.')", 'a refusal'],
    ["'response.required' => 'Select one.'", 'a custom request message'],
    ["abort(403, 'Not yours.')", 'an abort message'],
    ["abort_if(\$unit === null, 404, 'Gone.')", 'an abort message'],
    ['abort_unless($ok, 403, "Nope.")', 'an abort message'],
    ["Inertia::flash('toast', 'Saved.')", 'a flashed message'],
    ["self::Meet => 'Meet the words.'", 'a sentence in a match arm or array'],
    ["'note' => 'That is a different word.'", 'a sentence in a match arm or array'],
    ["back()->with('status', 'Sent.')", 'a status or toast message'],
    ["['type' => 'success', 'message' => 'Saved.']", 'a status or toast message'],
]);

it('does not flag text that goes through __()', function (string $line): void {
    foreach (LocaleCatalogs::rawTextPatterns() as $pattern) {
        expect(preg_match($pattern, $line))->toBe(0);
    }
})->with([
    'abort(403)',
    'abort_if($unit === null, 404)',
    "Inertia::flash('toast', \$milestone)",
    "self::Meet => __('Meet the words.')",
    "'status' => 'queued'",
    "->subject(__('Hello'))",
    '->line(__("Hello"))',
    "\$this->refuse(__('Nope.'))",
    "ValidationException::withMessages(['lesson' => [__('Nope.')]])",
    "'response.required' => __('Select one.')",
    "back()->with('status', __('Sent.'))",
    "['type' => 'success', 'message' => __('Saved.')]",
]);
