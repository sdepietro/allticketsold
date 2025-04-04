<?php

namespace App\Mail;

use App\Models\Attendee;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class SendOrderAttendeeTicketMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The Attendee instance.
     *
     * @var Attendee
     */
    public $attendee;
    public $email_logo;
	public $ticketUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Attendee $attendee, $ticketUrl)
    {
        $this->attendee = $attendee;
		$this->ticketUrl = $ticketUrl;
        $this->email_logo = $attendee->event->organiser->full_logo_path;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //$file_name = $this->attendee->getReferenceAttribute();
        //$file_path = public_path(config('attendize.event_pdf_tickets_path')) . '/' . $file_name . '.pdf';

        $subject = trans(
            "Controllers.tickets_for_event",
            ["event" => $this->attendee->event->title]
        );
        return $this->subject($subject)
                    ->view('Emails.OrderAttendeeTicket')
					->with([
                        'ticket_url' => $this->ticketUrl,  // Pasamos la URL a la vista
                    ]);
    }
}
