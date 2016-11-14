<?php namespace KurtJensen\Passage\Models;

use Model;

/**
 * UsersGroups Model
 */
class UsersGroups extends Model {

	/**
	 * @var string The database table used by the model.
	 */
	public $table = 'users_groups';

	/**
	 * @var array Guarded fields
	 */
	protected $guarded = ['*'];

	/**
	 * @var array Fillable fields
	 */
	protected $fillable = [];

	public $belongsTo = [
		'user' => ['RainLab\User\Models\User',
			'key' => 'user_id',
			'otherKey' => 'id'],

		'group' => ['RainLab\User\Models\UserGroup',
			'table' => 'user_groups',
			'key' => 'user_group_id',
			'otherkey' => 'id',
		],
	];

	public $belongsToMany = [
		'passage_keys' => ['KurtJensen\Passage\Models\Key',
			'table' => 'kurtjensen_passage_groups_keys',
			'key' => 'user_group_id',
			'otherKey' => 'key_id',
		],
	];

}