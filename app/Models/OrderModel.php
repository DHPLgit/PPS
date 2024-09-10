<?php

namespace App\Models;

use App\Libraries\EnumsAndConstants\Order;
use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'pps_order_list';
    protected $primaryKey       = 'order_list_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['order_id', 'item_id', 'reference_id', 'customer_id', 'order_date', 'type', 'colour', 'length', 'texture', 'ext_size', 'unit', 'bundle_count', 'quantity', 'status', 'due_date', 'overdue', 'created_by', 'updated_by'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function GetOrder($condition){

      $result=  $this->where($condition)->first();
      return $result;
    }

    public function FilterOrder($select,$condition)
    {

        // for ($i = 0; $i < count($condition); $i++) {
        //     # code...
        //     $this->select($select)->like($condition[$i]['key'], $condition[$i]['value'], $condition[$i]['side']);
        // }

        // $this->select($select)->like(Order::OrderId, $condition, "after")->orLike(Order::ReferenceId, $condition, "after");
        $this->select($select)->like(Order::OrderId, $condition, "after");
        
        $result =  $this->findAll();

        return $result;
    }
}
