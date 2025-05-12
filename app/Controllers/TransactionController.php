<?php

namespace App\Controllers;

use App\Models\Transaction;
use App\View;

require_once __DIR__ . '/../Helpers/TransactionFormatter.php';

class TransactionController
{
    public array $transactions = [];
    public array $totals = [];

    public function upload(): void
    {
        session_start();
        $addedEntriesCount = 0;

        $files = $_FILES['receipt'];

        if(!$files['name'][0] == null)
        {
            if($files['size'] > 0)
            {
                foreach($files['tmp_name'] as $file) {
                    /** !! Don't assign $transactions[] to array_merge, only to $transactions,
                    * cause it creates new array inside array, and duplicates old + new array */
                    $this->transactions = array_merge($this->transactions, $this->getTransactions($file));
                }

                $tr = new Transaction();

                $tr->add($this->transactions);

                $addedEntriesCount = count($this->transactions);

                $_SESSION['flash'] = "✅ $addedEntriesCount transactions uploaded successfully.";

                header("Location: /transactions");
                exit;

            } else {
                echo("⚠ Please upload non-empty file");
            }
        } else {
            echo("⚠ Please upload any file");
        }
    }

    function getTransactions(string $fileName): array
    {
        if (! file_exists($fileName)) {
            trigger_error('File "' . $fileName . '" does not exist.', E_USER_ERROR);
        }

        $handle = fopen($fileName, 'r'); // open file for r=Reading

        fgetcsv($handle); // Trick to hide first line of CSV with Date,Check #,Description,Amount

        $transactions = []; // Array for all transactions from csv-file

        while (($transaction = fgetcsv($handle)) !== false) {

            $transaction = $this->extractTransaction($transaction);

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

    public function index(): View {
        session_start();

        $tr = new Transaction();
        $this->transactions = $tr->getAllTransactions();
        $this->totals = $this->calculateTotals($this->transactions);

        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']); // This need for shop message only once

        return View::make('transactions', [
            'transactions' => $this->transactions,
            'totals' => $this->totals,
            'flash' => $flash
        ]);
    }
}