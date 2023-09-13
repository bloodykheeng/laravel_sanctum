<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\API\DataSetElementDetailController;
use App\Models\DataSetDetail;
use App\Models\DataSetElementDetail;

class WeeklyHMIS033bDataElementsSeeder extends Seeder
{
    public function run()
    {
        // Check if dataSetId is set in the request
        $datasetId = 'C4oUitImBPK'; // Replace with the desired datasetId

        if ($datasetId) {
            // When dataSetId is set in the request
            $apiEndpoint = "https://hmis-training.health.go.ug/api/dataSets/$datasetId.json?fields=id,name,periodType,dataSetElements[dataElement[id,code,name,aggregationType,domainType,displayName,description,valueType,dimensionItem,dimensionItemType,categoryCombo,dataSetElements]]";

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
            // Handle the case when dataSetId is not set in the request
            return response()->json(['message' => 'DataSetId not provided in the request'], 400);
        }
    }
}