<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Film;
use Illuminate\Support\Facades\Validator;

class FilmController extends Controller
{
/**
 * @SWG\Get(
 *     path="/film",
 *     summary="Display a listing of films.",
 *     tags={"film"},
 *     produces={"application/xml", "application/json"},
 *     @SWG\Response(
 *          response=200,
 *          description="successful operation",
 *          @SWG\Schema(
 *              type="array",
 *              @SWG\Items(ref="#/definitions/Film"),
 *          ),
 *     ),
 * )
 */
 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $films = Film::all();
        
        return $films;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titre' => 'required|unique:films',
        ]);
        
        if($validator->fails()){
            return response()->json(
                ['errors' => $validator->errors()->all()], 
                422
            );    
        }   
             
        $film = new Film;
        $film->titre = $request->titre;
        $film->save();
        
        return response()->json(
            ['id_film' => $film->id_film],
            201
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $film = Film::find($id);
        
        if(empty($film)){
            return response()->json(
                ['error' => 'this film does not exist'], 404
            );
        }
        
        return $film;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $film = Film::find($id);
        $film->titre = $request->titre;
        $film->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $film = Film::find($id);
        
        $film->delete();
    }
}
