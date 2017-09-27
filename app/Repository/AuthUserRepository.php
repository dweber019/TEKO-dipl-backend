<?php
/**
 * Created by PhpStorm.
 * User: tzhweda9
 * Date: 27.09.17
 * Time: 11:33
 */

namespace App\Repository;

use App\Models\User;
use Auth0\Login\Contract\Auth0UserRepository;
use Auth0\Login\facade\Auth0;
use Illuminate\Auth\AuthenticationException;

class AuthUserRepository implements Auth0UserRepository
{
    /* This class is used on api authN to fetch the user based on the jwt.*/
    public function getUserByDecodedJWT($jwt) {
        /*
         * The `sub` claim in the token represents the subject of the token
         * and it is always the `user_id`
         */
        $jwt->user_id = $jwt->sub;

        return $this->upsertUser($jwt);
    }

    public function getUserByUserInfo($userInfo) {
        return $this->upsertUser($userInfo['profile']);
    }

    protected function upsertUser($profile) {

        $user = User::where("auth0_id", $profile->user_id)->first();

        if ($user === null) {

            $authHeader = request()->headers->get('Authorization');

            $client = new \GuzzleHttp\Client();
            $res = $client->request('GET', 'https://' . config('laravel-auth0.domain') .'/userinfo', [
              'headers' => [
                'Authorization' => $authHeader,
                'Accept'     => 'application/json',
              ]
            ]);

            if($res->getStatusCode() !== 200) {
                throw new AuthenticationException('Unauthorized');
            }

            $userInfo = json_decode($res->getBody());

            $user = User::where("invite_email", $userInfo->email)->first();

            if ($user !== null) {
                $user->auth0_id = $profile->user_id;
                $user->save();
            } else {
                throw new AuthenticationException('Unauthorized');
            }
        }

        return $user;
    }

    public function getUserByIdentifier($identifier) {
        //Get the user info of the user logged in (probably in session)
        $user = \App::make('auth0')->getUser();

        if ($user === null) return null;

        // build the user
        $user = $this->getUserByUserInfo($user);

        // it is not the same user as logged in, it is not valid
        if ($user && $user->auth0id == $identifier) {
            return $user;
        }
    }
}