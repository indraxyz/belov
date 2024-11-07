@extends('admin.layout')

@section('style')
    {{-- link reactjs --}}
@endsection

@section('title')
    Home
@endsection

@section('content')
<div class="underdesk-padding">
  <div class="py-6">
    <h1 class="title my-6">
      Hai {{session('admin')->nama}}, selesaikan progres tiket yuk 
    </h1>

    {{-- notif --}}
    @if ($message = session('false'))
        <div class="notification is-danger is-light">
            <span>Terjadi kesalahan, silahkan coba kembali.</span>
        </div>
    @elseif ($message = session('true'))
        <div class="notification is-info is-light">
            <span>{{ $message }}</span>
        </div>
    @endif

    <div class="is-flex is-justify-content-end is-align-items-center">
      <p class="mx-4"><span>{{$awal->format('d M Y')}}</span> - <span>{{$akhir->format('d M Y')}}</span></p>
      <div class="buttons">
        
        <button class="button is-rounded is-white" id="buttonFilter">
          <ion-icon name="filter-outline" size='large' ></ion-icon>
        </button>
      </div>
    </div>
    <div class="columns my-4">
      <div class="column">
        <div class="box">
          <div class="is-flex is-align-items-center is-justify-content-center">
            <ion-icon name="archive-outline" size='large'></ion-icon> 
            <span class="is-size-3 ml-3">Baru</span>
          </div>
          <div class="has-text-centered">
            <span class="is-size-1">
            @php
              $v = 0;
            @endphp
            @foreach ($datas as $data)
                @if ($data->status_tiket===0)
                  @php
                      $v=$data->jumlah_status;
                  @endphp 
                  @break                      
                @endif
            @endforeach
            {{$v}}
            </span>
          </div>
        </div>
      </div>
      <div class="column">
        <div class="box">
          <div class="is-flex is-align-items-center is-justify-content-center">
            <ion-icon name="reload-outline" size='large' ></ion-icon> 
            <span class="is-size-3 ml-3">Proses</span>
          </div>
          <div class="has-text-centered">
            <span class="is-size-1">
            @php
              $v = 0;
            @endphp
            @foreach ($datas as $data)
                @if ($data->status_tiket===1)
                  @php
                      $v=$data->jumlah_status;
                  @endphp 
                  @break                      
                @endif
            @endforeach
            {{$v}}
            </span>
          </div>
        </div>
      </div>
      <div class="column">
        <div class="box">
          <div class="is-flex is-align-items-center is-justify-content-center">
            <ion-icon name="close-outline" size='large' ></ion-icon> 
            <span class="is-size-3 ml-3">Ditolak</span>
          </div>
          <div class="has-text-centered">
            <span class="is-size-1">
            @php
              $v = 0;
            @endphp
            @foreach ($datas as $data)
                @if ($data->status_tiket===2)
                  @php
                      $v=$data->jumlah_status;
                  @endphp 
                  @break                      
                @endif
            @endforeach
            {{$v}}
            </span>
          </div>
        </div>
      </div>
      <div class="column">
        <div class="box">
          <div class="is-flex is-align-items-center is-justify-content-center">
            <ion-icon name="folder-outline" size='large' ></ion-icon> 
            <span class="is-size-3 ml-3">Selesai</span>
          </div>
          <div class="has-text-centered">
            <span class="is-size-1">
            @php
              $v = 0;
            @endphp
            @foreach ($datas as $data)
                @if ($data->status_tiket===3)
                  @php
                      $v=$data->jumlah_status;
                  @endphp 
                  @break                      
                @endif
            @endforeach
            {{$v}}
            </span>
          </div>
        </div>
      </div>
      <div class="column">
        <div class="box">
          <div class="is-flex is-align-items-center is-justify-content-center">
            <ion-icon name="file-tray-full-outline" size='large' ></ion-icon> 
            <span class="is-size-3 ml-3">Total</span>
          </div>
          <div class="has-text-centered">
            <span class="is-size-1">{{$total}}</span>
          </div>
        </div>
      </div>
    </div>
    
  </div>

  {{-- modal filter --}}
  <div class="modal " id="modalFilter">
    <div class="modal-background"></div>
    <div class="modal-content">
      
      <form action="{{ url('admin/home/filter') }}" method="post" class="box">
          <h1 class="title">Filter Data</h1>
          
          @csrf
          <div class="field">
          <label class="label">Tanggal Awal</label>
          <input class="input is-info" type="date" required name="tanggalAwal" value={{$awal}}>
          </div>
          <div class="field">
          <label class="label">Tanggal Akhir</label>
          <input class="input is-info" type="date" required name="tanggalAkhir" value={{$akhir}}>
          </div>
          <div class="field">
          <button type="submit" class="button is-info is-light">Terapkan</button>
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
      let buttonFilter = document.getElementById("buttonFilter");
      let modalFilter = document.getElementById("modalFilter");
      // let bgModal = document.getElementsByClassName("modal-background");
      // let buttonModalClose = document.getElementsByClassName("modal-close");

      // open modal
      buttonFilter.onclick = function () {
        modalFilter.classList.toggle('is-active');
      };
      // close modal
      bgModal[1].onclick = function () {
        modalFilter.classList.toggle('is-active');
      };
      buttonModalClose[1].onclick = function () {
        modalFilter.classList.toggle('is-active');
      };

    </script>
@endsection