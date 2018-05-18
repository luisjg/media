@extends('layouts.master')

@section('title')
    Version History
@endsection

@section('description')
    {{ env('APP_NAME') }} Web Service Version HIstory
@endsection

@section('content')
    <h2 class="type--header type--thin">Version History</h2>
    <h3>{{ env('APP_NAME') }} 1.0.2 <small>Release Date: 05/08/18</small></h3>
    <p>
        <strong>New Features:</strong>
        <ol>
            <li>Include documentation for the person's official image.</li>
        </ol>
        <strong>Improvements:</strong>
        <ol>
            <li>Clean-up the landing page section.</li>
        </ol>
    </p>
    <hr>
    <h3>{{ env('APP_NAME') }} 1.0.1 <small>Release Date: 02/06/18</small></h3>
    <p>
        <strong>Improvements:</strong>
        <ol>
            <li>HTTPS can now be enforced at the application level.</li>
        </ol>
    </p>
    <hr>
    <h3>{{ env('APP_NAME') }} 1.0.0 <small>Release Date: 12/21/17</small></h3>
    <p>
        <strong>New Features:</strong>
    <ol>
        <li>Initial release</li>
    </ol>
    </p>
@endsection
