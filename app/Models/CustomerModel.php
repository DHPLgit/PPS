<?php

namespace App\Models;

use App\Libraries\EnumsAndConstants\Employee;
use CodeIgniter\Model;

class CustomerModel extends Model
{
  protected $DBGroup = 'default';
  protected $table = 'pps_customer';
  protected $primaryKey = 'customer_id';
  protected $useAutoIncrement = true;
  protected $returnType = 'array';
  protected $useSoftDeletes = false;
  protected $protectFields = true;
  protected $allowedFields = [
    "customer_name",
    "status",
    "updated_by",
    "created_by"
  ];

  // Dates
  protected $useTimestamps = true;
  protected $dateFormat = 'datetime';
  protected $createdField = 'created_at';
  protected $updatedField = 'updated_at';
  protected $deletedField = 'deleted_at';

  // Validation
  protected $validationRules = [];
  protected $validationMessages = [];
  protected $skipValidation = false;
  protected $cleanValidationRules = true;

  // Callbacks
  protected $allowCallbacks = true;
  protected $beforeInsert = [];
  protected $afterInsert = [];
  protected $beforeUpdate = [];
  protected $afterUpdate = [];
  protected $beforeFind = [];
  protected $afterFind = [];
  protected $beforeDelete = [];
  protected $afterDelete = [];

  public function GetEmployee($condition)
  {
    $result = $this->where($condition)->findAll();


    return $result;
  }
  public function GetAllEmployee()
  {
    $condition = [Employee::Status => "1"];
    $result = $this->where($condition)->findAll();


    return $result;
  }
  public function UpdateEmployee($id, $data)
  {
    $result = $this->update($id, $data);

    return $result;
  }

  public function DeleteEmployee($id)
  {
    $result = $this->delete($id);

    return $result;
  }

  public function GetEmployeeByIds($key, $idList)
  {
    $result = $this->whereIn($key, $idList)->findAll();

    return $result;
  }
}
