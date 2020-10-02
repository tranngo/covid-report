var data = null; // Response from the API
var lineChart = null;
var bookmarks = new Map(); // Map is used to efficiently check if a country is in the user's bookmark list

const months = ["Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sept", "Oct", "Nov", "Dec"];
var lineChartLabels = [];

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

if (localStorage.getItem('showBookmarkTooltip') == "false") {
} else {
	localStorage.setItem('showBookmarkTooltip', 'true');
	let colTag = document.createElement('div');
	colTag.className = 'col-12';

	let alertTag = document.createElement('div');
	alertTag.className = 'alert alert-info alert-dismissible fade show text-center';
	alertTag.setAttribute('role', 'alert');
	alertTag.setAttribute('id', 'tooltip');
	alertTag.innerHTML = '<strong>Bookmarks!</strong> Use this page to stay up to date with the number of ongoing cases.';

	let alertBtnTag = document.createElement('button');
	alertBtnTag.setAttribute('type', 'button');
	alertBtnTag.className = 'close';
	alertBtnTag.setAttribute('data-dismiss', 'alert');
	alertBtnTag.setAttribute('aria-label', 'Close');

	let spanTag = document.createElement('span');
	spanTag.setAttribute('aria-hidden', 'true');
	spanTag.innerHTML = '&times;';

	alertBtnTag.appendChild(spanTag);
	alertTag.appendChild(alertBtnTag);
	colTag.appendChild(alertTag);
	document.querySelector('#tooltip-row').appendChild(colTag);
}

$('#tooltip').on('closed.bs.alert', function () {
	localStorage.setItem('showBookmarkTooltip', 'false');
})

if(bookmarks.size > 0) {
	$.getJSON('https://pomber.github.io/covid19/timeseries.json', function(response) {
	    data = response;

	    let initialized = false;
	    let tempCountry = null;
	    for (let country in data) {
	        if(!initialized) {
	        	tempCountry = country;
	        	initialized = true;
	        } else {
	        	break;
	        }
	    }

	    let i;
	    var numberOfDates = data[tempCountry].length;
	    for (i = 0; i < numberOfDates; i++) {
	        var dateObject = new Date(data[tempCountry][i].date);
	        var month = months[dateObject.getMonth()];
	        var day = dateObject.getDate();
	        // var year = dateObject.getFullYear();
	        var dateString = month + ' ' + day;
	        lineChartLabels.push(dateString);
	    }
		createLineChart();
	});
} else {
	createNoBookmarksAlert();
}

function Data(dataInput, labelInput, borderColorInput, fillInput) {
	this.data = dataInput;
	this.label = labelInput;
	this.borderColor = borderColorInput;
	this.fill = fillInput;
}

function createNoBookmarksAlert() {
	let colTag = document.createElement('div');
	colTag.className = 'col-12';

	let alertTag = document.createElement('div');
	alertTag.className = 'alert alert-danger text-center';
	alertTag.setAttribute('role', 'alert');
	alertTag.innerHTML = 'You currently do not have any bookmarks. Return to the home page to add a bookmark.';

	colTag.appendChild(alertTag);
	document.querySelector('#no-bookmarks-msg').appendChild(colTag);
	$('#no-bookmarks-msg').show();
}

function createLineChart() {
	for (let [k,v] of bookmarks) {
		let divTag = document.createElement('div');
		divTag.className = 'col-12 col-md-6 my-3';

		let divTagCard = document.createElement('div');
		divTagCard.className = 'card';

		let divTagCardBody = document.createElement('div');
		divTagCardBody.className = 'card-body text-center';

		let canvasTag = document.createElement('canvas');
		canvasTag.width = 800;
		canvasTag.height = 450;

		let buttonTag = document.createElement('button');
		buttonTag.setAttribute('type', 'button');
		buttonTag.className = 'btn btn-info mt-3 remove-bookmark';
		buttonTag.innerHTML = 'Remove Bookmark';
		buttonTag.setAttribute('country', k);

		divTagCardBody.appendChild(canvasTag);
		divTagCardBody.appendChild(buttonTag);
		divTagCard.appendChild(divTagCardBody);
		divTag.appendChild(divTagCard);

		let ctxLineChart = canvasTag.getContext('2d');

		let chartOngoingCases = [];

        let i;
        var numberOfLineChartLabels = lineChartLabels.length;
        for (i = 0; i < numberOfLineChartLabels; i++) {
            chartOngoingCases.push(data[k][i].confirmed - data[k][i].recovered - data[k][i].deaths);
        }

		new Chart(ctxLineChart, {
	  	type: 'line',
	  	data: {
            labels: lineChartLabels,
            datasets: [{
                label: 'Ongoing Cases',
                hoverBackgroundColor: 'rgb(91, 192, 222)',
                backgroundColor: 'rgba(91, 192, 222, 0.1)',
                borderColor: 'rgb(91, 192, 222)',
                data: chartOngoingCases
            }]
        },
		  	options: {
		    title: {
		      display: true,
		      text: k,
		      fontSize: 30,
		      fontColor: '#E1E1E1'
		    }
		  }
		});

		document.querySelector('#chart-area').appendChild(divTag);

	}

	$('.remove-bookmark').on('click', function() {
		let country = $(this).attr('country');
		$(this).parent().parent().parent().fadeOut(400, function() {
			$(this).remove();
			bookmarks.delete(country, null);
			localStorage.setItem('bookmarks', JSON.stringify(Array.from(bookmarks.keys())));
			console.log(bookmarks.size);
			if(bookmarks.size == 0) {
				createNoBookmarksAlert();
			}
		});

	});
   
}