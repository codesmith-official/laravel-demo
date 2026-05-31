<?php

namespace App\Services;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class MailService
{
    public function send(string $to, Mailable $mailable): bool
    {
        if (! $this->isConfigured()) {
            Log::info('Email skipped because mail is not configured.', [
                'to' => $to,
                'subject' => method_exists($mailable, 'envelope') ? $mailable->envelope()->subject : null,
                'mailer' => config('mail.default'),
            ]);

            return false;
        }

        try {
            Mail::to($to)->send($mailable);

            return true;
        } catch (Throwable $exception) {
            Log::warning('Email delivery failed and was skipped.', [
                'to' => $to,
                'mailer' => config('mail.default'),
                'error' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    public function isConfigured(): bool
    {
        $mailer = config('mail.default');

        if (in_array($mailer, ['log', 'array'], true)) {
            return true;
        }

        if ($mailer !== 'smtp') {
            return filled(config("mail.mailers.{$mailer}.transport"));
        }

        $host = config('mail.mailers.smtp.host');
        $port = config('mail.mailers.smtp.port');
        $from = config('mail.from.address');

        if (! filled($host) || ! filled($port) || ! filled($from)) {
            return false;
        }

        $placeholders = ['hello@example.com', 'null'];

        if (in_array((string) $from, $placeholders, true)) {
            return false;
        }

        return ! ($host === '127.0.0.1' && (int) $port === 2525 && ! filled(config('mail.mailers.smtp.username')));
    }
}
