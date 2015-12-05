<?php

namespace App\Http\Controllers;

use App\Repository\Entity\Subscriber;
use App\Repository\Exceptions\QueryException;
use App\Repository\SubscriberRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class SubscribeController extends Controller
{

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function store(Request $request)
    {
        // Read subscriber details
        $uuid = $request->json('uuid');
        $latitude = $request->json('latitude');
        $longitude = $request->json('longitude');

        // Validate latitude/longitude
        if (!is_numeric($latitude))
            return response()->setStatusCode(Response::HTTP_BAD_REQUEST)->json(['message' => "Latitude must be a number."]);

        if (!is_numeric($longitude))
            return response()->setStatusCode(Response::HTTP_BAD_REQUEST)->json(['message' => "Longitude must be a number."]);

        // Check if user is already subscribed
        $repository = SubscriberRepository::getInstance();
        $subscriber = $repository->getByUuid($uuid);
        if (!empty($subscriber)) {
            return response()->setStatusCode(Response::HTTP_BAD_REQUEST)->json(['message' => "Already subscribed."]);
        }

        $subscriber = new Subscriber();
        $subscriber->setUuid($uuid);
        $subscriber->setLatitude(doubleval($latitude));
        $subscriber->setLongitude(doubleval($longitude));
        try {
            $subscriber->save();
        } catch (QueryException $exception) {
            return response()->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)->json(['message' => "Oops, something went wrong."]);
        }

        return response()->setStatusCode(Response::HTTP_CREATED)->json();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
