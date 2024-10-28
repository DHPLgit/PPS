<?php
//require '/var/www/html/NPS-deploy/jobs/vendor/autoload.php';
require 'D:/xampp/htdocs/pps/jobs/vendor/autoload.php';

use Throwable;


$message = "Process started";
echo ($message);
//LogMessage($message);


//Get to merge list of all qa tasks
$taskTable = "pps_task_list";
$condition = "status = 'To merge'";
$result = GetData($taskTable, $condition);
foreach ($result as $key => $qaTask) {

	//Get parent task of that qa task
	$condition = "task_id = " . $qaTask['parent_task_id'];
	$result = GetData($taskTable, $condition);


	//Get all the split tasks
	$condition    = "split_from  = " . $task["split_from"];
	$childTasks   = GetData($taskTable, $condition);
	$totalQty     = 0;
	$count        = 0;
	$mergeTasks   = [];
	$mergeQaTasks = [];

	//check if all the split tasks are completed
	foreach ($childTasks as $key => $child) {
		if ($child["status"] == "Completed") {
			$count++;
		} else break;
	}

	//if all the split tasks are completed exceute it.
	if ($count == count($childTasks)) {
		$incompleteTaskCount = 0;

		//loop through all the split tasks and check the respective qa task status
		foreach ($childTasks as $key => $child) {
			$condition = "parent_task_id = " . $child["task_id"];
			$childQaTask = GetData($taskTable, $condition);
			if ($childQaTask["status"] == "To merge") {

				$totalQty += $child["out_qty"];
				// $data = "is_split = 2";
				// $result = UpdateData($taskTable, $child["task_id"], $data);

				//store the tasks in array if merging is only for some tasks
				array_push($mergeTasks, $child);
				array_push($mergeQaTasks, $childQaTask);

				$qaCount++;
			} else if ($childQaTask["status"] == "Not started" || $childQaTask["status"] != "In progress") {
				$incompleteTaskCount++;
			}
		}
		$result = GetStartAndEndTime($mergeTasks);
		$startTime = $result["startTime"];
		$endTime = $result["endTime"];

		$result = GetStartAndEndTime($mergeQaTasks);
		$qaStartTime = $result["startTime"];
		$qaEndTime = $result["endTime"];
		//if all the qa tasks are completed or in to merge status
		if ($incompleteTaskCount == 0) {
			$insertTask = $mergeTasks[0];
			$insertTask["out_qty"] = $totalQty;
			unset($insertTask['task_id'], $insertTask['employee_id'], $insertTask['start_time'], $insertTask['end_time']);
			$insertTask['start_ime'] = $startTime;
			$insertTask['end_time'] = $endTime;

			//insert new parent task which is the merged task for the split tasks.
			$insertedId = InsertData($taskTable, $insertTask);
			$qaParentTaskId = $insertedId;

			$taskIds = [];
			foreach ($mergeTasks as $key => $value) {
				$taskIds = array_push($taskIds, $value["task_id"]);
			}
			foreach ($mergeQaTasks as $key => $value) {
				$taskIds = array_push($taskIds, $value["task_id"]);
			}

			//update the tasks to merged status.
			$strTaskIds = implode(",", $taskIds);
			$condition = " task_id IN ( " . $strTaskIds . ")";
			$data = "status = Merged, is_split = 2";
			$result = UpdateData($taskTable, $taskIds, $data);


			//Get the parent task of QA
			$condition = ["task_id" => $qaParentTaskId];
			$parentTask = GetData($taskTable, $condition);

			//create a respective Qa task for that parent task
			$parentTask['start_ime'] = $qaStartTime;
            $parentTask['end_time'] = $qaEndTime;
			$qaTaskId = InsertQaTask($parentTask, $parentTask["task_detail_id"], "Completed");
			$qaParent = $qaParentTaskId;
			if ($qaTask["task_detail_id"] != 101) {
				$condition = ["task_id" => $qaParent];
				$parentTask = GetData($taskModel, $condition);
				$previousTaskId = $qaTaskId;
				//insert next task
				$insertedtaskId = InsertNextTask($parentTask, $qaTask["next_task_detail_id"], $previousTaskId);
				$parentTask["task_id"] = $insertedtaskId;
				InsertInputs($parentTask);
			}
		}
	}
}


function InsertNextTask($task, $nextTask, $previousTaskId)
{
	$taskDetailTable = "pps_task_detail";
	$condition = ["task_detail_id" => $nextTask];
	$taskDetailData = GetData($taskDetailTable, $condition, 1);

	$deptEmpTable = 'pps_dept_emp_map';
	$condition = 'dept_id = ' . $taskDetailData[0]['dept_id'];
	$deptEmpData = GetData($deptEmpTable, $condition);

	$data = [
		"parent_task_id" => $previousTaskId,
		"order_list_id"  => $task['order_list_id'],
		"order_id"  => $task['order_id'],
		"item_id"  => $task['item_id'],
		"supervisor_id"   => $deptEmpData['supervisor_id'],

		"task_detail_id"    => $nextTask,

		"status"       => "Not started",

		"created_by"    => 1,
		"updated_by"    => 1
	];
	$taskTable = "pps_task_list";

	$result = InsertData($taskTable, $data);

	return $result;
}
function InsertInputs($task)
{
	$data = [
		'task_id' => $task['task_id'],
		'in_colour'     => $task['out_colour'],
		'in_length'     => $task['out_length'],
		'in_ext_size'    => $task['out_ext_size'],
		'in_texture'    => $task['out_texture'],
		'in_quantity'   => $task['out_qty'],
		'in_type'       => $task['out_type'],
		'created_by'    => 1,
		'updated_by'    => 1
	];
	$inputTable = "pps_task_inputs";
	$result = InsertData($inputTable, $data);
}
function InsertQaTask($parentTask, $parentTaskDetail, $workStatus = "Not started")
{
	$taskDetailTable = "pps_task_detail";
	$condition = 'parent_task = ' . $parentTaskDetail;
	$qaTaskDetail =  GetData($taskDetailTable, $condition);


	$data = [
		'is_qa'          => "1",
		'parent_task_id'  => $parentTask['task_id'],
		'order_list_id'   => $parentTask['order_list_id'],
		'order_id'       => $parentTask['order_id'],
		'item_id'        => $parentTask['item_id'],
		'supervisor_id'  => $parentTask['supervisor_id'],
		'task_detail_id'  => $qaTaskDetail['task_detail_id'],
		'status'        => $workStatus,
		'created_by'     => 1,
		'updated_by'     => 1
	];
	if ($workStatus == "Completed") {
		$data['start_time'] = $parentTask['start_time'];
		$data['end_time'] = $parentTask['end_time'];
	}
	$taskTable = "pps_task_list";

	$qaTaskId =  InsertData($taskTable, $data);


	return $qaTaskId;
}

function GetStartAndEndTime($tasks)
{
	$startTime = null;
	$endTime = null;
	foreach ($tasks as $key => $value) {
		$startTime = $startTime ?: $value['start_time'];
		$endTime = $endTime ?: $value['end_time'];
		if ($startTime > $value['start_time']) {
			$startTime = $value['start_time'];
		}
		if ($endTime < $value['end_time']) {
			$endTime = $value['end_time'];
		}
	}
	$result = [
		"startTime" => $startTime,
		"endTime" => $endTime
	];
	return $result;
}

$logFilePath = "";




$message = "Process completed";
echo ($message);
//LogMessage($message);

exit();

function GetData($tableName, $condition, $limit = null)
{
	$dbConn = ConnectDB();
	//'task_id', 'is_qa', 'is_split', 'part', 'split_from', 'parent_task_id', 'order_list_id', 'order_id', `item_id`, `employee_id`, `supervisor_id`, `start_time`, `end_time`, `time_taken`, `task_detail_id`, `sizing`, `out_length`, `out_texture`, `out_colour`, `out_qty`, `out_ext_size`, `out_type`, `status`, `next_task_id`,
	if ($limit) {
		$limit = "Limit " . $limit;
	}
	//$limit = (!$limit) ?: "Limit " . $limit;
	$query = "SELECT  * FROM $tableName where $condition  $limit;";
	$result = mysqli_query($dbConn, $query)->fetch_all(MYSQLI_ASSOC);
	mysqli_close($dbConn);
	return $result;
}

function UpdateData($tableName, $condition, $data)
{
	$dbConn = ConnectDB();
	$query = "UPDATE $tableName  SET $data where $condition;";
	$result = mysqli_query($dbConn, $query);
	mysqli_close($dbConn);
	return $result;
}

function InsertData($tableName, $data)
{
	$dbConn = ConnectDB();
	$key = array_keys($data);
	$values = array_values($data);
	$query = "INSERT INTO $tableName  ( " . implode(',', $key) . ") VALUES('" . implode("','", $values) . "')";

	$result = mysqli_query($dbConn, $query);
	if ($result) {
		// Get the last inserted ID
		$insertedId = mysqli_insert_id($dbConn);
	}
	mysqli_close($dbConn);
	return $insertedId;
}
function ConnectDB()
{
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "pps";

	// $servername = "nps2024.c77lbckdfmrs.us-west-2.rds.amazonaws.com";
	// $username = 'admin';
	// $password = 'Admin2024';
	// $dbname = "pps";


	try {
		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);

		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
	} catch (Throwable $ex) {
		print_r("Exception: " . $ex->getMessage());
		exit();
	}
	$message = "database connected";
	echo ($message);
	//LogMessage($message);
	return $conn;
}

function CreateLogFile()
{
	global $logFilePath;
	$curr_date = date('Y-m-d H:i:s');
	$logFilePath = '/var/www/html/public_html/jobs/Logs/log-' . $curr_date . '.txt';
	if (file_exists($logFilePath)) {
		echo "File exists!";
		return true;
	} else {
		// Create a new file
		$file = fopen($logFilePath, "w");

		if ($file) {
			echo "New file created!";
			fclose($file);
			return true;
		} else {
			echo "Error creating the file!";
			return false;
		}
	}
}

function LogMessage($message)
{

	global $logFilePath;
	// Create a log message
	$logMessage = date('Y-m-d H:i:s') . ' - ' . $message;

	// Write the log message to the file
	file_put_contents($logFilePath, $logMessage . PHP_EOL, FILE_APPEND);
}
