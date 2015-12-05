<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Repository\Entity\Subscriber;
use App\Repository\Exceptions\QueryException;
use App\Repository\SubscriberRepository;
use Illuminate\Http\Request;
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
            return response()->json(['message' => "Latitude must be a number."], Response::HTTP_BAD_REQUEST);

        if (!is_numeric($longitude))
            return response()->json(['message' => "Longitude must be a number."], Response::HTTP_BAD_REQUEST);

        // Check if user is already subscribed
        $repository = SubscriberRepository::getInstance();
        $subscriber = $repository->getByUuid($uuid);
        if (!empty($subscriber)) {
            return response()->json(['message' => "Already subscribed."], Response::HTTP_BAD_REQUEST);
        }

        $subscriber = new Subscriber();
        $subscriber->setUuid($uuid);
        $subscriber->setLatitude(doubleval($latitude));
        $subscriber->setLongitude(doubleval($longitude));
        try {
            $subscriber->save();
        } catch (QueryException $exception) {
            return response()->json(['message' => "Oops, something went wrong."], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([], Response::HTTP_CREATED);
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

    }
}
