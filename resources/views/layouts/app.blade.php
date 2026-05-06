@extends('adminlte::page')

@push('js')
    @if (session('status'))
        <script>toastr.success('{{ session('status') }}');</script>
    @endif
    @if (session('error'))
        <script>toastr.error('{{ session('error') }}');</script>
    @endif
@endpush
