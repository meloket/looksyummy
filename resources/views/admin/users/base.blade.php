@extends('admin.layouts.app-template')
@section('content')
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Users Management
      </h1>
      
    @yield('action-content')
    <!-- /.content -->
  </div>
@endsection