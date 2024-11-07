@extends('admin.layout')

@section('title')
    Profile
@endsection

@section('content')
  <div class="underdesk-padding">
    <div class="py-6">
      <h1 class="title">
        Profile Admin
      </h1>

      {{-- notif Update profil/ password --}}
      @if ($message = session('false'))
          <div class="notification is-danger is-light">
              <span>Terjadi kesalahan, silahkan coba kembali.</span>
          </div>
      @elseif ($message = session('true'))
          <div class="notification is-info is-light">
              <span>{{ $message }}</span>
          </div>
      @endif

      {{-- Profile Data --}}
      <div class="pt-6">
        {{-- foto --}}
        {{-- <div class="underdesk-flex-center">
          <figure class="image image-s">
            <img class="is-rounded" src="{{ asset('assets/img/thumbnail.png') }}">
          </figure>
        </div> --}}
        
        <div class="py-6">
          <p class="title is-4">Username</p>
          <p class="subtitle is-6">{{$admin->username}}</p>

          <p class="title is-4">Nama</p>
          <p class="subtitle is-6">{{$admin->nama}}</p>

          <p class="title is-4">Password</p>
          <p class="subtitle is-6"><a href="#" id="buttonEditPassword">Ubah Password ?</a></p>

          <button class="button is-info is-light" id="buttonEditProfil">
            <span>Edit</span>
          </button>
        </div>
      </div>
    </div>
    
    {{-- modal edit profil --}}
    <div class="modal " id="modalEditProfil">
      <div class="modal-background"></div>
      <div class="modal-content modal-full-width">
        
        <form action="{{ url('admin/profil/update') }}" method="post" class="box">
            <h1 class="title">Edit Profile</h1>
            
            @csrf
            <input type="hidden" name="id" value="{{$admin->id}}">

            <div class="field">
            <label class="label">Username</label>
            <input class="input is-info" type="text" required name="username" value={{$admin->username}}>
            </div>
            <div class="field">
            <label class="label">Nama</label>
            <input class="input is-info" type="text" required name="nama" value="{{$admin->nama}}">
            </div>
            <div class="field">
            <button type="submit" class="button is-info is-light">Update</button>
            </div>
        </form>

      </div>
      <button class="modal-close is-large" aria-label="close"></button>
    </div>

    {{-- modal edit password --}}
    <div class="modal " id="modalEditPassword">
      <div class="modal-background"></div>
      <div class="modal-content modal-full-width">
        
        <form action="{{ url('admin/profil/password/update') }}" method="post" class="box">
          <h1 class="title">Edit Password</h1>
          
          @csrf
          <input type="hidden" name="id" value="{{$admin->id}}">

          <div class="field">
          <label class="label">Password Lama</label>
          <input class="input is-info" type="text" required name="oldPassword">
          </div>
          <div class="field">
          <label class="label">Password Baru</label>
          <input class="input is-info" type="text" required name="password">
          </div>
          <div class="field">
          <button type="submit" class="button is-info is-light">Update</button>
          </div>
      </form>

      </div>
      <button class="modal-close is-large" aria-label="close"></button>
    </div>

  </div>
      
@endsection

@section('js')
    <script>
      // deklarasi
      let buttonEditProfil = document.getElementById("buttonEditProfil");
      let buttonEditPassword = document.getElementById("buttonEditPassword");
      let modalEditProfil = document.getElementById("modalEditProfil");
      let modalEditPassword = document.getElementById("modalEditPassword");
      // let bgModal = document.getElementsByClassName("modal-background");
      // let buttonModalClose = document.getElementsByClassName("modal-close");

      // open modal edit profil
      buttonEditProfil.onclick = function () {
        modalEditProfil.classList.toggle('is-active');
      };
      // close modal edit profil
      bgModal[1].onclick = function () {
        modalEditProfil.classList.toggle('is-active');
      };
      buttonModalClose[1].onclick = function () {
        modalEditProfil.classList.toggle('is-active');
      };

      // open modal edit password
      buttonEditPassword.onclick = function () {
        modalEditPassword.classList.toggle('is-active');
      };

      // close modal edit password
      bgModal[2].onclick = function () {
        modalEditPassword.classList.toggle('is-active');
      };
      buttonModalClose[2].onclick = function () {
        modalEditPassword.classList.toggle('is-active');
      };
    </script>
@endsection