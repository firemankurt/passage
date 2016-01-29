<?php namespace KurtJensen\Passage\Models;

use Model;

/**
 * UserGroupsKeys Model
 */
class UserGroupsKeys extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'kurtjensen_passage_groups_keys';

    /**
     * @var array Guarded fields
     */
    protected $guarded = [];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['*'];

    /**
     * @var array Relations
     */
    public $hasOne = [
        'key' => ['KurtJensen\Passage\Models\Key',
            'table' => 'kurtjensen_passage_keys',
            'key' => 'key_id',
            'otherkey' => 'id',
        ],
        'group' => ['RainLab\User\Models\UserGroup',
            'table' => 'user_groups',
            'key' => 'user_group_id',
            'otherkey' => 'id',
        ],
    ];
}
