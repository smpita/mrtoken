<?php

namespace Hackage\MrToken\Interfaces;

interface MrTokenInterface
{
    /**
     * The name of the model column to uniquely ID the user.
     * @return string
     */
    public function getMrTokenKeyColumn();

    /**
     * The name of the model column that stores the token's salt.
     * @return string
     */
    public function getMrTokenSaltColumn();

    /**
     * The name of the model column that stores the token's epoch expiration.
     * @return string
     */
    public function getMrTokenExpiresColumn();

    /**
     * Generate a new token for the user.
     * @return mixed string|boolean
     */
    public function freshApiToken();

    /**
     * Regenerate the last token for the user.
     * @return mixed string|boolean
     */
    public function lastApiToken();

    /**
     * Save the model to the database.
     *
     * @param  array  $options
     * @return bool
     */
    public function save();
}
