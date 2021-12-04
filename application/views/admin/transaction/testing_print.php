<button class='btn btn-md printdm'>TEST</button>

<script src="<?php echo base_url('assets/global/plugins/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/jquery-migrate.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/global/plugins/jquery-ui/jquery-ui.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets_noondev/js/webprint.js'); ?>" type="text/javascript"></script>
<script>
    var baseurl = "<?php print base_url(); ?>";

    webprint = new WebPrint(true, {
        relayHost: "127.0.0.1",
        relayPort: "8080",
        readyCallback: function(){
            
        }
    });

    $(document).on('click', '.printdm', function() {
        var data = ['\x1B' + '\x40',          // init
            '\x1B' + '\x21' + '\x39', // em mode on
            <?="'".sprintf("%'-80s", '')."'";?>+ '\x0A',
            '\x1B' + '\x69',          // cut paper
            '\x10' + '\x14' + '\x01' + '\x00' + '\x05',  // Generate Pulse to kick-out cash drawer**
                                                        // **for legacy drawer cable CD-005A.  Research before using.
                                                        // see also http://keyhut.com/popopen4.htm
        ];

        webprint.printRaw(data, 'Nota');
    });
</script>