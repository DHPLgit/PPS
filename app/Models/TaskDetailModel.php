<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\EnumsAndConstants\ModelNames;
use App\Libraries\EnumsAndConstants\TaskDetail;


require_once APPPATH . 'Libraries/EnumsAndConstants/Enums.php';
class TaskDetailModel extends Model
{


    protected $DBGroup = 'default';
    protected $table = 'pps_task_detail';
    protected $primaryKey = 'task_detail_id ';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        "task_name",
        "time_taken",
        "supervisor",
        "dept_id",
        "days_taken",
        "quality_analyst",
        "parent_task"
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

    public function GetAllTaskDetails()
    {
        $result = $this->orderBy(TaskDetail::ParentTask)->findAll();



        return $result;
    }

    public function InsertTaskDetail($data)
    {
        $insertId = $this->insert($data);
        return $insertId;
    }

    public function GetTaskDetail($condition)
    {
        $result = $this->where($condition)->findAll();


        return $result;
    }
    public function GetTaskDetailByIds($key,$idList)
    {
        $result = $this->wherein($key, $idList)->findAll();


        return $result;
    }

    public function DeleteTaskDetail($id)
    {
        $result = $this->delete($id);


        return $result;
    }

    public function UpdateTaskDetail($id,$data)
    {
        $result = $this->update($id,$data);


        return $result;
    }

    public function GetParentTaskDetailList(){


        $taskDetailList = $this->whereNotIn(TaskDetail::OrderId, array(0))->orderBy(TaskDetail::OrderId, "asc")->findAll();

        return $taskDetailList;
    }
}
