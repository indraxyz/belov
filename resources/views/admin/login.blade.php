<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>Login Admin</title>

    <link rel="stylesheet" href="{{ asset('assets/bulma.min.css') }}">
    <link rel="stylesheet" href="{{ mix('assets/app.css') }}">
    <style>
        .kontainer{
            padding: 7rem 0;
        }
        .cont-form{
            margin-top: 5rem;
            min-height: 35vh;
        }
        .footer {
            /* padding: 3em 0; */
            background-color: #1d4d99;
            color: white;
        }
    </style>

</head>
<body>
    <div class="container kontainer ">
        <div class="columns is-flex is-justify-content-center underdesk-padding">
            <div class="column is-half ">
                <div>
                    <h1 class="title is-1 belov-login">
                        BELOV
                    </h1>
                    <p class="subtitle">
                        Admin Panel
                    </p>
                </div>
            
                <div class="cont-form">
                    {{-- notif --}}
                    @if ($message = session('false'))
                        <div class="notification is-danger is-light">
                            <span>{{ $message }}</span>
                        </div>
                    @elseif ($message = session('logout'))
                        <div class="notification is-info is-light">
                            <span>{{ $message }}</span>
                        </div>
                    @endif
            
                    <form action="{{ url('admin/login') }}" method="post">
                        @csrf
                        <div class="field">
                        <label class="label">Username</label>
                        <input class="input is-info" type="text" required name="username">
                        </div>
                        <div class="field">
                        <label class="label">Password</label>
                        <input class="input is-info" type="password" required name="password">
                        </div>
                        <div class="field">
                        <button type="submit" class="button is-info is-light">Login</button>
                        </div>
                        
                    </form>

                </div>
            
                
            </div>
        </div>
    
    </div>
    {{-- footer --}}
    <div class="footer">
        <footer class="container is-max-widescreen">
            <div class="columns is-desktop">
                <div class="column is-two-fifths is-flex is-justify-content-center is-align-items-center underdesk-full-width">
                    <div class="underdesk-flex-center">
                        <img class="underdesk-medium-width" src="{{ asset('assets/img/ilustrasi-footer.png') }}">
                    </div>
                </div>
                <div class="column pt-6">
                    
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
                                <p class="title is-5 py-1 has-text-white">BPJS Ketenagakerjaan - Kator Cabang Sidoarjo</p>
                                <p class="subtitle is-6 has-text-white soft-text">Jl. Pahlawan Ruko Taman Pinang Indah Blok A2 No.1-4, Kel.Lemahputro, Kec.Sidoarjo, Kab.Sidoarjo (031)8945592 - 4 Ext 201 s/d 214, 303, 445</p>
                            </div>
                        </div>
                        
                </div>
            </div>
            <div class="is-flex is-justify-content-center has-text-centered">
                <span class="soft-text is-size-7">2021 &copy; BPJS Ketenaga Kerjaan Sidoarjo</span>
            </div>
            
        </footer>
    </div>

    {{-- js --}}
    <script type="module" src="{{ asset('assets/ionicons/ionicons.esm.js') }}"></script>
    <script src="{{ asset('assets/ionicons/ionicons.js') }} "></script>

</body>
</html>