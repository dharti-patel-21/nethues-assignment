<!DOCTYPE html>
<html>
<head>
    <title>Simple Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script>
        $(function() {
            $("#start_date").datepicker({ dateFormat: 'yy-mm-dd' });
            $("#end_date").datepicker({ dateFormat: 'yy-mm-dd' });

            $.validator.addMethod("greaterThan", function(value, element, param) {
                var startDate = new Date($(param).val());
                var endDate = new Date(value);
                return this.optional(element) || endDate >= startDate;
            }, "End Date must be greater than or equal to Start Date");

            $.validator.addMethod("lessOrEqualToToday", function(value, element) {
                var inputDate = new Date(value);
                var today = new Date();
                return this.optional(element) || inputDate <= today;
            }, "Date must be less than or equal to today");


            $("form").validate({
                rules: {
                    symbol: "required",
                    start_date: {
                        required: true,
                        date: true,
                        lessOrEqualToToday: true
                    },
                    end_date: {
                        required: true,
                        date: true,
                        greaterThan: "#start_date",
                        lessOrEqualToToday: true
                    },
                    email: {
                        required: true,
                        email: true
                    }
                },
                messages: {
                    symbol: "Please select a company symbol",
                    start_date: {
                        required: "Please enter a start date",
                        date: "Please enter a valid date",
                        lessOrEqualToToday: "Start Date must be less than or equal to today"
                    },
                    end_date: {
                        required: "Please enter an end date",
                        date: "Please enter a valid date",
                        greaterThan: "End Date must be greater than or equal to Start Date",
                        lessOrEqualToToday: "End Date must be less than or equal to today"
                    },
                    email: "Please enter a valid email address"
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
</head>
<body>
<div class="container mt-5">
        <h1 class="mb-4">Simple Form</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="/process/form" method="post">
            @csrf
            <div class="form-group">
                <label for="symbol">Company Symbol:</label>
                <select name="symbol" id="symbol" class="form-control" required>
                    @foreach($symbols as $symbol)
                        <option value="{{ $symbol->Symbol }}">{{ $symbol->Symbol }} - {{ $symbol->{'Company Name'} }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="text" id="start_date" name="start_date" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="text" id="end_date" name="end_date" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>