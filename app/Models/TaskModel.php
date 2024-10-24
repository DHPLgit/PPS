<?php

namespace App\Models;

use App\Libraries\EnumsAndConstants\Order;
use App\Libraries\EnumsAndConstants\Task;
use CodeIgniter\Model;

class TaskModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'pps_task_list';
    protected $primaryKey       = 'task_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['task_id', 'is_qa', 'is_split', 'part', 'split_from', 'parent_task_id','sibling_id_list', 'order_list_id', 'order_id', 'item_id', 'employee_id', 'supervisor_id', 'start_time', 'end_time', 'time_taken', 'task_detail_id', 'sizing', 'out_length', 'out_texture', 'out_colour', 'out_qty', 'out_ext_size', 'out_type', 'status', 'next_task_id', 'created_by', 'updated_by'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'Created_at';
    protected $updatedField  = 'Updated_at';
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

    public function InsertTask($data)
    {
        $insertId = $this->insert($data);
        return $insertId;
    }

    public function GetTaskList($condition)
    {

        $result = $this->where($condition)->findAll();

        return $result;
    }

    public function GetTaskCount($condition)
    {

        $result = $this->select(Task::TaskDetailId)->selectCount(Task::TaskDetailId, "count")->where($condition)->groupBy(Task::TaskDetailId)->findAll();

        return $result;
    }

    public function GetTaskList1($where, $whereInKey,$whereInVal)
    {

        $result = $this->where($where)->whereIn($whereInKey,$whereInVal)->findAll();

        return $result;
    }
}
