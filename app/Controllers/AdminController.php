<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use Exception;

class AdminController extends BaseController
{


    public function getOrderId(int $orderId){

        

    }
    public function getTotalResponse($tenant, $userId, $startDate, $endDate)
    {

        $dbname = "nps_" . $tenant['tenant_name'];
        //new DB creation for Tenant details
        $db = db_connect();
        $db->query('USE ' . $dbname);
        $getcontactdata = '';
        $getsurveylist = array();
        $userIdlist = implode(",", $userId);
        $multiClause1 = "SELECT * FROM " . $dbname . ".nps_external_contacts  WHERE `nps_external_contacts`.`created_by` IN (" . $userIdlist . ")";
        $multiClause1 .= " AND CAST(created_at AS DATE) BETWEEN '$startDate' AND '$endDate'";
        $externalcount = $db->query($multiClause1);
        if (count($externalcount->getResultArray()) > 0) {
            $getcontactdata = $externalcount->getResultArray();
        }
        $multiClause2 = "SELECT * FROM " . $dbname . ".nps_survey_response  WHERE `nps_survey_response`.`user_id` IN (" . $userIdlist . ")";
        $multiClause2 .= " AND CAST(created_at AS DATE) BETWEEN '$startDate' AND '$endDate'";
        $externalcount = $db->query($multiClause2);
        if (count($externalcount->getResultArray()) > 0) {
            $getsurveylist = $externalcount->getResultArray();
        }

        $db->close();
        $data = [
            "getcontactdata" => $getcontactdata,
            "getsurveylist" => $getsurveylist
        ];
        return $data;
    }
    public function ajaxrequest()
    {
        echo view('ajax-request');
    }

    public function updateRole()
    {
        $output = "Ajax request Success.";
        if ($this->request->isAJAX()) {
            $query = service('request')->getPost();
            $userId = $query['id'];
            $data = [
                "role" => $query['query']
            ];
            $model = new UserModel();
            $model->update($userId, $data);
            var_dump($this->request->getPost('query'));
            echo json_encode(['success' => $output, 'csrf' => csrf_hash(), 'query ' => $query]);
        }
        // echo json_encode($output);
    }
}
