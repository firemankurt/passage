<?php namespace KurtJensen\Passage\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use DB;
use KurtJensen\Passage\Models\Key;
use KurtJensen\Passage\Models\UserGroupsKeys;
use System\Classes\PluginManager;

/**
 * Keys Back-end Controller
 */
class Keys extends Controller {
	public $addBtns = '';
	public $requiredPermissions = ['kurtjensen.passage.keys'];
	public $implement = [
		'Backend.Behaviors.FormController',
		'Backend.Behaviors.ListController',
	];

	public $formConfig = 'config_form.yaml';
	public $listConfig = 'config_list.yaml';

	public function __construct() {
		parent::__construct();

		BackendMenu::setContext('RainLab.User', 'user', 'passage_keys');
	}

	public function index() {
		parent::index();
		$manager = PluginManager::instance();
		$this->addBtns = ($manager->exists('shahiemseymor.roles')) ?
		'
        <div class="layout-row">
            <div class="padded-container">
                <div class="callout callout-info">
                    <div class="header">
                    <p>It looks like you have "Frontend User Roles Manager" installed.<br />
                    I can try help you transfer data over to the "Passage Keys" to save you time.<br />
                    For best results press red buttons in order from Left to Right.</p>
                    <a href="#"
                      data-request="onConvertFromPerms"
                      data-load-indicator="Loading..."
                      data-request-confirm="Are you sure you want all Permissions copied into Passage Keys?"
                      class="btn btn-danger  oc-icon-exchange ">
                      (1) Transfer Permissions to Passage Keys
                    </a>
                    <p>&nbsp;</p>

                    <a href="#"
                      data-request="onConvertFromRoles"
                      data-load-indicator="Loading..."
                      data-request-confirm="Are you sure you want all Roles copied into User Groups?"
                      class="btn btn-danger  oc-icon-exchange ">
                      (2) Transfer Roles to User Groups
                    </a><p>&nbsp;</p>

                    <a href="#"
                      data-request="onConvertFromRolesPerms"
                      data-load-indicator="Loading..."
                      data-request-confirm="Are you sure you want all Goup Permissions copied into Group Passage Keys?"
                      class="btn btn-danger  oc-icon-exchange ">
                      (3) Transfer Goup Permissions to Group Passage Keys
                    </a>
                    <p class="small">This notice will go away if you uninstall "Frontend User Roles Manager".</p>
                    </div>
                </div>
            </div>
        </div>' : '';

	}

	public function onConvertFromPerms() {
		$manager = PluginManager::instance();
		if ($manager->exists('shahiemseymor.roles')) {

			$perms = DB::table('shahiemseymor_permissions')->get();
			foreach ($perms as $perm) {

				$newRows[] = [
					'id' => $perm->id,
					'name' => $perm->name,
					'description' => $perm->display_name];
			}
			Key::insert($newRows);
		}
	}

	public function onConvertFromRoles() {
		$manager = PluginManager::instance();
		if ($manager->exists('shahiemseymor.roles')) {
			$roles = DB::table('shahiemseymor_roles')->get();
			foreach ($roles as $role) {

				$newRows[] = [
					'id' => $role->id,
					'name' => $role->name,
					'code' => str_replace(' ', '_', strtolower($role->name)),
					'description' => $role->name];
			}

			\RainLab\User\Models\UserGroup::insert($newRows);
		}
	}

	public function onConvertFromRolesPerms() {
		$manager = PluginManager::instance();
		if ($manager->exists('shahiemseymor.roles')) {
			$permRoles = DB::table('shahiemseymor_permission_role')->get();
			foreach ($permRoles as $pr) {

				$newRows[] = [
					'key_id' => $pr->permission_id,
					'user_group_id' => $pr->role_id];
			}

			UserGroupsKeys::insert($newRows);
		}
	}
}
