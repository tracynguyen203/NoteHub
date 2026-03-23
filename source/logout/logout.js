window.addEventListener('load', startTheCountdown);

function startTheCountdown() {
    let countdown = 10;

    let id = setInterval(() => {
        countdown--;
        counter.innerHTML = countdown.toString();

        if(countdown == 0) {
            clearInterval(id);
            window.location.href = 'https://n0tehub.me/login/login.php';
        }
    }, 1000);
}