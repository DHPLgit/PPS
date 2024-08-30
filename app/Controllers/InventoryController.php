<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Exception;
use CodeIgniter\Database\Exceptions\DatabaseException;
use App\Libraries\Response\Response;
use App\Libraries\Response\Error;
use App\Models\ModelFactory;
use App\Libraries\EnumsAndConstants\ModelNames;

class InventoryController extends BaseController
{

    public function getStocks()
    {
        try {
            $stock_type = $this->request->getGet('stock_type');
            $model = ModelFactory::createModel(ModelNames::Stock);
            $selectArray = ['Stock_id', 'Colour', 'Length', 'Texture', 'Unit', 'Quantity', 'Type'];
            $resp = $model->select($selectArray)->where('Status', $stock_type)->findAll();

            $result = Response::SetResponse(200, $resp, new Error());
        } catch (DatabaseException $ex) {

            $result = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
        } catch (Exception $ex) {

            $result = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
        }
        return json_encode($result);
    }

    public function getInpt_drpdwn()
    {
    }
}
