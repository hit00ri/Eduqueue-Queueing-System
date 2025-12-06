// Show/hide other purpose field based on checkbox
document
  .getElementById("others-checkbox")
  .addEventListener("change", function () {
    document.getElementById("other-purpose").style.display = this.checked
      ? "block"
      : "none";
  });

// Initialize display on page load
document.addEventListener("DOMContentLoaded", function () {
  document.getElementById("other-purpose").style.display =
    document.getElementById("others-checkbox").checked ? "block" : "none";
});
