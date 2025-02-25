<?php

namespace App\Controllers;

use App\Models\ModelHelper;
use App\Controllers\BaseController;
use App\Models\ModelFactory;
use App\Libraries\EnumsAndConstants\ModelNames;
use App\Libraries\EnumsAndConstants\Order;
use App\Libraries\EnumsAndConstants\WorkStatus;
use App\Libraries\EnumsAndConstants\WtStd;
use Exception;
use CodeIgniter\Database\Exceptions\DatabaseException;
use App\Libraries\Response\Response;
use App\Libraries\Response\Error;
use App\Libraries\EnumsAndConstants\OrderItems;
use App\Libraries\TokenManagement\TokenManagement;
use CodeIgniter\Model;
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
    // public function GetOrderList()
    // {
    //     try {
    //         $request = $this->request->getGet();
    //         $search = "";
    //         $currentPage = $this->request->getGet('page') ? $this->request->getGet('page') : 1;

    //         // Define the number of records per page
    //         $perPage = 20;

    //         // Calculate the offset
    //         $offset = ($currentPage - 1) * $perPage;

    //         //$order_id = $this->request->getGet('Order_list_id');
    //         $model = ModelFactory::createModel(ModelNames::OrderItems);
    //         $selectArray = [OrderItems::OrderListId, OrderItems::OrderId, OrderItems::ItemId, OrderItems::CustomerId, OrderItems::OrderDate, OrderItems::Type, OrderItems::Colour, OrderItems::Length, OrderItems::Texture, OrderItems::ExtSize, OrderItems::Unit, OrderItems::BundleCount, OrderItems::Quantity, OrderItems::Status, OrderItems::DueDate];

    //         if (isset($request["query"]) && !empty($request["query"])) {
    //             $search = $request["query"];
    //             $result = $model->GetOrders($selectArray,  $perPage, $offset, $request['query']);
    //         } else {
    //             $result = $model->GetOrders($selectArray, $perPage, $offset);
    //         }
    //         foreach ($result[1] as $key => $value) {


    //         }
    //         $totalRecords = $result[0];
    //         $orderList = $result[1];

    //         $pageLinks = GetPaginationLinks($totalRecords, $perPage, $currentPage);

    //         $response = Response::SetResponse(200, $orderList, new Error());
    //         return view('order/orderList', ["orderList" => $orderList, "pageLinks" => $pageLinks, "search" => $search]);
    //     } catch (DataBaseException $ex) {

    //         $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
    //     } catch (Exception $ex) {

    //         $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
    //     }
    //     return json_encode($response);
    // }
    public function GetOrderList()
    {
        try {
            $request = $this->request->getGet();
            $search = "";
            $currentPage = $this->request->getGet('page') ? $this->request->getGet('page') : 1;

            // Define the number of records per page
            $perPage = 20;

            // Calculate the offset
            $offset = ($currentPage - 1) * $perPage;

            //$order_id = $this->request->getGet('Order_list_id');
            $model = ModelFactory::createModel(ModelNames::Order);
            $selectArray = [Order::OrderId, Order::CustomerId, Order::OrderDate, Order::Status, Order::CompletionPercentage, Order::DueDate];

            if (isset($request["query"]) && !empty($request["query"])) {
                $search = $request["query"];
                $result = $model->GetOrders($selectArray,  $perPage, $offset, $request['query']);
            } else {
                $result = $model->GetOrders($selectArray, $perPage, $offset);
            }

            $totalRecords = $result[0];
            $orderList = $result[1];

            $pageLinks = GetPaginationLinks($totalRecords, $perPage, $currentPage);

            $response = Response::SetResponse(200, $orderList, new Error());
            return view('order/orderList', ["orderList" => $orderList, "pageLinks" => $pageLinks, "search" => $search]);
        } catch (DataBaseException $ex) {

            $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
        } catch (Exception $ex) {

            $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
        }
        return json_encode($response);
    }
    //search the order for task initialize page
    public function SearchOrder()
    {
        try {

            $query = $this->request->getPost(index: 'like');
            $model = ModelFactory::createModel(ModelNames::Order);
            $selectArr = [Order::OrderId];
            //$selectArr = [Order::OrderId, OrderItems::OrderListId, OrderItems::ItemId, OrderItems::Colour, OrderItems::Length, OrderItems::Texture, OrderItems::Quantity];

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

        $model = ModelFactory::createModel(ModelNames::OrderItems);

        // $selectArray = [OrderItems::OrderListId, OrderItems::OrderId, OrderItems::ItemId, OrderItems::Type, OrderItems::Colour, OrderItems::Length, OrderItems::Texture, OrderItems::ExtSize, OrderItems::BundleCount, OrderItems::Quantity];

        $modelHelper = new ModelHelper();
        $condition = [OrderItems::OrderId => $request['orderId']];
        $itemList = $modelHelper->GetAllDataUsingWhere($model, $condition);

        $res = [];
        //  foreach ($itemList as $key => $order) {

        // $data = [
        //     "order_list_id" => $order[OrderItems::OrderListId],
        //     "order_id" => $order[OrderItems::OrderId],
        //     "item_id" => $order[OrderItems::ItemId],
        //     // "reference_id" => $order[Order::ReferenceId],
        //    // "customer_id" => $order[OrderItems::CustomerId],
        //    // "order_date" => $order[OrderItems::OrderDate],
        //     "item_description" => $order[OrderItems::Type] . " " . $order[OrderItems::Colour] . " " . $order[OrderItems::Length] . " " . $order[OrderItems::Texture] . " " . $order[OrderItems::Texture] . " " . $order[OrderItems::ExtSize],
        //     "bundle_count" => $order[OrderItems::BundleCount],
        //     "quantity" => $order[OrderItems::Quantity],
        //     "status" => $order[OrderItems::Status],
        //     "due_date" => $order[OrderItems::DueDate]
        // ];
        // array_push($res, $itemList);
        // }

        //  $orderList= $this->modelHelper->GetDataUsingLike($model, $req);

        return json_encode(["success" => true, 'csrf' => csrf_hash(), 'output' => $itemList]);
    }

    // public function CreateOrder()
    // {

    //     if (($_SERVER["REQUEST_METHOD"] == "POST")) {
    //         try {

    //             $rules = [
    //                 'order_id'      => 'required|alpha_numeric', //|CheckOrderId[order_id]',
    //                 //  'item_id'  => 'required|validateOrderId[item_id]',

    //                 //'reference_id'  => 'required',
    //                 //'customer_id'      => 'required',
    //                 'order_date'    => 'required|valid_date[Y-m-d]',
    //                 'type'          => 'required',
    //                 'colour'        => 'required|ValidateOtherColour[colour]',
    //                 'length'        => 'required',
    //                 'texture'       => 'required',
    //                 'ext_size'      => 'required',
    //                 'unit'          => 'required',
    //                 // 'is_bundle'        => 'required',
    //                 'bundle_count'  => 'CheckBundle[bundle_count]',
    //                 'quantity'      => 'CheckQuantity[quantity]|greater_than[0]',
    //                 'due_date'      => 'required|valid_date[Y-m-d]|validateDueDate[due_date]',

    //             ];

    //             $errors = [
    //                 'order_id'            => [
    //                     'required'        => "Order id is required."
    //                 ], //,'alpha_numeric' => "Order id is required"],
    //                 // 'item_id'     => ['validateOrderId' => "Item id is already present."],
    //                 //'reference_id'            => ['required' => "Reference id is required"],
    //                 'order_date'          => [
    //                     'required'        => "Order date is required",
    //                     'valid_date'      => "Date should be in dd-mm-yyyy format."
    //                 ],
    //                 'unit'                => [
    //                     'required'        => 'Please select any one.',
    //                 ],
    //                 'bundle_count'        => [
    //                     'CheckBundle'     => 'Bundle count is required'
    //                 ],
    //                 'quantity'            => ['CheckQuantity' => "Quantity is required"],
    //                 'due_date'            => [
    //                     'required'        => "Due date is required",
    //                     'validateDueDate' => 'Due date is less than or equal to the order date.',
    //                     'valid_date'      => "Date should be in dd-mm-yyyy format."
    //                 ]

    //             ];
    //             if (!$this->validate($rules, $errors)) {

    //                 $output = $this->validator->getErrors();
    //                 $errorMsg = implode(";", $output);
    //                 //$response = Response::SetResponse(400, null, new Error($errorMsg));
    //                 return json_encode(['success' => false, 'csrf' => csrf_hash(), 'error' => $output]);
    //                 // $jsonFile = file_get_contents('../public/uploads/dropdown.json');
    //                 // $data = json_decode($jsonFile);

    //                 // return view('order/createOrder', ["validation" => $this->validator, "json" => $data]);
    //             } else {
    //                 $request = $this->request->getPost();
    //                 $request['colour'] = $request['colour'] == "Others" ? $request['other_colour'] : $request['colour'];

    //                 $data = [
    //                     OrderItems::OrderId        => strtoupper($request['order_id']),
    //                     OrderItems::ItemId         => $request['item_id'],
    //                     //  Order::CustomerId     => $request['customer_id'],
    //                     // Order::ReferenceId    => $request['reference_id'],
    //                     OrderItems::OrderDate      => $request['order_date'],
    //                     OrderItems::Type           => $request['type'],
    //                     OrderItems::Colour         => $request['colour'],
    //                     OrderItems::Length         => $request['length'],
    //                     OrderItems::Texture        => $request['texture'],
    //                     OrderItems::ExtSize        => $request['ext_size'],
    //                     OrderItems::Unit           => $request['unit'],
    //                     OrderItems::BundleCount    => $request['bundle_count'],
    //                     OrderItems::Quantity       => $request['quantity'],
    //                     OrderItems::Status         => WorkStatus::NS,
    //                     OrderItems::DueDate        => $request['due_date'],
    //                     // 'Overdue'            =>$request['Colour'],
    //                     OrderItems::CreatedBy      => session()->get('id'),
    //                     OrderItems::UpdatedBy      => session()->get('id')
    //                 ];
    //                 $model = ModelFactory::createModel(ModelNames::OrderItems);

    //                 $result = $model->insert($data);

    //                 if ($result) {
    //                     $createdOrderId = $data[OrderItems::OrderId] . " - " . $data[OrderItems::ItemId];
    //                     $status = "$createdOrderId order created successfully!";
    //                 } else $status = "Something went wrong!";
    //                 session()->setFlashdata('response', $status);
    //                 //$response = Response::SetResponse(201, null, new Error());
    //                 return json_encode(['success' => true, 'csrf' => csrf_hash(), 'url' => base_url('/order/orderList')]);
    //             }
    //         } catch (DataBaseException $ex) {

    //             $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
    //         } catch (Exception $ex) {

    //             $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
    //         }
    //         return json_encode($response);
    //     } else {
    //         $jsonFile = file_get_contents('../public/uploads/dropdown.json');
    //         $data = json_decode($jsonFile);

    //         return view("order/createOrder", ["json" => $data]);
    //     }
    // }

    public function CreateOrder()
    {

        if (($_SERVER["REQUEST_METHOD"] == "POST")) {
            try {

                $rules = [
                    'order_id'      => 'required|alpha_numeric',
                    'customer_id'      => 'required',
                    'order_date'    => 'required|valid_date[Y-m-d]',
                    'due_date'      => 'required|valid_date[Y-m-d]|validateDueDate[due_date]',

                ];

                $errors = [
                    'order_id'            => [
                        'required'        => "Order id is required."
                    ],
                    'order_date'          => [
                        'required'        => "Order date is required",
                        'valid_date'      => "Date should be in dd-mm-yyyy format."
                    ],
                    'due_date'            => [
                        'required'        => "Due date is required",
                        'validateDueDate' => 'Due date is less than or equal to the order date.',
                        'valid_date'      => "Date should be in dd-mm-yyyy format."
                    ]

                ];
                if (!$this->validate($rules, $errors)) {

                    $output = $this->validator->getErrors();

                    return json_encode(['success' => false, 'csrf' => csrf_hash(), 'error' => $output]);
                } else {
                    $request = $this->request->getPost();
                    // $request['colour'] = $request['colour'] == "Others" ? $request['other_colour'] : $request['colour'];

                    $data = [
                        Order::OrderId        => strtoupper($request['order_id']),
                        Order::CustomerId         => $request['customer_id'],
                        Order::OrderDate      => $request['order_date'],
                        Order::DueDate        => $request['due_date'],
                        Order::Status         => WorkStatus::NS,
                        // 'Overdue'            =>$request['Colour'],
                        Order::CreatedBy      => session()->get('id'),
                        Order::UpdatedBy      => session()->get('id')
                    ];
                    $model = ModelFactory::createModel(ModelNames::Order);

                    $result = $model->insert($data);

                    if ($result) {
                        $createdOrderId = $data[OrderItems::OrderId];
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
            $data = $this->GetJson();
            $customerController = new CustomerController();
            $customerList = $customerController->GetCustomerList();
            return view("order/createOrder", ["json" => $data, "customerList" => $customerList]);
        }
    }
    public function GenerateOrderId()
    {
        if ($this->request->getMethod() == 'get') {
            try {
                $rules = [
                    'order_id'      => 'required|alpha_numeric|CheckOrderId[order_id]',
                ];
                $errors = [
                    'order_id'          => [
                        'required'      => "Order id is required.",
                        'alpha_numeric' => "Order id should be alpha numeric.",
                        'CheckOrderId'  => "Order id is already present."
                    ],
                ];
                if (!$this->validate($rules, $errors)) {
                    $output = $this->validator->getErrors();
                    return json_encode(["success" => false, "csrf" => csrf_hash(), "error" => $output]);
                } else {
                    return json_encode(['success' => true, 'csrf' => csrf_hash()]);
                }
            } catch (DataBaseException $ex) {

                $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
            } catch (Exception $ex) {

                $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
            }
            return json_encode($response);
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
                        OrderItems::OrderId        => strtoupper($request['order_id']),
                        OrderItems::ItemId         => $request['item_id'],
                        //  Order::CustomerId     => $request['customer_id'],
                        OrderItems::OrderDate      => $request['order_date'],
                        //  Order::ReferenceId     => $request['reference_id'],
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
    public function CreateItem()
    {

        try {

            $rules = [
                //'order_id'      => 'required|alpha_numeric', //|CheckOrderId[order_id]',
                //  'item_id'  => 'required|validateOrderId[item_id]',

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
                // 'order_id'            => [
                //     'required'        => "Order id is required."
                // ], //,'alpha_numeric' => "Order id is required"],
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
            } else {
                $request = $this->request->getPost();
                $request['colour'] = $request['colour'] == "Others" ? $request['other_colour'] : $request['colour'];

                $data = [
                    OrderItems::OrderId        => strtoupper($request['order_id']),
                    OrderItems::ItemId         => $request['item_id'],
                    //  Order::CustomerId     => $request['customer_id'],
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
                    OrderItems::CreatedBy      => session()->get('id'),
                    OrderItems::UpdatedBy      => session()->get('id')
                ];
                $model = ModelFactory::createModel(ModelNames::OrderItems);

                $result = $model->insert($data);
                $success = false;
                if ($result) {
                    $createdOrderId = $data[OrderItems::OrderId] . " - " . $data[OrderItems::ItemId];
                    $status = "$createdOrderId - Item added successfully!";
                    $success = true;
                    $data = [
                        OrderItems::OrderListId        => $result,
                        OrderItems::ItemId         => $request['item_id'],
                        //  Order::CustomerId     => $request['customer_id'],
                        OrderItems::OrderDate      => $request['order_date'],
                        OrderItems::Type           => $request['type'],
                        OrderItems::Colour         => $request['colour'],
                        OrderItems::Length         => $request['length'],
                        OrderItems::Texture        => $request['texture'],
                        OrderItems::ExtSize        => $request['ext_size'],
                        // OrderItems::Unit           => $request['unit'],
                        OrderItems::BundleCount    => $request['bundle_count'],
                        OrderItems::Quantity       => $request['quantity'],
                        OrderItems::Status         => WorkStatus::NS,
                        OrderItems::CompletionPercentage         => 0,
                    ];
                    $output = ["order_id" => $request['order_id'], "item" => $data];
                } else {
                    $status = "Something went wrong!";
                    $output = [];
                }
                session()->setFlashdata('response', $status);
                //$response = Response::SetResponse(201, null, new Error());
                return json_encode(['success' => $success, 'csrf' => csrf_hash(), 'url' => base_url('/order/orderList'), 'output' => $output]);
            }
        } catch (DataBaseException $ex) {

            $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
        } catch (Exception $ex) {

            $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
        }
        return json_encode($response);
    }
    public function GetOrderItems($order_id)
    {
        //  $request = $this->request->getGet();
        $data = $this->GetJson();
        $order = $this->GetOrder($order_id);
        $itemList =  $this->GetitemsByOrderId($order_id);
        return view("order/addItem", ["json" => $data, "order" => $order, "orderItems" => $itemList]);
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
    public function DeleteItem()
    {
        if ($this->request->getMethod() == 'post') {
            try {

                $request = $this->request->getPost();

                $model = ModelFactory::createModel(ModelNames::OrderItems);

                $delete_status = $model->where(OrderItems::OrderListId, $request['orderListId'])->delete();

                if ($delete_status) {
                    $status = "Item deleted successfully!";
                } else $status = "Something went wrong!";
                session()->setFlashdata('response', $status);

                return json_encode(['success' => true, 'csrf' => csrf_hash()]);
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
            $result = $this->GetitemByOrderId($orderId);
            if ($result) {
                $itemId = $result[OrderItems::ItemId] + 1;
                $result[OrderItems::ItemId] = $itemId;
            } else {
                $result[OrderItems::OrderId] = 0;
                $result[OrderItems::ItemId] = 1;
            }
            $request = $this->request->getGet();
            if (isset($request["isAddItem"]) && $request["isAddItem"] == 0) {
                return $result;
            }
            $data = $this->GetJson();

            $result[OrderItems::OrderDate] = date("Y-m-d", strtotime($result[OrderItems::OrderDate]));
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

    public function GetOrder($orderId)
    {
        $model = ModelFactory::createModel(ModelNames::Order);

        $order = $model->where(Order::OrderId, $orderId)->first();

        return $order;
    }
    public function GetitemByOrderId($orderId)
    {
        $model = ModelFactory::createModel(ModelNames::OrderItems);

        $order = $model->where(OrderItems::OrderId, $orderId)->orderBy(OrderItems::ItemId, "desc")->first();

        return $order;
    }
    public function GetitemsByOrderId($orderId)
    {
        $model = ModelFactory::createModel(ModelNames::OrderItems);

        $order = $model->where(OrderItems::OrderId, $orderId)->orderBy(OrderItems::ItemId, "asc")->findAll();

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

        $model = ModelFactory::createModel(ModelNames::OrderItems);

        $selectArray = [OrderItems::OrderListId, OrderItems::OrderId, OrderItems::ItemId, OrderItems::CustomerId, OrderItems::OrderDate, OrderItems::Type, OrderItems::Colour, OrderItems::Length, OrderItems::Texture, OrderItems::ExtSize, OrderItems::BundleCount, OrderItems::Quantity, OrderItems::Status, OrderItems::DueDate];

        $perPage = 20;
        $currentPage = $this->request->getGet('page') ? (int)$this->request->getGet('page') : 1;

        // Calculate the offset
        $offset = ($currentPage - 1) * $perPage;
        $result = $model->FilterOrder($selectArray, $request['query'], $perPage, $offset);

        $orderList = $result[1];
        $totalRecords = $result[0];

        $pageLinks = GetPaginationLinks($totalRecords, 20, $currentPage);
        $res = [];
        foreach ($orderList as $key => $order) {

            $data = [
                "order_list_id" => $order[OrderItems::OrderListId],
                "order_id" => $order[OrderItems::OrderId],
                "item_id" => $order[OrderItems::ItemId],
                // "reference_id" => $order[Order::ReferenceId],
                "customer_id" => $order[OrderItems::CustomerId],
                "order_date" => $order[OrderItems::OrderDate],
                "item_description" => $order[OrderItems::Type] . " " . $order[OrderItems::Colour] . " " . $order[OrderItems::Length] . " " . $order[OrderItems::Texture] . " " . $order[OrderItems::Texture] . " " . $order[OrderItems::ExtSize],
                "bundle_count" => $order[OrderItems::BundleCount],
                "quantity" => $order[OrderItems::Quantity],
                "status" => $order[OrderItems::Status],
                "due_date" => $order[OrderItems::DueDate]
            ];
            array_push($res, $data);
        }

        //  $orderList= $this->modelHelper->GetDataUsingLike($model, $req);

        // return json_encode(["success" => true, 'csrf' => csrf_hash(), 'output' => $res,'pageLinks'=>$pageLinks]);
        //return view('order/orderList', ["orderList" => $orderList]);
        return view('order/orderList', ["orderList" => $orderList, "pageLinks" => $pageLinks]);
    }
}
