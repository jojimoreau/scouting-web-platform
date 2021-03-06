@extends('base')
<?php
/**
 * Belgian Scouting Web Platform
 * Copyright (C) 2014  Julien Dupuis
 * 
 * This code is licensed under the GNU General Public License.
 * 
 * This is free software, and you are welcome to redistribute it
 * under under the terms of the GNU General Public License.
 * 
 * It is distributed without any warranty; without even the
 * implied warranty of merchantability or fitness for a particular
 * purpose. See the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 **/
?>

@section('title')
  Réinscrire votre adresse e-mail
@stop

@section('content')
  <div class="row">
    <div class="col-md-12">
      <h1>Vous n'êtes plus dans notre liste de destinataires</h1>
      <div class="alert alert-danger">
        <p>
          Souhaitez-vous remettre l'adresse <strong>{{{ $email }}}</strong> dans notre liste de destinataires&nbsp;?
        </p>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-6">
      <a class="btn btn-primary" href="{{ URL::route('confirm_unban_email', array('ban_code' => $ban_code)) }}">Confirmer</a>
    </div>
    <div class="col-xs-6 text-right">
      <a class="btn btn-default" href="{{ URL::route('home') }}">Annuler</a>
    </div>
  </div>
@stop