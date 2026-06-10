const form = document.querySelector("#filters");
var formData = new FormData(form);
var page = 1;
var pageCount = 1;
var display = sfd.default_display;

jQuery( document ).ready( function( $ ) {

    // Get posts on initial page load
    getPosts();

    // Per page selection
    $('#per-page').bind('change', function() {
        getPosts();
    });

    // Pagination buttons
    $('#pagination-first').bind('click', function() {
        if (page > 1) {
            page = 1;
            getPosts(false, page);
        }
    });
    
    $('#pagination-previous').bind('click', function() {
        if (page > 1) {
            page = --page;
            getPosts(false, page);
        }
    });

    $('#pagination-next').bind('click', function() {
        if (page < pageCount) {
            page = ++page;
            getPosts(false, page);
        }
    });

    $('#pagination-last').bind('click', function() {
        if (page < pageCount) {
            page = pageCount;
            getPosts(false, page);
        }
    });

    // Output layout buttons
    $('#grid-layout').bind('click', function() {
        if (display != 'grid') {
            display = 'grid';
            getPosts(false, page);
        }
    });

    $('#table-layout').bind('click', function() {
        if (display != 'table') {
            display = 'table';
            getPosts(false, page);
        }
    });

});

form.addEventListener("submit", (event) => {
    event.preventDefault();
    formData = new FormData(form);
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
        
        // Updated filter settings
        if (filterUpdated == true) {
            formData = new FormData(form);
        }

        const data = formData;
        data.set("page", p);
        data.set("display", display);
        data.set("cache", sfd.cache);

        // Template names from SFD_Loader
        data.set("grid", sfd.grid);
        data.set("table", sfd.table);

        // Request
        const response = await fetch(sfd.endpoint, {
            method: 'POST',
            body: data,
        });

        // Response
        const res = await response.json();

        // Reset current page to 1 if new query
        if (filterUpdated == true) {
            page = 1;
        }

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

            document.querySelector("#pagination-page-counter").innerHTML = page + ' / ' + pageCount;
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

    if (page == 1) {
        first = true;
    }

    if (page == pageCount) {
        last = true;
    }

    document.querySelector("#pagination-first").disabled = first;
    document.querySelector("#pagination-previous").disabled = first;

    document.querySelector("#pagination-next").disabled = last;
    document.querySelector("#pagination-last").disabled = last;
}