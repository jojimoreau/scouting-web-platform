<?php if (!isset($leader_only)) $leader_only = false; ?>
<?php if (!isset($form_legend)) $form_legend = "Membre"; ?>
<?php if (!isset($edit_identity)) $edit_identity = false; ?>
<?php if (!isset($edit_contact)) $edit_contact = true; ?>
<?php if (!isset($edit_section)) $edit_section = false; ?>
<?php if (!isset($edit_totem)) $edit_totem = false; ?>
<?php if (!isset($edit_leader)) $edit_leader = false; ?>

<div id="member_form" class='well'
     @if (!Session::has('_old_input')) style="display: none;" @endif
     >
  <legend>{{ $form_legend }}</legend>
  {{ Form::open(array('files' => true, 'url' => $submit_url)) }}
    <div class="form-group">
      <div class="col-md-12">
        <div class="text-center">
          {{ Form::submit('Enregistrer', array('class' => 'btn btn-primary')) }}
          <a class='btn btn-default' href="javascript:dismissMemberForm()">Fermer</a>
        </div>
      </div>
    </div>
    {{ Form::hidden('member_id') }}
    <div class="row">
      <div class="col-md-6 form-horizontal">
        <div class="form-group">
          {{ Form::label('first_name', "Prénom", array('class' => 'control-label col-md-4')) }}
          <div class='col-md-8'>
            {{ Form::text('first_name', '', array('class' => 'form-control', ($edit_identity ? "enabled" : "disabled") )) }}
          </div>
        </div>
        <div class="form-group">
          {{ Form::label('last_name', "Nom", array('class' => 'control-label col-md-4')) }}
          <div class='col-md-8'>{{ Form::text('last_name', '', array('class' => 'form-control', ($edit_identity ? "enabled" : "disabled") )) }}</div>
        </div>
        <div class="form-group">
          {{ Form::label('birth_date', "Date de naissance", array('class' => 'control-label col-md-4')) }}
          <div class='col-md-8'>
            {{ Form::text('birth_date_day', '', array('class' => 'small form-control', 'placeholder' => 'Jour', ($edit_identity ? "enabled" : "disabled") )) }} /
            {{ Form::text('birth_date_month', '', array('class' => 'small form-control', 'placeholder' => 'Mois', ($edit_identity ? "enabled" : "disabled") )) }} /
            {{ Form::text('birth_date_year', '', array('class' => 'small form-control', 'placeholder' => 'Année', ($edit_identity ? "enabled" : "disabled") )) }}
          </div>
        </div>
        <div class="form-group">
          {{ Form::label('gender', "Sexe", array('class' => 'control-label col-md-4')) }}
          <div class='col-md-8'>{{ Form::select('gender', array('M' => 'Garçon', 'F' => 'Fille'), '', array('class' => 'form-control', ($edit_identity ? "enabled" : "disabled") )) }}</div>
        </div>
        <div class="form-group">
          {{ Form::label('nationality', "Nationalité", array('class' => 'control-label col-md-4')) }}
          <div class='col-md-8'>{{ Form::text('nationality', 'BE', array('class' => 'small form-control', ($edit_identity ? "enabled" : "disabled") )) }}</div>
        </div>
        <div class="form-group">
          {{ Form::label('address', "Rue et numéro", array('class' => 'control-label col-md-4')) }}
          <div class='col-md-8'>{{ Form::text('address', '', array('class' => 'form-control')) }}</div>
        </div>
        <div class="form-group">
          {{ Form::label('postcode', "Code postal", array('class' => 'control-label col-md-4')) }}
          <div class='col-md-8'>{{ Form::text('postcode', '', array('class' => 'form-control')) }}</div>
        </div>
        <div class="form-group">
          {{ Form::label('city', "Localité", array('class' => 'control-label col-md-4')) }}
          <div class='col-md-8'>{{ Form::text('city', '', array('class' => 'form-control')) }}</div>
        </div>
        @if (!$leader_only)
          <div class="form-group">
            {{ Form::label('phone1', "Téléphone", array('class' => 'control-label col-md-4')) }}
            <div class='col-md-8'>
              {{ Form::text('phone1', '', array('class' => 'medium form-control', ($edit_contact ? "enabled" : "disabled") )) }}
              de {{ Form::text('phone1_owner', '', array('class' => 'medium form-control', ($edit_contact ? "enabled" : "disabled") )) }}
              Confidentiel : {{ Form::checkbox('phone1_private', '1', '', array( ($edit_contact ? "enabled" : "disabled") )) }}
            </div>
          </div>
          <div class="form-group">
            <div class='col-md-8 col-md-offset-4'>
              {{ Form::text('phone2', '', array('class' => 'medium form-control', ($edit_contact ? "enabled" : "disabled") )) }}
              de {{ Form::text('phone2_owner', '', array('class' => 'medium form-control', ($edit_contact ? "enabled" : "disabled") )) }}
              Confidentiel : {{ Form::checkbox('phone2_private', '1', '', array( ($edit_contact ? "enabled" : "disabled") )) }}
            </div>
          </div>
          <div class="form-group">
            <div class='col-md-8 col-md-offset-4'>
              {{ Form::text('phone3', '', array('class' => 'medium form-control', ($edit_contact ? "enabled" : "disabled") )) }}
              de {{ Form::text('phone3_owner', '', array('class' => 'medium form-control', ($edit_contact ? "enabled" : "disabled") )) }}
              Confidentiel : {{ Form::checkbox('phone3_private', '1', '', array( ($edit_contact ? "enabled" : "disabled") )) }}
            </div>
          </div>
        @endif
        <div class="form-group">
          {{ Form::label('phone_member', ($leader_only ? "GSM" : "GSM du scout"), array('class' => 'control-label col-md-4')) }}
          <div class='col-md-8'>
            {{ Form::text('phone_member', '', array('class' => 'form-control medium', ($edit_contact ? "enabled" : "disabled") )) }}
            Confidentiel : {{ Form::checkbox('phone_member_private', '1', '', array( ($edit_contact ? "enabled" : "disabled") )) }}
          </div>
        </div>
        @if (!$leader_only)
          <div class="form-group">
            {{ Form::label('email1', "Adresse e-mail", array('class' => 'control-label col-md-4')) }}
            <div class='col-md-8'>
              {{ Form::text('email1', '', array('placeholder' => "L'adresse e-mail n'est jamais publiée", 'class' => 'form-control', ($edit_contact ? "enabled" : "disabled") )) }}
            </div>
          </div>
          <div class="form-group">
            <div class='col-md-8 col-md-offset-4'>
              {{ Form::text('email2', '', array('placeholder' => "L'adresse e-mail n'est jamais publiée", 'class' => 'form-control', ($edit_contact ? "enabled" : "disabled") )) }}
            </div>
          </div>
          <div class="form-group">
            <div class='col-md-8 col-md-offset-4'>
              {{ Form::text('email3', '', array('placeholder' => "L'adresse e-mail n'est jamais publiée", 'class' => 'form-control', ($edit_contact ? "enabled" : "disabled") )) }}
            </div>
          </div>
        @endif
        <div class="form-group">
          {{ Form::label('email_member', ($leader_only ? "Adresse e-mail" : "Adresse e-mail du scout"), array('class' => 'control-label col-md-4')) }}
          <div class='col-md-8'>{{ Form::text('email_member', '', array('placeholder' => "L'adresse e-mail n'est jamais publiée", 'class' => 'form-control', ($edit_contact ? "enabled" : "disabled") )) }}</div>
        </div>
      </div>
      <div class="col-md-6 form-horizontal">
        <div class="form-group">
          {{ Form::label('section', "Section", array('class' => 'control-label col-md-4')) }}
          <div class='col-md-8'>{{ Form::select('section', Section::getSectionsForSelect(), '', array('class' => 'form-control', ($edit_section ? "enabled" : "disabled") )) }}</div>
        </div>
        <div class="form-group">
          {{ Form::label('totem', "Totem", array('class' => 'control-label col-md-4')) }}
          <div class='col-md-8'>{{ Form::text('totem', '', array('class' => 'form-control', ($edit_totem ? "enabled" : "disabled") )) }}</div>
        </div>
        <div class="form-group">
          {{ Form::label('quali', "Quali", array('class' => 'control-label col-md-4')) }}
          <div class='col-md-8'>{{ Form::text('quali', '', array('class' => 'form-control', ($edit_totem ? "enabled" : "disabled") )) }}</div>
        </div>
        @if ($edit_leader)
          @if ($leader_only)
            {{ Form::hidden('is_leader', true) }}
          @else
            <div class="form-group">
              {{ Form::label('is_leader', "Animateur", array('class' => 'control-label col-md-4')) }}
              <div class='col-md-8'>
                <div class="checkbox">
                  {{ Form::checkbox('is_leader') }}
                </div>
              </div>
            </div>
          @endif
          <div class='form-group @if (!$leader_only) leader_specific @endif'>
            {{ Form::label('leader_name', "Nom d'animateur", array('class' => 'control-label col-md-4')) }}
            <div class='col-md-8'>{{ Form::text('leader_name', '', array('placeholder' => "Nom utilisé dans sa section", 'class' => 'form-control')) }}</div>
          </div>
          <div class='form-group @if (!$leader_only) leader_specific @endif'>
            {{ Form::label('leader_in_charge', "Animateur responsable", array('class' => 'control-label col-md-4')) }}
            <div class='col-md-8'>
              <div class="checkbox">
                {{ Form::checkbox('leader_in_charge') }}
              </div>
            </div>
          </div>
          <div class='form-group @if (!$leader_only) leader_specific @endif'>
            {{ Form::label('leader_description', "Description de l'animateur", array('class' => 'control-label col-md-4')) }}
            <div class='col-md-8'>{{ Form::textarea('leader_description', '', array('placeholder' => "Petite description qui apparaitra sur la page des animateurs", 'class' => 'form-control', 'rows' => 3)) }}</div>
          </div>
          <div class='form-group @if (!$leader_only) leader_specific @endif'>
            {{ Form::label('leader_role', "Rôle de l'animateur", array('class' => 'control-label col-md-4')) }}
            <div class='col-md-8'>{{ Form::text('leader_role', '', array('placeholder' => "Rôle particulier dans le staff", 'class' => 'form-control')) }}</div>
          </div>
          <div class='form-group @if (!$leader_only) leader_specific @endif'>
            {{ Form::label('picture', "Photo", array('class' => 'control-label col-md-4')) }}
            <div class='col-md-8'>
              {{ Form::file('picture', array('class' => 'btn btn-default')) }}
            </div>
          </div>
        @endif
        <div class="row">
          {{ Form::label('has_handicap', "Handicap", array('class' => 'control-label col-md-4')) }}
          <div class='col-md-8'>
            <div class="checkbox">
              {{ Form::checkbox('has_handicap') }}
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-8 col-md-offset-4">
            {{ Form::textarea('handicap_details', '', array('placeholder' => "Détails du handicap", 'class' => 'form-control', 'rows' => 3)) }}
          </div>
        </div>
        <div class="form-group">
          {{ Form::label('comments', "Commentaires (privés)", array('class' => 'control-label col-md-4')) }}
          <div class='col-md-8'>{{ Form::textarea('comments', '', array('placeholder' => 'Toute information utile à partager aux animateurs', 'class' => 'form-control', 'rows' => 3)) }}</div>
        </div>
        <div class="form-group">
          {{ Form::label('family_in_other_units', "Famille autres unités", array('class' => 'control-label col-md-4')) }}
          <div class='col-md-8'>
            {{ Form::select('family_in_other_units', Member::getFamilyOtherUnitsForSelect(), '', array('class' => 'form-control')) }}
            {{ Form::textarea('family_in_other_units_details', '',
                      array('placeholder' => "S'il y a des membres de la même famille dans une autre unité, " .
                                              "cela peut entrainer une réduction de la cotisation. Indiquer " .
                                              "ici qui et dans quelle(s) unité(s).", 'class' => 'form-control', 'rows' => 4)) }}
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="text-center">
          {{ Form::submit('Enregistrer', array('class' => 'btn btn-primary')) }}
          <a class='btn btn-default' href="javascript:dismissMemberForm()">Fermer</a>
        </div>
      </div>
    </div>
  {{ Form::close() }}
</div>
