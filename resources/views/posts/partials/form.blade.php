
{{-- set default value at old(1, $post->title) --}}
<div class="form-group">
    <label for="title">Title</label>
    <input id="title" type="text" name="title" class="form-control" value="{{ old('title', optional($post ?? null)->title) }}">
</div>

{{-- error directive show specific property --}}
@error('title')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

{{-- second parameter will get insert the data first --}}
<div class="form-group">
    <label for="content">Content</label>
    <textarea class="form-control" id="content" name="content">{{ old('content', optional($post ?? null)->content) }}</textarea>
</div>

{{-- Episode 182 add type="file" remember put in FILESYSTEM_DRIVER=public in .env --}}
<div class="form-group">
    <label for="Thumbnail">Thumbnail</label>
    <input id="title" type="file" name="thumbnail" class="form-control-file">
</div>

{{-- check got error see Kernel.php and PostsController.php store method
--}}
{{-- @if ($errors->any())
    <div class="mb-3">
        <ul class="list-group">
            @foreach ($errors->all() as $error)
                <li class="list-group-item list-group-item-danger">
                    {{ $error }}
                </li>
            @endforeach
        </ul>
    </div>
@endif --}}

{{-- find this alias at AppServiceProvider.php at line 35 --}}
@errors @enderrors
