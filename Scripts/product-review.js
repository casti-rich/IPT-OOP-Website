const stars = document.querySelectorAll(".star");
const rating = document.getElementById("rating");
const reviewText = document.getElementById("review");
const reviewImage = document.getElementById("review-image");
const submitBtn = document.getElementById("submit");
const reviewsContainer = document.getElementById("reviews");

// Update the rating display and star styles when the user picks a score.
stars.forEach((star) => {
	star.addEventListener("click", () => {
		const value = parseInt(star.getAttribute("data-value"));
		rating.innerText = value;

		// Remove all existing classes from stars
		stars.forEach((s) => s.classList.remove("one", 
												"two", 
												"three", 
												"four", 
												"five"));

		// Add the appropriate class to 
		// each star based on the selected star's value
		stars.forEach((s, index) => {
			if (index < value) {
				s.classList.add(getStarColorClass(value));
			}
		});

		// Remove "selected" class from all stars
		stars.forEach((s) => s.classList.remove("selected"));
		// Add "selected" class to the clicked star
		star.classList.add("selected");
	});
});

// Send the review data to PHP, then append the saved review to the page.
submitBtn.addEventListener("click", async () => {
	const review = reviewText.value.trim();
	const userRating = parseInt(rating.innerText, 10);

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
				// Parse JSON manually so we can report raw server output if it fails.
				data = responseText ? JSON.parse(responseText) : {};
			} catch {
				throw new Error(responseText || "Server returned an invalid response.");
			}

			if (!response.ok) {
				alert(data.error || "Unable to submit review.");
				return;
			}
			const reviewElement = document.createElement("div");
			reviewElement.classList.add("review");

			const ratingParagraph = document.createElement("p");
			ratingParagraph.innerHTML = `<strong>Rating: ${data.rating}/5</strong>`;

			const reviewParagraph = document.createElement("p");
			reviewParagraph.textContent = data.review;

			const timestampParagraph = document.createElement("p");
			timestampParagraph.style.fontSize = "0.85em";
			timestampParagraph.style.color = "#888";
			timestampParagraph.style.marginTop = "8px";
			timestampParagraph.textContent = `Submitted on: ${data.timestamp}`;

			reviewElement.appendChild(ratingParagraph);
			reviewElement.appendChild(reviewParagraph);

			if (data.imageUrl) {
				const imageElement = document.createElement("img");
				imageElement.src = data.imageUrl;
				imageElement.alt = "Uploaded review image";
				imageElement.style.maxWidth = "100%";
				imageElement.style.height = "auto";
				imageElement.style.marginTop = "12px";
				reviewElement.appendChild(imageElement);
			}

			reviewElement.appendChild(timestampParagraph);
			reviewsContainer.appendChild(reviewElement);

			// Clear the form state after a successful submission.
			reviewText.value = "";
			if (reviewImage) {
				reviewImage.value = "";
			}
			rating.innerText = "0";
			stars.forEach((s) => s.classList.remove("one", 
												"two", 
												"three", 
												"four", 
												"five", 
												"selected"));
		} catch (error) {
			alert(error.message);
		}
	}
});

// Map each rating value to the CSS class used for its color state.
function getStarColorClass(value) {
	switch (value) {
		case 1:
			return "one";
		case 2:
			return "two";
		case 3:
			return "three";
		case 4:
			return "four";
		case 5:
			return "five";
		default:
			return "";
	}
}