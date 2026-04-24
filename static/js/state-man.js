//  ╭──────────────┬──────────────────────────────────┬───────┬────────────╮
//  │ NAME:        │ StateMan v0.11                   │ DATE: │ 2026-04-01 │
//  ├──────────────┼──────────────────────────────────┴───────┴────────────┤
//  │ DESCRIPTION: │ Custom State management for User Interface            │
//  │ PROJECT:     │ SIGGRAPH History Archive Website Search Filter Plugin │
//  ╰──────────────┴───────────────────────────────────────────────────────╯



/**
  * StateMan class
  * ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈
  * Core State Management Object Class, which handles storage of SPA data
  * and UI updates alongside callback subscriptions
  */

class StateMan {
  constructor(initState = {}) {
    this.state = initState;
    this.observers = [];
  }
  subscribe(observer) {
    this.observers.push(observer);
    return () => {
      this.observers = this.observers.filter(obs => obs !== observer);
    };
  }
  setState(newState) {
    this.state = { ...this.state, ...newState };
    this.notifyObservers();
  }
  getState() {
    return { ...this.state };
  }
  notifyObservers() {
    this.observers.forEach(observer => observer(this.getState()));
  }
}




  // ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈ State Store (tableManager) ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈

  const tableManager = new StateMan({
    pod: 'archive_inventory',
    mode: 'list',  // list or mode
    filtercard_hidden: true,
    perpage: 25,
    page: 1,
    search: false,
    filter: 'View All',
    missingitems: false,
    conference: 'both',
    orderby: 'inventory_year DESC',
    pagination: true
  });

  const filterManager = new StateMan({
    filters: {}
  });





  // ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈ filterDisplay function ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈


  const filterDisplay = (state) => {
    let f = JSON.stringify(state.filters);
    document.getElementById('filtertype').textContent = `Filter Type: ${f}`;
  };





  // ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈ mini-functions state display ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈

  const tableModeDisplay = (state) => {
    document.getElementById('mode')
      .textContent = `Mode: ${state.mode}`;
  };
  const tableFilterCardDisplay = (state) => {
    document.getElementById('filtercard_hidden')
      .textContent = `Popup: ${state.filtercard_hidden}`;
  };
  const tablePodDisplay = (state) => {
    document.getElementById('pod')
      .textContent = `Pod: ${state.pod}`;
  };
  const tablePerPageDisplay = (state) => {
    document.getElementById('perpage')
      .textContent = `Perpage: ${state.perpage}`;
  };
  const tablePageDisplay = (state) => {
    document.getElementById('page')
      .textContent = `Page: ${state.page}`;
  };
  const tableFilterDisplay = (state) => {
    document.getElementById('filter')
      .textContent = `Filter: ${state.filter}`;
  };


  const unsubMode = tableManager.subscribe(tableModeDisplay);
  const unsubPod = tableManager.subscribe(tablePodDisplay);
  const unsubPerPage = tableManager.subscribe(tablePerPageDisplay);
  const unsubPage = tableManager.subscribe(tablePageDisplay);
  const unsubFilter = tableManager.subscribe(tableFilterDisplay);
  const unsubFilterCard = tableManager.subscribe(tableFilterCardDisplay);






  // ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈ UI Event Bindings ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈


  // display buttons
  document.getElementById('list-button').addEventListener('click', () => {
    tableManager.setState({ mode: 'list' });
  });
  document.getElementById('grid-button').addEventListener('click', () => { 
    tableManager.setState({ mode: 'grid' });
  });

  // navigation buttons
  document.getElementById('forward-onepage-button')
    .addEventListener('click', () => {
      _page = tableManager.getState().page;
      _page += 1;
      tableManager.setState({ page: _page });
  });
  document.getElementById('back-onepage-button')
    .addEventListener('click', () => { 
      _page = tableManager.getState().page;
      _page -= 1;
      tableManager.setState({ page: _page });
  });
  document.getElementById('goto-firstpage-button')
    .addEventListener('click', () => { 
      let _page = 1;
      tableManager.setState({ page: _page });
  });

  // dropdown cpp & filter
  document.getElementById('current-per-page')
    .addEventListener('dropDownEvent', (e) => { 
      const current = Number(e.target.textContent);
      tableManager.setState({ perpage: current });
  });






  // ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈ Bind Filter Buttons ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈






  // ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈ Initialize the UI ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈


  tableModeDisplay(tableManager.getState());
  tableFilterCardDisplay(tableManager.getState());
  tablePodDisplay(tableManager.getState());
  tablePerPageDisplay(tableManager.getState());
  tableFilterDisplay(tableManager.getState());
  tablePageDisplay(tableManager.getState());
  filterDisplay(filterManager.getState());
