@extends('layouts.master')

@section('title')
    Version History
@endsection

@section('description')
    {{ env('APP_NAME') }} Web Service Version History
@endsection

@section('content')
    <h2>Version History</h2>
    <h3>{{ env('APP_NAME')}} 1.2.0 <small>Release Date: 10/23/18</small></h3>
    <strong>New Features:</strong>
    <ol>
        <li>Ability to retrieve the specified resource by appending the source=true to the URL.</li>
    </ol>
    <h3>{{ env('APP_NAME')}} 1.2.0 <small>Release Date: 10/23/18</small></h3>
    <strong>Improvements:</strong>
    <ol>
        <li>Fix CORS issues for the current version of the API.</li>
        <li>Update the landing pages to include the latest version of <a href="//csun-metalab.github.io/metaphorV2/">Metaphor</a>.</li>
    </ol>
    <h3>{{ env('APP_NAME') }} 1.1.0 <small>Release Date: 05/08/18</small></h3>
    <strong>New Features:</strong>
    <ol>
        <li>Ability to retrieve student images securely.</li>
        <li>Ability to upload a person's image securely.</li>
    </ol>
    <h3>{{ env('APP_NAME') }} 1.0.2 <small>Release Date: 05/08/18</small></h3>
    <strong>New Features:</strong>
    <ol>
        <li>Include documentation for the person's official image.</li>
    </ol>
    <strong>Improvements:</strong>
    <ol>
        <li>Clean-up the landing page section.</li>
    </ol>
    <h3>{{ env('APP_NAME') }} 1.0.1 <small>Release Date: 02/06/18</small></h3>
        <strong>Improvements:</strong>
        <ol>
            <li>HTTPS can now be enforced at the application level.</li>
        </ol>
    <h3>{{ env('APP_NAME') }} 1.0.0 <small>Release Date: 12/21/17</small></h3>
    <strong>New Features:</strong>
    <ol>
        <li>Initial release.</li>
    </ol>
@endsection
