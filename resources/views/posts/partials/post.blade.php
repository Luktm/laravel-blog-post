{{-- @break($key == 2) --}}

{{-- skip 1 and continue --}}
{{-- @continue($key == 1) --}}

{{-- use include in other pages, the properties will auto pass downt to this page --}}

<h3>
    {{-- softdelete directive, display trash post as well --}}
    @if ($post->trashed())
        <del>
    @endif

    <a class="{{ $post->trashed() ? 'text-muted' : '' }}"
        href="{{ route('posts.show', ['post' => $post->id]) }}">{{ $post->title }}</a>
    {{-- softdelete directive, display trash post as well --}}
    @if ($post->trashed())
        </del>
    @endif
</h3>

{{-- AuthServiceProvider.php preset updated component --}}
{{-- <p class="text-muted">
    Added {{ $post->created_at->diffForHumans() }}
    by {{ $post->user->name }}
</p> --}}
@updated(['date' => $post->created_at, 'name' => $post->user->name])
{{-- $slot --}}
@endupdated


@if ($post->comments_count)
    <p>{{ $post->comments_count }} comments</p>
@else
    <p> No comments yet!</p>
@endif

<div class="mb-3">

    {{-- only same user id / admin for this post able edit else hide edit button --}}
    {{-- go AuthServiceProvider.php line 20, bcuz it's declared, we can just call update or posts.update --}}
    @can('update', $post)
        <a href="{{ route('posts.edit', ['post' => $post->id]) }}" class="btn btn-primary">Edit</a>
    @endcan

    {{-- delete ability removed then this will show up --}}
    @cannot('delete', $post)
    <p>You can't delete this post</p>
    @endcannot

    {{-- only same user id / admin for this post able delete else hide delete button --}}
    {{-- go AuthServiceProvider.php line 20, bcuz it's declared, we can just call delete or posts.delete in Gate::resource('posts') --}}
    {{-- softdelete directive, display trash post as well --}}
    @if (!$post->trashed())
        @can('delete', $post)
            <form class="d-inline" action="{{ route('posts.destroy', ['post' => $post->id]) }}" method="POST">
                @csrf
                @method('DELETE')
                <input type="submit" value="Delete!" class="btn btn-primary">
            </form>
        @endcan
    @endif
</div>
