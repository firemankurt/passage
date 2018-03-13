<?php namespace KurtJensen\Passage\Classes;

use Auth;
use KurtJensen\Passage\Models\Key;
use KurtJensen\Passage\Models\Variance;

/**
 * Passage Service Class
 * Provides methods for checking pemissions of front end users.
 *
 * @package kurtjensen\passage
 * @author Kurt Jensen
 */
class KeyRing {
	use \October\Rain\Support\Traits\Singleton;

	public static $keys = null;
	public static $groups = null;

	public function __construct() {
	}

/**
 * Find active user who is logged in
 * @return object Rainlab\User\Model\User
 */
	public static function getUser() {
		if (!$user = Auth::getUser()) {
			return false;
		}
		if (!$user->is_activated) {
			return false;
		}
		return $user;
	}

/**
 * Alias of hasKeyName()
 * @param  string  $key_name name of Key
 * @return boolean  true if user has key
 */
	public static function can($key_name) {
		return self::hasKeyName($key_name);
	}

/**
 * Get an array of all keys approved for user
 * @return array approved user keys names keyed by id
 */
	public static function passageKeys() {
		if (self::$keys === null) {
			if (!self::getUser()) {
				return [];
			}
			$add = $subtract = [];
			$variances = Variance::where('user_id', self::getUser()->id)->get(['user_id', 'key_id', 'grant']);
			foreach ($variances as $variance) {
				if ($variance->grant) {
					$add[] = $variance->key_id;
				} else {
					$subtract[] = $variance->key_id;
				}
			}

			$query = Key::whereHas('groups.users', function ($q) {
				$q->where('user_id', self::getUser()->id);
			});
			if ($subtract) {
				$query->whereNotIn('id', $subtract);
			}
			if ($add) {
				$query->orWhereIn('id', $add);
			}
			self::$keys = $query->lists('name', 'id');
		}
		return self::$keys;
	}

/**
 * Test if user has a approved key of a given name
 * @param  string  $key_name name of Key
 * @return boolean  true if user has key
 */
	public static function hasKeyName(string $key_name) {
		$keys = self::passageKeys();
		return in_array($key_name, $keys);
	}

/**
 * Test if user has a approved key of a given key id
 * @param  integer  $key_id id of a Key
 * @return boolean  true if user has corresponding key
 */
	public static function hasKey(int $key_id) {
		$keys = self::passageKeys();
		return array_key_exists($key_id, $keys);
	}

/**
 * Test if user has all keys in a given array approved
 * @param  array  $check_keys names of Keys to check
 * @return boolean  true if user has corresponding keys
 */
	public static function hasKeys(array $check_key_ids) {
		$keys = array_flip(self::passageKeys());
		return count(array_intersect($check_key_ids, $keys)) == count($check_key_ids);
	}

/**
 * Test if user has all keys in a given array approved
 * @param  array  $check_keys names of Keys to check
 * @return boolean  true if user has corresponding keys
 */
	public static function hasKeyNames(array $check_keys) {
		$keys = self::passageKeys();
		return count(array_intersect($check_keys, $keys)) == count($check_keys);
	}

	/**
	 * Group methods
	 */

/**
 * Get an array of all groups approved for user
 * @return array approved user group names keyed by code
 */
	public static function passageGroups() {
		if (self::$groups === null) {
			if (!$user = self::getUser()) {
				return self::$groups = [];
			}
			self::$groups = $user->groups->lists('name', 'code');
		}
		return self::$groups;
	}

/**
 * Test if user is in a group of a given name
 * @param  string  $group_name name of UsersGroup
 * @return boolean  true if user is part of group
 */
	public static function inGroupName(string $group_name) {
		if (!$user = self::getUser()) {
			return false;
		}
		return in_array($group_name, self::passageGroups());
	}

/**
 * Alias for inGroupName()
 * @param  string  $group_name name of UsersGroup
 * @return boolean  true if user is part of group
 */
	public static function hasGroupName(string $group_name) {
		return self::inGroupName($group_name);
	}

/**
 * Test if user is in a group of a given user group code
 * @param  string  $group_code code of UsersGroup
 * @return boolean  true if user is part of group
 */
	public static function inGroup(string $group_code) {
		return array_key_exists($group_code, self::passageGroups());
	}

/**
 * Test if user is in a group of a given user group code
 * @param  string  $group_code code of UsersGroup
 * @return boolean  true if user is part of group
 */
	public static function hasGroup(string $group_code) {
		return self::inGroup($group_code);
	}

/**
 * Test if user is in groups in a given array of group codes
 * @param  array  $check_group_codes names of Groups to check
 * @return boolean  true if user is in all groups
 */
	public static function inGroups(array $check_group_codes) {
		$group_codes = array_flip(self::passageGroups());
		return count(array_intersect($check_group_codes, $group_codes)) == count($check_group_codes);
	}

/**
 * Test if user is in groups in a given array of group names
 * @param  array  $check_groups names of Groups to check
 * @return boolean  true if user is in all groups
 */
	public static function inGroupNames(array $check_groups) {
		$group_names = self::passageGroups();
		return count(array_intersect($check_groups, $group_names)) == count($check_groups);
	}
}