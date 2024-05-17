<?php 
namespace App\Controllers;
use App\Models\UserModel;
use CodeIgniter\HTTP\URI;

class Register extends BaseController{
    public function index(){
        helper('form');
        $session = \Config\Services::session();
        $data['message'] = $session->getFlashdata('message'); 
        echo view('register_form', $data);
    } 
    
    public function userRegister(){
        helper('form'); 
        return view('user-login');
    }
    
    
    public function verify($link)
     { 
        $register = new Register();
        return $register->activate($link);  
     }  

    public function activate($link)
    { 
        $userModel = new UserModel();
        $user = $userModel->where('link', $link)->first();

        if ($user && isset($user['status']) && $user['status'] == 0) {
            $id = $user['id'];
            $statusUpdated = $userModel->update($id, ['status' => 1]);

            if ($statusUpdated) { 
                $userModel->update($id, ['link' => null]); 
                return view('verify-page');  
            } else {
                echo 'Failed to update user status';
            }
        } else {
            echo '<p style="text-align:center;">Activation link already activated your account</p>';
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
              echo 'User not vaild';
            echo $this->index();
        } else{
            $userRequest = \Config\Services::request();
            $isEmail = $userRequest->getPost('email');
            $userModel = new UserModel();
            $existingUser = $userModel->where('email', $isEmail)->first();
            
            if($existingUser){
                $session = session();
                $session->setFlashdata('message', 'This ' . $isEmail . 'already exist ! Please use other email');
                echo $this->index();
            }
            // else{

            // }

            $randomString = bin2hex(random_bytes(10));
            $data = [
                'name' => $userRequest->getPost('name'),
                'email' => $userRequest->getPost('email'),
                'mobile' => $userRequest->getPost('mobile'),
                'password' => md5($userRequest->getPost('password')), 
                'link' =>  $randomString, // Simplified
                'status' => 0,
            ]; 

            $activationLink = base_url('verify/'.$data['link']);
            $messageLink = "Please confirm to activate your account <a href='$activationLink'>Activate Now</a>";

            $email = \Config\Services::email();
            $email->setFrom('serviceid02@gmail.com', 'Account Verification From Admin Portal');
            $email->setTo($data['email']);  
            $email->setSubject('Activate your account | Verification From Admin Portal');
            $email->setMessage($messageLink); 
    
            if ($email->send()) { 
                $session = session();
                $userModel = new UserModel();
                $userModel->insert($data); 
                $userRegisterUrl = $this->userRegister(); 
                echo '<h1 class"text-center" style="text-align: center;margin:25px 0px 0px 20px">Please Verify your account first</h1>' . '<p class="text-center" style="text-align: center;">Please check your ' . $data['email'] . '</p>' ;
                sleep(10);
                echo '<script> 
                         setTimeout(function() {
                             window.location.href = "' . $userRegisterUrl . '"
                         }, 20000);
                       </script>';

                // sleep(30);
                // header("location: $userRegisterUrl");
                // exit();
            } else {
                echo 'Data not sent';
                echo $email->printDebugger(['headers']);
            } 
        }  
    }   
}
?>
