// Function to fetch live queue data
async function fetchQueueData() {
  try {
    const response = await fetch("../../api/staff-api/fetch_queue_data.php");
    const data = await response.json();
    updateQueueTable(data.queues);
  } catch (error) {
    console.error("Error fetching queue data:", error);
  }
}

// Function to update the queue table dynamically
function updateQueueTable(queues) {
  const tbody = document.querySelector("section:nth-of-type(1) table tbody");
  tbody.innerHTML = "";

  queues.forEach((queue) => {
    const row = document.createElement("tr");

    row.innerHTML = `
            <td>${queue.queue_number}</td>
            <td>${queue.student_name}</td>
            <td>${queue.status}</td>
            <td>${queue.time_in}</td>
        `;

    tbody.appendChild(row);
  });
}

// Initialize live updates
function initializeDashboard() {
  fetchQueueData();

  // Refresh data every 10 seconds
  setInterval(() => {
    fetchQueueData();
  }, 10000);
}

// Run the dashboard initialization on page load
document.addEventListener("DOMContentLoaded", initializeDashboard);
