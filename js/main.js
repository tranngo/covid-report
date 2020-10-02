var countries = [];
var totalConfirmed = 0;
var totalRecovered = 0;
var totalDeaths = 0;
var data = null; // Response from the API
var lineChart = null;
var pieChart = null;
var bookmarks = new Map(); // Map is used to efficiently check if a country is in the user's bookmark list
var lastUpdated = null;

const months = ["Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sept", "Oct", "Nov", "Dec"];
var lineChartLabels = [];
var globalConfirmedData = [];
var globalRecoveredData = [];
var globalDeathsData = [];

function Country(name, numConfirmed, numDeaths, confirmedIncreaseAmount, deathsIncreaseAmount) {
    this.countryName = name;
    this.confirmed = numConfirmed;
    this.deaths = numDeaths;
    this.confirmedIncrease = confirmedIncreaseAmount;
    this.deathsIncrease = deathsIncreaseAmount;
}

if (localStorage.getItem('bookmarks')) {
    let i;
    var bookmarkedCountries = JSON.parse(localStorage.getItem('bookmarks'));
    var bookmarkedCountriesCount = bookmarkedCountries.length;
    for (i = 0; i < bookmarkedCountriesCount; i++) {
        bookmarks.set(bookmarkedCountries[i], null);
    }
} else {
    localStorage.setItem('bookmarks', JSON.stringify(Array.from(bookmarks.keys())));
}

$.getJSON('https://pomber.github.io/covid19/timeseries.json', function(response) {
    data = response;

    for (var country in data) {

        var tempCountry = new Country(country, data[country][data[country].length - 1].confirmed, data[country][data[country].length - 1].deaths, data[country][data[country].length - 1].confirmed - data[country][data[country].length - 2].confirmed, data[country][data[country].length - 1].deaths - data[country][data[country].length - 2].deaths);
        countries.push(tempCountry);

        totalConfirmed += data[country][data[country].length - 1].confirmed;
        totalRecovered += data[country][data[country].length - 1].recovered;
        totalDeaths += data[country][data[country].length - 1].deaths;
    }

    countries.sort();

    let i;
    var numberOfDates = data[countries[0].countryName].length;
    for (i = 0; i < numberOfDates; i++) {
        var dateObject = new Date(data[country][i].date);
        var month = months[dateObject.getMonth()];
        var day = dateObject.getDate();
        // var year = dateObject.getFullYear();
        var dateString = month + ' ' + day;
        lineChartLabels.push(dateString);

        if (i == numberOfDates - 1) {
            lastUpdated = dateString + ', ' + dateObject.getFullYear();
            $('#last-updated').text(lastUpdated);
        }
    }

    setGlobalData();
    addDropdown();
    fillLineChart('Global');
    createLineChart();
    createPieChart();
    sortCountries('confirmedIncrease');
    fillDailyStatisticsTable();
});

$('#country').on('change', function() {
    fillLineChart($('#country').val());
    updateLineChart($('#country').val());

    if (bookmarks.has($('#country').val())) {
        $('#bookmark').prop('checked', true);
    } else {
        $('#bookmark').prop('checked', false);
    }
});

$('#disinfection-guide-btn').on('click', function() {
    window.open('https://www.cdc.gov/coronavirus/2019-ncov/prevent-getting-sick/cleaning-disinfection.html', '_blank');
});

function sortCountries(sortBy) {
    if(sortBy == 'confirmed') {
        countries = countries.sort(function (a, b) {
            return b.confirmed - a.confirmed;
        });
    } else if(sortBy == 'deaths') {
        countries = countries.sort(function (a, b) {
            return b.deaths - a.deaths;
        });
    } else if(sortBy == 'confirmedIncrease') {
        countries = countries.sort(function (a, b) {
            return b.confirmedIncrease - a.confirmedIncrease;
        });
    } else if(sortBy == 'deathsIncrease') {
        countries = countries.sort(function (a, b) {
            return b.deathsIncrease - a.deathsIncrease;
        });
    } else if(sortBy == 'name') {
        countries = countries.sort(function (a, b) {
            return a.countryName.localeCompare(b.countryName);
        });
    }
}

function addDropdown() {
    sortCountries('name');

    let i;
    var numberOfCountries = countries.length;
    for (i = 0; i < numberOfCountries; i++) {
        $('#country').append('<option value="' + countries[i].countryName + '">' + countries[i].countryName + '</select>');
    }
}

function fillLineChart(country) {
    if (country == 'Global') {
        $('#confirmed').text(totalConfirmed.toLocaleString());
        $('#recovered').text(totalRecovered.toLocaleString());
        $('#deaths').text(totalDeaths.toLocaleString());
    } else {
        $('#confirmed').text(data[country][data[country].length - 1].confirmed.toLocaleString());
        $('#recovered').text(data[country][data[country].length - 1].recovered.toLocaleString());
        $('#deaths').text(data[country][data[country].length - 1].deaths.toLocaleString());
    }
}

function updateLineChart(country) {
    if (country == 'Global') {
        lineChart.data.datasets[0].data = globalConfirmedData;
        lineChart.data.datasets[1].data = globalRecoveredData;
        lineChart.data.datasets[2].data = globalDeathsData;
    } else {

        var chartConfirmedData = [];
        var chartRecoveredData = [];
        var chartDeathsData = [];

        let i;
        var numberOfLineChartLabels = lineChartLabels.length;
        for (i = 0; i < numberOfLineChartLabels; i++) {
            chartConfirmedData.push(data[country][i].confirmed);
            chartRecoveredData.push(data[country][i].recovered);
            chartDeathsData.push(data[country][i].deaths);
        }

        lineChart.data.datasets[0].data = chartConfirmedData;
        lineChart.data.datasets[1].data = chartRecoveredData;
        lineChart.data.datasets[2].data = chartDeathsData;
    }
    lineChart.update();
}

function setGlobalData() {
    let i;
    var numberOfLineChartLabels = lineChartLabels.length;
    for (i = 0; i < numberOfLineChartLabels; i++) {
        var confirmedCases = 0;
        var recoveredCases = 0;
        var deathCases = 0;

        let j;
        var numberOfCountries = countries.length;
        for (j = 0; j < numberOfCountries; j++) {
            confirmedCases += data[countries[j].countryName][i].confirmed;
            recoveredCases += data[countries[j].countryName][i].recovered;
            deathCases += data[countries[j].countryName][i].deaths;
        }
        globalConfirmedData.push(confirmedCases);
        globalRecoveredData.push(recoveredCases);
        globalDeathsData.push(deathCases);
    }
}

function createPieChart() {
    var ctxPieChart = document.getElementById('pieChart').getContext('2d');

    var pieChartData = [];
    var pieChartLabels = []

    sortCountries('confirmed');

    let i;
    for (i = 0; i < 15; i++) {
        pieChartData.push(countries[i].confirmed);
        pieChartLabels.push(countries[i].countryName + ' (' + ((100 * countries[i].confirmed) / totalConfirmed).toFixed(1).toString() + '%)');
    }

    pieChartData.push(totalConfirmed - pieChartData[0] - pieChartData[1] - pieChartData[2] - pieChartData[3] - pieChartData[4] - pieChartData[5] - pieChartData[6] - pieChartData[7] - pieChartData[8] - pieChartData[9] - pieChartData[10] - pieChartData[11] - pieChartData[12] - pieChartData[13] - pieChartData[14]);
    pieChartLabels.push('Other (' + ((100 * pieChartData[15]) / totalConfirmed).toFixed(1).toString() + '%)');

    pieChart = new Chart(ctxPieChart, {
        // The type of chart we want to create
        type: 'pie',

        // The data for our dataset
        data: {
            datasets: [{
                data: pieChartData,
                backgroundColor: ['#2193b0', '#2f99b5', '#3a9eba', '#44a4bf', '#4daac4', '#56b0ca', '#5fb5cf', '#67bbd4', '#6fc1d9', '#77c7df', '#7fcde4', '#87d3e9', '#8fd9ef', '#97dff4', '#9ee5fa', '#a6ebff'],
                borderColor: '#1E1E1E'
            }],

            // These labels appear in the legend and in the tooltips when hovering different arcs
            labels: pieChartLabels
        },

        // Configuration options go here
        options: {
            legend: {
                display: false
            }
        }
    });
}

function createLineChart() {
    var ctxLineChart = document.getElementById('lineChart').getContext('2d');

    lineChart = new Chart(ctxLineChart, {
        // The type of chart we want to create
        type: 'line',

        // The data for our dataset
        data: {
            labels: lineChartLabels,
            datasets: [{
                label: 'Confirmed',
                hoverBackgroundColor: 'rgb(91, 192, 222)',
                backgroundColor: 'rgba(91, 192, 222, 0.1)',
                borderColor: 'rgb(91, 192, 222)',
                data: globalConfirmedData
            }, {
                label: 'Recovered',
                hoverBackgroundColor: 'rgb(92, 184, 92)',
                backgroundColor: 'rgba(92, 184, 92, 0.1)',
                borderColor: 'rgb(92, 184, 92)',
                data: globalRecoveredData
            }, {
                label: 'Deaths',
                hoverBackgroundColor: 'rgb(217, 83, 79)',
                backgroundColor: 'rgba(217, 83, 79, 0.2)',
                borderColor: 'rgb(217, 83, 79)',
                data: globalDeathsData
            }]
        },

        // Configuration options go here
        options: {}
    });
}

function fillDailyStatisticsTable() {
    let i;

    let tbody = document.querySelector("tbody");
    while(tbody.hasChildNodes()) {
        tbody.removeChild(tbody.lastChild);
    }

    let trTag = document.createElement('tr');
    tbody.appendChild(trTag);

    for (i = 0; i < countries.length; i++) {
        
        let bookmarkHTML = null;
        if(bookmarks.has(countries[i].countryName)) {
            bookmarkHTML = '<td><button type="button" class="btn bookmark-btn mt-1" id="' + countries[i].countryName + '"><i class="fas fa-star bookmark-icon" aria-hidden="true"></i></button></td>';
        } else {
            bookmarkHTML = '<td><button type="button" class="btn bookmark-btn mt-1" id="' + countries[i].countryName + '"><i class="far fa-star bookmark-icon" aria-hidden="true"></i></button></td>';
        }

        $('#daily-table tr:last').after(
            '<tr>' + bookmarkHTML + '<td><img src="img/flags/' + countries[i].countryName + '.png" class="pt-2"></td>' +
            '<td><div class="daily-country font-weight-bold">' + countries[i].countryName + '</div><div class="daily-confirmed">' + countries[i].confirmed.toLocaleString() + ' Confirmed - ' +
            countries[i].deaths.toLocaleString() + ' Deaths</div></td><td><div class="badge badge-info">+ ' + countries[i].confirmedIncrease.toLocaleString() + ' Confirmed</div><br><div class="badge badge-danger">+ ' +
            countries[i].deathsIncrease.toLocaleString() + ' Deaths</div></td></tr>'
        );
    }

    $('.bookmark-btn').on('click', function() {
        if ($(this).html() == '<i class="far fa-star bookmark-icon" aria-hidden="true"></i>') {
            $(this).html('<i class="fas fa-star bookmark-icon" aria-hidden="true"></i>');
            bookmarks.set($(this).attr('id'), null);
        } else if($(this).html() == '<i class="fas fa-star bookmark-icon" aria-hidden="true"></i>') {
            $(this).html('<i class="far fa-star bookmark-icon" aria-hidden="true"></i>');
            bookmarks.delete($(this).attr('id'), null);
        }
        localStorage.setItem('bookmarks', JSON.stringify(Array.from(bookmarks.keys())));
    });
}

$('#sort-by').on('change', function() {
    sortCountries($(this).val());
    fillDailyStatisticsTable();
    $('#country-search').val('');
});

$('#country-search').keyup(function() {
    var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

    $('.daily-country').each(function(i, obj) {
        if($(this).text().toLowerCase().indexOf(val) >= 0) {
            $(this).parent().parent().show();
        } else {
            $(this).parent().parent().hide();
        }
    });
});