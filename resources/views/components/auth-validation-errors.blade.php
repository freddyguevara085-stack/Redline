@props(['errors'])

@if ($errors->any())
    <div {{ $attributes->merge(['class' => 'alert-error']) }}>
        <strong>{{ __('Whoops! Something went wrong.') }}</strong>

        <ul class="form-errors">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
