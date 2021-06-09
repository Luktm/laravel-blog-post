@forelse($comments as $comment)
{{-- comment come from props --}}
    <p class="text-muted">
        {{ $comment->content }}
        {{-- , added {{ $comment->created_at->diffForHumans() }} --}}
    </p>
    @tags(["tags" => $comment->tags])@endtags
    {{-- specify full component template name in AppServiceProvider.php and component folder --}}
    @updated(['date' => $comment->created_at, 'name' => $comment->user->name, "userId" => $comment->user->id])
    @endupdated
    @empty
    <p>No comments yet!</p>
@endforelse
