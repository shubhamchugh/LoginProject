@if (config('app.debug'))
<div class="card-body">
    <div class="demo-spacing-0">
        @include('backend.partials.message')
    </div>
</div>
<div class="card-body">
    <div class="row">
        <div class="col-6">
            {{-- site_name start --}}
            <div class="form-group {{ $errors->has('site_name') ? 'is-invalid' : '' }}">
                <label for="first-name-vertical">site_name</label>
                <input type="text" id="first-name-vertical" class="form-control" name="site_name"
                    placeholder="site_name" value="{{ old('site_name', $site_name ?? null)}}" />
                <div class="valid-feedback">Looks good!</div>
                <div class="invalid-feedback">Please enter Title</div>

                @if($errors->has('site_name'))
                <span class="help-block text-warning">{{ $errors->first('site_name') }}</span>
                @endif
            </div>
            {{-- site_name end --}}


            {{-- home_title start --}}
            <div class="form-group {{ $errors->has('home_title') ? 'is-invalid' : '' }}">
                <label for="first-name-vertical">home_title</label>
                <input type="text" id="first-name-vertical" class="form-control" name="home_title"
                    placeholder="home_title" value="{{ old('home_title', $home_title ?? null)}}" />
                <div class="valid-feedback">Looks good!</div>
                <div class="invalid-feedback">Please enter Title</div>

                @if($errors->has('home_title'))
                <span class="help-block text-warning">{{ $errors->first('home_title') }}</span>
                @endif
            </div>
            {{-- home_title end --}}


            {{-- google_forms_contact start --}}
            <div class="form-group {{ $errors->has('google_forms_contact') ? 'is-invalid' : '' }}">
                <label for="first-name-vertical">google_forms_contact</label>
                <input type="text" id="first-name-vertical" class="form-control" name="google_forms_contact"
                    placeholder="google_forms_contact"
                    value="{{ old('google_forms_contact', $google_forms_contact ?? null)}}" />
                <div class="valid-feedback">Looks good!</div>
                <div class="invalid-feedback">Please enter Title</div>

                @if($errors->has('google_forms_contact'))
                <span class="help-block text-warning">{{ $errors->first('google_forms_contact') }}</span>
                @endif
            </div>
            {{-- google_forms_contact end --}}


            {{-- home_h1_title start --}}
            <div class="form-group {{ $errors->has('home_h1_title') ? 'is-invalid' : '' }}">
                <label for="first-name-vertical">home_h1_title</label>
                <input type="text" id="first-name-vertical" class="form-control" name="home_h1_title"
                    placeholder="home_h1_title" value="{{ old('home_h1_title', $home_h1_title ?? null)}}" />
                <div class="valid-feedback">Looks good!</div>
                <div class="invalid-feedback">Please enter Title</div>

                @if($errors->has('home_h1_title'))
                <span class="help-block text-warning">{{ $errors->first('home_h1_title') }}</span>
                @endif
            </div>
            {{-- home_h1_title end --}}


            {{-- home_page_description start --}}
            <div class="form-group {{ $errors->has('home_page_description') ? 'is-invalid' : '' }}">
                <label for="first-name-vertical">home_page_description</label>
                <input type="text" id="first-name-vertical" class="form-control" name="home_page_description"
                    placeholder="home_page_description"
                    value="{{ old('home_page_description', $home_page_description ?? null)}}" />
                <div class="valid-feedback">Looks good!</div>
                <div class="invalid-feedback">Please enter Title</div>

                @if($errors->has('home_page_description'))
                <span class="help-block text-warning">{{ $errors->first('home_page_description') }}</span>
                @endif
            </div>
            {{-- home_page_description end --}}


            {{-- header_code start --}}
            <div class="form-group {{ $errors->has('header_code') ? 'is-invalid' : '' }}">
                <label for="first-name-vertical">header_code</label>
                <textarea style="width:635px; height:100px;" id="first-name-vertical" class="form-control"
                    name="header_code" placeholder="header_code"
                    value="{{ old('header_code', $header_code ?? null)}}">{{ $header_code }}</textarea>
                <div class="valid-feedback">Looks good!</div>
                <div class="invalid-feedback">Please enter Title</div>

                @if($errors->has('header_code'))
                <span class="help-block text-warning">{{ $errors->first('header_code') }}</span>
                @endif
            </div>
            {{-- header_code end --}}

            {{-- theme_color start --}}
            <div class="form-group {{ $errors->has('theme_color') ? 'is-invalid' : '' }}">
                <label for="first-name-vertical">theme_color Only (1-8)</label>
                <input type="text" id="first-name-vertical" class="form-control" name="theme_color"
                    placeholder="theme_color" value="{{ old('theme_color', $theme_color ?? null)}}" />
                <div class="valid-feedback">Looks good!</div>
                <div class="invalid-feedback">Please enter Title</div>

                @if($errors->has('theme_color'))
                <span class="help-block text-warning">{{ $errors->first('theme_color') }}</span>
                @endif
            </div>
            {{-- theme_color start --}}

            {{-- author_name start --}}
            <div class="form-group {{ $errors->has('author_name') ? 'is-invalid' : '' }}">
                <label for="first-name-vertical">author_name</label>
                <input type="text" id="first-name-vertical" class="form-control" name="author_name"
                    placeholder="author_name" value="{{ old('author_name', $author_name ?? null)}}" />
                <div class="valid-feedback">Looks good!</div>
                <div class="invalid-feedback">Please enter Title</div>

                @if($errors->has('author_name'))
                <span class="help-block text-warning">{{ $errors->first('author_name') }}</span>
                @endif
            </div>
            {{-- author_name end --}}


            {{-- bellow_title_ads start --}}
            <div class="form-group {{ $errors->has('bellow_title_ads') ? 'is-invalid' : '' }}">
                <label for="first-name-vertical">bellow_title_ads</label>
                <textarea style="width:635px; height:100px;" id="first-name-vertical" class="form-control"
                    name="bellow_title_ads" placeholder="bellow_title_ads"
                    value="{{ old('bellow_title_ads', $bellow_title_ads ?? null)}}">{{ $bellow_title_ads }}</textarea>
                <div class="valid-feedback">Looks good!</div>
                <div class="invalid-feedback">Please enter Title</div>

                @if($errors->has('bellow_title_ads'))
                <span class="help-block text-warning">{{ $errors->first('bellow_title_ads') }}</span>
                @endif
            </div>
            {{-- bellow_title_ads end --}}


            {{-- about_us start --}}
            <div class="form-group {{ $errors->has('about_us') ? 'is-invalid' : '' }}">
                <label for="first-name-vertical">about_us</label>
                <textarea style="width:635px; height:100px;" id="first-name-vertical" class="form-control"
                    name="about_us" placeholder="about_us"
                    value="{{ old('about_us', $about_us ?? null)}}">{{ $about_us }}</textarea>
                <div class="valid-feedback">Looks good!</div>
                <div class="invalid-feedback">Please enter Title</div>

                @if($errors->has('about_us'))
                <span class="help-block text-warning">{{ $errors->first('about_us') }}</span>
                @endif
            </div>
            {{-- about_us end --}}

            <button type="submit" class="btn btn-primary mr-1">Update</button>
        </div>

    </div>
</div>
@else

Please Enable Debug to edit Settings

@endif