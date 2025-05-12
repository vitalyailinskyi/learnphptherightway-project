<?php

declare(strict_types=1);

namespace App\Models;

use App\Model;

class Transaction extends Model {

    public function __get(string $name)
    {
        $statement = $this->db->prepare("SELECT * FROM `transactions` WHERE `name` = :name");
    }

    public function getAllTransactions(): array
    {
        $statement = $this->db->prepare("SELECT * FROM `transactions` ORDER BY `id`");
        $statement->execute();
        return $statement->fetchAll();
    }

    public function add(array $transactions) : void
    {
        $statement = $this->db->prepare("INSERT INTO transactions (date, check_number, description, amount) VALUES (?, ?, ?, ?)");

        foreach ($transactions as $transaction) {
            $date = date('Y/m/d', strtotime($transaction['date']));
            $statement->execute([$date, $transaction['checkNumber'], $transaction['description'], $transaction['amount'],]);
        }
//        return $statement->fetchAll();
    }
}