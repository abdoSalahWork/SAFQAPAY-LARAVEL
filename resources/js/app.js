require('./bootstrap');
window.Echo.channel('notification').listen('UserNotificationEvent', function(event) {
    console.log(event);
})
