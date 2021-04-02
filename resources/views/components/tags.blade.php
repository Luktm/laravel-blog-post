<p>
    @foreach ($tags as $tag)
        {{-- this will go thru web.php and navigate to new page --}}
        {{-- but mostCommented, etc will gone as --}}
        <a href="{{ route("posts.tags.index", ["tag" => $tag->id]) }}" class="badge badge-success badge-lg">
            {{ $tag->name }}
            {{-- then go to AppServiceProvider.php add Blade::aliasComponent() --}}
        </a>
    @endforeach
</p>
