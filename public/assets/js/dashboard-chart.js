var animo_jenis_kelamin_data = {
    series: [persentase_pendaftar_lakilaki, persentase_pendaftar_perempuan],
    labels: ["Laki-laki", "Perempuan"],
    chart: {
        width: 180,
        type: "donut",
        fontFamily: "'Plus Jakarta Sans', sans-serif",
        foreColor: "#adb0bb"
    },
    plotanimo_daerah: {
        pie: {
            startAngle: 0,
            endAngle: 360,
            donut: {
                size: "75%"
            }
        }
    },
    stroke: {
        show: false
    },
    dataLabels: {
        enabled: false
    },
    legend: {
        show: false
    },
    colors: ["var(--bs-primary)", "#ecf2ff"],
    tooltip: {
        theme: "dark",
        fillSeriesColor: false,
        y: {
            formatter: function(value) {
                return value + "%";
            }
        }
    }
};

var chart = new ApexCharts(document.querySelector("#animo_jenis_kelamin"), animo_jenis_kelamin_data);
chart.render();

var lulus_jenis_kelamin_data = {
    series: [persentase_pendaftar_lakilaki_approved, persentase_pendaftar_perempuan_approved],
    labels: ["Laki-laki", "Perempuan"],
    chart: {
        width: 180,
        type: "donut",
        fontFamily: "'Plus Jakarta Sans', sans-serif",
        foreColor: "#adb0bb"
    },
    plotanimo_daerah: {
        pie: {
            startAngle: 0,
            endAngle: 360,
            donut: {
                size: "75%"
            }
        }
    },
    stroke: {
        show: false
    },
    dataLabels: {
        enabled: false
    },
    legend: {
        show: false
    },
    colors: ["var(--bs-primary)", "#ecf2ff"],
    tooltip: {
        theme: "dark",
        fillSeriesColor: false,
        y: {
            formatter: function(value) {
                return value + "%";
            }
        }
    }
};

var chart = new ApexCharts(document.querySelector("#lulus_jenis_kelamin"), lulus_jenis_kelamin_data);
chart.render();



