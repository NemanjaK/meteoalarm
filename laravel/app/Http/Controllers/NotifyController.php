<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Repository\AlertQueueRepository;
use App\Repository\Entity\AlertQueueItem;
use App\Repository\SubscriberRepository;
use Illuminate\Http\Response;

class NotifyController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $repository = AlertQueueRepository::getInstance();
        $subscriberRepository = SubscriberRepository::getInstance();
        // Get subscriber
        $subscriber = $subscriberRepository->getByUuid($id);
        if (empty($subscriber))
            return response()->json(['message' => 'Unknown subscriber'], Response::HTTP_BAD_REQUEST);

        $messages = $repository->getForSubscriber($subscriber);
        if (empty($messages))
            return response()->json([], Response::HTTP_NO_CONTENT);

        /** @var AlertQueueItem $message */
        foreach ($messages as $message) {
            $message->setNotified(true);
            $message->save();
        }

        return response()->json($messages);
    }

}
