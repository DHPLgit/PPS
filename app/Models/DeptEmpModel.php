<?php

namespace App\Models;

use CodeIgniter\Model;

class DeptEmpModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'pps_dept_emp_map';
    protected $primaryKey       = 'dept_emp_map_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        "dept_id",
        "employee_ids",
        "supervisor_id",
        "status"

    ];

    // Dates
    protected $useTimestamps = true;
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

    public function InsertDeptEmpMap($data)
    {
      $result= $this->insert($data);

      return $result;
    }

    public function GetDeptEmpMap($condition)
    {
      $result= $this->where($condition)->findAll();

      return $result;
    }
}
