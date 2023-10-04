<?php

namespace App\Console\Commands;

use App\Models\Product;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ImportProductsCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:productscsv {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import products data from a CSV file into the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file');

    // Check if the file exists
    if (!file_exists($filePath)) {
        $this->error("The specified file '{$filePath}' does not exist.");
        return;
    }

    // Read the CSV file using Laravel's built-in CSV reader
    $data = \Illuminate\Support\Facades\File::get($filePath);
    $csvData = array_map('str_getcsv', explode("\n", $data));
    array_shift($csvData);
    $count=0;
    // Process and import data into the database
    
    foreach ($csvData as $row) {
        $count++;
        // Process each row as needed
        // Example: Create a new model and save it to the database
        try{
            Product::create([
                'product_name' => $row[1],
                'price' => $row[2],
                'created_at'=>now(),
                'updated_at'=>now()
                // Add more columns as needed
            ]);
        }catch(Exception $error){
            Log::error($error);
        }
    }
    Log::info("Inserted users {$count} record(s) into the database using csv command line.");
    $this->info('CSV data imported successfully.');
    }
}
