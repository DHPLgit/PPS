<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use PHPMailer\PHPMailer\PHPMailer;
use Exception;
use CodeIgniter\Database\Exceptions\DatabaseException;
use App\Libraries\Response\Response;
use App\Libraries\Response\Error;
use App\Models\ModelFactory;
use App\Libraries\EnumsAndConstants\ModelNames;

require_once APPPATH . 'Libraries/EnumsAndConstants/Enums.php';
require_once APPPATH . 'Libraries/EnumsAndConstants/Constants.php';
class EmployeeController extends BaseController
{


    public function EmployeeUploads()
    {
        if ($this->request->getMethod() == 'get') {
            $emplist = $this->GetEmployeeData();
            return view('employeedetails', ["emplist" => $emplist]);
        } else {
            $rules = [
                'formData' => 'uploaded[formData]|max_size[formData,2048]|ext_in[formData,csv]'
            ];
            $errors =
                [
                    'formData' =>
                    [
                        'max_size' => 'Uploaded file size is more than 2mb',
                        'ext_in' => "Uploaded file is not a csv file"
                    ]
                ];
            $input = $this->validate($rules, $errors);
            if (!$input) {
                $data = $this->validator->getErrors();
                echo json_encode(['success' => false, 'validation' => $data, 'csrf' => csrf_hash()]);
            } else {
                if ($file = $this->request->getFile('formData')) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $newName = $file->getRandomName();
                        $file->move('../public/csvfile', $newName);
                        $file = fopen("../public/csvfile/" . $newName, "r");
                        $i = 0;
                        $numberOfFields = 7;
                        $csvArr = array();
                        while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {

                            $num = count($filedata);
                            if ($i > 0 && $num == $numberOfFields) {
                                $csvArr[$i]['Emp code'] = $filedata[0];
                                $csvArr[$i]['Name'] = $filedata[1];
                                $csvArr[$i]['DOB'] = date("Y-m-d", strtotime($filedata[2]));
                                $csvArr[$i]['DOJ'] = date("Y-m-d", strtotime($filedata[3]));
                                $csvArr[$i]['Designation'] = $filedata[4];
                                $csvArr[$i]['Phone_no'] = $filedata[5];
                                $csvArr[$i]['Address'] = $filedata[6];
                            }
                            $i++;
                        }

                        fclose($file);
                        $count = 0;
                        foreach ($csvArr as $exportData) {
                            $flag = $this->DataExists($exportData['Emp code']);
                            if ($flag) {

                                $this->InsertEmployee($exportData);
                                $count++;
                            }
                        }
                    }
                    //echo json_encode(['success' => true, 'csrf' => csrf_hash(), "count" => $count]);
                   
                }
                $emplist = $this->GetEmployeeData();
                return view('employeedetails', ["emplist" => $emplist]);
            }
        }
    }

    public function DataExists($emp_code)
    {
        $model = ModelFactory::createModel(ModelNames::Employee);
        // Perform a query to check if the employee exists based on the emp_code
        $result = $model->where('emp_code', $emp_code)->first();

        // If the result is not empty, the employee exists
        if ($result) {
            return false;
        }
        return true;
    }
    public function GetEmployeeData()
    {
        $model = ModelFactory::createModel(ModelNames::Employee);
        // Perform a query to check if the employee exists based on the emp_code
        //$condition=[Employee::Status=>"1"];
        $result = $model->GetAllEmployee();
        return $result;
    }
    public function GetUniqueEmployeeData()
    {
        $empCode = $this->request->getPost('id');

        // Fetch the unique employee data based on the empCode
        $model = ModelFactory::createModel(ModelNames::Employee);
        $employeeData = $model->where("id", $empCode)->first(); 
        return view('employee_details', ["employee" =>  $employeeData]);
    }
    private function InsertEmployee(array $postdata)
    {

        $model = ModelFactory::createModel(ModelNames::Employee);
        $data = [
            "name" => $postdata['Name'],
            "emp_code" => $postdata['Emp code'],
            "designation" => $postdata['Designation'],
            "dob" => $postdata['DOB'],
            "phone_no" => $postdata['Phone_no'],
            "doj" => $postdata['DOJ'],
            "address" => $postdata['Address'],
        ];
        $userId = $model->insert($data);

        return $userId;
    }


    public function EmployeeDetails()
    {

        try {

            if ($this->request->getMethod() == 'get') {
                log_message('debug', 'Data received: ' . print_r($this->request->getPost(), true));
                $emplist = $this->GetEmployeeData();
                return view('employeedetails', ["emplist" => $emplist]);
            } elseif ($this->request->getMethod() == 'post') {
                $rules = [
                    'first_name' => 'required|alpha',
                    'last_name' => 'required|alpha',
                    'userid' => 'required|numeric',
                    'phone_no' => 'required|numeric|exact_length[10]',
                    'date_of_joining' => 'required|valid_date[Y-m-d]',
                    'appreciation' => 'required',
                    'supervisor' => 'required|alpha',

                ];

                $errors = [

                    'first_name' => [
                        'required' => 'First name field is required'
                    ],
                    'last_name' => [
                        'required' => 'Last name field is required'
                    ],
                    'userid' => [
                        'required' => 'You must choose a Employee ID.',
                        'ValidateUserid' => 'Employee ID is already present.'
                    ],
                    'phone_no' => [
                        'required' => 'Please enter your Phone Number',
                        'Validatephone_no' => 'Phone Number is already present.'
                    ],
                    'supervisor' => [
                        'required' => 'You must choose a Supervisor.',
                        // 'ValidateUserName' => 'User name is already present.'
                    ],
                    'date_of_joining' => [
                        'required' => 'You must choose a Date.',
                        'validatedate_of_joining' => 'Date of Joining is invalid or already present.',
                    ],


                ];

                if (!$this->validate($rules, $errors)) {
                    log_message('debug', 'Validation errors: ' . print_r($this->validator->getErrors(), true));
                    $output = $this->validator->getErrors();
                    $errorMsg = implode(";", $output);
                    //$response = Response::SetResponse(400, null, new Error($errorMsg));

                    return json_encode(['success' => false, 'csrf' => csrf_hash(), 'error' => $output]);
                } else {

                    $request = $this->request->getPost();
                    $userId = $this->InsertEmployee($request);
                    // $emailstatus = $this->CreateTemplateForMailReg($request, $userId);
                    // $response = Response::SetResponse(201, null, new Error());
                    return json_encode(['success' => true, 'csrf' => csrf_hash()]);
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

    public function DeleteEmployeeDetail()
    {

        if ($this->request->getMethod() == 'post') {
            try {

                $request = $this->request->getPost();

                $model = ModelFactory::createModel(ModelNames::Employee);
                //$data = [Employee::Status => "0"];
               // $delete_status = $model->UpdateEmployee($request['id'], $data);
                $delete_status = $model->DeleteEmployee($request['id']);

                if ($delete_status) {
                    $status = "Employee deleted successfully!";
                } else $status = "Something went wrong!";
                session()->setFlashdata('response', $status);

                //$response = Response::SetResponse(201, null, new Error());

                return json_encode(['success' => $delete_status, 'csrf' => csrf_hash(), 'url' => base_url('/employee/upload')]);
                // return redirect()->to(base_url("task/taskList"));
            } catch (DataBaseException $ex) {

                $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
            } catch (Exception $ex) {

                $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
            }
            return json_encode($response);
        }
    }
}
