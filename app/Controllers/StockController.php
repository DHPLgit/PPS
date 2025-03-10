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
use App\Libraries\EnumsAndConstants\Stock;

use function PHPUnit\Framework\isEmpty;

require_once APPPATH . 'Libraries/EnumsAndConstants/Enums.php';
require_once APPPATH . 'Libraries/EnumsAndConstants/Constants.php';
class StockController extends BaseController
{

    //search stock for task initialize.
    public function SearchStocks()
    {
        $request = $this->request->getPost();
        $data = [];
        if ($request["colour"] != "") $data[Stock::Colour] = $request["colour"];
        if ($request["texture"] != "") $data[Stock::Texture] = $request["texture"];
        if ($request["length"] != "") $data[Stock::Length] = $request["length"];

        $flag = false;
        $result = [];
        if (count($data) > 0) {
            $data[Stock::ActiveStatus] = "1";
            $model = ModelFactory::createModel(ModelNames::Stock);
            $result =  $model->GetStockByCondition($data);
            $flag = (count($result) > 0) ? true : false;
        }
        echo json_encode(['success' => $flag, 'csrf' => csrf_hash(), 'output' => $result]);
    }

    // ----------------------------Stock Uploads----------------------------------------

    public function StockUploads()
    {
        $request = $this->request->getGet();
        $request["query"] = isset($request["query"]) ? $request["query"] : "";

        if ($this->request->getMethod() == 'get') {

            $result = $this->GetStockData($request);
            $empstocklist = $result[0];
            $stdData = $result[1];
            $pageLinks = $result[2];

            return view('stockdetails', ["empstocklist" => $empstocklist, "stdData" => $stdData, "query" => $request["query"],"pageLinks" => $pageLinks]);
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
                            $csvArr = array();

                            while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
                                $num = count($filedata);
                                if ($i > 0) {
                                    $csvArr[$i]['Date'] = date("Y-m-d", strtotime($filedata[1]));
                                    $csvArr[$i]['Color'] = $filedata[2];
                                    $csvArr[$i]['Work order'] = substr($filedata[0], 0, 11);
                                    $csvArr[$i]['Texure'] = $filedata[3];
                                    $csvArr[$i]['Size'] = $filedata[4];
                                    $csvArr[$i]['IN'] = $filedata[5];
                                    $csvArr[$i]['Type'] = $filedata[6];
                                    $csvArr[$i]['Ext size'] = $filedata[7];
                                };

                                $i++;
                            }

                            fclose($file);
                            $count = 0;
                            foreach ($csvArr as $exportData) {
                                $flag = $this->StockDataExists($exportData['Work order']);
                                if ($flag) {
                                    $this->InsertStock($exportData);
                                    $count++;
                                }
                            }

                            // Set success message using session service
                            session()->setFlashdata('success', 'File uploaded successfully. ' . $count . ' records were inserted.');
                        }
                    }
                } catch (Exception $ex) {
                    // Handle exception and set error message
                    session()->setFlashdata('error', 'An error occurred during the upload process.');
                }

                $result = $this->GetStockData($request);
                $empstocklist = $result[0];
                $stdData = $result[1];
                $pageLinks = $result[2];
                return view('stockdetails', ["empstocklist" => $empstocklist, "stdData" => $stdData, "query" => $request["query"], "pageLinks" => $pageLinks]);
            }
        }
    }

    public function StockDataExists($stockId)
    {
        $model = ModelFactory::createModel(ModelNames::Stock);
        // Perform a query to check if the stock exists based on the emp_code
        $result = $model->where('stock_id', $stockId)->first();

        // If the result is not empty, the stock exists
        if ($result) {
            return false;
        }
        return true;
    }
    public function GetStockData($request)
    {
        // $request = $this->request->getGet();
        $model = ModelFactory::createModel(ModelNames::Stock);
        $currentPage = $this->request->getGet('page') ? $this->request->getGet('page') : 1;

        // Define the number of records per page
        $perPage = 20;

        // Calculate the offset
        $offset = ($currentPage - 1) * $perPage;
       $a=empty($request["query"]);
        if (isset($request["query"]) && !empty($request["query"])) {
            $result = $model->GetStockList( $perPage, $offset,$request["query"]);
        } else {
            $result = $model->GetStockList($perPage, $offset);
        }
        $totalRecords = $result[0];
        $stockList = $result[1];
        $pageLinks = GetPaginationLinks($totalRecords, 20, $currentPage);
        $stdData = GetJson();
        $result = [$stockList, $stdData, $pageLinks];
        return $result;
    }
    public function GetUniqueStockData()
    {
        $stockCode = $this->request->getPost('id');
        // Fetch the unique Stock data based on the stock id
        $model = ModelFactory::createModel(ModelNames::Stock);
        $condition = [Stock::StockListId => $stockCode];
        $stockData = $model->where($condition)->first();
        return view('stock_details', ["stock" => $stockData]);
    }
    private function InsertStock(array $postdata)
    {
        $model = ModelFactory::createModel(ModelNames::Stock);
        try {
            $data = [
                "stock_id" => $postdata['Work order'],
                "colour" => $postdata['Color'],
                "length" => $postdata['Size'],
                "texture" => $postdata['Texure'],
                "quantity" => floatval($postdata['IN']),
                "date" => $postdata['Date'],
                "type" => $postdata['Type'],
                "ext_size" => $postdata['Ext size']

            ];
            $stockId = $model->insert($data);
        } catch (Exception $ex) {
            print_r($ex);
        }

        return $stockId;
    }

    // ----------------------------Stock Uploads----------------------------------------

    public function DeleteStockDetail()
    {
        if ($this->request->getMethod() == 'post') {
            try {

                $request = $this->request->getPost();

                $model = ModelFactory::createModel(ModelNames::Stock);
                //$data = [Stock::DeleteStatus => "0"];
                //$delete_status = $model->UpdateStock($request['id'], $data);
                $delete_status = $model->DeleteStock($request['id']);

                if ($delete_status) {
                    $status = "Stock data deleted successfully!";
                } else
                    $status = "Something went wrong!";
                session()->setFlashdata('response', $status);

                //$response = Response::SetResponse(201, null, new Error());

                return json_encode(['success' => $delete_status, 'csrf' => csrf_hash(), 'url' => base_url('/stock/upload')]);
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
