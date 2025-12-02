// ===============================================
// LIVE QUEUE DISPLAY JAVASCRIPT
// ===============================================

// -------------------------------
// Real-Time Clock
// -------------------------------
function updateClock() {
    const now = new Date();

    const timeString =
        now.getHours().toString().padStart(2, "0") + ":" +
        now.getMinutes().toString().padStart(2, "0") + ":" +
        now.getSeconds().toString().padStart(2, "0");

    // Update all elements with class "current-time"
    document.querySelectorAll(".current-time").forEach(el => {
        el.textContent = timeString;
    });
}

// Initialize + run every second
setInterval(updateClock, 1000);
updateClock();

// -------------------------------
// Queue Number Glow Effects
// -------------------------------
document.addEventListener("DOMContentLoaded", () => {
    const queueNumbers = document.querySelectorAll(".queue-number, .next-queue-number");

    queueNumbers.forEach(number => {
        if (number.textContent.trim() !== "---") {
            number.style.color = "#666";
            number.style.textShadow = "0 0 15px rgba(255, 255, 255, 0.8)";
        }
    });
});

// -------------------------------
// Waiting List Toggle (click the Waiting stat card)
// -------------------------------
document.addEventListener("DOMContentLoaded", () => {
    const waitingStatCard = document.getElementById("waiting-stat-card");
    const waitingList = document.getElementById("waiting-list");

    if (waitingStatCard && waitingList) {
        waitingStatCard.style.cursor = "pointer"; // Add pointer cursor

        waitingStatCard.addEventListener("click", () => {
            if (waitingList.style.display === "none" || waitingList.style.display === "") {
                waitingList.style.display = "block";
            } else {
                waitingList.style.display = "none";
            }
        });
    }
});

// // ---------------------------------------------
// // SERVED STAT CARD TOGGLE FOR RECENTLY SERVED
// // ---------------------------------------------
// document.addEventListener("DOMContentLoaded", () => {
//     const servedCard = document.getElementById("served-stat-card");
//     const recentList = document.getElementById("recent-list");

//     if (servedCard && recentList) {
//         servedCard.addEventListener("click", () => {
//             if (recentList.style.display === "none" || recentList.style.display === "") {
//                 recentList.style.display = "block";
//             } else {
//                 recentList.style.display = "none";
//             }
//         });
//     }
// });

// // ---------------------------------------------
// // RECENTLY SERVED Toggle
// // ---------------------------------------------
// document.addEventListener("DOMContentLoaded", () => {
//     const recentToggle = document.getElementById("recent-toggle");
//     const recentList = document.getElementById("recent-list");

//     if (recentToggle && recentList) {
//         recentToggle.addEventListener("click", () => {
//             if (recentList.style.display === "none" || recentList.style.display === "") {
//                 recentList.style.display = "block";
//             } else {
//                 recentList.style.display = "none";
//             }
//         });
//     }
// });

document.addEventListener("DOMContentLoaded", () => {
    const servedCard = document.getElementById("served-stat-card");
    const recentToggle = document.getElementById("recent-toggle");
    const recentList = document.getElementById("recent-list");

    // Always hide Recently Served on page load
    if (recentList) {
        recentList.style.display = "none";
    }

    // Function to toggle Recently Served list
    function toggleRecent() {
        if (recentList.style.display === "none" || recentList.style.display === "") {
            recentList.style.display = "block";
        } else {
            recentList.style.display = "none";
        }
    }

    // Click on Served stat card
    if (servedCard) {
        servedCard.addEventListener("click", toggleRecent);
    }

    // Click on RECENTLY SERVED title
    if (recentToggle) {
        recentToggle.addEventListener("click", toggleRecent);
    }
});
