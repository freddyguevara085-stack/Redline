<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-neo']) }}>
    {{ $slot }}
</button>
