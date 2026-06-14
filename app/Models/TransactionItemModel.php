<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionItemModel extends Model
{
    protected $table = 'transaction_items';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $allowedFields = [
        'transaction_id',
        'product_id',
        'product_name',
        'price',
        'qty',
        'subtotal',
    ];

    protected $useTimestamps = false;
}
