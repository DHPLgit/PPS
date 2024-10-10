<?php

namespace App\Models;

use App\Libraries\EnumsAndConstants\Stock;
use CodeIgniter\Model;

class StockModel extends Model
{
  protected $DBGroup          = 'default';
  protected $table            = 'pps_stock_list';
  protected $primaryKey       = 'stock_list_id';
  protected $useAutoIncrement = true;
  protected $returnType       = 'array';
  protected $useSoftDeletes   = false;
  protected $protectFields    = true;
  protected $allowedFields    = ['stock_id', 'parent_id', 'active_status', 'colour', 'length', 'texture', 'ext_size', 'unit', 'quantity', 'type', 'status', 'date', 'in', 'out', 'delete_status', 'created_by', 'updated_by'];

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

  public function GetStockList($perPage, $offset, $query=null)
  {
    $condition = [Stock::ActiveStatus => "1"];

    if ($query) {
      $colour = [Stock::Colour => $query];
      $length = [Stock::Length => $query];
      $query = $this->like(Stock::StockId, $query, "after")->where($condition)->orWhere($colour)->orWhere($length);
    } else {
      $query = $this->where($condition);
    }
    $result[0] = $query->countAllResults(false);

    $result[1] = $query->findAll($perPage, $offset);

    return $result;
  }

  public function GetStockByCondition($data)
  {
    $result = $this->where($data)->findAll();

    return $result;
  }
  public function GetStockByIds($key, $idList)
  {
    $result = $this->wherein($key, $idList)->findAll();


    return $result;
  }

  public function UpdateStock($id, $data)
  {
    $result = $this->update($id, $data);

    return $result;
  }

  public function InsertStock($data)
  {
    $result = $this->insert($data);

    return $result;
  }

  public function DeleteStock($id)
  {
    $result = $this->delete($id);

    return $result;
  }
  public function FilterStock($query, $perPage, $offset)
  {

    $colour = [Stock::Colour => $query];
    $length = [Stock::Length => $query];
    $condition = [Stock::ActiveStatus => "1"];
    $query = $this->like(Stock::StockId, $query, "after")->where($condition)->orWhere($colour)->orWhere($length);

    $result[0] = $query->countAllResults(false);

    $stock =  $query->findAll($perPage, $offset);
    $result[1] = $stock;
    return $result;
  }
}
