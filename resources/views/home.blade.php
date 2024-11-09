@extends('layout')

@section('title')
Welcome, BELOV
@endsection

@section('style')
<link rel="stylesheet" href="{{ asset('assets/bulma-steps.min.css') }}">
<style>
  .home-intro {
    padding: 6em 0;
    background-color: #1f57a3;
  }

  .home-intro .title {
    color: white;
  }

  /* loading saat submit */
  .button.is-info.is-loading::after {
    border-color: transparent transparent #1f57a3 #1f57a3 !important;
  }
</style>
@endsection

@section('content')
<div class="home-intro">
  <div class="container is-max-widescreen underdesk-padding">
    <h1 class="title is-2">
      Selamat Datang <br> di Layanan
    </h1>

    <h1 class="title is-1 belov-jargon">
      BELOV
    </h1>
  </div>
</div>

{{-- segment --}}
<div class="py-6 my-6">
  <div class="container is-max-widescreen underdesk-padding">

    {{-- AKSES SAAT JAM KERJA SAJA --}}
    @if ((intval(date("H"))>=7 && intval(date("H"))<=16)&&(intval(date("w"))>=1 && intval(date("w"))<=5))
        <form action="{{ url('submit-peserta') }}" method="post" id="formPengajuan">
        @csrf

        <ul class="steps is-vertical">
          <li class="steps-segment">
            <a href="#" class="steps-marker"></a>
            <div class="steps-content">
              <p class="is-size-4 bold">Syarat dan Ketentuan</p>
              <div class="py-5">
                <div class="content">
                  <p>
                    Layanan ini merupakan media pengajuan koreksi data kepesertaan tenaga kerja sehingga mempermudah akses layanan e-channel dan proses klaim BPJS Ketenagakerjaan, dengan sebab :
                  </p>
                  <ol>
                    <li>Peserta aktif BPJS Ketenagakerjaan Sidoarjo yang terdapat kesalahan pada NIK, nama, tanggal lahir dan sebagainya.</li>
                    <li>Peserta non aktif yang mengajukan klaim di BPJS Ketenagakerjaan Sidoarjo yang terdapat kesalahan pada NIK, nama, tanggal lahir dan sebagainya.</li>
                  </ol>

                  <p>
                    Demi kelancaran proses pengajuan koreksi data bapak/ibu dapat menyiapkan dokumen sebagai berikut:
                  </p>
                  <ol>
                    <li>KTP</li>
                    <li>Kartu BPJS Ketenagakerjaan</li>
                    <li>Foto selfie terbaru (tampak depan)</li>
                    <li>Formulir diisi lengkap dengan ttd PIC/HRD dan stempel perusahaan </li>
                  </ol>

                  <p>
                    Pastikan seluruh dokumen di atas sudah lengkap untuk melanjutkan ke tahap berikutnya.
                  </p>
                </div>
                <div>
                  <label class="checkbox">
                    <input type="checkbox" id="cbSyarat">
                    Saya setuju dan telah mempersiapkan dokumen dengan lengkap.
                  </label>
                </div>
                <button type="button" class="button is-info is-light my-2" id="btSyarat" disabled>Berikutnya</button>
              </div>
            </div>
          </li>
          <li class="steps-segment">
            <a href="#" class="steps-marker"></a>
            <div class="steps-content">
              <p class="is-size-4 bold">Pengisian Data Peserta</p>

              {{-- konten --}}
              <div id="kontenPengisianData" style="display: none">
                <div class="pt-4 columns">
                  {{-- form --}}
                  {{-- form kiri --}}
                  <div class="column is-half">
                    <div class="field">
                      <label class="label">Status Kepesertaan Tenaga Kerja</label>
                      <div class="select">
                        <select name="status_peserta" required>
                          <option value="0">-</option>
                          <option value="1">Non Aktif</option>
                          <option value="2">Aktif</option>
                        </select>
                      </div>
                    </div>
                    <div class="field">
                      <label class="label">Pemohon</label>
                      <div class="select">
                        <select name="pemohon" id="inputPemohon" required>
                          <option value="0">-</option>
                          <option value="1">PIC/HRD Perusahaan</option>
                          <option value="2">Tenaga Kerja yang Bersangkutan</option>
                        </select>
                      </div>
                    </div>
                    <div class="field">
                      <label class="label">Nama PIC/HRD Perusahaan</label>
                      <div class="control">
                        <input name="pic_hrd" id="inputPicHrd" class="input" type="text" disabled>
                      </div>
                    </div>
                    <div class="field">
                      <label class="label">Nama Perusahaan</label>
                      <div class="control">
                        <input name="perusahaan" class="input" type="text" name="perusahaan" required>
                      </div>
                      <span class="help">tanpa menyebutkan CV, PT</span>
                    </div>
                    <div class="field">
                      <label class="label">Nama Lengkap</label>
                      <div class="control">
                        <input name="nama_lengkap" class="input" type="text" name="nama_lengkap" required>
                      </div>
                    </div>
                    <div class="field">
                      <label class="label">NIK KTP</label>
                      <div class="control">
                        <input name="nik" class="input" type="text" maxlength="16" name="nik" required>
                      </div>
                    </div>
                    <div class="field">
                      <label class="label">Nomor Kartu Peserta</label>
                      <div class="control">
                        <input name="no_kartu_peserta" class="input" type="text" maxlength="11" required>
                      </div>
                      <span class="help">11 digit</span>
                    </div>
                  </div>
                  {{-- form kanan --}}
                  <div class="column is-half">
                    <div class="field">
                      <label class="label">Tanggal Lahir</label>
                      <div class="control">
                        <input name="tgl_lahir" class="input" type="date" required>
                      </div>
                    </div>
                    <div class="field">
                      <label class="label">Nomor Handphone Peserta</label>
                      <div class="control">
                        <input name="nohp" class="input" type="text" maxlength="14" required>
                      </div>
                    </div>
                    <div class="field">
                      <label class="label">Email Peserta</label>
                      <div class="control">
                        <input name="email" class="input" type="email" required>
                      </div>
                    </div>
                    <div class="field">
                      <label class="label">Data yang diperbaiki</label>
                      <div class="">
                        <label class="checkbox">
                          <input name="data_perbaikan[]" type="checkbox" value="1">
                          NIK
                        </label>
                      </div>
                      <div class="">
                        <label class="checkbox">
                          <input name="data_perbaikan[]" type="checkbox" value="2">
                          Nama
                        </label>
                      </div>
                      <div class="">
                        <label class="checkbox">
                          <input name="data_perbaikan[]" type="checkbox" value="3">
                          Tempat Tanggal Lahir
                        </label>
                      </div>
                      <div class="">
                        <label class="checkbox">
                          <input name="data_perbaikan[]" type="checkbox" value="4">
                          Alamat
                        </label>
                      </div>
                      <div class="">
                        <label class="checkbox">
                          <input name="data_perbaikan[]" type="checkbox" value="5">
                          Nama Ibu Kandung
                        </label>
                      </div>
                      <div class="">
                        <label class="checkbox">
                          <input name="data_perbaikan[]" type="checkbox" value="6">
                          Kartu Hilang / Belum diterima
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
                {{-- end columns --}}
                {{-- upload file PDF/ GAMBAR --}}
                <div class="">
                  <label class="label">Upload File <span class="help">image file max 2MB</span></label>
                  <br>
                  <div class="field columns">
                    <div class="column">
                      <label class="sub-label">KTP</label>
                      <div class="control">
                        <input name="file_ktp" class="input" type="file" accept="image/jpeg" required>
                      </div>
                    </div>
                    <div class="column">
                      <label class="sub-label">Kartu Peserta BPJS Ketenagakerjaan</label>
                      <div class="control">
                        <input name="file_kartu_bpjs" class="input" type="file" accept="image/jpeg" required>
                      </div>
                      <span class="help">Jika hilang/ belum diterima dapat melampirkan surat pendukung</span>
                    </div>
                  </div>
                  <div class="columns">
                    <div class="column">
                      <label class="sub-label">Formulir Koreksi/ Duplikat</label>
                      <div class="control">
                        <input name="file_formulir" class="input" type="file" accept="image/jpeg" required>
                      </div>
                      <span class="help">Dilengkapi tanda tangan HRD dan stempel Perusahaan</span>
                    </div>
                    <div class="column">
                      <label class="sub-label">Foto Selfie</label>
                      <div class="control">
                        <input name="file_foto" class="input" type="file" accept="image/jpeg">
                      </div>
                      <span class="help">Opsional</span>
                    </div>
                  </div>
                </div>
                {{-- konfirm --}}
                <div class="block my-4">
                  <div>
                    <label class="checkbox">
                      <input type="checkbox" id="cbSubmit">
                      Saya telah mengisi data dengan benar
                    </label>
                  </div>
                  <button type="submit" class="button is-info is-light my-2" id="btSubmit" disabled>Submit Data</button>
                  <div class="notification is-light" id="notifSubmit" style="display: none">

                  </div>

                </div>
              </div>
              {{-- end konten --}}

            </div>
          </li>
          <li class="steps-segment">
            <a href="#" class="steps-marker"></a>
            <div class="steps-content bold">
              <p class="is-size-4">Review Pengajuan</p>
              <div class="content my-2" id="kontenReview" style="display: none">
                <p>Data pengajuan Anda berhasil terkirim.</p>
                <div>
                  <div>
                    <p class="title is-5 py-1">No. Tiket</p>
                    <p class="subtitle is-6" id="hasilNoTiket">Subtitle 5</p>
                  </div>
                  <div>
                    <p class="title is-5 py-1">Perusahaan</p>
                    <p class="subtitle is-6" id="hasilPerusahaan">Subtitle 5</p>
                  </div>
                  <div>
                    <p class="title is-5 py-1">No. Kartu</p>
                    <p class="subtitle is-6" id="hasilNoKartu">Subtitle 5</p>
                  </div>
                  <div>
                    <p class="title is-5 py-1">NIK</p>
                    <p class="subtitle is-6" id="hasilNik">Subtitle 5</p>
                  </div>
                  <div>
                    <p class="title is-5 py-1">Nama Peserta</p>
                    <p class="subtitle is-6" id="hasilNamaPeserta">Subtitle 5</p>
                  </div>
                  <div>
                    <p class="title is-5 py-1">Jenis Koreksi</p>
                    <ul class="subtitle is-6" id="hasilJenisKoreksi">

                    </ul>
                  </div>
                </div>
                <p class="my-4">Simpan Nomor Tiket Anda. Detail data dan progres pengajuan dapat dilihat pada <a href="{{ url('lacak') }}" id="lacakTiket" target="_blank">Halaman Lacak Tiket</a> </p>
              </div>
            </div>
          </li>
        </ul>
        </form>
        @else
        <h1 class="title has-text-link">Sistem hanya bisa diakses pada pukul 07.00 - 16.00 WIB</h1>

        <div class="">
          <p class="is-size-4 bold">Syarat dan Ketentuan</p>
          <div class="py-2">
            <div class="content">
              <p>
                Layanan ini merupakan media pengajuan koreksi data kepesertaan tenaga kerja sehingga mempermudah akses layanan e-channel dan proses klaim BPJS Ketenagakerjaan, dengan sebab :
              </p>
              <ol>
                <li>Peserta aktif BPJS Ketenagakerjaan Sidoarjo yang terdapat kesalahan pada NIK, nama, tanggal lahir dan sebagainya.</li>
                <li>Peserta non aktif yang mengajukan klaim di BPJS Ketenagakerjaan Sidoarjo yang terdapat kesalahan pada NIK, nama, tanggal lahir dan sebagainya.</li>
              </ol>

              <p>
                Demi kelancaran proses pengajuan koreksi data bapak/ibu dapat menyiapkan dokumen sebagai berikut:
              </p>
              <ol>
                <li>KTP</li>
                <li>Kartu BPJS Ketenagakerjaan</li>
                <li>Foto selfie terbaru (tampak depan)</li>
                <li>Formulir diisi lengkap dengan ttd PIC/HRD dan stempel perusahaan </li>
              </ol>

              <p>
                Pastikan seluruh dokumen di atas sudah lengkap untuk melanjutkan ke tahap berikutnya.
              </p>
            </div>

          </div>
        </div>

        @endif

        {{-- download form, lacak --}}
        <div class="container is-max-widescreen pt-6">
          <div class="my-6">
            <a class="button is-info is-light underdesk-margin-y" href="{{ url('assets/file/form_duplikat.pdf') }}" target="_blank">Download Formulir Koreksi</a>
            {{-- <a href="{{ url('lacak') }}" target="_blank" class="button is-info is-light underdesk-margin-y">Lacak Tiket</a> --}}
          </div>
        </div>
  </div>
</div>

@endsection

@section('js')
<script>
  // el
  let elCbSyarat = document.getElementById("cbSyarat");
  let elBtSyarat = document.getElementById("btSyarat");
  let elCbSubmit = document.getElementById("cbSubmit");
  let elBtSubmit = document.getElementById("btSubmit");
  let elViewForm = document.getElementById("kontenPengisianData");
  let elViewPengajuan = document.getElementById("kontenReview");
  let elFormPengajuan = document.getElementById("formPengajuan");
  let elInpPemohon = document.getElementById("inputPemohon");
  let elInpPicHrd = document.getElementById("inputPicHrd");
  let elNotifSubmit = document.getElementById("notifSubmit");
  let elHasilNoTiket = document.getElementById("hasilNoTiket");
  let elHasilPerusahaan = document.getElementById("hasilPerusahaan");
  let elHasilNoKartu = document.getElementById("hasilNoKartu");
  let elHasilNik = document.getElementById("hasilNik");
  let elHasilNamaPeserta = document.getElementById("hasilNamaPeserta");
  let elHasilJenisKoreksi = document.getElementById("hasilJenisKoreksi");
  let elLacakTiket = document.getElementById("lacakTiket");

  // syarta
  elCbSyarat.onclick = function(e) {
    elBtSyarat.disabled = !e.currentTarget.checked;

  };
  elBtSyarat.onclick = function(e) {
    elViewForm.style.display = 'block';
  };

  // pic
  elInpPemohon.onchange = function(e) {
    let el = e.currentTarget;
    (el.options[el.selectedIndex].value == 1 ? elInpPicHrd.disabled = false : elInpPicHrd.disabled = true)
    // console.log(el.selectedIndex);
  };

  // submit
  elCbSubmit.onclick = function(e) {
    elBtSubmit.disabled = !e.currentTarget.checked;
  };
  elBtSubmit.onclick = function(e) {
    e.preventDefault();
    elBtSubmit.classList.toggle('is-loading');
    elViewPengajuan.style.color = 'black';

    // send to server, delay load 2s
    setTimeout(
      async function() {

        await postData(elFormPengajuan.getAttribute('action'), elFormPengajuan)
          .then(async response => {

            console.log(response.status);
            if (response.status == 200) {
              // extract data
              let hasilSubmit = '';
              await response.json().then(function(data) {
                console.log(data);
                hasilSubmit = data;
              });

              console.log('ok');
              elViewPengajuan.style.display = 'block';
              elBtSubmit.disabled = true;
              elNotifSubmit.classList.remove('is-danger');
              elNotifSubmit.classList.add('is-info');
              elNotifSubmit.innerHTML = "<span>Data berhasil tersimpan.</span>"

              // UPDATE VIEW HASIL/REVIEW
              elHasilNoTiket.innerHTML = hasilSubmit.no_tiket;
              elHasilPerusahaan.innerHTML = hasilSubmit.perusahaan;
              elHasilNoKartu.innerHTML = hasilSubmit.no_kartu_peserta;
              elHasilNik.innerHTML = hasilSubmit.nik;
              elHasilNamaPeserta.innerHTML = hasilSubmit.nama_lengkap;
              elHasilJenisKoreksi.innerHTML = buildDataPerbaikan(hasilSubmit.data_perbaikan);
              // update link lacak tiket
              // elLacakTiket.setAttribute('href','');
              // let lacak = elLacakTiket.getAttribute('href');
              elLacakTiket.setAttribute('href', 'lacak?no_tiket=' + hasilSubmit.no_tiket);

              // reset form
              elFormPengajuan.reset();

            } else {
              elNotifSubmit.classList.remove('is-info');
              elNotifSubmit.classList.add('is-danger');
              elNotifSubmit.innerHTML = "<span>Data tidak berhasil tersimpan, periksa isian form Anda dan coba kembali.</span>"
            }
            elNotifSubmit.style.display = 'block';
            elBtSubmit.classList.toggle('is-loading');

          })
          .catch((error) => {
            console.error('Error:', error);
          });

      }, 2000);

    return false;
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



  // Post Fetch
  async function postData(url = '', data = {}) {
    // Default options are marked with *
    let res = await fetch(url, {
      method: 'POST', // *GET, POST, PUT, DELETE, etc.
      // mode: 'cors', // no-cors, *cors, same-origin
      cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
      credentials: 'same-origin', // include, *same-origin, omit
      body: new FormData(data) // body data type must match "Content-Type" header
      // headers: {
      //   'Content-Type': 'application/json'
      //   // 'Content-Type': 'application/x-www-form-urlencoded',
      // },
      // redirect: 'follow', // manual, *follow, error
      // referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
    });
    return res; // parses JSON response into native JavaScript objects
  }
</script>
@endsection