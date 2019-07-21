<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

use App\Question;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        Route::bind('slug', function($slug, $id){
           
            //Sort cau tra loi theo thu tu diem vote cao len tren cung
            //C1
            // $question = Question::with(['user', 'answers.user', 'answers' => function($query){
            //     $query->orderBy('votes_count', 'DESC');
            // }])->where('slug', $slug)->where('id', $id->parameters['id'])->first();
            
            
            //C2: orderBy ben Question Model luon, phia sau function answers()


            //Khong sort cau tra loi
            $question = Question::with(['user', 'answers.user'])->where('slug', $slug)->where('id', $id->parameters['id'])->first();

            // return $question ? $question : abort(404);
            return $question ?? abort(404);
        });

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
