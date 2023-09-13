<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DataSetDetail;
use App\Models\DataSetElementDetail;
use Illuminate\Support\Facades\Http;

class DataSetElementDetailController extends Controller
{
    public function fetchAndStoreDataElementsUnderDataSet(Request $request)
    {
        // try {
        // Check if dataSetId is provided in the request parameters
        // $dataSetId = $request->input('dataSetId');
        $dataSetId = $request->query('dataSetId');


        if ($dataSetId) {
            // When dataSetId is set in the request
            $apiEndpoint = "https://hmis-training.health.go.ug/api/dataSets/$dataSetId.json?fields=id,name,periodType,dataSetElements[dataElement[id,code,name,aggregationType,domainType,displayName,description,valueType,dimensionItem,dimensionItemType,categoryCombo,dataSetElements]]";

            $existingDataElements = [];
            $newDataElements = [];

            // Make the API call with basic authentication
            $response = Http::withBasicAuth('api-test.uhf', 'TestMRDRs@2024')->get($apiEndpoint);

            // Check if the API call was successful
            if (!$response->successful()) {
                return response()->json(['message' => 'Failed to fetch data from the API'], 500);
            }

            $dataSetId = $response->json()['id'];
            $DataSetDetail = DataSetDetail::where('id', $dataSetId)->get();
            if ($DataSetDetail->isEmpty()) {
                return response()->json(["message" => "please first create Data Set Details"]);
            }

            // Extract dataSetElements and dataElements
            $dataSetElements = $response->json()['dataSetElements'];

            foreach ($dataSetElements as $dataSetElement) {

                $dataSetElement =  $dataSetElement['dataElement'];
                // Check if the data element already exists in the database
                $existingDataElement = DataSetElementDetail::find($dataSetElement['id']);

                // Prepare data for insertion
                $data = [
                    'id' => $dataSetElement['id'],
                    'code' => $dataSetElement['code'],
                    'name' => $dataSetElement['name'],
                    'aggregationType' => $dataSetElement['aggregationType'],
                    'description' => $dataSetElement['description'],
                    'valueType' => $dataSetElement['valueType'],
                    'dimensionItem' => $dataSetElement['dimensionItem'],
                    'dimensionItemType' => $dataSetElement['dimensionItemType'],
                    'categoryComboId' => $dataSetElement['categoryCombo']['id'] ?? null,
                    'dataSetId' => $dataSetId,
                ];

                // Store or update the data element based on existence
                if ($existingDataElement) {
                    // $existingDataElement->update($data);
                    $existingDataElements[] = $data;
                } else {
                    DataSetElementDetail::create($data);
                    $newDataElements[] = $data;
                }
            }

            $message = 'Data elements stored successfully';
            $result = [
                'message' => $message,
                'newDataElements' => $newDataElements,
                'existingDataElements' => $existingDataElements,
            ];

            return response()->json($result, 200);
        } else {
            // When dataSetId is not set in the request
            $apiEndpoint = "https://hmis-training.health.go.ug/api/dataSets.json?fields=id,name,periodType,dataSetElements[dataElement[id,code,name,aggregationType,domainType,displayName,description,valueType,dimensionItem,dimensionItemType,categoryCombo,dataSetElements]]";

            $username = 'api-test.uhf';
            $password = 'TestMRDRs@2024';

            // Make the API call with basic authentication
            $response = Http::withBasicAuth($username, $password)->get($apiEndpoint);

            // Check if the API call was successful
            if (!$response->successful()) {
                return response()->json(['message' => 'Failed to fetch data from the API'], 500);
            }

            $dataSets = $response->json()['dataSets'];
            $existingDataElements = [];
            $newDataElements = [];

            foreach ($dataSets as $apiDataSet) {

                $DataSetDetail = DataSetDetail::where('id', $apiDataSet['id'])->get();
                if ($DataSetDetail->isEmpty()) {
                    return response()->json(["message" => "please first create Data Set Details"]);
                }
                // Extract dataSetElements and dataElements
                $dataSetElements = $apiDataSet['dataSetElements'];

                foreach ($dataSetElements as $dataSetElement) {
                    $dataSetElement = $dataSetElement['dataElement'];
                    // Check if the data element already exists in the database
                    $existingDataElement = DataSetElementDetail::find($dataSetElement['id']);

                    // Prepare data for insertion
                    $data = [
                        'id' => $dataSetElement['id'],
                        'code' => $dataSetElement['code'] ?? null,
                        'name' => $dataSetElement['name'] ?? null,
                        'aggregationType' => $dataSetElement['aggregationType'],
                        'description' => $dataSetElement['description'] ?? null,
                        'valueType' => $dataSetElement['valueType'] ?? null,
                        'dimensionItem' => $dataSetElement['dimensionItem'] ?? null,
                        'dimensionItemType' => $dataSetElement['dimensionItemType'] ?? null,
                        'categoryComboId' => $dataSetElement['categoryCombo']['id'] ?? null,
                        'dataSetId' => $apiDataSet['id'],
                    ];

                    // Store or update the data element based on existence
                    if ($existingDataElement) {
                        // $existingDataElement->update($data);
                        $existingDataElements[] = $data;
                    } else {
                        DataSetElementDetail::create($data);
                        $newDataElements[] = $data;
                    }
                }
            }

            $message = 'Data elements stored successfully';
            $result = [
                'message' => $message,
                'newDataElements' => $newDataElements,
                'existingDataElements' => $existingDataElements,
            ];

            return response()->json($result, 200);
        }
        // } catch (\Exception $e) {
        //     return response()->json(['message' => $e->getMessage()], 500);
        // }
    }
}