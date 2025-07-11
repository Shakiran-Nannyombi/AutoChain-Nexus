<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supply Chain Challenges</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Sticky Navigation */
        .sticky-nav {
            position: sticky;
            top: 0;
            z-index: 1000;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
        }

        .nav-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        .nav-logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2a4365;
        }

        .nav-links {
            display: flex;
            gap: 25px;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: #4a5568;
            font-weight: 500;
        }

        .nav-button {
            background-color: #2a4365;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
        }

        /* Sticky Header */
        .sticky-header {
            position: sticky;
            top: 68px; /* Height of the navigation bar */
            z-index: 999;
            background-color: #f7fafc;
            padding: 30px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            text-align: center;
        }

        .sticky-header h1 {
            font-size: 2rem;
            color: #2d3748;
            margin-bottom: 10px;
        }

        .sticky-header p {
            color: #4a5568;
            font-size: 1.1rem;
        }

        /* Content Section */
        .content {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .options-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .option-card {
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
            transition: transform 0.3s ease;
            cursor: pointer;
            text-align: center;
        }

        .option-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        .option-card h2 {
            color: #2a4365;
            margin: 0;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <!-- Sticky Navigation Bar -->
    <nav class="sticky-nav">
        <div class="nav-content">
            <div class="nav-logo">Manhattan Solutions</div>
            <div class="nav-links">
                <a href="#">Products</a>
                <a href="#">Industries</a>
                <a href="#">Resources</a>
                <button class="nav-button">Contact Us</button>
            </div>
        </div>
    </nav>

    <!-- Sticky Heading Section -->
    <header class="sticky-header">
        <div class="header-content">
            <h1>What Are Your Biggest Supply Chain Challenges?</h1>
            <p>Select an option below to see how Manhattan solutions can help your business.</p>
        </div>
    </header>

    <!-- Content Section -->
    <main class="content">
        <div class="options-grid">
            <div class="option-card">
                <h2>Unified Business Planning</h2>
            </div>
            <div class="option-card">
                <h2>Workforce Retention</h2>
            </div>
            <div class="option-card">
                <h2>Transportation & Distribution Unification</h2>
            </div>
            <div class="option-card">
                <h2>Cloud Technology Transformation</h2>
            </div>
        </div>
    </main>
</body>
</html>