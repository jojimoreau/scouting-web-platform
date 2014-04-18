<?php

class ParameterController extends BaseController {
  
  function showEdit() {
    // Check that the user can edit the parameters
    if (!$this->user->can(Privilege::$EDIT_GLOBAL_PARAMETERS, 1)) {
      return Helper::forbiddenResponse();
    }
    $prices = array(
        '1 child' => Parameter::get(Parameter::$PRICE_1_CHILD),
        '1 leader' => Parameter::get(Parameter::$PRICE_1_LEADER),
        '2 children' => Parameter::get(Parameter::$PRICE_2_CHILDREN),
        '2 leaders' => Parameter::get(Parameter::$PRICE_2_LEADERS),
        '3 children' => Parameter::get(Parameter::$PRICE_3_CHILDREN),
        '3 leaders' => Parameter::get(Parameter::$PRICE_3_LEADERS),
    );
    return View::make('pages.parameters.editParameters', array(
        'pages' => $this->getPageList(),
        'registration_active' => Parameter::get(Parameter::$REGISTRATION_ACTIVE),
        'prices' => $prices,
        'document_categories' => explode(";", Parameter::get(Parameter::$DOCUMENT_CATEGORIES)),
        'safe_emails' => explode(";", Parameter::get(Parameter::$VERIFIED_EMAIL_SENDERS)),
    ));
  }
  
  function submitParameters() {
    // Check that the user can edit the parameters
    if (!$this->user->can(Privilege::$EDIT_GLOBAL_PARAMETERS, 1)) {
      return Helper::forbiddenResponse();
    }
    // Save new prices
    try {
      Parameter::set(Parameter::$PRICE_1_CHILD, Helper::formatCashAmount(Input::get('price_1_child')));
      Parameter::set(Parameter::$PRICE_1_LEADER, Helper::formatCashAmount(Input::get('price_1_leader')));
      Parameter::set(Parameter::$PRICE_2_CHILDREN, Helper::formatCashAmount(Input::get('price_2_children')));
      Parameter::set(Parameter::$PRICE_2_LEADERS, Helper::formatCashAmount(Input::get('price_2_leaders')));
      Parameter::set(Parameter::$PRICE_3_CHILDREN, Helper::formatCashAmount(Input::get('price_3_children')));
      Parameter::set(Parameter::$PRICE_3_LEADERS, Helper::formatCashAmount(Input::get('price_3_leaders')));
    } catch (Exception $e) {
      $error = true;
    }
    // Save active registration parameter
    $registration_active = Input::get('registration_active');
    try {
      Parameter::set(Parameter::$REGISTRATION_ACTIVE, $registration_active ? "true" : "false");
    } catch (Exception $e) {
      $error = true;
    }
    // Save the page parameters
    $pages = $this->getPageList();
    $error = false;
    foreach ($pages as $page=>$pageData) {
      $pageInput = Input::get($page);
      try {
        Parameter::set($pageData['parameter_name'], $pageInput ? "true" : "false");
      } catch (Exception $e) {
        $error = true;
      }
    }
    // Save document categories
    $documentCategoryArray = Input::get('document_categories');
    $documentCategories = "";
    foreach ($documentCategoryArray as $categoryName) {
      if ($categoryName) {
        if ($documentCategories) $documentCategories .= ";";
        $documentCategories .= str_replace(";", ",", $categoryName);
      }
    }
    try {
      Parameter::set(Parameter::$DOCUMENT_CATEGORIES, $documentCategories);
    } catch (Exception $e) {
      $error = true;
    }
    // Save the unit parameters
    try {
      Parameter::set(Parameter::$UNIT_LONG_NAME, Input::get('unit_long_name'));
      Parameter::set(Parameter::$UNIT_SHORT_NAME, Input::get('unit_short_name'));
      Parameter::set(Parameter::$UNIT_BANK_ACCOUNT, Input::get('unit_bank_account'));
    } catch (Exception $e) {
      $error = true;
    }
    // Save the logo
    $logoFile = Input::file('logo');
    try {
      if ($logoFile) {
        $filename = $logoFile->getClientOriginalName();
        $logoFile->move(storage_path() . "/" . Parameter::$LOGO_IMAGE_FOLDER, $filename);
        Parameter::set(Parameter::$LOGO_IMAGE, $filename);
      }
    } catch (Exception $e) {
      $error = true;
    }
    // Save the advanced parameters
    try {
      Parameter::set(Parameter::$WEBMASTER_EMAIL, Input::get('webmaster_email'));
      Parameter::set(Parameter::$DEFAULT_EMAIL_FROM_ADDRESS, Input::get('default_email_from_address'));
      Parameter::set(Parameter::$SMTP_HOST, Input::get('smtp_host'));
      Parameter::set(Parameter::$SMTP_PORT, Input::get('smtp_port'));
      Parameter::set(Parameter::$SMTP_USERNAME, Input::get('smtp_username'));
      Parameter::set(Parameter::$SMTP_PASSWORD, Input::get('smtp_password'));
      Parameter::set(Parameter::$SMTP_SECURITY, Input::get('smtp_security'));
    } catch (Exception $e) {
      $error = true;
    }
    // Save verified e-mail sender list
    $verifiedSendersArray = Input::get('email_safe_list');
    $verifiedSenders = "";
    foreach ($verifiedSendersArray as $verifiedSender) {
      if ($verifiedSender && strpos($verifiedSenders, ";") === false) {
        if ($verifiedSenders) $verifiedSenders .= ";";
        $verifiedSenders .= strtolower($verifiedSender);
      }
    }
    try {
      Parameter::set(Parameter::$VERIFIED_EMAIL_SENDERS, $verifiedSenders);
    } catch (Exception $e) {
      $error = true;
    }
    // Return to parameter page
    if (!$error) {
      return Redirect::route('edit_parameters')
              ->with('success_message', 'Les paramètres ont été enregistrés avec succès.');
    } else {
      return Redirect::route('edit_parameters')
              ->with('error_message', 'Une erreur est survenue. Tous les paramètres n\'ont peut-être pas été enregistrés.');
    }
  }
  
  function getPageList() {
    $pages = array(
        // Welcome
        'page_sections' => array(
            'description' => 'Afficher les pages des sections',
            'parameter_name' => Parameter::$SHOW_SECTIONS
        ),
        'page_addresses' => array(
            'description' => 'Afficher la page "adresses utiles',
            'parameter_name' => Parameter::$SHOW_ADDRESSES
        ),
        'page_contacts' => array(
            'description' => 'Afficher la page de contacts',
            'parameter_name' => Parameter::$SHOW_CONTACTS
        ),
        // General
        'page_annual_feast' => array(
            'description' => "Afficher la page d'inscription à la fête d'unité",
            'parameter_name' => Parameter::$SHOW_ANNUAL_FEAST
        ),
        'page_registration' => array(
            'description' => "Afficher la page \"inscription\"",
            'parameter_name' => Parameter::$SHOW_REGISTRATION
        ),
        'page_health_cards' => array(
            'description' => "Afficher la page \"fiches santé\"",
            'parameter_name' => Parameter::$SHOW_HEALTH_CARDS
        ),
        'page_unit_policy' => array(
            'description' => "Afficher la page \"charte d'unité\"",
            'parameter_name' => Parameter::$SHOW_UNIT_POLICY
        ),
        'page_uniforms' => array(
            'description' => "Afficher la page \"uniformes\"",
            'parameter_name' => Parameter::$SHOW_UNIFORMS
        ),
        'page_links' => array(
            'description' => "Afficher la page \"liens utiles\"",
            'parameter_name' => Parameter::$SHOW_LINKS
        ),
        // Animation
        'page_news' => array(
            'description' => "Afficher la page \"nouvelles\"",
            'parameter_name' => Parameter::$SHOW_NEWS
        ),
        'page_calendar' => array(
            'description' => 'Afficher la page "calendrier"',
            'parameter_name' => Parameter::$SHOW_CALENDAR
        ),
        'download_calendar' => array(
            'description' => 'Calendrier téléchargeable en pdf',
            'parameter_name' => Parameter::$CALENDAR_DOWNLOADABLE
        ),
        'page_documents' => array(
            'description' => 'Afficher la page "télécharger"',
            'parameter_name' => Parameter::$SHOW_DOCUMENTS
        ),
        'page_emails' => array(
            'description' => 'Afficher la page "e-mails"',
            'parameter_name' => Parameter::$SHOW_EMAILS
        ),
        'page_photos' => array(
            'description' => "Afficher la page \"photos\"",
            'parameter_name' => Parameter::$SHOW_PHOTOS
        ),
        'page_leaders' => array(
            'description' => "Afficher la page \"animateurs\"",
            'parameter_name' => Parameter::$SHOW_LEADERS
        ),
        'page_listing' => array(
            'description' => "Afficher la page \"listing\"",
            'parameter_name' => Parameter::$SHOW_LISTING
        ),
        // Your opinion
        'page_suggestions' => array(
            'description' => "Afficher la page \"suggestions\"",
            'parameter_name' => Parameter::$SHOW_SUGGESTIONS
        ),
        'page_guest_book' => array(
            'description' => "Afficher le livre d'or",
            'parameter_name' => Parameter::$SHOW_GUEST_BOOK
        ),
        // Help
        'page_help' => array(
            'description' => "Afficher la page \"aide\"",
            'parameter_name' => Parameter::$SHOW_HELP
        ),
    );
    foreach ($pages as $page=>$pageData) {
      $pageData['active'] = Parameter::get($pageData['parameter_name']);
      $pages[$page] = $pageData;
    }
    return $pages;
  }
  
}