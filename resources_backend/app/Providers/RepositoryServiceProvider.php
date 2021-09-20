<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repository\EloquentRepositoryInterface;
use App\Repository\PdfRepositoryInterface;
use App\Repository\HtmlRepositoryInterface;
use App\Repository\LinkRepositoryInterface;
use App\Repository\Eloquent\BaseRepository;
use App\Repository\Eloquent\PdfRepository;
use App\Repository\Eloquent\HtmlRepository;
use App\Repository\Eloquent\LinkRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(PdfRepositoryInterface::class, PdfRepository::class);
        $this->app->bind(HtmlRepositoryInterface::class, HtmlRepository::class);
        $this->app->bind(LinkRepositoryInterface::class, LinkRepository::class);                
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
