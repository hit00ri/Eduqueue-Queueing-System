// Character count for comments
const commentsTextarea = document.getElementById("comments");
const charCount = document.getElementById("charCount");
const charCountContainer = document.querySelector(".character-count");

commentsTextarea.addEventListener("input", function () {
  const length = this.value.length;
  charCount.textContent = length;

  if (length > 450) {
    charCountContainer.classList.add("warning");
  } else {
    charCountContainer.classList.remove("warning");
  }
});

// Star rating interaction
const stars = document.querySelectorAll(".star-rating input");
stars.forEach((star) => {
  star.addEventListener("change", function () {
    // Optional: Add visual feedback when a star is selected
    const labels = document.querySelectorAll(".star-rating label");
    labels.forEach((label) => (label.style.transform = "scale(1)"));

    const selectedLabel = document.querySelector(`label[for="${this.id}"]`);
    if (selectedLabel) {
      selectedLabel.style.transform = "scale(1.2)";
    }
  });
});

// Form validation
document.querySelector("form").addEventListener("submit", function (e) {
  const rating = document.querySelector('input[name="rating"]:checked');
  if (!rating) {
    e.preventDefault();
    alert("Please select a rating before submitting.");
    return false;
  }
});
