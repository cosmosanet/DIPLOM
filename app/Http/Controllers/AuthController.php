<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
class AuthController extends Controller
{


   public function login(Request $request)
    {
        // $Jwt = $this->getJwtToken(); Не удалять
        $code = $request->code;
        // $oathKey = $this->getOath($code); //раскоментировать при загрузке на хостинк
        // $userData = $this->getApiUserInfo($oathKey);
        // $email = $userData['default_email']; 
        $email = 'kaba4okus@yandex.ru';
        if($code == null)
        {
            return redirect('/loginPage');
        }
        if($this->checkUser($email)) {
            $this->logout();
            // $iamKey = $this->getIAmKey($Jwt); //Не удалять
            $userInfo = $this->getUsernfo($email);
            if($userInfo->status == "Удалён" || $userInfo->id_role == null)
            {
                return redirect('/loginPage')->with('errors', 'У вас нет доступа к этому ресурсу');
            }
            else {
            // $secretId = $userInfo->role['yandex_secret_id']; //Не удалять
            $userId = $userInfo->id;
            $userName = $userInfo->name;
      
            // if($userName == null) //раскоментировать при загрузке на хостинк
            // {
            //     User::where('id', $userId)->update(['name' => $userData['real_name']]);
            //     $userName =  $userData['real_name'];
            // }
            // $apiKeys = $this->getApiKeys($secretId, $iamKey); //Не удалять
            // session(['email' => $email , 'name' => $userName, 'apiKey' => $apiKeys->key, 'secretApiKey' => $apiKeys->textValue, 'userId' => $userId , 'iamKey' => $iamKey]);
            session(['email' => $email , 'name' => $userName, 'apiKey' => $userInfo->role['yandex_cloud_id'], 'secretApiKey' => $userInfo->role['yandex_cloud_secret_id'], 'userId' => $userId, 'role' => $userInfo->role['name_roles'] ]);
            return redirect('/')->with(['success' => 'Авторизация проша успешно']);
            }
           
        } else {
          return redirect('/loginPage')->with('errors', 'У вас нет доступа к этому ресурсу');
        }
       
    }

    public function loginPage()
    {
        return view('loginPage');


        
    }

    public function logout()
    {
        session()->flush();
        return redirect('/loginPage');
    }

    // private function getIAmKey(string $jwt)
    // {
    //     $data = [
    //         'Content-Type' => 'application/json',
    //         'jwt' => $jwt,
    //     ];

    //     $client = new Client();

       
    //     $response = $client->post('https://iam.api.cloud.yandex.net/iam/v1/tokens', [
    //         'json' => $data,
    //     ]);
    //     dd($response);
    // }

    // private function getApiKeys(string $secretId, string $iAmKey)
    // {
    //     $curl = curl_init();

    //     $url = "https://payload.lockbox.api.cloud.yandex.net/lockbox/v1/secrets/" . $secretId . "/payload";
    //     $headers = array(
    //         'Authorization: Bearer ' . $iAmKey,
    //     );

    //     curl_setopt($curl, CURLOPT_URL, $url);
    //     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    //     $response = json_decode(curl_exec($curl));
 
    //     if(isset($response->code)){
    //         $massage = $response ;
    //         return $massage;
    //     }

    //     return $response->entries[0];
    // }

    private function checkUser(string $email)
        {
            $result = User::where('email', $email)->first();
            return $result ? true : false;
        }
        
    private function getUsernfo(string $email)
        {
            $result = User::where('email', $email)->first();
            return  $result;
        }

    private function getOath($code)
    {

        $client = new Client();
        $response = $client->post('https://oauth.yandex.ru/token', [
            'form_params' => [
                    "grant_type"=>'authorization_code',
                    "code" => $code,
                    "client_id" => config('app.yandex_id_key'),
                    'client_secret' => config('app.yandex_secret_id_key'),
            ],
        ]);
        $data = json_decode($response->getBody(), true);
        return $data['access_token'];

    }


    private function getApiUserInfo(string $OAth)
    {
        $client = new Client();
        $response = $client->get('https://login.yandex.ru/info?format=json&jwt_secre=' . config('app.yandex_secret_id_key'), [
            'headers' => [
                    "Authorization"=>'OAuth ' . $OAth,  
            ],
        ]);
        $data = json_decode($response->getBody(), true);
        return $data;
    }
}
