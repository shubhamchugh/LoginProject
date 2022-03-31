@extends('settings.master')

@section('content')
<div class="auth-wrapper auth-v1 px-2">
    <div class="auth-inner py-2">
        <!-- Login v1 -->
        <div class="card mb-0">
            <div class="card-body">
                <h4 class="card-title mb-1">Genral Settings Update!</h4>
                <form method="POST" action="{{ route('settings.update')}}" id="post-form" enctype="multipart/form-data">
                    @csrf

                    <!-- Basic Vertical form layout section start -->
                    @include('settings.form')
                    <!-- Basic Vertical form layout section end -->
                </form>
            </div>
        </div>
    </div>
</div>

<div class="auth-wrapper auth-v1 px-2">
    <div class="auth-inner py-2">
        <!-- Login v1 -->
        <div class="card mb-0">
            <div class="card-body">
                <h4 class="card-title mb-1">Author Image Update! 👋</h4>
                <form method="POST" action="{{ route('settings.image.update') }}" enctype="multipart/form-data">
                    @csrf
                    {{-- Image Upload start --}}
                    <div class="form-group {{ $errors->has('author_Image') ? 'is-invalid' : '' }}">
                        <label for="first-name-vertical">author_Image</label>
                        <input type="file" id="first-name-vertical" class="form-control" name="author_Image"
                            placeholder="author_Image" required />
                        <div class="valid-feedback">Looks good!</div>
                        <div class="invalid-feedback">Please enter Title</div>

                        @if($errors->has('author_Image'))
                        <span class="help-block text-warning">{{ $errors->first('author_Image') }}</span>
                        @endif
                    </div>
                    {{-- Image Upload end --}}
                    <button type="submit" class="btn btn-primary mr-1">upload Image</button>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection