@extends('layouts.main')

@section('container')

<section id="chatbot" class="chatbot">
    <div class="container">
        <section class="section-chatbot">
            <div class="container">
                <div class="header text-center">
                    <h3>Chatbot</h3>
                </div>
                <div class="row justify-content-center">
                    <div class="col-12 col-sm-12 col-md-10 col-lg-6">
                        <form action="{{ route('chat.completion') }}" method="POST">
                            @csrf
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="content" placeholder="Enter question">
                                <button type="submit" class="btn" style="background-color: #ed174f; color:white">Ask</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-8">
                        @if(isset($response))
                            <div class="message bot-message">
                                <strong>Chatbot:</strong> {{ $response }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>        
    </div>
</section>

@endsection