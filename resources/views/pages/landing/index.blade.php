@extends('layouts.master')

@section('title')
    Documentation
@endsection

@section('description')
    {{ env('APP_NAME') }} Web Service Documentation
@endsection

@section('content')
    <h2 id="introduction">Introduction</h2>
    <p>
        The {{ env('APP_NAME') }} Web Service leverages
        <a href="//cloud.name-coach.com">NameCoach</a> to retrieve an
        individual’s profile image and their pre-recorded name pronunciation.
        The web service provides a gateway via a REST-ful API. The information is retrieved by
        creating a specific URI and giving values to filter the data. The information that is
        returned is a JSON object that contains media information of a particular CSUN individual.
        The format of the JSON object is as follows:
    </p>
    <pre class="prettyprint"><code>{
    "success": "true",
    "status": "200",
    "api": "media",
    "version": "1.1",
    "collection": "media",
    "count": "3",
    "media": [
        {
            "audio_recording": "{{ url('1.1/faculty/media/'.$emailUri.'/audio-recording') }}",
            "avatar_image": "{{ url('1.1/faculty/media/'.$emailUri.'/avatar-image') }}",
            "photo_id_image": "{{ url('1.1/faculty/media/'.$emailUri.'/photo-id-image') }}"
        }
    ]
}</code></pre>
    <br>
    <h2 id="getting-started">Getting Started</h2>
    <ol>
        <li><strong>GENERATE THE URI:</strong> Find the usage that fits your need. Browse through subcollections, instances and query types to help you craft your URI.</li>
        <li><strong>PROVIDE THE DATA:</strong> Use the URI to query your data. See the Usage Example session.</li>
        <li><strong>SHOW THE RESULTS</strong></li>
    </ol>
    <p>Loop through the data to display its information. See the <a href="#usage-example">Usage Example</a> section.</p>
    <br>
    <h2 id="collections">Collections</h2>
    <strong>All Persons Media Listing</strong>
    <ul>
        <li>
            <a href="{{ url('1.1/faculty/media/'.$emailUri) }}">
                {{ url('1.1/faculty/media/'.$emailUri) }}
            </a>
        </li>
    </ul>
    <br>
    <h2 id="subcollections">Subcollections</h2>
    <strong>Specific Media Retrieval</strong>
    <ul>
        <li>
            <a href="{{ url('1.1/faculty/media/'.$emailUri.'/audio') }}">
                {{ url('1.1/faculty/media/'.$emailUri.'/audio') }}
            </a>
        </li>
        <li>
            <a href="{{ url('1.1/faculty/media/'.$emailUri.'/avatar') }}">
                {{ url('1.1/faculty/media/'.$emailUri.'/avatar') }}
            </a>
        </li>
        <li>
            <a href="{{ url('1.1/faculty/media/'.$emailUri.'/official') }}">
                {{ url('1.1/faculty/media/'.$emailUri.'/official') }}
            </a>
        </li>
    </ul>
    <h2 id="code-samples">Code Samples</h2>
    <div class="accordion">
        <div class="card">
            <div id="jquery-header" class="card-header">
                <p class="mb-0">
                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#jquery-body" aria-expanded="true" aria-controls="jquery-body">
                        JQuery
                    </button>
                </p>
            </div>
            <div id="jquery-body" class="collapse" aria-labelledby="jquery-header">
                <div class="card-body">
                    <pre>
                        <code class="prettyprint lang-js">
//construct a function to get url and iterate over
$(document).ready(function() {
    //generate a url
    var url = '{!! url('1.1/faculty/media/'.$emailUri.'/avatar') !!}';
    //use the URL as a request
    $.ajax({
        url: url
    }).done(function(data) {
        // print the image url
        console.log(data.avatar_image);
    });
});
                        </code>
                    </pre>
                </div>
            </div>
        </div>
        <div class="card">
            <div id="php-header" class="card-header">
                <p class="mb-0">
                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#php-body" aria-expanded="true" aria-controls="php-body">
                        PHP
                    </button>
                </p>
            </div>
            <div id="php-body" class="collapse" aria-labelledby="php-header">
                <div class="card-body">
                    <pre>
                        <code class="prettyprint lang-php">
//generate a url
$url = '{!! url('1.1/faculty/media/'.$emailUri.'/avatar') !!}';

//add extra necessary
$arrContextOptions = [
    "ssl" => [
        "verify_peer"=>false,
        "verify_peer_name"=>false
    ]
];

//perform the query
$data = file_get_contents($url, false, stream_context_create($arrContextOptions));

//decode the json
$data = json_decode($data, true);

//iterate over the list of data and print
echo $data['avatar_image'];
                        </code>
                    </pre>
                </div>
            </div>
        </div>
        <div class="card">
            <div id="python-header" class="card-header">
                <p class="mb-0">
                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#python-body" aria-expanded="true" aria-controls="python-body">
                        Python
                    </button>
                </p>
            </div>
            <div id="python-body" class="collapse" aria-labelledby="python-header">
                <div class="card-body">
                    <pre>
                        <code class="prettyprint language-py">
#python
import urllib2
import json

#generate a url
url = u'{!! url('1.1/faculty/media/'.$emailUri.'/avatar') !!}'

#open the url
try:
    u = urllib2.urlopen(url)
    data = u.read()
except Exception as e:
    data = {}

#load data with json object
data = json.loads(data)

#iterate over the json object and print
print data['avatar_image']
                        </code>
                    </pre>
                </div>
            </div>
        </div>
        <div class="card">
            <div id="ruby-header" class="card-header">
                <p class="mb-0">
                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#ruby-body" aria-expanded="true" aria-controls="ruby-body">
                        Ruby
                    </button>
                </p>
            </div>
            <div id="ruby-body" class="collapse" aria-labelledby="ruby-header">
                <div class="card-body">
                    <pre>
                        <code class="prettyprint lang-rb">
require 'net/http'
require 'json'

#generate a url
source = '{!! url('1.1/faculty/media/'.$emailUri.'/avatar') !!}'

#prepare the uri
uri = URI.parse(source)

#request the data
response = Net::HTTP.get(uri)

#parse the json
data = JSON.parse(response)

#print the value
puts "#{data['avatar_image']}"
                        </code>
                    </pre>
                </div>
            </div>
        </div>
    </div>
@endsection