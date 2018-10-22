@extends('layouts.master')

@section('title')
    Version History
@endsection

@section('description')
    {{ env('APP_NAME') }} Web Service Version History
@endsection

@section('content')
    <h2>Version History</h2>
    <h3 class="h5 padding"{{ env('APP_NAME') }} 1.1.0 <small>Release Date: 05/08/18</small></h3>
    <strong>New Features:</strong>
    <ol>
        <li>Ability to retrieve student images securely.</li>
        <li>Ability to upload a person's image securely.</li>
    </ol>
    <hr class="margin">
    <h3 class="h5 padding">{{ env('APP_NAME') }} 1.0.2 <small>Release Date: 05/08/18</small></h3>
    <strong>New Features:</strong>
    <ol>
        <li>Include documentation for the person's official image.</li>
    </ol>
    <strong>Improvements:</strong>
    <ol>
        <li>Clean-up the landing page section.</li>
    </ol>
    <hr class="margin">
    <h3 class="h5 padding">{{ env('APP_NAME') }} 1.0.1 <small>Release Date: 02/06/18</small></h3>
        <strong>Improvements:</strong>
        <ol>
            <li>HTTPS can now be enforced at the application level.</li>
        </ol>
    <hr class="margin">
    <h3 class="h5 padding">{{ env('APP_NAME') }} 1.0.0 <small>Release Date: 12/21/17</small></h3>
    <strong>New Features:</strong>
    <ol>
        <li>Initial release</li>
    </ol>
@endsection
