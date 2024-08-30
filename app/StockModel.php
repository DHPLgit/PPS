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
  protected $allowedFields    = ['stock_id', 'parent_id', 'active_status', 'colour', 'length', 'texture', 'unit', 'quantity', 'type', 'status', 'date', 'in', 'out', 'delete_status', 'created_by', 'updated_by'];

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

  public function GetStockList()
  {
    $result = $this->findAll();

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
  public function FilterStock($query)
  {

    $colour = [Stock::Colour => $query];
    $length = [Stock::Length => $query];
    $result =  $this->like(Stock::StockId, $query, "after")->orWhere($colour)->orWhere($length)->findAll();
    return $result;
  }
}
