<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;
use App\Libraries\EnumsAndConstants\ModelNames;

require_once APPPATH . 'Libraries/EnumsAndConstants/Enums.php';
class ModelFactory
{
    public static function createModel($model)
    {
        switch ($model) {
            case 'User':
                return new UserModel();
            case 'Access':
                return new AccessModel();
            case 'Stock':
                return new StockModel();
            case 'Order':
                    return new OrderModel();   
            case 'OrderItems':
                return new OrderItemsModel();
            case 'Task':
                return new TaskModel();
            case 'Input':
                return new InputModel();
            case 'Employee':
                return new EmployeeModel();
            case 'TaskDetail':
                return new TaskDetailModel();
            case 'QualityCheck':
                return new QualityCheckModel();
            case 'OrderStockMap':
                return new OrderStockMapModel();
            case 'Wtstd':
                return new WtStdModel();
            case 'Department':
                return new DepartmentModel();
            case 'DeptEmpMap':
                return new DeptEmpModel();
            case 'Customer':
                return new CustomerModel();
            default:
                throw new Exception("Invalid model type: " . $model);
        }
    }
}
