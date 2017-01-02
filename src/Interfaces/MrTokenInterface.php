<?php

namespace Hackage\MrToken\Interfaces;

interface MrTokenInterface
{
    public function getMrTokenKeyColumn();
    public function getMrTokenSaltColumn();
    public function freshApiToken();
    public function save();
}
