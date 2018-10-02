@extends('layouts.app')

@section('page-head')
<link rel="stylesheet" href="https://releases.flowplayer.org/7.2.7/skin/skin.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/hls.js/0.10.1/hls.light.min.js"></script>
<script src="https://releases.flowplayer.org/7.2.7/commercial/flowplayer.min.js"></script>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Live</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>
                <div id="live" class="fp-edgy fp-mute"></div>
            </div>
        </div>
    </div>
</div>
<script>
        flowplayer("#live", {
            live: true,
            embed: false,
            tooltip: true,
            autoplay: true,
            loop: true,
            ratio: 3/4,
            fullscreen: true,
            hlsjs: {safari: true},
            clip: {
                sources: [
                    { 
                        type: "application/x-mpegurl", 
                        src: "http://parspack/playlist.m3u8" 
                    }
                ]
            }
        });
</script>
@endsection
