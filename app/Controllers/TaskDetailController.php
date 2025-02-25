<?php

namespace App\Controllers;

use App\Models\ModelHelper;
use App\Libraries\EnumsAndConstants\OrderItems;
use App\Libraries\EnumsAndConstants\TaskInput;
use App\Libraries\EnumsAndConstants\DeptEmpMap;
use App\Libraries\EnumsAndConstants\Department;
use App\Libraries\EnumsAndConstants\Employee;
use App\Libraries\EnumsAndConstants\Stock;
use App\Libraries\EnumsAndConstants\OrderStockMap;
use App\Models\ModelFactory;
use App\Libraries\EnumsAndConstants\ModelNames;
use App\Libraries\EnumsAndConstants\QualityCheck;
use App\Libraries\EnumsAndConstants\WorkStatus;
use App\Libraries\EnumsAndConstants\Task;
use App\Libraries\EnumsAndConstants\TaskDetail;
use App\Libraries\EnumsAndConstants\WtStd;
use App\Libraries\Response\Response;
use App\Libraries\Response\Error;
use Exception;
use CodeIgniter\Database\Exceptions\DatabaseException;
use PhpParser\Node\Stmt\ElseIf_;
use function PHPUnit\Framework\assertTrue;
require_once APPPATH . 'Libraries/EnumsAndConstants/Enums.php';
require_once APPPATH . 'Libraries/EnumsAndConstants/Constants.php';
class TaskDetailController extends BaseController
{
    public $modelHelper;

    public function __construct()
    {
        $this->modelHelper = new ModelHelper();
    }

    public function CreateTaskDetail()
    {
        try {
            if ($this->request->getMethod() == 'get') {
                $departments = $this->GetDepartments();
                $qcList = $this->GetQCList();
                return view('taskdetails', ["departmentList" => $departments, "qcList" => $qcList]);
            } elseif ($this->request->getMethod() == 'post') {

                $rules = [
                    'task_name' => 'required|alpha',
                    'is_qa' => 'required',
                    'quality_analyst' => 'CheckQA[quality_analyst]',
                    'hours_taken' => 'required',
                    'department' => 'required',
                ];

                $errors = [
                    'task_name' => [
                        'required' => 'Task name field is required',
                        'alpha' => 'Please enter only alphabetical letters.'
                    ],
                    'is_qa' => [
                        'required' => 'Please select any one.',
                    ],
                    'department' => [
                        'required' => 'Please select department',
                    ],
                    'hours_taken' => [
                        'required' => 'Please enter the time required for this task.',
                    ],
                    'quality_analyst' => [
                        'CheckQA' => 'Please select atleast one quality Check ',
                    ]
                ];

                if (!$this->validate($rules, $errors)) {
                    log_message('debug', 'Validation errors: ' . print_r($this->validator->getErrors(), true));
                    $output = $this->validator->getErrors();
                    $errorMsg = implode(";", $output);
                    return json_encode(['success' => false, 'csrf' => csrf_hash(), 'error' => $output]);
                } else {
                    $request = $this->request->getPost();
                    $userId = $this->InsertTaskdetails($request);
                    return json_encode(['success' => true, 'csrf' => csrf_hash(), "url" => base_url("taskDetail/list")]);
                }
            }
        } catch (DataBaseException $ex) {
            log_message('error', 'Database exception: ' . $ex->getMessage());
            $response = Response::SetResponse(500, null, new Error($ex->getMessage()));
        } catch (Exception $ex) {
            log_message('error', 'Exception: ' . $ex->getMessage());
            $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
        }
        return json_encode($response);
    }

    private function InsertTaskdetails(array $postdata)
    {
        $hours_taken = $postdata['hours_taken'];
        $seconds = round($hours_taken * 3600); // convert hours to seconds
        $daysTaken = ($seconds / (8 * 60 * 60));
        $qcStr = "";
        if (array_key_exists("quality_analyst", $postdata)) {
            $qcStr = implode(",", $postdata['quality_analyst']);
        }
        $model = ModelFactory::createModel(ModelNames::TaskDetail);
        $data = [
            "task_name" => $postdata['task_name'],
            "time_taken" => $postdata['hours_taken'],
            "dept_id" => $postdata['department'],
            "is_qa" => $postdata['is_qa'],
            "days_taken" => $daysTaken,
            "quality_analyst" => $qcStr,
        ];
        $userId = $model->InsertTaskDetail($data);
        return $userId;
    }

    public function GetDepartments()
    {
        $deptmodel = ModelFactory::createModel(ModelNames::Department);
        $deptList = $this->modelHelper->GetAllData($deptmodel);
        return  $deptList;
    }
    public function GetTaskDetailList()
    {
        $model = ModelFactory::createModel(ModelNames::TaskDetail);
        $taskDetailList = $model->GetParentTaskDetailList();
        $qaTaskList = [];
        for ($i = 0; $i < count($taskDetailList); $i++) {
            $condition = [TaskDetail::ParentTask => $taskDetailList[$i][TaskDetail::TaskDetailId], TaskDetail::IsQa => "1"];
            $qaTask = $this->modelHelper->GetSingleData($model, $condition);
            $qaTask["parent_task_id"] = $taskDetailList[$i][TaskDetail::TaskDetailId];
            $qaTask["parent_task_name"] = $taskDetailList[$i][TaskDetail::TaskName];
            array_push($qaTaskList, $qaTask);
            $taskDetailList[$i]["qa_task_id"] = $qaTask[TaskDetail::TaskDetailId];
            $taskDetailList[$i]["qa_task_name"] = $qaTask[TaskDetail::TaskName];
        }
        return view('taskDetailList', ["taskDetailList" => $taskDetailList, "qaTaskList" => $qaTaskList]);
    }

    public function GetQCList()
    {
        $model = ModelFactory::createModel(ModelNames::QualityCheck);
        $result = $model->GetAllQC();
        return $result;
    }

    public function GetTaskDetail()
    {
        $request = $this->request->getPost();
        $taskDetModel = ModelFactory::createModel(ModelNames::TaskDetail);
        $condition = [TaskDetail::TaskDetailId => $request['task_detail_id']];
        $taskDetail = $taskDetModel->GetTaskDetail($condition);
        $deptModel = ModelFactory::createModel(ModelNames::Department);
        $deptCondition = [Department::DepartmentId => $taskDetail[0][TaskDetail::DepartmentId]];
        $dept = $deptModel->GetDepartment($deptCondition);
        $qcModel = ModelFactory::createModel(ModelNames::QualityCheck);
        $qcIds = explode(",", $taskDetail[0][TaskDetail::QualityAnalyst]);
        $qcList = $qcModel->GetQCByIdList(QualityCheck::QCId, $qcIds);
        $taskDetailList = $this->GetTasksInOrder();
        $result = [
            "task_detail_id" => $taskDetail[0][TaskDetail::TaskDetailId],
            "task_name" => $taskDetail[0][TaskDetail::TaskName],
            "time_taken" => $taskDetail[0][TaskDetail::TimeTaken],
            "dept_id" => $dept[Department::DepartmentId],
            "dept_name" => $dept[Department::DepartmentName],
            "qc" => $qcList
        ];
        return view("taskDetailView", ["taskDetail" => $result, "taskDetailList" => $taskDetailList]);
    }

    public function GetTasksInOrder()
    {
        $tskDetmodel = ModelFactory::createModel(ModelNames::TaskDetail);
        $taskDetailList = $tskDetmodel->GetParentTaskDetailList();
        $result = [];
        $count = 0;
        foreach ($taskDetailList as $key => $value) {
            $count++;
            $result[$count] = $value;

            $condition = [TaskDetail::ParentTask => $value[TaskDetail::TaskDetailId]];
            $res = $this->modelHelper->GetAllDataUsingWhere($tskDetmodel, $condition);
            if ($res) {
                if (count($res) > 1) {
                    foreach ($res as $key => $value) {
                        $count++;
                        $result[$count] = $value;
                    }
                } else {
                    $count++;
                    $result[$count] = $res[0];
                }
            }
        }
        return $result;
    }

    public function DeleteTaskDetail()
    {
        if ($this->request->getMethod() == 'post') {
            try {
                $request = $this->request->getPost();
                $model = ModelFactory::createModel(ModelNames::TaskDetail);
                $delete_status = $model->DeleteTaskDetail($request['task_detail_id']);

                if ($delete_status) {
                    $status = "Task detail deleted successfully!";
                } else $status = "Something went wrong!";
                session()->setFlashdata('response', $status);
                return json_encode(['success' => true, 'csrf' => csrf_hash(), 'url' => base_url('/taskDetail/list')]);
            } catch (DataBaseException $ex) {

                $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
            } catch (Exception $ex) {

                $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
            }
            return json_encode($response);
        }
    }

    public function UpdateParentTask()
    {
        try {
            $request = $this->request->getPost();

            $data = [
                //TaskDetail::TaskDetailId => $request["task_detail_id"],
                TaskDetail::ParentTask => $request["prev_task_id"]
            ];
            $model = ModelFactory::createModel(ModelNames::TaskDetail);

            $update_status = $model->UpdateTaskDetail($request["task_detail_id"], $data);

            $status =   $update_status ? "Previous task updated successfully!" : "Something went wrong!";

            session()->setFlashdata('response', $status);

            return redirect()->to(base_url("taskDetail/list"));
        } catch (DataBaseException $ex) {

            $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
        } catch (Exception $ex) {

            $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
        }
        return json_encode($response);
    }
    public function AddOrUpdateDepartment()
    {
        try {
            $rules = [
                'department' => 'required|validateDepartmentName[department]',

            ];
            $errors = [
                'department' => [
                    'required' => 'Department name is required.',
                    'validateDepartmentName' => 'name already exists.'
                ],
            ];

            if (!$this->validate($rules, $errors)) {
                log_message('debug', 'Validation errors: ' . print_r($this->validator->getErrors(), true));
                $output = $this->validator->getErrors();
                $errorMsg = implode(";", $output);
                return json_encode(['success' => false, 'csrf' => csrf_hash(), 'error' => $output]);  
            } else {
                $request = $this->request->getPost();
                $data = [Department::DepartmentName => $request["department"]];
                $model = ModelFactory::createModel(ModelNames::Department);

                if (isset($request["deptId"])) {
                    $result = $this->modelHelper->UpdateData($model, $request["deptId"], $data);
                } else {
                    $result = $model->InsertDepartment($data);
                }
                return json_encode(['success' => true, 'csrf' => csrf_hash(), 'output' => $result, 'url' => base_url("department/list")]);
            }
        } catch (DataBaseException $ex) {
            log_message('error', 'Database exception: ' . $ex->getMessage());
            $response = Response::SetResponse(500, null, new Error($ex->getMessage()));
        } catch (Exception $ex) {
            log_message('error', 'Exception: ' . $ex->getMessage());
            $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
        }
        return json_encode($response);
    }

    public function DepartmentMap()
    {
        try {
            $deptEmpMapModel = ModelFactory::createModel(ModelNames::DeptEmpMap);
            if ($this->request->getMethod() == "get") {
                $model = ModelFactory::createModel(ModelNames::Employee);
                $employeeList = $model->GetAllEmployee();
                $condition = [Employee::Designation => 'supervisor'];
                $supervisorList = $model->GetEmployee($condition);
                $request = $this->request->getGet();
                $deptEmpMapData = null;
                $flag = "0";
                if (isset($request["deptId"])) {
                    $flag = "1";
                    $condition = [DeptEmpMap::DeptId => $request["deptId"] , DeptEmpMap::Status => "1"];
                    $deptEmpMapData = $this->modelHelper->GetSingleData($deptEmpMapModel, $condition);
                }
                return view("task/deptMap", ["employeeList" => $employeeList, "supervisorList" => $supervisorList, "deptEmpMapData" => $deptEmpMapData, "isEdit" => $flag]);
            } elseif ($this->request->getMethod() == "post") {

                $rules = [
                    'dept_id' => 'required',
                    'supervisor' => 'required',
                    'employee' => 'required'
                ];
                $errors = [
                    'employee' => [
                        'required' => 'Please select any one employee.',
                    ],
                    'supervisor' => [
                        'required' => 'Please select supervisor',
                    ],
                ];

                if (!$this->validate($rules, $errors)) {
                    $output = $this->validator->getErrors();
                    $errorMsg = implode(";", $output);
                    return json_encode(['success' => false, 'csrf' => csrf_hash(), 'error' => $output]);
                } else {
                    $request = $this->request->getPost();
                    if (isset($request["deptEmpMapId"])) {
                        $data = [DeptEmpMap::Status => "0"];
                        $result = $this->modelHelper->UpdateData($deptEmpMapModel, $request["deptEmpMapId"], $data);
                    }
                    $employeeIds = implode(",", $request["employee"]);
                    $data = [DeptEmpMap::DeptId => $request["dept_id"], DeptEmpMap::SupervisorId => $request["supervisor"], DeptEmpMap::EmployeeIds => $employeeIds];
                    $model = ModelFactory::createModel(ModelNames::DeptEmpMap);
                    $result = $model->InsertDeptEmpMap($data);
                    return json_encode(['success' => true, 'csrf' => csrf_hash(), "url" => base_url("department/list")]);
                }
            }
        } catch (DataBaseException $ex) {
            log_message('error', 'Database exception: ' . $ex->getMessage());
            $response = Response::SetResponse(500, null, new Error($ex->getMessage()));
        } catch (Exception $ex) {
            log_message('error', 'Exception: ' . $ex->getMessage());
            $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
        }
        return json_encode($response);
    }

    public function GetDepartmentList()
    {
        $deptList = $this->GetDepartments();
        return view("task/departmentList", ["deptList" => $deptList]);
    }
}
