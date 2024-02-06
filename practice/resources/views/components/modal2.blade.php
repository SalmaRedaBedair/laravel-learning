{{-- @php
$modules = [
    (object) ['title' => 'Module 1'],
    (object) ['title' => 'Module 2'],
    (object) ['title' => 'Module 3'],
];
@endphp
<div class="sidebar">
    @each('child', $modules, 'module', 'partials.empty-module')
</div> --}}


<div>
    {{ $title }}
    {{ $slot }}
<div>
