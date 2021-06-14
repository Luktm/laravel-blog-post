@extends("layouts.app")

@section("content")
{{-- route("users.update") gonna pass it to web.php route --}}
    <form action="{{ route("users.update", ["user" => $user->id]) }}" method="POST" enctype="multipart/form-data"  class="form-horizontal">

        @csrf
        @method("PUT") {{-- run php artisan route:list in web.php Route::resource("users", UserController::class) update() is PUT method --}}
        {{-- this send the token --}}

        <div class="row">
            <div class="col-4">
                <img
                    {{--  $user->image->url() get from User.php model image() --}}
                    src="{{ $user->image ? $user->image->url() : "" }}"
                    class="img-thumbnail avatar"
                >

                <div class="card mt-4">
                    <div class="card-body">
                        <h6>Upload a different photo</h6>
                        <input type="file" class="form-control-file" name="avatar" />
                    </div>
                </div>
            </div>

            <div class="col-8">
                <div class="form-group">
                    <label for="">{{ __("Name:") }}</label>
                    <input type="text" class="form-control" name="name" />
                </div>

                <div class="form-group">
                    {{-- get from resource/lang/xx.json key name --}}
                    {{-- remember put public const LOCALE =[] in User.php --}}
                    <label for="">{{ __("Language") }}</label>
                    <select class="form-control" name="locale" id="">
                        {{-- loop from user.php LOCALES const variable by passing path--}}
                        {{-- set key as $locale and value as $label --}}
                        @foreach (App\Models\User::LOCALES as $locale => $label)
                        {{--  ?: without space is do nothing, like ? :, but space remove --}}
                            <option value="{{ $locale }}" {{ $user->locale !== $locale ?: "selected" }} >{{$label}}</option>
                        @endforeach
                    </select>
                </div>

                {{-- get from AppServiceProvider.php line 37 --}}
                @errors @enderrors

                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Save Changes" />
                </div>
            </div>
        </div>
    </form>
@endsection
