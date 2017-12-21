@extends('layouts.master')

@section('title')
    Documentation
@endsection

@section('description')
    Media Web Service Documentation
@endsection

@section('content')
    <h2 id="introduction" class="type--header type--thin">Introduction</h2>
    <p>
        The information is derived from the
        <a href="//api.metalab.csun.edu/directory">Directory Web Service</a> as well
        as <a href="//cloud.name-coach.com/">NameCoach</a>.
        The web service provides a gateway via a REST-ful API. The information is retrieved by
        creating a specific URI and giving values to filter the data. The information that is
        returned is a JSON object that contains room location information to a particular room;
        the format of the JSON object is as follows:
    </p>
    <pre class="prettyprint"><code>{
  "success": "true",
  "status": "200",
  "api": "media",
  "version": "1.0",
  "collection": "media",
  "count": "2",
  "media": [
    {
      "audio": "http://localhost/media-api/public/api/1.0/steven.fitzgerald/audio",
      "avatar": "http://localhost/media-api/public/api/1.0/steven.fitzgerald/avatar"
    }
  ]
}</code></pre>
    <br>
    <h2 id="getting-started" class="type--header type--thin">Getting Started</h2>
    <ol>
        <li><strong>GENERATE THE URI:</strong> Find the usage that fits your need. Browse through subcollections, instances and query types to help you craft your URI.</li>
        <li><strong>PROVIDE THE DATA:</strong> Use the URI to query your data. See the Usage Example session.</li>
        <li><strong>SHOW THE RESULTS</strong></li>
    </ol>
    <p>Loop through the data to display its information. See the Usage Example session.</p>
    <br>
    <h2 id="collections" class="type--header type--thin">Collections</h2>
    <strong>All Persons Media Listing</strong>
    <ul>
        <li>
            <a href="{{url('api/1.0/faculty/media/steven.fitzgerald')}}">
                {{url('api/1.0/faculty/media/steven.fitzgerald')}}
            </a>
        </li>
    </ul>
    <br>
    <h2 id="subcollections" class="type--header type--thin">Subcollections</h2>
    <strong>Specific Media retrieval</strong>
    <ul>
        <li>
            <a href="{{url('api/1.0/steven.fitzgerald/audio')}}">
                {{url('api/1.0/steven.fitzgerald/audio')}}
            </a>
        </li>
        <li>
            <a href="{{url('api/1.0/steven.fitzgerald/avatar')}}">
                {{url('api/1.0/steven.fitzgerald/avatar')}}
            </a>
        </li>
    </ul>
@endsection