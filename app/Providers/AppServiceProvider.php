<?php

namespace App\Providers;

use App\Repositories\PostRepository;
use App\Repositories\PostRepositoryInterface;
use App\Services\BinaryTree\TreeBuilderService;
use App\Services\QueryBuilderFilter\MysqlOperators\AndOperator;
use App\Services\QueryBuilderFilter\MysqlOperators\EqualOperator;
use App\Services\QueryBuilderFilter\MysqlOperators\GreaterThanOperator;
use App\Services\QueryBuilderFilter\MysqlOperators\LessThanOperator;
use App\Services\QueryBuilderFilter\MysqlOperators\NotOperator;
use App\Services\QueryBuilderFilter\MysqlOperators\OrOperator;
use App\Services\QueryBuilderFilter\QueryBuilderFilterService;
use App\Services\QueryBuilderFilter\Stages\BuildTreeStage;
use App\Services\QueryBuilderFilter\Stages\CompileWhereQueryStage;
use App\Services\QueryBuilderFilter\Stages\GetResultStage;
use App\Services\QueryBuilderFilter\Stages\SortQueryStage;
use App\Services\Search\Drivers\QueryBuilderPostSearchDriver;
use App\Services\Search\Paginator;
use App\Services\Search\PaginatorInterface;
use App\Services\Search\PostSearchDriverInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PostSearchDriverInterface::class, QueryBuilderPostSearchDriver::class);
        $this->app->bind(PaginatorInterface::class, Paginator::class);
        $this->app->bind(PostRepositoryInterface::class, PostRepository::class);

        $this->app->tag([
            EqualOperator::class,
            AndOperator::class,
            OrOperator::class,
            GreaterThanOperator::class,
            LessThanOperator::class,
            NotOperator::class,
        ],
            'mysql.search.operators'
        );

        $this->app->tag([
            BuildTreeStage::class,
            CompileWhereQueryStage::class,
            SortQueryStage::class,
            GetResultStage::class,
        ],
            'mysql.filter.stages'
        );

        $this->app->when(QueryBuilderFilterService::class)
                  ->needs('$stages')
                  ->giveTagged('mysql.filter.stages');

        $this->app->when(TreeBuilderService::class)
                  ->needs('$operators')
                  ->giveTagged('mysql.search.operators');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
