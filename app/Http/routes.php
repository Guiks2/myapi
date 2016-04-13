<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'jwt.auth'], function() {
    Route::resource('abonnement', 'AbonnementController');
    
    Route::resource('distributeur', 'DistributeurController');
    
    Route::resource('employe', 'EmployeController');
    
    Route::resource('film', 'FilmController');
    Route::get('film/distributeur/{id_distributeur}', 'FilmController@listFilmsByDistributor');
    Route::get('film/genre/{id_genre}', 'FilmController@listFilmsByGenre');
    
    Route::resource('fonction', 'FonctionController');

    Route::resource('forfait', 'ForfaitController');

    Route::resource('genre', 'GenreController');

    Route::resource('historique_membre', 'HistoriqueMembreController');

    Route::resource('membre', 'MembreController');

    Route::resource('personne', 'PersonneController');

    Route::resource('reduction', 'ReductionController');

    Route::resource('salle', 'SalleController');

    Route::resource('seance', 'SeanceController');
}
});

Route::resource('distributeur', 'DistributeurController');


Route::post('authenticate', [
    'as' => 'authenticate', 'uses' => 'JWTController@authenticate'
]);

Route::post('hashPassword', [
    'as' => 'hashPassword', 'uses' => 'JWTController@hashPassword'
]);
