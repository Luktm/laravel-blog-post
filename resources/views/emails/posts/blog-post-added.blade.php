@component('mail::message')
# Somene has posted a blog post

Be sure to proof read it.

Thanks,<br>
{{ config('app.name') }}
@endcomponent

{{-- Connect with BlogPostAdded.php --}}
