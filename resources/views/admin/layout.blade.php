<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- meta --}}

    {{-- icon --}}

    <title>Admin - @yield('title')</title>

    {{-- style --}}
    <link rel="stylesheet" href="{{ asset('assets/bulma.min.css') }}">
    <link rel="stylesheet" href="{{ mix('assets/app.css') }}">
    @yield('style')

    <style>
        .belov{
            padding: 0 1em;
            font-weight: bold;
        }
    </style>

</head>
<body>
    
    {{-- nav --}}
    <nav class="navbar is-fixed-top" role="navigation" aria-label="main navigation">
        <div class="container is-max-widescreen">
            
            <div class="navbar-brand">
                <a class="navbar-item" href="{{ url('admin/home') }}">
                    <img class="logo" src="{{ asset('assets/img/belov.png') }}">
                    <span class="belov">BELOV</span>
                </a>

                <a id="buttonNav" role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="topMenu">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </a>
            </div>
            
            <div id="topMenu" class="navbar-menu">
                <div class="navbar-end">
                    <a class="navbar-item" href="{{ url('admin/home') }}">
                        Home
                    </a>
                    <a class="navbar-item" href="{{ url('admin/tiket') }}">
                        Tiket
                    </a>
                    <a class="navbar-item" href="{{ url('admin/profil') }}">
                        Profil
                    </a>
                    <a class="navbar-item" href="{{ url('admin/logout') }}">
                        Logout
                    </a>
                </div>
            </div>

        </div>
    </nav>
    {{-- MOBILE NAV --}}
    <div class="modal" id="modalNav">
        <div class="modal-background"></div>
        <div class="modal-content modal-full-width">
          
         <div class="box">
            <aside class="menu">
                <p class="menu-label">
                  BELOV
                </p>
                <ul class="menu-list">
                  <li><a href="{{ url('admin/home') }}">Home</a></li>
                  <li><a href="{{ url('admin/tiket') }}">Tiket</a></li>
                  <li><a href="{{ url('admin/profil') }}">Profil</a></li>
                  <li><a href="{{ url('admin/logout') }}">Logout</a></li>
                </ul>
                
              </aside>
         </div>
  
        </div>
        <button class="modal-close is-large" aria-label="close"></button>
    </div>

    {{-- content --}}
    <div class="container is-max-widescreen layout-konten">
        @yield('content')
    </div>

    {{-- footer --}}
    {{-- <div class="layout-footer">
        <footer class="container is-max-widescreen ">
            <div class="has-text-centered">
                belov
            </div>
        </footer>
    </div> --}}
    <div class="footer">
        <footer class="container is-max-widescreen">
            <div class="columns is-desktop">
                
                <div class="column pt-3">
                    <h1 class="title is-4 pb-6 has-text-white underdesk-text-center">
                        Admin Panel
                    </h1>
                    <div class="underdesk-flex-center">
                        <img class="image-footer" src="{{ asset('assets/img/belov.png') }}">
                        <img class="image-footer" src="{{ asset('assets/img/175.png') }}">
                        <img class="image-footer" src="{{ asset('assets/img/logo-bpjstk.png') }}">
                    </div>
                    <div class="underdesk-text-center">
                        <div class="my-4">
                            <p class="title is-5 py-1 has-text-white">BELOV (BPJSTK E-REPORTING LABOR VALIDITY)</p>
                            <p class="subtitle is-6 has-text-white soft-text">Sistem Pelaporan Koreksi Data Tenaga Kerja Peserta BPJAMSOSTEK Sidoarjo</p>
                        </div>
                        <div class="my-4">
                            <p class="title is-5 py-1 has-text-white">BPJS Ketenagakerjaan - Kantor Cabang Sidoarjo</p>
                            <p class="subtitle is-6 has-text-white soft-text">Jl. Pahlawan Ruko Taman Pinang Indah Blok A2 No.1-4, Kel.Lemahputro, Kec.Sidoarjo, Kab.Sidoarjo (031)8945592-4 Ext 201 s/d 214, 303, 445</p>
                        </div>
                    </div>
                        
                </div>
            </div>
            <div class="is-flex is-justify-content-center has-text-centered">
                <span class="soft-text is-size-7">2021 &copy; BPJS Ketenagakerjaan Sidoarjo</span>
            </div>
            
        </footer>
    </div>

    {{-- js --}}
    <script type="module" src="{{ asset('assets/ionicons/ionicons.esm.js') }}"></script>
    <script src="{{ asset('assets/ionicons/ionicons.js') }} "></script>

    <script>
        let buttonNav = document.getElementById("buttonNav");
        let modalNav = document.getElementById("modalNav");
        let bgModal = document.getElementsByClassName("modal-background");
        let buttonModalClose = document.getElementsByClassName("modal-close");

        // open modal NAV
        buttonNav.onclick = function () {
            modalNav.classList.toggle('is-active');
        };
        // close modal NAV
        bgModal[0].onclick = function () {
            modalNav.classList.toggle('is-active');
        };
        buttonModalClose[0].onclick = function () {
            modalNav.classList.toggle('is-active');
        };

    </script>

    @yield('js')
</body>
</html>