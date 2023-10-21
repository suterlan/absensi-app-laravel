@extends('layouts.app')

@push('style')
@powerGridStyles
@endpush


@section('content')
@include('partials.alerts')
<livewire:employee-table />
@endsection

@push('script')
<script src="{{ asset('jquery/jquery-3.6.0.min.js') }}"></script>
@powerGridScripts
@endpush
