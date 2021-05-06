@extends("layouts.app")

@section("content")
{{-- route("users.update") gonna pass it to web.php route --}}
    <form action="POST" enctype="multipart/form-data" action="{{ route("users.update", ["user" => $user->id]) }}" class="form-horizontal">

        @csrf
        @method("PUT") {{-- run php artisan route:list in web.php Route::resource("users", UserController::class) update() is PUT method --}}
        {{-- this send the token --}}

        <div class="row">
            <div class="col-4">
                <img src="" alt="" class="img-thumbnail avatar">

                <div class="card mt-4">
                    <div class="card-body">
                        <h6>Upload a different photo</h6>
                        <input type="file" class="form-control-file" name="avatar" />
                    </div>
                </div>
            </div>

            <div class="col-8">
                <div class="form-group">
                    <label for="">Name:</label>
                    <input type="text" class="form-control" name="name" />
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Save Changes" />
                </div>
            </div>
        </div>
    </form>
@endsection
