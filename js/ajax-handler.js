class DisplayButtonHandler {
  constructor(element_name, mode, sto) { 
    this.mode = mode;
    this.element_name = element_name;
    this.sto = sto;
    this.element = document.getElementById(element_name);
    this.element.addEventListener('click', this.handler);
  }
  // FUNC: Handler function
  handler = () => {
    this.sto.mode = this.mode;
    console.log(this.sto);
  }
}




// FUNC: Triggers on initial page load

jQuery(document).ready(function($) {

  console.log('ajax search triggered');

  // HINT: Function to call to change format of display, or reduce query amt.
  //       Increases in query will require and update too, but will be
  //       triggered after the ajax call for additional information.

  function rebuildTable(data) {
    $('#table-header').replaceWith(`
      <thead>
        <tr>
          <th>Year</th> <th style="text-align: left;">Title</th> <th>Category</th> <th></th> <th style="text-align: left;">Item Type</th> <th>Quantity</th>
        </tr>
      </thead>
      `);


    // $('#pagination_temp').html(data.data[0]['test']);

    let return_list = [];
    $.each(data.data, function(index, item) {
      let volnum = "";
      if (item.volume.length>0) {
        if (item.number.length>0){
          volnum = `(Vol. ${item.volume}, No. ${item.number})`;
        }
      }
      return_list.push(
        `<tr>
          <td style="text-align: center;">${item.year}</td>
          <td style="text-align: left;"><b><a href="${item.url}">${item.title} ${volnum}</a><b></td>
          <td style="text-align: center;">${item.category}</td>
          <td style="text-align: center;"></td>
          <td>${item.type}</td>
          <td style="text-align: center;">${item.quantity}</td>
        </tr>`);
    });
    $('#custom_search_results').html(return_list);
  }
  
  function rebuildGrid(data) {
    console.log('rebuild grid called');
  }

  function changePage(pnum){
    console.log(`current_page:....`);
    console.log(`change page number to ${pnum}`);
  }

  function updateResultsDisplay() {
    console.log();
    // 1. check if list or grid
    // 2. check how many results from state object
    // 3. delete/replace existing html with new results.
    // 4. if changing to grid mode, images should be lazy loaded
  // 5. and a placeholder is substituted to show loading.
  }
  // HINT: Function to change pages

  function changePageResults() {
    // * when new ajax search is warrented this is the function.
    
    const embedded_page_state_object = getState();
    jQuery.ajax({
      type: "post",
      dataType: "json",
      url: ajax_obj.ajaxurl,
      data: {
        action: 'fetch_search_results',
        nonce: ajax_obj.nonce,
        query: embedded_page_state_object,
      },
      beforeSend: function(data) {
        console.log(ajax_obj);
      },
      success: function(data) {

        // HINT: Updating the global results object for tracking
        $('#results_object').text(JSON.stringify(data));
        
        // HINT: Add in missing bits
        const total_found = getStateItem('total_found');
        const current_page = getStateItem('page');
        const per_page = getStateItem('perpage');
        let count = 0;
        if (total_found > 0){
          count = total_found/Number(per_page);
          console.log(count);
        }

        const display_page_count = `${current_page}/${count}`;
        $('#page_counter').text(display_page_count);
        
        console.log(data.data);
        if (ajax_obj.query_state.mode == 'list') {
          rebuildTable(data);
        }
        if (ajax_obj.query_state.mode == 'grid') {
          rebuildGrid(data);
        }
      },
    })
  }
  
  function initialResults() {
    // initialize search results.
    jQuery.ajax({
      type: "post",
      dataType: "json",
      url: ajax_obj.ajaxurl,
      data: {
        action: 'fetch_search_results',
        nonce: ajax_obj.nonce,
        query: ajax_obj.query_state,
      },
      beforeSend: function(data) {
        // console.log(ajax_obj);
      },
      success: function(data) {

        // HINT: Updating the global results object for tracking
        $('#results_object').text(JSON.stringify(data));
        
        // HINT: Add in missing bits
        const total_found = Number(data.data[0]['total_found']);
        ajax_obj.query_state.total_found = total_found;
        
        const current_page = ajax_obj.query_state['page'];
        const per_page = ajax_obj.query_state['perpage'];
        let count = 0;
        if (total_found > 0){
          count = total_found/Number(per_page);
          console.log(count);
        }

        ajax_obj.query_state.lastpage = count;
        ajax_obj.query_state.firstpage = 1;
        $('#state_object').text(JSON.stringify(ajax_obj.query_state));

        const display_page_count = `${current_page}/${count}`;
        $('#page_counter').text(display_page_count);
        
        console.log(data.data);
        if (ajax_obj.query_state.mode == 'list') {
          rebuildTable(data);
        }
        if (ajax_obj.query_state.mode == 'grid') {
          rebuildGrid(data);
        }
      },
    })
  }
  
  initialResults();




// NOTE: The state utility functions

  function getState() {
    const st = JSON.parse($("#state_object").text());
    return st;
  }
  
  function getStateItem(key) {
    const st = JSON.parse($("#state_object").text());
    return st[key];
  }

  function setState(data) {
    let st_obj = $("#state_object");
    st_obj.text(JSON.stringify(data));
    st_obj.trigger('change');
  }

  function setStateItem(key, value) {
    let st = getState();
    st[key] = value;
    let st_obj = $("#state_object");
    st_obj.text(JSON.stringify(st));
    st_obj.trigger('change');
  }


  //  NOTE:  Bindings to buttons and changes

  //  "pod": "archive_inventory",
  //  "mode": "list",
  //  "perpage": "15",
  //  "page": "1",
  //  "search": "false",
  //  "pagination": "true",
  //  "orderby": "inventory_year DESC"

  // NOTE:  Button bindings

  
  $('#list_button').bind('click', function() {
    st = getState();
    if (st.mode != 'list') {
      setStateItem('mode', 'list');
    }
  });
  
  $('#grid_button').bind('click', function() {
    st = getState();
    if (st.mode != 'grid') {
      setStateItem('mode', 'grid');
    }
  });
  // session = getState();
  // let list = new DisplayButtonHandler( "list-button", "list", session );
  // let grid = new DisplayButtonHandler( "grid-button", "grid", session );

  // NOTE: Dropdown binding

  $("#current-per-page").bind('dropDownEvent', function() {
    let newValue = this.textContent;
    console.log(this);
    setStateItem('perpage', newValue);
  });


  // NOTE: Bottom buttons bindings

  $('#goto_firstpage_button').bind('click', function() {
    st = getState();
    if (st.firstpage != st.page) {
      setStateItem('page', getState().firstpage);
    }
  });

  $('#goto_lastpage_button').bind('click', function() {
    st = getState();
    if (st.lastpage != st.page) {
      setStateItem('page', getState().lastpage);
    }
  });
  
  $('#back_onepage_button').bind('click', function() {
    st = getState();
    if (st.firstpage != st.page) {
    }
    back_page = Math.max(1, Number(st.page) - 1);
    setStateItem('page', back_page);
  });

  $('#forward_onepage_button').bind('click', function() {
    st = getState();
    st.lastpage
    st.page;
    forward_page = Math.min(Number(st.lastpage), Number(st.page) + 1);
    setStateItem('page', forward_page);
  });


  // Monitor changes in state object
  $('#state_object').bind('change', function() {
    console.log('standin for update function.', getState());
    changePageResults();
  });


  // setStateItem('mode', 'grid');
  // NOTE: Get the element
  let rpp_amt_hidden = document.getElementById("rpp_amt");

  // FUNC: Event listener for the dropdown results per-page
  rpp_amt_hidden.addEventListener('input', function (e) { console.log(e) });

})



// console.log(rpp_amt_hidden.value);
  // function old(){
  // $.ajax({
    // type: "post",
    // dataType: "json",
    // url: ajax_obj.ajaxurl,
    // data: {
    //   action: 'fetch_search_results',
    //   nonce: ajax_obj.nonce,
    //   query: ajax_obj.query_state,
    // },
    // beforeSend: function(data) {
    //   console.log(ajax_obj);
    // },
    // success: function(data) {
    //     let return_list = [];
    //     $.each(data.data, function(index, item) {
    //       let volnum = "";
    //       if (item.volume.length>0) {
    //         if (item.number.length>0){
    //           volnum = `(Vol. ${item.volume}, No. ${item.number})`;
    //         }
    //       }
        //   return_list.push(
        //     `<tr>
        //       <td style="text-align: center;">${item.year}</td>
        //       <td style="text-align: left;"><b><a href="${item.url}">${item.title} ${volnum}</a><b></td>
        //       <td style="text-align: center;">${item.category}</td>
        //       <td style="text-align: center;"></td>
        //       <td>${item.type}</td>
        //       <td style="text-align: center;">${item.quantity}</td>
        //     </tr>`);
        // });
        //
        // // TODO: replace header in the table
        //
        // $('#table-header').replaceWith(`
        //   <thead>
        //     <tr>
        //       <th>Year</th> <th style="text-align: left;">Title</th> <th>Category</th> <th></th> <th style="text-align: left;">Item Type</th> <th>Quantity</th>
        //     </tr>
        //   </thead>
        //   `);
        //
        // // TODO: generate results for page 1
        
        // $('#search-results').html(return_list);
        // $('#data-display')
    //   }
    // });
