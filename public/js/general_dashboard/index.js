window.onload = function () {
    console.log("onloade", projects);
    //Reference the DropDownList.
    var ddlYears = document.getElementById("ddlYears");

    //Determine the Current Year.
    var currentYear = new Date().getFullYear();

    //Loop and add the Year values to DropDownList.
    for (var i = 2022; i <= 2040; i++) {
        var option = document.createElement("OPTION");
        option.innerHTML = i;
        option.value = i;
        ddlYears.appendChild(option);
    }
    ddlYears.value = currentYear;
};

$(".fa-angle-down").click(function () {
    console.log($(this).parents().children(":first-child"));
    $(this).parents().children(":first-child").toggleClass("active");
});

document.addEventListener("DOMContentLoaded", function () {
    var options = {
        chart: {
            type: "bar",
            fontFamily: "inherit",
            height: 540,
            parentHeightOffset: 0,
            toolbar: {
                show: false,
            },
            animations: {
                enabled: false,
            },
            stacked: true,
        },
        plotOptions: {
            bar: {
                columnWidth: "50%",
            },
        },
        dataLabels: {
            enabled: false,
        },
        fill: {
            opacity: 6,
        },
        series: [
            {
                name: "Actual Units",
                data: unitsStatistic[1],
            },
            {
                name: "Hold Units",
                data: unitsStatistic[0],
            },
        ],
        grid: {
            padding: {
                top: -20,
                right: 0,
                left: -4,
                bottom: -4,
            },
            strokeDashArray: 4,
        },
        xaxis: {
            labels: {
                padding: 0,
            },
            tooltip: {
                enabled: false,
            },
            axisBorder: {
                show: false,
            },
            type: "month",
        },
        yaxis: {
            labels: {
                padding: 10,
            },
            title: {
                text: "Units",
                offsetX: -7,
            },
        },
        labels: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec",

            // '2020-06-20', '2020-06-21', '2020-06-22', '2020-06-23', '2020-06-24', '2020-06-25', '2020-06-26'
        ],
        colors: ["#FBBCBC", "#B9D4ED"],
        legend: {
            show: true,
        },
        tooltip: {
            y: {
                formatter: " thousands",
            },
        },
    };

    var barchart = new ApexCharts(
        document.querySelector("#chart-completion-tasks-9"),
        options
    );
    barchart.render();

    document.getElementById("ddlYears").onchange = function () {
        var year = document.getElementById("ddlYears").value;
        var city_id = document.getElementById("city_id").value;

        $.ajax({
            type: "GET",
            dataType: "json",
            url: "/dashboard/general/get_unitsStatistic",
            data: {
                year: year,
                city_id: city_id,
            },
            success: function (response) {
                console.log(response.unitsStatistic);
                unitsStatistic = response.unitsStatistic;

                barchart.updateSeries([
                    {
                        name: "Actual Units",
                        data: unitsStatistic[1],
                    },
                    {
                        name: "Hold Units",
                        data: unitsStatistic[0],
                    },
                ]);
            },

            error: function (error) {
                console.log("error", error);
            },
        });
    };
});

$(document).ready(function () {
    var projectsArray = [];

    colors = ["#b1deb5", "#FBBCBC", "#FBE6BC", "#B9D4ED"];
    // colors = ['#f4bf98','#00adbb','#55705aa', '#b31e1a']

    index = 0;
    projects = Object.values(projects);

    console.log("projects", projects);
    for (var num = 0; num < projects.length; num++) {
        console.log("hi");
        var project = projects[num];
        // const todayDate =  new Date(project.start_date.split(/\D+/).reverse().map((v,i)=>+v-(i%2)));
        Start = project.start_date.split("-");
        startdate = new Date(Start[2] + "-" + Start[1] + "-" + Start[0]);
        End = project.end_date.split("-");
        enddate = new Date(End[2] + "-" + End[1] + "-" + End[0]);
        // enddate = enddate +1
        // var endDate = new Date(startDate.getTime() + day);

        var day = 60 * 60 * 24 * 1000;
        endDay = new Date(enddate.getTime() + day);
        // console.log();
        projectsArray.push({
            title: project.en_name,
            start: moment(startdate, "YYYY-MM-DD").format("YYYYMMDD"),
            end: moment(endDay, "YYYY-MM-DD").format("YYYYMMDD"),
            color: colors[index],
        });

        console.log(projectsArray);

        console.log(colors.length);
        if (index == colors.length - 1) index = 0;
        else index = index + 1;
    }

    $("#calendar").fullCalendar({
        // editable:true,
        header: {
            left: "prev title next",
            // center: 'prev title next',
            // right: 'month,basicWeek,basicDay'
        },
        events: "/full-calender",
        selectable: true,
        selectHelper: true,
        displayEventTime: false,
        height: 430,
        eventColor: "#52b2cf",
        changeMonth: true,
        changeYear: true,
        showOn: "both",
        events: projectsArray,
        fullDay: false,
    });

    console.log("projectsArray", projectsArray);

    // start the hack for the date picker
    $(".fc-header-toolbar .fc-left h2")
        .attr("id", "fc-datepicker-header")
        .before(
            '<input size="1" style="height: 0px; width:0px; border: 0px;" id="fc-datepicker" value="" />'
        );
    $("#fc-datepicker").MonthPicker({
        Button: false,
        OnAfterMenuClose: function () {
            var d = $("#fc-datepicker").MonthPicker("Validate");
            console.log(d);
            if (d !== null) {
                $("#calendar").fullCalendar("gotoDate", d);
            }
        },
    });
    $("#fc-datepicker-header").click(function () {
        $("#fc-datepicker").MonthPicker("Open");
    });

    console.log($("#calendar").events);

    const tabs = document.querySelectorAll(".nav-item");
    tabs.forEach((clickedTab) => {
        clickedTab.addEventListener("click", () => {
            tabs.forEach((tab) => {
                tab.classList.remove("active");
            });
            e.target.classList.add("active");
        });
    });

    new Chart("attent-chart", {
        type: "doughnut",
        plugins: [
            {
                afterDraw: (chart) => {
                    var needleValue = chart.config.data.datasets[0].needleValue;
                    var dataTotal = 100;

                    // var dataTotal = chart.config.data.datasets[0].data.reduce((a, b) => a + b, 0);
                    var angle =
                        Math.PI + (1 / dataTotal) * needleValue * Math.PI;
                    var ctx = chart.ctx;
                    var cw = chart.canvas.offsetWidth;
                    var ch = chart.canvas.offsetHeight;
                    var cx = cw / 2;
                    var cy = ch - 6;

                    (vm = chart._view),
                        // sA = vm.startAngle,
                        // eA = vm.endAngle,
                        (opts = chart.config.options);

                    ctx.translate(cx, cy);
                    ctx.rotate(angle);
                    ctx.beginPath();
                    ctx.moveTo(0, -3);
                    ctx.lineTo(ch - 20, 0);
                    ctx.lineTo(0, 3);
                    ctx.fillStyle = "rgb(0, 0, 0)";
                    ctx.fill();
                    ctx.rotate(-angle);
                    ctx.translate(-cx, -cy);
                    ctx.beginPath();
                    ctx.arc(cx, cy, 5, 0, Math.PI * 2);
                    ctx.fill();

                    //display in the center the total sum of all segments
                    //ctx.fillText('Total = ' + total, vm.x, vm.y - 20, 200);
                    ctx.fillText("75%", cx + 96, cy - 110, 200);
                    ctx.fillText("90%", cx + 150, cy - 50, 200);
                    ctx.fillText("0%", cx - 150, cy);
                    ctx.fillText("100%", cx + 150, cy);
                },
            },
        ],
        data: {
            datasets: [
                {
                    data: [0, 75, 15, 10],
                    needleValue: attendancePersentage,
                    backgroundColor: ["#b1deb5", "#FBBCBC", "#FBE6BC"],
                },
            ],
        },
        options: {
            responsive: true,
            aspectRatio: 2,
            layout: {
                padding: {
                    bottom: 3,
                },
            },
            rotation: -90,
            circumference: 180,
            cutout: "70%",
            total: 100,
            legend: {
                // display: true
            },
            animation: {
                animateRotate: false,
                animateScale: true,
            },
        },
    });

    var totalT = 100;
    if (totalSeat > 0) {
        var totalT = totalSeat;
    }

    new Chart("attent-num-chart", {
        type: "doughnut",
        plugins: [
            {
                afterDraw: (chart) => {
                    var needleValue = chart.config.data.datasets[0].needleValue;
                    var dataTotal = totalT;

                    // var dataTotal = chart.config.data.datasets[0].data.reduce((a, b) => a + b, 0);
                    var angle =
                        Math.PI + (1 / dataTotal) * needleValue * Math.PI;
                    var ctxx = chart.ctx;
                    var cw = chart.canvas.offsetWidth;
                    var ch = chart.canvas.offsetHeight;
                    var cx = cw / 2;
                    var cy = ch - 6;

                    (vm = chart._view),
                        // sA = vm.startAngle,
                        // eA = vm.endAngle,
                        (opts = chart.config.options);

                    ctxx.translate(cx, cy);
                    ctxx.rotate(angle);
                    ctxx.beginPath();
                    ctxx.moveTo(0, -3);
                    ctxx.lineTo(ch - 20, 0);
                    ctxx.lineTo(0, 3);
                    ctxx.fillStyle = "rgb(0, 0, 0)";
                    ctxx.fill();
                    ctxx.rotate(-angle);
                    ctxx.translate(-cx, -cy);
                    ctxx.beginPath();
                    ctxx.arc(cx, cy, 5, 0, Math.PI * 2);
                    ctxx.fill();

                    //display in the center the total sum of all segments
                    //ctx.fillText('Total = ' + total, vm.x, vm.y - 20, 200);
                    ctxx.fillText(
                        ((totalT * 75) / 100.0).toFixed(0),
                        cx + 96,
                        cy - 110,
                        200
                    );
                    ctxx.fillText(
                        ((totalT * 90) / 100.0).toFixed(0),
                        cx + 150,
                        cy - 50,
                        200
                    );
                    ctxx.fillText("0%", cx - 150, cy);
                    ctxx.fillText(totalT, cx + 150, cy);
                },
            },
        ],
        data: {
            datasets: [
                {
                    data: [0, 75, 15, 10],

                    data: [
                        0,
                        (totalT * 75) / 100.0,
                        (totalT * 15) / 100.0,
                        (totalT * 10) / 100.0,
                    ],
                    needleValue: attendance,
                    backgroundColor: ["#b1deb5", "#FBBCBC", "#FBE6BC"],
                },
            ],
        },
        options: {
            responsive: true,
            aspectRatio: 2,
            layout: {
                padding: {
                    bottom: 3,
                },
            },
            rotation: -90,
            circumference: 180,
            cutout: "70%",
            total: 100,
            legend: {
                // display: true
            },
            animation: {
                animateRotate: false,
                animateScale: true,
            },
        },
    });
});

// document.getElementById('country_id').onchange = function () {
//     // var year = document.getElementById('ddlYears').value;
//     var country_id = document.getElementById('country_id').value;

//     $.ajax({
//         type: 'GET',
//         dataType: 'json',
//         url: '/dashboard/general/get_unitsStatistic',
//         data: {
//             'country_id': country_id,
//         },
//         success: function (response) {
//             console.log(response.unitsStatistic);
//             unitsStatistic = response.unitsStatistic;
//         },

//         error: function (error) {
//             console.log('error', error);
//         }
//     });

// };

function accept(id, userResponse) {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/dashboard/general/notifications/accept",
        data: {
            id: id,
            response: userResponse,
        },
        success: function (response) {
            console.log(response.message);
            document.getElementById("Response" + id).innerHTML = userResponse;
            document.getElementById("Response" + id).style.color = "green";
        },
        error: function (error) {
            console.log(error);
        },
    });
}

function reject(id, userResponse) {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/dashboard/general/notifications/reject",
        data: {
            id: id,
            response: userResponse,
        },
        success: function (response) {
            console.log(response.message);
            document.getElementById("Response" + id).innerHTML = userResponse;
            document.getElementById("Response" + id).style.color = "#9d0707";
        },
        error: function (error) {
            console.log(error);
        },
    });
}
