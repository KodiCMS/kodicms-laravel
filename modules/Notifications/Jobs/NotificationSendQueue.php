<?php

namespace KodiCMS\Notifications\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;

class NotificationSendQueue extends NotificationSend implements ShouldQueue
{
}
