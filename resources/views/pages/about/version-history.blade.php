@extends('layouts.master')

@section('title')
    Version History
@endsection

@section('description')
    Media Web Service Version HIstory
@endsection

@section('content')
    <h1 class="type--header type--thin">Version History</h1>
    <h2>{{ env('APP_NAME') }} 1.0.2 <small>Release Date: 04/09/18</small></h2>
    <p>
        <strong>Improvements:</strong>
        <ol>
            <li>Ability to upload a profile picture.</li>
        </ol>
    </p>
    <hr>
    <h2>{{ env('APP_NAME') }} 1.0.1 <small>Release Date: 02/06/18</small></h2>
    <p>
        <strong>Improvements:</strong>
        <ol>
            <li>HTTPS can now be enforced at the application level.</li>
        </ol>
    </p>
    <hr>
    <h2>{{ env('APP_NAME') }} 1.0.0 <small>Release Date: 12/21/17</small></h2>
    <p>
        <strong>New Features:</strong>
    <ol>
        <li>Initial release</li>
    </ol>
    </p>
@endsection
