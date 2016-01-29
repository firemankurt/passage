<?php namespace KurtJensen\Passage\Models;

use Model;

/**
 * Key Model
 */
class Key extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'kurtjensen_passage_keys';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Relations
     */
    public $belongsToMany = [
        'groups' => ['RainLab\User\Models\UserGroup',
            'table' => 'kurtjensen_passage_groups_keys',
            'key' => 'key_id',
            'otherkey' => 'user_group_id',
        ],
        'users_count' => ['RainLab\User\Models\UserGroup',
            'table' => 'kurtjensen_passage_groups_keys',
            'count' => true,
        ],
    ];
}
