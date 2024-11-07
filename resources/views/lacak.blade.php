@extends('layout')

@section('title')
    Lacak Tiket
@endsection

@section('style')
  <link rel="stylesheet" href="{{ asset('assets/bulma-steps.min.css') }}">
    <style>
      .button.is-outlined.is-light:hover{
        border-color:#3e8ed0 !important
      }
    </style>
@endsection

@section('content')
<div class="lacak-konten underdesk-padding">
  <div class="container is-max-widescreen py-6">
    <h1 class="title">
      Lacak Tiket Pengajuan
    </h1>
    <p class="">
      Peserta dapat melakukan pelacakan proses koreksi data kepesertaan yang telah diajukan dengan cara memasukkan nomor tiket
    </p>
    <form action="{{ url('lacak') }}" method="get" class="columns my-4">
      {{-- @csrf --}}
      <div class="column is-half">
        <div class="field has-addons">
          <div class="control is-expanded">
            <input class="input is-info" name="no_tiket" type="text" placeholder="nomor tiket" 
            value=
              @isset($tiket->no_tiket)
                {{$tiket->no_tiket}}
              @endisset
            >
          </div>
          <div class="control">
            <button type="submit" class="button is-outlined is-info is-light">
              <ion-icon name="search-outline" size='small' ></ion-icon> 
              <span class="ml-2">Cari</span>
            </button>
          </div>
        </div>
      </div>
    </form>

    {{-- detail TIKET --}}
    @if (isset($tiket->no_tiket))
      <div>
        <h1 class="title is-4">Detail Tiket</h1>
        <div class="my-2">
          <p class="title is-5 py-1">No. Tiket</p>
          <p class="subtitle is-6">{{$tiket->no_tiket}}</p>
        </div>
        <div class="my-2">
          <p class="title is-5 py-1">Perusahaan</p>
          <p class="subtitle is-6">{{$tiket->perusahaan}}</p>
        </div>
        <div class="my-2">
          <p class="title is-5 py-1">No. Kartu</p>
          <p class="subtitle is-6">{{$tiket->no_kartu_peserta}}</p>
        </div>
        <div class="my-2">
          <p class="title is-5 py-1">NIK</p>
          <p class="subtitle is-6">{{$tiket->nik}}</p>
        </div>
        <div class="my-2">
          <p class="title is-5 py-1">Nama Peserta</p>
          <p class="subtitle is-6">{{$tiket->nama_lengkap}}</p>
        </div>
        <div class="my-2 content">
          <p class="title is-5 py-1">Jenis Koreksi</p>
          <ul class="subtitle is-6" id="jenisKoreksi" data-perbaikan="{{$tiket->data_perbaikan}}">

          </ul>
        </div>

      </div>

      {{-- progres tiket --}}
      <div class="my-6">
        <div class="columns">
          <div class="column">
            <div>
              <ul class="steps is-vertical">
                <li class="steps-segment">
                  <a href="#" class="steps-marker"></a>
                  <div class="steps-content">
                    <p class="is-size-4 bold">Dalam Antrian</p>
                    <div class="pt-2">
                      <div class="content pb-4">
                        <span>
                          @if (isset($progres[0]))
                              {{$progres[0]->created_at}}
                          @else
                              -
                          @endif
                        </span>
                      </div>
                    </div>
                  </div>
                </li>
                <li class="steps-segment">
                  <a href="#" class="steps-marker"></a>
                  <div class="steps-content">
                    <p class="is-size-4 bold">Sedang Diproses </p>
                    <div class="pt-2">
                      <div class="content pb-4">
                        <span>
                          @if (isset($progres[1]))
                              {{$progres[1]->created_at}}
                          @else
                              -
                          @endif
                        </span>
                      </div>
                    </div> 
                  </div>
                </li>
                <li class="steps-segment">
                  <a href="#" class="steps-marker"></a>
                  <div class="steps-content">
                    <p class="is-size-4 bold ">
                      @if (isset($progres[2]))
                          @if ($progres[2]->progres==2)
                              Ditolak
                          @else
                              Selesai
                          @endif
                      @else
                          Selesai
                      @endif
                    </p>
                    
                    <div class="content my-2">
                      @if (isset($progres[2]))
                          {{$progres[2]->created_at}}

                          {{-- catatan --}}
                          <div class="py-4">
                            <p>
                              {{$progres[2]->catatan}}
                            </p> 

                            <span class="is-size-6">Best regards,</span><br>
                            <span class="is-size-5 has-text-weight-semibold	">{{$tiket->akun->nama}}</span> 
                          </div>
                      @else
                          -
                      @endif
                    </div>

                  </div>
                </li>
              </ul>
            </div>
          </div>
          <div class="column">
            <figure class="underdesk-flex-center is-flex is-justify-content-center">
              <img class="underdesk-medium-width"  src="{{ asset('assets/img/ilustrasi-lacak.png') }}">
            </figure>   
          </div>
        </div>
      </div>
    @else
        --- --- ---
    @endif

  </div>
</div>
      
@endsection

@section('js')
    <script>
      let elJenisKoreksi = document.getElementById("jenisKoreksi");
      let dataPerbaikan = '';

      // jika tidak null
      if (elJenisKoreksi) {
        dataPerbaikan = elJenisKoreksi.dataset.perbaikan;
        // update view jenis koreksi
        elJenisKoreksi.innerHTML = buildDataPerbaikan(dataPerbaikan);
      }

      // build view data perbaikan
      function buildDataPerbaikan(data) {
        let view = '';
        data = data.split("-");
        data.map((value, index) => {
          
          switch (parseInt(value)) {
            case 1:
              view += '<li>NIK</li>';
              break;
            case 2:
              view += '<li>Nama</li>';
              break;
            case 3:
              view += '<li>Tempat Tanggal Lahir</li>';
              break;
            case 4:
              view += '<li>Alamat</li>';
              break;
            case 5:
              view += '<li>Nama Ibu Kadung</li>';
              break;
            case 6:
              view += '<li>Kartu Hilang/ Belum Diterima</li>';
              break;
          }

        });

        return view;
      }



    </script>
@endsection