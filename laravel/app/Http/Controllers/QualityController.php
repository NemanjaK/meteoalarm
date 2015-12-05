<?php

namespace App\Http\Controllers;

use App\Repository\ComponentRepository;
use App\Repository\Entity\Component;
use App\Repository\Entity\Station;
use App\Repository\MeasurementRepository;
use App\Repository\StationRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class QualityController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     * @internal param \Illuminate\Http\Request $request
     * @internal param int $id
     */
    public function index(Request $request)
    {
        // Get lat / lng
        $lat = $request->get('lat', false);
        $lng = $request->get('lng', false);

        if (!$lat || !$lng) {
            return response()->json(['message' => 'Both latitude and longitude are required.'], Response::HTTP_BAD_REQUEST);
        }

        if (!is_numeric($lat) || !is_numeric($lng)) {
            return response()->json(['message' => 'Both latitude and longitude must be double values.'], Response::HTTP_BAD_REQUEST);
        }

        $stationRepository = StationRepository::getInstance();
        $station = $stationRepository->getCaqiForLocation(doubleval($lat), doubleval($lng));
        if (!empty($station)) {
            // Get measurements for components
            $componentsRepository = ComponentRepository::getInstance();
            $measurementRepository = MeasurementRepository::getInstance();
            $components = $componentsRepository->getAll();
            $componentMeasurements = [];
            /** @var Component $component */
            foreach ($components as $component) {
                // Get latest measurement!
                $measurement = $measurementRepository->getLatestForStationAndComponent($station, $component);
                $componentMeasurements[$component->getSepaId()]['measurement'] = $measurement;
                $componentMeasurements[$component->getSepaId()]['component'] = $component;
            }

            $responseData = [
                'station' => $station,
                'components' => $componentMeasurements
            ];

            return response()->json($responseData);
        }

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

}
