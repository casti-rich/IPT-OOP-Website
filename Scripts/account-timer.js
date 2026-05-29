// Resolve timer elements and modal controls once on load.
const timerElements = document.querySelectorAll(".store-navbar__timer[data-remaining]");
const expiredModal = document.querySelector(".session-expired-modal");
const expiredModalButton = expiredModal?.querySelector(".session-expired-modal__button");
const loginUrl = expiredModal?.dataset.loginUrl || "login.php";
let modalShown = false;

// Show a one-time modal when the countdown reaches zero.
const showExpiredModal = () => {
  if (!expiredModal || modalShown) {
    return;
  }

  modalShown = true;
  expiredModal.classList.add("is-visible");
  expiredModal.setAttribute("aria-hidden", "false");
  document.body.classList.add("modal-open");
};

// Redirect to login when the user acknowledges the modal.
if (expiredModalButton) {
  expiredModalButton.addEventListener("click", () => {
    window.location.href = loginUrl;
  });
}

// Convert seconds into a readable time label.
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

// Run a per-element countdown based on the remaining seconds.
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
      showExpiredModal();
      return;
    }

    element.textContent = `Cookie timer: ${formatTime(remaining)}`;
  }, 1000);
});
