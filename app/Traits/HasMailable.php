<?php

namespace App\Traits;

use App\Mail\DynamicMail;
use App\Mail\DynamicMailQueued;
use App\Models\MailSmsTemplate;
use Illuminate\Support\Facades\Mail;

trait HasMailable
{
    protected mixed $cc;
    protected mixed $bcc;

    public function __construct()
    {
        $this->cc = null;
        $this->bcc = null;
    }

    public function mail(MailSmsTemplate|string|int $template, array $replaceable): static
    {
        $mailTemplate = new MailSmsTemplate();
        if (is_integer($template)) {
            $mailTemplate = MailSmsTemplate::where('id', $template)->first();
        } elseif (is_string($template)) {
            $mailTemplate = MailSmsTemplate::where('code', $template)->first();
        } elseif (gettype($template) == MailSmsTemplate::class) {
            $mailTemplate = $template;
        }
        $mailBody = $mailTemplate->content;
        $mailSubject = $mailTemplate->subject;

        // Replace User Defined Variables
        foreach (array_keys($replaceable) as $key) {
            $mailBody = str_replace($key, $replaceable[$key], $mailBody);
            $mailSubject = str_replace($key, $replaceable[$key], $mailSubject);
        }

        // Replace default Variables
        $mailBody = str_replace('{nl}', '<br>', $mailBody);
        $mailBody = str_replace('{br}', '<br>', $mailBody);
        $mailBody = str_replace('{app.name}', getSetting('app_name'), $mailBody);
        $mailBody = str_replace('{app.url}', url('/'), $mailBody);
        // Replace variables from Subject
        $mailSubject = str_replace('{app.name}', getSetting('app_name'), $mailSubject);
        $mailSubject = str_replace('{app.url}', url('/'), $mailSubject);

        // Send the mail to provided user
        // With provided mail template
        $mail = Mail::to($this->email)
            ->cc($this->cc)
            ->bcc($this->bcc);

        if ((int)getSetting('mail_queue_enabled')) {
            $mail->send(new DynamicMailQueued($mailSubject, $mailBody));
        } else {
            $mail->send(new DynamicMail($mailSubject, $mailBody));
        }

        return $this;
    }

    public function cc(mixed $users): static
    {
        $this->cc = $users;
        return $this;
    }


    public function bcc(mixed $users): static
    {
        $this->bcc = $users;
        return $this;
    }
}
