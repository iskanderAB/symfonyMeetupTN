/// <reference path="../types/stimulus.d.ts" />
import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://symfony.com/bundles/StimulusBundle/current/index.html#lazy-stimulus-controllers
*/

/* stimulusFetch: 'lazy' */
export default class extends Controller {

    initialize() {
        const url = new URL('https://example.com/.well-known/mercure');
        url.searchParams.append('topic', 'https://example.com/foo');
        url.searchParams.append('topic', 'bar');
        url.searchParams.append('topic', 'https://example.com/bar/{id}');

        const eventSource = new EventSource(url);

        eventSource.onmessage =  ({data}: MessageEvent<any>): void =>  {
            console.log(data);
        };
    }

    connect() {
        console.log("Hello iskander")
        // Called every time the controller is connected to the DOM
        // (on page load, when it's added to the DOM, moved in the DOM, etc.)

        // Here you can add event listeners on the element or target elements,
        // add or remove classes, attributes, dispatch custom events, etc.
        // this.fooTarget.addEventListener('click', this._fooBar)
    }

    // Add custom controller actions here
    // fooBar() { this.fooTarget.classList.toggle(this.bazClass) }

    disconnect() {
        // Called anytime its element is disconnected from the DOM
        // (on page change, when it's removed from or moved in the DOM, etc.)

        // Here you should remove all event listeners added in "connect()" 
        // this.fooTarget.removeEventListener('click', this._fooBar)
    }
}
