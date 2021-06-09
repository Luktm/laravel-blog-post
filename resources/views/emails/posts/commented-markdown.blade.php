@component('mail::message')
# Comment was posted on your blog post

{{-- {luk}: $comment data passed from the CommentPostedMarkdown.php --}}

Hi {{ $comment->commentable->user->name }}

Someone has commented on your blog post

{{-- refer back to one to many polymorphic relationship --}}
@component('mail::button', ['url' =>  route("posts.show", ["post" => $comment->commentable->id])])
View the Blog Post
@endcomponent

@component('mail::button', ['url' => route("users.show", ["user" => $comment->user->id]) ])
Visit {{ $comment->user->name }} profile
@endcomponent

@component("mail::panel")
{{ $comment->content }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
{{-- run "php artisan vendor:publish --tag=laravel-mail" to copy folder to public one --}}
{{-- this copy /vendor/laravel/framework/src/Illuminate/Mail/resources/views to /resources/views/vendor/mail   --}}
{{-- check resources/view/vendor/mail --}}

{{-- after that go post a new comment Mailtrap website will receive a template from this page --}}

{{-- markdown cheatsheet webstie https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet --}}
