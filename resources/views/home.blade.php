@extends('layouts.app')

@section('content')
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav mr-auto">
        </ul>
        <span class="navbar-text">
            @if (Auth::check())
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Çıkış Yap</a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
            @endif
        </span>
    </div>
</nav>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-4 header">
                            <h3><small class="font-weight-bold text-muted">Bekleyen Listesi</small></h3>
                        </div>
                        <div class="col-4 classes">
                            <label class="sr-only" for="select-class">Salon Seçin</label>
                            <div class="input-group float-right">
                                <select id="select-class" class="custom-select" @change="selectClass">
                                    <option selected>Salon Seçin...</option>
                                    <option v-for="salon in classes" :value="salon.id">Salon @{{ salon.id }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-4 import">
                            @if (Auth::id() === 1)
                            <div class="input-group">
                                <div class="custom-file">
                                    <input id="file-import" type="file" class="custom-file-input" id="import-queue" ref="file" @change="importQueueList">
                                    <label class="custom-file-label" for="file-import">Yükle</label>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div v-if="currentUser" class="alert alert-success" role="alert">
                        <h5 style="margin-bottom: -3px">
                            <span class="font-weight-bold"><i class="fa fa-user-circle mr-2" aria-hidden="true"></i>SON GELEN KİŞİ @{{ currentUser }}</span>
                        </h5>
                    </div>
                    <table v-if="loading" class="table text-center">
                        <tbody>
                            <tr>
                                <td class="text-center font-weight-bold" colspan="100%">
                                    <i class="fa fa-spinner rotating" aria-hidden="true"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table v-else class="table text-center">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Ad</th>
                                <th scope="col">Durum</th>
                                <th scope="col">İşlem</th>
                            </tr>
                        </thead>
                        <tbody v-if="queueList.length > 0">
                            <tr v-for="queueUser in queueList">
                                <th scope="row">@{{ queueUser.id }}</th>
                                <td>@{{ queueUser.fullname }}</td>
                                <td class="font-weight-bold">
                                    <span class="text-warning">Bekliyor</span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-success" @click.prevent="callQueueUser(queueUser.id)" data-toggle="tooltip" data-original-title="Çağır">
                                        <strong>Çağır</strong>
                                        <span class="btn-inner--icon"><i class="fa fa-forward" aria-hidden="true"></i></span>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td class="text-center font-weight-bold" colspan="100%">
                                    Sonuç Bulunamadı!
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .header {
        flex: 63.4%!important;
        max-width: 63.4%!important;
    }

    .card-body {
        max-height: 400px;
        overflow-y: scroll;
        scrollbar-width: none; /* Also needed to disable scrollbar Firefox */
        -ms-overflow-style: none;  /* Disable scrollbar IE 10+ */
    }

    .card-body::-webkit-scrollbar {
        display: none;
        width: 0px;
        background: transparent; /* Disable scrollbar Chrome/Safari/Webkit */
    }

    @media (max-width: 578px) {
        .header {
            flex: 60%!important;
            max-width: 60%!important;
        }

        .classes {
            margin-left: -20px;
            flex: 45%!important;
            max-width: 45%!important;
            float: left;
        }

        .import {
            margin-top: 10px;
            max-width: 100%!important;
            flex: 100%!important;
        }
    }

    @media (min-width: 768px) {
        .header {
            flex: 60%!important;
            max-width: 60%!important;
        }

        .classes {
            flex: 40%!important;
            max-width: 40%!important;
        }

        .import {
            margin-top: 10px;
            max-width: 100%!important;
            flex: 100%!important;
        }
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script>
    const app = new Vue({
        el: '#app',
        data: {
            queueList: {},
            classes: {},
            queueUserId: '',
            currentUser: '',
            classId: '',
            status: '',
            message: '',
            loading: true
        },
        mounted: function () {
            this.getCLasses();
        },
        methods: {
            getCLasses: function () {
                axios.get('/admin/get-classes')
                .then((response) => {
                    this.classes = response.data
                })
                .catch((error) => {
                    console.log(error)
                })
                .finally(() => {
                    this.loading = false
                })
            },
            selectClass: function (event) {
                this.classId = event.target.value
                this.loading = true

                axios.post('/admin/get-selected-class', {
                    classId: this.classId
                })
                .then((response) => {
                    this.queueList = response.data.queueList
                    this.currentUser = response.data.currentUser
                })
                .catch((error) => {
                    console.log(error)
                })
                .finally(() => {
                    this.loading = false
                })
            },
            getQueueList: function () {
                axios.post('/admin/queue-list', {
                    classId: this.classId
                })
                .then((response) => {
                    this.queueList = response.data.queueList
                    this.currentUser = response.data.currentUser
                })
                .catch((error) => {
                    console.log(error);
                })
            },
            callQueueUser: function (queueUserId) {
                axios.post('/admin/call-queue-user', {
                    queueUserId: queueUserId,
                    classId: this.classId
                })
                .then((response) => {
                    this.currentUser = response.data.fullname
                    this.getQueueList();
                })
                .catch((error) => {
                    console.log(error);
                })
                .finally(() => {
                    this.notification('success', 'Seçtiğiniz Kişi Çağrıldı')
                })
            },
            importQueueList: function () {
                let formData = new FormData();
                formData.append('file', this.$refs.file.files[0]);

                axios.post('/admin/import-queue', formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
                ).then((response) => {
                    this.status = response.data.status
                    this.message = response.data.message
                })
                .catch((e) => {
                    console.log(e);
                })
                .finally(() => {
                    this.getQueueList()
                    this.getCLasses()
                    this.notification(this.status, this.message)

                    $('file-import').trigger('change').val(null);
                });
            },
            notification: function (status, message) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    onOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                })

                Toast.fire({
                    icon: status,
                    title: message
                })
            }
        }
    })
</script>
@endsection
