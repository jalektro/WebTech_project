<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Temperature Graph</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <canvas id="temperatureChart"></canvas>

    <script>
        // Fetch temperature data from the server
        fetch("https://server-of-robert.pxl.bjth.xyz/api/v1/temperature.php")
          .then(response => response.json())
          .then(data => {
              // Extract temperatures from the data
              const temperatures = data.map(entry => entry.temperature);

              // Create labels (indices) for the x-axis
              const labels = Array.from({ length: temperatures.length }, (_, i) => i + 1);

              // Create a new Chart.js chart
              var ctx = document.getElementById('temperatureChart').getContext('2d');
              var myChart = new Chart(ctx, {
                  type: 'line',
                  data: {
                      labels: labels,
                      datasets: [{
                          label: 'Temperature Readings',
                          data: temperatures,
                          borderColor: 'blue',
                          borderWidth: 1
                      }]
                  },
                  options: {
                      scales: {
                          y: {
                              title: {
                                  display: true,
                                  text: 'Temperature (°C)'
                              }
                          }
                      }
                  }
              });
          });
    </script>
 </body>
</html>
