<?php

namespace App\Mail;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\RawMessage;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;

class CustomMailer extends AbstractTransport
{
    protected $baseUrl;
    protected $username;
    protected $password;
    protected $appName;

    public function __construct(string $baseUrl, string $username, string $password, ?EventDispatcherInterface $dispatcher = null, ?LoggerInterface $logger = null)
    {
        parent::__construct($dispatcher, $logger);
        $this->baseUrl = $baseUrl;
        $this->username = $username;
        $this->password = $password;
        $this->appName = config('app.name', 'Inventory Management System');
    }

    protected function doSend(SentMessage $sentMessage): void
    {
        $message = $sentMessage->getOriginalMessage();

        if (!$message instanceof Email) {
            throw new \RuntimeException('Expected Symfony\\Component\\Mime\\Email instance.');
        }

        $to = collect($message->getTo())->map(fn ($address) => $address->getAddress())->first();
        $from = collect($message->getFrom())->map(fn ($address) => $address->getAddress())->first();

        // Convert HTML to plain text
        $textContent = strip_tags($message->getHtmlBody() ?? $message->getTextBody());
        
        // Extract the reset link from the HTML
        preg_match('/http:\/\/[^\s<>"]+/', $textContent, $matches);
        $resetLink = $matches[0] ?? '';

        // Create a professional message
        $simpleMessage = "Password Reset Request\n\n" .
            "Dear User,\n\n" .
            "You are receiving this email because we received a password reset request for your account.\n\n" .
            "Reset Password Link: " . $resetLink . "\n\n" .
            "This password reset link will expire in 60 minutes.\n\n" .
            "If you did not request a password reset, please ignore this email or contact support if you have concerns.\n\n" .
            "Best regards,\n" .
            $this->appName . " Team\n" .
            "Email: " . $from . "\n" .
            "This is an automated message, please do not reply directly to this email.";

        $requestData = [
            'to' => $to,
            'subject' => $message->getSubject(),
            'text' => $simpleMessage
        ];

        Log::info('Attempting to send email via Java service', [
            'url' => $this->baseUrl . '/api/email/send',
            'request_data' => $requestData
        ]);

        try {
            $response = Http::withBasicAuth($this->username, $this->password)
                ->withHeaders([
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Accept' => 'application/json',
                    'X-Mailer' => $this->appName,
                    'X-Priority' => '1',
                    'X-MSMail-Priority' => 'High',
                    'Importance' => 'High'
                ])
                ->asForm()
                ->post($this->baseUrl . '/api/email/send', $requestData);

            $responseBody = $response->body();
            $responseStatus = $response->status();

            Log::info('Java service response', [
                'status' => $responseStatus,
                'headers' => $response->headers(),
                'body' => $responseBody
            ]);

            if ($responseStatus !== 200) {
                $errorMessage = is_string($responseBody) ? $responseBody : json_encode($responseBody);
                Log::error('Failed to send email', [
                    'status' => $responseStatus,
                    'body' => $errorMessage,
                    'request_data' => $requestData,
                    'headers' => $response->headers()
                ]);
                throw new \Exception('Failed to send email: ' . $errorMessage);
            }

            Log::info('Email sent successfully', [
                'to' => $to,
                'subject' => $message->getSubject(),
                'response' => $responseBody
            ]);
        } catch (\Exception $e) {
            Log::error('Exception while sending email', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $requestData
            ]);
            throw $e;
        }
    }

    public function __toString(): string
    {
        return 'custom';
    }
} 