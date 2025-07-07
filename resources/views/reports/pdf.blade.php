<!DOCTYPE html>
<html>
<head>
    <title>{{ $report->title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; line-height: 1.6; }
        h1 { color: #4a4a4a; }
        .section { margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>{{ $report->title }}</h1>
    <p><strong>Type:</strong> {{ ucfirst($report->type) }}</p>
    <p><strong>Target Role:</strong> {{ ucfirst($report->target_role) }}</p>
    <div class="section">
        <h3>Summary:</h3>
        <p>{{ $report->summary }}</p>
    </div>
</body>
</html>
