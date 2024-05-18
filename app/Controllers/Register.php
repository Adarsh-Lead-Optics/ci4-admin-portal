<?php 
namespace App\Controllers;
use App\Models\UserModel; 

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
    
    public function userProfile(){ 
        $session = session();
        $userData = new UserModel(); 
        $userData = $session->get('user');

        echo view('profile', ['userData' => $userData]);
         
    } 

    public function showMessage($type, $message, $duration = 5000, $autoHide = true){
        $data['type'] = $type;
        $data['message'] = $message; 
        $data['duration'] = $duration;
        $data['autoHide'] = $autoHide;  
        echo view('alert-message', $data);
    } 
    public function verify($link){ 
        $register = new Register();
        return $register->activate($link);  
     }  

    public function activate($link){ 
        $userModel = new UserModel();
        $user = $userModel->where('link', $link)->first();

        if ($user && isset($user['status']) && $user['status'] == 0) {
            $id = $user['id'];
            $statusUpdated = $userModel->update($id, ['status' => 1]);

            if ($statusUpdated) { 
                $userModel->update($id, ['link' => null]); 
                return view('verify-page');  
            } else { 
                $this->showMessage('alert-danger', 'Failed to update user status', 5000, true);
            }
        } else { 
            $this->showMessage('alert-success', 'Activation link already activated your account', 5000, true);
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
                // $session = session(); 
                $this->showMessage('alert-danger', 'This ' . $isEmail . ' already exist ! Please use other email', 5000, true);
                echo $this->index();
                exit();
            }  else{
                $randomString = bin2hex(random_bytes(10));
                $data = [
                    'name' => $userRequest->getPost('name'),
                    'email' => $userRequest->getPost('email'),
                    'mobile' => $userRequest->getPost('mobile'),
                    'password' => md5($userRequest->getPost('password')), 
                    'link' =>  $randomString,  
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
                     echo '<p class="text-center" style="text-align: center;width: 60%;font-size:14px;font-weight:600;color:black;position:relative;top:15rem;margin:auto;border:1px solid;border-radius:10px;background:#b4ecb4;padding:10px 10px;">Please check your ' . $data['email'] . ' for account verification </p>' ;
                    //   sleep(15); 
                    // echo '<script> 
                    //          setTimeout(function() {
                    //              window.location.href = "' . $userRegisterUrl . '"
                    //          }, 15000);
                    //        </script>'; 
                } else {
                    echo 'Data not sent';
                    echo $email->printDebugger(['headers']);
                } 
           } 
            
        }  
    }   
 
    public function loginUser(){
        $userModel = new UserModel();
        $session = session();
        $userRequest = \Config\Services::request();
        $email = $userRequest->getPost('email');
        $password = $userRequest->getPost('password');
    
        $result = $userModel->where('email', $email)->first();
         
        if(!$result){ 
            $this->showMessage('alert-danger', 'User details not found', 5000, true);
            echo $this->userRegister();
        } 
        if($result['status'] == 0){  
            $this->showMessage('alert-danger', 'Please verify your account first', 5000, true);
            echo $this->userRegister();
        } 
        if(md5($password) === $result['password']){ 
            $this->showMessage('alert-success', 'Login successful', 5000, true);  
            return $this->userProfile();
        } else { 
            $this->showMessage('alert-danger', 'Invalid email or password', 5000, true);
            echo $this->userRegister();
        }

        if($result){
            $adminData = [
                'id' => $result['id'],
                'name' => $result['name'],
                'email' => $result['email'],
                'mobile' => $result['mobile'],
                'password' => $result['password'],
                'status' => $result['status'],
                'link' => $result['link']
            ];
            $session->set('user', $adminData);
        } 

    }  
}
?>
