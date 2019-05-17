<?php
namespace App\Modules\Core\Services;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

class BaseService extends ServiceProvider
{
    public function __construct(Application $app)
    {
        parent::__construct($app);
    }
}