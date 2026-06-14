<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $allowedFields = [
        'invoice_no',
        'user_id',
        'total_amount',
        'payment_amount',
        'change_amount',
        'note',
        'created_at',
    ];

    protected $useTimestamps = false;
}
