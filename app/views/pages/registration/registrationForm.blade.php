@extends('base')

@section('title')
  Formulaire d'inscription
@stop

@section('additional_javascript')
  <script src="{{ URL::to('/') }}/js/registration_form.js"></script>
@stop

@section('content')
  
  {{-- Link to management --}}
  @if ($can_manage)
    <div class="row">
      <div class='pull-right management'>
        <p>
          <a class='button' href='{{ URL::route('manage_registration') }}'>
            Gérer les inscriptions
          </a>
        </p>
      </div>
    </div>
  @endif
  
  <div class="row">
    <div class="col-lg-12">
      <h1>Formulaire d'inscription</h1>
      @if (Session::has('success_message'))
        <p class='alert alert-success'>{{ Session::get('success_message'); }}</p>
      @endif
      <p>
        Ce formulaire ne fait pas office d'inscription. Avant de le remplir, il est
        indispensable de prendre contact avec l'animateur d'unité. L'inscription de
        votre enfant dans l'unité ne sera effective qu'après une confirmation de la
        part de l'animateur d'unité, et après le paiement de la cotisation.
      </p>
      <p>
        Il est inutile de remplir ce formulaire pour un membre étant déjà inscrit.
      </p>
      <h2>Remplissez le formulaire</h2>
      @if (Session::has('error_message'))
        <p class='alert alert-danger'>{{ Session::get('error_message'); }}</p>
      @endif
    </div>
  </div>
      
      
      
  <div id="registration_form" class='div-based-form'>
    {{ Form::open(array('url' => URL::route('registration_form_submit'))) }}
      <div class="row">
        <div class="col-lg-12">
          <h3>Identité de l'enfant/ado</h3>
        </div>
      </div>
      
      <div class='row'>
        <div class="col-lg-3">
          {{ Form::label('first_name', "Prénom") }} :
        </div>
        <div class="col-lg-9">
          {{ Form::text('first_name') }}
        </div>
      </div>
      
      <div class='row'>
        <div class="col-lg-3">
          {{ Form::label('last_name', "Nom") }} :
        </div>
        <div class="col-lg-9">
          {{ Form::text('last_name') }}
        </div>
      </div>
      
      <div class='row'>
        <div class="col-lg-3">
          {{ Form::label('birth_date', "Date de naissance") }} :
        </div>
        <div class="col-lg-9">
          {{ Form::text('birth_date_day', '', array('class' => 'small', 'placeholder' => 'Jour')) }} /
          {{ Form::text('birth_date_month', '', array('class' => 'small', 'placeholder' => 'Mois')) }} /
          {{ Form::text('birth_date_year', '', array('class' => 'small', 'placeholder' => 'Année')) }}
        </div>
      </div>
      
      <div class='row'>
        <div class="col-lg-3">
          {{ Form::label('gender', "Sexe") }} :
        </div>
        <div class="col-lg-9">
          {{ Form::select('gender', array('M' => 'Garçon', 'F' => 'Fille')) }}
        </div>
      </div>
      
      <div class='row'>
        <div class="col-lg-3">
          {{ Form::label('nationality', "Nationalité") }} :
        </div>
        <div class="col-lg-9">
          {{ Form::text('nationality', 'BE', array('class' => 'small')) }}
        </div>
      </div>
      
      <div class="row">
        <div class="col-lg-12">
          <h3>Adresse</h3>
        </div>
      </div>
      
      <div class='row'>
        <div class="col-lg-3">
          {{ Form::label('address', "Rue et numéro") }} :
        </div>
        <div class="col-lg-9">
          {{ Form::text('address') }}
        </div>
      </div>
      
      <div class='row'>
        <div class="col-lg-3">
          {{ Form::label('postcode', "Code postal") }} :
        </div>
        <div class="col-lg-9">
          {{ Form::text('postcode') }}
        </div>
      </div>
      
      <div class='row'>
        <div class="col-lg-3">
          {{ Form::label('city', "Localité") }} :
        </div>
        <div class="col-lg-9">
          {{ Form::text('city') }}
        </div>
      </div>
      
      <div class="row">
        <div class="col-lg-12">
          <h3>Contact</h3>
        </div>
      </div>
      
      <div class='row'>
        <div class="col-lg-3">
          {{ Form::label('phone1', "Téléphone/GSM") }} :
        </div>
        <div class="col-lg-9">
          {{ Form::text('phone1', '', array('placeholder' => "Numéro principal")) }}
          Confidentiel (*) : {{ Form::checkbox('phone1_private') }}
          Téléphone de : {{ Form::text('phone1_owner', '', array('placeholder' => "Ex: maison", 'class' => "medium")) }}
          <br />
          {{ Form::text('phone2', '', array('placeholder' => "Autre numéro")) }}
          Confidentiel (*) : {{ Form::checkbox('phone2_private') }}
          Téléphone de : {{ Form::text('phone2_owner', '', array('placeholder' => "Ex: gsm maman", 'class' => "medium")) }}
          <br />
          {{ Form::text('phone3', '', array('placeholder' => "Autre numéro")) }}
          Confidentiel (*) : {{ Form::checkbox('phone3_private') }}
          Téléphone de : {{ Form::text('phone3_owner', '', array('placeholder' => "Ex: gsm papa", 'class' => "medium")) }}
        </div>
      </div>
      
      <div class='row'>
        <div class="col-lg-3">
          {{ Form::label('phone_member', "GSM de l'enfant/ado") }} :
        </div>
        <div class="col-lg-9">
          {{ Form::text('phone_member', '', array('placeholder' => "GSM de l'enfant/ado")) }}
          Confidentiel* : {{ Form::checkbox('phone_member_private') }}
          <br />
        </div>
      </div>
      
      <div class='row'>
        <div class="col-lg-3">
          {{ Form::label('email_member', "Adresses e-mail des parents") }} :
        </div>
        <div class="col-lg-9">
          {{ Form::text('email1', '', array()) }}
          Il est recommandé de donner une adresse e-mail.
          <br />
          {{ Form::text('email2', '', array()) }}
          Les adresses e-mail resteront toujours confidentielles (*).
          <br />
          {{ Form::text('email3', '', array()) }}
        </div>
      </div>
      
      <div class='row'>
        <div class="col-lg-3">
          {{ Form::label('email_member', "Adresse e-mail de l'enfant/ado") }} :
        </div>
        <div class="col-lg-9">
          {{ Form::text('email_member', '', array('placeholder' => "")) }}
        </div>
      </div>
  
  <div class="row">
    <div class="col-lg-12">
      <p>
        (*) Confidentiel signifie que seuls les animateurs auront accès à l'information.
      </p>
    </div>
  </div>
      
      <div class='row'>
        <div class="col-lg-12">
          <h3>Choix de la section</h3>
        </div>
      </div>
      
      <div class='row'>
        <div class="col-lg-3">
          {{ Form::label('section', "Section") }} :
        </div>
        <div class="col-lg-9">
          {{ Form::select('section', Section::getSectionsForSelect(), $user->currentSection->id) }}
        </div>
      </div>
      
      <div class='row'>
        <div class="col-lg-3">
          {{ Form::label('totem', "Totem") }} :
        </div>
        <div class="col-lg-9">
          {{ Form::text('totem') }}
          Si l'ado a déjà été totémisé précédemment.
        </div>
      </div>
      
      <div class='row'>
        <div class="col-lg-3">
          {{ Form::label('quali', "Quali") }} :
        </div>
        <div class="col-lg-9">
          {{ Form::text('quali') }}
          Si l'ado a déjà été qualifié précédemment.
        </div>
      </div>
      
      <div class='row'>
        <div class="col-lg-3">
          {{ Form::label('is_leader', "Inscription d'un animateur") }} :
        </div>
        <div class="col-lg-9">
          {{ Form::checkbox('is_leader') }}
        </div>
      </div>
    
      <div class="leader_specific" style="display:none;">
        
        <div class='row'>
          <div class="col-lg-3">
            {{ Form::label('leader_name', "Nom d'animateur") }} :
          </div>
          <div class="col-lg-9">
            {{ Form::text('leader_name', '', array('placeholder' => "Nom utilisé dans sa section")) }}
          </div>
        </div>
        
        <div class='row'>
          <div class="col-lg-3">
            {{ Form::label('leader_in_charge', "Animateur responsable") }} :
          </div>
          <div class="col-lg-9">
            {{ Form::checkbox('leader_in_charge') }}
          </div>
        </div>
        
        <div class='row'>
          <div class="col-lg-3">
            {{ Form::label('leader_description', "Description de l'animateur") }} :
          </div>
          <div class="col-lg-9">
            {{ Form::textarea('leader_description', '', array('placeholder' => "Petite description qui apparaitra sur la page des animateurs")) }}
          </div>
        </div>
        
        <div class='row'>
          <div class="col-lg-3">
            {{ Form::label('leader_role', "Rôle de l'animateur") }} :
          </div>
          <div class="col-lg-9">
            {{ Form::text('leader_role', '', array('placeholder' => "Rôle particulier dans le staff")) }}
          </div>
        </div>
        
      </div>
      
      <div class='row'>
        <div class="col-lg-12">
          <h3>Remarques particulières</h3>
        </div>
      </div>
      
      <div class='row'>
        <div class="col-lg-3">
          {{ Form::label('has_handicap', "Handicap") }} :
        </div>
        <div class="col-lg-9">
          {{ Form::checkbox('has_handicap') }}
          <br />
          {{ Form::textarea('handicap_details', '', array('placeholder' => "Détails du handicap")) }}
        </div>
      </div>
      
      <div class='row'>
        <div class="col-lg-3">
          {{ Form::label('comments', "Commentaires") }} :
        </div>
        <div class="col-lg-9">
          {{ Form::textarea('comments', '', array('placeholder' => "Toute information utile à partager aux animateurs (sauf les informations médicales que vous serez amené à indiquer dans une fiche santé).")) }}
        </div>
      </div>
      
      <div class='row'>
        <div class="col-lg-3">
          {{ Form::label('family_in_other_units', "Membres de la famille dans d'autres unités :") }}
        </div>
        <div class="col-lg-9">
          {{ Form::select('family_in_other_units', Member::getFamilyOtherUnitsForSelect()) }}
          <br />
          {{ Form::textarea('family_in_other_units_details', '',
                    array('placeholder' => "Si l'enfant/ado a des frères et sœurs dans une autre unité, " .
                                           "cela peut entrainer une réduction de la cotisation. Indiquer " .
                                           "ici qui et dans quelle(s) unité(s).")) }}
        </div>
      </div>
      
      <div class='row'>
        <div class="col-lg-12">
          <h3>Terminer l'inscription</h3>
        </div>
      </div>
      
      <div class="row">
        <div class="col-lg-3">
        </div>
        <div class="col-lg-9">
          {{ Form::submit('Inscrire maintenant') }}
        </div>
      </div>
    {{ Form::close() }}
  </div>
@stop