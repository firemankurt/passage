<?php namespace KurtJensen\Passage;

use App;
use Backend;
use BackendAuth;
use Event;
use Illuminate\Foundation\AliasLoader;
use RainLab\User\Models\UserGroup;
use System\Classes\PluginBase;

/**
 * passage Plugin Information File
 */
class Plugin extends PluginBase {
	public static $keys = null;
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
				'key' => 'user_group_id',
				'otherKey' => 'key_id',
				'order' => 'name'];
		});

		Event::listen('backend.menu.extendItems', function ($manager) {
			$manager->addSideMenuItems('RainLab.User', 'user', [
				'usergroups' => [
					'label' => 'rainlab.user::lang.groups.all_groups',
					'icon' => 'icon-group',
					'code' => 'u_groups',
					'owner' => 'RainLab.User',
					'url' => Backend::url('rainlab/user/usergroups'),
				],
				'passage_keys' => [
					'label' => 'kurtjensen.passage::lang.plugin.backend_menu',
					'icon' => 'icon-key',
					'order' => 1001,
					'code' => 'passage',
					'owner' => 'RainLab.User',
					'permissions' => ['kurtjensen.passage.*'],
					'url' => Backend::url('kurtjensen/passage/keys'),
				],
				'variance' => [
					'label' => 'kurtjensen.passage::lang.plugin.backend_variance',
					'icon' => 'icon-key',
					'order' => 1002,
					'code' => 'passage',
					'owner' => 'RainLab.User',
					'permissions' => ['kurtjensen.passage.*'],
					'url' => Backend::url('kurtjensen/passage/variances'),
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

		$alias = AliasLoader::getInstance();
		$alias->alias('PassageService', '\KurtJensen\Passage\Classes\KeyRing');
		App::register('\KurtJensen\Passage\Services\PassageServiceProvider');
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

	public function registerMarkupTags() {
		return [
			'functions' => [
				'can' => function ($key) {return app('PassageService')::hasKeyName($key);},
				'hasKeyName' => function ($key) {return app('PassageService')::hasKeyName($key);},
				'hasKeyNames' => function ($keys) {return app('PassageService')::hasKeyNames($keys);},
				'hasKey' => function ($key_id) {return app('PassageService')::hasKey($key_id);},
				'hasKeys' => function ($key_ids) {return app('PassageService')::hasKeys($key_ids);},

				'inGroupName' => function ($group) {return app('PassageService')::inGroupName($group);},
				'inGroupNames' => function ($groups) {return app('PassageService')::inGroupNames($groups);},
				'inGroup' => function ($group_key) {return app('PassageService')::inGroup($group_key);},
				'inGroups' => function ($group_keys) {return app('PassageService')::inGroups($group_keys);},
			],
		];
	}

	public static function globalPassageKeys() {
		traceLog("Deprecated method \KurtJensen\Passage\Plugin::globalPassageKeys() called. Use PassageService::passageKeys() instead. See Passage Upgrade Guide.");
		//trigger_error("Deprecated method \KurtJensen\Passage\Plugin::globalPassageKeys() called. Use app('PassageService')::passageKeys() instead.", E_USER_DEPRECATED);
		return app('PassageService')::passageKeys();
	}

	public static function passageKeys() {
		traceLog("Deprecated method \KurtJensen\Passage\Plugin::passageKeys() called. Use PassageService::passageKeys() instead. See Passage Upgrade Guide.");
		//trigger_error("Deprecated method \KurtJensen\Passage\Plugin::passageKeys() called. Use app('PassageService')::passageKeys() instead.", E_USER_DEPRECATED);
		return app('PassageService')::passageKeys();
	}

	public static function hasKeyName($key_name) {
		traceLog("Deprecated method \KurtJensen\Passage\Plugin::hasKeyName() called. Use PassageService::hasKeyName() instead. See Passage Upgrade Guide.");
		//trigger_error("Deprecated method \KurtJensen\Passage\Plugin::hasKeyName() called. Use app('PassageService')::hasKeyName() instead.", E_USER_DEPRECATED);
		$keys = app('PassageService')::passageKeys();
		return in_array($key_name, $keys);
	}

	public static function hasKey($key_id) {
		traceLog("Deprecated method \KurtJensen\Passage\Plugin::hasKey() called. Use PassageService::hasKey() instead. See Passage Upgrade Guide.");
		//trigger_error("Deprecated method \KurtJensen\Passage\Plugin::hasKey() called. Use app('PassageService')::hasKey() instead.", E_USER_DEPRECATED);
		$keys = app('PassageService')::passageKeys();
		return array_key_exists($key_id, $keys);
	}

	public static function passageGroups() {
		traceLog("Deprecated method \KurtJensen\Passage\Plugin::passageGroups() called. Use PassageService::passageGroups() instead. See Passage Upgrade Guide.");
		//trigger_error("Deprecated method \KurtJensen\Passage\Plugin::passageGroups() called. Use app('PassageService')::passageGroups() instead.", E_USER_DEPRECATED);
		return app('PassageService')::passageGroups();
	}

	public static function hasGroupName($group_name) {
		traceLog("Deprecated method \KurtJensen\Passage\Plugin::hasGroupName() called. Use PassageService::hasGroupName() instead. See Passage Upgrade Guide.");
		//trigger_error("Deprecated method \KurtJensen\Passage\Plugin::hasGroupName() called. Use app('PassageService')::hasGroupName() instead.", E_USER_DEPRECATED);
		$groups = app('PassageService')::passageGroups();
		return in_array($group_name, $groups);
	}

	public static function hasGroup($group_code) {
		traceLog("Deprecated method \KurtJensen\Passage\Plugin::hasGroup() called. Use PassageService::hasGroup() instead. See Passage Upgrade Guide.");
		//trigger_error("Deprecated method \KurtJensen\Passage\Plugin::hasGroup() called. Use app('PassageService')::hasGroup() instead.", E_USER_DEPRECATED);
		$groups = app('PassageService')::passageGroups();
		return array_key_exists($group_code, $groups);
	}
}