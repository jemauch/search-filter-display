/*
 *   This content is licensed according to the W3C Software License at
 *   https://www.w3.org/Consortium/Legal/2015/copyright-software-and-document
 *
 *   Simple accordion pattern example
 */

'use strict';

class Disclosure {
  constructor(domNode) {
    this.buttonEl = domNode;

    const controlsId = this.buttonEl.getAttribute('aria-controls');
    this.contentEl = document.getElementById(controlsId);

    this.open = this.buttonEl.getAttribute('aria-expanded') === 'true';

    // add event listeners
    this.buttonEl.addEventListener('click', this.onButtonClick.bind(this));
  }

  onButtonClick() {
    this.toggle(!this.open);
  }

  toggle(open) {
    // don't do anything if the open state doesn't change
    if (open === this.open) {
      return;
    }

    // update the internal state
    this.open = open;

    // handle DOM updates
    this.buttonEl.setAttribute('aria-expanded', `${open}`);
    if (open) {
      this.contentEl.removeAttribute('hidden');
    } else {
      this.contentEl.setAttribute('hidden', '');
    }
  }

  // Add public open and close methods for convenience
  open() {
    this.toggle(true);
  }

  close() {
    this.toggle(false);
  }
}

// init disclosure triggers
const disclosures = document.querySelectorAll('.disclosure-trigger');
disclosures.forEach((disclosureEl) => {
  new Disclosure(disclosureEl);
});

// init modal
const modalToggle = document.querySelector('.modal-toggle');
const modal = new Disclosure(modalToggle);

// Pressing ESC closes modal
document.addEventListener("keydown", (event) => {
    if (event.key === "Escape") {
        modal.close();
    }
});

// close modal when modalClosers are clicked
const modalClosers = document.querySelectorAll('.modal-close');
modalClosers.forEach((triggerEl) => {
    triggerEl.addEventListener('click', function() {
        modal.close();
    });
});