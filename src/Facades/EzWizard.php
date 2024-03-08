<?php

declare(strict_types=1);
namespace Dgtlss\EzWizard\Facades;
use Illuminate\Support\Facades\Facade;

class EzWizard extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'EzWizard';
    }
}
