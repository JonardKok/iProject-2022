const timeFactorMs = 1000; //1000ms = 1s;
$(".countdownclock").each(function() {
    const $this = $(this);
    const countdown = $this.children(".countdown");
    const auctionButton = $this.children(".auctionButton");
    countdown.text(convertToTime(countdown.attr("data-time")));
    let changeTime = setInterval(function() {
            let newtime = countdown.attr("data-time") - 1;
            countdown.attr("data-time", newtime);
            countdown.text(convertToTime(newtime));
            if (countdown.attr("data-time") <= 0) {
                clearInterval(changeTime);
                countdown.text('Afgelopen!');
                auctionButton.text('Veiling voorbij');
                auctionButton.prop('disabled', true);
            }
        },
        timeFactorMs);
});

function convertToTime(time) {
    let days = Math.floor(time / 24 / 3600);
    let hours = Math.floor(time / 3600 % 24);
    let minutes = Math.floor(time % 3600 / 60);
    let seconds = Math.floor(time % 3600 % 60);
    let tijd = checkTimeAllowed((days), 'd ') + checkTimeAllowed(checkTimeLength(hours), 'h ') + checkTimeLength(minutes) + 'm ' + checkTimeLength(seconds) + 's';
    return String(tijd);
}

function checkTimeLength(time) {
    return ('' + time).length > 1 ? time : '0' + time;
}

function checkTimeAllowed(time, timeType) {
    return time > 0 ? time + timeType : '';
}