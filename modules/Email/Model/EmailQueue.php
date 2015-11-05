<?php

namespace KodiCMS\Email\Model;

use KodiCMS\Email\Support\EmailSender;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class EmailQueue.
 *
 * @property int $id
 * @property string  $status
 * @property object  $parameters
 * @property string  $message_type
 * @property string  $body
 * @property int $attempts
 *
 * @property Carbon  $created_at
 * @property Carbon  $updated_at
 */
class EmailQueue extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_SENT = 'sent';
    const STATUS_FAILED = 'failed';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($instance) {
            $instance->status = static::STATUS_PENDING;
            $instance->attempts = 0;
        });
    }

    public static function sendAll()
    {
        set_time_limit(0);

        $size = config('email_queue.batch_size');
        $interval = config('email_queue.interval');

        $queue = static::pending()->get();

        $i = 0;
        foreach ($queue as $email) {
            if ($i >= $size) {
                $i = 0;
                sleep($interval);
            }
            $email->send();
            $i++;
        }
    }

    public static function cleanOld()
    {
        static::notPending()->old()->delete();
    }

    /**
     * @param EmailTemplate $emailTemplate
     * @param array         $options
     *
     * @return static
     */
    public static function addEmailTemplate(EmailTemplate $emailTemplate, array $options = [])
    {
        $parameters = array_only($emailTemplate->toArray(), [
            'email_from',
            'email_to',
            'subject',
            'cc',
            'bcc',
            'reply_to',
        ]);

        return static::create([
            'parameters'   => $parameters,
            'message_type' => $emailTemplate->message_type,
            'body'         => $emailTemplate->message,
        ]);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parameters',
        'message_type',
        'body',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'parameters' => 'object',
    ];

    public function send()
    {
        $sent = EmailSender::send($this->body, $this->parameters, $this->message_type);

        $this->attempts++;

        if ($sent) {
            $this->status = static::STATUS_SENT;
        } elseif ($this->attempts >= config('email_queue.max_attempts')) {
            $this->status = static::STATUS_FAILED;
        }
        $this->save();
    }

    /*******************************************************
     * Scopes
     *******************************************************/

    /**
     * @param Builder $query
     */
    public function scopePending(Builder $query)
    {
        $query->whereStatus(static::STATUS_PENDING);
    }

    /**
     * @param Builder $query
     */
    public function scopeNotPending(Builder $query)
    {
        $query->where('status', '!=', static::STATUS_PENDING);
    }

    /**
     * @param Builder $query
     */
    public function scopeOld(Builder $query)
    {
        $query->where('created_at', '<', Carbon::create()->subDays(10));
    }
}
