$('body').show();
NProgress.start();
setTimeout(function() {
    NProgress.done();
    $('.fade').removeClass('out');
}, 1000);