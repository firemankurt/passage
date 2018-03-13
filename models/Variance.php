<?php namespace KurtJensen\Passage\Models;

use KurtJensen\Passage\Models\Key;
use Lang;
use Model;
use RainLab\User\Models\User;

/**
 * Variance Model
 */
class Variance extends Model {
	use \October\Rain\Database\Traits\Validation;
	/**
	 * @var string The database table used by the model.
	 */
	public $table = 'kurtjensen_passage_variances';

	public $rules = [
		'key_id' => 'required',
		'user_id' => 'required',
	];

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
	public $belongsTo = [
		'key' => ['KurtJensen\Passage\Models\Key',
			'table' => 'kurtjensen_passage_keys',
			'key' => 'key_id',
			'otherkey' => 'id'],
		'user' => ['RainLab\User\Models\User',
			'table' => 'users',
			'key' => 'user_id',
			'otherkey' => 'id'],
	];

	public function __construct(array $attributes = array()) {
		$this->setRawAttributes([
			'grant' => true,
		], true);

		parent::__construct($attributes);
	}

	public function beforeValidate() {
		$invalid = $this->newQuery()->
			where('id', '!=', $this->id)->
			where('key_id', $this->key_id)->
			where('user_id', $this->user_id)->
			count() > 0;
		if ($invalid) {
			throw new \ValidationException(['unique_attribute' => Lang::get('kurtjensen.passage::lang.variance.error_duplicate')]);
		}
	}

	public function getUserIdOptions() {

		$options[0] = Lang::get('kurtjensen.passage::lang.choose_one');
		$users = User::orderBy('surname')->
			orderBy('name')->
			get(['surname', 'name', 'email', 'id']);
		foreach ($users as $user) {
			$options[$user->id] = $user->surname . ', ' . $user->name . ' - ' . $user->email;
		}
		return $options;
	}

	public function getKeyIdOptions() {
		return Key::lists('name', 'id');
	}

}
