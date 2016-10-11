<?php namespace KurtJensen\Passage;

use Auth;
use Backend;
use BackendAuth;
use Event;
use KurtJensen\Passage\Models\Key;
use RainLab\User\Models\UserGroup;
use System\Classes\PluginBase;

/**
 * passage Plugin Information File
 */
class Plugin extends PluginBase {
	public static $keys = [];
	public static $groups = null;

	public $require = ['RainLab.User'];

	/**
	 * Returns information about this plugin.
	 *
	 * @return array
	 */
	public function pluginDetails() {
		return [
			'name' => 'passage',
			'description' => 'Fast, Efficient permission system for controlling access to your website resources.',
			'author' => 'KurtJensen',
			'icon' => 'icon-key',
		];
	}

	public function messageURL() {
		return 'http://firemankurt.com/notices/';
	}

	public function boot() {
		UserGroup::extend(function ($model) {
			$model->belongsToMany['passage_keys'] = ['KurtJensen\Passage\Models\Key',
				'table' => 'kurtjensen_passage_groups_keys',
				'key' => 'user_group_id', 'otherKey' => 'key_id'];
		});

		Event::listen('backend.menu.extendItems', function ($manager) {
			$manager->addSideMenuItems('RainLab.User', 'user', [
				'u_groups' => [
					'label' => 'rainlab.user::lang.groups.all_groups',
					'icon' => 'icon-group',
					'code' => 'u_groups',
					'owner' => 'RainLab.User',
					'url' => Backend::url('rainlab/user/usergroups'),
				],
				'passage_keys' => [
					'label' => 'kurtjensen.passage::lang.plugin.backend_menu',
					'icon' => 'icon-key',
					'code' => 'passage',
					'owner' => 'RainLab.User',
					'permissions' => ['kurtjensen.passage.*'],
					'url' => Backend::url('kurtjensen/passage/keys'),
				],
			]);
		});

		Event::listen('backend.form.extendFields', function ($widget) {
			if (!$widget->getController() instanceof \RainLab\User\Controllers\UserGroups) {
				return;
			}

			if (!$widget->model instanceof \RainLab\User\Models\UserGroup) {
				return;
			}
			//die(BackendAuth::getUser()->first_name);
			if (!BackendAuth::getUser()->hasAccess('kurtjensen.passage.usergroups')) {
				return;
			}

			$widget->addFields([
				'passage_keys' => [
					'tab' => 'kurtjensen.passage::lang.plugin.field_tab',
					'label' => 'kurtjensen.passage::lang.plugin.field_label',
					'commentAbove' => 'kurtjensen.passage::lang.plugin.field_commentAbove',
					'span' => 'left',
					'type' => 'relation',
					'emptyOption' => 'kurtjensen.passage::lang.plugin.field_emptyOption',
				],
			], 'primary');
		});
	}

	/**
	 * Registers any front-end components implemented in this plugin.
	 *
	 * @return array
	 */
	public function registerComponents() {
		return [
			//'KurtJensen\Passage\Components\Lock' => 'Lock',
		];
	}

	/**
	 * Registers any back-end permissions used by this plugin.
	 *
	 * @return array
	 */
	public function registerPermissions() {
		return [
			'kurtjensen.passage.*' => [
				'tab' => 'rainlab.user::lang.plugin.tab',
				'label' => 'kurtjensen.passage::lang.plugin.permiss_label',
			],

			'kurtjensen.passage.usergroups' => [
				'tab' => 'rainlab.user::lang.plugin.tab',
				'label' => 'kurtjensen.passage::lang.plugin.permiss_label_ug',
			],
		];
	}

	/**
	 * Registers back-end navigation items for this plugin.
	 *
	 * @return array
	 */
	public function registerNavigation() {
		return []; // Remove this line to activate
	}

	public function registerMarkupTags() {
		return [
			'functions' => [
				'can' => function ($lock) {return $this->can($lock);},
				'inGroup' => function ($group) {return $this->inGroup($group);},
				'inGroupName' => function ($group) {return $this->inGroupName($group);},
			],
		];
	}

	private function inGroup($code) {
		$answer = array_key_exists($code, $this->passage_groups());
		if (!$answer) {
			$answer = $this->inGroupName($code);
			if ($answer) {
				trigger_error("Possible Deprecated use of twig function inGroup. The inGroup funtion now should use the unique user group code rather than the user group name.", E_USER_NOTICE);
			}
		}
		return $answer;
	}

	private function inGroupName($name) {
		if (!$user = Auth::getUser()) {
			return false;
		}
		return in_array($name, $this->passage_groups());
	}

	private function can($lock) {
		return in_array($lock, self::passage_keys());
	}

	public static function passage_groups() {
		if (self::$groups === null) {
			if (!$user = Auth::getUser()) {
				return self::$groups = [];
			}
			self::$groups = $user->groups->lists('name', 'code');
		}
		return self::$groups;
	}

	public static function passage_keys() {
		if (!count(self::$keys)) {
			if (!Auth::getUser()) {
				return [];
			}

			self::$keys = Key::whereHas('groups.users', function ($q) {
				$q->where('user_id', Auth::getUser()->id);
			})
				->lists('name', 'id');
		}
		return self::$keys;
	}

	public static function globalPassageKeys() {
		trigger_error("globalPassageKeys() Deprecated use passageKeys() instead.", E_USER_NOTICE);
		return \System\Classes\PluginManager::instance()->findByNamespace(__CLASS__)->passage_keys();
	}

	public static function passageKeys() {
		return \System\Classes\PluginManager::instance()->findByNamespace(__CLASS__)->passage_keys();
	}

	public static function hasKeyName($key_name) {
		$keys = \System\Classes\PluginManager::instance()->findByNamespace(__CLASS__)->passage_keys();
		return in_array($key_name, $keys);
	}

	public static function hasKey($key_id) {
		$keys = \System\Classes\PluginManager::instance()->findByNamespace(__CLASS__)->passage_keys();
		return array_key_exists($key_id, $keys);
	}

	public static function passageGroups() {
		return \System\Classes\PluginManager::instance()->findByNamespace(__CLASS__)->passage_groups();
	}

	public static function hasGroupName($group_name) {
		$groups = \System\Classes\PluginManager::instance()->findByNamespace(__CLASS__)->passage_groups();
		return in_array($group_name, $groups);
	}

	public static function hasGroup($group_code) {
		$groups = \System\Classes\PluginManager::instance()->findByNamespace(__CLASS__)->passage_groups();
		return array_key_exists($group_code, $groups);
	}
}
