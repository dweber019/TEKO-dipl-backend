<?php
/**
 * Created by PhpStorm.
 * User: tzhweda9
 * Date: 16.10.17
 * Time: 10:47
 */

namespace App\Helpers;


class UserInvitation
{

    /**
     * @var String
     */
    public $firstname;

    /**
     * @var String
     */
    public $lastname;

    /**
     * @var String
     */
    public $client;

    /**
     * UserInvitation constructor.
     * @param $firstname
     * @param $lastname
     */
    public function __construct($firstname, $lastname)
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->client = config('app.client');
    }


}