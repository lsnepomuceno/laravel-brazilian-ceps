<?php

namespace LSNepomuceno\LaravelBrazilianCeps;

use Illuminate\Support\ServiceProvider;

class LaravelBrazilianCepsServiceProvider extends ServiceProvider
{
    protected string $configPath = __DIR__ . '/Config/config.php';
    protected string $configName = 'brazilian-ceps';
    protected string $routesPath = __DIR__ . '/Routes/api.php';

    public function register()
    {
        $this->mergeConfigFrom($this->configPath, $this->configName);
    }

    public function boot(): void
    {
        $this->publishConfig()
             ->registerRoutes();
    }

    protected function publishConfig(): self
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [
                    $this->configPath => config_path("{$this->configName}.php"),
                ]
                ,
                $this->configName
            );
        }

        return $this;
    }

    protected function registerRoutes(): self
    {
        $hasApiRouteEnabled = config('brazilian-ceps.enable_api_consult_cep_route', true);

        if ($hasApiRouteEnabled) {
            $this->loadRoutesFrom($this->routesPath);
        }

        return $this;
    }
}
