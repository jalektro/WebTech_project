<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roberts frontpage</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Welcome to Robert's Website/Server</h1>
        <nav>
            <ul>
                <li><a href="#home">Home</a></li>
                <li><a href="#about">What will be on the site</a></li>
                <li><a href="#data">Data</a></li> <!-- Link to the Data section -->
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section id="home">
            <h2>Welcome</h2>
            <p>This is just a try out to get PHP nicely working on the site.</p>
        </section>
        <section id="about">
            <h2>temperature measerment</h2>
            <p>I will measure the temp with a rasp Pico and show this here in a table r graph.</p>
        </section>
        <section id="data">
            <h2>Data</h2>
            <div id="data-container">
                <!-- Temperature data table will be populated here by JavaScript -->
            </div>
                        <!-- Add a button to open a new tab -->
                        <button id="viewGraphBtn">View Graph</button>
        </section>
        <section id="contact">
            <h2>Contact</h2>
            <form id="contact-form">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email">
                <input type="submit" value="Submit">
            </form>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Robert's Website/Server</p>
    </footer>
    <script>
        // Add event listener to the button
        document.getElementById("viewGraphBtn").addEventListener("click", function() {
            // Open a new tab
            var newWindow = window.open();

            // Construct the URL for the graph page
            var graphPageUrl = "graph.php";

            // Redirect the new tab to the graph page
            newWindow.location.href = graphPageUrl;
        });
    </script>
    <script>
        function fetchData(callback) {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "https://server-of-robert.pxl.bjth.xyz/api/v1/temperature.php");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var data = JSON.parse(xhr.responseText); // Parse the JSON response
                        callback(data);
                    } else {
                        console.error("Error fetching data");
                    }
                }
            };
            xhr.send();
        }

        document.addEventListener("DOMContentLoaded", function() {
            function displayData(data) {
                var container = document.getElementById("data-container");
                var html = "<h2>Last 20 Temperature Readings</h2>";
                html += "<table><tr><th>ID</th><th>Temperature</th><th>Timestamp</th></tr>";

                // Loop through each data object and format it
                for (var i = 0; i < data.length; i++) {
                    var id = data[i].id;
                    var temperature = data[i].temperature;
                    var timestamp = data[i].timestamp;
                    html += "<tr><td>" + id + "</td><td>" + temperature + "</td><td>" + timestamp + "</td></tr>";
                }

                html += "</table>";
                container.innerHTML = html;
            }

            // Fetch and display data initially and then every 5 seconds
            fetchData(displayData);
            setInterval(function () {
                fetchData(displayData);
            }, 5000);
        });
    </script>
</body>
</html>
