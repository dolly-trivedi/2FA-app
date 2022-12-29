importScripts('https://www.gstatic.com/firebasejs/8.3.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.0/firebase-messaging.js');
   
	firebase.initializeApp({
        apiKey: "AIzaSyD1mC_cgY_CMdIVecEbl5F-D77ZizvhUjU",
          authDomain: "skinner-b9bb0.firebaseapp.com",
          projectId: "skinner-b9bb0",
          storageBucket: "skinner-b9bb0.appspot.com",
          messagingSenderId: "643973041720",
          appId: "1:643973041720:web:3c304e79d3d5a1d1f7a0cd",
          measurementId: "G-D212L0HR5B"
    });

	const messaging = firebase.messaging();
	messaging.setBackgroundMessageHandler(function(payload) {
    console.log(
        "[firebase-messaging-sw.js] Received background message ",
        payload,
    );
        
    const notificationTitle = "Background Message Title";
    const notificationOptions = {
        body: "Background Message body.",
        icon: "/itwonders-web-logo.png",
    };
  
    return self.registration.showNotification(
        notificationTitle,
        notificationOptions,
    );
});