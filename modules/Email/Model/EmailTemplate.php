<?php

namespace KodiCMS\Email\Model;

use Carbon\Carbon;
use KodiCMS\Email\Support\EmailSender;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class EmailTemplate.
 *
 * @property int    $id
 * @property int    $email_event_id
 * @property int    $status
 * @property string     $status_string
 * @property bool       $use_queue
 * @property string     $email_from
 * @property string     $email_to
 * @property string     $subject
 * @property string     $message
 * @property string     $message_type
 * @property string     $cc
 * @property string     $bcc
 * @property string     $reply_to
 *
 * @property EmailEvent $event
 *
 * @property Carbon     $created_at
 * @property Carbon     $updated_at
 */
class EmailTemplate extends Model
{
    const TYPE_HTML = 'html';
    const TYPE_TEXT = 'plain';

    const ACTIVE = 1;
    const INACTIVE = 0;

    const USE_QUEUE = 1;
    const USE_DIRECT = 0;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($emailTemplate) {
            if (empty($emailTemplate->message_type)) {
                $emailTemplate->message_type = 'html';
            }
        });
    }

    /**
     * @return array
     */
    public static function statuses()
    {
        return [
            static::ACTIVE   => trans('email::core.statuses.1'),
            static::INACTIVE => trans('email::core.statuses.0'),
        ];
    }

    /**
     * @return array
     */
    public static function queueStatuses()
    {
        return [
            static::USE_DIRECT => trans('email::core.queue.0'),
            static::USE_QUEUE  => trans('email::core.queue.1'),
        ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email_event_id',
        'status',
        'use_queue',
        'email_from',
        'email_to',
        'subject',
        'message',
        'message_type',
        'cc',
        'bcc',
        'reply_to',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'use_queue' => 'boolean',
    ];

    /**
     * @return string
     */
    public function getNotFoundMessage()
    {
        return trans('email::core.messages.templates.not_found');
    }

    /**
     * @param array $options
     *
     * @return bool|EmailTemplate
     */
    public function send($options = [])
    {
        $options = $this->prepareOptions($options);
        $this->prepareInnerValues($options);

        if ($this->use_queue) {
            return $this->addToQueue($options);
        } else {
            return EmailSender::send($this->message, $this, $this->message_type);
        }
    }

    /**
     * @param array $options
     *
     * @return static
     */
    public function addToQueue(array $options = [])
    {
        return EmailQueue::addEmailTemplate($this, $options);
    }

    /*******************************************************
     * Mutators
     *******************************************************/

    /**
     * @return string
     */
    public function getStatusStringAttribute()
    {
        return trans('email::core.statuses.'.$this->status);
    }

    /*******************************************************
     * Scopes
     *******************************************************/

    /**
     * @param Builder $query
     */
    public function scopeActive(Builder $query)
    {
        $query->whereStatus(static::ACTIVE);
    }

    /*******************************************************
     * Relations
     *******************************************************/

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(EmailEvent::class, 'email_event_id');
    }

    /**
     * @param array $options
     *
     * @return array
     */
    protected function prepareOptions(array $options = [])
    {
        $prepared = [];
        foreach ($options as $key => $value) {
            $prepared['{'.$key.'}'] = $value;
        }

        return $prepared;
    }

    /**
     * @param array $options
     */
    protected function prepareInnerValues(array $options = [])
    {
        foreach ($this->fillable as $field) {
            $this->$field = strtr($this->$field, $options);
        }
    }
}
