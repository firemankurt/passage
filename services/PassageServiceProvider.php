<?php namespace KurtJensen\Passage\Services;

use October\Rain\Support\ServiceProvider;

class PassageServiceProvider extends ServiceProvider {

	public function register() {
		$this->app->singleton('PassageService', function ($app) {
			return new \KurtJensen\Passage\Classes\KeyRing;
		});
	}

}