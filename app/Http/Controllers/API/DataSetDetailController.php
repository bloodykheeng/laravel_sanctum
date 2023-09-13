<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\DataSetDetail;
use App\Models\DataSet;
use Illuminate\Support\Facades\Http;

class DataSetDetailController extends Controller
{
    public function fetchAndStoreDataSetDetail()
    {

        try {
            // Define the API endpoint and authentication credentials
            $apiEndpoint = 'https://hmis-training.health.go.ug/api/dataSets.json?fields=id,name,dimensionItemType,dimensionItem,expiryDays,compulsoryFieldsCompleteOnly,formType,periodType,dataEntryForm,categoryCombo';
            $username = 'api-test.uhf';
            $password = 'TestMRDRs@2024';

            // Make the API call with basic authentication
            $response = Http::withBasicAuth($username, $password)->get($apiEndpoint);

            // Check if the API call was successful
            if ($response->successful()) {
                $apiDataSets = $response->json()['dataSets'];
                $result = [
                    'message' => 'Dataset stored successfully',
                    'newRows' => [],
                    'existingRows' => [],
                ];

                foreach ($apiDataSets as $apiDataSet) {
                    // Check if the dataset already exists in the database
                    $existingDataSet = DataSetDetail::find($apiDataSet['id']);

                    // Prepare data for insertion
                    $data = [
                        'id' => $apiDataSet['id'],
                        'name' => $apiDataSet['name'] ?? null,
                        'dimensionItemType' => $apiDataSet['dimensionItemType'] ?? null,
                        'dimensionItem' => $apiDataSet['dimensionItem'] ?? null,
                        'expiryDays' => $apiDataSet['expiryDays'] ?? null,
                        'compulsoryFieldsCompleteOnly' => $apiDataSet['compulsoryFieldsCompleteOnly'] ?? null,
                        'formType' => $apiDataSet['formType'] ?? null,
                        'periodType' => $apiDataSet['periodType'] ?? null,
                        'dataEntryFormId' => isset($apiDataSet['dataEntryForm']['id']) ? $apiDataSet['dataEntryForm']['id'] : null,
                        'categoryComboId' => isset($apiDataSet['categoryCombo']['id']) ? $apiDataSet['categoryCombo']['id'] : null,
                    ];

                    // Store or update the dataset based on existence
                    if ($existingDataSet) {
                        $existingDataSet->update($data);
                        $result['existingRows'][] = $data;
                    } else {
                        DataSetDetail::create($data);
                        $result['newRows'][] = $data;
                    }
                }

                return response()->json($result, 200);
            } else {
                return response()->json(['message' => 'Failed to fetch data from the API'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function showAllDatasetDetails()
    {

        $datasetDetails = DataSetDetail::all();
        return response()->json(["dataSetDetails" => $datasetDetails], 200);
    }


    public function showAllDatasetDetailsWithDataElementDetails()
    {
        $datasetId = request()->query('datasetId');

        if ($datasetId) {
            $datasetDetails = DataSetDetail::where('id', $datasetId)->with('dataElementDetails')->get();

            foreach ($datasetDetails as $datasetDetail) {
                $datasetDetail->dataElementsTotal = count($datasetDetail->dataElementDetails);
            }
        } else {
            $datasetDetails = DataSetDetail::with('dataElementDetails')->get();

            foreach ($datasetDetails as $datasetDetail) {
                $datasetDetail->dataElementsTotal = count($datasetDetail->dataElementDetails);
            }
        }

        return response()->json(["dataSetDetails" => $datasetDetails], 200);
    }
}