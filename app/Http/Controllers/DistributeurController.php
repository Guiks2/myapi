<?php

namespace App\Http\Controllers;

use App\Distributeur;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;

class DistributeurController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/distributeur",
     *     summary="Display a listing of distributors.",
     *     tags={"distributeur"},
     *     @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Distributeur")
     *          ),
     *     ),
     *  )
     */
    public function index()
    {
        $distributeurs = Distributeur::all();
        return $distributeurs;
    }


    /**
     * @SWG\Post(
     *     path="/distributeur",
     *     summary="Create a distributor",
     *     description="Use this method to create a distributor",
     *     operationId="createDistributor",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"distributeur"},
     *     @SWG\Parameter(
     *         description="Nom du distributeur",
     *         in="formData",
     *         name="nom",
     *         required=true,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Adresse du distributeur",
     *         in="formData",
     *         name="adresse",
     *         required=false,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Code postal du distributeur",
     *         in="formData",
     *         name="cpostal",
     *         required=false,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Ville du distributeur",
     *         in="formData",
     *         name="ville",
     *         required=false,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Pays du distributeur",
     *         in="formData",
     *         name="pays",
     *         required=false,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Parameter(
     *         description="Téléphone du distributeur",
     *         in="formData",
     *         name="telephone",
     *         required=true,
     *         type="string",
     *         maximum="255"
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Distributor created"
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Champs manquant obligatoire ou incorrect"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|unique:distributeurs|max:255',
            'telephone' => 'required|max:255'
        ]);

        if($validator->fails()){
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422);
        }

        $distributeur = new Distributeur;
        $distributeur->nom = $request->nom;
        $distributeur->adresse = $request->adresse;
        $distributeur->cpostal = $request->cpostal;
        $distributeur->ville = $request->ville;
        $distributeur->pays = $request->pays;
        $distributeur->telephone = $request->telephone;
        $distributeur->save();

        return response()->json(
            $distributeur,
            201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
