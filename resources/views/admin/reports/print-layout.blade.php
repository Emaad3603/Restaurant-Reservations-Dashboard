<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Report' }} - {{ config('app.name') }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 1cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000;
        }
        .header h1 {
            font-size: 24pt;
            margin: 0;
            padding: 0;
        }
        .header p {
            font-size: 12pt;
            margin: 5px 0;
            color: #666;
        }
        .report-info {
            margin-bottom: 20px;
            font-size: 11pt;
        }
        .report-info p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-size: 11pt;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #000;
            font-size: 10pt;
            color: #666;
            position: running(footer);
        }
        .chart-container {
            margin: 20px 0;
            page-break-inside: avoid;
        }
        .metrics {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .metric-card {
            border: 1px solid #000;
            padding: 10px;
            width: 23%;
            text-align: center;
        }
        .metric-card h3 {
            margin: 0;
            font-size: 14pt;
            color: #666;
        }
        .metric-card .value {
            font-size: 20pt;
            font-weight: bold;
            margin: 10px 0;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10pt;
            font-weight: bold;
        }
        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-canceled {
            background-color: #f8d7da;
            color: #721c24;
        }
        @page {
            @bottom-center {
                content: element(footer);
            }
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                counter-reset: page;
            }
            .footer {
                counter-increment: page;
            }
            .footer::after {
                content: "Page " counter(page) " of " counter(pages);
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title ?? 'Report' }}</h1>
        <p>{{ config('app.name') }}</p>
        <p>Generated on: {{ now()->format('F j, Y g:i A') }}</p>
    </div>

    <div class="report-info">
        @if(isset($filters))
            <p><strong>Filters Applied:</strong></p>
            <ul>
                @foreach($filters as $key => $value)
                    @if($value)
                        <li>{{ ucfirst(str_replace('_', ' ', $key)) }}: {{ $value }}</li>
                    @endif
                @endforeach
            </ul>
        @endif
    </div>

    @yield('content')

    <div class="footer">
        <p>This is a computer-generated report. No signature is required.</p>
    </div>
</body>
</html> 