<?php

namespace App\Http\Controllers;

use App\Models\AdminLogin;
use App\Models\Config as ModelsConfig;
use App\Models\Logs;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use SpomkyLabs\Pki\ASN1\Type\Primitive\Real;

class AdminController extends Controller
{
    //Страница админа
    public function admin(Request $request)
    {
        
        $logs = Logs::orderBy('logs.log_create_time', 'desc');
        if(isset($request->user))
        {
            $logs = $logs->join('users', 'logs.id_user', '=', 'users.id')
            ->where('users.name', 'LIKE', '%'. $request->user .'%');
        }
        if(isset($request->filter))
        {
            $logs =  $logs->where('action', $request->filter);
        }
       
        $logs =  $logs->get();
        return view('admin', ['logs' => $logs, 'filter' => $request->filter , 'user' => $request->user]);
    }
    //Авторизация для админа
    public function adminLogin(Request $request)
    {
        $messages = [
            'login.required' => 'Поле логин обязательно для заполнения.',
            'password.required' => 'Поле пароль обязательно дл  я заполнения.',
            'password.min' => 'Пароль должен быть длинее 6 символов.',
        ];
        $validated = $request->validate([
            'login' => 'required',
            'password' => 'required|min:6',

        ], $messages);
        $login = $request->input('login');
        $password = hash('sha512', $request->input('password'));
        $adminCheck = ModelsConfig::where('login', '=', $login)->where('password', '=', $password)->count();
        if($adminCheck == 0)
        {
            return redirect('/loginAdmin')->withErrors(['password' => 'Неверный логин или пароль']);
        }
        $admin = ModelsConfig::where('login', '=', $login, 'AND', 'password', '=',  $password)->first();
       
        session([ 'name' => $admin->role['name_roles'], 'role' => $admin->role['name_roles']]);
        return redirect('/admin')->with(['success' => 'Авторизация админестратора проша успешно']);
    }
    //страница управления пользователями
    public function userControl()
    {
        $users = User::get();
        $roles = Role::get();

        return view('userControl', ['users' => $users, 'roles' =>  $roles]);
    }
    //страница управления ролями
    public function roleControl()
    {
        $roles = Role::get();
        return view('roleControl', ['roles' =>  $roles]);
    }
    //Добавление пользователя
    public function addUser(Request $request)
    {
        $email = $request->input('email');
        $role = $request->input('role');
        User::insert(['email' => $email, 'id_role' => $role]);
        return redirect('userControl')->with('success', 'Пользователь добавлен');
    }
    //Удаление пользователя
    public function deleteUser(Request $request)
    {
        $id = $request->input('id_user');
        User::where('id', $id)->update(['status' => 'Удалён', 'id_role' => null]);
        return redirect('userControl')->with('success', 'Пользователь удален');
    }
    //изменение пользователя
    public function editUser(Request $request)
    {
        $id = $request->input('id_user');
        $role = $request->role;
        if( $role == 'NULL')
        {
            return redirect('userControl')->with('errors', 'Выбирите роль');
        }
        User::where('id', $id)->update(['id_role' => $role, 'status' => null]);
        return redirect('userControl')->with('success', 'Пользователь изменен');
        // dd($id, $role);
    }
    //добавление роли
    public function addRole(Request $request)
    {
        $validated = $request->validate([
            'role_name' => 'required|min:1|max:10',
            'deception' => 'required|min:6|max:30',
            'static_key' => 'required',
            'secret_key' => 'required',
            
        ]);
        $role_name = $request->input('role_name');
        $deception = $request->input('deception');
        $static_key = $request->input('static_key');
        $secret_key = $request->input('secret_key');
        Role::insert(['name_roles' => $role_name, 'deception' =>$deception, 'yandex_cloud_id' =>  $static_key, 'yandex_cloud_secret_id' => $secret_key]);
        return redirect('roleControl')->with('success', 'Роль добавлена');
    }
    //удаление роли
    public function deleteRole(Request $request)
    {
        $id_role = $request->input('id_role');
        User::where('id_role', $id_role)->update(['id_role' => null]);
        Role::where('id',  $id_role)->delete();
        return redirect('roleControl')->with('success', 'Роль удалена');
    }
    //изменение роли
    public function editRole(Request $request)
    {
        $idRole= $request->input('id_role');
        $statickKey = $request->input('static_key');
        $secretKey = $request->input('secret_key');
        Role::where('id', $idRole)->update(['yandex_cloud_id' => $statickKey, 'yandex_cloud_secret_id' =>  $secretKey]);
        return redirect('roleControl')->with('success', 'Роль изменена');
    }
    //Страница настроек
    public function settings()
    {
        return view('settings');
    }

    public function changePassword(Request $request)
    {
        $messages = [
            'oldPassword.required' => 'Поле старый пароль обязательно для заполнения.',
            'password.required' => 'Поле пароль обязательно для заполнения.',
            'password.min' => 'Пароль должен быть длинее 6 символов.',
            'oldPassword.min' => 'Старый пароль должен быть длинее 6 символов.',
            'password.max' => 'Пароль должен быть короче 20 символов.',
            'oldPassword.max' => 'Старый пароль должен быть короче 20 символов.',
        ];
        $validated = $request->validate([
            'oldPassword' => 'required|min:6|max:20',
            'password' => 'required|min:6|max:20',
        ], $messages);
        
        $oldPassword = $request->input('oldPassword');
        $asd = ModelsConfig::where('password', hash('sha512', $oldPassword))->count();
        if($asd == 1)
        {
       
            ModelsConfig::where('password', hash('sha512', $oldPassword))->update(['password' => hash('sha512', $oldPassword) ]);
            return redirect('settings')->with('success_action', 'Пароль изменен');
        
        } else {
        return redirect('settings')->with('errors_action', 'Неверный пароль');
        
        }
        
    }

    //Страница входа админестратора
    public function loginAdmin()
    {
        return view('loginAdmin');
    }
    
}
