export {};
declare global {
    interface Window {
        KTUtil: any;
        KTCard: any;
        KTCookie: any;
        KTMenu: any;
        Popper: any;
        KTOffcanvas: any;
        $: any;
        jQuery: any;
        axios: any;
        PerfectScrollbar: any;
        Pusher: any;
        Echo: any;
        [index: string]: any; // code for any property can be added dynamically
    }
}
