<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Http\Controllers\API\DataSetDetailController;

class FetchAndStoreDataSetDetailSeeder extends Seeder
{
    public function run()
    {
        // Create an instance of the controller
        $controller = new DataSetDetailController();

        // Call the fetchAndStoreDataSetDetail method
        $response = $controller->fetchAndStoreDataSetDetail();

        // You can log or handle the response as needed
        // For example, you can log the results or perform other actions.
        // If you want to log the response, you can use the Laravel logger.

        // Log the response
        // logger()->info(json_encode($response));

        // If you want to check if the response was successful, you can use the following:
        // if ($response->getStatusCode() === 200) {
        //     // Handle success
        // } else {
        //     // Handle failure
        // }
    }
}