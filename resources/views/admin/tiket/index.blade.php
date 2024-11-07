@extends('admin.layout')

@section('style')
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="url-admin" content="{{ url('admin') }}">

<link rel="stylesheet" href="{{ asset('assets/nprogress.css') }}">
<style>
  #nprogress .bar {
    background:lightskyblue;
  }
  ion-icon{
    pointer-events: none;
  }
  .button-aksi:hover{
    cursor: pointer
  }
</style>
@endsection

@section('title')
    Tiket
@endsection

@section('content')
  <div class="underdesk-padding" id="app"></div>  
@endsection

@section('js')
    <script src="{{ asset('assets/react.production.min.js') }}"></script>
    <script src="{{ asset('assets/react-dom.production.min.js') }}"></script>
    <script src="{{ asset('assets/nprogress.js') }}"></script>
    <script src="{{ asset('tiket.js') }}"></script>
@endsection