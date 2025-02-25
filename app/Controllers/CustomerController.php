<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\EnumsAndConstants\Customer;
use App\Libraries\EnumsAndConstants\Employee;
use PHPMailer\PHPMailer\PHPMailer;
use Exception;
use CodeIgniter\Database\Exceptions\DatabaseException;
use App\Libraries\Response\Response;
use App\Libraries\Response\Error;
use App\Models\ModelFactory;
use App\Libraries\EnumsAndConstants\ModelNames;
use App\Models\ModelHelper;

require_once APPPATH . 'Libraries/EnumsAndConstants/Enums.php';
require_once APPPATH . 'Libraries/EnumsAndConstants/Constants.php';
class CustomerController extends BaseController
{
    private $modelHelper;

    public function __construct()
    {
        $this->modelHelper = new ModelHelper();
    }
    public function UploadCustomer()
    {
        if ($this->request->getMethod() == 'get') {
            $customerList = $this->GetCustomerList();
            return view('customerList', ["customerList" => $customerList]);
        } else {
            $rules = [
                'formData' => 'uploaded[formData]|max_size[formData,2048]|ext_in[formData,csv]'
            ];
            $errors = [
                'formData' => [
                    'max_size' => 'Uploaded file size is more than 2MB.',
                    'ext_in' => 'Uploaded file is not a CSV file.'
                ]
            ];

            $input = $this->validate($rules, $errors);

            if (!$input) {
                $data = $this->validator->getErrors();
                echo json_encode(['success' => false, 'validation' => $data, 'csrf' => csrf_hash()]);
            } else {
                try {
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
                                    $csvArr[$i]['name'] = $filedata[1];
                                }
                                $i++;
                            }

                            fclose($file);
                            $count = 0;
                            foreach ($csvArr as $exportData) {
                                $flag = $this->DataExists($exportData['name']);
                                if ($flag) {
                                    $this->InsertCustomer($exportData);
                                    $count++;
                                }
                            }
                            // Set success message and redirect
                            session()->setFlashdata('success', 'File uploaded successfully. ' . $count . ' records were inserted.');
                        }
                    }
                } catch (Exception $ex) {
                    // Handle exception and set error message
                    session()->setFlashdata('error', 'An error occurred during the upload process.');
                }
                $customerList = $this->GetCustomerList();
                return view('customerList', ["customerList" => $customerList]);
            }
        }
    }

    public function DataExists($customerName)
    {
        $model = ModelFactory::createModel(ModelNames::Employee);
        $result = $this->modelHelper->GetSingleData($model, Customer::CustomerName, $customerName);

        if ($result) {
            return false;
        }
        return true;
    }
    public function GetCustomerList()
    {
        $model = ModelFactory::createModel(ModelNames::Customer);
        $condition = [Customer::Status => "1"];
        $result = $this->modelHelper->GetAllDataUsingWhere($model, $condition);
        return $result;
    }
    private function InsertCustomer(array $postdata)
    {

        $model = ModelFactory::createModel(ModelNames::Customer);
        $data = [
            "customer_name" => $postdata['Name'],
        ];
        $userId = $this->modelHelper->InsertData($model, $data);

        return $userId;
    }

    public function DeleteCustomer()
    {
        if ($this->request->getMethod() == 'post') {
            try {

                $request = $this->request->getPost();

                $model = ModelFactory::createModel(ModelNames::Customer);
                $data = [Customer::Status => "0"];
                $delete_status = $this->modelHelper->UpdateData($model, $request['id'], $data);

                if ($delete_status) {
                    $status = "Customer deleted successfully!";
                } else
                    $status = "Something went wrong!";
                session()->setFlashdata('response', $status);

                return json_encode(['success' => $delete_status, 'csrf' => csrf_hash(), 'url' => base_url('/employee/upload')]);
            } catch (DataBaseException $ex) {

                $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
            } catch (Exception $ex) {

                $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
            }
            return json_encode($response);
        }
    }
}
