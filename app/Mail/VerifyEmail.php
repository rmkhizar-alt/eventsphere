<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $otp;

    public function __construct($user, $otp)
    {
        $this->user = $user;
        $this->otp = $otp;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your EventSphere OTP Code',
        );
    }

    public function content(): Content
    {
        return new Content(
            htmlString: $this->buildHtml(),
        );
    }

    public function attachments(): array
    {
        return [];
    }

    private function buildHtml(): string
    {
        $name = e($this->user->name);
        $otp = e($this->otp);

        return <<<HTML
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="margin:0;padding:0;background:#f6f4ee;font-family:'Helvetica Neue',Arial,sans-serif;">
<div style="max-width:520px;margin:40px auto;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.06);">

  <div style="background:linear-gradient(140deg,#16132a,#2b2358 55%,#433a86);padding:40px 36px;text-align:center;">
    <h1 style="color:#f6f4ee;font-size:24px;margin:0 0 6px;">EventSphere</h1>
    <p style="color:#b9b3d6;font-size:12px;margin:0;letter-spacing:1px;text-transform:uppercase;">Email Verification</p>
  </div>

  <div style="padding:36px;">
    <p style="color:#252230;font-size:15px;margin:0 0 8px;">Hi <strong>{$name}</strong>,</p>
    <p style="color:#66665c;font-size:14px;margin:0 0 28px;">Use the OTP below to verify your account:</p>

    <div style="text-align:center;margin:0 0 28px;">
      <div style="display:inline-block;background:#f5f3ff;border:2px solid #433a86;border-radius:12px;padding:16px 40px;">
        <span style="font-size:32px;font-weight:700;letter-spacing:8px;color:#433a86;font-family:'Courier New',monospace;">{$otp}</span>
      </div>
    </div>

    <p style="color:#888;font-size:12px;margin:0 0 24px;text-align:center;">This code expires in <strong>15 minutes</strong>.</p>

    <hr style="border:none;border-top:1px solid #e8e6dc;margin:0 0 24px;">

    <p style="color:#888;font-size:12px;margin:0;text-align:center;">If you didn't create an account, ignore this email.</p>
  </div>

  <div style="background:#e8e6dc;padding:16px 36px;text-align:center;">
    <p style="color:#6b6b5c;font-size:11px;margin:0;">© 2026 EventSphere · Campus Event System</p>
  </div>

</div>
</body>
</html>
HTML;
    }
}
