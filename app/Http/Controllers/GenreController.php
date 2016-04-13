<?php

namespace App\Http\Controllers;

use App\Genre;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;

class GenreController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/genre",
     *     summary="Display a listing of genres.",
     *     tags={"genre"},
     *     @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Genre")
     *          ),
     *     ),
     *  )
     */
    public function index()
    {
        $genres = Genre::all();
        return $genres;
    }

    /**
     * @SWG\Post(
     *     path="/genre",
     *     summary="Create a genre",
     *     description="Use this method to create a genre",
     *     operationId="createGenre",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"genre"},
     *     @SWG\Parameter(
     *         description="Nom du genre",
     *         in="formData",
     *         name="nom",
     *         required=true,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Genre created",
     *         @SWG\Schema(
     *              ref="#/definitions/Genre",
     *         ),
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Le champ 'Nom' est obligatoire.",
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|unique:genres|max:255'
        ]);

        if($validator->fails()){
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }

        $genre = new Genre;
        $genre->nom = $request->nom;
        $genre->save();

        return response()->json(
            $genre,
            201);
    }

    /**
     * @SWG\Get(
     *      path="/genre/{id_genre}",
     *      summary="Display a single genre",
     *      description="Use this method to return a single genre attributes based on its id.",
     *      operationId="showGenre",
     *      tags={"genre"},
     *      @SWG\Parameter(
     *          name="id_genre",
     *          in="path", 
     *          type="integer",
     *          description="id of genre to fetch",
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *           @SWG\Schema(
     *               ref="#/definitions/Genre",
     *           ),
     *      ),
     *      @SWG\Response(
     *           response=404, 
     *           description="Genre not found"
     *       ),
     * )
     */
    public function show($id)
    {
        $genre = Genre::find($id);

        if(empty($genre)){
            return response()->json(
                ['error' => 'This genre does not exist'],
                404);
        }
        return $genre;
    }

    /**
     * @SWG\Put(
     *     path="/genre/{id_genre}",
     *     summary="Update a genre",
     *     description="Use this method to update the attributes of a genre based on its id.",
     *     operationId="updateGenre",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"genre"},
     *     @SWG\Parameter(
     *         description="Nom du genre",
     *         in="formData",
     *         name="nom",
     *         required=true,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Genre updated",
     *         @SWG\Schema(
     *              ref="#/definitions/Genre",
     *         ),
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Le champ 'Nom' est obligatoire."
     *     ),
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|unique:genres|max:255'
        ]);

        if($validator->fails()){
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }

        $genre = Genre::find($id);
        $genre->nom = $request->nom;
        $genre->save();
        
        return response()->json(
            $genre,
            200);
    }

    /**
     * @SWG\Delete(
     *     path="/genre/{id_genre}",
     *     summary="Delete a genre",
     *     description="Use this method to delete a genre based on its id.",
     *     operationId="deleteGenre",
     *     tags={"genre"},
     *     @SWG\Parameter(
     *         description="Genre ID to delete",
     *         in="path",
     *         name="id_genre",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Genre deleted"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Invalid genre ID"
     *     )
     *
     * )
     */
    public function destroy($id)
    {
        $genre = Genre::find($id);

        if(empty($genre)){
            return response()->json(
                ['error' => 'This genre does not exist'],
                404);
        }

        $genre->delete();
    }
}
