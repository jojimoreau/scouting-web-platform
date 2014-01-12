@extends('base')

@section('title')
  Gestion des photos
@stop

@section('additional_javascript')
  <script src="{{ URL::to('/') }}/js/reorderList.js"></script>
  <script src="{{ URL::to('/') }}/js/editAlbum.js"></script>
  <script>
    var savePhotoOrderURL = "{{ URL::route('ajax_change_photo_order') }}";
    var deletePhotoURL = "{{ URL::route('ajax_delete_photo') }}";
    var uploadPhotoURL = "{{ URL::route('ajax_add_photo') }}";
    var currentAlbumId = "{{ $album->id }}";
  </script>
@stop

@section('back_links')
  <p>
    <a href="{{ URL::route('edit_photos') }}">Retour à la liste d'albums</a>
  </p>
@stop

@section('content')
  
  <div class="row">
    <div class="col-md-12">
      <h1>Album photos {{ $user->currentSection->de_la_section }}</h1>
      @include ('subviews.flashMessages')
    </div>
  </div>
  
  <div class="row">
    <div class="col-md-12">
      <p>
        <label>Nom de l'album :</label>
        {{ $album->name }} <span class="glyphicon glyphicon-edit"></span>
      </p>
    </div>
  </div>
  
  <div class="row">
    <div class="col-md-12">
      <table class="table table-striped table-hover draggable-table" id="photo-table">
        <tbody>
          @foreach($photos as $photo)
            <tr class="photo-row draggable-row" id="photo-{{ $photo->id }}" data-draggable-id="{{ $photo->id }}">
              <td>
                <div class="photo-thumbnail">
                  <img src='{{ $photo->getThumbnailURL() }}' />
                </div>
              </td>
              <td>
                <p>
                  Description : {{ $photo->caption }} <span class="glyphicon glyphicon-edit"></span>
                </p>
              </td>
              <td>
                <a class="btn-sm btn-default" onclick="deletePhoto(this)">
                  Supprimer
                </a>
              </td>
            </tr>
          @endforeach
          <tr style="display: none;" id="upload-row-prototype" class="photo-row">
            <td>
              <div class="photo-thumbnail">
                <img src="{{ URL::to('/') }}/images/loading.gif" />
              </div>
            </td>
            <td></td>
            <td></td>
          </tr>
          <tr style="display: none;" id="photo-row-prototype" class="photo-row">
            <td>
              <div class="photo-thumbnail">
                <img src="" />
              </div>
            </td>
            <td>
              <p>
                Description : <span class="glyphicon glyphicon-edit"></span>
              </p>
            </td>
            <td>
              <a class="btn-sm btn-default" onclick="deletePhoto(this)">
                Supprimer
              </a>
            </td>
          </tr>
          <tr>
            <td colspan="3">
              <div id='photo-drop-area'>
                <p>
                  <strong>Glisse ici</strong> des photos depuis ton ordinateur pour les ajouter (jpeg/png)
                </p>
                <p>
                  ou clique simplement ici pour les sélectionner
                </p>
              </div>
              <input type='file' style='display: none;' multiple id='file-input' onChange='picturesManuallySelected()' />
            </td>
          </tr>
        </tbody>
      </table>
      <p>Note : tu peux glisser-déplacer une photo pour changer sa position.</p>
    </div>
  </div>
    
@stop