class Filter {

    // All changes should be made to the HTML DOM instead of formData.
    // updateFormData() should be the only place where formData is updated/changed.
    // The URL search params are created using formData.
    // When the page is loaded with initial URL search params, the HTML DOM is updated using those search params.
    #formData;

    constructor() {
        this.form = document.querySelector("#filters");
        this.loadingIndicator = document.querySelector("#loader");
        this.perPage = document.querySelector("#per-page");
        this.sort = document.querySelector("#sort");

        let initParams = new URL(window.location.href).searchParams;
        this.params = new URLSearchParams(LZString.decompressFromEncodedURIComponent(initParams.get("q")));

        this.endpoint = sfd.endpoint;
        this.cache = sfd.cache;
        this.grid = sfd.grid;
        this.table = sfd.table;

        this.pageCount = 1;

        // Functions to run when page first loads
        this.autofillForm();
        this.getPosts();
        this.addEvents();
    }

    // Autofill form input on first page load using URL search params
    autofillForm() {
        this.params.forEach((value, key) => {
            // Set current page
            if (key == "page") {
                this.page = value;
                return;
            }
            // Set display mode
            if (key == "display") {
                document.querySelector("#display").value = value;
                return;
            }
            // Set checked checkboxes and radios
            let v = document.querySelector("input[name='" + key + "'][value='" + value + "']");
            if (v != null) {
                v.checked = true;
            }
            // Set select element value
            v = document.querySelector("select[name='" + key + "']");
            if (v != null) {
                v.value = value;
            }
            // Set default per page select option
            if (key == "per-page") {
                this.perPage.querySelector("option[selected]").removeAttribute("selected");
                this.perPage.querySelector("option[value='" + value +"']").setAttribute('selected', 'selected');
            }
        });
    }

    // Update formData, used at the start of getPosts()
    updateFormData() {
        this.#formData = new FormData(this.form);

        // Show number of filters selected
        let selectedFiltersDisclosure = document.querySelector("#applied-filters__selected");
        let selectedFilters = document.querySelector("#applied-filters-list");
        let numSelected = document.querySelector("#applied-filters__num");
        let noneSelected = document.querySelector("#applied-filters__none");
        //let disclosure = document.querySelector("#applied-filters__disclosure");

        //disclosure.setAttribute("hidden", "");
        selectedFiltersDisclosure.setAttribute("hidden", "");
        selectedFilters.innerHTML = '';

        // Display selected filter options
        for (const pair of this.#formData.entries()) {
            // Exclude empty string values (defaults), current page num, display option, per-page num, and sort option.
            // Include if year or if checkbox without children.
            if (pair[1] !== '' && pair[0] !== 'page' && pair[0] !== 'display' && pair[0] !== 'per-page' && pair[0] !== 'sort') {
                let selectedValue = document.createElement("li");
                selectedValue.classList.add("filter-pill");

                if (pair[0] === 'year') {
                    selectedValue.textContent = pair[1];
                }
                else {
                    selectedValue.textContent = this.form.querySelector("label[for='" + pair[1] +"']").textContent;
                }

                selectedFilters.appendChild(selectedValue);
            }
        }

        if (selectedFilters.childElementCount > 0) {
            selectedFiltersDisclosure.removeAttribute("hidden");
            noneSelected.setAttribute("hidden", "");
            //numSelected.removeAttribute("hidden");
            //disclosure.removeAttribute("hidden");

            let numFilters = selectedFilters.childElementCount;
            numSelected.textContent = numFilters;
        }
        else {
            noneSelected.removeAttribute("hidden");
        }

        this.#formData.set("cache", this.cache);
        this.#formData.set("grid", this.grid);
        this.#formData.set("table", this.table);
    }

    // Make request and handle response
    async getPosts() {
        try {
            this.loadingIndicator.hidden = false;

            this.updateFormData();
            
            let qstr = '';

            // Update URL without refreshing the page
            qstr = new URLSearchParams(this.#formData).toString();
            qstr = LZString.compressToEncodedURIComponent(qstr);
            qstr = '?q=' + qstr;

            window.history.replaceState({}, "", qstr);

            // Request
            const response = await fetch(this.endpoint + qstr, {
                method: 'GET',
            });

            // Response
            const res = await response.json();

            // Handle response
            if (response.ok) {
                let total = res['total'];
                let output = res['output'];

                this.pageCount = 1;
                if (total > 0){
                    this.pageCount = total / Number(this.get("per-page"));

                    if (this.pageCount < 1) {
                        this.pageCount = 1;
                    }

                    this.pageCount = Math.ceil(this.pageCount);
                }

                this.updatePagination();

                document.querySelector("#pagination-page-counter").innerHTML = this.get("page") + ' / ' + this.pageCount;
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
            this.loadingIndicator.hidden = true;
        }
    }

    // Check if pagination buttons should be disabled based on the current page and total page count
    updatePagination() {
        let first = false;
        let last = false;

        if (this.get("page") == 1) {
            first = true;
        }

        if (this.get("page") == this.pageCount) {
            last = true;
        }

        document.querySelector("#pagination-first").disabled = first;
        document.querySelector("#pagination-previous").disabled = first;

        document.querySelector("#pagination-next").disabled = last;
        document.querySelector("#pagination-last").disabled = last;
    }

    // Shorthand method to get formData values
    get(k) {
        return this.#formData.get(k);
    }

    // Setter method for current page, 
    set page(v) {
        document.querySelector("#page").value = v;
    }

    // Method to add event listeners for input
    addEvents() {
        // Submit button
        this.form.addEventListener("submit", (event) => {
            event.preventDefault();
            this.page = 1;
            this.getPosts();
        });

        // Per-page input
        this.perPage.addEventListener("change", (event) => {
            this.perPage.querySelector("option[selected]").removeAttribute("selected");
            this.perPage.querySelector("option[value='" + this.perPage.value +"']").setAttribute('selected', 'selected');

            this.page = 1;
            this.getPosts();
        });

        // Sort input
        this.sort.addEventListener("change", (event) => {
            this.sort.querySelector("option[selected]").removeAttribute("selected");
            this.sort.querySelector("option[value='" + this.sort.value +"']").setAttribute('selected', 'selected');

            this.getPosts();
        });

        // Pagination buttons
        document.querySelector("#pagination-first").addEventListener("click", (event) => {
            if (this.get("page") > 1) {
                this.page = 1;
                this.getPosts();
            }
        });
        document.querySelector("#pagination-previous").addEventListener("click", (event) => {
            if (this.get("page") > 1) {
                this.page = Number(this.get("page")) - 1;
                this.getPosts();
            }
        });
        document.querySelector("#pagination-next").addEventListener("click", (event) => {
            if (this.get("page") < this.pageCount) {
                this.page = Number(this.get("page")) + 1;
                this.getPosts();
            }
        });
        document.querySelector("#pagination-last").addEventListener("click", (event) => {
            if (this.get("page") < this.pageCount) {
                this.page = this.pageCount;
                this.getPosts();
            }
        });

        // Output display buttons
        document.querySelector("#grid-layout").addEventListener("click", (event) => {
            if (this.get("display") != 'grid') {
                document.querySelector("#display").value = 'grid';
                this.getPosts();
            }
        });
        document.querySelector("#table-layout").addEventListener("click", (event) => {
            if (this.get("display") != 'table') {
                document.querySelector("#display").value = 'table';
                this.getPosts();
            }
        });
    }
}

const instance = new Filter();