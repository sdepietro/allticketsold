<?php

namespace App\Jobs;

use App\Mail\SendOrderAttendeeTicketMail;
use App\Models\Attendee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Config;
use Mail;

class SendOrderAttendeeTicketJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $attendee;
	public $ticketUrl;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Attendee $attendee, $ticketUrl)
    {
        $this->attendee = $attendee;
		$this->ticketUrl = $ticketUrl;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //GenerateTicketJob::dispatchNow($this->attendee);
		try {
		if (filter_var($this->attendee->email, FILTER_VALIDATE_EMAIL)) {
        $mail = new SendOrderAttendeeTicketMail($this->attendee, $this->ticketUrl);
        Mail::to($this->attendee->email)
            ->locale(Config::get('app.locale'))
            ->send($mail);
		}
		} catch (\Exception $e) {
        // No hacer nada; simplemente continuar con la ejecución
		}
    }
}
