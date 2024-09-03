<?php

namespace App\Controllers;

use App\Models\ModelHelper;
use App\Libraries\EnumsAndConstants\Order;
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
class TaskController extends BaseController
{
    public $modelHelper;

    public function __construct()
    {

        $this->modelHelper = new ModelHelper();
    }

    public function CreateTask()
    {
        if ($this->request->getMethod() == 'get') {

            $model = ModelFactory::createModel(ModelNames::TaskDetail);
            $drpdwnData = GetJson();
            //$stockList = $model->GetStockList();
            $taskDetailList = $model->GetParentTaskDetailList();
            return view("task/task_initialize", ["taskDetailList" => $taskDetailList, "drpdwnData" => $drpdwnData]);
        } elseif ($this->request->getMethod() == 'post') {
            try {
                $a = $this->request->getPost();
                $rules = [
                    'order_list_id'   => 'required',
                    //'Order_date'       => 'required',
                    //'Type'             => 'required',
                    'task_detail_id' => 'required',
                    // 'sizing'          => 'required',
                    // 'output_length'   => 'required',
                    // 'output_texture'  => 'required',
                    // 'output_colour'   => 'required',
                    // 'input_list.*'    => 'required',
                    'stock'        => 'required',
                    // 'quantity'        => 'required'
                ];
                $errors = [
                    'stock'        => ['required' => "Please give input to start the task."]
                ];

                if (!$this->validate($rules, $errors)) {

                    $output = $this->validator->getErrors();
                    $errorMsg = implode(";", $output);
                    $response = Response::SetResponse(400, null, new Error($errorMsg));
                    return json_encode(['success' => false, 'csrf' => csrf_hash(), "error" => $output]);
                } else {
                    $inputs = $this->request->getPost();
                    $parentTaskId = 0;
                    $model = ModelFactory::createModel(ModelNames::Task);

                    // $taskList=$model->where('Order_id',$inputs['Order_Id'])->findAll();
                    $maxId = $model->selectMax(Task::ParentTaskId)->where(Task::OrderListId, $inputs['order_list_id'])->first();

                    if ($maxId[Task::ParentTaskId]) {
                        $taskIdRes = $model->select(Task::TaskId)->where(Task::ParentTaskId, $maxId[Task::ParentTaskId])->first();
                        if (count($taskIdRes) > 0) {
                            $parentTaskId = $taskIdRes[Task::TaskId];
                        }
                    }

                    $taskDetailData = $this->GetTaskDetailData($inputs['task_detail_id']);
                    $deptEmpData = $this->GetDeptEmpMapData($taskDetailData[TaskDetail::DepartmentId]);

                    $data = [
                        //'Task_id'          => $taskId,
                        Task::ParentTaskId => $parentTaskId,
                        Task::OrderListId  => $inputs['order_list_id'],
                        Task::OrderId  => $inputs['order_id'],
                        Task::ItemId  => $inputs['item_id'],
                        //'Employee_id'     => $inputs,
                        Task::SupervisorId   => $deptEmpData[DeptEmpMap::SupervisorId],
                        //'Start_time'      => $inputs,
                        //'End_time'        => $inputs,
                        //'Time_taken'      => $inputs,
                        Task::TaskDetailId    => $inputs['task_detail_id'],
                        // Task::Sizing       => $inputs['sizing'],
                        // Task::OutLength    => $inputs['output_length'],
                        // Task::OutTexture   => $inputs['output_texture'],
                        // Task::OutColour    => $inputs['output_colour'],
                        //'Out_qty'          => $inputs['outputlength'],
                        Task::Status       => WorkStatus::NS,
                        //'Next_task_id'=>,

                        Task::CreatedBy    => session()->get('id'),
                        Task::UpdatedBy    => session()->get('id')
                    ];
                    $taskId = $model->InsertTask($data);

                    $this->SaveInput($inputs['stock'], $taskId, $inputs['order_list_id']);
                    return json_encode(['success' => true, 'csrf' => csrf_hash(), "url" => base_url("task/list")]);
                    //return redirect()->to(base_url("task/list"));
                    //$response = Response::SetResponse(201, null, new Error());
                }
            } catch (DataBaseException $ex) {
                $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
            } catch (Exception $ex) {
                $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
            }
            return json_encode($response);
        }
    }

    private function SaveInput(array $stockArr, int $taskId, int $orderId): bool|int
    {
        try {
            $stockModel = ModelFactory::createModel(ModelNames::Stock);

            $stockIds = array_keys($stockArr);

            $stockList = $stockModel->GetStockByIds(Stock::StockListId, $stockIds);


            $model = ModelFactory::createModel(ModelNames::Input);


            //update the stock list based on the selected stock quantity
            $arrayData = [];
            $count = 0;
            foreach ($stockList as $key => $value) {
                $parent = $value;
                $selectedQty = $stockArr[$value[Stock::StockListId]];
                $value[Stock::ActiveStatus] = '2';
                if ($value[Stock::Quantity] > $selectedQty) {

                    $value[Stock::ParentId] = $value[Stock::StockListId];
                    $parent[Stock::ActiveStatus] = "2";
                    $remainingQty = $value[Stock::Quantity] - $selectedQty;

                    $child1 = $value;
                    $child2 = $value;
                    $child1[Stock::Quantity] = $selectedQty;
                    $child1[Stock::ActiveStatus] = '0';
                    $child2[Stock::Quantity] = $remainingQty;
                    $child2[Stock::ActiveStatus] = '1';

                    $childId1 = $stockModel->InsertStock($child1);

                    $ordStckMdl = ModelFactory::createModel(ModelNames::OrderStockMap);
                    $ordStckData = [OrderStockMap::OrderListId => $orderId, OrderStockMap::StockListId => $childId1];
                    $ordStckMdl->InsertOrderStockMap($ordStckData);
                    $childId2 = $stockModel->InsertStock($child2);
                } else {
                    $parent[Stock::ActiveStatus] = "0";
                }
                $stockModel->UpdateStock($parent[Stock::StockListId], $parent);

                //create input data
                $data = [
                    TaskInput::TaskId     => $taskId,
                    TaskInput::InputCount => $count + 1,
                    TaskInput::InLength   => $parent[Stock::Length],
                    TaskInput::InColour   => $parent[Stock::Colour],
                    TaskInput::InQuantity => $stockArr[$value[Stock::StockListId]],
                    TaskInput::InTexture  => $parent[Stock::Texture],
                    TaskInput::InType     => $parent[Stock::Type],
                    TaskInput::InExtSize => $parent[Stock::ExtSize],
                    TaskInput::CreatedBy  => session()->get('id'),
                    TaskInput::UpdatedBy  => session()->get('id')
                ];
                array_push($arrayData, $data);
            }


            $result = $model->insertBatch($arrayData);
        } catch (DataBaseException $ex) {
            throw $ex;
        } catch (Exception $ex) {
            throw $ex;
        }
        return $result;
    }


    public function GetTask()
    {
        try {
            $request = $this->request->getGet();

            $model = ModelFactory::createModel(ModelNames::Task);

            $result = $model->where('Task_id', $request['Task_id'])->first();

            $response = Response::SetResponse(200, $result, new Error());
        } catch (DataBaseException $ex) {
            $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
        } catch (Exception $ex) {
            $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
        }
        return json_encode($response);
    }

    public function UpdateTask()
    {
        try {
            $request = $this->request->getPost();

            $model = ModelFactory::createModel(ModelNames::Task);

            $result = $model->where('Task_id', $request['Task_id'])->first();

            $response = Response::SetResponse(200, $result, new Error());
        } catch (DataBaseException $ex) {
            $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
        } catch (Exception $ex) {
            $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
        }
        return json_encode($response);
    }

    public function getDropdownList()
    {

        $data = [
            'products' => ['Cylinder, Weft', 'Poly'],
            'colour' => ['2A', '2B', '2N', '3A', '3B'],
            'texture' => ['Regular', 'Curly', 'Wavy'],
            'Ext_size' => ['Full', 'Half', 'Quarter', 'Thin'],
            'length' => ['8', '10', '12', '14'],
            'inputType' => ['Raw', 'inventory'],
            'inputIn' => ['kg', 'Bundle']
        ];


        $response = Response::SetResponse(200, $data, new Error());

        return json_encode($response);
    }

    public function GetSupervisors()
    {
        $model = ModelFactory::createModel(ModelNames::Employee);
        $condition = [Employee::Designation => 'supervisor'];
        $result = $model->GetEmployee($condition);

        return $result;
    }

    private function FindElement($searchArr, $searchKey, $searchValue)
    {

        foreach ($searchArr as $index => $value) {

            if ($value[$searchKey] == $searchValue) {
                return $value;
            }
        }
    }

    //Get the search condition based on the search value.
    public function GetCondition($request)
    {
        $flag = str_contains($request["order_item_id"], "-");
        if ($flag) {
            $orderItemId = explode("-", $request["order_item_id"]);
            $condition[Task::OrderId] = $orderItemId[0];
            $condition[Task::ItemId] = $orderItemId[1];
        } else {
            $condition[Task::OrderId] = $request["order_item_id"];
        }
        return $condition;
    }
    public function GetOrdersUnderTask($taskDetailId)
    {
        $request = $this->request->getGet();

        if (isset($request["order_item_id"])) {
            $condition = $this->GetCondition($request);
        }
        $condition[Task::TaskDetailId] = $taskDetailId;
        $condition[Task::IsSplit] = "0";

        $taskModel = ModelFactory::createModel(ModelNames::Task);
        $taskList = $taskModel->GetTaskList($condition);

        $model = ModelFactory::createModel(ModelNames::TaskDetail);
        $condition = [task::TaskDetailId => $taskDetailId];
        $taskDetail = $model->GetTaskDetail($condition);

        $model = ModelFactory::createModel(ModelNames::Employee);
        $condition = [Employee::Designation => 'supervisor'];
        $supervisorList = $model->GetEmployee($condition);

        for ($i = 0; $i < count($taskList); $i++) {
            $taskList[$i]["supervisor_name"] = "";
            if ($taskList[$i][Task::SupervisorId] > 0) {
                $supervisor =  $this->FindElement($supervisorList, Employee::Id, $taskList[$i][Task::SupervisorId]);
                $taskList[$i]["supervisor_name"] = $supervisor[Employee::Name];
            }
            $taskList[$i]["employee_name"] = "";
            if ($taskList[$i][Task::EmployeeId] > 0) {
                $model = ModelFactory::createModel(ModelNames::Employee);
                $condition = [Employee::Id => $taskList[$i][Task::EmployeeId]];
                $employee = $model->GetEmployee($condition);

		//     $taskList[$i]["employee_name"] = $employee[0][Employee::Name];
		if (is_array($employee) && isset($employee[0]) && is_array($employee[0]) && isset($employee[0][Employee::Name])) {
        $taskList[$i]["employee_name"] = $employee[0][Employee::Name];
    } else {
        // Handle the case where $employee does not have the expected structure
        $taskList[$i]["employee_name"] = 'Unknown';
    }
            }

            $condition = [Task::ParentTaskId => $taskList[$i][Task::TaskId]];
            $result = $this->modelHelper->GetAllDataUsingWhere($taskModel, $condition);

            $nextTaskList = [];
            foreach ($result as $key => $value) {
                $nextTask["id"] = $value[Task::TaskId];
                $nextTask["is_qa"] = $value[Task::IsQa];
                array_push($nextTaskList, $nextTask);
            }

            $taskList[$i]["next_task"] = $nextTaskList;
        }

        return view('task/task_order', ["taskList" => $taskList, "taskDetail" => $taskDetail]);
    }

    public function GetAllTask()
    {
        $request = $this->request->getGet();
        $order_item_id = null;
        if (isset($request["order_item_id"])) {

            $condition = $this->GetCondition($request);

            $order_item_id = $request["order_item_id"];
        }
        $condition[Task::IsSplit] = "0";

        $taskDetailList = $this->GetTasksInOrder();

        $model = ModelFactory::createModel(ModelNames::Task);

        $overAllCount = $model->GetTaskCount($condition);

        $condition[Task::Status] = "Not started";
        $toDoCount = $model->GetTaskCount($condition);

        $condition[Task::Status] = "In progress";
        $inProgressCount = $model->GetTaskCount($condition);
        $result = [];
        foreach ($taskDetailList as $taskDetail) {
            $taskDetail['overAllCount'] = 0;
            $taskDetail['toDoCount'] = 0;
            $taskDetail['inProgressCount'] = 0;
            foreach ($overAllCount as $key => $value) {
                # code... 
                if ($value[Task::TaskDetailId] == $taskDetail[TaskDetail::TaskDetailId]) {
                    $taskDetail['overAllCount'] = $value['count'];
                    break;
                }
            }
            foreach ($toDoCount as $key => $value) {
                # code... 
                if ($value[Task::TaskDetailId] == $taskDetail[TaskDetail::TaskDetailId]) {
                    $taskDetail['toDoCount'] = $value['count'];
                    break;
                }
            }
            foreach ($inProgressCount as $key => $value) {
                # code... 
                if ($value[Task::TaskDetailId] == $taskDetail[TaskDetail::TaskDetailId]) {
                    $taskDetail['inProgressCount'] = $value['count'];
                    break;
                }
            }
            if ($taskDetail['overAllCount'] != 0) {
                array_push($result, $taskDetail);
            }
        }
        return view('task/taskList', ["taskDetailList" => $result, "orderItemId" => $order_item_id]);
    }


    //Get all the taskdetails along with QA in correct sequence.
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

    public function MapEmployee($taskId)
    {
        try {
            $modelHelper = $this->modelHelper;

            if ($this->request->getMethod() == "get") {
                $request = "";

                $taskOrderDetails = $this->GetTaskAndOrderDetails($taskId);
                $task = $taskOrderDetails["task"];
                $drpdwnData = null;
                if ($task[Task::Status] == WorkStatus::IP) {
                    $drpdwnData = GetJson();
                }
                return view('task/taskMap', ["task" => $taskOrderDetails["task"], "order" => $taskOrderDetails["order"], "employeeList" => $taskOrderDetails["employeeList"], "inputDetails" => $taskOrderDetails["inputDetails"], "currentTask" => $taskOrderDetails["task"], "currentTaskDetail" => $taskOrderDetails["taskDetail"], "drpdwnData" => $drpdwnData, "qcList" => $taskOrderDetails["qcList"]]);
            } elseif ($this->request->getMethod() == "post") {

                $request = $this->request->getPost();
                $rules = [
                    // 'task' => 'required',
                    'employee' => 'required',
                ];


                $errors = [

                    'employee' => [
                        'required' => 'Please select any one employee.',
                    ],



                ];
                if (!$this->validate($rules, $errors)) {
                    //  log_message('debug', 'Validation errors: ' . print_r($this->validator->getErrors(), true));
                    $output = $this->validator->getErrors();
                    $errorMsg = implode(";", $output);
                    //$response = Response::SetResponse(400, null, new Error($errorMsg));


                    return json_encode(['success' => false, 'csrf' => csrf_hash(), 'error' => $output]);
                } else {
                    $request = $this->request->getPost();

                    //update task table
                    $data = [Task::EmployeeId => $request["employee"], Task::Status => WorkStatus::IP, Task::StartTime => date("Y-m-d H:i:s")];
                    $taskModel = ModelFactory::createModel(ModelNames::Task);
                    $result =  $modelHelper->UpdateData($taskModel, $taskId, $data);

                    $condition = [Task::TaskId => $taskId];
                    $task = $modelHelper->GetSingleData($taskModel, $condition);

                    $condition = [TaskDetail::TaskDetailId => $task[TaskDetail::TaskDetailId]];
                    $taskDetailModel = ModelFactory::createModel(ModelNames::TaskDetail);
                    $taskDetail = $modelHelper->GetSingleData($taskDetailModel, $condition);

                    //update status in order table
                    $data = [Order::Status => WorkStatus::IP . " - " . $taskDetail[TaskDetail::TaskName]];
                    $orderModel = ModelFactory::createModel(ModelNames::Order);
                    $result =  $modelHelper->UpdateData($orderModel, $task[Task::OrderListId], $data);



                    // $response = Response::SetResponse(201, null, new Error());
                    // return json_encode(['success' => true, 'csrf' => csrf_hash(), "url" => base_url("task/taskDetailList")]);

                    return redirect()->to(base_url("task/orderList/" . $request["taskDetailId"]));
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
    public function SplitTaskAndMapEmployees($taskId)
    {
        try {
            $modelHelper = new ModelHelper();
            if ($this->request->getMethod() == "post") {

                $request = $this->request->getPost();
                $postData =  json_decode($request["req"]);
                $rules = [
                    'req' => 'required',
                    //  'employee' => 'required',
                ];

                if (!$this->validate($rules)) {
                    //  log_message('debug', 'Validation errors: ' . print_r($this->validator->getErrors(), true));
                    $output = $this->validator->getErrors();
                    $errorMsg = implode(";", $output);
                    //$response = Response::SetResponse(400, null, new Error($errorMsg));


                    return json_encode(['success' => false, 'csrf' => csrf_hash(), 'error' => $output]);
                } else {
                    $request = $this->request->getPost();

                    $data = [Task::IsSplit => "1"];
                    $condition = [Task::TaskId => $taskId];
                    $taskModel = ModelFactory::createModel(ModelNames::Task);

                    $result = $modelHelper->UpdateData($taskModel, $taskId, $data);
                    $taskInputModel = ModelFactory::createModel(ModelNames::Input);
                    $inputList = $modelHelper->GetAllDataUsingWhere($taskInputModel, $condition);
                    $inputKeyValue = [];
                    foreach ($inputList as $key => $input) {
                        $inputKeyValue[$input[TaskInput::InputId]] = $input;
                        //$this->FindElement($inputList, TaskInput::InputId,)
                    }
                    // $result =  $modelHelper->UpdateData($taskModel, $taskId, $data);

                    $parentTask =   $modelHelper->GetSingleData($taskModel, $condition);
                    $parentTaskId = $parentTask[Task::TaskId];
                    $parentTask[Task::TaskId] = null;
                    $count = 0;
                    // for ($i = 0; $i < count($postData); $i++) {
                    foreach ($postData as $employee => $qtyList) {
                        $count++;
                        $parentTask[Task::IsSplit] = "0";
                        $parentTask[Task::EmployeeId] = $employee;
                        $parentTask[Task::Part] = $count;
                        $parentTask[Task::SplitFrom] = $parentTaskId;
                        $splitTaskId = $modelHelper->InsertData($taskModel, $parentTask);

                        foreach ($qtyList as $key => $value) {
                            //for ($j = 0; $i < $postData[$i][]; $i++) {
                            $splitInput = $inputKeyValue[$value->in_qty_id];

                            $splitInput[TaskInput::InputId] = null;
                            $splitInput[TaskInput::InQuantity] = $value->in_qty;
                            $splitInput[TaskInput::TaskId] = $splitTaskId;
                            $modelHelper->InsertData($taskInputModel, $splitInput);
                        }
                    }

                    // $response = Response::SetResponse(201, null, new Error());
                    return json_encode(['success' => true, 'csrf' => csrf_hash(), "url" => base_url("task/orderList/" . $request["taskDetailId"])]);
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

    public function StartQC()
    {

        $request = $this->request->getPost();

        $rules = [
            'qa_task' => 'required'
        ];


        if (!$this->validate($rules)) {
            //  log_message('debug', 'Validation errors: ' . print_r($this->validator->getErrors(), true));
            $output = $this->validator->getErrors();
            $errorMsg = implode(";", $output);
            //$response = Response::SetResponse(400, null, new Error($errorMsg));


            return json_encode(['success' => false, 'csrf' => csrf_hash(), 'error' => $output]);
        } else {
            $taskModel = ModelFactory::createModel(ModelNames::Task);

            //update the task
            $data = [
                Task::StartTime => date("Y-m-d H:i:s"),
                Task::Status => WorkStatus::IP
            ];
            $this->modelHelper->UpdateData($taskModel, $request["qa_task"], $data);
            return json_encode(['success' => true, 'csrf' => csrf_hash()]);
        }
    }
    public function RestartTask()
    {

        $request = $this->request->getPost();

        $rules = [
            'qa_task'     => 'required',
            'parent_task' => 'required'
        ];


        if (!$this->validate($rules)) {
            //  log_message('debug', 'Validation errors: ' . print_r($this->validator->getErrors(), true));
            $output = $this->validator->getErrors();
            $errorMsg = implode(";", $output);
            //$response = Response::SetResponse(400, null, new Error($errorMsg));


            return json_encode(['success' => false, 'csrf' => csrf_hash(), 'error' => $output]);
        } else {

            $taskModel = ModelFactory::createModel(ModelNames::Task);

            //update the task
            $data = [
                Task::EndTime => date("Y-m-d H:i:s"),
                Task::Status => WorkStatus::C
            ];
            $this->modelHelper->UpdateData($taskModel, $request["qa_task"], $data);
            $condition = [Task::TaskId => $request["qa_task"]];
            $qaTask = $this->modelHelper->GetSingleData($taskModel, $condition);


            $condition = [Task::TaskId => $request["parent_task"]];
            $parentTask = $this->modelHelper->GetSingleData($taskModel, $condition);
            $data = [
                //'Task_id'          => $taskId,
                Task::ParentTaskId => $request["qa_task"],
                Task::OrderListId  => $parentTask[Task::OrderListId],
                Task::OrderId      => $parentTask[Task::OrderId],
                Task::ItemId       => $parentTask[Task::ItemId],
                //'Employee_id'     => $inputs,
                Task::SupervisorId => $parentTask[Task::SupervisorId],
                //'Start_time'      => $inputs,
                //'End_time'        => $inputs,
                //'Time_taken'      => $inputs,
                Task::TaskDetailId => $parentTask[Task::TaskDetailId],
                // Task::Sizing       => $inputs['sizing'],
                // Task::OutLength    => $inputs['output_length'],
                // Task::OutTexture   => $inputs['output_texture'],
                // Task::OutColour    => $inputs['output_colour'],
                //'Out_qty'          => $inputs['outputlength'],
                Task::Status       => WorkStatus::NS,
                //'Next_task_id'=>,

                Task::CreatedBy    => session()->get('id'),
                Task::UpdatedBy    => session()->get('id')
            ];

            $insertedId = $this->modelHelper->InsertData($taskModel, $data);
            $parentTask[Task::TaskId] = $insertedId;
            $this->InsertInputs($parentTask);

            //$taskModel = ModelFactory::createModel(ModelNames::Task);
            //$condition = [Task::TaskId => $request["qa_task"]];
            // $qaTask = $this->modelHelper->GetSingleData($taskModel, $condition);
            // $data[Task::TaskId] = $taskId;
            // $qaTask = $this->InsertQaTask($data, $inputs['task_detail_id']);

            return json_encode(['success' => true, 'csrf' => csrf_hash(), 'url'=> base_url("task/orderList/".$qaTask[Task::TaskDetailId] )]);
        }
    }
    public function QualityCheck($taskId)
    {
        if ($this->request->getMethod() == "get") {

            $tskDetmodel = ModelFactory::createModel(ModelNames::TaskDetail);

            $taskDetailList = $tskDetmodel->GetParentTaskDetailList();
            $taskOrderDetails = $this->GetTaskAndOrderDetails($taskId);
            $qaTask = $taskOrderDetails["qaTask"];
            $taskDetail = $taskOrderDetails["taskDetail"];
            $qcIdList = explode(",", $taskDetail[TaskDetail::QualityAnalyst]);
            $qcList = $this->GetQCChecks($qcIdList);
            $supervisorList = $this->GetSupervisors();
            return view('task/qualityCheck', ["taskDetailList" => $taskDetailList, "task" => $taskOrderDetails["task"], "order" => $taskOrderDetails["order"], "employeeList" => $taskOrderDetails["employeeList"], "inputDetails" => $taskOrderDetails["inputDetails"], "taskDetail" => $taskOrderDetails["taskDetail"], "supervisorList" => $supervisorList, "qcList" => $qcList, "qaTask" => $qaTask]);
        } else {
            $request = $this->request->getPost();

            $rules = [
                'parent_task' => 'required',
               // 'is_complete' => 'required|CheckCompleteStatus[is_complete]',
                //'next_task_detail_id' => 'required',
                'qa_task' => 'required',
                'current_task_detail_id'=>'required'
            ];

            if (!$this->validate($rules)) {
                //  log_message('debug', 'Validation errors: ' . print_r($this->validator->getErrors(), true));
                $output = $this->validator->getErrors();
                $errorMsg = implode(";", $output);
                //$response = Response::SetResponse(400, null, new Error($errorMsg));


                return json_encode(['success' => false, 'csrf' => csrf_hash(), 'error' => $output]);
            } else {
                $taskModel = ModelFactory::createModel(ModelNames::Task);

                //update the task
                $data = [
                    Task::EndTime => date("Y-m-d H:i:s"),
                    Task::Status => WorkStatus::C
                ];
                $this->modelHelper->UpdateData($taskModel, $request["qa_task"], $data);

                if ($request["current_task_detail_id"] != 101) {
                    $condition = [Task::TaskId => $request["parent_task"]];
                    $parentTask = $this->modelHelper->GetSingleData($taskModel, $condition);
                    $previousTaskId = $request["qa_task"];
                    //insert next task
                    $insertedtaskId = $this->InsertNextTask($parentTask, $request["next_task_detail_id"], $previousTaskId);
                    $parentTask[Task::TaskId] = $insertedtaskId;
                    $this->InsertInputs($parentTask);
                }

                return json_encode(["success" => true, "csrf" => csrf_hash(), "url" => base_url("task/orderList/" . $request["current_task_detail_id"])]);
                // return redirect()->to(base_url("task/list"));
            }
        }
    }
    public function GetQCChecks($qcIdList)
    {

        $qcModel = ModelFactory::createModel(ModelNames::QualityCheck);
        $qcList = $this->modelHelper->GetAllDataUsingWhereIn($qcModel, QualityCheck::QCId, $qcIdList);

        return $qcList;
    }

    public function GetTaskAndOrderDetails($taskId)
    {

        $taskModel = ModelFactory::createModel(ModelNames::Task);


        $condition = [Task::TaskId => $taskId];
        $task = $this->modelHelper->GetSingleData($taskModel, $condition);

        $qaTask = [];
        if ($task[Task::IsQa] == 1) {
            $qaTask = $task;
            $condition = [Task::TaskId => $task[Task::ParentTaskId]];

            //assign parent task as a task to get the input detais for that.
            $task = $this->modelHelper->GetSingleData($taskModel, $condition);

            $taskDetcondition = [TaskDetail::TaskDetailId => $qaTask[Task::TaskDetailId]];
        } else {
            $taskDetcondition = [TaskDetail::TaskDetailId => $task[Task::TaskDetailId]];
        }
        //get the task details
        $taskDetModel = ModelFactory::createModel(ModelNames::TaskDetail);
        $taskDetail = $this->modelHelper->GetSingleData($taskDetModel, $taskDetcondition);
        $qcList = [];
        if (count($qaTask) > 0) {

            $qcIdList = explode(",", $taskDetail[TaskDetail::QualityAnalyst]);
            $qcList = $this->GetQCChecks($qcIdList);
            // $qaTask["qcList"]= $qcList;
        }
        //get the input details
        $inputModel = ModelFactory::createModel(ModelNames::Input);


        $condition = [TaskInput::TaskId => $task[Task::TaskId]];
        $inputDetail = $this->modelHelper->GetAllDataUsingWhere($inputModel, $condition);

        //get input details if it the task is one of the splitted task because of multilple outputs.
        if (count($inputDetail) == 0) {
            if ($task[Task::Status] == WorkStatus::C && $task[Task::SplitFrom] > 0) {
                $condition = [TaskInput::TaskId => $task[Task::SplitFrom]];
                $inputDetail = $this->modelHelper->GetAllDataUsingWhere($inputModel, $condition);
            }
        }
        //get the order details
        $ordModel = ModelFactory::createModel(ModelNames::Order);
        $condition = [Order::OrderId => $task[Task::OrderId], Order::ItemId => $task[Task::ItemId]];
        $order = $ordModel->GetOrder($condition);


        //get the department details
        $deptEmpModel = ModelFactory::createModel(ModelNames::DeptEmpMap);
	//    $condition = [Department::DepartmentId => $taskDetail[TaskDetail::DepartmentId]];
	$condition = [DeptEmpMap::DeptId => $taskDetail[TaskDetail::DepartmentId],DeptEmpMap::Status=>"1"];
        $deptEmp = $deptEmpModel->GetDeptEmpMap($condition);

        //get the employee details under the department
        $empModel = ModelFactory::createModel(ModelNames::Employee);
        $key = Employee::Id;
        $idList = explode(",", $deptEmp[0][DeptEmpMap::EmployeeIds]);
        $empList =  $empModel->GetEmployeeByIds($key, $idList);

        $taskOrderDetails = [
            "task" => $task,
            "order" => $order,
            "employeeList" => $empList,
            "inputDetails" => $inputDetail,
            "taskDetail" => $taskDetail,
            "qaTask" => $qaTask,
            "qcList" => $qcList
        ];
        return $taskOrderDetails;
    }
    public function SplitTaskBasedOnOutputs($taskId)
    {
        $request = $this->request->getPost();
        $rules = [
            'req' => 'required',
        ];

        if (!$this->validate($rules)) {
            //  log_message('debug', 'Validation errors: ' . print_r($this->validator->getErrors(), true));
            $output = $this->validator->getErrors();
            $errorMsg = implode(";", $output);
            //$response = Response::SetResponse(400, null, new Error($errorMsg));


            return json_encode(['success' => false, 'csrf' => csrf_hash(), 'error' => $output]);
        } else {


            $modelHelper = new ModelHelper();


            $taskModel = ModelFactory::createModel(ModelNames::Task);
            $condition = [Task::TaskId => $taskId];


            $parentTask =   $modelHelper->GetSingleData($taskModel, $condition);
            $count = 0;
            $time = date("Y-m-d H:i:s");

            //if the count is more than one than split it according to the count and insert new tasks.
            if (count($request["req"]) > 1) {

                $data = [Task::IsSplit => "1"];
                $result = $modelHelper->UpdateData($taskModel, $taskId, $data);

                foreach ($request["req"] as $key => $value) {
                    $parentTask[Task::TaskId] = null;

                    $count++;
                    $parentTask[Task::OutColour] = $value["colour"];
                    $parentTask[Task::OutLength] = $value["length"];
                    $parentTask[Task::OutTexture] = $value["texture"];
                    $parentTask[Task::OutQty] = $value["weight"];
                    $parentTask[Task::OutType] = $value["type"];
                    $parentTask[Task::OutExtSize] = $value["extSize"];


                    $parentTask[Task::IsSplit] = "0";
                    $parentTask[Task::SplitFrom] = $taskId;
                    $parentTask[Task::Part] = $count;
                    $parentTask[Task::EndTime] = $time;
                    $parentTask[Task::Status] = WorkStatus::C;

                    $insertedId = $modelHelper->InsertData($taskModel, $parentTask);
                    $parentTask[Task::TaskId] = $insertedId;
                    $qaTask = $this->InsertQaTask($parentTask, $parentTask[Task::TaskDetailId]);
                }
            }
            //update the existing task to done status
            else {
                $value = $request["req"][0];

                //move to stock logic
                if ($parentTask[Task::TaskDetailId] == "100") {

                    $stockModel = ModelFactory::createModel(ModelNames::Stock);
                    $data[Stock::Colour] = $value["colour"];
                    $data[Stock::Length] = $value["length"];
                    $data[Stock::Texture] = $value["texture"];
                    $data[Stock::Quantity] = $value["weight"];
                    $data[Stock::Type] = $value["type"];
                    $data[Stock::ExtSize] = $value["extSize"];

                    $data[Stock::StockId] = $parentTask[Task::OrderId]."-".$parentTask[Task::ItemId];
                    $data[Stock::Date] = date("Y-m-d");
                    $modelHelper->InsertData($stockModel, $data);
                }
                $parentTask[Task::OutColour] = $value["colour"];
                $parentTask[Task::OutLength] = $value["length"];
                $parentTask[Task::OutTexture] = $value["texture"];
                $parentTask[Task::OutQty] = $value["weight"];
                $parentTask[Task::OutType] = $value["type"];
                $parentTask[Task::OutExtSize] = $value["extSize"];


                $parentTask[Task::IsSplit] = "0";
                $parentTask[Task::EndTime] = $time;
                $parentTask[Task::Status] = WorkStatus::C;
                $modelHelper->UpdateData($taskModel, $parentTask[Task::TaskId], $parentTask);

                $qaTask = $this->InsertQaTask($parentTask, $parentTask[Task::TaskDetailId]);
            }
            return json_encode(['success' => true, 'csrf' => csrf_hash(), "url" => base_url("task/orderList/" . $request["taskDetailId"])]);
        }
    }

    public function InsertNextTask($task, $nextTask, $previousTaskId)
    {

        $modelHelper = $this->modelHelper;
        $taskDetailData = $this->GetTaskDetailData($nextTask);
        $deptEmpData = $this->GetDeptEmpMapData($taskDetailData[TaskDetail::DepartmentId]);

        $data = [
            //'Task_id'          => $taskId,
            Task::ParentTaskId => $previousTaskId,
            Task::OrderListId  => $task[Task::OrderListId],
            Task::OrderId  => $task[Task::OrderId],
            Task::ItemId  => $task[Task::ItemId],
            //'Employee_id'     => $inputs,
            Task::SupervisorId   => $deptEmpData[DeptEmpMap::SupervisorId],
            //'Start_time'      => $inputs,
            //'End_time'        => $inputs,
            //'Time_taken'      => $inputs,
            Task::TaskDetailId    => $nextTask,
            // Task::Sizing       => $inputs['sizing'],
            // Task::OutLength    => $inputs['output_length'],
            // Task::OutTexture   => $inputs['output_texture'],
            // Task::OutColour    => $inputs['output_colour'],
            //'Out_qty'          => $inputs['outputlength'],
            Task::Status       => WorkStatus::NS,
            //'Next_task_id'=>,

            Task::CreatedBy    => session()->get('id'),
            Task::UpdatedBy    => session()->get('id')
        ];
        $taskModel = ModelFactory::createModel(ModelNames::Task);
        $result = $modelHelper->InsertData($taskModel, $data);

        return $result;
    }

    public function InsertInputs($task)
    {
        $modelHelper = new ModelHelper();
        $data = [
            TaskInput::TaskId => $task[Task::TaskId],
            TaskInput::InColour     => $task[Task::OutColour],
            TaskInput::InLength     => $task[Task::OutLength],
            TaskInput::InExtSize    => $task[Task::OutExtSize],
            TaskInput::InTexture    => $task[Task::OutTexture],
            TaskInput::InQuantity   => $task[Task::OutQty],
            TaskInput::InType       => $task[Task::OutType],
            TaskInput::CreatedBy    => session()->get('id'),
            TaskInput::UpdatedBy    => session()->get('id')
        ];
        $inputModel = ModelFactory::createModel(ModelNames::Input);
        $result = $modelHelper->InsertData($inputModel, $data);
    }
    public function GetDeptEmpMapData($deptId)
    {
        $modelHelper = new ModelHelper();
        $deptEmpModel = ModelFactory::createModel(ModelNames::DeptEmpMap);
        $condition = [DeptEmpMap::DeptId => $deptId,DeptEmpMap::Status=>"1"];
        $deptEmpData = $modelHelper->GetSingleData($deptEmpModel, $condition);

        return $deptEmpData;
    }

    public function GetTaskDetailData($taskDetailId)
    {
        $modelHelper = new ModelHelper();
        $taskDetailModel = ModelFactory::createModel(ModelNames::TaskDetail);
        $condition = [TaskDetail::TaskDetailId => $taskDetailId];
        $taskDetailData = $modelHelper->GetSingleData($taskDetailModel, $condition);

        return $taskDetailData;
    }

    //Get the previously completed tasks of an order
    public function GetPreviousTaskList($taskId)
    {
        $result = [];
        $taskArr = [];
        $taskDetailArr = [];
        $modelHelper = $this->modelHelper;
        $taskModel =  ModelFactory::createModel(ModelNames::Task);
        $taskDetailModel =  ModelFactory::createModel(ModelNames::TaskDetail);
        while ($taskId > 0) {
            $condition = [Task::TaskId => $taskId];
            $taskData = $modelHelper->GetSingleData($taskModel, $condition);

            $empModel = ModelFactory::createModel(ModelNames::Employee);
            $empIdArr = [$taskData[Task::EmployeeId], $taskData[Task::SupervisorId]];

            $empData =  $modelHelper->GetAllDataUsingWhereIn($empModel, Employee::Id, $empIdArr);

            $worker = $this->FindElement($empData, Employee::Id, $taskData[Task::EmployeeId]);
            $supervisor = $this->FindElement($empData, Employee::Id, $taskData[Task::SupervisorId]);


            array_unshift($taskArr, $taskData);
            $condition = [TaskDetail::TaskDetailId => $taskData[Task::TaskDetailId]];
            $taskDetailData = $modelHelper->GetSingleData($taskDetailModel, $condition);
            $taskId = $taskData[Task::ParentTaskId];
            array_unshift($taskDetailArr, $taskDetailData);


            $data = [
                "task_name" => $taskDetailData[TaskDetail::TaskName],
                "start_time" => $taskData[Task::StartTime],
                "end_time" => $taskData[Task::EndTime],

                "supervisor_name" => $supervisor[Employee::Name]

            ];
            $data["employee_name"] = "";
            if ($worker) {
                $data["employee_name"] = $worker[Employee::Name];
            }
            array_unshift($result, $data);
        }


        return json_encode(['success' => true, 'csrf' => csrf_hash(), "output" => $result]);
    }
    public function InsertQaTask($parentTask, $parentTaskDetail)
    {

        $taskDetModel = ModelFactory::createModel(ModelNames::TaskDetail);
        $condition = [TaskDetail::ParentTask => $parentTaskDetail];
        $qaTaskDetail =  $this->modelHelper->GetSingleData($taskDetModel, $condition);

        $taskModel = ModelFactory::createModel(ModelNames::Task);

        $data = [
            Task::IsQa          => "1",
            Task::ParentTaskId  => $parentTask[Task::TaskId],
            Task::OrderListId   => $parentTask[Task::OrderListId],
            Task::OrderId       => $parentTask[Task::OrderId],
            Task::ItemId        => $parentTask[Task::ItemId],
            Task::SupervisorId  => $parentTask[Task::SupervisorId],
            Task::TaskDetailId  => $qaTaskDetail[TaskDetail::TaskDetailId],
            Task::Status        => WorkStatus::NS,
            Task::CreatedBy     => session()->get('id'),
            Task::UpdatedBy     => session()->get('id')
        ];
        $qaTaskId =  $this->modelHelper->InsertData($taskModel, $data);


        return $qaTaskId;
    }
    public function DeleteTask()
    {

        if ($this->request->getMethod() == 'post') {
            try {

                $request = $this->request->getPost();

                $model = ModelFactory::createModel(ModelNames::Task);

                //Delete task data
                $task_status = $this->modelHelper->DeleteData($model, $request["taskId"]);


                //delete input data
                $model = ModelFactory::createModel(ModelNames::Input);
                $condition = [TaskInput::TaskId => $request["taskId"]];
                $input_status = $this->modelHelper->DeleteDataUsingWhere($model, $condition);

                if ($task_status && $input_status) {
                    $status = "Task deleted successfully!";
                } else $status = "Something went wrong!";
                session()->setFlashdata('response', $status);

                //$response = Response::SetResponse(201, null, new Error());

                // return json_encode(['success' => true, 'csrf' => csrf_hash(), 'url' => base_url('/order/orderList')]);

                return redirect()->to(base_url('/task/orderList/' . $request["taskDetailId"]));
            } catch (DataBaseException $ex) {

                $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
            } catch (Exception $ex) {

                $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
            }
            return json_encode($response);
        }
    }
}
