<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Historical Data for {{ $symbol }}</title>
</head>
<body>
    <h2>Historical Quotes for {{ $symbol }}</h2>
    <a href="{{ route('display.form') }}">Go to Form</a>
    @if(!empty($quotes))
        <table border='1'>
            <tr>
                <th>Date</th>
                <th>Open</th>
                <th>High</th>
                <th>Low</th>
                <th>Close</th>
                <th>Volume</th>
            </tr>
            @foreach ($quotes as $quote)
                <tr>
                    <td>{{ $quote['date'] }}</td>
                    <td>{{ $quote['open'] }}</td>
                    <td>{{ $quote['high'] }}</td>
                    <td>{{ $quote['low'] }}</td>
                    <td>{{ $quote['close'] }}</td>
                    <td>{{ $quote['volume'] }}</td>
                </tr>
            @endforeach
        </table>
    @else
        <p>No historical data found for {{ $symbol }}</p>
    @endif
</body>
</html>