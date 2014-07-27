<?php namespace ThibaudDauce\EloquentInheritanceStorage;

use Illuminate\Support\ServiceProvider;

class EloquentInheritanceStorageServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

		/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('thibaud-dauce/eloquent-inheritance-storage');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['eloquent-inheritance-storage'] = $this->app->share(function($app) {
			return new InheritanceStorage;
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
