function createDonutChart(canvasId, dataUrl, title) {
  fetch(dataUrl)
    .then(response => response.json())
    .then(result => {
      new Chart(canvasId, {
        type: "doughnut",
        data: {
          labels: result.labels,
          datasets: [{
            backgroundColor: ["#FFCC00", "#406882", "#F2E5D7", "#2F5D50", "#2B2B2B"],
            data: result.data,
            borderWidth: 10,
            hoverBorderWidth: 2,
          }]
        },
        options: {
          title: {
            display: true,
            text: title,
          },
        legend: {
      display: false, 
        }
        }
      });
    });
}

createDonutChart("hotelChart", "ADfetchhotelchart.php", "Most Selected Room Types");
createDonutChart("seatChart", "ADfetchseatschart.php", "Most Preferred Flight's Seats");
createDonutChart("bookingChart", "ADfetchbookingpchart.php", "User's Most Preferred Package Plans");


