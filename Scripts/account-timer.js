const timerElements = document.querySelectorAll(".account-panel__timer[data-remaining]");

const formatTime = (totalSeconds) => {
  const hours = Math.floor(totalSeconds / 3600);
  const minutes = Math.floor((totalSeconds % 3600) / 60);
  const seconds = totalSeconds % 60;

  const pad = (value) => String(value).padStart(2, "0");

  if (hours > 0) {
    return `${pad(hours)}:${pad(minutes)}:${pad(seconds)}`;
  }

  return `${pad(minutes)}:${pad(seconds)}`;
};

timerElements.forEach((element) => {
  let remaining = Number.parseInt(element.dataset.remaining || "0", 10);

  if (!Number.isFinite(remaining) || remaining <= 0) {
    element.textContent = "Cookie timer: Not set";
    return;
  }

  element.textContent = `Cookie timer: ${formatTime(remaining)}`;

  const intervalId = window.setInterval(() => {
    remaining -= 1;

    if (remaining <= 0) {
      window.clearInterval(intervalId);
      element.textContent = "Cookie timer: Not set";
      return;
    }

    element.textContent = `Cookie timer: ${formatTime(remaining)}`;
  }, 1000);
});
