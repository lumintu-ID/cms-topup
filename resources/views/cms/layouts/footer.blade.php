
</main>

<script src="/build/assets/app.61f518c6.js"></script>

<script>

    function dateFormat() {
        const today = new Date();
	    const yyyy = today.getFullYear();
        let mm = today.getMonth() + 1; // Months start at 0!
        let dd = today.getDate();

        if (dd < 10) dd = '0' + dd;
        if (mm < 10) mm = '0' + mm;

        const formattedToday = dd + '/' + mm + '/' + yyyy;

        return formattedToday
    }


    window.Echo.channel("messages").listen("Transaction", (event) => {
        const notyf = new Notyf({
            position: {
                x: 'right',
                y: 'top',
            },
            types: [
                {
                    type: 'info',
                    background: 'blue',
                    icon: {
                        className: 'fas fa-info-circle',
                        tagName: 'span',
                        color: '#fff'
                    },
                    dismissible: false
                }
            ]
        });
        notyf.open({
            type: 'info',
            message: 'New transaction From '+event.message.email+ ' in '+ dateFormat()
        });
    });
</script>


<!-- Core -->
<script src="{{ url('assets') }}/vendor/@popperjs/core/dist/umd/popper.min.js"></script>
<script src="{{ url('assets') }}/vendor/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- Vendor JS -->
<script src="{{ url('assets') }}/vendor/onscreen/dist/on-screen.umd.min.js"></script>

<!-- Slider -->
<script src="{{ url('assets') }}/vendor/nouislider/distribute/nouislider.min.js"></script>

<!-- Smooth scroll -->
<script src="{{ url('assets') }}/vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>

<!-- Charts -->
<script src="{{ url('assets') }}/vendor/chartist/dist/chartist.min.js"></script>
<script src="{{ url('assets') }}/vendor/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script>

<!-- Datepicker -->
<script src="{{ url('assets') }}/vendor/vanillajs-datepicker/dist/js/datepicker.min.js"></script>

<!-- Sweet Alerts 2 -->
<script src="{{ url('assets') }}/vendor/sweetalert2/dist/sweetalert2.all.min.js"></script>

<!-- Moment JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"></script>

<!-- Vanilla JS Datepicker -->
<script src="{{ url('assets') }}/vendor/vanillajs-datepicker/dist/js/datepicker.min.js"></script>

<!-- Notyf -->
<script src="{{ url('assets') }}/vendor/notyf/notyf.min.js"></script>

<!-- Simplebar -->
<script src="{{ url('assets') }}/vendor/simplebar/dist/simplebar.min.js"></script>

<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>

<!-- Volt JS -->
<script src="{{ url('assets') }}/js/volt.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" crossorigin="anonymous"></script>

</body>

</html>