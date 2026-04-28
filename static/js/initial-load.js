//  ╭──────────────┬──────────────────────────────────┬───────┬────────────╮
//  │ NAME:        │ initial loader                   │ DATE: │ 2026-04-08 │
//  ├──────────────┼──────────────────────────────────┴───────┴────────────┤
//  │ DESCRIPTION: │ Initialization loader to cue the REST API calls       │
//  │ PROJECT:     │ SIGGRAPH History Archive: Search Filter Plugin        │
//  ╰──────────────┴───────────────────────────────────────────────────────╯



// Connect to pre-loaded jquery functions
// ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈

let $ = window.jQuery;
const jQuery = window.jQuery;

// Deep copy function
// ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈
function clone(el) {
  let newNode = el.cloneNode(true);
  newNode.classList.remove("hidden");
  return newNode;
}

// Accordion binding and switching
// ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈
window.onload = function() {
  var acc = document.getElementsByClassName("filter-accordion");
  var i;

  for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function() {
      this.classList.toggle("active");
      var panel = this.nextElementSibling;
      // panel.classList.toggle("active");
      if ($(panel).is(':visible')) {
        $(panel).slideUp('slow');
      } else {
        panel.style.maxHeight = "100vh";
        $(panel).slideDown('slow');
      }
      if (panel.style.maxHeight) {
        panel.style.maxHeight = null;
      } else {
      }
    });
  }
};


// FilterQuery triggered by state change in filters
// ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈

async function filterQuery(data) {
  console.log(data);

  let st = JSON.parse($("#state_object").text());
  
  st['filter'] = data.filters;

  let r = await fetch('http://localhost/wp-json/sfd/v1/archive_inventory', {
    method: 'POST',
    headers: {
      'Accept': 'application/json, text/plain, */*',
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(st)
  }).then(res => res.json()).then(res => {
    let entries = [];

    $.each(res.entries, function(idx, item) {
      let parent_type_id;
      if (item.parent_type_id === false) {
        parent_type_id = item.item_type_id;
      } else {
        parent_type_id = item.parent_type_id;
      }
      const entry = {
        id: item.id,
        all_info: item.all_info,
        title: item.title,
        subtitle: item.subtitle,
        image: item.image,
        year: item.year,
        url: item.url,
        item_type_id: item.item_type_id,
        parent_type_id: parent_type_id,
        volume: item.volume,
        number: item.number,
        amount: item.quantity
      };
      entries.push(entry);
    });

    const total = res.total_found;
    const pages = res.pages;
    const lookup = res.lookup;
    const hierarchy = res.hierarchy;
    const children = res.children;
    const parent = res.parent;
  
    // returns
    const results_obj = {
      entries, pages, total, lookup, parent, children, hierarchy
    };

    console.log(results_obj);

    import("./rest-handler.js")
      .then((module) => {
        module.updateResults(results_obj);
      });
  });
}




// Minifunction called when filters are updated
// ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈

const filterUrlDisplay = (state) => {
  // let filters_tax_arr = state;
  let query = document.getElementById('url-query-display');
  filterQuery(state);
}




// Wait for DOMContent to emit the load completion event
// ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈

document.addEventListener("DOMContentLoaded", (event) => {
  let t = new Date();
  console.log(`[${t.toTimeString()}] - DOM loaded & parsed`);
  document.getElementById('filtering-ids');
  var terms = pullTerms(); // terms is the Promise Object
  terms.then((v) => {
    console.log(v); // v is the returned data from the promise
    // take action
    populateFlatFilters(v); 
    populateHierarchicalFilters(v);
    bindFilterPanelButtons();
    const unsubFilterApply = filterManager.subscribe(filterUrlDisplay);
  });
});



/**
  * pullTerms()
  * ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈
  * Asynchronous function call to the REST api returning pedigree information
  * @param   {object} id       - id of object 
  * @param   {object} termData - object with 'lookup' list of ids to search 
  * @returns {int}    parent   - name of parent 
  */

async function pullTerms() {
  const base = "http://localhost/wp-json/sfd/v1/archive_inventory";
  const query = "?q[taxonomy]=";
  const taxonomy = "inventory_main_type,media_type,inventory_item_origin";
  let url = base + query + taxonomy;
  const resp = await fetch(url);
  const results = await resp.json();
  return results;
}



/**
  * getNameFromLookup(id, termData)
  * ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈
  * Get name from lookup table using the id as index key                  
  * @param   {object} id       - id of object 
  * @param   {object} termData - object with 'lookup' list of ids to search 
  * @returns {int}    parent   - name of parent 
  */

function getNameFromLookup(id, termData) {
  const lookup = termData['lookup']; 
  let name = lookup[id];
  return name;
}



/**
  * has_parent(id, termData)
  * ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈
  * Find out if the supplied id has a perent or is a top level item
  * @param   {object} id       - id of object 
  * @param   {object} termData - heirarchical list of ids to search 
  * @returns {int}    parent   - id of parent 
  */

function has_parent(id, termData) {
  let has_parent_bool = false;
  const type_terms = termData['inventory_main_type'];

  Object.keys(type_terms).forEach((term_id) => {
    let child_arr = type_terms[term_id];
    // loop through array checking to see if parent or child 
    if (child_arr.includes(Number(id))) {
      has_parent_bool = true;
    } 
  });
  return has_parent_bool;
}



/**
  * find_parent(id, termData)
  * ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈
  * Find parent upstream of an individual ID                  
  * @param   {object} id       - id of object 
  * @param   {object} termData - heirarchical list of ids to search 
  * @returns {int}    parent   - id of parent 
  */

function find_parent(id, termData) {
  let parent = 0;
  const type_terms = termData['inventory_main_type'];

  Object.keys(type_terms).forEach((term_id) => {
    let child_arr = type_terms[term_id];
    // loop through array checking to see if parent or child 
    if (child_arr.includes(Number(id))) {
      parent = term_id;
    } 
  });
  return parent;
}



/**
  * checkboxAction(checkbox, more_button)
  * ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈
  * Checkbox function called on checkbox checked                  
  * @param {HTMLElement} checkbox    - checkbox option element
  * @param {HTMLElement} more_button - button that expands subpanel (unused)
  * @return none
  */

function checkboxAction(checkbox, more_button) {

  let box = checkbox.nextElementSibling;
  if (checkbox.checked == true) {
    setChildren(checkbox.closest('div'), "checked");
    } else {
    setChildren(checkbox.closest('div'), "unchecked");
  }
}



/**
  * moreButtonAction(more, panel)
  * ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈
  * Dropdown function for the 'more' subpanels denoted by +/-
  * @param {HTMLElement} more  - dropdown subpanel clickable element
  * @param {HTMLElement} panel - option child subpanel itself
  * @return none
  */

function moreButtonAction(more, panel) {

  let type_panel = document.getElementById('type-filter-panel');
  if ($(panel).is(':visible')) {
    $(panel).slideUp('slow');
    more.classList.remove('open');
  } else {
    $(panel).slideDown('slow');
    more.classList.add('open');
    type_panel.style.maxHeight = "100vh";
  }
}



/**
  * bindFilterPanelButtons()
  * ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈
  * Bind the Apply and Reset buttons in the Filter Card Panel
  * @param  none
  * @return none
  */

function bindFilterPanelButtons() {
  
  let filterCard = document.getElementById("filterCard");  
  //apply button
  const apply = document.getElementById('filter-apply'); 
  apply.addEventListener('click', () => applyFilters());
  //reset button
  const reset = document.getElementById('filter-reset'); 
  reset.addEventListener('click', () => {
    setChildren(filterCard, "unchecked");
    document.getElementById('filter-form').reset(); 
    applyFilters();
  });

}



/**
  * applyFilters()
  * ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈
  * Applies the Filters set in the Filter Card Panel and Subpanels
  * @param none
  * @return none
  */

function applyFilters() {

  // get all checked filters
  let filterCard = document.getElementById("filterCard");
  let all_panels = filterCard.getElementsByClassName("panel");
  let panel_arr = Array.from(all_panels); // convert to array
  filter_arr = [];
  // loop through each panel
  panel_arr.forEach((panel) => {
    let tax = panel.getAttribute("data-taxonomy");
    let checkboxes = panel.querySelectorAll('input[type="checkbox"]');
    cb_arr = Array.from(checkboxes);
    let id_arr = []; // blank array for checkbox ids
    cb_arr.forEach((cb) => {
      if (cb.checked == true) {
        id_arr.push(cb.getAttribute("data-id"));
      }
    });
    // if any option is checked in panel push to the master filters 
    if (id_arr.length > 0) {
      filter_arr.push({'taxonomy': tax, 'terms': id_arr});
      }
  });

  // Missing items checkbox
  if (document.getElementById('missing-items').checked) {
    import("./statelib.js")
      .then((module) => {
        module.setStateItem('missingitems', true, false);
      });
  }
  else {
    import("./statelib.js")
      .then((module) => {
        module.setStateItem('missingitems', false, false);
      });
  }

  // Conference radio buttons
  // TODO: Refactor this
  if (document.getElementById('siggraph').checked) {
    import("./statelib.js")
      .then((module) => {
        module.setStateItem('conference', 'siggraph', false);
      });
  }
  if (document.getElementById('siggraph-asia').checked) {
    import("./statelib.js")
      .then((module) => {
        module.setStateItem('conference', 'siggraph-asia', false);
      });
  }
  if (document.getElementById('both-conferences').checked) {
    import("./statelib.js")
      .then((module) => {
        module.setStateItem('conference', 'both', false);
      });
  }

    import("./statelib.js")
      .then((module) => {
        module.setStateItem('year', document.getElementById('year').value, false);
      });


    console.log('applying filters');
    // let tester = document.querySelector("#filter-test-display");
    // tester.innerText = "";
    // filter_arr.forEach((f) => {
    //   tester.innerText += JSON.stringify(f);
    // });

    // ATTENTION: line below commented out because it was making an unneeded POST request. 
    // filterManager.setState({filters: filter_arr});

    import("./statelib.js")
      .then((module) => {
        module.setStateItem('filter', filter_arr);
      });
    console.log('applying filters');
}



/**
  * populateHierarchicalFilters(termData)
  * ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈
  * Populates a collection of filters in the menu and creates the various
  * filter-options and subpanels.  
  * @param termData - object contains 'search' values
  * @return none
  */

function populateHierarchicalFilters(termData) {

  const type_terms = termData['search'];
  let type_panel = document.getElementById('type-filter-panel');
  type_panel.setAttribute("data-taxonomy", "inventory_main_type");
  recursiveMenuBuild(type_terms, type_panel);
  bindCheckboxToMoreAndPanel(type_panel); 

  const origin_terms = termData['inventory_item_origin'];
  let origin_panel = document.getElementById('origin-filter-panel');
  origin_panel.setAttribute("data-taxonomy", "inventory_item_origin");
  recursiveMenuBuild(origin_terms, origin_panel);
  bindCheckboxToMoreAndPanel(origin_panel); 
}



/**
  * setChildren(root_element, action)
  * ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈
  * Set the status of child option checkboxes as HTML elements
  * @param {HTMLElement} root_element - parent HTML object
  * @return none
  */

function setChildren(root_element, action) {
  // for all children set checkbox
  let checkboxes = root_element.querySelectorAll('input[type="checkbox"]');
  cb_arr = Array.from(checkboxes);
  switch (action) {
    case "checked":
      cb_arr.forEach((cb) => {
        let box = cb.nextElementSibling;
        // box.classList.add("implicit");
        cb.checked = true;
      });
      break;
    case "unchecked":
      cb_arr.forEach((cb) => {
        let box = cb.nextElementSibling;
        if (box.classList.contains("implicit")) {
          box.classList.remove("implicit");
        }
        cb.checked = false;
      });
      break;
    case "implicit":
      cb_arr.forEach((cb) => {
        let box = cb.nextElementSibling;
        if (box.classList.add("implicit")) {
          box.classList.add("implicit");
        }
        cb.checked = true;
      });
      break;
    default:
      console.log('default case');
      break;
  }
}



/**
  * bindCheckboxToMoreAndPanel(root_element)
  * ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈
  * Binding for options checkbox item, and subpanel expand and collapse events
  * @param {HTMLElement} root_element - parent HTML object
  * @return none
  */

function bindCheckboxToMoreAndPanel(root_element) {
 
  let type_panel = document.getElementById('type-filter-panel');
  let checkboxes = root_element.querySelectorAll('input[type="checkbox"]');
  cb_arr = Array.from(checkboxes);

  let panels = root_element.getElementsByClassName('subpanel');
  let mores = root_element.getElementsByClassName('more');
  panel_arr = Array.from(panels);
  more_arr = Array.from(mores);
  more_arr.forEach((more) => {
    const more_id = more.getAttribute('data-id');
    cb_arr.forEach((checkbox) => {
      const check_id = checkbox.getAttribute('data-id');
      if (more_id === check_id) {
        checkbox.addEventListener('click', () => checkboxAction(checkbox, more));
      }
    });
    panel_arr.forEach((panel) => {
      const panel_id = panel.getAttribute('data-id');
      if (more_id === panel_id) {
        more.addEventListener('click', () => moreButtonAction(more, panel));
      }
    });
  });
}



/**
  * recursiveMenuBuild(items, parent_element)
  * ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈
  * Menu builder for the hierachical taxonomies like inventory_main_type
  * @param {Array} items - list of items 
  * @return none
  */

function recursiveMenuBuild(items, parent_element) {

  items.forEach((item) => {

    // create a menu option entry
    let filter_option = document.createElement('div');
    filter_option.classList.add('filter-option');
    filter_option.setAttribute("id", item.term_id);
    filter_option.setAttribute("data-name", item.name);
    filter_option.style.position = "relative";

    let filter_label = document.createElement('label');
    filter_label.classList.add('simpui-checkbox');

    let filter_input = document.createElement('input');
    filter_input.setAttribute('type', 'checkbox');
    filter_input.setAttribute('data-id', item.term_id);
    filter_input.setAttribute('data-slug', item.slug);

    let filter_box = document.createElement('span');
    filter_box.classList.add('simpui-box');

    let filter_name = document.createElement('span');
    filter_name.innerText = item.name;
    filter_name.classList.add('simpui-checkbox-label');
    
    filter_label.appendChild(filter_input);
    filter_label.appendChild(filter_box);
    filter_label.appendChild(filter_name);
    filter_option.appendChild(filter_label);
    parent_element.appendChild(filter_option);

    if (item.children instanceof Array) {
      if (item.children.length > 0) {

        // more button to reveal children
        let filter_more = document.createElement('span');
        filter_more.setAttribute('data-id', item.term_id);
        filter_more.classList.add('more');
        // filter_more.textContent = 'more..';
        filter_option.appendChild(filter_more);

        // create hidden subpanel which shows when parent is ticked
        let filter_subpanel = document.createElement('div');
        filter_subpanel.setAttribute('data-id', item.term_id);
        filter_subpanel.classList.add('subpanel');
        filter_option.appendChild(filter_subpanel);
        recursiveMenuBuild(item.children, filter_subpanel);
      }
    }
  });
}



/**
  * addFilterOption(data, el)
  * ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈
  * Build filter-option HTML from individual entry item data and update
  * the supplied HTML element contents
  * @param {Array}        data  - item data object 
  * @param {HTMLElement}  el    - HTMLElement to append the filter-option to 
  */

function addFilterOption(data, el) {
  el.innerHTML += `
      <div class="filter-option" id="${data.term_id}" style="display: flex; flex-direction: row;">
        <label class="simpui-checkbox" style="gap:0.325rem">
          <input type="checkbox" data-id="${data.term_id}" data-slug="${data.slug}" required/>
          <span class="simpui-box"></span><span class="simpui-checkbox-label">
            ${data.name}
          </span>
        </label>
      </div>`;
}



/**
  * populateFlatFilters(termData)
  * ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈
  * Menu builder for the flat / non-hierachical taxonomies (default)
  * @param termData - object contains 'search' values
  * @return none
  */

function populateFlatFilters(termData) {
  
  const media_terms = termData['media_type'];
  media_terms.forEach((term) => {
    let media_panel = document.getElementById('media-filter-panel');
    media_panel.setAttribute("data-taxonomy", "media_type");
    addFilterOption(term, media_panel);
  });
}