<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return function(ContainerConfigurator $container): void {
    // default configuration for services in *this* file
    $services = $container->services()
        ->defaults()
            ->autowire()      // Automatically injects dependencies in your services.
            ->autoconfigure() // Automatically registers your services as commands, event subscribers, etc.
    ;

    // makes classes in src/ available to be used as services
    // this creates a service per class whose id is the fully-qualified class name
    $services
        ->load('App\\', '../src/')
		->share(false) // se isso n for setado, fudeu, pq ele vai reusar as instâncias entre requests
		->exclude('../src/{DependencyInjection,Entity,Kernel.php}')
    ;

    // order is important in this file because service definitions
    // always *replace* previous ones; add your own service configuration below
};