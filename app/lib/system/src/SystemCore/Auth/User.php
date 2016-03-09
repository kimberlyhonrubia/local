<?php namespace SystemCore\Auth;

/**
 * SystemCore\Auth\User
 *
 * Handle request for Authenticating all users that
 * will login, to our System.
 *
 * @package SystemCore\Auth\User
 * @author  Anthony Pillos <dev.anthonypillos@gmail.com>
 * @version v1
 */

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;


use Sentry;
use SystemCore\Traits\Logger;
use Models\Repositories\UserRepository;

class User {

    use Logger;


    protected $currentUser;
    protected $groupname                = 'customer';
    protected $user;

    protected $credentials              = [];
    protected $rules                    = [];
    protected $loginAttr                = [];
    protected $rememberme               = false;
    protected $sessionName              = 'userinfo';
    protected $notAllowedGroups         = [];
    protected $checkForNotAllowedGroups = false;


	/**
    * __construct()
    * Initialize our Class Here for Dependecy Injection
    *
    * @return void
    * @access  public
    **/
	public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

}