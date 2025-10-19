declare module '@hotwired/stimulus' {
    export class Controller {
        readonly element: Element;
        initialize?(): void;
        connect(): void;
        disconnect(): void;
    }
}
