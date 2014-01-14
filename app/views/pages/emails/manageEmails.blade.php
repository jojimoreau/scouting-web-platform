@extends('base')

@section('back_links')
  <p>
    <a href="{{ URL::route('emails') }}">
      Retour aux e-mails
    </a>
  </p>
@stop

@section('forward_links')
  <p>
    <a href="{{ URL::route('send_section_email') }}">
      Envoyer un e-mail
    </a>
  </p>
@stop

@section('content')
  <div class="row">
    <div class="col-md-12">
      <h1>Gestion des e-mails {{ $user->currentSection->de_la_section }}</h1>
      @include('subviews.flashMessages')
    </div>
  </div>
  
  @foreach($emails as $email)
    <div class="row">
      <div class="col-md-12">
        <div class="well">
          <legend>
            <div class="row">
              <div class="col-md-10">
                {{ $email->subject }} – {{ Helper::dateToHuman($email->date) }}
              </div>
              <div class="col-md-2 text-right">
                <a class="btn-sm btn-default" href="#">Archiver</a>
              </div>
            </div>
          </legend>
          <p>
            {{ $email->body_html }}
          </p>
          <p>&nbsp;</p>
          <p>
            <strong>Destinataires :</strong>
            {{ $email->recipient_list }}
          </p>
        </div>
      </div>
    </div>
  @endforeach
  
@stop