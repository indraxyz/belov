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
    modalHapus: ""
  };

  async componentDidMount() {
    // config loading bar
    NProgress.configure({
      showSpinner: false
    });
    this.getTikets();
  }

  filter = async e => {
    e.preventDefault();
    this.setState({
      filter: true
    }); // POST api/filter-tikets/{skip?}

    await this._fetch(this.state.url + `/api/filter-tikets`, "POST", new FormData(e.target)).then(async res => {
      await res.json().then(data => {
        this.setState({
          tikets: data
        });
      });
    }).catch(err => {
      console.log(err);
    });
  };
  resetFilter = () => {
    this.formFilter.reset();
    this.modalClose(0);
    this.getTikets();
  };
  more = async (skip = 0) => {
    let {
      tikets,
      filter
    } = this.state;

    if (filter == false) {
      // GET api/more-tikets/{skip}
      await this._fetch(this.state.url + `/api/more-tikets/${skip}`, "get").then(async res => {
        // console.log(res);
        await res.json().then(data => {
          tikets.push(...data);
          this.setState({
            tikets
          });
          console.log(tikets);
        });
      }).catch(err => {
        console.log(err);
      });
    } else {
      // POST api/filter-tikets/{skip}
      await this._fetch(this.state.url + `/api/filter-tikets/${skip}`, "POST", new FormData(this.formFilter)).then(async res => {
        // console.log(res);
        await res.json().then(data => {
          tikets.push(...data);
          this.setState({
            tikets
          });
          console.log(tikets);
        });
      }).catch(err => {
        console.log(err);
      });
    }
  };
  detailTiket = i => {
    this.setState({
      modalDetail: "is-active"
    });
    this.setState({
      tiket: this.state.tikets[i]
    });
  };
  filesTiket = async i => {
    this.setState({
      modalFiles: "is-active",
      tiket: this.state.tikets[i]
    });
    let tiket = this.state.tikets[i];
    let files = {};
    files.file_formulir = tiket.file_formulir;
    files.file_foto = tiket.file_foto;
    files.file_kartu_bpjs = tiket.file_kartu_bpjs;
    files.file_ktp = tiket.file_ktp;
    await this.setState({
      files
    });
    console.log(this.state.files);
  };
  submitProgres = async e => {
    e.preventDefault();
    await this._fetch(this.state.url + `/api/verifikasi-tiket`, "POST", new FormData(this.formProgres)).then(async res => {
      // console.log(res);
      await res.json().then(data => {
        // console.log(data);
        let {
          tikets,
          selectedIndex
        } = this.state;
        tikets[selectedIndex] = data.tiket;
        this.setState({
          modalProgres: "",
          tikets
        });
        this.formProgres.reset();
      });
    }).catch(err => {
      console.log(err);
    });
  };
  progresTiket = async i => {
    this.setState({
      modalProgres: "is-active",
      tiket: this.state.tikets[i],
      selectedIndex: i
    });
    await this._fetch(this.state.url + `/api/riwayat-tiket/${this.state.tikets[i].id}`, "get").then(async res => {
      // console.log(res);
      await res.json().then(data => {
        this.setState({
          riwayatTiket: data
        }); // tikets.push(...data);
        // console.log(data);
      });
    }).catch(err => {
      console.log(err);
    });
  };
  submitHapusTiket = async () => {
    await this._fetch(this.state.url + `/api/hapus-tiket/${this.state.tiket.id}`, "get").then(async res => {
      // console.log(res);
      await res.json().then(data => {
        let {
          tikets,
          selectedIndex
        } = this.state;
        tikets.splice(selectedIndex, 1);
        this.setState({
          modalHapus: "",
          tikets
        });
      });
    }).catch(err => {
      console.log(err);
    });
  };
  hapusTiket = i => {
    this.setState({
      modalHapus: "is-active",
      tiket: this.state.tikets[i],
      selectedIndex: i
    });
    console.log(" hapus ?");
  };
  modalClose = (aksi = 0) => {
    console.log(`close modal ${aksi}`);

    switch (aksi) {
      case 0:
        // modal filter
        this.setState({
          modalFilter: ""
        });
        this.inputCari.focus();
        break;

      case 1:
        // modal detail
        this.setState({
          modalDetail: ""
        });
        break;

      case 2:
        // modal files
        this.setState({
          modalFiles: ""
        });
        break;

      case 3:
        // modal progres
        this.setState({
          modalProgres: ""
        });
        this.formProgres.reset();
        break;

      case 4:
        // modal hapus
        this.setState({
          modalHapus: ""
        });
    }
  };
  getTikets = async () => {
    await this._fetch(this.state.url + "/api/get-tikets", "get").then(async res => {
      // NProgress.done();
      // console.log(res);
      await res.json().then(data => {
        console.log(data);
        this.setState({
          tikets: data
        }); // console.log(this.state.tikets);
      });
    }).catch(err => {
      console.log(err);
    });
  };
  _fetch = async (url = "", method = "", data = "") => {
    NProgress.start();
    NProgress.inc();
    let res = method == "POST" ? await fetch(url, {
      method: method,
      // *GET, POST, PUT, DELETE, etc.
      cache: "no-cache",
      // *default, no-cache, reload, force-cache, only-if-cached
      credentials: "same-origin",
      // include, *same-origin, omit
      body: data // body data type must match "Content-Type" header

    }) : await fetch(url, {
      method: method,
      // *GET, POST, PUT, DELETE, etc.
      cache: "no-cache",
      // *default, no-cache, reload, force-cache, only-if-cached
      credentials: "same-origin" // include, *same-origin, omit

    });
    NProgress.done();
    return res;
  };
  uiOptionStatusTiket = key => {
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
  uiStatusPeserta = key => {
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
  uiPemohon = key => {
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
      modalHapus
    } = this.state;
    return /*#__PURE__*/React.createElement("div", {
      className: "py-6"
    }, /*#__PURE__*/React.createElement("h1", {
      className: "title"
    }, "Kelola Tiket"), /*#__PURE__*/React.createElement("div", {
      className: "columns mt-6 mb-2"
    }, /*#__PURE__*/React.createElement("div", {
      className: "column is-half"
    }, /*#__PURE__*/React.createElement("form", {
      action: "#",
      method: "post",
      onSubmit: this.filter,
      ref: el => this.formFilter = el
    }, /*#__PURE__*/React.createElement("input", {
      type: "hidden",
      name: "_token",
      value: _token
    }), /*#__PURE__*/React.createElement("div", {
      className: `modal ${modalFilter}`
    }, /*#__PURE__*/React.createElement("div", {
      className: "modal-background",
      onClick: () => this.modalClose(0)
    }), /*#__PURE__*/React.createElement("div", {
      className: "modal-content modal-full-width"
    }, /*#__PURE__*/React.createElement("div", {
      className: "box"
    }, /*#__PURE__*/React.createElement("div", {
      className: "field"
    }, /*#__PURE__*/React.createElement("label", {
      className: "label"
    }, "Status Kepesertaan Tenaga Kerja"), /*#__PURE__*/React.createElement("div", {
      className: "select"
    }, /*#__PURE__*/React.createElement("select", {
      name: "status_peserta"
    }, /*#__PURE__*/React.createElement("option", {
      value: ""
    }, "-"), /*#__PURE__*/React.createElement("option", {
      value: "1"
    }, "Non Aktif"), /*#__PURE__*/React.createElement("option", {
      value: "2"
    }, "Aktif")))), /*#__PURE__*/React.createElement("div", {
      className: "field"
    }, /*#__PURE__*/React.createElement("label", {
      className: "label"
    }, "Status Tiket"), /*#__PURE__*/React.createElement("div", {
      className: "select"
    }, /*#__PURE__*/React.createElement("select", {
      name: "status_tiket"
    }, /*#__PURE__*/React.createElement("option", {
      value: ""
    }, "-"), /*#__PURE__*/React.createElement("option", {
      value: "0"
    }, "Baru"), /*#__PURE__*/React.createElement("option", {
      value: "1"
    }, "Proses"), /*#__PURE__*/React.createElement("option", {
      value: "2"
    }, "Ditolak"), /*#__PURE__*/React.createElement("option", {
      value: "3"
    }, "Selesai")))), /*#__PURE__*/React.createElement("button", {
      type: "button",
      className: "button is-info is-light mr-2",
      onClick: this.resetFilter
    }, /*#__PURE__*/React.createElement("span", {
      className: ""
    }, "Reset")), /*#__PURE__*/React.createElement("button", {
      type: "button",
      className: "button is-info is-light mx-2",
      onClick: () => this.modalClose(0)
    }, /*#__PURE__*/React.createElement("span", {
      className: ""
    }, "OK")))), /*#__PURE__*/React.createElement("button", {
      className: "modal-close is-large",
      "aria-label": "close",
      onClick: () => this.modalClose(0)
    })), /*#__PURE__*/React.createElement("div", {
      className: "field has-addons"
    }, /*#__PURE__*/React.createElement("p", {
      className: "control"
    }, /*#__PURE__*/React.createElement("button", {
      className: "button",
      type: "button",
      onClick: () => this.setState({
        modalFilter: "is-active"
      })
    }, /*#__PURE__*/React.createElement("ion-icon", {
      name: "filter-outline",
      size: "small"
    }))), /*#__PURE__*/React.createElement("p", {
      className: "control is-expanded"
    }, /*#__PURE__*/React.createElement("input", {
      className: "input",
      type: "text",
      name: "key",
      placeholder: "...",
      ref: el => this.inputCari = el
    })), /*#__PURE__*/React.createElement("p", {
      className: "control"
    }, /*#__PURE__*/React.createElement("button", {
      type: "submit",
      className: "button"
    }, /*#__PURE__*/React.createElement("ion-icon", {
      name: "search-outline",
      size: "small"
    }), /*#__PURE__*/React.createElement("span", {
      className: "ml-2"
    }, "Cari"))))))), /*#__PURE__*/React.createElement("div", {
      className: "mb-6"
    }, /*#__PURE__*/React.createElement("div", {
      className: "table-container"
    }, /*#__PURE__*/React.createElement("table", {
      className: "table is-hoverable is-fullwidth"
    }, /*#__PURE__*/React.createElement("thead", null, /*#__PURE__*/React.createElement("tr", null, /*#__PURE__*/React.createElement("th", {
      className: "has-text-centered"
    }, /*#__PURE__*/React.createElement("ion-icon", {
      name: "settings-outline",
      size: "large"
    })), /*#__PURE__*/React.createElement("th", null, "No.Tiket"), /*#__PURE__*/React.createElement("th", null, "Status Peserta"), /*#__PURE__*/React.createElement("th", null, "Perusahaan"), /*#__PURE__*/React.createElement("th", null, "Nama TK"), /*#__PURE__*/React.createElement("th", null, "No. Kartu"), /*#__PURE__*/React.createElement("th", null, "NIK"), /*#__PURE__*/React.createElement("th", null, "Tiket Dibuat"), /*#__PURE__*/React.createElement("th", null, "Perbaikan"), /*#__PURE__*/React.createElement("th", null, "Pemohon"), /*#__PURE__*/React.createElement("th", null, "PIC/ HRD"), /*#__PURE__*/React.createElement("th", null, "Tgl. Lahir"), /*#__PURE__*/React.createElement("th", null, "No. Hp"), /*#__PURE__*/React.createElement("th", null, "E-Mail"), /*#__PURE__*/React.createElement("th", null, "Status Tiket"), /*#__PURE__*/React.createElement("th", null, "Admin"))), /*#__PURE__*/React.createElement("tbody", null, tikets.map((tiket, i) => /*#__PURE__*/React.createElement("tr", null, /*#__PURE__*/React.createElement("td", {
      className: ""
    }, /*#__PURE__*/React.createElement("div", {
      className: "is-flex is-align-items-center is-justify-content-center"
    }, /*#__PURE__*/React.createElement("span", {
      className: "mx-2 button-aksi",
      onClick: () => this.detailTiket(i),
      title: "Detail Tiket"
    }, /*#__PURE__*/React.createElement("ion-icon", {
      name: "document-text-outline",
      size: "large"
    })), /*#__PURE__*/React.createElement("span", {
      className: "mx-2 button-aksi",
      onClick: () => this.filesTiket(i),
      title: "Files Tiket"
    }, /*#__PURE__*/React.createElement("ion-icon", {
      name: "document-attach-outline",
      size: "large"
    })), /*#__PURE__*/React.createElement("span", {
      className: `mx-2 button-aksi ${this.uiColorStatus(tiket.status_tiket)}`,
      onClick: () => this.progresTiket(i),
      title: "Progres Tiket"
    }, /*#__PURE__*/React.createElement("ion-icon", {
      name: "ticket-outline",
      size: "large"
    })))), /*#__PURE__*/React.createElement("th", null, tiket.no_tiket), /*#__PURE__*/React.createElement("td", null, /*#__PURE__*/React.createElement("div", {
      style: {
        minWidth: 100
      }
    }, this.uiStatusPeserta(tiket.status_peserta))), /*#__PURE__*/React.createElement("td", null, /*#__PURE__*/React.createElement("div", {
      style: {
        minWidth: 250
      }
    }, tiket.perusahaan)), /*#__PURE__*/React.createElement("td", null, /*#__PURE__*/React.createElement("div", {
      style: {
        minWidth: 200
      }
    }, tiket.nama_lengkap)), /*#__PURE__*/React.createElement("td", null, /*#__PURE__*/React.createElement("div", {
      style: {
        minWidth: 200
      }
    }, tiket.no_kartu_peserta)), /*#__PURE__*/React.createElement("td", null, /*#__PURE__*/React.createElement("div", {
      style: {
        minWidth: 200
      }
    }, tiket.nik)), /*#__PURE__*/React.createElement("td", null, /*#__PURE__*/React.createElement("div", {
      style: {
        minWidth: 100
      }
    }, tiket.created_at)), /*#__PURE__*/React.createElement("td", {
      className: "content"
    }, /*#__PURE__*/React.createElement("ul", {
      style: {
        minWidth: 150
      },
      dangerouslySetInnerHTML: {
        __html: this.uiDataPerbaikan(tiket.data_perbaikan)
      }
    })), /*#__PURE__*/React.createElement("td", null, /*#__PURE__*/React.createElement("div", {
      style: {
        minWidth: 150
      }
    }, this.uiPemohon(tiket.pemohon))), /*#__PURE__*/React.createElement("td", null, /*#__PURE__*/React.createElement("div", {
      style: {
        minWidth: 150
      }
    }, tiket.pic_hrd != null ? tiket.pic_hrd : "-")), /*#__PURE__*/React.createElement("td", null, /*#__PURE__*/React.createElement("div", {
      style: {
        minWidth: 100
      }
    }, tiket.tgl_lahir)), /*#__PURE__*/React.createElement("td", null, tiket.nohp), /*#__PURE__*/React.createElement("td", null, tiket.email), /*#__PURE__*/React.createElement("td", null, /*#__PURE__*/React.createElement("div", {
      style: {
        minWidth: 100
      }
    }, this.uiStatusTiket(tiket.status_tiket))), /*#__PURE__*/React.createElement("td", null, tiket.akun != null ? tiket.akun.nama : "-"))))))), /*#__PURE__*/React.createElement("button", {
      type: "button",
      className: "button is-info is-light",
      onClick: () => this.more(tikets.length)
    }, /*#__PURE__*/React.createElement("span", {
      className: ""
    }, "selanjutnya")), /*#__PURE__*/React.createElement("div", {
      className: `modal ${modalHapus}`
    }, /*#__PURE__*/React.createElement("div", {
      className: "modal-background",
      onClick: () => this.modalClose(4)
    }), /*#__PURE__*/React.createElement("div", {
      className: "modal-content modal-full-width"
    }, /*#__PURE__*/React.createElement("div", {
      className: "box"
    }, /*#__PURE__*/React.createElement("h1", {
      className: "title"
    }, "Hapus Tiket"), /*#__PURE__*/React.createElement("p", null, "Yakin hapus Tiket", " ", /*#__PURE__*/React.createElement("strong", null, tiket.no_tiket, " ?")), /*#__PURE__*/React.createElement("div", {
      className: "my-4"
    }, /*#__PURE__*/React.createElement("button", {
      className: "button is-info is-light",
      onClick: () => this.modalClose(4)
    }, /*#__PURE__*/React.createElement("span", {
      className: ""
    }, "Tidak")), /*#__PURE__*/React.createElement("button", {
      className: "button is-info is-light mx-2",
      onClick: this.submitHapusTiket
    }, /*#__PURE__*/React.createElement("span", {
      className: ""
    }, "Hapus"))))), /*#__PURE__*/React.createElement("button", {
      className: "modal-close is-large",
      "aria-label": "close",
      onClick: () => this.modalClose(4)
    })), /*#__PURE__*/React.createElement("div", {
      className: `modal ${modalDetail}`
    }, /*#__PURE__*/React.createElement("div", {
      className: "modal-background",
      onClick: () => this.modalClose(1)
    }), /*#__PURE__*/React.createElement("div", {
      className: "modal-content modal-full-width"
    }, /*#__PURE__*/React.createElement("div", {
      className: "box"
    }, /*#__PURE__*/React.createElement("h1", {
      className: "title"
    }, "Detail Tiket"), /*#__PURE__*/React.createElement("div", {
      className: "columns is-gapless is-multiline"
    }, /*#__PURE__*/React.createElement("div", {
      className: "column"
    }, /*#__PURE__*/React.createElement("div", {
      className: "my-2"
    }, /*#__PURE__*/React.createElement("h1", {
      class: "title is-5"
    }, "No. Tiket"), /*#__PURE__*/React.createElement("h2", {
      class: "subtitle is-6"
    }, tiket.no_tiket === null ? "-" : tiket.no_tiket)), /*#__PURE__*/React.createElement("div", {
      className: "my-2"
    }, /*#__PURE__*/React.createElement("h1", {
      class: "title is-5"
    }, "Status Peserta"), /*#__PURE__*/React.createElement("h2", {
      class: "subtitle is-6"
    }, tiket.status_peserta === null ? "-" : this.uiStatusPeserta(tiket.status_peserta))), /*#__PURE__*/React.createElement("div", {
      className: "my-2"
    }, /*#__PURE__*/React.createElement("h1", {
      class: "title is-5"
    }, "Pemohon"), /*#__PURE__*/React.createElement("h2", {
      class: "subtitle is-6"
    }, tiket.pemohon === null ? "-" : this.uiPemohon(tiket.pemohon))), /*#__PURE__*/React.createElement("div", {
      className: "my-2"
    }, /*#__PURE__*/React.createElement("h1", {
      class: "title is-5"
    }, "Perusahaan"), /*#__PURE__*/React.createElement("h2", {
      class: "subtitle is-6"
    }, tiket.perusahaan === null ? "-" : tiket.perusahaan)), /*#__PURE__*/React.createElement("div", {
      className: "my-2"
    }, /*#__PURE__*/React.createElement("h1", {
      class: "title is-5"
    }, "PIC/ HRD"), /*#__PURE__*/React.createElement("h2", {
      class: "subtitle is-6"
    }, tiket.pic_hrd === null ? "-" : tiket.pic_hrd)), /*#__PURE__*/React.createElement("div", {
      className: "my-2"
    }, /*#__PURE__*/React.createElement("h1", {
      class: "title is-5"
    }, "NIK"), /*#__PURE__*/React.createElement("h2", {
      class: "subtitle is-6"
    }, tiket.nik === null ? "-" : tiket.nik))), /*#__PURE__*/React.createElement("div", {
      className: "column"
    }, /*#__PURE__*/React.createElement("div", {
      className: "my-2"
    }, /*#__PURE__*/React.createElement("h1", {
      class: "title is-5"
    }, "Nama Peserta"), /*#__PURE__*/React.createElement("h2", {
      class: "subtitle is-6"
    }, tiket.nama_lengkap === null ? "-" : tiket.nama_lengkap)), /*#__PURE__*/React.createElement("div", {
      className: "my-2"
    }, /*#__PURE__*/React.createElement("h1", {
      class: "title is-5"
    }, "No. Kartu Peserta"), /*#__PURE__*/React.createElement("h2", {
      class: "subtitle is-6"
    }, tiket.no_kartu_peserta === null ? "-" : tiket.no_kartu_peserta)), /*#__PURE__*/React.createElement("div", {
      className: "my-2"
    }, /*#__PURE__*/React.createElement("h1", {
      class: "title is-5"
    }, "Tanggal Lahir"), /*#__PURE__*/React.createElement("h2", {
      class: "subtitle is-6"
    }, tiket.tgl_lahir === null ? "-" : tiket.tgl_lahir)), /*#__PURE__*/React.createElement("div", {
      className: "my-2"
    }, /*#__PURE__*/React.createElement("h1", {
      class: "title is-5"
    }, "No. Hp"), /*#__PURE__*/React.createElement("h2", {
      class: "subtitle is-6"
    }, tiket.nohp === null ? "-" : tiket.nohp)), /*#__PURE__*/React.createElement("div", {
      className: "my-2"
    }, /*#__PURE__*/React.createElement("h1", {
      class: "title is-5"
    }, "Email"), /*#__PURE__*/React.createElement("h2", {
      class: "subtitle is-6"
    }, tiket.email === null ? "-" : tiket.email)), /*#__PURE__*/React.createElement("div", {
      className: "my-2 content"
    }, /*#__PURE__*/React.createElement("h1", {
      class: "title is-5"
    }, "Data Perbaikan"), /*#__PURE__*/React.createElement("ul", {
      className: "subtitle is-6",
      dangerouslySetInnerHTML: {
        __html: tiket.data_perbaikan === null ? "-" : this.uiDataPerbaikan(tiket.data_perbaikan)
      }
    })))), /*#__PURE__*/React.createElement("div", {
      className: "my-2"
    }, /*#__PURE__*/React.createElement("h1", {
      class: "title is-5"
    }, "Status Tiket :", " ", this.uiStatusTiket(tiket.status_tiket)), /*#__PURE__*/React.createElement("h2", {
      class: "subtitle is-6"
    }, "Admin by", " ", tiket.id_admin == null ? "-" : tiket.akun.nama)))), /*#__PURE__*/React.createElement("button", {
      className: "modal-close is-large",
      "aria-label": "close",
      onClick: () => this.modalClose(1)
    })), /*#__PURE__*/React.createElement("div", {
      className: `modal ${modalFiles}`
    }, /*#__PURE__*/React.createElement("div", {
      className: "modal-background",
      onClick: () => this.modalClose(2)
    }), /*#__PURE__*/React.createElement("div", {
      className: "modal-content modal-full-width"
    }, /*#__PURE__*/React.createElement("div", {
      className: "box"
    }, /*#__PURE__*/React.createElement("h1", {
      className: "title"
    }, "Detail Files"), /*#__PURE__*/React.createElement("h1", {
      className: "subtitle"
    }, tiket.no_tiket), /*#__PURE__*/React.createElement("div", {
      className: "my-2"
    }, /*#__PURE__*/React.createElement("h1", {
      class: "title is-5"
    }, "KTP"), /*#__PURE__*/React.createElement("h1", {
      class: "subtitle is-6"
    }, /*#__PURE__*/React.createElement("a", {
      href: `/assets/file/${files.file_ktp}`,
      target: "_blank"
    }, files.file_ktp === null ? "-" : files.file_ktp))), /*#__PURE__*/React.createElement("div", {
      className: "my-2"
    }, /*#__PURE__*/React.createElement("h1", {
      class: "title is-5"
    }, "Formulir Koreksi/ Duplikat"), /*#__PURE__*/React.createElement("h1", {
      class: "subtitle is-6"
    }, /*#__PURE__*/React.createElement("a", {
      href: `/assets/file/${files.file_formulir}`,
      target: "_blank"
    }, files.file_formulir === null ? "-" : files.file_formulir))), /*#__PURE__*/React.createElement("div", {
      className: "my-2"
    }, /*#__PURE__*/React.createElement("h1", {
      class: "title is-5"
    }, "Kartu Peserta BPJS Ketenagakerjaan"), /*#__PURE__*/React.createElement("h1", {
      class: "subtitle is-6"
    }, /*#__PURE__*/React.createElement("a", {
      href: `/assets/file/${files.file_kartu_bpjs}`,
      target: "_blank"
    }, files.file_kartu_bpjs === null ? "-" : files.file_kartu_bpjs))), /*#__PURE__*/React.createElement("div", {
      className: "my-2"
    }, /*#__PURE__*/React.createElement("h1", {
      class: "title is-5"
    }, "Foto Selfie"), /*#__PURE__*/React.createElement("h1", {
      class: "subtitle is-6"
    }, /*#__PURE__*/React.createElement("a", {
      href: `/assets/file/${files.file_foto}`,
      target: "_blank"
    }, files.file_foto === null ? "-" : files.file_foto))))), /*#__PURE__*/React.createElement("button", {
      className: "modal-close is-large",
      "aria-label": "close",
      onClick: () => this.modalClose(2)
    })), /*#__PURE__*/React.createElement("div", {
      className: `modal ${modalProgres}`
    }, /*#__PURE__*/React.createElement("div", {
      className: "modal-background",
      onClick: () => this.modalClose(3)
    }), /*#__PURE__*/React.createElement("div", {
      className: "modal-content modal-full-width"
    }, /*#__PURE__*/React.createElement("div", {
      className: "box"
    }, /*#__PURE__*/React.createElement("h1", {
      className: "title"
    }, "Progres Tiket"), /*#__PURE__*/React.createElement("h1", {
      className: "subtitle"
    }, tiket.no_tiket), /*#__PURE__*/React.createElement("div", {
      className: "my-2"
    }, /*#__PURE__*/React.createElement("h1", {
      class: "title is-5"
    }, "Status Tiket"), /*#__PURE__*/React.createElement("h1", {
      class: "subtitle is-6"
    }, this.uiStatusTiket(tiket.status_tiket))), /*#__PURE__*/React.createElement("div", {
      className: "mb-4"
    }, /*#__PURE__*/React.createElement("h1", {
      class: "title is-5"
    }, "Riwayat Tiket"), /*#__PURE__*/React.createElement("div", {
      className: "subtitle"
    }, riwayatTiket.map((riwayat, i) => /*#__PURE__*/React.createElement("div", {
      className: "my-2"
    }, /*#__PURE__*/React.createElement("h1", {
      class: "title is-6"
    }, this.uiStatusTiket(riwayat.progres)), /*#__PURE__*/React.createElement("h1", {
      class: "subtitle is-6"
    }, riwayat.created_at, /*#__PURE__*/React.createElement("br", null), /*#__PURE__*/React.createElement("span", {
      className: "my-2"
    }, riwayat.progres > 1 && riwayat.catatan)))))), /*#__PURE__*/React.createElement("form", {
      method: "post",
      onSubmit: this.submitProgres,
      ref: el => this.formProgres = el
    }, /*#__PURE__*/React.createElement("input", {
      type: "hidden",
      name: "_token",
      value: _token
    }), /*#__PURE__*/React.createElement("input", {
      type: "hidden",
      name: "id",
      value: tiket.id
    }), /*#__PURE__*/React.createElement("div", {
      className: "field"
    }, /*#__PURE__*/React.createElement("label", {
      className: "label"
    }, "Progres Selanjutnya"), /*#__PURE__*/React.createElement("div", {
      className: "select"
    }, /*#__PURE__*/React.createElement("select", {
      name: "status_tiket",
      dangerouslySetInnerHTML: {
        __html: this.uiOptionStatusTiket(tiket.status_tiket)
      }
    }))), /*#__PURE__*/React.createElement("div", {
      className: "field"
    }, /*#__PURE__*/React.createElement("label", {
      className: "label"
    }, "Catatan"), /*#__PURE__*/React.createElement("div", {
      className: "control"
    }, /*#__PURE__*/React.createElement("textarea", {
      className: "textarea",
      disabled: tiket.status_tiket == 1 ? false : true,
      name: "catatan"
    }, "-"))), /*#__PURE__*/React.createElement("button", {
      type: "submit",
      className: "button is-info is-light",
      onClick: this.submitProgres,
      disabled: (tiket.status_tiket == 2 || tiket.status_tiket == 3) && true
    }, /*#__PURE__*/React.createElement("span", {
      className: ""
    }, "Submit"))))), /*#__PURE__*/React.createElement("button", {
      className: "modal-close is-large",
      "aria-label": "close",
      onClick: () => this.modalClose(3)
    })));
  }

}

let domContainer = document.getElementById("app");
ReactDOM.render( /*#__PURE__*/React.createElement(Tiket, null), domContainer);