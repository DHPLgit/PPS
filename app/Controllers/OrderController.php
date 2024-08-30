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
use App\Libraries\EnumsAndConstants\Order;
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
            $model = ModelFactory::createModel(ModelNames::Order);
            $selectArray = [Order::OrderListId, Order::OrderId, Order::ItemId, Order::CustomerId, Order::OrderDate, Order::Type, Order::Colour, Order::Length, Order::Texture, Order::ExtSize, Order::Unit, Order::BundleCount, Order::Quantity, Order::Status, Order::DueDate];
            $orderList = $model->select($selectArray)->where(Order::Status . "!=", WorkStatus::C)->orderBy(Order::OrderId)->findAll();

            foreach ($orderList as $key => $order) {
                # code...

                $orderId=$order[Order::OrderId];
            }
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
            $model = ModelFactory::createModel(ModelNames::Order);
            $selectArr = [Order::OrderId, Order::OrderListId, Order::ItemId, Order::Colour, Order::Length, Order::Texture, Order::Quantity];
            $result = $model->select($selectArr)->like(Order::OrderId, $query, 'after')->findAll();

            //$response = Response::SetResponse(200, $result, new Error());

            return json_encode(['success' => true, 'csrf' => csrf_hash(), 'output' => $result]);
        } catch (DataBaseException $ex) {

            $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
        } catch (Exception $ex) {

            $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
        }
        return json_encode($response);
    }

    public function GetItems()
    {

        $request = $this->request->getGet();

        // $condition["key"] = Order::OrderId;
        // $condition["value"] = $request['query'];
        // $condition["side"] = "after";
        // $req[0]=$condition;
        $model = ModelFactory::createModel(ModelNames::Order);

        $selectArray = [Order::OrderListId, Order::OrderId, Order::ItemId, Order::CustomerId, Order::OrderDate, Order::Type, Order::Colour, Order::Length, Order::Texture, Order::ExtSize, Order::BundleCount, Order::Quantity, Order::Status, Order::DueDate];

        $modelHelper= new ModelHelper();
        $condition=[Order::OrderId=>$request['orderId']];
        $orderList = $modelHelper->GetAllDataUsingWhere($model,$condition);

        $res = [];
        foreach ($orderList as $key => $order) {

            $data = [
                "order_list_id" => $order[Order::OrderListId],
                "order_id" => $order[Order::OrderId],
                "item_id" => $order[Order::ItemId],
                // "reference_id" => $order[Order::ReferenceId],
                "customer_id" => $order[Order::CustomerId],
                "order_date" => $order[Order::OrderDate],
                "item_description" => $order[Order::Type] . " " . $order[Order::Colour] . " " . $order[Order::Length] . " " . $order[Order::Texture] . " " . $order[Order::Texture] . " " . $order[Order::ExtSize],
                "bundle_count" => $order[Order::BundleCount],
                "quantity" => $order[Order::Quantity],
                "status" => $order[Order::Status],
                "due_data" => $order[Order::DueDate]
            ];
            array_push($res, $data);
        }

        //  $orderList= $this->modelHelper->GetDataUsingLike($model, $req);

        return json_encode(["success" => true, 'csrf' => csrf_hash(), 'output' => $res]);
    }

    public function CreateOrder()
    {

        if (($_SERVER["REQUEST_METHOD"] == "POST")) {
            try {

                $rules = [
                    'order_id'      => 'required|alpha_numeric', //|CheckOrderId[order_id]',
                    //  'item_id'  => 'required|validateOrderId[item_id]',

                    //'reference_id'  => 'required',
                    //'customer_id'      => 'required',
                    'order_date'    => 'required|valid_date[Y-m-d]',
                    'type'          => 'required',
                    'colour'        => 'required|ValidateOtherColour[colour]',
                    'length'        => 'required',
                    'texture'       => 'required',
                    'ext_size'      => 'required',
                    'unit'          => 'required',
                    // 'is_bundle'        => 'required',
                    'bundle_count'  => 'CheckBundle[bundle_count]',
                    'quantity'      => 'CheckQuantity[quantity]|greater_than[0]',
                    'due_date'      => 'required|valid_date[Y-m-d]|validateDueDate[due_date]',

                ];

                $errors = [
                    'order_id'            => [
                        'required'        => "Order id is required."
                    ], //,'alpha_numeric' => "Order id is required"],
                    // 'item_id'     => ['validateOrderId' => "Item id is already present."],
                    //'reference_id'            => ['required' => "Reference id is required"],
                    'order_date'          => [
                        'required'        => "Order date is required",
                        'valid_date'      => "Date should be in dd-mm-yyyy format."
                    ],
                    'unit'                => [
                        'required'        => 'Please select any one.',
                    ],
                    'bundle_count'        => [
                        'CheckBundle'     => 'Bundle count is required'
                    ],
                    'quantity'            => ['CheckQuantity' => "Quantity is required"],
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
                    $request['colour'] = $request['colour'] == "Others" ? $request['other_colour'] : $request['colour'];

                    $data = [
                        Order::OrderId        => strtoupper($request['order_id']),
                        Order::ItemId         => $request['item_id'],
                        //  Order::CustomerId     => $request['customer_id'],
                        // Order::ReferenceId    => $request['reference_id'],
                        Order::OrderDate      => $request['order_date'],
                        Order::Type           => $request['type'],
                        Order::Colour         => $request['colour'],
                        Order::Length         => $request['length'],
                        Order::Texture        => $request['texture'],
                        Order::ExtSize        => $request['ext_size'],
                        Order::Unit           => $request['unit'],
                        Order::BundleCount    => $request['bundle_count'],
                        Order::Quantity       => $request['quantity'],
                        Order::Status         => WorkStatus::NS,
                        Order::DueDate        => $request['due_date'],
                        // 'Overdue'            =>$request['Colour'],
                        Order::CreatedBy      => session()->get('id'),
                        Order::UpdatedBy      => session()->get('id')
                    ];
                    $model = ModelFactory::createModel(ModelNames::Order);

                    $result = $model->insert($data);

                    if ($result) {
                        $createdOrderId = $data[Order::OrderId] . " - " . $data[Order::ItemId];
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
            $model = ModelFactory::createModel(ModelNames::Order);

            $order = $model->where(Order::OrderListId, $orderListId)->first();

            $order[Order::OrderDate] = date("Y-m-d", strtotime($order[Order::OrderDate]));
            $order[Order::DueDate] = date("Y-m-d", strtotime($order[Order::DueDate]));
            return view("order/createOrder", ["json" => $data, "editOrderData" => $order]);
        } else
        if ($this->request->getMethod() == 'post') {
            try {

                $rules = [
                    //'order_id'  => 'required|validateOrderId[order_id]',
                    //'customer_id'      => 'required',
                    // 'reference_id'     => 'required',
                    'order_date'       => 'required|valid_date[Y-m-d]',
                    'type'             => 'required',
                    'colour'           => 'required|ValidateOtherColour[colour]',
                    'length'           => 'required',
                    'texture'          => 'required',
                    'ext_size'         => 'required',
                    'unit'             => 'required',
                    //'is_bundle'        => 'required',
                    'bundle_count'     => 'CheckBundle[bundle_count]',
                    'quantity'      => 'CheckQuantity[quantity]|greater_than[0]',
                    'due_date'         => 'required|valid_date[Y-m-d]|validateDueDate[due_date]',

                ];

                $errors = [
                    //'order_id'  => ['validateOrderId' => "Order id is already present."],
                    // 'reference_id'            => ['required' => "Reference id is required"],
                    'order_date'          => [
                        'required'        => "Order date is required",
                        'valid_date'      => "Date should be in dd-mm-yyyy format."
                    ],
                    'colour'              => [
                        'ValidateOtherColour' => "Please enter the other colour else choose from the list."
                    ],
                    'unit'                => [
                        'required'        => 'Please select any one.',
                    ],
                    'bundle_count'        => [
                        'CheckBundle'     => 'Bundle count is required'
                    ],
                    'quantity'            => ['CheckQuantity' => "Quantity is required"],
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
                    $request['colour'] = $request['colour'] == "Others" ? $request['other_colour'] : $request['colour'];
                    $data = [
                        Order::OrderId        => strtoupper($request['order_id']),
                        Order::ItemId         => $request['item_id'],
                        //  Order::CustomerId     => $request['customer_id'],
                        Order::OrderDate      => $request['order_date'],
                        //  Order::ReferenceId     => $request['reference_id'],
                        Order::Type           => $request['type'],
                        Order::Colour         => $request['colour'],
                        Order::Length         => $request['length'],
                        Order::Texture        => $request['texture'],
                        Order::ExtSize        => $request['ext_size'],
                        Order::Unit          => $request['unit'],
                        Order::BundleCount      => $request['bundle_count'],
                        Order::Quantity       => $request['quantity'],
                        Order::Status         => WorkStatus::NS,
                        Order::DueDate        => $request['due_date'],
                        // 'Overdue'            =>$request['Colour'],
                        Order::CreatedBy      => session()->get('id'),
                        Order::UpdatedBy      => session()->get('id')

                    ];
                    $model = ModelFactory::createModel(ModelNames::Order);

                    $update_status = $model->update($orderListId, $data);

                    if ($update_status) {
                        $updatedOrderId = $data[Order::OrderId] . " - " . $data[Order::ItemId];
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

                $model = ModelFactory::createModel(ModelNames::Order);

                $delete_status = $model->where(Order::OrderListId, $request['orderListId'])->delete();

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

    public function CheckOrderAndGenerateItemId()
    {
        if ($this->request->getMethod() == 'get') {
            try {
                $rules = [
                    'order_id'      => 'required|alpha_numeric',
                ];
                $errors = [
                    'order_id'          => [
                        'required'      => "Order id is required.",
                        'alpha_numeric' => "Order id should be alpha numeric."
                    ],
                ];
                if (!$this->validate($rules, $errors)) {
                    $output = $this->validator->getErrors();
                    return json_encode(["success" => false, "csrf" => csrf_hash(), "error" => $output]);
                } else {
                    $request = $this->request->getGet();
                    $result = $this->GenerateItemId($request["order_id"]);
                    return json_encode(['success' => true, 'csrf' => csrf_hash(), "output" => $result]);
                }
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
        try {
            $result = $this->GetOrderByOrderId($orderId);
            if ($result) {
                $itemId = $result[Order::ItemId] + 1;
                $result[Order::ItemId] = $itemId;
            } else {
                $result[Order::OrderId] = 0;
                $result[Order::ItemId] = 1;
            }
            $request = $this->request->getGet();
            if (isset($request["isAddItem"]) && $request["isAddItem"] == 0) {
                return $result;
            }
            $data = $this->GetJson();

            $result[Order::OrderDate] = date("Y-m-d", strtotime($result[Order::OrderDate]));
            // $result[Order::DueDate] = date("Y-m-d", strtotime($result[Order::DueDate]));
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
        $model = ModelFactory::createModel(ModelNames::Order);

        $order = $model->where(Order::OrderListId, $orderListId)->first();

        return $order;
    }
    public function GetOrderByOrderId($orderId)
    {
        $model = ModelFactory::createModel(ModelNames::Order);

        $order = $model->where(Order::OrderId, $orderId)->orderBy(order::ItemId, "desc")->first();

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
        $model = ModelFactory::createModel(ModelNames::Order);

        $selectArray = [Order::OrderListId, Order::OrderId, Order::ItemId, Order::CustomerId, Order::OrderDate, Order::Type, Order::Colour, Order::Length, Order::Texture, Order::ExtSize, Order::BundleCount, Order::Quantity, Order::Status, Order::DueDate];

        $orderList = $model->FilterOrder($selectArray, $request['query']);

        $res = [];
        foreach ($orderList as $key => $order) {

            $data = [
                "order_list_id" => $order[Order::OrderListId],
                "order_id" => $order[Order::OrderId],
                "item_id" => $order[Order::ItemId],
                // "reference_id" => $order[Order::ReferenceId],
                "customer_id" => $order[Order::CustomerId],
                "order_date" => $order[Order::OrderDate],
                "item_description" => $order[Order::Type] . " " . $order[Order::Colour] . " " . $order[Order::Length] . " " . $order[Order::Texture] . " " . $order[Order::Texture] . " " . $order[Order::ExtSize],
                "bundle_count" => $order[Order::BundleCount],
                "quantity" => $order[Order::Quantity],
                "status" => $order[Order::Status],
                "due_data" => $order[Order::DueDate]
            ];
            array_push($res, $data);
        }

        //  $orderList= $this->modelHelper->GetDataUsingLike($model, $req);

        return json_encode(["success" => true, 'csrf' => csrf_hash(), 'output' => $res]);
        //  return view('order/orderList', ["orderList" => $orderList]);
    }
}
