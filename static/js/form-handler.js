const form = document.querySelector("#filters");
var formData = new FormData(form);
var page = document.querySelector("#page");
var display = document.querySelector("#display");
var pageCount = 1;

const initURL = new URL(window.location.href);
const initParams = new URLSearchParams(initURL.search);
var initRun = true;

// console.log(LZString.decompressFromEncodedURIComponent(initParams.get("q")));

// Autofill form from query params
var prams = new URLSearchParams(LZString.decompressFromEncodedURIComponent(initParams.get("q")));
prams.forEach((value, key) => {
    // Set initial page
    if (key == "page") {
        page.value = value;
        return;
    }
    // Set initial display
    if (key == "display") {
        display.value = value;
        return;
    }
    // Check initial values for checkboxes
    let v = document.querySelector("input[name='" + key + "'][value='" + value + "']");
    if (v != null) {
        v.checked = true;
    }
    // Set initial value for select elements
    v = document.querySelector("select[name='" + key + "']");
    if (v != null) {
        v.value = value;
    }
});
// get the length of initParams and put it into the first getPosts() as an arg
// then the getPosts() function checks the length and gets the initParams and uses that string instead to query


jQuery( document ).ready( function( $ ) {

    // Get posts on initial page load
    getPosts();

    // Per page selection
    $('#per-page').bind('change', function() {
        getPosts();
    });

    // Pagination buttons
    $('#pagination-first').bind('click', function() {
        if (page.value > 1) {
            page.value = 1;
            getPosts(false, page);
        }
    });
    
    $('#pagination-previous').bind('click', function() {
        if (page.value > 1) {
            page.value = --page.value;
            getPosts(false, page);
        }
    });

    $('#pagination-next').bind('click', function() {
        if (page.value < pageCount) {
            page.value = ++page.value;
            getPosts(false, page);
        }
    });

    $('#pagination-last').bind('click', function() {
        if (page.value < pageCount) {
            page.value = pageCount;
            getPosts(false, page.value);
        }
    });

    // Output layout buttons
    $('#grid-layout').bind('click', function() {
        if (display.value != 'grid') {
            display.value = 'grid';
            getPosts(false, page.value);
        }
    });

    $('#table-layout').bind('click', function() {
        if (display.value != 'table') {
            display.value = 'table';
            getPosts(false, page.value);
        }
    });

});

form.addEventListener("submit", (event) => {
    event.preventDefault();
    page.value = 1;
    getPosts();
});

/** 
 * Function to make and handle a POST request using REST API endpoint.
 * 
 * See SFD_Loader->enqueue_scripts() for request
 * See SFD_REST_Controller for response
 */
async function getPosts(filterUpdated = true, p = 1) {
    try {
        document.querySelector("#loader").hidden = false;
        
        formData = new FormData(form);

        const data = formData;
        data.set("cache", sfd.cache);

        // Template names from SFD_Loader
        data.set("grid", sfd.grid);
        data.set("table", sfd.table);

        let qstr = '';

        if (initParams.size > 0 && initRun == true) {
            qstr = "/?" + initParams.toString();
            initRun = false;
        }
        else {
            // Update URL without refreshing the page
            qstr = new URLSearchParams(data).toString();
            qstr = LZString.compressToEncodedURIComponent(qstr);
            qstr = '?q=' + qstr;

            window.history.replaceState({}, "", qstr);
        }

        // Request
        const response = await fetch(sfd.endpoint + qstr, {
            method: 'GET',
        });

        // Response
        const res = await response.json();

        // Handle response
        if (response.ok) {
            var total = res['total'];
            var output = res['output'];

            pageCount = 1;
            if (total > 0){
                pageCount = total / Number(data.get("per-page"));

                if (pageCount < 1) {
                    pageCount = 1;
                }

                pageCount = Math.ceil(pageCount);
            }

            updatePagination();

            document.querySelector("#pagination-page-counter").innerHTML = page.value + ' / ' + pageCount;
            document.querySelector("#total").innerHTML = total + ' items found.';
            document.querySelector("#output-view").innerHTML = output;
        }
        else {
            // HTTP Response status not within 200-299
            throw new Error(response.statusText);
        }
    }
    catch (error) {
        document.querySelector("#total").innerHTML = `There has been an error.`;
        console.log(error);
        document.querySelector("#output-view").innerHTML = '';
    }
    finally {
        document.querySelector("#loader").hidden = true;
    }
}

function updatePagination() {
    let first = false;
    let last = false;

    if (page.value == 1) {
        first = true;
    }

    if (page.value == pageCount) {
        last = true;
    }

    document.querySelector("#pagination-first").disabled = first;
    document.querySelector("#pagination-previous").disabled = first;

    document.querySelector("#pagination-next").disabled = last;
    document.querySelector("#pagination-last").disabled = last;
}