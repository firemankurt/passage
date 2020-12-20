<?php namespace KurtJensen\Passage\Behaviors;
use RainLab\User\Models\UserGroup;

/**
 * Adds features for copying keys to another group
 *
 * This behavior is implemented in the component like so:
 *
 *    public $implement = ['KurtJensen.Passage.Behaviors.KeyCopy'];
 *
 *
 **/

class KeyCopy extends \October\Rain\Extension\ExtensionBase {

	protected $controller;
	public $allGroups;
	/**
	 * Constructor
	 */
	public function __construct($controller) {
		$this->controller = $controller;
	}

	public function getAllGroups() {
		$this->controller->allGroups = UserGroup::orderBy('name')->get();
	}

	public function onCopy() {
		$group = UserGroup::find(post('CGid'));
		if (!$group->passage_keys->count() > 0) {
			return [];
		}
		foreach ($group->passage_keys as $key) {

			$funct_lines[] = '$(\'input:checkbox[name="UserGroup[passage_keys][]"][value="' . $key->id . '"]\').prop( "checked", true );';
		}

		return ['#copyGkeys' => '
			<script type="text/javascript">
			               ' . implode('', $funct_lines) . '
			</script>
			'];
	}

	public function onGetGroups() {

		return ['#copyForm' => 'fo'];
	}
}