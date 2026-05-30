const STAR_CLASSES = ["one", "two", "three", "four", "five"];

const stars = Array.from(document.querySelectorAll(".star"));
const rating = document.getElementById("rating");
const reviewText = document.getElementById("review");
const reviewImage = document.getElementById("review-image");
const submitBtn = document.getElementById("submit");
const reviewsContainer = document.getElementById("reviews");

if (!stars.length || !rating || !reviewText || !submitBtn || !reviewsContainer) {
	throw new Error("Review widgets are missing from the page.");
}

// Apply the star color classes based on the selected rating.
const updateStars = (value) => {
	const className = STAR_CLASSES[value - 1] || "";
	stars.forEach((star, index) => {
		star.classList.remove(...STAR_CLASSES);
		if (className && index < value) {
			star.classList.add(className);
		}
	});
};

// Sync the numeric rating display and star visuals.
const setRating = (value) => {
	rating.textContent = String(value);
	updateStars(value);
};

// Build a review card using the CSS classes defined for the review panel.
const buildReviewNode = (data) => {
	const reviewElement = document.createElement("div");
	reviewElement.classList.add("review");

	const ratingParagraph = document.createElement("p");
	ratingParagraph.classList.add("review-rating");
	ratingParagraph.innerHTML = `<strong>Rating: ${data.rating}/5</strong>`;

	const reviewParagraph = document.createElement("p");
	reviewParagraph.classList.add("review-text");
	reviewParagraph.textContent = data.review;

	const timestampParagraph = document.createElement("p");
	timestampParagraph.classList.add("review-timestamp");
	timestampParagraph.textContent = `Submitted on: ${data.timestamp}`;

	reviewElement.appendChild(ratingParagraph);
	reviewElement.appendChild(reviewParagraph);

	if (data.imageUrl) {
		const imageElement = document.createElement("img");
		imageElement.classList.add("review-image");
		imageElement.src = data.imageUrl;
		imageElement.alt = "Uploaded review image";
		reviewElement.appendChild(imageElement);
	}

	reviewElement.appendChild(timestampParagraph);
	return reviewElement;
};

// Update the rating display and star styles when the user picks a score.

stars.forEach((star) => {
	star.addEventListener("click", () => {
		const value = parseInt(star.getAttribute("data-value"), 10) || 0;
		setRating(value);
	});
});

// Send the review data to PHP, then append the saved review to the page.
submitBtn.addEventListener("click", async () => {
	// Validate input and post the review form data to the server.
	const review = reviewText.value.trim();
	const userRating = parseInt(rating.textContent, 10);

	if (!userRating || !review) {
		alert(
"Please select a rating and provide a review before submitting."
			);
		return;
	}

	if (userRating > 0) {
		const formData = new FormData();
		formData.append("review", review);
		formData.append("rating", String(userRating));

		// Include the optional image only when the user selected one.
		if (reviewImage && reviewImage.files[0]) {
			formData.append("review_image", reviewImage.files[0]);
		}

		try {
			const response = await fetch("submit-review.php", {
				method: "POST",
				body: formData
			});
			const responseText = await response.text();
			let data;

			try {
				// Parse JSON manually to report raw server output if it fails.
				data = responseText ? JSON.parse(responseText) : {};
			} catch {
				throw new Error(responseText || "Server returned an invalid response.");
			}

			if (!response.ok) {
				alert(data.error || "Unable to submit review.");
				return;
			}
			reviewsContainer.appendChild(buildReviewNode(data));

			// Clear the form state after a successful submission.
			reviewText.value = "";
			if (reviewImage) {
				reviewImage.value = "";
			}
			setRating(0);
		} catch (error) {
			alert(error.message);
		}
	}
});