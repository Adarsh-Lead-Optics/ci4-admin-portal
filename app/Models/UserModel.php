<?php
namespace App\Models;
use CodeIgniter\Model; 

class UserModel extends Model{
      // protected $table = 'admin_table';
      protected $table = 'login_table';

    protected $primaryKey = 'id'; 
    protected $allowedFields = ['name', 'email', 'password', 'mobile', 'status', 'link'];
}

?>