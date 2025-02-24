<?php

namespace App\Mail;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MessageMailable extends Mailable
{
  use Queueable, SerializesModels;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public $nombres;
  public $apellidos;
  public $email;
  public $celular;
  public $fecha;
  public $hora;
  public $distrito_origen;
  public $distrito_destino;

  public function __construct($nombres, $apellidos, $email, $celular, $fecha, $hora, $distrito_origen, $distrito_destino)
  {
    $this->nombres = $nombres;
    $this->apellidos	= $apellidos;
    $this->email	= $email;
    $this->celular	= $celular;
    $this->fecha = $fecha;
    $this->hora = $hora;
    $this->distrito_origen = $distrito_origen;
    $this->distrito_destino = $distrito_destino;
  }

  /**
   * Get the message envelope.
   *
   * @return \Illuminate\Mail\Mailables\Envelope
   */
  public function envelope()
  {
    return new Envelope(
      subject: 'Reserva',
    );
  }

  /**
   * Get the message content definition.
   *
   * @return \Illuminate\Mail\Mailables\Content
   */
  public function content()
  {
    return new Content(
      view: 'mails.reservation',
    );
  }

  /**
   * Get the attachments for the message.
   *
   * @return array
   */
  public function attachments()
  {
    return [];
  }
}
