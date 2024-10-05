<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Journal;
use App\Models\JournalLineItem;
use App\Models\ThirdParty;
use App\Models\TransactionType;
use App\Models\Account;
use App\Models\Currency;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;

class ImportOldData extends Command
{
    protected $signature = 'import:old-data';
    protected $description = 'Import old data from Excel files into the database';

    public function handle()
    {
        ini_set('memory_limit', '4G'); // Increase memory limit to 2GB

        DB::enableQueryLog(); // Enable query logging

        $this->info("Starting import...");

        try {
            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            $this->info("Importing Currencies...");
            $this->importCurrencies();

            $this->info("Importing Accounts...");
            $this->importAccounts();


            $this->info("Importing Transaction Types...");
            $this->importTransactionTypes();

            $this->info("Importing Third Parties...");
            $this->importThirdParties();

            $this->info("Importing Journals...");
            $this->importJournals();

            $this->info("Importing Journal Line Items...");
            $this->importJournalLineItems();

            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // Output all the queries that were run
            dd(DB::getQueryLog());

            $this->info('Data imported successfully!');
        } catch (\Exception $e) {
            $this->error('Failed to import data: ' . $e->getMessage());
            $this->error('Trace: ' . $e->getTraceAsString());
        }
    }

    protected function importTransactionTypes()
    {
        $path = storage_path('app/public/dbo.SAcTransType.xlsx');
        $data = $this->readExcel($path);

        foreach ($data as $row) {
            try {
                TransactionType::updateOrCreate(
                    ['trans_code' => $row['TransCode']],
                    [
                        'description' => $row['Description'],
                        'show_in_receivable' => $this->cleanNumericData($row['ShowInReceivable']),
                        'show_in_payable' => $row['ShowInPayable'],
                        'created_by' => 1,
                    ]
                );
            } catch (\Exception $e) {
                $this->error("Failed to import Transaction Type: " . $row['TransCode'] . ". Error: " . $e->getMessage());
            }
        }
    }
    protected function importJournals()
    {
        $path = storage_path('app/public/dbo.AcJournal1ES.xlsx');
        $data = $this->readExcelInChunks($path);

        foreach ($data as $row) {
            $type = TransactionType::where('trans_code', $row['TransCode'])->first();

            if (!$type) {
                $this->error("Transaction Type not found for TransCode: " . $row['TransCode']);
                continue;
            }

            try {
                $journal = Journal::updateOrCreate(
                    ['trans_id' => $row['TransId']],
                    [
                        'trans_code' => $row['TransCode'],
                        'type_id' => $type->id,
                        'manual_ref' => $row['ManualRef'],
                        'trans_date' => $this->cleanDate($row['TransDate']),
                        'activation_date' => $this->cleanDate($row['ActivationDate']),
                        'locked' => $row['Locked'] ?? false,
                        'status' => $row['Status'] ?? 0,
                        'parent_id' => null,
                        'created_by' => 1,
                    ]
                );
                $this->info("Successfully imported Journal for TransId: " . $row['TransId']);
            } catch (\Exception $e) {
                $this->error("Failed to import Journal: " . $row['TransId'] . ". Error: " . $e->getMessage());
            }
        }
    }

    protected function importAccounts()
    {
        $path = storage_path('app/public/dbo.SAcAccount.xlsx');
        $data = $this->readExcel($path);

        foreach ($data as $row) {
            try {
                Account::updateOrCreate(
                    ['account_code' => $row['AccCode']],  // Use 'AccCode' instead of 'AccountCode'
                    [
                        'account_name' => $row['Description'],  // Assuming 'Description' is the account name
                        'account_type' => $row['Type'],  // Assuming 'Type' is the account type
                        'currency_code' => $row['CurrencyCode'],  // Match with 'currency_code' in migration
                        'is_active' => true,  // You can adjust this logic as needed
                        'parent_id' => null,  // Handle hierarchical structure if relevant
                    ]
                );
            } catch (\Exception $e) {
                $this->error("Failed to import Account: " . $row['AccCode'] . ". Error: " . $e->getMessage());
            }
        }
    }

    protected function importCurrencies()
    {
        $path = storage_path('app/public/dbo.SAcCurrency.xlsx');
        $data = $this->readExcel($path);

        foreach ($data as $index => $row) {
            try {
                // Log the row number and data being processed
                $this->info("Processing row {$index}: " . json_encode($row));

                // Check if 'CurCode' exists in the row
                if (!isset($row['CurCode'])) {
                    $this->error("Missing 'CurCode' in row {$index}: " . json_encode($row));
                    continue;
                }

                // Clean and prepare the exchange rate value
                $exchangeRate = $this->cleanNumericData($row['decimals'] ?? 1);
                if (!is_numeric($exchangeRate)) {
                    $this->error("Invalid exchange rate for 'CurCode' {$row['CurCode']} in row {$index}: " . json_encode($row));
                    continue;
                }

                // Attempt to update or create the currency record
                $currency = Currency::updateOrCreate(
                    ['currency_code' => $row['CurCode']], // Use 'CurCode' here
                    [
                        'currency_name' => $row['description'] ?? 'Unknown', // Use 'description' for currency name
                        'exchange_rate' => $exchangeRate,
                    ]
                );

                // Log successful creation or update
                $this->info("Successfully processed 'CurCode' {$row['CurCode']}.");
            } catch (\Exception $e) {
                // Log the error with row details
                $this->error("Failed to import Currency: " . ($row['CurCode'] ?? 'Unknown') . ". Error in row {$index}: " . $e->getMessage());
            }
        }
    }


    protected function importJournalLineItems()
    {
        $path = storage_path('app/public/dbo.AcJournalB1ES.xlsx');
        foreach ($this->readExcelInChunks($path) as $row) {
            $journal = Journal::where('trans_id', $row['TransId'])->first();  // Use trans_id instead of id

            if (!$journal) {
                $this->error("Journal not found for TransId: " . $row['TransId']);
                continue;
            }

            try {
                JournalLineItem::updateOrCreate(
                    ['trans_id' => $journal->trans_id, 'ligne_id' => $row['LigneId']],  // Use trans_id here
                    [
                        'account_code' => $row['AccCode'],
                        'dc_indicator' => $row['DC'],
                        'amount' => $this->cleanNumericData($row['Amount']),
                        'third_party_id' => ThirdParty::where('id', $this->cleanNumericData($row['ThirdId']))->first()->id ?? null,
                        'created_by' => 1,
                    ]
                );
            } catch (\Exception $e) {
                $this->error("Failed to import Journal Line Item for TransId: " . $row['TransId'] . ". Error: " . $e->getMessage());
            }
        }
    }

    protected function importThirdParties()
    {
        $path = storage_path('app/public/dbo.SAcThirdParty.xlsx');
        $data = $this->readExcelInChunks($path);

        foreach ($data as $row) {
            try {
                ThirdParty::updateOrCreate(
                    ['name' => $row['Name']],
                    [
                        'address' => $row['Address'],
                        'phone' => $row['Phone'] ?? null,
                        'email' => $row['Email'] ?? null,
                        'created_by' => 1,
                    ]
                );
            } catch (\Exception $e) {
                $this->error("Failed to import Third Party: " . $row['Name'] . ". Error: " . $e->getMessage());
            }
        }
    }

    protected function cleanNumericData($value)
    {
        return preg_replace('/[^\d.]/', '', $value);
    }

    protected function cleanDate($date)
    {
        try {
            // Use a regular expression to capture only the date part in the format dd/mm/yyyy
            if (preg_match('/\d{2}\/\d{2}\/\d{4}/', $date, $matches)) {
                // Parse the captured date string
                return Carbon::createFromFormat('d/m/Y', $matches[0])->toDateTimeString();
            } else {
                // Log the error if the date doesn't match the expected pattern
                $this->error("Failed to convert date: $date");
                return null;
            }
        } catch (\Exception $e) {
            $this->error("Failed to convert date: $date");
            return null;
        }
    }



    protected function readExcel($path)
    {
        if (!file_exists($path) || !is_readable($path)) {
            $this->error("Excel file not found or is not readable: $path");
            return [];
        }

        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray(null, true, true, true);

        $header = array_shift($data);
        $result = [];

        foreach ($data as $row) {
            if (count($header) !== count($row)) {
                continue;
            }
            $result[] = array_combine($header, $row);
        }

        return $result;
    }

    protected function readExcelInChunks($path, $chunkSize = 50) // Smaller chunk size
    {
        if (!file_exists($path) || !is_readable($path)) {
            $this->error("Excel file not found or is not readable: $path");
            return [];
        }

        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestRow();
        $header = $sheet->rangeToArray('A1:' . $sheet->getHighestColumn() . '1', null, true, true, true)[1];

        for ($startRow = 2; $startRow <= $highestRow; $startRow += $chunkSize) {
            $endRow = min($startRow + $chunkSize - 1, $highestRow);
            $data = $sheet->rangeToArray("A$startRow:" . $sheet->getHighestColumn() . $endRow, null, true, true, true);

            foreach ($data as $row) {
                if (count($header) !== count($row)) {
                    $this->error("Row number $startRow has a column mismatch. Skipping this row.");
                    continue;
                }
                yield array_combine($header, $row);
            }
        }

        // Explicitly clear memory after processing
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
    }
}
