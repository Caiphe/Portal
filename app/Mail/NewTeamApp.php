<?php

namespace App\Mail\Invites;

use App\User;
use App\Team;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewTeamAppCreated extends Mailables
{
    use Queueable, SerializesModels;
}
