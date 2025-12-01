document.addEventListener("DOMContentLoaded", function () {

    fetch("/Eduqueue-Queueing-System/api/staff-api/admin/api_served_voided.php")
        .then(response => response.json())
        .then(data => {

            const dates = data.map(row => row.date);
            const served = data.map(row => row.served_count);
            const voided = data.map(row => row.voided_count);

            new Chart(document.getElementById("servedVoidedChart"), {
                type: "line",
                data: {
                    labels: dates,
                    datasets: [
                        {
                            label: "Served",
                            data: served,
                            borderWidth: 2,
                            borderColor: "green",
                            backgroundColor: "rgba(0,255,0,0.1)",
                            fill: true
                        },
                        {
                            label: "Voided",
                            data: voided,
                            borderWidth: 2,
                            borderColor: "red",
                            backgroundColor: "rgba(255,0,0,0.1)",
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });

        });
});
