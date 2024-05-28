<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Robert's website/server</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Welcome to Robert's Website/Server</h1>
        <nav>
            <ul>
                <li><a href="#home">Home</a></li>
                <li><a href="#about">what will be on the site</a></li>
                <li><a href="#data">Data</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section id="home">
            <h2>Welcome</h2>
            <p>This is just a try out to get PHP nice working on the site.</p>
        </section>
        <section id="about">
            <h2>What will be on this site</h2>
            <p>I will try to built u a database, don't know with what yet having no inspiration at this point.</p>
        </section>
        <section id="data">
            <h2>Data</h2>
            <p>Dynamic data visualization will be here.</p>
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
    <script src="script.js"></script>
</body>
</html>
