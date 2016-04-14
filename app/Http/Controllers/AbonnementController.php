<?php

namespace App\Http\Controllers;

use App\Abonnement;
use Illuminate\Http\Request;

use App\Http\Requests;

class AbonnementController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/abonnement",
     *     summary="Get subscription list",
     *     description="Use this method to return a listing of subscriptions.",
     *     operationId="listSubscription",
     *     tags={"abonnement"},
     *     @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Abonnement")
     *          ),
     *     ),
     *     @SWG\Response(
     *         response=204,
     *         description="The request didn't return any content.",
     *     ),
     * )
     */
    public function index()
    {
        $abonnements = Abonnement::all();
        return $abonnements;
    }


    /**
     * @SWG\Post(
     *     path="/abonnement",
     *     summary="Create a subscription",
     *     description="Use this method to create a new subscription.",
     *     operationId="createSubscription",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"abonnement"},
     *     @SWG\Parameter(
     *         description="Id of the forfait",
     *         in="formData",
     *         name="id_forfait",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="Initial date of the subscription",
     *         in="formData",
     *         name="debut",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response=403,
     *         description="You don't have authorization for this content"
     *     ),
     *     @SWG\Response(
     *          response=201,
     *          description="Subscription created",
     *          @SWG\Schema(
     *               ref="#/definitions/Abonnement",
     *          ),
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Champs manquant obligatoire ou incorrect"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if ($user->type != 1){
            return response()->json(
                ['error' => 'You don\'t have authorization for this content'],
                403);
        } else {
            $validator = Validator::make($request->all(), [
                'id_forfait' => 'required|unique:forfaits',
                'debut' => 'required|date'
            ]);

            if($validator->fails()){
                return response()->json(
                    ['errors' => $validator->errors()->all()],
                    422);
            }

            $abonnement = new Abonnement;
            $abonnement->id_forfait = $request->id_forfait;
            $abonnement->debut = $request->debut;
            $abonnement->save();

            return response()->json(
                $abonnement,
                201);
        }
    }

    /**
     * @SWG\Get(
     *      path="/abonnement/{id_abonnement}",
     *      summary="Display a subscription",
     *      description="Use this method to return a single subscription attributes based on its id.",
     *      operationId="showSubscription",
     *      tags={"abonnement"},
     *      @SWG\Parameter(
     *          name="id_abonnement",
     *          in="path",
     *          type="integer",
     *          description="Id of subscription to fetch",
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *           @SWG\Schema(
     *               ref="#/definitions/Abonnement",
     *           ),
     *      ),
     *      @SWG\Response(
     *           response=404,
     *           description="Subscription not found"
     *       ),
     * )
     */
    public function show($id)
    {
        $abonnement = Abonnement::find($id);

        if(empty($abonnement)){
            return response()->json(
                ['error' => 'This subscription does not exist'],
                404);
        }
        return $abonnement;
    }

    /**
     * @SWG\Put(
     *     path="/abonnement/{id_abonnement}",
     *     summary="Update a subscription",
     *     description="Use this method to update the attributes of a subscription based on its id.",
     *     operationId="updateSubscription",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"abonnement"},
     *     @SWG\Parameter(
     *         description="Name of the subscription",
     *         in="formData",
     *         name="id_forfait",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="Start date of the subscription",
     *         in="formData",
     *         name="debut",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response=403,
     *         description="You don't have authorization for this content"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Subscription updated",
     *         @SWG\Schema(
     *              ref="#/definitions/Abonnement",
     *         ),
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Champs manquants obligatoires ou incorrects"
     *     ),
     * )
     */
    public function update(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if ($user->type != 1){
            return response()->json(
                ['error' => 'You don\'t have authorization for this content'],
                403);
        } else {
            $validator = Validator::make($request->all(), [
                'id_forfait' => 'unique:forfaits',
                'debut' => 'date'
            ]);

            if ($validator->fails()) {
                return response()->json(
                    ['errors' => $validator->errors()->all()],
                    422);
            }

            $abonnement = Abonnement::find($id);
            $abonnement->id_forfait = $request->id_forfait;
            $abonnement->debut = $request->debut;
            $abonnement->save();

            return response()->json(
                $abonnement,
                200);
        }
    }

    /**
     * @SWG\Delete(
     *     path="/abonnement/{id_abonnement}",
     *     summary="Delete a subscription",
     *     description="Use this method to delete a subscription based on its id.",
     *     operationId="deleteSubscription",
     *     tags={"abonnement"},
     *     @SWG\Parameter(
     *         description="Subscription id to delete",
     *         in="path",
     *         name="id_abonnement",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=403,
     *         description="You don't have authorization for this content"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Subscription deleted"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Invalid subscription value"
     *     )
     *
     * )
     */
    public function destroy($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if ($user->type != 1){
            return response()->json(
                ['error' => 'You don\'t have authorization for this content'],
                403);
        } else {
            $abonnement = Abonnement::find($id);

            if (empty($abonnement)) {
                return response()->json(
                    ['error' => 'this subscription does not exist'],
                    404);
            }

            $abonnement->delete();
        }
    }
}
