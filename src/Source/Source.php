<?php

namespace App\Source;

interface Source
{
    public function retrieve(): string;
}
