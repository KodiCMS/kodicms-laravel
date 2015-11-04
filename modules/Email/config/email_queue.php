<?php

return [

    /*
     * The number of emails to send out in each batch. This should be tuned to your servers abilities
     * and the frequency of the cron.
     */
    'batch_size'   => 50,
    'interval'     => 120,
    /*
     * The maximum number of attempts to send an email before giving up. An email may fail to send if the
     * server is too busy, or there's a problem with the email itself.
     */
    'max_attempts' => 5,

];
