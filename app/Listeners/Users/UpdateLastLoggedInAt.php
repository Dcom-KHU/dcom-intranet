<?php

namespace App\Listeners\Users;

use DateTime;
use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateLastLoggedInAt
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
	 
	 // 마지막 로그인 체크
	 // https://laracasts.com/discuss/channels/general-discussion/can-we-track-last-login-date-of-authorised-user?page=1 참고
	public function handle(Login $event)
	{
		$event->user->logintime = new DateTime;
		$event->user->save();
	}
}
