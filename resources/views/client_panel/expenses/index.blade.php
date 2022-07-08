@extends('client_panel.layouts.app')
@section('title')
    Expenses
@endsection
@section('content')
<div class="container-fluid">
    <div class="d-flex flex-column ">
        <livewire:client-expenses-table/>
    </div>
</div>
@endsection
