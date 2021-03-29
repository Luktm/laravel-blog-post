<div class="card" style="width: 100%;">
    <div class="card-body">
        <h5 class="card-title">{{ $title }}</h5>
        <h6 class="card-text text-muted mb-2">
            {{ $subtitle }}
        </h6>
    </div>
    <ul class="list-group list-group-flush">
        {{-- get from PostController.php index local scope query --}}
        {{-- is_a check $items is collection, if yes return card item else return $items possibly contain different html --}}
        @if(is_a($items, 'Illuminate\Support\Collection'))
            @foreach ($items as $item)
                <li class="list-group-item">
                    {{ $item }}
                </li>
            @endforeach
        @else
            {{ $items }}
        @endif
    </ul>
</div>
