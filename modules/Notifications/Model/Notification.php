<?php

namespace KodiCMS\Notifications\Model;

use DB;
use Carbon\Carbon;
use KodiCMS\Users\Model\User;
use Illuminate\Database\Eloquent\Model;
use KodiCMS\Notifications\Contracts\NotificationTypeInterface;

/**
 * Class Notification.
 *
 * @property int $id
 * @property int $sender_id
 * @property string  $type
 * @property string  $message
 * @property int $object_id
 * @property string  $object_type
 * @property array   $parameters
 *
 * @property User    $sender
 * @property User[]  $users
 *
 * @property Carbon  $created_at
 * @property Carbon  $updated_at
 * @property Carbon  $sent_at
 */
class Notification extends Model
{
    /**
     * @var Model
     */
    private $relatedObject = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sender_id', 'type', 'message', 'object_id', 'object_type', 'sent_at', 'parameters'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['sent_at'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'sender_id'   => 'integer',
        'object_id'   => 'integer',
        'type'        => 'string',
        'message'     => 'string',
        'object_type' => 'string',
        'parameters'  => 'array',
    ];

    /**
     * @param string $message
     *
     * @return $this
     */
    public function withMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @param array $parameters
     *
     * @return $this
     */
    public function withParameters(array $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @param NotificationTypeInterface $type
     *larav
     *
     * @return $this
     */
    public function withType(NotificationTypeInterface $type)
    {
        $this->type = get_class($type);

        return $this;
    }

    /**
     * @param Model $object
     *
     * @return $this
     */
    public function regarding(Model $object)
    {
        $this->object_id = $object->getKey();
        $this->object_type = get_class($object);

        return $this;
    }

    /**
     * @param User $user
     *
     * @return $this
     */
    public function from(User $user)
    {
        $this->sender()->associate($user);

        return $this;
    }

    /**
     * @param array $users
     *
     * @return $this
     */
    public function deliver(array $users = [])
    {
        $this->sent_at = new Carbon;
        $this->save();

        foreach ($users as $user) {
            $this->users()->attach($user);
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function hasValidObject()
    {
        try {
            $object = call_user_func_array($this->object_type.'::findOrFail', [$this->object_id]);
        } catch (\Exception $e) {
            return false;
        }

        $this->relatedObject = $object;

        return true;
    }

    /**
     * @return Model
     * @throws \Exception
     */
    public function getObject()
    {
        if (is_null($this->relatedObject)) {
            $hasObject = $this->hasValidObject();

            if (! $hasObject) {
                throw new \Exception(sprintf(
                    'No valid object (%s with ID %s) associated with this notification.',
                    $this->object_type,
                    $this->object_id
                ));
            }
        }

        return $this->relatedObject;
    }

    /**
     * @param int $userId
     */
    public function markRead($userId)
    {
        $this->users()
            ->wherePivot('user_id', $userId)
            ->rawUpdate([
                'is_read' => true,
            ]);
    }

    public function deleteExpired()
    {
        // Delete expired notifications_users rows
        DB::table('notifications_users')
           ->leftJoin($this->getTable(), 'notifications_users.notification_id', '=', $this->getTable().'.id')
           ->whereRaw('DATE(created_at) < CURDATE() + INTERVAL 5 DAY')
           ->where('is_read', true)
           ->delete();

        // Delete expired notifications without unread
        DB::table($this->getTable())
           ->whereRaw('DATE(sent_at) < CURDATE() + INTERVAL 5 DAY')
           ->whereRaw('(select COUNT(*) from notifications_users where is_read = 0 AND notification_id = id) = 0')
           ->delete();
    }

    /*******************************************************************************************
     * Mutators
     *******************************************************************************************/

    /**
     * @param string $type
     *
     * @return NotificationTypeInterface
     */
    public function getTypeAttribute($type)
    {
        $type = class_exists($type) ? new $type : new DefaultNotificationType;

        $type->setObject($this);

        return $type;
    }

    /*******************************************************************************************
     * Relations
     *******************************************************************************************/

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'notifications_users', 'notification_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
