@extends('Shared.Layouts.BlankSlate')

@section('blankslate-icon-class')
    ico-ticket
@stop

@section('blankslate-title')
¡No hay ningun evento todavía!
@stop

@section('blankslate-text')
Parece que aún no has creado un evento. Puedes crear uno pulsando en el botón de abajo.
@stop

@section('blankslate-body')
<button data-invoke="modal" data-modal-id="CreateEvent" data-href="{{route('showCreateGlobalEvent', ['organiser_id' => $organiser->id])}}" href='javascript:void(0);'  class="btn btn-success mt5 btn-lg" type="button">
    <i class="ico-ticket"></i>
  Crear Evento
</button>
@stop


