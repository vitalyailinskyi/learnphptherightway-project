<?php

declare(strict_types = 1);

function getTransactionFiles(string $dirPath): array
{
    $files = [];

    foreach (scandir($dirPath) as $file) {
        if (is_dir($file)) {
            continue;
        }

        if (pathinfo($file)['extension'] === 'csv') {
            $files[] = $dirPath . $file;
            // echo $file . "<br/>";
        }
    }

    return $files;
}

function getTransactions(string $fileName, ?callable $transactionHandler = null): array
{
    if (! file_exists($fileName)) {
        trigger_error('File "' . $fileName . '" does not exist.', E_USER_ERROR);
    }

    $handle = fopen($fileName, 'r'); // open file for r=Reading

    fgetcsv($handle); // Trick to hide first line of CSV with Date,Check #,Description,Amount

    $transactions = []; // Array for all transactions from csv-file

    while (($transaction = fgetcsv($handle)) !== false) {
        if ($transactionHandler !== null) {
            $transaction = $transactionHandler($transaction);
        }
        $transactions[] = $transaction; // Add lines from csv-file as new arrays into $transactions-array.
    }
    fclose($handle);
    return $transactions;
}

function extractTransaction(array $transactionRow): array
{
    [$date, $checkNumber, $description, $amount] = $transactionRow;

    $amount = (float) str_replace(['$', ','], '', $amount);

    return [
        'date'        => $date,
        'checkNumber' => $checkNumber,
        'description' => $description,
        'amount'      => $amount,
    ];
}

/* IF CSV-files from another bank are formatter in other way  NOT IN: Date,Check #,Description,Amount
We write a new function below with its logic */
// function extractTransactionFromBankY(){

// }

function calculateTotals(array $transactions): array
{
    $totals = ['netTotal' => 0, 'totalIncome' => 0, 'totalExpense' => 0];

    foreach ($transactions as $transaction) {
        $totals['netTotal'] += $transaction['amount'];

        if ($transaction['amount'] >= 0) {
            $totals['totalIncome'] += $transaction['amount'];
        } else {
            $totals['totalExpense'] += $transaction['amount'];
        }
    }

    return $totals;
}
