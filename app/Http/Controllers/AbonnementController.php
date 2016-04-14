<?php

namespace App\Http\Controllers;

use App\Abonnement;
use Illuminate\Http\Request;

use App\Http\Requests;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

class AbonnementController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/abonnement",
     *     summary="Get subscription list",
     *     description="Use this method to return a listing of subscriptions.",
     *     operationId="indexAbonnement",
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
        if ($abonnements->isEmpty()) {
            return response()->json("The request didn't return any content.", 204);
        }
        return $abonnements;
    }


    /**
     * @SWG\Post(
     *     path="/abonnement",
     *     summary="Create a subscription",
     *     description="Use this method to create a new subscription.",
     *     operationId="storeAbonnement",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"abonnement"},
     *     @SWG\Parameter(
     *         description="Forfait ID",
     *         in="formData",
     *         name="id_forfait",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="Initial date",
     *         in="formData",
     *         name="debut",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response=403,
     *         description="You're not allowed to access this service."
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
     *         description="Missing or incorrect fields"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if ($user->type != 1){
            return response()->json(
                ['error' => 'You\'re not allowed to access this service.'],
                403);
        } else {
            $validator = Validator::make($request->all(), [
                'id_forfait' => 'required|exists:forfaits|numeric',
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
     *      operationId="showAbonnement",
     *      tags={"abonnement"},
     *      @SWG\Parameter(
     *          name="id_abonnement",
     *          in="path",
     *          type="integer",
     *          required=true,
     *          description="Subscription ID",
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
                ['error' => 'Subscription not found'],
                404);
        }
        return $abonnement;
    }

    /**
     * @SWG\Put(
     *     path="/abonnement/{id_abonnement}",
     *     summary="Update a subscription",
     *     description="Use this method to update the attributes of a subscription based on its id.",
     *     operationId="updateAbonnement",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"abonnement"},
     *     @SWG\Parameter(
     *         description="Subscription ID",
     *         in="path",
     *         name="id_abonnement",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="Forfait ID",
     *         in="formData",
     *         name="id_forfait",
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="Starting date",
     *         in="formData",
     *         name="debut",
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response=403,
     *         description="You're not allowed to access this service."
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
     *         description="Missing or incorrect fields"
     *     ),
     * )
     */
    public function update(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if ($user->type != 1){
            return response()->json(
                ['error' => 'You\'re not allowed to access this service.'],
                403);
        } else {
            $validator = Validator::make($request->all(), [
                'id_forfait' => 'numeric',
                'debut' => 'date'
            ]);

            if ($validator->fails()) {
                return response()->json(
                    ['errors' => $validator->errors()->all()],
                    422);
            }

            $abonnement = Abonnement::find($id);
            $abonnement->id_forfait = $request->id_forfait != null ? $request->id_forfait : $abonnement->id_forfait;
            $abonnement->debut = $request->debut != null ? $request->debut : $abonnement->debut;
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
     *     operationId="destroyAbonnement",
     *     tags={"abonnement"},
     *     @SWG\Parameter(
     *         description="Subscription ID",
     *         in="path",
     *         name="id_abonnement",
     *         required=true,
     *         type="integer",
     *         format="int64"
     *     ),
     *     @SWG\Response(
     *         response=403,
     *         description="You're not allowed to access this service."
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Subscription deleted"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Subscription not found"
     *     )
     *
     * )
     */
    public function destroy($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if ($user->type != 1){
            return response()->json(
                ['error' => 'You\'re not allowed to access this service.'],
                403);
        } else {
            $abonnement = Abonnement::find($id);

            if (empty($abonnement)) {
                return response()->json(
                    ['error' => 'Subscription not found'],
                    404);
            }

            $abonnement->delete();

            return response()->json(
                'Successfully deleted',
                200);
        }
    }
}
