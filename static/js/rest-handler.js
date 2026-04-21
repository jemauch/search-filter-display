//  ╭──────────────┬──────────────────────────────────┬───────┬────────────╮
//  │ NAME:        │ Rest Handler v0.01               │ DATE: │ 2026-04-09 │
//  ├──────────────┼──────────────────────────────────┴───────┴────────────┤
//  │ DESCRIPTION: │ REST requests and query creation library              │
//  │ PROJECT:     │ SIGGRAPH History Archive Website Search Filter Plugin │
//  ╰──────────────┴───────────────────────────────────────────────────────╯
//
//  Description:  Supporting JavaScript for filter and interactive display
//  Version:      0.01
//  Last Change:  9 April 2026
//  Author:       Ken Stewart <kengfx@gmail.com>
// 
//  This code is a supporting library only implementing functions to   
//  call wordpress specific php and pod searches using REST API requests.         
//  This was created expressly for the ACM SIGGRAPH History Archive at BGSU, 
//  all software is provided as-is and is not licensed for commercial use 
//  by anyone outside of the ACM unless explicitly authorized by the author
//  or the presiding ACM Siggraph History Archive team.


import { getState, getStateItem, setState, setStateItem, setResults, getResults, 
  getFunc } from './statelib.js';
import {rebuildTable, rebuildGrid} from './displaylib.js';



                                                                         

var sfd_inventory = "sfd/v1/archive_inventory"
var jQuery = window.jQuery;
var $ = jQuery;

/*
  QueryDetails object w/function
  ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈
  OBJECT: define which fields are needed to display in the table
  also add functions for constructing the query and adding filters to the
  results. Adds defaults to the query.
  ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈ */

const QueryDetails = {
  
  pagenum: 1,
  per_page: 15,
  show_filter: "false",
  show_search: "false",
  mode: "list",
  orderby: "&q[orderby]=inventory_year",
  order: "DESC",
  pod: "",
  filt: "",
  childof: "",
  fields: [
  'id',
  'type',
  'link',
  'inventory_title',
  'inventory_image-one',
  'inventory_item_main_type',
  'inventory_main_type',
  'inventory_year',
  'inventory_volume',
  'inventory_number',
  'inventory_total_number_of_item',
  '_links'
  ],
  baseurl: 'localhost',
  endpoint: '',
  setEndpoint: function (e) {
    var links = document.getElementsByTagName( 'link' );
    var link = Array.prototype.filter.call( links, function ( item ) {
      return (item.rel === 'https://api.w.org/');
    });
    var api_root = link[0].href;
    this.endpoint = `${api_root}${e}`;
  },
  setPage: function (pnum) {
    let oldnum = this.pagenum;
    this.pagenum = pnum;
    console.log(`page: ${oldnum} changed to page: ${pnum}`);
  },
  setPerPage: function (per) {
    let oldper = this.per_page;
    this.per_page = per;
    console.log(`per_page: ${oldper} changed to per_page: ${per}`);
  },
  queryCompile: function () {
    let comp = `${this.endpoint}?${this.filt}${this.childof}q[limit]=${this.per_page}&q[page]=${this.pagenum}${this.orderby}`;
    console.log(comp);
    return comp;
  },
  addFilter: function (key, val) {
    if (this.filt === "") { 
      // http://localhost/wp-json/sfd/v1/archive_inventory?q[filter]=&q[orderby]=inventory_year&q[limit]=10
      this.filt += `q[filter]=${key},${val}&`;
    } else {
      this.filt += `q[filter]=${key},${val}&`;
    }
    console.log(`filter is now: ${this.filt}`);
  },
  setChildOf: function (key) {
    // http://localhost/wp-json/sfd/v1/archive_inventory?q[childof]=
    if (key == "") {
      this.childof = "";
    } else {
    this.childof = `q[childof]=${key}&`;
    console.log(`childof is now: ${this.childof}`);
    }
  },
  clearChildOf: function () {
    this.childof = "";
  },
  setOrderBy: function (orderby) {
    this.orderby = `&q[orderby]=${orderby}`;
  },
  clearFilter: function () {
    this.filt = "";
  }
}






//  Function: getFromEndpoint - url(string), filter(null)
//  Description: retreive data using custom api endpoint

async function getFromEndpoint(url, filter = null) {

  console.log(getFunc(new Error().stack));
  console.log(url, filter);

  try {
    if (filter != null) {
       url = url + filter;
    }
    // if (!resp.ok) {
    //   throw new Error(`Error: ${url}`);
    // }

    const resp = fetch(url)
      .then(res => res.json())
      .then((res) => {
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
    // added lookup information
    updateResults(results_obj);
  });
  } catch (err) {
    console.error('fetch error:', err);
  }
}





function dropdownHandler( event ) {
  const level = event.data.level; 
  const t = event.target;
  const chosen_name = t.textContent;
  const chosen_id = t.getAttribute("value");
  const selection = { name: chosen_name, id: chosen_id};
  if (chosen_name === 'View All') {
    setStateItem('childof', "");
    QueryDetails.setChildOf("");
  } else {
    setStateItem('childof', chosen_id);
  }
  console.log(selection);
  
  function setWidgetVisibility(widget_name, isvisible) {
    const w = $(widget_name);
    if (isvisible) {
      w.show();
    } else {
      w.hide();
    }
  }

  // after request is resolved....

  function childOptions() {
    let res_obj = getResults();
    const lookup = res_obj.lookup;
    const children_ids = res_obj.hierarchy[parent_id];
    let options_array = [];

    children_ids.forEach((child_id) => {
      let newOption = document.createElement("span");
      newOption.setAttribute("data-term", child_id);
      newOption.setAttribute("style", "height: 24px;");
      newOption.textContent = lookup[child_id];
      options_array.push(newOption);
      });
    }
  }


  $("#current-per-page").bind('dropDownEvent', function() {
    let newValue = this.textContent;
    console.log(this);
    setStateItem('perpage', newValue);
  });

  const st_obj = $('#state_object');

  st_obj.bind('change', function() {
    console.log('STATE_OBJECT changed');
    updateQuery(st_obj);
  });

  let filter_option = $("#current-filter");
  filter_option.bind('dropDownEvent', function() { 
    console.log(`filter option changed: ${this.textContent}`);
    updateQuery(st_obj);
  });

  // let rpp_amt_hidden = $("rpp_amt");
  // rpp_amt_hidden.bind('input', function() { 
  //   console.log('result amount changed'); 
  // });



  $("#current-per-page").bind('dropDownEvent', function() {
    let newValue = this.textContent;
    console.log(this);
    setStateItem('perpage', newValue);
  });


  function logger(log_message) {
      let logtime = new Date();
      const uuid = crypto.randomUUID();
      console.log(`[${logtime.toLocaleString()}] - ${log_message} [${uuid}]`);
  }


  function updateQuery(inline_state) {
    /* updates query from html object */
    console.log(new Error().stack); 
    let st = getState();
    QueryDetails.pagenum = st.page;

    if (st.childof) {
      if (st.childof === 0 || st.childof === "") {
        // const st_obj = $('#state_object');
        QueryDetails.setChildOf("");
        QueryDetails.clearChildOf();
      } else {
      QueryDetails.setChildOf("");
      QueryDetails.setChildOf(st.childof);
      }
    }
    QueryDetails.per_page = st.perpage;
    QueryDetails.show_search = st.search;
    QueryDetails.mode = st.mode;
    QueryDetails.orderby = st.orderby;
    QueryDetails.pod = st.pod;

    let new_query = QueryDetails.queryCompile();
    console.log("New query: ", new_query);
    /* kick off the search */
    getFromEndpoint(QueryDetails.queryCompile());

    // getFromEndpoint(QueryDetails.queryCompile()).then((result_data) => {
    //   updateResults(result_data);
    // }).catch(err)(console.log('getFromEndpoint promise:', e));
  }



  function updateResults(results_obj) {
    
    // await results_obj;

    console.log('result: ', results_obj);
    console.log(getFunc(new Error().stack));
    
    // HINT: put in new results object
    $('#results_object').text(JSON.stringify(results_obj));
    
    // every time the table is updated with results it updates the results object,
    // aswell as updating the state object with the page range and total entries found.
    
    setStateItem('firstpage', "1", false);
    setStateItem('lastpage', results_obj.pages, false);
    setStateItem('total_found', results_obj.total, false);
    const container = $('#container');
    container.innerHTML = '';


    // HINT: Get missing bits for pagination
    const total_found = getStateItem('total_found') ?? 0;
    const current_page = getStateItem('page');
    const per_page = getStateItem('perpage');
    let count = 0;
    if (total_found > 0){
      count = total_found/Number(per_page);
    }

    // update pagination
    count = ~~count;
    const display_page_count = `${current_page} / ${count}`;
    $('#page_counter').text(display_page_count);
    if (getStateItem('mode') == 'list') {
      logger('should rebuild table now');
      rebuildTable(results_obj.entries, results_obj.lookup);
    }
    if (getStateItem('mode') == 'grid') {
      rebuildGrid(results_obj.entries, results_obj.lookup);
    }
  }



jQuery(document).ready(function($) {
  // NOTE: Instantiate main thread
  main($);
});

// NOTE: Running as main thread
function main($) {
  // get the details from the json embedded in the page 

  let state_obj = $("#state_object");
  state_obj = JSON.parse(state_obj.text());
  console.log(state_obj); 
  
  // adjust query-details to match sfd
  QueryDetails.pagenum = state_obj.page;
  QueryDetails.per_page = state_obj.perpage;
  // QueryDetails.show_filter = state_obj.filter;
  // QueryDetails.show_search = state_obj.search;
  QueryDetails.mode = state_obj.mode;
  QueryDetails.setOrderBy('inventory_year');
  QueryDetails.pod = state_obj.pod;
  QueryDetails.setEndpoint(sfd_inventory);




  // call the main REST endpoint request 
  getFromEndpoint(QueryDetails.queryCompile());     // FUNC:

  //  NOTE: Dropdown binding

  const filter01 = $("#current-filter-level-01");
  const group01 = $("#dropdown-group-level-01");
  filter01.index = 1;
  group01.index = 1;
  const filter02 = $("#current-filter-level-02");
  const group02 = $("#dropdown-group-level-02");
  filter02.index = 2;
  group02.index = 2;
  const filter03 = $("#current-filter-level-03");
  const group03 = $("#dropdown-group-level-03");
  group03.index = 3;
  filter03.index = 3;
  const filter04 = $("#current-filter-level-04");
  const group04 = $("#dropdown-group-level-04");
  group04.index = 4;
  filter04.index = 4;

  //  NOTE: ---- One by One method --- starting with small chunks ------------
  
  // * Filter01 is already set up with options.
  // * the logic is build into the data-term value
  
  filter01.on('dropDownEvent', { level: 1 }, dropdownHandler);
  filter02.on('dropDownEvent', { level: 2 }, dropdownHandler);
  filter03.on('dropDownEvent', { level: 3 }, dropdownHandler);
  filter04.on('dropDownEvent', { level: 4 }, dropdownHandler);


  //  NOTE:  Bindings to buttons and changes
  
  $('#list-button').bind('click', function() {
    let st = getState();
    console.log(st);
    if (st.mode != 'list') {
      setStateItem('mode', 'list');
    }
  });
  


  $('#grid-button').bind('click', function() {
    let st = getState();
    if (st.mode != 'grid') {
      setStateItem('mode', 'grid');
      console.log(st);
    }
  });


  // NOTE: Bottom buttons bindings

  $('#goto_firstpage_button').bind('click', function() {
    let st = getState();
    if (st.firstpage != st.page) {
      setStateItem('page', getState().firstpage);
    }
  });


  $('#goto_lastpage_button').bind('click', function() {

    let st = getState();
    if (st.lastpage != st.page) {
      setStateItem('page', getState().lastpage);
    }
  });
  

  
  $('#back_onepage_button').bind('click', function() {
    let st = getState();
    if (st.firstpage != st.page) {
    }
    let back_page = Math.max(1, Number(st.page) - 1);
    setStateItem('page', back_page.toString());
  });



  $('#forward_onepage_button').bind('click', function() {
    let st = getState();
    st.lastpage
    st.page;
    let forward_page = Math.min(Number(st.lastpage), Number(st.page) + 1);
    setStateItem('page', forward_page.toString());
  });

  // end of main
}

