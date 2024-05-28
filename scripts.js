// JavaScript to add interactivity

// Example: Display an alert on form submission
document.getElementById('contact-form').addEventListener('submit', function(event) {
    event.preventDefault();
    alert('Form submitted!');
});

// Example: Fetch and display data from an API (assuming an API endpoint is available)
async function fetchData() {
    try {
        let response = await fetch('/api/v1/temperature');
        let data = await response.json();
        let dataSection = document.getElementById('data');
        dataSection.innerHTML = `<h2>Data</h2><pre>${JSON.stringify(data, null, 2)}</pre>`;
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}

// Call fetchData when the Data section is clicked
document.getElementById('data').addEventListener('click', fetchData);
