@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>
                <center>
                    <button onclick="initFirebaseMessagingRegistration()" class="btn btn-danger btn-xs btn-flat">Allow for Notification</button>
                </center><br>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
                <div class="card-body">
                <form action="{{ route('send.push-notification') }}" method="POST">

                    @csrf

                    <div class="form-group">

                        <label>Title</label>

                        <input type="text" class="form-control" name="title">

                    </div>

                    <div class="form-group">

                        <label>Body</label>

                        <textarea class="form-control" name="body"></textarea>

                      </div>

                    <button type="submit" class="btn btn-primary">Send Notification</button>

                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://www.gstatic.com/firebasejs/8.3.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.3.0/firebase-messaging.js"></script>
<script>
  var firebaseConfig = {
    apiKey: "AIzaSyD1mC_cgY_CMdIVecEbl5F-D77ZizvhUjU",
          authDomain: "skinner-b9bb0.firebaseapp.com",
          projectId: "skinner-b9bb0",
          storageBucket: "skinner-b9bb0.appspot.com",
          messagingSenderId: "643973041720",
          appId: "1:643973041720:web:3c304e79d3d5a1d1f7a0cd",
          measurementId: "G-D212L0HR5B"
  };
      
    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();
  
    function initFirebaseMessagingRegistration() {
            messaging
            .requestPermission()
            .then(function () {
                return messaging.getToken({ vapidKey: 'Your_Public_Key' })
            })
            .then(function(token) {
                console.log(token);
   
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
  
                $.ajax({
                    url: '{{ route("save-push-notification-token") }}',
                    type: 'POST',
                    data: {
                        token: token
                    },
                    dataType: 'JSON',
                    success: function (response) {
                        alert('Token saved successfully.');
                    },
                    error: function (err) {
                        console.log('User Chat Token Error'+ err);
                    },
                });
  
            }).catch(function (err) {
                console.log('User Chat Token Error'+ err);
            });
     }  
      
    messaging.onMessage(function(payload) {
        const noteTitle = payload.notification.title;
        const noteOptions = {
            body: payload.notification.body,
            icon: payload.notification.icon,
        };
        new Notification(noteTitle, noteOptions);
    });
   
</script>
@endsection
