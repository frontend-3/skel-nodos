<?php
namespace AdminAuth\Controller;

use Zend\Db\Sql\Sql;
use Zend\Crypt\Password\Bcrypt;
use Zend\Mvc\Controller\AbstractActionController;

use Zend\Console\Prompt\Line;

use AdminAuth\Model\AdminUser;
use AdminAuth\Model\AdminRole;
use AdminAuth\Model\AdminPermission;
use AdminAuth\Model\AdminRolePermission;

class AdminAuthLoadController extends AbstractActionController{

    public function helpAction($value = '') {
        $config = $this->getServiceLocator()->get('Config');
        $routes = $config['console']['router']['routes'];
        
        foreach ($routes as $key => $value) {
            echo $value['options']['route'];
            print PHP_EOL;
        }
    }
    
    
    public function updatePasswordSuperUserAction() {
        $table_user = $this->getServiceLocator()->get('ServiceAuthUser');
        $user = $table_user->first(array('username' => 'admin'));
        if($user) {
            $password = Line::prompt(
                'Write new your password, please',
                false,
                100
            );
            $bcrypt = new Bcrypt();
            $user->password = $bcrypt->create($password);
            $table_user->save($user);
            
            echo "Password updated for superadmin";
            echo PHP_EOL;
        }
    }
    
    
    public function updatePermsAction() {
        $routes = $this->getServiceLocator()->get('Config')['router']['routes'];
        $keys = array_keys($routes['admin']['child_routes']);
        $exclude = array('system_users', 'system_roles', 'system_permissions', 'welcome', 'logout');
        $methods = array('list', 'add', 'edit', 'update', 'view', 'delete');
        $time = date('Y-m-d H:i:s');
        $table_permission = $this->getServiceLocator()->get('ServiceAuthPermission');
        $table_role = $this->getServiceLocator()->get('ServiceAuthRole');
        $role = $table_role->first(array('name'=>'Admin'));
        $id_role = $role->id;
        $table_roles_permission = $this->getServiceLocator()->get('ServiceAuthRolePermission');
        foreach ($keys as $key => $value) {
            if(!in_array($value, $exclude)){
                $url = substr($routes['admin']['child_routes'][$value]['options']['route'], 1);
                foreach ($methods as $meth) {
                    $data = array();
                    $data['name'] = sprintf("%s-%s", $url,$meth);
                    $p = $table_permission->first(array('name'=>$data['name']));
                    if(!$p){
                        $data['label'] = sprintf("%s-%s", $url,$meth);
                        $data['created_at'] = $time;
                        $data['updated_at'] = $time;
                        $data['status'] = 1;
                        $id_perm = $table_permission->save_array($data);
                        $data = array();
                        $data['role_id'] = $id_role;
                        $data['permission_id'] = $id_perm;
                        $table_roles_permission->save_array($data);
                    }
                }
            }
        }
    }
    
    
    public function createSuperUserAction() {
        $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $table_roles_permission = $this->getServiceLocator()->get('ServiceAuthRolePermission');
        $table_role = $this->getServiceLocator()->get('ServiceAuthRole');
        $table_permission = $this->getServiceLocator()->get('ServiceAuthPermission');
        $table_user = $this->getServiceLocator()->get('ServiceAuthUser');

        $table_roles_permission->delete_all($adapter);
        $table_user->delete_all($adapter);
        $table_role->delete_all($adapter);
        $table_permission->delete_all($adapter);
        $time = date('Y-m-d H:i:s');
        $data = array();
        $data['name'] = 'Admin';
        $data['created_at'] = $time;
        $data['updated_at'] = $time;
        $id_role = $table_role->save_array($data);
        $perms = array('system_users', 'system_roles', 'system_permissions');

        $methods = array('list', 'add', 'edit', 'update', 'view', 'delete');

        foreach ($perms as $perm) {
            foreach ($methods as $meth) {
                $data = array();
                $data['name'] = sprintf("%s-%s", $perm,$meth);
                $data['label'] = sprintf("%s-%s", $perm,$meth);
                $data['created_at'] = $time;
                $data['updated_at'] = $time;
                $data['status'] = 1;
                $id_perm = $table_permission->save_array($data);
                $data = array();
                $data['role_id'] = $id_role;
                $data['permission_id'] = $id_perm;
                $table_roles_permission->save_array($data);
            }
        }

        $password = Line::prompt(
            'Write your password, please',
            false,
            100
        );
        $role = $table_role->first(array('id'));
        $id = $role->id;
        $data = array();
        $data['first_name'] = 'Nodos';
        $data['last_name'] = 'Digital';
        $data['username'] = 'Admin';
        $data['created_at'] = $time;
        $data['updated_at'] = $time;
        $data['email'] = 'desarrollo@nodosdigital.pe';
        $bcrypt = new Bcrypt();
        $data['password'] = $bcrypt->create($password);
        $data['status'] = 'enabled';
        $data['is_superuser'] = 1;
        $data['role_id'] = $id;
        $time = date('Y-m-d H:i:s');
        $data['last_login'] = $time;
        $table_user->save_array($data);
        echo "End record superuser";
    }
}
