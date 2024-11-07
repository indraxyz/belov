"use strict";

class Tiket extends React.Component {
    state = {
        _token: document.querySelector('meta[name="csrf-token"]').content,
        url: document.querySelector('meta[name="url-admin"]').content,
        tikets: [],
        tiket: {},
        files: {},
        riwayatTiket: [],
        selectedIndex: "",

        filter: false,
        modalFilter: "",
        modalDetail: "",
        modalFiles: "",
        modalProgres: "",
        modalHapus: "",
    };

    async componentDidMount() {
        // config loading bar
        NProgress.configure({
            showSpinner: false,
        });
        this.getTikets();
    }

    filter = async (e) => {
        e.preventDefault();
        this.setState({ filter: true });

        // POST api/filter-tikets/{skip?}
        await this._fetch(
            this.state.url + `/api/filter-tikets`,
            "POST",
            new FormData(e.target)
        )
            .then(async (res) => {
                await res.json().then((data) => {
                    this.setState({ tikets: data });
                });
            })
            .catch((err) => {
                console.log(err);
            });
    };

    resetFilter = () => {
        this.formFilter.reset();
        this.modalClose(0);
        this.getTikets();
    };

    more = async (skip = 0) => {
        let { tikets, filter } = this.state;

        if (filter == false) {
            // GET api/more-tikets/{skip}
            await this._fetch(
                this.state.url + `/api/more-tikets/${skip}`,
                "get"
            )
                .then(async (res) => {
                    // console.log(res);
                    await res.json().then((data) => {
                        tikets.push(...data);
                        this.setState({ tikets });
                        console.log(tikets);
                    });
                })
                .catch((err) => {
                    console.log(err);
                });
        } else {
            // POST api/filter-tikets/{skip}
            await this._fetch(
                this.state.url + `/api/filter-tikets/${skip}`,
                "POST",
                new FormData(this.formFilter)
            )
                .then(async (res) => {
                    // console.log(res);
                    await res.json().then((data) => {
                        tikets.push(...data);
                        this.setState({ tikets });
                        console.log(tikets);
                    });
                })
                .catch((err) => {
                    console.log(err);
                });
        }
    };

    detailTiket = (i) => {
        this.setState({
            modalDetail: "is-active",
        });
        this.setState({
            tiket: this.state.tikets[i],
        });
    };

    filesTiket = async (i) => {
        this.setState({
            modalFiles: "is-active",
            tiket: this.state.tikets[i],
        });

        let tiket = this.state.tikets[i];
        let files = {};
        files.file_formulir = tiket.file_formulir;
        files.file_foto = tiket.file_foto;
        files.file_kartu_bpjs = tiket.file_kartu_bpjs;
        files.file_ktp = tiket.file_ktp;

        await this.setState({
            files,
        });
        console.log(this.state.files);
    };

    submitProgres = async (e) => {
        e.preventDefault();

        await this._fetch(
            this.state.url + `/api/verifikasi-tiket`,
            "POST",
            new FormData(this.formProgres)
        )
            .then(async (res) => {
                // console.log(res);
                await res.json().then((data) => {
                    // console.log(data);
                    let { tikets, selectedIndex } = this.state;

                    tikets[selectedIndex] = data.tiket;
                    this.setState({ modalProgres: "", tikets });
                    this.formProgres.reset();
                });
            })
            .catch((err) => {
                console.log(err);
            });
    };

    progresTiket = async (i) => {
        this.setState({
            modalProgres: "is-active",
            tiket: this.state.tikets[i],
            selectedIndex: i,
        });

        await this._fetch(
            this.state.url + `/api/riwayat-tiket/${this.state.tikets[i].id}`,
            "get"
        )
            .then(async (res) => {
                // console.log(res);
                await res.json().then((data) => {
                    this.setState({ riwayatTiket: data });
                    // tikets.push(...data);
                    // console.log(data);
                });
            })
            .catch((err) => {
                console.log(err);
            });
    };

    submitHapusTiket = async () => {
        await this._fetch(
            this.state.url + `/api/hapus-tiket/${this.state.tiket.id}`,
            "get"
        )
            .then(async (res) => {
                // console.log(res);
                await res.json().then((data) => {
                    let { tikets, selectedIndex } = this.state;

                    tikets.splice(selectedIndex, 1);
                    this.setState({ modalHapus: "", tikets });
                });
            })
            .catch((err) => {
                console.log(err);
            });
    };

    hapusTiket = (i) => {
        this.setState({
            modalHapus: "is-active",
            tiket: this.state.tikets[i],
            selectedIndex: i,
        });
        console.log(" hapus ?");
    };

    modalClose = (aksi = 0) => {
        console.log(`close modal ${aksi}`);
        switch (aksi) {
            case 0:
                // modal filter
                this.setState({ modalFilter: "" });
                this.inputCari.focus();
                break;
            case 1:
                // modal detail
                this.setState({ modalDetail: "" });
                break;
            case 2:
                // modal files
                this.setState({ modalFiles: "" });
                break;
            case 3:
                // modal progres
                this.setState({ modalProgres: "" });
                this.formProgres.reset();
                break;
            case 4:
                // modal hapus
                this.setState({ modalHapus: "" });
        }
    };

    getTikets = async () => {
        await this._fetch(this.state.url + "/api/get-tikets", "get")
            .then(async (res) => {
                // NProgress.done();
                // console.log(res);
                await res.json().then((data) => {
                    console.log(data);
                    this.setState({ tikets: data });
                    // console.log(this.state.tikets);
                });
            })
            .catch((err) => {
                console.log(err);
            });
    };

    _fetch = async (url = "", method = "", data = "") => {
        NProgress.start();
        NProgress.inc();
        let res =
            method == "POST"
                ? await fetch(url, {
                      method: method, // *GET, POST, PUT, DELETE, etc.
                      cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
                      credentials: "same-origin", // include, *same-origin, omit
                      body: data, // body data type must match "Content-Type" header
                  })
                : await fetch(url, {
                      method: method, // *GET, POST, PUT, DELETE, etc.
                      cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
                      credentials: "same-origin", // include, *same-origin, omit
                  });
        NProgress.done();
        return res;
    };

    uiOptionStatusTiket = (key) => {
        let view = "";
        switch (key) {
            case 0:
                view += `<option value="1">Proses</option>`;
                break;
            case 1:
                view += `<option value="2">Ditolak</option><option value="3">Selesai</option>`;
                break;
            default:
                view += "";
        }

        return view;
    };

    uiStatusPeserta = (key) => {
        let res = "";
        switch (key) {
            case 0:
                res = "-";
                break;
            case 1:
                res = "Non Aktif";
                break;
            case 2:
                res = "Aktif";
        }
        return res;
    };

    uiPemohon = (key) => {
        let res = "";
        switch (key) {
            case 0:
                res = "-";
                break;
            case 1:
                res = "PIC/ HRD Perusahaan";
                break;
            case 2:
                res = "Tenaga Kerja yang Bersangkutan";
        }
        return res;
    };

    uiDataPerbaikan = (data = "") => {
        let view = "";
        data = data.split("-");
        data.map((value, index) => {
            switch (parseInt(value)) {
                case 1:
                    view += `<li>NIK</li>`;
                    break;
                case 2:
                    view += `<li>Nama</li>`;
                    break;
                case 3:
                    view += `<li>Tempat Tanggal Lahir</li>`;
                    break;
                case 4:
                    view += `<li>Alamat</li>`;
                    break;
                case 5:
                    view += `<li>Nama Ibu Kadung</li>`;
                    break;
                case 6:
                    view += `<li>Kartu Hilang/ Belum Diterima</li>`;
                    break;
            }
        });

        return view;
    };

    uiStatusTiket = (status = 0) => {
        let res = "";
        switch (status) {
            case 0:
                res = "Baru";
                break;
            case 1:
                res = "Proses";
                break;
            case 2:
                res = "Ditolak";
                break;
            case 3:
                res = "Selesai";
        }
        return res;
    };

    uiColorStatus = (status = 0) => {
        let res = "";
        switch (status) {
            case 0:
                res = "has-text-info";
                break;
            case 1:
                res = "has-text-warning";
                break;
            case 2:
                res = "has-text-danger";
                break;
            case 3:
                res = "has-text-success";
        }
        return res;
    };

    uiPemohon = (key = 0) => {
        let res = "";
        switch (key) {
            case 0:
                res = "-";
                break;
            case 1:
                res = "PIC/ HRD Perusahaan";
                break;
            case 2:
                res = "Tenaga Kerja yang Bersangkutan";
        }
        return res;
    };

    render() {
        let {
            modalFilter,
            modalDetail,
            modalFiles,
            modalProgres,
            tikets,
            _token,
            tiket,
            files,
            riwayatTiket,
            modalHapus,
        } = this.state;

        return (
            <div className="py-6">
                <h1 className="title">Kelola Tiket</h1>

                <div className="columns mt-6 mb-2">
                    <div className="column is-half">
                        <form
                            action="#"
                            method="post"
                            onSubmit={this.filter}
                            ref={(el) => (this.formFilter = el)}
                        >
                            <input type="hidden" name="_token" value={_token} />

                            {/* modal FILTER */}
                            <div className={`modal ${modalFilter}`}>
                                <div
                                    className="modal-background"
                                    onClick={() => this.modalClose(0)}
                                ></div>
                                <div className="modal-content modal-full-width">
                                    <div className="box">
                                        <div className="field">
                                            <label className="label">
                                                Status Kepesertaan Tenaga Kerja
                                            </label>
                                            <div className="select">
                                                <select name="status_peserta">
                                                    <option value="">-</option>
                                                    <option value="1">
                                                        Non Aktif
                                                    </option>
                                                    <option value="2">
                                                        Aktif
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div className="field">
                                            <label className="label">
                                                Status Tiket
                                            </label>
                                            <div className="select">
                                                <select name="status_tiket">
                                                    <option value="">-</option>
                                                    <option value="0">
                                                        Baru
                                                    </option>
                                                    <option value="1">
                                                        Proses
                                                    </option>
                                                    <option value="2">
                                                        Ditolak
                                                    </option>
                                                    <option value="3">
                                                        Selesai
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <button
                                            type="button"
                                            className="button is-info is-light mr-2"
                                            onClick={this.resetFilter}
                                        >
                                            <span className="">Reset</span>
                                        </button>
                                        <button
                                            type="button"
                                            className="button is-info is-light mx-2"
                                            onClick={() => this.modalClose(0)}
                                        >
                                            <span className="">OK</span>
                                        </button>
                                    </div>
                                </div>
                                <button
                                    className="modal-close is-large"
                                    aria-label="close"
                                    onClick={() => this.modalClose(0)}
                                ></button>
                            </div>

                            <div className="field has-addons">
                                <p className="control">
                                    <button
                                        className="button"
                                        type="button"
                                        onClick={() =>
                                            this.setState({
                                                modalFilter: "is-active",
                                            })
                                        }
                                    >
                                        <ion-icon
                                            name="filter-outline"
                                            size="small"
                                        ></ion-icon>
                                    </button>
                                </p>
                                <p className="control is-expanded">
                                    <input
                                        className="input"
                                        type="text"
                                        name="key"
                                        placeholder="..."
                                        ref={(el) => (this.inputCari = el)}
                                    />
                                </p>
                                <p className="control">
                                    <button type="submit" className="button">
                                        <ion-icon
                                            name="search-outline"
                                            size="small"
                                        ></ion-icon>
                                        <span className="ml-2">Cari</span>
                                    </button>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>

                <div className="mb-6">
                    <div className="table-container">
                        <table className="table is-hoverable is-fullwidth">
                            <thead>
                                <tr>
                                    <th className="has-text-centered">
                                        <ion-icon
                                            name="settings-outline"
                                            size="large"
                                        ></ion-icon>
                                    </th>
                                    <th>No.Tiket</th>
                                    <th>Status Peserta</th>
                                    <th>Perusahaan</th>
                                    <th>Nama TK</th>
                                    <th>No. Kartu</th>
                                    <th>NIK</th>
                                    <th>Tiket Dibuat</th>
                                    <th>Perbaikan</th>
                                    <th>Pemohon</th>
                                    <th>PIC/ HRD</th>
                                    <th>Tgl. Lahir</th>
                                    <th>No. Hp</th>
                                    <th>E-Mail</th>
                                    <th>Status Tiket</th>
                                    <th>Admin</th>
                                </tr>
                            </thead>

                            <tbody>
                                {/* loop TIKETS */}
                                {tikets.map((tiket, i) => (
                                    <tr>
                                        <td className="">
                                            <div className="is-flex is-align-items-center is-justify-content-center">
                                                <span
                                                    className="mx-2 button-aksi"
                                                    onClick={() =>
                                                        this.detailTiket(i)
                                                    }
                                                    title="Detail Tiket"
                                                >
                                                    <ion-icon
                                                        name="document-text-outline"
                                                        size="large"
                                                    ></ion-icon>
                                                </span>

                                                <span
                                                    className="mx-2 button-aksi"
                                                    onClick={() =>
                                                        this.filesTiket(i)
                                                    }
                                                    title="Files Tiket"
                                                >
                                                    <ion-icon
                                                        name="document-attach-outline"
                                                        size="large"
                                                    ></ion-icon>
                                                </span>

                                                {/* <span
                                                    className="mx-1 button-aksi"
                                                    onClick={() =>
                                                        this.hapusTiket(i)
                                                    }
                                                    title="Hapus Tiket"
                                                >
                                                    <ion-icon
                                                        name="close-outline"
                                                        size="large"
                                                    ></ion-icon>
                                                </span> */}

                                                <span
                                                    className={`mx-2 button-aksi ${this.uiColorStatus(
                                                        tiket.status_tiket
                                                    )}`}
                                                    onClick={() =>
                                                        this.progresTiket(i)
                                                    }
                                                    title="Progres Tiket"
                                                >
                                                    <ion-icon
                                                        name="ticket-outline"
                                                        size="large"
                                                    ></ion-icon>
                                                </span>
                                            </div>
                                        </td>
                                        <th>{tiket.no_tiket}</th>

                                        <td>
                                            <div style={{ minWidth: 100 }}>
                                                {this.uiStatusPeserta(
                                                    tiket.status_peserta
                                                )}
                                            </div>
                                        </td>
                                        <td>
                                            <div style={{ minWidth: 250 }}>
                                                {tiket.perusahaan}
                                            </div>
                                        </td>
                                        <td>
                                            <div style={{ minWidth: 200 }}>
                                                {tiket.nama_lengkap}
                                            </div>
                                        </td>
                                        <td>
                                            <div style={{ minWidth: 200 }}>
                                                {tiket.no_kartu_peserta}
                                            </div>
                                        </td>
                                        <td>
                                            <div style={{ minWidth: 200 }}>
                                                {tiket.nik}
                                            </div>
                                        </td>
                                        <td>
                                            <div style={{ minWidth: 100 }}>
                                                {tiket.created_at}
                                            </div>
                                        </td>
                                        <td className="content">
                                            <ul
                                                style={{ minWidth: 150 }}
                                                dangerouslySetInnerHTML={{
                                                    __html: this.uiDataPerbaikan(
                                                        tiket.data_perbaikan
                                                    ),
                                                }}
                                            ></ul>
                                        </td>
                                        <td>
                                            <div style={{ minWidth: 150 }}>
                                                {this.uiPemohon(tiket.pemohon)}
                                            </div>
                                        </td>
                                        <td>
                                            <div style={{ minWidth: 150 }}>
                                                {tiket.pic_hrd != null
                                                    ? tiket.pic_hrd
                                                    : "-"}
                                            </div>
                                        </td>
                                        <td>
                                            <div style={{ minWidth: 100 }}>
                                                {tiket.tgl_lahir}
                                            </div>
                                        </td>
                                        <td>{tiket.nohp}</td>
                                        <td>{tiket.email}</td>
                                        <td>
                                            <div style={{ minWidth: 100 }}>
                                                {this.uiStatusTiket(
                                                    tiket.status_tiket
                                                )}
                                            </div>
                                        </td>
                                        <td>
                                            {tiket.akun != null
                                                ? tiket.akun.nama
                                                : "-"}
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </div>

                <button
                    type="button"
                    className="button is-info is-light"
                    onClick={() => this.more(tikets.length)}
                >
                    <span className="">selanjutnya</span>
                </button>

                {/* MODAL HAPUS */}
                <div className={`modal ${modalHapus}`}>
                    <div
                        className="modal-background"
                        onClick={() => this.modalClose(4)}
                    ></div>
                    <div className="modal-content modal-full-width">
                        <div className="box">
                            <h1 className="title">Hapus Tiket</h1>

                            <p>
                                Yakin hapus Tiket{" "}
                                <strong>{tiket.no_tiket} ?</strong>
                            </p>

                            <div className="my-4">
                                <button
                                    className="button is-info is-light"
                                    onClick={() => this.modalClose(4)}
                                >
                                    <span className="">Tidak</span>
                                </button>
                                <button
                                    className="button is-info is-light mx-2"
                                    onClick={this.submitHapusTiket}
                                >
                                    <span className="">Hapus</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <button
                        className="modal-close is-large"
                        aria-label="close"
                        onClick={() => this.modalClose(4)}
                    ></button>
                </div>

                {/* MODAL DETAIL  */}
                <div className={`modal ${modalDetail}`}>
                    <div
                        className="modal-background"
                        onClick={() => this.modalClose(1)}
                    ></div>
                    <div className="modal-content modal-full-width">
                        <div className="box">
                            <h1 className="title">Detail Tiket</h1>
                            <div className="columns is-gapless is-multiline">
                                <div className="column">
                                    <div className="my-2">
                                        <h1 class="title is-5">No. Tiket</h1>
                                        <h2 class="subtitle is-6">
                                            {tiket.no_tiket === null
                                                ? "-"
                                                : tiket.no_tiket}
                                        </h2>
                                    </div>
                                    <div className="my-2">
                                        <h1 class="title is-5">
                                            Status Peserta
                                        </h1>
                                        <h2 class="subtitle is-6">
                                            {tiket.status_peserta === null
                                                ? "-"
                                                : this.uiStatusPeserta(
                                                      tiket.status_peserta
                                                  )}
                                        </h2>
                                    </div>
                                    <div className="my-2">
                                        <h1 class="title is-5">Pemohon</h1>
                                        <h2 class="subtitle is-6">
                                            {tiket.pemohon === null
                                                ? "-"
                                                : this.uiPemohon(tiket.pemohon)}
                                        </h2>
                                    </div>
                                    <div className="my-2">
                                        <h1 class="title is-5">Perusahaan</h1>
                                        <h2 class="subtitle is-6">
                                            {tiket.perusahaan === null
                                                ? "-"
                                                : tiket.perusahaan}
                                        </h2>
                                    </div>
                                    <div className="my-2">
                                        <h1 class="title is-5">PIC/ HRD</h1>
                                        <h2 class="subtitle is-6">
                                            {tiket.pic_hrd === null
                                                ? "-"
                                                : tiket.pic_hrd}
                                        </h2>
                                    </div>
                                    <div className="my-2">
                                        <h1 class="title is-5">NIK</h1>
                                        <h2 class="subtitle is-6">
                                            {tiket.nik === null
                                                ? "-"
                                                : tiket.nik}
                                        </h2>
                                    </div>
                                </div>
                                <div className="column">
                                    <div className="my-2">
                                        <h1 class="title is-5">Nama Peserta</h1>
                                        <h2 class="subtitle is-6">
                                            {tiket.nama_lengkap === null
                                                ? "-"
                                                : tiket.nama_lengkap}
                                        </h2>
                                    </div>
                                    <div className="my-2">
                                        <h1 class="title is-5">
                                            No. Kartu Peserta
                                        </h1>
                                        <h2 class="subtitle is-6">
                                            {tiket.no_kartu_peserta === null
                                                ? "-"
                                                : tiket.no_kartu_peserta}
                                        </h2>
                                    </div>
                                    <div className="my-2">
                                        <h1 class="title is-5">
                                            Tanggal Lahir
                                        </h1>
                                        <h2 class="subtitle is-6">
                                            {tiket.tgl_lahir === null
                                                ? "-"
                                                : tiket.tgl_lahir}
                                        </h2>
                                    </div>
                                    <div className="my-2">
                                        <h1 class="title is-5">No. Hp</h1>
                                        <h2 class="subtitle is-6">
                                            {tiket.nohp === null
                                                ? "-"
                                                : tiket.nohp}
                                        </h2>
                                    </div>
                                    <div className="my-2">
                                        <h1 class="title is-5">Email</h1>
                                        <h2 class="subtitle is-6">
                                            {tiket.email === null
                                                ? "-"
                                                : tiket.email}
                                        </h2>
                                    </div>
                                    <div className="my-2 content">
                                        <h1 class="title is-5">
                                            Data Perbaikan
                                        </h1>
                                        <ul
                                            className="subtitle is-6"
                                            dangerouslySetInnerHTML={{
                                                __html:
                                                    tiket.data_perbaikan ===
                                                    null
                                                        ? "-"
                                                        : this.uiDataPerbaikan(
                                                              tiket.data_perbaikan
                                                          ),
                                            }}
                                        ></ul>
                                    </div>
                                </div>
                            </div>
                            <div className="my-2">
                                <h1 class="title is-5">
                                    Status Tiket :{" "}
                                    {this.uiStatusTiket(tiket.status_tiket)}
                                </h1>
                                <h2 class="subtitle is-6">
                                    Admin by{" "}
                                    {tiket.id_admin == null
                                        ? "-"
                                        : tiket.akun.nama}
                                </h2>
                            </div>
                        </div>
                    </div>
                    <button
                        className="modal-close is-large"
                        aria-label="close"
                        onClick={() => this.modalClose(1)}
                    ></button>
                </div>

                {/* MODAL FILES */}
                <div className={`modal ${modalFiles}`}>
                    <div
                        className="modal-background"
                        onClick={() => this.modalClose(2)}
                    ></div>
                    <div className="modal-content modal-full-width">
                        <div className="box">
                            <h1 className="title">Detail Files</h1>
                            <h1 className="subtitle">{tiket.no_tiket}</h1>
                            <div className="my-2">
                                <h1 class="title is-5">KTP</h1>
                                <h1 class="subtitle is-6">
                                    <a
                                        href={`/assets/file/${files.file_ktp}`}
                                        target="_blank"
                                    >
                                        {files.file_ktp === null
                                            ? "-"
                                            : files.file_ktp}
                                    </a>
                                </h1>
                            </div>
                            <div className="my-2">
                                <h1 class="title is-5">
                                    Formulir Koreksi/ Duplikat
                                </h1>
                                <h1 class="subtitle is-6">
                                    <a
                                        href={`/assets/file/${files.file_formulir}`}
                                        target="_blank"
                                    >
                                        {files.file_formulir === null
                                            ? "-"
                                            : files.file_formulir}
                                    </a>
                                </h1>
                            </div>
                            <div className="my-2">
                                <h1 class="title is-5">
                                    Kartu Peserta BPJS Ketenagakerjaan
                                </h1>
                                <h1 class="subtitle is-6">
                                    <a
                                        href={`/assets/file/${files.file_kartu_bpjs}`}
                                        target="_blank"
                                    >
                                        {files.file_kartu_bpjs === null
                                            ? "-"
                                            : files.file_kartu_bpjs}
                                    </a>
                                </h1>
                            </div>
                            <div className="my-2">
                                <h1 class="title is-5">Foto Selfie</h1>
                                <h1 class="subtitle is-6">
                                    <a
                                        href={`/assets/file/${files.file_foto}`}
                                        target="_blank"
                                    >
                                        {files.file_foto === null
                                            ? "-"
                                            : files.file_foto}
                                    </a>
                                </h1>
                            </div>
                        </div>
                    </div>
                    <button
                        className="modal-close is-large"
                        aria-label="close"
                        onClick={() => this.modalClose(2)}
                    ></button>
                </div>

                {/* MODAL PROGRES */}
                <div className={`modal ${modalProgres}`}>
                    <div
                        className="modal-background"
                        onClick={() => this.modalClose(3)}
                    ></div>
                    <div className="modal-content modal-full-width">
                        <div className="box">
                            <h1 className="title">Progres Tiket</h1>
                            <h1 className="subtitle">{tiket.no_tiket}</h1>
                            <div className="my-2">
                                <h1 class="title is-5">Status Tiket</h1>
                                <h1 class="subtitle is-6">
                                    {this.uiStatusTiket(tiket.status_tiket)}
                                </h1>
                            </div>

                            {/* riwayat tiket */}
                            <div className="mb-4">
                                <h1 class="title is-5">Riwayat Tiket</h1>
                                <div className="subtitle">
                                    {riwayatTiket.map((riwayat, i) => (
                                        <div className="my-2">
                                            <h1 class="title is-6">
                                                {this.uiStatusTiket(
                                                    riwayat.progres
                                                )}
                                            </h1>
                                            <h1 class="subtitle is-6">
                                                {riwayat.created_at}
                                                <br />
                                                <span className="my-2">
                                                    {riwayat.progres > 1 &&
                                                        riwayat.catatan}
                                                </span>
                                            </h1>
                                        </div>
                                    ))}
                                </div>
                            </div>

                            <form
                                method="post"
                                onSubmit={this.submitProgres}
                                ref={(el) => (this.formProgres = el)}
                            >
                                <input
                                    type="hidden"
                                    name="_token"
                                    value={_token}
                                />
                                <input
                                    type="hidden"
                                    name="id"
                                    value={tiket.id}
                                />

                                <div className="field">
                                    <label className="label">
                                        Progres Selanjutnya
                                    </label>
                                    <div className="select">
                                        <select
                                            name="status_tiket"
                                            dangerouslySetInnerHTML={{
                                                __html: this.uiOptionStatusTiket(
                                                    tiket.status_tiket
                                                ),
                                            }}
                                        ></select>
                                    </div>
                                </div>
                                <div className="field">
                                    <label className="label">Catatan</label>
                                    <div className="control">
                                        <textarea
                                            className="textarea"
                                            disabled={
                                                tiket.status_tiket == 1
                                                    ? false
                                                    : true
                                            }
                                            name="catatan"
                                        >
                                            -
                                        </textarea>
                                    </div>
                                </div>
                                <button
                                    type="submit"
                                    className="button is-info is-light"
                                    onClick={this.submitProgres}
                                    disabled={
                                        (tiket.status_tiket == 2 ||
                                            tiket.status_tiket == 3) &&
                                        true
                                    }
                                >
                                    <span className="">Submit</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    <button
                        className="modal-close is-large"
                        aria-label="close"
                        onClick={() => this.modalClose(3)}
                    ></button>
                </div>
            </div>
        );
    }
}

let domContainer = document.getElementById("app");
ReactDOM.render(<Tiket />, domContainer);
