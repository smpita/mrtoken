To install, add the following to the composer.json file and then 'composer update'

    "require": {
        "hipstercreative/mrtoken": "dev-Development"
    }

    "repositories": [
        {
            "type": "vcs",
            "url": "git@bitbucket.org:hipstercreative/mrtoken.git"
        }
    ]

To register the service provider, add the following line to the config/app.php
file in the provider section.

    Hackage\MrToken\MrTokenServiceProvider::class

To register the middleware, add the following line to the app/Http/Kernel.php

    'mrtoken' => \Hackage\MrToken\Middleware\MrTokenMiddleware::class
