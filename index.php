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
                <li><a href="#about">Temperature measurement</a></li>
                <li><a href="#data">Data</a></li> <!-- Link to the Data section -->

            </ul>
        </nav>
    </header>
    <main>
        <section id="home">
            <h2>Welcome</h2>
            <p>Hello, I'm Robert a student at PXL. You can find my github repo here: </p>
            <button id="githubpage">Github Repo</button>
            <video width= "600" controls>
                <source src="filmpje.mp4" type = "video/mp4">
            </video>
        </section>
        <section id="about">
            <h2>Temperature measurement</h2>
            <p>I will measure the temp with a rasp Pico and show this here in a table and a graph.</p>
        </section>
        <section id="data">
            <h2>Data</h2>
            <div id="data-container">
                <!-- Temperature data table will be populated here by JavaScript -->
            </div>
                        <!-- Add a button to open a new tab -->
                        <button id="viewGraphBtn">View Graph</button>
                        <button id="exportCsvBtn">Export CSV</button>
        </section>

    </main>

    <script>
        // Add event listener to the GitHub button
        document.getElementById("githubpage").addEventListener("click", function() {
            window.open("https://github.com/jalektro/WebTech_project", "_blank");
        });

        // Add event listener to the CSV export button
        document.getElementById("exportCsvBtn").addEventListener("click", function() {
            var csvExportUrl = "https://server-of-robert.pxl.bjth.xyz/api/v1/temperature.php?export=csv";
            var anchor = document.createElement("a");
            anchor.href = csvExportUrl;
            anchor.download = "temperature_data.csv";
            anchor.style.display = "none";
            document.body.appendChild(anchor);
            anchor.click();
            document.body.removeChild(anchor);
        });

        // Add event listener to the View Graph button
        document.getElementById("viewGraphBtn").addEventListener("click", function() {
            var newWindow = window.open();
            var graphPageUrl = "graph.php";
            newWindow.location.href = graphPageUrl;
        });

function deleteData(id) {
    var xhr = new XMLHttpRequest();
    xhr.open("DELETE", "https://server-of-robert.pxl.bjth.xyz/api/v1/temperature.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                console.log("Record deleted successfully");
                fetchData(displayData); // Refresh data after deletion
            } else {
                console.error("Error deleting record: " + xhr.statusText);
            }
        }
    };
    xhr.send("id=" + id);
}

function fetchData(callback) {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "https://server-of-robert.pxl.bjth.xyz/api/v1/temperature.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var data = JSON.parse(xhr.responseText);
                callback(data);
            } else {
                console.error("Error fetching data: " + xhr.statusText);
            }
        }
    };
    xhr.send();
}

function displayData(data) {
    var container = document.getElementById("data-container");
    var html = "<h2>Last 20 Temperature Readings</h2>";
    html += "<table><tr><th>ID</th><th>Temperature</th><th>Timestamp</th><th>Actions</th></tr>";
    data.forEach(function(item) {
        html += "<tr>";
        html += "<td>" + item.id + "</td>";
        html += "<td>" + item.temperature + "</td>";
        html += "<td>" + item.timestamp + "</td>";
        html += "<td><button onclick='deleteData(" + item.id + ")'>Delete</button></td>";
        html += "</tr>";
    });
    html += "</table>";
    container.innerHTML = html;
}

document.addEventListener("DOMContentLoaded", function() {
    fetchData(displayData);
    setInterval(function() {
        fetchData(displayData);
    }, 5000);
});
    </script>

</body>
</html>
