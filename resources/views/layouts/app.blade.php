@extends('adminlte::page')

@push('js')
    @if (session('status'))
        <script>toastr.success('{{ session('status') }}');</script>
    @endif
@endpush
