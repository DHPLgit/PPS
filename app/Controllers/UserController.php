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
use App\Libraries\EnumsAndConstants\Roles;
use App\Libraries\EnumsAndConstants\Status;
use App\Libraries\EnumsAndConstants\User;


require_once APPPATH . 'Libraries/EnumsAndConstants/Enums.php';
require_once APPPATH . 'Libraries/EnumsAndConstants/Constants.php';
class UserController extends BaseController
{
    public function LoginIndex()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to(base_url('/order/orderList'));
        }
        return view('user/login');
    }
    public function Login()
    {
        try {
            if ($this->request->getMethod() == 'post') {

                $rules = [
                    'mail_id'  => 'required|valid_email|checkEmail[mail_id]|checkActivation[mail_id]',
                    'password' => 'required',
                ];

                $errors = [
                    'mail_id' => [
                        'required'        => 'Email address is required.',
                        'valid_email'     => 'Please check the Email field. It does not appear to be valid.',
                        'checkEmail'      => 'Email Address is not registered',
                        'checkActivation' => 'Your account is not activated, please activate it to login.'
                    ],
                    'password' => [
                        'required'        => 'Password is required.',
                    ],

                ];
                if (!$this->validate($rules, $errors)) {

                    $output = $this->validator->getErrors();
                    //$errorMsg = implode(";", $output);
                    //$response = Response::SetResponse(401, null, new Error($errorMsg));
                    return json_encode(['success' => false, 'csrf' => csrf_hash(), 'error' => $output]);
                } else {
                    $rules = [
                        'password' => 'required|min_length[4]|max_length[255]|validateUser[mail_id, password]',
                    ];
                    $errors = [

                        'password' => [
                            'validateUser' => "Email/Password didn't match",
                        ],
                    ];

                    if (!$this->validate($rules, $errors)) {

                        $output = $this->validator->getErrors();
                        //$errorMsg = implode(";", $output);
                        //$response = Response::SetResponse(401, null, new Error($errorMsg));
                        return json_encode(['success' => false, 'csrf' => csrf_hash(), 'error' => $output]);
                    } else {
                        $request = $this->request->getPost();
                        $model = ModelFactory::createModel(ModelNames::User);
                        $selectArray = [User::UserId, User::FirstName, User::LastName, User::MailId, User::Role];
                        $user = $model->select($selectArray)->where(User::MailId, $request['mail_id'])->first();

                        // $model = ModelFactory::createModel(ModelNames::Access);
                        // $access = $model->where('Access_id', $user['Role'])->first();

                        // $tokenManagement = new TokenManagement();
                        // $token = $tokenManagement->generate_jwt_token($user);

                        // $data = new LoginData();
                        // $data->token = $token;
                        // $data->accessPages = explode(",", $access['Page_list']);
                        $otp = $this->OtpGeneration();
                        $data = [User::Otp => $otp];
                        $status = $model->update($user[User::UserId], $data);
                        if ($status) {
                            $user[User::Otp] = $otp;
                        }
                        $this->CreateTemplateForOtp($user);
                        //$this->setUserSession($user);
                        //$response = Response::SetResponse(200, $data, new Error());
                        return json_encode(['success' => true, 'csrf' => csrf_hash(), 'url' => base_url('/order/orderList'), 'user_id' => $user['user_id']]);
                    }
                }
            } else  $response = Response::SetResponse(405, null, new Error());
        } catch (DataBaseException $ex) {
            //$a= $this->Logger->log("error","Exception".$ex->getMessage());
            $response = Response::SetResponse(500, null, new Error($ex->getMessage()));
            //return view("user/login");
        } catch (Exception $ex) {

            $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
            //return view("user/login");
        }
        //return json_encode($response);
    }

    public function Signup()
    {
        try {
            if ($this->request->getMethod() == 'get') {

                return view("user/signup");
            } elseif ($this->request->getMethod() == 'post') {
                $rules = [
                    'first_name' => 'required|alpha|min_length[3]|max_length[10]',
                    'last_name' => 'required|alpha|max_length[10]',
                    //  'username' => 'required|min_length[6]|max_length[50]|ValidateUserName[username]',
                    'mail_id' => 'required|min_length[6]|max_length[50]|valid_email|validateEmail[mail_id]',
                    // 'phone_no' => 'required|numeric|exact_length[10]',
                    'password' => 'required|min_length[4]|max_length[255]',
                    'confirm_password' => 'required|min_length[4]|max_length[255]|matches[password]',
                ];
                $errors = [
                    // 'username' => [
                    //     'required' => 'You must choose a username.',
                    //     'ValidateUserName' => 'User name is already present.'
                    // ],

                    'first_name' => [
                        'required' => 'First name field is required.',
                        'alpha' => 'Please enter only alphabetical letters.',
                        'min_length' => 'Atleast 3 letters required.',
                        'max_length' => 'Maximum 10 letters.'
                    ],
                    'last_name' => [
                        'required' => 'Last name field is required',
                        'alpha' => 'Please enter only alphabetical letters.',
                        'max_length' => 'Maximum 10 letters.'
                    ],
                    'mail_id' => [
                        'required' => 'Email field is required',
                        'valid_email' => 'Please check the Email field. It does not appear to be valid.',
                        'validateEmail' => 'Email Address is already available',
                    ],
                    'password' => [
                        'required'        => 'Password field is required.',
                    ],
                    'confirm_password' => [
                        'required' => 'Confirm password field is required',
                        'matches' => 'Password mismatch',
                        'min_length' => "Please enter atleast 4 characters"
                    ]

                ];
                if (!$this->validate($rules, $errors)) {

                    $output = $this->validator->getErrors();
                    $errorMsg = implode(";", $output);
                    //$response = Response::SetResponse(400, null, new Error($errorMsg));

                    return json_encode(['success' => false, 'csrf' => csrf_hash(), 'error' => $output]);
                } else {

                    $request = $this->request->getPost();
                    $userId = $this->InsertUser($request);
                    $emailstatus = $this->CreateTemplateForMailReg($request, $userId);
                    // $response = Response::SetResponse(201, null, new Error());
                    return json_encode(['success' => true, 'csrf' => csrf_hash(), 'url' => base_url('/')]);
                }
            }
        } catch (DataBaseException $ex) {

            $response = Response::SetResponse(500, null, new Error($ex->getMessage()));
        } catch (Exception $ex) {

            $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
        }
        return json_encode($response);
    }

    private function InsertUser(array $postdata)
    {

        $model = ModelFactory::createModel(ModelNames::User);
        $data = [
            User::FirstName => $postdata['first_name'],
            User::LastName  => $postdata['last_name'],
            //  "username"   => $postdata['username'],
            User::MailId    =>  $postdata['mail_id'],
            // "phone_no"    =>  $postdata['phone_no'],
            User::Role      => Roles::Admin,
            User::Password  => password_hash($postdata['password'], PASSWORD_DEFAULT),
            User::Status    => Status::Inactive
        ];
        $userId = $model->insert($data);

        return $userId;
    }

    public function CreateTemplateForMailReg($postdata, $userId)
    {
        $whitelist = array('127.0.0.1', '::1');
        $mail = new PHPMailer(true);
        $userId = encrypt_url_segment($userId);

        $template = view("template/email-template-reg", ["postdata" => $postdata, "userId" => $userId]);
        $subject = "PPS Regsitration || Activate Account";

        try {
            if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {

                $mail->Host         = 'email-smtp.us-west-2.amazonaws.com';
                $mail->SMTPAuth     = true;
                $mail->Username     = 'AKIASKRV7H5JDOJCUGGT';
                $mail->Password     = 'BAYiFnjSzn1W4zX8+UC+xINscVJXCNY6XbQqTeY5p3V9';
            } else {
                $mail->Host         = 'smtp.gmail.com';
                $mail->SMTPAuth     =  true;
                $mail->Username     = 'hctoolssmtp@gmail.com';
                $mail->Password     = 'iyelinyqlqdsmhro';
            }
            $mail->isSMTP();
            $mail->SMTPAuth     = true;
            $mail->SMTPSecure   = 'tls';
            $mail->Port         = 587;
            $mail->Subject      = $subject;
            $mail->Body         = $template;
            $mail->setFrom('support@cxanalytix.in', 'PPS');
            $mail->addAddress($postdata["mail_id"]);
            $mail->isHTML(true);
            $response = $mail->send();

            if (!$response) {
                return "Something went wrong. Please try again." . $mail->ErrorInfo;
            } else {
                return "A New Account has created.";
            }
        } catch (Exception $e) {
            return "Something went wrong. Please try again." . $mail->ErrorInfo;
        }
    }

    public function ActivateAccount($encryptedVal)
    {

        try {
            $User_id = decrypt_url_segment($encryptedVal);

            $model = ModelFactory::createModel(ModelNames::User);
            $usersvalidate = $model->where(User::UserId, $User_id)->first();
            $updateId = $usersvalidate[User::UserId];
            $data = [User::Status => Status::Active];
            $statusupdate = $model->update($updateId, $data);

            if ($statusupdate) {
                $status = "You account is activated successfully. Please login and proceed.";
            } else {
                $status = "Something went wrong. Please try again.";
            }
            session()->setFlashdata('response', $status);
            // $response = Response::SetResponse(201, $statusupdate, new Error());
        } catch (DataBaseException $ex) {

            $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
        } catch (Exception $ex) {

            $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
        }
        return view("user/login");
    }

    public function Logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }

    public function ForgetPassword()
    {
        // if ($this->request->getMethod() == 'get') {

        //     return view('user/forgetPassword');
        // } else
        if ($this->request->getMethod() == 'post') {
            $rules = [
                'fp_mail_id' => 'required|min_length[6]|max_length[50]|valid_email|CheckEmail[mail_id]'
            ];

            $errors = [
                'fp_mail_id' => [
                    'required'        => 'Email address is required.',
                    'valid_email' => 'Please check the Email field. It does not appear to be valid.',
                    'CheckEmail' =>  "You don't have account, please create a new account."
                ],
            ];
            if (!$this->validate($rules, $errors)) {
                // return view('user/forgetPassword', [
                //     "validation" => $this->validator,
                // ]);
                $output = $this->validator->getErrors();
                $errorMsg = implode(";", $output);
                return json_encode(['success' => false, 'csrf' => csrf_hash(), 'error' => $output]);
            } else {
                $mailId = $this->request->getPost("fp_mail_id");
                $model = ModelFactory::createModel(ModelNames::User);
                $userData = $model->where(User::MailId, $mailId)->first();

                $random_key = $this->RandomKey();
                $data = [User::RandomKey => $random_key];
                $model->update($userData[User::UserId], $data);
                $userData[User::RandomKey] = $random_key;
                $emailstatus = $this->CreateTemplateForFP($userData);
                // session()->setFlashdata('response', $emailstatus);
                // return redirect()->to(base_url('forgetPassword'));
                return json_encode(['success' => true, 'csrf' => csrf_hash(), 'msg' => $emailstatus]);
            }
        }
        //return view("forgetpassword");
    }
    public function CreateTemplateForFP($userData)
    {
        $userData[User::UserId] = encrypt_url_segment($userData[User::UserId]);
        $whitelist = array('127.0.0.1', '::1');
        $mail = new PHPMailer(true);
        $template = view("template/email-template", ["userData" => $userData]);
        $subject = "PPS Account || Forget Password";
        try {
            if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {

                $mail->Host         = 'email-smtp.us-west-2.amazonaws.com';
                $mail->SMTPAuth     = true;
                $mail->Username     = 'AKIASKRV7H5JDOJCUGGT';
                $mail->Password     = 'BAYiFnjSzn1W4zX8+UC+xINscVJXCNY6XbQqTeY5p3V9';
            } else {
                $mail->Host         = 'smtp.gmail.com';
                $mail->SMTPAuth     =  true;
                $mail->Username     = 'hctoolssmtp@gmail.com';
                $mail->Password     = 'iyelinyqlqdsmhro';
            }
            $mail->isSMTP();
            $mail->SMTPAuth     = true;
            $mail->SMTPSecure   = 'tls';
            $mail->Port         = 587;
            $mail->Subject      = $subject;
            $mail->Body         = $template;
            $mail->setFrom('support@cxanalytix.in', 'PPS');
            $mail->addAddress($userData["mail_id"]);
            $mail->isHTML(true);
            $response = $mail->send();

            if (!$response) {
                return "Something went wrong. Please try again." . $mail->ErrorInfo;
            } else {
                return "Password reset link has been sent to your email";
            }
        } catch (Exception $e) {
            return "Something went wrong. Please try again." . $mail->ErrorInfo;
        }
    }
    public function CreateTemplateForOtp($userData)
    {
        //$userData[User::UserId] = encrypt_url_segment($userData[User::UserId]);
        $whitelist = array('127.0.0.1', '::1');
        $mail = new PHPMailer(true);
        $template = view("template/email-template-otp", ["userData" => $userData]);
        $subject = "PPS Account || OTP";
        try {
            if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {

                $mail->Host         = 'email-smtp.us-west-2.amazonaws.com';
                $mail->SMTPAuth     = true;
                $mail->Username     = 'AKIASKRV7H5JDOJCUGGT';
                $mail->Password     = 'BAYiFnjSzn1W4zX8+UC+xINscVJXCNY6XbQqTeY5p3V9';
            } else {
                $mail->Host         = 'smtp.gmail.com';
                $mail->SMTPAuth     =  true;
                $mail->Username     = 'hctoolssmtp@gmail.com';
                $mail->Password     = 'iyelinyqlqdsmhro';
            }
            $mail->isSMTP();
            $mail->SMTPAuth     = true;
            $mail->SMTPSecure   = 'tls';
            $mail->Port         = 587;
            $mail->Subject      = $subject;
            $mail->Body         = $template;
            $mail->setFrom('support@cxanalytix.in', 'PPS');
            $mail->addAddress($userData["mail_id"]);
            $mail->isHTML(true);
            $response = $mail->send();

            if (!$response) {
                return "Something went wrong. Please try again." . $mail->ErrorInfo;
            }
        } catch (Exception $e) {
            return "Something went wrong. Please try again." . $mail->ErrorInfo;
        }
    }
    public function ResetPassword($encryptedVal, $randomKey)
    {

        try {
            $userId = decrypt_url_segment($encryptedVal);

            if ($this->request->getMethod() == "get") {

                if (!$this->CheckKey($randomKey, $userId)) {
                    $changeStatus = "Link expired, please try again.";
                    session()->setFlashdata('response', $changeStatus);
                    return redirect()->to(uri: base_url());

                }
                return view("user/resetPassword", ["encrptVal" => $encryptedVal, "randomKey" => $randomKey]);
            } elseif ($this->request->getMethod() == "post") {
                $rules = [

                    'password' => 'required|min_length[4]|max_length[255]',
                    'confirm_password' => 'required|min_length[6]|max_length[255]|matches[password]',
                ];
                $errors = [

                    'password' => [
                        'required'        => 'Password field is required.',
                        'min_length' => "Please enter atleast 6 characters."
                    ],
                    'confirm_password' => [
                        'required' => 'Confirm password field is required',
                        'matches' => 'Password mismatch',
                        'min_length' => "Please enter atleast 6 characters"
                    ]

                ];
                if (!$this->validate($rules, $errors)) {

                    $output = $this->validator->getErrors();
                    $errorMsg = implode(";", $output);
                    //$response = Response::SetResponse(400, null, new Error($errorMsg));

                    //return json_encode(['success' => false, 'csrf' => csrf_hash(), 'error' => $output]);
                    return view("user/resetPassword", ["encrptVal" => $encryptedVal, "randomKey" => $randomKey, "validation" => $this->validator]);
                } else {

                    if (!$this->CheckKey($randomKey, $userId)) {
                        $changeStatus = "Link expired, please try again.";
                    } else {
                        $user_id = decrypt_url_segment($encryptedVal);
                        $request = $this->request->getPost();
                        $model = ModelFactory::createModel(ModelNames::User);
                        $user = $model->where(User::UserId, $user_id)->first();
                        $updateId = $user[User::UserId];
                        $data = [User::Password => password_hash($request['password'], PASSWORD_DEFAULT), User::RandomKey => ""];
                        $update = $model->update($updateId, $data);

                        if ($update) {
                            $changeStatus = "Password changed successfully.";
                        } else  $changeStatus = "Something went wrong. Please try again.";
                        // $response = Response::SetResponse(201, $update, new Error());

                    }
                    session()->setFlashdata(data: 'response', value: $changeStatus);
                    return redirect()->to(uri: base_url());
                }
            }
        } catch (DataBaseException $ex) {

            $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
        } catch (Exception $ex) {

            $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
        }
        //return view("user/login");
    }


    public function RandomKey()
    {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    public function OtpCheck()
    {

        try {
            if ($this->request->getMethod() == "post") {
                $request = $this->request->getPost();
                $user = $this->GetUser($request);
                $status = false;
                if ($user[User::Otp] == $request["otp"]) {
                    $model = ModelFactory::createModel(ModelNames::User);
                    $user[User::Otp] = "";
                    $data = [User::Otp => $user[User::Otp]];
                    $updateStatus = $model->update($user[User::UserId],  $data);
                    $status = $updateStatus;
                    $msg = "";
                    if (!$updateStatus) {
                        $msg = "Something went wrong. Please try again.";
                    }
                    $this->setUserSession($user);
                    //return json_encode(['success' => $updateStatus, 'csrf' => csrf_hash(), 'msg'=> ""]);

                } else {
                    // $user_id = decrypt_url_segment($encryptedVal);
                    //$request = $this->request->getPost();
                    $msg = "Incorrect Otp, Please try again";
                }

                // $response = Response::SetResponse(201, $update, new Error());
                //session()->setFlashdata('response', $changeStatus);

                return json_encode(['success' => $status, 'csrf' => csrf_hash(), 'msg' => $msg, "url" => base_url('order/orderList')]);
                //return view("user/resetPassword", ["encrptVal" => $encryptedVal, "randomKey" => $randomKey]);
            }
        } catch (DataBaseException $ex) {

            $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
        } catch (Exception $ex) {

            $response = Response::SetResponse($ex->getCode(), null, new Error($ex->getMessage()));
        }
        //return view("user/login");
    }
    public function OtpGeneration()
    {
        $num = "0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($num) - 1; //put the length -1 in cache
        for ($i = 0; $i < 6; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $num[$n];
        }
        return implode($pass); //turn the array into a string
    }

    private function setUserSession($user)
    {
        $data = [
            'id' => $user[User::UserId],
            'firstname' => $user[User::FirstName],
            'lastname' => $user[User::LastName],
            //'username' => $user['username'],
            //'status' => $user['status'],
            // 'phone_no' => $user['phone_no'],
            'email' => $user[User::MailId],
            'isLoggedIn' => true,
            "role" => $user[User::Role],

        ];

        session()->set($data);
        return true;
    }

    private function CheckKey($randomKey, $userId)
    {
        $model = ModelFactory::createModel(ModelNames::User);
        $user = $model->where(User::UserId, $userId)->first();

        if ($user[User::RandomKey] == $randomKey) {
            return true;
        }
    }

    private function GetUser($request)
    {
        $model = ModelFactory::createModel(ModelNames::User);
        $user = $model->where(User::UserId, $request[User::UserId])->first();

        return $user;
    }
}
