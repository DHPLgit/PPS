<?php

namespace App\Controllers;

use App\Models\ModelHelper;
use App\Controllers\BaseController;
use App\Models\ModelFactory;
use App\Libraries\EnumsAndConstants\ModelNames;
use App\Libraries\EnumsAndConstants\WorkStatus;
use App\Libraries\EnumsAndConstants\WtStd;
use Exception;
use CodeIgniter\Database\Exceptions\DatabaseException;
use App\Libraries\Response\Response;
use App\Libraries\Response\Error;
use App\Libraries\EnumsAndConstants\OrderItems;
use App\Libraries\TokenManagement\TokenManagement;
use Config\Services;

use function PHPUnit\Framework\containsOnly;

require_once APPPATH . 'Libraries/EnumsAndConstants/Enums.php';
require_once APPPATH . 'Libraries/EnumsAndConstants/Constants.php';
class OrderController extends BaseController
{
    private $userData;
    public $modelHelper;


    public function __construct()
    {
        $this->userData = session()->get();
        $this->modelHelper = new ModelHelper();
    }
    //function to get the order details,by passing no value;returning the json -order
    public function GetOrderList()
    {
        try {

            //$order_id = $this->request->getGet('Order_list_id');
            $model = ModelFactory::createModel(ModelNames::OrderItems);
            $selectArray = [OrderItems::OrderListId, OrderItems::OrderId, OrderItems::ItemId, OrderItems::ReferenceId, OrderItems::CustomerId, OrderItems::OrderDate, OrderItems::Type, OrderItems::Colour, OrderItems::Length, OrderItems::Texture, OrderItems::ExtSize, OrderItems::Unit, OrderItems::BundleCount, OrderItems::Quantity, OrderItems::Status, OrderItems::DueDate];
            $orderList = $model->select($selectArray)->orderBy(OrderItems::UpdatedAt,"desc")->findAll();

            $response = Response::SetResponse(200, $orderList, new Error());
            return view('order/orderList', ["orderList" => $orderList]);
        } catch (DataBaseException $ex) {

            $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
        } catch (Exception $ex) {

            $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
        }
        return json_encode($response);
    }

    public function SearchOrder()
    {
        try {

            $query = $this->request->getPost('like');
            $model = ModelFactory::createModel(ModelNames::OrderItems);
            $selectArr = [OrderItems::OrderId, OrderItems::OrderListId, OrderItems::ItemId, OrderItems::Colour, OrderItems::Length, OrderItems::Texture, OrderItems::Quantity];
            $result = $model->select($selectArr)->like(OrderItems::OrderId, $query, 'after')->findAll();

            //$response = Response::SetResponse(200, $result, new Error());

            return json_encode(['success' => true, 'csrf' => csrf_hash(), 'output' => $result]);
        } catch (DataBaseException $ex) {

            $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
        } catch (Exception $ex) {

            $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
        }
        return json_encode($response);
    }

    public function CreateOrder()
    {


        if (($_SERVER["REQUEST_METHOD"] == "POST")) {
            try {

                $rules = [
                    'order_id'      => 'required|numeric',
                  //  'item_id'  => 'required|validateOrderId[item_id]',

                    'reference_id'  => 'required',
                    //'customer_id'      => 'required',
                    'order_date'    => 'required|valid_date[Y-m-d]',
                    'type'          => 'required',
                    'colour'        => 'required',
                    'length'        => 'required',
                    'texture'       => 'required',
                    'ext_size'      => 'required',
                    'unit'          => 'required',
                    // 'is_bundle'        => 'required',
                    'bundle_count'  => 'CheckBundle[bundle_count]',
                    'quantity'      => 'required|greater_than[0]',
                    'due_date'      => 'required|valid_date[Y-m-d]|validateDueDate[due_date]',

                ];

                $errors = [
                    'order_id'     => ['numeric' => "Order id is required."],
                   // 'item_id'     => ['validateOrderId' => "Item id is already present."],
                    'reference_id'            => ['required' => "Reference id is required"],
                    'order_date'          => [
                        'required'        => "Order date is required",
                        'valid_date'      => "Date should be in dd-mm-yyyy format."
                    ],
                    'unit' => [
                        'required' => 'Please select any one.',
                    ],
                    'quantity'            => ['required' => "Quantity is required"],
                    'due_date'            => [
                        'required'        => "Due date is required",
                        'validateDueDate' => 'Due date is less than or equal to the order date.',
                        'valid_date'      => "Date should be in dd-mm-yyyy format."
                    ]

                ];
                if (!$this->validate($rules, $errors)) {

                    $output = $this->validator->getErrors();
                    $errorMsg = implode(";", $output);
                    //$response = Response::SetResponse(400, null, new Error($errorMsg));
                    return json_encode(['success' => false, 'csrf' => csrf_hash(), 'error' => $output]);
                    // $jsonFile = file_get_contents('../public/uploads/dropdown.json');
                    // $data = json_decode($jsonFile);

                    // return view('order/createOrder', ["validation" => $this->validator, "json" => $data]);
                } else {
                    $request = $this->request->getPost();

                    $data = [
                        OrderItems::OrderId        => $request['order_id'],
                        OrderItems::ItemId         => $request['item_id'],
                      //  Order::CustomerId     => $request['customer_id'],
                        OrderItems::ReferenceId    => $request['reference_id'],
                        OrderItems::OrderDate      => $request['order_date'],
                        OrderItems::Type           => $request['type'],
                        OrderItems::Colour         => $request['colour'],
                        OrderItems::Length         => $request['length'],
                        OrderItems::Texture        => $request['texture'],
                        OrderItems::ExtSize        => $request['ext_size'],
                        OrderItems::Unit           => $request['unit'],
                        OrderItems::BundleCount    => $request['bundle_count'],
                        OrderItems::Quantity       => $request['quantity'],
                        OrderItems::Status         => WorkStatus::NS,
                        OrderItems::DueDate        => $request['due_date'],
                        // 'Overdue'            =>$request['Colour'],
                        OrderItems::CreatedBy      => session()->get('id'),
                        OrderItems::UpdatedBy      => session()->get('id')
                    ];
                    $model = ModelFactory::createModel(ModelNames::OrderItems);

                    $result = $model->insert($data);

                    if ($result) {
                        $createdOrderId = $data[OrderItems::OrderId] . " - " . $data[OrderItems::ItemId];
                        $status = "$createdOrderId order created successfully!";
                    } else $status = "Something went wrong!";
                    session()->setFlashdata('response', $status);
                    //$response = Response::SetResponse(201, null, new Error());
                    return json_encode(['success' => true, 'csrf' => csrf_hash(), 'url' => base_url('/order/orderList')]);
                }
            } catch (DataBaseException $ex) {

                $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
            } catch (Exception $ex) {

                $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
            }
            return json_encode($response);
        } else {
            $jsonFile = file_get_contents('../public/uploads/dropdown.json');
            $data = json_decode($jsonFile);

            return view("order/createOrder", ["json" => $data]);
        }
    }
    public function EditOrder($orderListId)
    {
        if ($this->request->getMethod() == 'get') {
            $jsonFile = file_get_contents('../public/uploads/dropdown.json');
            $data = json_decode($jsonFile);
            $model = ModelFactory::createModel(ModelNames::OrderItems);

            $order = $model->where(OrderItems::OrderListId, $orderListId)->first();

            $order[OrderItems::OrderDate] = date("Y-m-d", strtotime($order[OrderItems::OrderDate]));
            $order[OrderItems::DueDate] = date("Y-m-d", strtotime($order[OrderItems::DueDate]));
            return view("order/createOrder", ["json" => $data, "editOrderData" => $order]);
        } else
        if ($this->request->getMethod() == 'post') {
            try {

                $rules = [
                    //'order_id'  => 'required|validateOrderId[order_id]',
                    //'customer_id'      => 'required',
                    'reference_id'     => 'required',
                    'order_date'       => 'required|valid_date[Y-m-d]',
                    'type'             => 'required',
                    'colour'           => 'required',
                    'length'           => 'required',
                    'texture'          => 'required',
                    'ext_size'         => 'required',
                    'unit'           => 'required',
                    // 'is_bundle'        => 'required',
                    'bundle_count'            => 'CheckBundle[bundle_count]',
                    'quantity'         => 'required|greater_than[0]',
                    'due_date'         => 'required|valid_date[Y-m-d]|validateDueDate[due_date]',

                ];

                $errors = [
                    //'order_id'  => ['validateOrderId' => "Order id is already present."],
                    'reference_id'            => ['required' => "Reference id is required"],
                    'order_date'          => [
                        'required'        => "Order date is required",
                        'valid_date'      => "Date should be in dd-mm-yyyy format."
                    ],
                    'unit' => [
                        'required' => 'Please select any one.',
                    ],
                    'quantity'            => ['required' => "Quantity is required"],
                    'due_date'            => [
                        'validateDueDate' => 'Due date is less than or equal to the order date.',
                        'valid_date'      => "Date should be in dd-mm-yyyy format."
                    ]

                ];
                if (!$this->validate($rules, $errors)) {

                    $output = $this->validator->getErrors();
                    $errorMsg = implode(";", $output);
                    //$response = Response::SetResponse(400, null, new Error($errorMsg));

                    return json_encode(['success' => false, 'csrf' => csrf_hash(), 'error' => $output]);
                    //return view('admin/emailtemplate', ["validation" => $this->validator]);
                } else {
                    $request = $this->request->getPost();

                    //$key='Order_unique_id'=>$request['Order_unique_id'];
                    $data = [
                        OrderItems::OrderId        => $request['order_id'],
                        OrderItems::ItemId         => $request['item_id'],
                      //  Order::CustomerId     => $request['customer_id'],
                        OrderItems::OrderDate      => $request['order_date'],
                        OrderItems::ReferenceId     => $request['reference_id'],
                        OrderItems::Type           => $request['type'],
                        OrderItems::Colour         => $request['colour'],
                        OrderItems::Length         => $request['length'],
                        OrderItems::Texture        => $request['texture'],
                        OrderItems::ExtSize        => $request['ext_size'],
                        OrderItems::Unit          => $request['unit'],
                        OrderItems::BundleCount      => $request['bundle_count'],
                        OrderItems::Quantity       => $request['quantity'],
                        OrderItems::Status         => WorkStatus::NS,
                        OrderItems::DueDate        => $request['due_date'],
                        // 'Overdue'            =>$request['Colour'],
                        OrderItems::CreatedBy      => session()->get('id'),
                        OrderItems::UpdatedBy      => session()->get('id')

                    ];
                    $model = ModelFactory::createModel(ModelNames::OrderItems);

                    $update_status = $model->update($orderListId, $data);

                    if ($update_status) {
                        $updatedOrderId = $data[OrderItems::OrderId] . " - " . $data[OrderItems::ItemId];
                        $status = "$updatedOrderId order updated successfully!";
                    } else $status = "Something went wrong!";
                    session()->setFlashdata('response', $status);

                    // $response = Response::SetResponse(201, null, new Error());
                    return json_encode(['success' => true, 'csrf' => csrf_hash(), 'url' => base_url('/order/orderList')]);
                }
            } catch (DataBaseException $ex) {

                $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
            } catch (Exception $ex) {

                $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
            }
            return json_encode($response);
        }
    }
    public function DeleteOrder()
    {
        if ($this->request->getMethod() == 'post') {
            try {


                $request = $this->request->getPost();

                $model = ModelFactory::createModel(ModelNames::OrderItems);

                $delete_status = $model->where(OrderItems::OrderListId, $request['orderListId'])->delete();

                if ($delete_status) {
                    $status = "order deleted successfully!";
                } else $status = "Something went wrong!";
                session()->setFlashdata('response', $status);

                //$response = Response::SetResponse(201, null, new Error());

                // return json_encode(['success' => true, 'csrf' => csrf_hash(), 'url' => base_url('/order/orderList')]);

                return redirect()->to(base_url('/order/orderList'));
            } catch (DataBaseException $ex) {

                $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
            } catch (Exception $ex) {

                $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
            }
            return json_encode($response);
        }
    }

    public function GenerateOrderId()
    {
        if ($this->request->getMethod() == 'get') {
            try {

                $model = ModelFactory::createModel(ModelNames::OrderItems);
                $selectArr = [OrderItems::OrderId, OrderItems::ItemId];
                $result = $model->select($selectArr)->orderBy(OrderItems::OrderId, "desc")->first();

                if ($result) {
                    $orderId = $result[OrderItems::OrderId] + 1;
                    $itemId = 1;
                    $nextId = [$orderId, $itemId];
                } else $nextId = [1, 1];
                //$response = Response::SetResponse(201, null, new Error());

                return json_encode(['success' => true, 'csrf' => csrf_hash(), "output" => $nextId]);
            } catch (DataBaseException $ex) {

                $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
            } catch (Exception $ex) {

                $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
            }
            return json_encode($response);
        }
    }
    public function GenerateItemId($orderId)
    {
        // if ($this->request->getMethod() == 'get') {
        try {
            $data = $this->GetJson();
            // $orderListId = $this->request->getGet('orderListId');
            $result = $this->GetitemByOrderId($orderId);
            if($result){
            $itemId = $result[OrderItems::ItemId] + 1;
            $result[OrderItems::ItemId] = $itemId;
            }
            else{
                $result[OrderItems::OrderId] = 0;
                $result[OrderItems::ItemId] = 1;
            }
            $request=$this->request->getGet();
            if(isset($request["isAddItem"]) && $request["isAddItem"]==0){
                return json_encode(['success' => true, 'csrf' => csrf_hash(), "output" => $result]);
            }
            //  if ($result) {S
            //$orderId = $result[Order::OrderId] + 1;
            //$nextId = [$orderId, $itemId];
            // }
            //$response = Response::SetResponse(201, null, new Error());

            // return json_encode(['success' => true, 'csrf' => csrf_hash(), "output" => $itemId]);

            return view("order/createOrder", ["json" => $data, "item_ord_Id" => $result]);
        } catch (DataBaseException $ex) {

            $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
        } catch (Exception $ex) {

            $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
        }
        return json_encode($response);
    }

    public function GetOrder($orderListId)
    {
        $model = ModelFactory::createModel(ModelNames::OrderItems);

        $order = $model->where(OrderItems::OrderListId, $orderListId)->first();

        return $order;
    }
    public function GetitemByOrderId($orderId)
    {
        $model = ModelFactory::createModel(ModelNames::OrderItems);

        $order = $model->where(OrderItems::OrderId, $orderId)->orderBy(order::ItemId, "desc")->first();

        return $order;
    }

    public function GetJson()
    {
        $jsonFile = file_get_contents('../public/uploads/dropdown.json');
        $data = json_decode($jsonFile);

        return $data;
    }

    public function CalculateWeight()
    {
        try {
            $request =  $this->request->getGet();

            $productType = strtoupper($request["product_type"]);
            if (str_contains($productType, "POLY") || str_contains($productType, "CYLINDER")) {
                $model = ModelFactory::createModel(ModelNames::WtStd);
                $condition = [WtStd::Length => $request["length"]];
                $result = $model->GetWeight($condition);
                $totalWt = 0;
                if ($result) {
                    $totalWt = $result[0]["weight"] * $request["count"];
                }
            } else $totalWt = $request["count"] * 30;
            return json_encode(['success' => true, 'csrf' => csrf_hash(), "output" => $totalWt]);
        } catch (DataBaseException $ex) {

            $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
        } catch (Exception $ex) {

            $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
        }
        return json_encode($response);
    }

    public function FilterOrder()
    {

        $request = $this->request->getGet();

        // $condition["key"] = Order::OrderId;
        // $condition["value"] = $request['query'];
        // $condition["side"] = "after";
        // $req[0]=$condition;
        $model = ModelFactory::createModel(ModelNames::OrderItems);

        $selectArray = [OrderItems::OrderListId, OrderItems::OrderId, OrderItems::ItemId, OrderItems::ReferenceId, OrderItems::CustomerId, OrderItems::OrderDate, OrderItems::Type, OrderItems::Colour, OrderItems::Length, OrderItems::Texture, OrderItems::ExtSize, OrderItems::BundleCount, OrderItems::Quantity, OrderItems::Status, OrderItems::DueDate];

        $orderList = $model->FilterOrder($selectArray, $request['query']);

        $res = [];
        foreach ($orderList as $key => $order) {

            $data = [
                "order_list_id" => $order[OrderItems::OrderListId],
                "order_id" => $order[OrderItems::OrderId],
                "item_id" => $order[OrderItems::ItemId],
                "reference_id" => $order[OrderItems::ReferenceId],
                "customer_id" => $order[OrderItems::CustomerId],
                "order_date" => $order[OrderItems::OrderDate],
                "item_description" => $order[OrderItems::Type] . " " . $order[OrderItems::Colour] . " " . $order[OrderItems::Length] . " " . $order[OrderItems::Texture] . " " . $order[OrderItems::Texture] . " " . $order[OrderItems::ExtSize],
                "bundle_count" => $order[OrderItems::BundleCount],
                "quantity" => $order[OrderItems::Quantity],
                "status" => $order[OrderItems::Status],
                "due_data" => $order[OrderItems::DueDate]
            ];
            array_push($res, $data);
        }

        //  $orderList= $this->modelHelper->GetDataUsingLike($model, $req);

        return json_encode(["success" => true, 'csrf' => csrf_hash(), 'output' => $res]);
        //  return view('order/orderList', ["orderList" => $orderList]);
    }
}
