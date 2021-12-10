function showHeader() {
    var body = document.querySelector('body');
        body.style.overflow = 'hidden';

    var header = document.getElementById('headerClick');
        header.style.transform = 'translate3d(0, 0, 0)';
}

function closeHeader() {
    var body = document.querySelector('body');
        body.style.overflow = 'visible';

    var header = document.getElementById('headerClick');
        header.style.transform = 'translate3d(-100%, 0, 0)';
}