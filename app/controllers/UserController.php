<?php

class UserController extends BaseController {
  
  public function login() {
    
    $action = Session::get('action', null);
    
    // Save referrer to session if need be
    if (!$action && URL::previous() != URL::current()) {
      Session::put('login_referrer', URL::previous());
    }
    
    return View::make('user.login', array(
        "error_login" => $action == 'login',
        "error_create" => $action == "create"
    ));
  }
  
  public function submitLogin() {
    $username = Input::get('login_username');
    $password = Input::get('login_password');
    $remember = Input::get('login_remember');
    
    $user = User::getWithUsernameAndPassword($username, $password);
    
    if ($user) {
      // Log user in
      Session::put('user_id', $user->id);
      // Save cookies
      if ($remember) {
        $cookiePassword = User::getCookiePassword($password, $user->password);
        Cookie::queue(User::getCookieUsernameName(), $username, 365 * 24 * 60);
        Cookie::queue(User::getCookiePasswordName(), $cookiePassword, 365 * 24 * 60);
      }
      // Redirect to previous page
      $referrer = Session::get('login_referrer', URL::route('home'));
      Session::forget('login_referrer');
      return Redirect::to($referrer);
    }
    
    // No matching user
    return Redirect::route('login')->withInput()->with('action', 'login');
  }

  public function logout() {
    // Unlog user
    Session::flush();
    // Remove cookies
    Cookie::queue(User::getCookieUsernameName(), null, -1);
    Cookie::queue(User::getCookiePasswordName(), null, -1);
    // Redirect to previous page
    return \Symfony\Component\HttpFoundation\RedirectResponse::create(URL::previous());
  }
  
  public function create() {
    // Retrieve data from form
    $username = Input::get('create_username');
    $email = strtolower(Input::get('create_email'));
    $password = Input::get('create_password');
    $remember = Input::get('create_remember');
    
    // Validate data
    $validator = Validator::make(
            array(
                "create_username" => $username,
                "create_email" => $email,
                "create_password" => $password,
            ),
            array(
                "create_username" => "required|unique:users,username",
                "create_email" => "required|email",
                "create_password" => "required|min:6",
            ),
            array(
                "create_username.required" => "Veuillez entrer un nom d'utilisateur.",
                "create_username.unique" => "Ce nom d'utilisateur est déjà utilisé. Choisissez-en un autre.",
                "create_email.required" => "Veuillez entrer votre adresse e-mail.",
                "create_email.email" => "Votre adresse e-mail n'est pas valide.",
                "create_password.required" => "Veuillez entrer un mot de passe.",
                "create_password.min" => "Votre mot de passe doit compter au moins 6 caractères.",
            )
    );
    if ($validator->fails()) {
      return Redirect::to(URL::route('login') . '#nouvel-utilisateur')->withInput()->withErrors($validator)->with('action', 'create');
    }
    
    // Validation passed, create user
    $user = User::createWith($username, $email, $password);
    
    // Send verification e-mail
    $emailContent = View::make('emails.createUser', array(
        'website_name' => Parameter::get(Parameter::$UNIT_SHORT_NAME),
        'verification_code' => $user->verification_code,
        'ban_email_code' => BannedEmail::getCodeForEmail($email),
    ))->render();
    $pendingEmail = PendingEmail::create(array(
        'subject' => "[Site " . Parameter::get(Parameter::$UNIT_SHORT_NAME) . "] Validez votre compte d'utilisateur",
        'raw_body' => $emailContent,
        'sender_name' => "Site " . Parameter::get(Parameter::$UNIT_SHORT_NAME),
        'sender_email' => Parameter::get(Parameter::$DEFAULT_EMAIL_FROM_ADDRESS),
        'recipient' => $email,
        'priority' => PendingEmail::$PERSONAL_EMAIL_PRIORITY,
    ));
    $pendingEmail->send();
    
    // Log user in
    Session::put('user_id', $user->id);
    
    // Save cookies
    if ($remember) {
      $cookiePassword = User::getCookiePassword($password, $user->password);
      Cookie::queue(User::getCookieUsernameName(), $username, 365 * 24 * 60);
      Cookie::queue(User::getCookiePasswordName(), $cookiePassword, 365 * 24 * 60);
    }
    
    // Redirect to previous page
    $referrer = Session::get('login_referrer', URL::route('home'));
    Session::forget('login_referrer');
    return Redirect::to($referrer);
    
  }
  
  public function verify($code) {
    
    // Find user corresponding to verification code
    $user = User::where('verification_code', '=', $code)->first();
    
    if ($user) {
      // User exists, mark it as verified
      $user->verified = true;
      $user->save();
      $status = "verified";
    } else {
      // Code is invalid
      $status = "unknown";
    }
    
    return View::make('user.verify', array('status' => $status));
    
  }
  
  public function cancelVerification($code) {
    $user = User::where('verification_code', '=', $code)->first();
    if ($user) {
      if ($user->verified) {
        $status = "already verified";
      } else {
        $user->delete();
        $status = "canceled";
      }
    } else {
      $status = "unknown";
    }
    return View::make('user.verify', array('status' => $status));
  }
  
  public function editUser($action = null) {
    
    if (!$this->user->isConnected()) {
      return Redirect::route('login');
    }
    
    if (Request::isMethod('post')) {
      $oldPassword = Input::get('old_password');
      $email = strtolower(Input::get('email'));
      $password = Input::get('password');
      $defaultSection = Input::get('default_section');
      
      // Check that the old password is valid
      $oldPasswordValid = User::testPassword($oldPassword, $this->user->password);
      
      if ($oldPasswordValid || $action == 'section') {
        if ($action == 'email') {
          // Validation for e-mail update
          $validator = Validator::make(
                  array("email" => $email),
                  array("email" => "required|email"),
                  array(
                      "email.required" => "Veuillez entrer votre adresse e-mail.",
                      "email.email" => "L'adresse e-mail n'est pas valide.",
                  )
          );
          if ($validator->fails()) {
            return Redirect::to(URL::route('edit_user_email') . "#modification")
                    ->withInput()
                    ->withErrors($validator);
          } else {
            $this->user->changeEmail($email);
            // Send validation link by e-mail
            $emailContent = View::make('emails.changeUserEmailAddress', array(
                'website_name' => Parameter::get(Parameter::$UNIT_SHORT_NAME),
                'verification_code' => $this->user->verification_code,
                'ban_email_code' => BannedEmail::getCodeForEmail($this->user->email),
            ))->render();
            $pendingEmail = PendingEmail::create(array(
                'subject' => "[Site " . Parameter::get(Parameter::$UNIT_SHORT_NAME) . "] Activer votre compte d'utilisateur",
                'raw_body' => $emailContent,
                'sender_name' => "Site " . Parameter::get(Parameter::$UNIT_SHORT_NAME),
                'sender_email' => Parameter::get(Parameter::$DEFAULT_EMAIL_FROM_ADDRESS),
                'recipient' => $this->user->email,
                'priority' => PendingEmail::$PERSONAL_EMAIL_PRIORITY,
            ));
            $pendingEmail->send();
            return Redirect::route('edit_user')->with('success_message', 'Votre adresse e-mail a été modifiée avec succès. Un lien de validation vous a été envoyé par e-mail.');
          }
        } elseif ($action == 'password') {
          // Validation for password update
          $validator = Validator::make(
                  array("password" => $password),
                  array("password" => "required|min:6"),
                  array(
                      "password.required" => "Veuillez entrer un nouveau mot de passe.",
                      "password.min" => "Votre mot de passe doit compter au moins 6 caractères.",
                  )
          );
          if ($validator->fails()) {
            return Redirect::to(URL::route('edit_user_password') . "#modification")
                    ->withInput()
                    ->withErrors($validator);
          } else {
            $this->user->changePassword($password);
            return Redirect::route('edit_user')
                    ->with('success_message', 'Votre mot de passe a été modifié avec succès.');
          }
        } elseif ($action == 'section') {
          // Validation for default section update
          $validator = Validator::make(
                  array("default_section" => $defaultSection),
                  array("default_section" => "required|integer")
          );
          if ($validator->fails()) {
            return Redirect::to(URL::route('edit_user_section') . "#modification")
                    ->withInput()
                    ->withErrors($validator);
          } else {
            $this->user->changeDefaultSection($defaultSection);
            return Redirect::route('edit_user')
                    ->with('success_message', 'Votre section par défaut a été modifiée avec succès.');
          }
          
        }
      } else {
        return Redirect::to(URL::current() . "#modification")
                ->withInput()
                ->withErrors(array('old_password' => 'Le mot de passe actuel est erronné'));
      }
    }
    
    // Get section list for default section selection
    $sections = array();
    if ($action == 'section') {
      $sections = Section::getSectionsForSelect();
    }
    
    return View::make('user.edit', array(
        'action' => $action,
        'sections' => $sections,
    ));
    
  }
  
  public function editEmail() {
    return $this->editUser('email');
  }
  
  public function editPassword() {
    return $this->editUser('password');
  }
  
  public function editSection() {
    return $this->editUser('section');
  }
  
  public function resendValidationLink() {
    // Send validation link by e-mail
    $emailContent = View::make('emails.resendValitationLink', array(
        'website_name' => Parameter::get(Parameter::$UNIT_SHORT_NAME),
        'verification_code' => $this->user->verification_code,
        'ban_email_code' => BannedEmail::getCodeForEmail($this->user->email),
    ))->render();
    $pendingEmail = PendingEmail::create(array(
        'subject' => "[Site " . Parameter::get(Parameter::$UNIT_SHORT_NAME) . "] Activer votre compte d'utilisateur",
        'raw_body' => $emailContent,
        'sender_name' => "Site " . Parameter::get(Parameter::$UNIT_SHORT_NAME),
        'sender_email' => Parameter::get(Parameter::$DEFAULT_EMAIL_FROM_ADDRESS),
        'recipient' => $this->user->email,
        'priority' => PendingEmail::$PERSONAL_EMAIL_PRIORITY,
    ));
    $pendingEmail->send();
    return Redirect::to(URL::previous())->with('success_message', 'Un e-mail avec le lien de validation vous a été envoyé.');
  }
  
  public function retrievePassword() {
    
    if (Request::isMethod('post')) {
      $email = strtolower(Input::get('email'));
      $users = User::where('email', '=', $email)->get();
      if (count($users)) {
        $passwordRecoveries = array();
        foreach ($users as $user) {
          $passwordRecoveries[$user->username] = PasswordRecovery::createForUser($user);
        }
        // Send e-mail
        $emailContent = View::make('emails.passwordRecovery', array(
            'recoveries' => $passwordRecoveries,
            'website_name' => Parameter::get(Parameter::$UNIT_SHORT_NAME)
        ))->render();
        $pendingEmail = PendingEmail::create(array(
            'subject' => 'Récupérer votre mot de passe',
            'raw_body' => $emailContent,
            'sender_name' => "Site " . Parameter::get(Parameter::$UNIT_SHORT_NAME),
            'sender_email' => Parameter::get(Parameter::$DEFAULT_EMAIL_FROM_ADDRESS),
            'recipient' => $email,
            'priority' => PendingEmail::$PERSONAL_EMAIL_PRIORITY,
        ));
        $pendingEmail->send();
        return Redirect::to(URL::current())->with('success_message', "Un e-mail a été envoyé à $email.");
      } else {
        return Redirect::to(URL::current())->with('error_message', "Aucun utilisateur n'est enregistré avec l'adresse $email.");
      }
    }
    return View::make('user.retrieve_password');
  }
  
  public function changePassword($code) {
    if ($code != 'done') {
      $passwordRecovery = PasswordRecovery::where('code', '=', $code)
              ->where('timestamp', '>=', time() - 24*3600) // A password recovery code is valid for 24 hours
              ->first();
      if (!$passwordRecovery) {
        $status = 'unknown';
      } else {
        if (Request::isMethod('post')) {
          $password = Input::get('password');
          $validator = Validator::make(
                  array("password" => $password),
                  array("password" => "required|min:6"),
                  array(
                      "password.required" => "Veuillez entrer un nouveau mot de passe.",
                      "password.min" => "Votre mot de passe doit compter au moins 6 caractères.",
                  )
          );
          if ($validator->fails()) {
            return Redirect::route('change_password', array('code' => $code))->withErrors($validator);
          } else {
            $user = $passwordRecovery->getUser();
            $user->changePassword($password);
            $passwordRecovery->delete();
            return Redirect::route('change_password', array('code' => 'done'));
          }
        } else {
          $status = 'normal';
        }
      }
    } else {
      $status = 'done';
    }
    return View::make('user.change_password', array(
        "status" => $status,
    ));
  }
  
  public function showUserList() {
    if (!$this->user->isLeader()) {
      return Helper::forbiddenResponse();
    }
    $users = User::orderBy('last_visit', 'desc')->get();
    return View::make('user.userList', array(
        'users' => $users,
        'can_delete' => ($this->user->can(Privilege::$DELETE_USERS)),
    ));
  }
  
  public function deleteUser($user_id) {
    if (!$this->user->can(Privilege::$DELETE_USERS)) {
      return Helper::forbiddenResponse();
    }
    $user = User::find($user_id);
    if ($user) {
      try {
        $user->delete();
        return Redirect::route('user_list')->with("success_message", "L'utilisateur " . $user->username . " a été supprimé du site.");
      } catch (Exception $e) {
      }
    }
    return Redirect::route('user_list')->with('error_message', "Une erreur est survenue. L'utilisateur n'a pas été supprimé");
  }
  
}
