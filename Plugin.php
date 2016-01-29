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
class Plugin extends PluginBase
{
    public static $keys = [];

    public $require = ['RainLab.User'];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name' => 'passage',
            'description' => 'No description provided yet...',
            'author' => 'KurtJensen',
            'icon' => 'icon-key',
        ];
    }

    public function boot()
    {
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
    public function registerComponents()
    {
        return [
            //'KurtJensen\Passage\Components\Lock' => 'Lock',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
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
    public function registerNavigation()
    {
        return []; // Remove this line to activate
    }

    public function registerMarkupTags()
    {
        return [
            'functions' => [
                'can' => function ($lock) {return $this->can($lock);},
            ],
        ];
    }

    private function can($lock)
    {
        return in_array($lock, self::passage_keys());
    }

    public static function passage_keys()
    {
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

    public static function globalPassageKeys()
    {return \System\Classes\PluginManager::instance()->findByNamespace(__CLASS__)->passage_keys();}

}
