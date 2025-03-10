<?php

namespace App\Models;

use App\Libraries\EnumsAndConstants\Order;
use App\Libraries\EnumsAndConstants\WorkStatus;
use CodeIgniter\Model;

class OrderModel extends Model
{
  protected $DBGroup          = 'default';
  protected $table            = 'pps_order';
  protected $primaryKey       = 'order_id';
  protected $useAutoIncrement = false;
  protected $returnType       = 'array';
  protected $useSoftDeletes   = false;
  protected $protectFields    = true;
  protected $allowedFields    = ['order_id','customer_id', 'order_date', 'status', 'completion_percentage', 'due_date', 'overdue', 'created_by', 'updated_by'];

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

  public function GetOrder($condition)
  {

    $result =  $this->where($condition)->first();
    return $result;
  }
  public function GetOrders($select, $perPage, $offset, $condition = null)
  {
    if (!$condition) {
      $query = $this->select($select)->where(Order::Status . "!=", WorkStatus::C)->orderBy(Order::OrderId);
    }
    else{
    $query =  $this->select($select)->where(Order::Status . "!=", WorkStatus::C)->like(Order::OrderId, $condition, "after"); //->orLike(Order::ReferenceId, $condition, "after");

    }
    $result[0] =  $query->countAllResults(false);

    $result[1] =  $query->findAll($perPage, $offset);
    return $result;
  }
  public function FilterOrder($select, $condition, $perPage, $offset)
  {

    $query =  $this->select(select: $select)->where(Order::Status . "!=", WorkStatus::C)->like(Order::OrderId, $condition, "after"); //->orLike(Order::ReferenceId, $condition, "after");
    $result[0] =  $query->countAllResults(false);

    $result[1] =  $query->findAll($perPage, $offset);

    return $result;
  }
}
