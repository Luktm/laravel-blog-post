<style>
    /* come from CommentPosted.php */
    body {
        font-family: Arial, Helvetica, sans-serif;
    }
</style>

<p>hi {{ $comment->commentable->user->name }}</p>

<p>
    Somene has commented on your blog post
    {{-- refer back to one to many polymorphic relationship --}}
    <a href="{{ route("posts.show", ["post" => $comment->commentable->id]) }}">
        {{ $comment->commentable->title }}
    </a>
</p>

<hr />

<p>
    {{-- there is a specific variable available inside all of your email template call $message --}}
    {{-- Comment.php has user() and user() has image(), then image has url() --}}
    <img src="{{ $message->embed($comment->user->image->url()) }}" alt="">
    <a href="{{ route("users.show", ["user" => $comment->user->id]) }}">
        {{ $comment->user->name }}
    </a> said:
</p>

<p>
    "{{ $comment->content }}"
</p>
