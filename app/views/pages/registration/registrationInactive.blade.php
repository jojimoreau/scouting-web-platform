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
  Inscriptions
@stop

@section('forward_links')
  {{-- Link to management --}}
  @if ($can_manage)
    <p>
      <a href='{{ URL::route('manage_registration') }}'>
        Gérer les inscriptions
      </a>
    </p>
  @endif
@stop

@section('content')
  <div class="row page_body">
    <div class="col-md-12">
      <h1>{{{ $page_title }}}</h1>
      @include('subviews.flashMessages')
    </div>
  </div>
  <div class="row page_body">
    <div class="col-md-12 alert alert-warning">
      <p>
        Les inscriptions ne sont pas ouvertes pour l'instant.
      </p>
      <p>
        Pour toute question à ce propos, veuillez contacter le <a href="{{ URL::route('contacts') }}">staff d'unité</a>.
      </p>
    </div>
  </div>
@stop
