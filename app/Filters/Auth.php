<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use App\Libraries\TokenManagement\TokenManagement;
use App\Libraries\Response\Response;
use App\Libraries\Response\Error;
use Config\Services;
use App\Models\ModelFactory;
use App\Libraries\EnumsAndConstants\ModelNames;
use App\Libraries\EnumsAndConstants\Access;
use CodeIgniter\CLI\Console;

require_once APPPATH . 'Libraries/EnumsAndConstants/Enums.php';
require_once APPPATH . 'Libraries/EnumsAndConstants/Constants.php';
class Auth implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {

        // try {
        //     $tokenManagement = new TokenManagement();
        //     $resp = $tokenManagement->verify_token($request);
        //     $model = ModelFactory::createModel(ModelNames::Access);

        //     $res =  $model->where('Access_id', $resp->Role)->first();

        //     $access = explode(', ', $res['Page_list']);
        //     $page = $request->getUri()->getSegments();
        //     if (in_array($page[0], $access)) {
        //         return true;
        //     }
        //     $result = Response::SetResponse(403, null, new Error("You don't have authorization to view this page."));
        //    return redirect('/');
        // } catch (Exception $ex) {

        //     $result = Response::SetResponse(403, null, new Error($ex->getMessage()));
        // }
        // return Services::response()->setBody(json_encode($result));

        if (session()->get('isLoggedIn')) {
            $model = ModelFactory::createModel(ModelNames::Access);
            $res =  $model->where(Access::AccessId, session()->get('role'))->first();

            $access = explode(', ', $res[Access::PageList]);
            $page = $request->getUri()->getSegments();
            if (in_array($page[0], $access)) {
                return true;
            }
        }
        else return redirect()->to(site_url("/"));

    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //echo "here";
    }
}
