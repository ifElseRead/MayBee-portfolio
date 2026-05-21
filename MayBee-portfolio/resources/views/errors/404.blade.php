<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Hive Not Found</title>
    <style>
        body {
            margin: 0;
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f8fafc;
            color: #1a1a1a;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
        }

        .container {
            max-width: 32rem;
            padding: 2rem;
        }

        h1 {
            font-size: 8rem;
            font-weight: 800;
            margin: 0;
            color: #e2e8f0;
            line-height: 1;
        }

        h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-top: -2.5rem;
            margin-bottom: 1rem;
        }

        p {
            color: #475569;
            margin-bottom: 2rem;
            line-height: 1.5;
            font-size: 1.125rem;
        }

        a {
            display: inline-block;
            background-color: #facc15;
            color: #020617;
            padding: 0.75rem 1.5rem;
            border-radius: 9999px;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.2s ease;
        }

        a:hover {
            background-color: #eab308;
            transform: translateY(-1px);
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>404</h1>
        <h2>Hive Not Found 🐝</h2>
        <p>It seems this page has buzzed off. Even my 60,000 debugging bees couldn't track down the URL you're looking
            for. Let's get you back to the codebase.</p>
        <a href="{{ url('/') }}">Fly Back Home ↗</a>
    </div>
</body>

</html>
