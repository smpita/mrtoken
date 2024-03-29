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

Be sure to add the trait and interface to the user model

    Hackage\MrToken\Traits\MrTokenTrait;
    Hackage\MrToken\Interfaces\MrTokenInterface;

To publish the default config file

    php artisan vendor:publish --provider="Hackage\MrToken\MrTokenServiceProvider"

You must define the following methods in your user model

    /**
     * The name of the model column to uniquely ID the user.
     *
     * @return string
     */
    public function getMrTokenKeyColumn()
    {
        return 'id';
    }

    /**
     * The name of the model column that stores the token's salt.
     *
     * @return string
     */
    public function getMrTokenSaltColumn()
    {
        return 'api_token';
    }

    /**
     * The name of the model's integer column that stores the token's epoch expiration.
     *
     * @return string
     */
    public function getMrTokenExpiresColumn()
    {
        return 'token_expires_at';
    }
