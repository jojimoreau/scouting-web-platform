<?php

/**
 * This class is the parent of all controllers and offers some common
 * functionalities: it logs in the user and set the selected section.
 */
abstract class BaseController extends Controller {
  
  // The current user
  protected $user;
  
  // The current section
  protected $section;
  
  /**
   * Creates layout used by the controller.
   */
  protected function setupLayout() {
    if (!is_null($this->layout)) {
      $this->layout = View::make($this->layout);
    }
  }
  
  /**
   * Constructor: logs in the user and retrieves the currently selected section
   */
  public function __construct() {
    $this->user = self::getUser();
    // Retrieve section slug in route parameters
    $routeParameters = Route::current()->parameters();
    $sectionSlug = "";
    if (array_key_exists("section_slug", $routeParameters)) {
      $sectionSlug = $routeParameters['section_slug'];
    }
    // Select current tab
    $this->selectSection($sectionSlug);
  }
  
  /**
   * Tries logging in the user and returns it. Returns a dummy user if
   * the user is not logged in.
   */
  public static function getUser() {
    // Retrieve user id from session
    $userId = Session::get('user_id', null);
    // Retrieve user id from cookies
    if ($userId === null) {
      $username = Cookie::get(User::getCookieUsernameName());
      $password = Cookie::get(User::getCookiePasswordName());
      if ($username && $password) {
        $user = User::getWithUsernameAndPassword($username, $password);
        if ($user) {
          $userId = $user->id;
        }
      }
    }
    $resultUser = null;
    if ($userId) {
      // Find user
      $resultUser = User::find($userId);
      if ($resultUser) {
        if ($resultUser->last_visit < time() - 3600) {
          $resultUser->last_visit = time();
          $resultUser->save();
        }
      }
    }
    if ($resultUser == null) {
      // Load dummy user
      $resultUser = User::disconnectedUser();
    }
    View::share('user', $resultUser);
    return $resultUser;
  }
  
  /**
   * Sets the given section as selected
   * 
   * @param string/null $section_slug  The selected section's slug, or null
   */
  protected function selectSection($section_slug) {
    // Determine currently selected section
    $section = null;
    // A specific section is selected
    if ($section_slug) {
      $sections = Section::where('slug', '=', $section_slug)->get();
      if (count($sections)) {
        $section = $sections[0];
      }
    }
    // Use section from current session
    if ($section === null && Session::has('currentSection')) {
      $section = Section::find(Session::get('currentSection', '1'));
    }
    // Use default section for the user
    if ($section === null && isset($this->user->default_section)) {
      $section = Section::find($this->user->default_section);
    }
    // Use main section
    if ($section == null) {
      $section = Section::find(1);
    }
    $this->user->currentSection = $section;
    $this->section = $section;
    // Save selected section to session
    Session::put('currentSection', $section->id);
  }
  
}
