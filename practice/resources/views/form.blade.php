<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Form</title>
</head>
<body>

    <h1>Simple Form</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('message') }}</p>
    @endif

    <form method="post" action="{{ route('sent', ['name'=>1]) }}">
        @csrf

        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="{{ old('name') }}" required>

        <br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="{{ old('email') }}" required>

        <br>

        <button type="submit">Submit</button>
    </form>

</body>
</html>
