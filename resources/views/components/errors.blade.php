{{-- check got error see Kernel.php and PostsController.php store method --}}
{{-- and go to AppServiceProvider.php to register new blade component --}}
@if ($errors->any())
    <div class="mt-2 mb-3">
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
                {{ $error }}
            </div>
        @endforeach
    </div>
@endif
