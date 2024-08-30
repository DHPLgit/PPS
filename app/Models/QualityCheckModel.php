<?php

namespace App\Models;

use CodeIgniter\Model;

class QualityCheckModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'pps_qc';
    protected $primaryKey       = 'qc_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        "qc_name",
        "description",

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

    public function GetAllQC()
    {
        $result = $this->findAll();

        return $result;
    }

    public function GetQCByIdList($key, $idArr)
    {
        $result = $this->whereIn($key, $idArr)->findAll();

        return $result;
    }
}
