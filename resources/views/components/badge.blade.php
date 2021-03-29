@if (!isset($show) || $show)
{{-- resuseable component at AppServiceProvider.php --}}
    <span class="badge badge-{{ $type ?? 'success' }}">
        {{-- type was extra parameter --}}
        {{-- pass from show.blade.php line 26 --}}
        {{-- slow is like props in react --}}
        {{ $slot }}
    </span>
@endif
