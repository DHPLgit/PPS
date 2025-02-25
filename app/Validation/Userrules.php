<?php

namespace App\Validation;

use App\Libraries\EnumsAndConstants\Department;
use App\Models\UserModel;
use App\Models\ModelFactory;
use App\Libraries\EnumsAndConstants\ModelNames;
use App\Libraries\EnumsAndConstants\OrderItems;
use App\Libraries\EnumsAndConstants\User;
use DateTime;

class Userrules
{

    public function validateOtp(string $str, string $fields, array $data)
    {
        $model = new UserModel();
        $userid = session()->get('otp_id');
        $multiClause = array('id' => $userid, "otp_check" => $data["vcode"]);
        $user = $model->where($multiClause)
            ->first();
        if ($user) {
            return true;
        }
        return false;
    }

    public function validateEmail(string $str, string $fields, array $data)
    {
        $user = $this->GetUser($str);

        if (!$user) {
            return true;
        }
        return false;
    }

    public function GetUser($mailId)
    {
        $model = new UserModel();
        $user = $model->where(User::MailId, $mailId)
            ->first();
        return $user;
    }

    public function checkActivation(string $str, string $fields, array $data)
    {
        $user = $this->GetUser($str);

        return ($user[User::Status] == 0) ? false : true;
    }
    public function CheckQA(?array $arr, string $fields, array $data)
    {
        if (array_key_exists("is_qa", $data)) {
            if ($data["is_qa"] == "1") {
                $flag = (array_key_exists("quality_analyst", $data)) ? true : false;

                return $flag;
            }
        }
        return true;
    }
    public function CheckEmail(string $str, string $fields, array $data)
    {
        $user = $this->GetUser($str);

        return ($user) ? true : false;
    }

    public function validateUser(string $str, string $fields, array $data)
    {
        $model = ModelFactory::createModel(ModelNames::User);
        $user = $model->where(User::MailId, $data['mail_id'])->first();

        if (!$user) {
            return false;
        }
        return password_verify($data['password'], $user[User::Password]);
    }
    public function GetOrder($orderId)
    {
        $model = ModelFactory::createModel(ModelNames::OrderItems);
        $order = $model->where(OrderItems::OrderId, $orderId)->first();
        return $order;
    }

    public function CheckOrderId(string $str, string $fields, array $data)
    {
        $orderId = strtoupper($data['order_id']);
        $order =  $this->GetOrder($orderId);
        if ($order) {
            return false;
        } else return true;
    }

    public function validateOrderId(string $str, string $fields, array $data)
    {
        $orderId = strtoupper($data['order_id']);
        $order =  $this->GetOrder($orderId);
        if ($order) {
            if (array_key_exists("isEdit", $data)) {
                $arrConv = (array)$order;
                if ($arrConv[OrderItems::OrderId] == $orderId) {
                    $order = null;
                }
            }
        }
        if (!$order) {
            return true;
        }
        return false;
    }

    public function ValidateOtherColour(string $str, string $fields, array $data)
    {

        if ($str == "Others") {
            if (array_key_exists("other_colour", $data)) {

                if (strlen($data["other_colour"]) > 0) {
                    return true;
                } else return false;
            }
        } else return true;
    }



    public function validateDueDate(string $str, string $fields, array $data)
    {
        $flag = $data['due_date'] > $data['order_date'];
        return $flag;
    }

    public function CheckBundle(?string $str, string $fields, array $data)
    {
        if (array_key_exists("unit", $data)) {
            if ($data["unit"] == "1") {
                // $flag = (array_key_exists("bundle_count", $data)) ? true : false;

                if (array_key_exists("bundle_count", $data) && $data["bundle_count"] > 0) {

                    $flag = true;
                } else $flag = false;
                return $flag;
            }
        }
        return true;
    }
    public function CheckQuantity(?string $str, string $fields, array $data)
    {
        $flag = true;
        if (array_key_exists("unit", $data)) {
            if (array_key_exists("bundle_count", $data) && $data["bundle_count"] > 0) {

                $flag =   ($data["quantity"]==0) ? false : true;
            }// elseif (array_key_exists("quantity", $data)) {
            //     $falg=true;
            // }
        }
        return $flag;
    }
    public function validateDepartmentName(string $str, string $fields, array $data)
    {
        $model = ModelFactory::createModel(ModelNames::Department);
        $dept = null;

        $deptName = strtoupper($str);
        $condition = [Department::DepartmentName => $deptName];
        $dept =  $model->GetDepartment($condition);

        if (!$dept) {
            return true;
        }
        return false;
    }

    public function CheckCompleteStatus(string $str, string $fields, array $data)
    {
        if ($data["is_complete"] == "0") {
            $flag = (array_key_exists("next_task_detail_id", $data)) ? true : false;

            return $flag;
        }
        return true;
    }
}
