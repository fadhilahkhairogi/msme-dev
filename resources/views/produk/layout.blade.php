@extends('adminlte::page')

@section('title', 'Master Data | UMKM App')

@section('js')
    @if (session('Sukses') || session('sukses') || session('error'))
        <script>
            $(function() {
                var Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });

                Toast.fire({
                    icon: '{{ session('error') ? 'error' : 'success' }}',
                    title: '{{ session('Sukses') ?? (session('sukses') ?? session('error')) }}'
                });
            });
        </script>
    @endif

    @stack('scripts')
@stop
