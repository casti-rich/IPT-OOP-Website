document.addEventListener('DOMContentLoaded', function () {

    function updateTimers() {

        document.querySelectorAll('.rental-timer').forEach(timer => {

            const endTime = new Date(timer.dataset.end).getTime();
            const now = Date.now();

            const remaining = endTime - now;

            if (remaining <= 0) {
                timer.textContent = "Rental period expired";
                return;
            }

            const days = Math.floor(remaining / (1000 * 60 * 60 * 24));

            const hours = Math.floor(
                (remaining % (1000 * 60 * 60 * 24))
                / (1000 * 60 * 60)
            );

            const minutes = Math.floor(
                (remaining % (1000 * 60 * 60))
                / (1000 * 60)
            );

            const seconds = Math.floor(
                (remaining % (1000 * 60))
                / 1000
            );

            timer.textContent =
                `${days}d ${hours}h ${minutes}m ${seconds}s remaining`;
        });
    }

    updateTimers();
    setInterval(updateTimers, 1000);

});