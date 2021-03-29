<p class="text-muted">
    {{-- pass from post.blade.php at line 22 --}}
    {{ empty(trim($slot)) ? 'Added ' : $slot }} {{ $date->diffForHumans() }}
    @if(isset($name))
        by {{ $name }}
    @endif
</p>
