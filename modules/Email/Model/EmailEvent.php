<?php

namespace KodiCMS\Email\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EmailEvent.
 *
 * @property int         $id
 * @property string          $code
 * @property string          $name
 * @property string          $full_name
 * @property array           $fields
 *
 * @property EmailTemplate[] $templates
 *
 * @property Carbon          $created_at
 * @property Carbon          $updated_at
 */
class EmailEvent extends Model
{
    /**
     * @param string $code
     *
     * @return static
     */
    public static function get($code)
    {
        return static::whereCode($code)->first();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'fields',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'fields' => 'array',
    ];

    /**
     * @return string
     */
    public function getNotFoundMessage()
    {
        return trans('email::core.messages.events.not_found');
    }

    /**
     * @return array
     */
    public function defaultOptions()
    {
        $now = Carbon::create();

        return [
            'default_email'    => config('mail.default'),
            'site_title'       => config('cms.title'),
            'site_description' => config('cms.description'),
            'base_url'         => url('/'),
            'current_time'     => $now->format('H:i:s'),
            'current_date'     => $now->format(config('cms.date_format')),
        ];
    }

    /**
     * @param array $options
     */
    public function send(array $options = [])
    {
        $options = array_merge($options, $this->defaultOptions());
        $templates = $this->templates()->active()->get();
        foreach ($templates as $template) {
            $template->send($options);
        }
    }

    /*******************************************************
     * Mutators
     *******************************************************/

    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->name} ({$this->code})";
    }

    /*******************************************************
     * Relations
     *******************************************************/

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function templates()
    {
        return $this->hasMany(EmailTemplate::class, 'email_event_id');
    }
}
