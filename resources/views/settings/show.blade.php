@extends('settings.master')

@section('content')
<form method="POST" action="{{ route('settings.update')}}" id="post-form" enctype="multipart/form-data">
    @csrf

    <!-- Basic Vertical form layout section start -->
    @include('settings.form')
    <!-- Basic Vertical form layout section end -->
</form>
@endsection