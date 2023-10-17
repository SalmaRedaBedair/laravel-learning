{{-- <?php
$pages = [(object) ['title' => 'Talk 1', 'length' => 30], (object) ['title' => 'Talk 2', 'length' => 45], (object) ['title' => 'Talk 3', 'length' => 20]];
$talks = [(object) ['title' => 'Talk 1', 'length' => 30], (object) ['title' => 'Talk 2', 'length' => 45], (object) ['title' => 'Talk 3', 'length' => 20]];
?>

@forelse ($talks as $talk)
    {{ $talk->title }} ({{ $talk->length }} minutes)<br>
@empty
    No talks this day.
@endforelse

<ul>
    @foreach ($pages as $page)
        <li>{{ $loop->iteration }}: {{ $page->title }}
            @if ($page->hasChildren())
                <ul>
                    @foreach ($page->children() as $child)
                        <li>{{ $loop->parent->iteration }}
                            .{{ $loop->iteration }}:
                            {{ $child->title }}</li>
                    @endforeach
                </ul>
            @endif
        </li>
    @endforeach
</ul> --}}

{{-- <head>
    <title>My Site | @yield('title', 'Home Page')</title>
</head>

<body>
    <div class="container">
        @yield('content')
    </div>
    @section('footerScripts')
        <p>loma</p>
        <script src="app.js"></script>
    @stop
</body>

</html> --}}

{{--

<a class="button button--callout" data-page-name="loma">
    <i class="exclamation-icon"></i> {{ $text }}
</a> --}}

{{-- <html>

<head><!-- the head --></head>

<body>
    <p>loma</p>
    <!-- the rest of the page -->

    <style>
        p{
            color: red;
        }
    </style>
    <!-- the placeholder where stack content will be placed -->
    @stack('styles')
</body>

</html> --}}

<!-- resources/views/test.blade.php -->
<!-- resources/views/test.blade.php -->
{{-- <div class="modal">
    <div class="close button etc">...</div>
</div> --}}

{{-- {{ $recentPosts }}
{{ $mmss }} --}}

@ifok
{{ 'loma' }}
@endif

@if(true)
{{ 'loma' }}
@endif

@newlinesToBr('coco')
