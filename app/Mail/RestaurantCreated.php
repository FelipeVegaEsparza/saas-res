<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use App\Models\SystemSetting;

class RestaurantCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $restaurantName;
    public $slug;
    public $adminName;
    public $adminEmail;
    public $adminPassword;
    public $planName;
    public $trialDays;
    public $loginUrl;
    public $companyName;
    public $supportEmail;
    public $welcomeMessage;
    public $footerText;

    /**
     * Create a new message instance.
     */
    public function __construct($restaurantName, $slug, $adminName, $adminEmail, $adminPassword, $planName, $trialDays = 0)
    {
        $this->restaurantName = $restaurantName;
        $this->slug = $slug;
        $this->adminName = $adminName;
        $this->adminEmail = $adminEmail;
        $this->adminPassword = $adminPassword;
        $this->planName = $planName;
        $this->trialDays = $trialDays;
        $this->loginUrl = url("/{$slug}/login");

        // Cargar configuraciones personalizables
        $this->companyName = SystemSetting::get('company_name', 'Sistema de Gestión de Restaurantes');
        $this->supportEmail = SystemSetting::get('support_email', config('mail.from.address'));
        $this->welcomeMessage = SystemSetting::get('email_welcome_message', 'Tu cuenta está lista para usar. Hemos configurado todo lo necesario para que puedas comenzar a gestionar tu restaurante de inmediato.');
        $this->footerText = SystemSetting::get('email_footer_text', 'Este es un correo automático, por favor no respondas a este mensaje.');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = SystemSetting::get('email_welcome_subject', '¡Bienvenido! Tu cuenta ha sido activada');

        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: $subject . ' - ' . $this->restaurantName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.restaurant-created',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
