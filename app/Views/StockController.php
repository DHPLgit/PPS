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

require_once APPPATH . 'Libraries/EnumsAndConstants/Enums.php';
require_once APPPATH . 'Libraries/EnumsAndConstants/Constants.php';
class StockController extends BaseController
{

    public function SearchStocks()
    {
        $request = $this->request->getPost();
        $data = [];
        // foreach ($request as $key => $value) {
        # code...
        if ($request["colour"] != "") $data[Stock::Colour] = $request["colour"];
        if ($request["texture"] != "") $data[Stock::Texture] = $request["texture"];
        if ($request["length"] != "") $data[Stock::Length] = $request["length"];
        // }

        //$data=[Stock::Colour=>,Stock::Length=> Stock::]
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

        if ($this->request->getMethod() == 'get') {
            $empstocklist = $this->GetStockData();
            return view('stockdetails', ["empstocklist" => $empstocklist]);
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
                try {
                    if ($file = $this->request->getFile('formData')) {
                        if ($file->isValid() && !$file->hasMoved()) {
                            $newName = $file->getRandomName();
                            $file->move('../public/csvfile', $newName);
                            $file = fopen("../public/csvfile/" . $newName, "r");
                            $i = 0;
                            $numberOfFields = 6;
                            $csvArr = array();

                            while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
                                $num = count($filedata);
                                if ($i > 0 && $num == $numberOfFields) {
                                    $csvArr[$i]['Date'] = date("Y-m-d", strtotime($filedata[1]));
                                    $csvArr[$i]['Color'] = $filedata[2];
                                    $csvArr[$i]['Work order'] = $filedata[0];
                                    $csvArr[$i]['Texure'] = $filedata[3];
                                    $csvArr[$i]['Size'] = $filedata[4];
                                    $csvArr[$i]['IN'] = $filedata[5];

                                }
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
                        }
                        //echo json_encode(['success' => true, 'csrf' => csrf_hash(), "count" => $count]);
                    }
                } catch (Exception $ex) {
                }
                $empstocklist = $this->GetStockData();

                return view('stockdetails', ["empstocklist" => $empstocklist]);
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
    public function GetStockData()
    {
        $model = ModelFactory::createModel(ModelNames::Stock);
        // Perform a query to check if the employee exists based on the emp_code
        //  $condition=[Stock::DeleteStatus=>"1"];
        $result = $model->GetStockList();
        return $result;
    }
    public function GetUniqueStockData()
    {
        $stockCode = $this->request->getPost('id');
        // Fetch the unique Stock data based on the stock id
        $model = ModelFactory::createModel(ModelNames::Stock);
        $stockData = $model->where("stock_id", $stockCode)->first();
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
