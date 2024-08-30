<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
//$routes->get('/', 'UserController::login');

$routes->get('/', 'UserController::LoginIndex', ["filter" => "noauth"]);
$routes->post('login', 'UserController::Login', ["filter" => "noauth"]);
$routes->get('logout', 'UserController::Logout', ["filter" => "noauth"]);
$routes->post('otpcheck', 'UserController::OtpCheck', ["filter" => "noauth"]);
$routes->match(['get', 'post'], 'signup', 'UserController::Signup', ["filter" => "noauth"]);
$routes->match(['get', 'post'], 'forgetPassword', 'UserController::ForgetPassword', ["filter" => "noauth"]);
$routes->get('activate/(:any)', 'UserController::ActivateAccount/$1', ["filter" => "noauth"]);
$routes->match(['get', 'post'], 'resetpwd/(:any)/(:any)', 'UserController::ResetPassword/$1/$2', ["filter" => "noauth"]);

$routes->get('verificationpage', 'UserController::generateOtp');
$routes->match(['get', 'post'], 'verificationpage', 'UserController::generateOtp', ["filter" => "noauth"]);

$routes->match(['get', 'post'], 'updateprofile', 'UserController::updateprofile', ["filter" => "auth"]);


// Order routes
$routes->group("order", ["filter" => "auth"], function ($routes) {
    $routes->get('orderList', 'OrderController::GetOrderList');
    $routes->match(['get', 'post'], 'createOrder', 'OrderController::CreateOrder');
    $routes->match(['get', 'post'], 'editOrder/(:any)', 'OrderController::EditOrder/$1');
    $routes->post('deleteOrder', 'OrderController::DeleteOrder');
    $routes->get('checkAndGenerateId', 'OrderController::CheckOrderAndGenerateItemId');
    $routes->get('generateItemId/(:any)', 'OrderController::GenerateItemId/$1');
    $routes->post('search', 'OrderController::SearchOrder', ["filter" => "noauth"]);
    $routes->get('getWeight', 'OrderController::CalculateWeight', ["filter" => "auth"]);
    $routes->get('filter', 'OrderController::FilterOrder', ["filter" => "auth"]);
});

$routes->group("task", function ($routes) {
    $routes->match(['get', 'post'], 'createTask', 'TaskController::CreateTask', ["filter" => "auth"]);
    $routes->post('deleteTask', 'TaskController::DeleteTask', ["filter" => "auth"]);
    $routes->get('getTask', 'TaskController::GetTask', ["filter" => "auth"]);
    $routes->get('dropdownList', 'TaskController::getDropdownList', ["filter" => "noauth"]);
    $routes->get('list', 'TaskController::GetAllTask', ["filter" => "auth"]);
    $routes->get('orderList/(:any)', 'TaskController::GetOrdersUnderTask/$1');
    $routes->match(['get', 'post'], 'mapEmployee/(:any)', 'TaskController::MapEmployee/$1', ["filter" => "auth"]);
    $routes->match(['get', 'post'], 'splitTask/(:any)', 'TaskController::SplitTaskAndMapEmployees/$1', ["filter" => "auth"]);
    $routes->match(['get', 'post'], 'splitTaskOnOutput/(:any)', 'TaskController::SplitTaskBasedOnOutputs/$1', ["filter" => "auth"]);
    $routes->match(['get', 'post'], 'qualityCheck/(:any)', 'TaskController::QualityCheck/$1', ["filter" => "auth"]);
    $routes->post('startQC', 'TaskController::StartQC', ["filter" => "auth"]);
    $routes->get('getPrevTaskList/(:any)', 'TaskController::GetPreviousTaskList/$1', ["filter" => "auth"]);
    $routes->get('filter', 'TaskController::GetTasksForAnOrder', ["filter" => "auth"]);
    $routes->post('restartTask', 'TaskController::RestartTask', ["filter" => "auth"]);

});

//Task detail
$routes->group("taskDetail", function ($routes) {
    $routes->match(['get', 'post'], 'createTaskDetail', 'TaskDetailController::CreateTaskDetail', ["filter" => "auth"]);
    $routes->get('list', 'TaskDetailController::GetTaskDetailList', ["filter" => "auth"]);
    $routes->post('getTaskDetail', 'TaskDetailController::GetTaskDetail', ["filter" => "auth"]);
    $routes->post('deleteTaskDetail', 'TaskDetailController::DeleteTaskDetail', ["filter" => "auth"]);
    $routes->post('updateParentTask', 'TaskDetailController::UpdateParentTask', ["filter" => "auth"]);
    $routes->match(['get', 'post'], 'deptMap', 'TaskDetailController::DepartmentMap', ["filter" => "auth"]);
});


//department
$routes->group("department", function ($routes) {

    $routes->get('list', 'TaskDetailController::GetDepartmentList', ["filter" => "auth"]);
    $routes->match(['post'], 'addDepartment', 'TaskDetailController::AddOrUpdateDepartment', ["filter" => "auth"]);

    $routes->match(['post'], 'updateDepartment', 'TaskDetailController::AddOrUpdateDepartment', ["filter" => "auth"]);

});


// Employee routes
$routes->group("employee", function ($routes) {
    $routes->match(['get', 'post'], 'upload', 'EmployeeController::EmployeeUploads', ["filter" => "auth"]);
    $routes->post('details', 'EmployeeController::GetUniqueEmployeeData', ["filter" => "auth"]);
    $routes->post('delete', 'EmployeeController::DeleteEmployeeDetail', ["filter" => "auth"]);
});

$routes->group("stock", function ($routes) {
    $routes->match(['get', 'post'], 'upload', 'StockController::StockUploads', ["filter" => "auth"]);
    $routes->post('details', 'StockController::GetUniqueStockData', ["filter" => "auth"]);
    $routes->post('search', 'StockController::SearchStocks', ["filter" => "noauth"]);
    $routes->post('delete', 'StockController::DeleteStockDetail', ["filter" => "auth"]);
});

$routes->get('userprofile', 'UserController::getprofile', ["filter" => "auth"]);
$routes->get('changepassword', 'UserController::changepassword', ["filter" => "auth"]);
$routes->get('userpermission', 'TenantController::getUserDetails', ["filter" => "auth"]);
$routes->get('sample', 'UserController::sample', ["filter" => "noauth"]);

$routes->get('stocks', 'InventoryController::getStocks', ["filter" => "noauth"]);

$routes->post('activate', 'UserController::activateAccount', ["filter" => "noauth"]);

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
