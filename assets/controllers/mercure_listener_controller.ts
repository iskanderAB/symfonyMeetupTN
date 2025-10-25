/// <reference path="../types/stimulus.d.ts" />
import { Controller } from '@hotwired/stimulus';
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
    }


    disconnect() {
    }
}
