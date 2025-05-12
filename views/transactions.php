<!DOCTYPE html>
<html>
    <head>
        <title>Transactions</title>
        <style>
            table {
                width: 100%;
                border-collapse: collapse;
                text-align: center;
            }

            table tr th, table tr td {
                padding: 5px;
                border: 1px #eee solid;
            }

            tfoot tr th, tfoot tr td {
                font-size: 20px;
            }

            tfoot tr th {
                text-align: right;
            }
            div.new-entities {
                padding: 10px;
                background-color: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
                margin-bottom: 20px;
            }
        </style>
    </head>
    <body>
        <?php if (!empty($flash)): ?>
            <div class="new-entities"">
                <?= $flash ?>
            </div>
        <?php endif; ?>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Check #</th>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php if (! empty($transactions)): ?>
                    <?php foreach($transactions as $transaction): ?>
                        <tr>
                            <td><?= formatDate($transaction['date']) ?></td>
                            <td><?= $transaction['check_number'] ?></td>
                            <td><?= $transaction['description'] ?></td>
                            <td>
                                <?php $amount = formatDollarAmount($transaction['amount']); ?>
                                <?php if ($transaction['amount'] < 0): ?>
                                    <span style="color: red;">
                                            <?= $amount ?>
                                        </span>
                                <?php elseif ($transaction['amount'] > 0) : ?>
                                    <span style="color: green;">
                                            <?= $amount ?>
                                        </span>
                                <?php else: ?>
                                    <?= $amount ?>
                                <?php endif ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php endif ?>
            </tbody><tfoot>
                <tr>
                    <th colspan="3">Total Income:</th>
                    <td><?= formatDollarAmount($totals['totalIncome'] ?? 0) ?></td>
                </tr>
                <tr>
                    <th colspan="3">Total Expense:</th>
                    <td><?= formatDollarAmount($totals['totalExpense'] ?? 0) ?></td>
                </tr>
                <tr>
                    <th colspan="3">Net Total:</th>
                    <td><?= formatDollarAmount($totals['netTotal'] ?? 0) ?></td>
                </tr>
            </tfoot>
        </table>
    </body>
</html>
