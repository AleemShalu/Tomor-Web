<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        /* Table Styling */
        table {
            border-collapse: collapse;
            width: 100%;
            font-family: Arial, sans-serif;
        }

        th, td {
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Sorting Arrows */
        .sort-arrow {
            margin-left: 5px;
        }

        .sort-arrow:before {
            content: '\2191';
            font-size: 10px;
            color: #999;
            margin-right: 2px;
            opacity: 0.6;
        }

        .sort-arrow.asc:before {
            content: '\2191';
            opacity: 1;
        }

        .sort-arrow.desc:before {
            content: '\2193';
            opacity: 1;
        }
    </style>

    <title>Your Page Title</title>
</head>
<body>
<table id="user-table">
    <thead>
    <tr>
        <th onclick="sortTable(0)">ID<span class="sort-arrow"></span></th>
        <th onclick="sortTable(1)">Name<span class="sort-arrow"></span></th>
        <th onclick="sortTable(2)">Email<span class="sort-arrow"></span></th>
        <th onclick="sortTable(3)">Store ID<span class="sort-arrow"></span></th>
        <th onclick="sortTable(4)">Created At<span class="sort-arrow"></span></th>
        <th onclick="sortTable(5)">Updated At<span class="sort-arrow"></span></th>
    </tr>
    </thead>
    <tbody>
    @foreach($export->collection() as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->store_id }}</td>
            <td>{{ $user->created_at }}</td>
            <td>{{ $user->updated_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<script>
    function sortTable(columnIndex) {
        var table = document.getElementById("user-table");
        var rows = Array.from(table.getElementsByTagName("tr"));
        var asc = true;

        rows.shift(); // Remove the table header row

        // Determine if the column is currently sorted in ascending order
        if (table.rows[0].cells[columnIndex].classList.contains("asc")) {
            asc = false;
        }

        // Sort the rows based on the column values
        rows.sort(function(a, b) {
            var cellA = a.cells[columnIndex].innerText.toLowerCase();
            var cellB = b.cells[columnIndex].innerText.toLowerCase();

            if (cellA < cellB) {
                return asc ? -1 : 1;
            } else if (cellA > cellB) {
                return asc ? 1 : -1;
            } else {
                return 0;
            }
        });

        // Remove existing sorting classes and update the arrow direction
        for (var i = 0; i < table.rows[0].cells.length; i++) {
            table.rows[0].cells[i].classList.remove("asc", "desc");

            if (i === columnIndex) {
                table.rows[0].cells[i].classList.add(asc ? "asc" : "desc");
            }
        }

        // Re-append the sorted rows to the table
        for (var j = 0; j < rows.length; j++) {
            table.tBodies[0].appendChild(rows[j]);
        }
    }
</script>
</body>
</html>
