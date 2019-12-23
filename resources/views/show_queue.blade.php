@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3><small class="font-weight-bold text-muted">Sırası Gelenler</small></h3>
                </div>
                <div class="card-body">
                    <table v-if="loading" class="table">
                        <tbody>
                            <tr>
                                <td class="text-center font-weight-bold" colspan="100%">
                                    <i class="fa fa-spinner rotating" aria-hidden="true"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table v-else class="table">
                        <thead>
                            <tr>
                                <th scope="col">Ad</th>
                                <th scope="col">Salon</th>
                            </tr>
                        </thead>
                        <tbody v-if="queueList.length > 0">
                            <tr v-for="queueUser in queueList">
                                <td>@{{ queueUser.fullname }}</td>
                                <td class="font-weight-bold">
                                    <span class="text-success">Salon @{{ queueUser.class_id }}</span>
                                </td>
                            </tr>
                            <tr class="text-center last">
                                <th colspan="100%">Sırası Geçenler</th>
                            </tr>
                            <tr v-for="queueUser in lastQueueList">
                                <td>@{{ queueUser.fullname }}</td>
                                <td class="font-weight-bold">
                                    <span class="text-danger">Salon @{{ queueUser.class_id }}</span>
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
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script>
    const app = new Vue({
        el: '#app',
        data: {
            queueList: {},
            lastQueueList: {},
            loading: true,
            notify: false
        },
        mounted: function () {
            this.getQueueList();
            this.listen();
        },
        methods: {
            getQueueList: function () {
                axios.get('/queue-list')
                .then((response) => {
                    this.queueList = response.data.currentQueue
                    this.lastQueueList = response.data.lastQueue
                })
                .catch((error) => {
                    console.log(error)
                })
                .finally(() => {
                    this.loading = false
                })
            },
            listen: function () {
                Echo.channel('queue-list')
                .listen('QueueList', (data) => {
                    this.getQueueList();
                    this.notification('Sıradaki kişi: ' + data.fullname);
                })

                Echo.channel('listen-import')
                .listen('TriggerImport', (data) => {
                    this.getQueueList();
                    this.notification(data.message);
                })
            },
            notification: function (message) {
                if (this.notify == true) {
                    var audio = new Audio('{{ asset('notification.mp3') }}'); // path to file
                    audio.play();
                }

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    onOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                })

                Toast.fire({
                    icon: 'success',
                    title: message
                })
            },
            playSound: function (status) {
                this.notify = status
            }
        }
    })
</script>
@endsection
