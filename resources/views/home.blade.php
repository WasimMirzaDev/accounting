
@extends('layouts.app')
@section('content')
<style media="screen">
   #dashboard_menu{
   color:white !important;
   }
   .demo{
   top:10px !important;
   }
</style>
<link rel="stylesheet" href="{{asset('css/dashboard-tiles.css')}}">
<div class="container-fluid">
  <div class="container page-heading">
     <h1>{{auth()->user()->roles->name}} - {{auth()->user()->name}}</h1>
  </div>
  
  <br/>
</div>
@endsection
