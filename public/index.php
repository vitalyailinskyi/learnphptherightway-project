<?php

declare(strict_types = 1);

$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;

define('APP_PATH', $root . 'app' . DIRECTORY_SEPARATOR);
define('FILES_PATH', $root . 'transaction_files' . DIRECTORY_SEPARATOR);
define('VIEWS_PATH', $root . 'views' . DIRECTORY_SEPARATOR);

require APP_PATH . "App.php";
require APP_PATH . "helpers.php";

$files = getTransactionFiles(FILES_PATH);

$transactions = [];
foreach($files as $file) {
    /** !! Don't assign $transactions[] to array_merge, only to $transactions,
     * cause it creates new array inside array, and duplicates old + new array */
    $transactions = array_merge($transactions, getTransactions($file, 'extractTransaction'));
}

$totals = calculateTotals($transactions);

require VIEWS_PATH . 'transactions.php';

/* IF CSV-files from another bank are formatter in other way NOT IN: Date,Check #,Description,Amount*/
// $files = getTransactionFiles(FILES_OTHER_PATH);

// foreach($files as $file) {
//     $transactions = array_merge($transactions, getTransactions($file, 'extractTransactionFromBankY'));
// }