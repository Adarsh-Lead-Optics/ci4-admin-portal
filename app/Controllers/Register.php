<?php 
namespace App\Controllers;
use App\Models\UserModel;

class Register extends BaseController{
    public function index(){
        helper('form');
        echo view('register_form');
    } 
    
    public function userRegister(){
        return view('user-login');
    }


    // public function verifyAccount($link){
    //     // $session = session();
    // } 


    // public function activate($link){
    //     $session = session();
    //     $userModel = new UserModel();

    //     $user = $userModel->where('link', $link)->first();


    //     if($user && isset($user['status']) && $user['status'] == 0){
    //         $userId = $user['id'];
    //         $updateProfile =  $userModel->update($userId, $user['status'] == 1);  

    //         if($updateProfile){
    //             echo 'User click the link <br>';
    //                 return $this->userRegister(); 
    //         }else{
    //                 echo 'Activation link not';
    //         }
    //     }else{
    //         echo 'Not Allowed to register this user 11111111111';
    //     }
    // }

        public function activate($link){
            $session = session();
            $userModel = new UserModel();

            $user = $userModel->where('link', $link)->first();

            if($user && isset($user['status']) && $user['status'] == 0){
                $userId = $user['id'];
                $statusUpdated = $userModel->update($userId, ['status' => 1]);  

                if($statusUpdated){
                    // echo 'User clicked the link <br>';
                    // return redirect()->to(base_url('user-login')); // Redirect to the user-login page
                    return $this->userRegister();
                }
                else{
                    echo 'Failed to update user status';
                }
            }
            else
            {
                echo '<p style="text-align:center;">Activation link already activate your account</p>';
            }
        }
    
    public function newUser(){ 
        $adminUser = $this->validate([
            'name' => 'required',
            'email' => 'required|valid_email',
            'mobile' => 'required',
            'password' => 'required',
        ]);

        if(!$adminUser){ 
            echo 'Not Register Yet !! You should check these all fields below.';
            return $this->index();
        } else{
            $userRequest = \Config\Services::request();
            $randomString = base64_encode(random_bytes(10));
            $data = [
                'name' => $userRequest->getPost('name'),
                'email' => $userRequest->getPost('email'),
                'mobile' => $userRequest->getPost('mobile'),
                'password' => md5($userRequest->getPost('password')), 
                'link' =>  $randomString = bin2hex(random_bytes(16)),
                'status' => 0,
            ]; 

            $deCodeStr = urldecode($randomString);
            $activationLink = base_url('register/activate/'.$deCodeStr);
            //  $messageLink = "Please confirm to activate your account ".anchor(uri:'user/activate/'.$deCodeStr, title:'Activate Now', attributes:''); 
              $messageLink = "Please confirm to activate your account <a href='$activationLink'>Activate Now</a>";

               
                $email = \Config\Services::email();
                $email->setFrom('serviceid02@gmail.com', 'Account Verification From Admin Portal');
                $email->setTo($data['email']);  
                $email->setSubject('Activate your account | Verification From Admin Portal');
                $email->setMessage($messageLink); 
    
                if ($email->send()) { 
                    $userModel = new UserModel();
                    $userModel->insert($data);
                    
                    echo '<h1 class"text-center" style="text-align: center;margin:25px 0px 0px 20px">Please Verify your account first</h1>' . '<p class="text-center" style="text-align: center;">Please check your ' . $data['email'] . '</p>' ;
                    return $this->activate('$link');
                } else {
                    echo 'Data not sent';
                    echo $email->printDebugger(['headers']);
                } 
        }  
    }  
    
}
?>
