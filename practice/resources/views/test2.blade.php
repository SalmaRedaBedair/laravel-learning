{{-- @extends('test')
@section('title', 'loma')
@section('content')
<p>loma</p>
@endsection
@section('footerScripts')
        <p>coco</p>
        <script src="app.js"></script>
@endsection
--}}
{{-- <div class="content" data-page-name="loma">
    <p>Here's why you should sign up for our app: <strong>It's Great.</strong></p>
    @include('test', ['text' => 'See just how great it is'])
</div> --}}

@extends('test')
@push('styles')
    <!-- push something to the bottom of the stack -->
@endpush
