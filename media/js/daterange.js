$(function(){
    /*****************************
         * Date Range
         */
    //        var nowTemp = new Date();
    //        var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
    //     
    var checkin = $('#datefrom').datepicker({
        format : 'yyyy-mm-dd'
    }).on('changeDate', function(ev) {
        if (ev.date.valueOf() > checkout.date.valueOf()) {
            var newDate = new Date(ev.date)
            newDate.setDate(newDate.getDate());
            checkout.setValue(newDate);
       }
        checkin.hide();
        oTable.fnDraw();
        $('#dateto')[0].click();
    }).data('datepicker');
    var checkout = $('#dateto').datepicker({
        format : 'yyyy-mm-dd',
        onRender: function(date) {
            //alert('tes');
            return date.valueOf() < checkin.date.valueOf() ? 'disabled' : '';
        }
    }).on('changeDate', function(ev) {
        checkout.hide();
        oTable.fnDraw();
    }).data('datepicker');
        
/*****************************
         * End Date Range
         */
})