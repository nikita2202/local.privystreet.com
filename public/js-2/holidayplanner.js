var holiday_slot = "";
var monthNames = ["January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
];
function getHolidaySlot(dynamicContent, slot) {
    return "<div class='white-wrapper newSlot'>" +
            "<h2 class='top-heading m-b-sm'>" + slot + "</h2>" +
            // "<span class='trash-icon'>" +
            // "<i data-toggle='modal' data-target='#myModal-trash2' class='fa fa-trash cancelAddSlot' title='Delete' aria-hidden='true'></i>" +
            // "</span>" +
            "<div class='table-responsive custom-tbl clearfix m-t-sm'>" +
            "<table id='example' class='list-table table ' cellspacing='0' width='100%'>" +
            "<thead>" +
            "<tr>" +
            "<th>S.no</th>" +
            "<th>Date</th>" +
            "<th>Event Name</th>" +
            "</tr>" +
            "</thead>" +
            "<tbody>" +
            dynamicContent +
            "</tbody>" +
            "</table>" +
            "</div>" +
            "<div class='button-wrap text-center'>" +
            "<input type='Submit' value='Cancel' class='commn-btn cancel cancelAddSlot'>" +
            "<input type='Reset' value='Save' class='commn-btn save submitAddSlot'>" +
            "</div>" +
            "</div>";
}
var updatedSlotRef;
$(document).ready(function () {
    $('.editEvent').click(function () {
        var eventId = $(this).attr('data-holiday-attr');
        updatedSlotRef = $(this).parent().prev();
        var eventDetail = updatedSlotRef.text();

        $('.eventDetail').val(eventDetail);
        $('.eventId').val(eventId);
        $('#myModal-edit').modal('show');
    });

    $('.updateEvent').click(function () {
        var eventDetail = $('.eventDetail').val();
        var eventId = $('.eventId').val();
        $.ajax({
            type: "get",
            url: "admin/AjaxUtil/updateEvents",
            dataType: 'json',
            data: {eventDetail: eventDetail, eventId: eventId},
            success: function (respdata) {
                if (respdata.code == 200) {
                    updatedSlotRef.text(eventDetail);
                    $('#myModal-edit').modal('hide');
                }
            }
        });
    });
    $('.updateEventCancel').click(function () {
        $('#myModal-edit').modal('hide');
    });
    $('.applyFilterHoliday').click(function () {
       
        var month = $('#filterMonthData').val();        
       // window.location.origin = '/Backend/admin/holiday_planner?month=' + month;
        window.location.href = window.location.origin = '/admin/holiday_planner?month=' + month;;
    });
    $('.cancelAddSlot').click(function () {
        $('.filter-wrap').removeClass('active');
    });
    $('.addSlot').click(function () {
        $('.filter-wrap').removeClass('active');
    });

    $('.addSlot').click(function () {
        var startDate = $('.slotStartDate').val();
        var endDate = $('.slotEndDate').val();
        var date1 = startDate;
        var date2 = endDate;
        /*
         * Format the date in m-d-Y
         */
        var datearray = date1.split("-");
        date1 = datearray[1] + '-' + datearray[0] + '-' + datearray[2];
        var datearray = date2.split("-");
        date2 = datearray[1] + '-' + datearray[0] + '-' + datearray[2];

        var dateObj1 = new Date(date1);
        var dateObj2 = new Date(date2);

        var timeDiff = Math.abs(dateObj2.getTime() - dateObj1.getTime());
        var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
        var params = [];
        params['daydiff'] = diffDays;
        params['startDate'] = date1;
        params['endDate'] = date2;
        if (startDate != '' && endDate != '') {
            $('.addSlotBtn').attr('disabled', true);
            addslot(params);
        } else {
            //alert('Please select some date-range');
            $("#image-error").html('Please select some date-range');
             $('.filter-wrap').addClass('active');
        }
       
    });
});
$(document).on('click', '.cancelAddSlot', function () {
    removeSlot();
});

/* remove slot function start*/
function removeSlot() {
    $('.newSlot').remove();
    $('#filter-side-wrapper').prop("disabled", false);
}
/* remove slot function end */

/*
 * Add Slot
 */
$(document).on('click', '.submitAddSlot', function () {
    var eventDetail = [];
    var saveEvent = true;
    $('.eventDate').each(function (index) {
        var eventObj = {};
        var date = $(this).text();
        var eventName = $('.' + date).val();
        if (eventName.length == 0) {
            $('.' + date).next().text('Event Name is Required');
            saveEvent = false;
        }
        eventObj[date] = eventName;
        eventDetail.push(eventObj);
    });
    if (saveEvent) {
        saveEvents(eventDetail);
    }
});

function saveEvents(eventDetail) {
    $.ajax({
        type: "get",
        url: "admin/AjaxUtil/addevents",
        dataType: 'json',
        data: {eventDetail: JSON.stringify(eventDetail)},
        success: function (respdata) {
            if (respdata.code == 200) {
                window.location.reload();
            }
        }
    });
}
/*
 * Append Slot Html
 */
function addslot(params) {
    var slot_html = "";
    var startDate = params['startDate'];
    var endDate = params['endDate'];
    var date = new Date(startDate);
    for (var i = 1; i <= (params['daydiff'] + 1); i++) {
        slot_html += "<tr class='text-center'>" +
                "<td>" + i + "</td>" +
                "<td><span class='eventDate'>" + getDate(date) + "</span></td>" +
                "<td>" +
                "<input type='text' class='" + getDate(date) + "' maxlength='100' placeholder='Enter Event Name'>" +
                "<label class='alert-danger'></label>" +
                "</td>" +
                "</tr>";
        date.setDate(date.getDate() + 1);
    }
    var slot = getSlotDetail(startDate, endDate);
    $('.holiday-slot').prepend(getHolidaySlot(slot_html, slot));
}

function getDate(date) {
    var day = (date.getDate() < 10) ? '0' + date.getDate() : date.getDate();
    var month = ((date.getMonth() + 1) < 10) ? '0' + (date.getMonth() + 1) : date.getMonth() + 1;
    var year = date.getFullYear();
    return day + '-' + month + '-' + year;
}
function getSlotDetail(startDate, endDate) {
    var date1 = new Date(startDate);
    var date2 = new Date(endDate);
    var day1 = (date1.getDate() < 10) ? '' + date1.getDate() : date1.getDate();
    var day2 = (date2.getDate() < 10) ? '' + date2.getDate() : date2.getDate();
    var month1 = date1.getMonth();
    var month2 = date2.getMonth();
    var slot;
    if (month1 == month2) {
        slot = day1 + ' to ' + day2 + ' ' + monthNames[month1];
    } else {
        slot = day1 + ' ' + monthNames[month1] + ' to ' + day2 + ' ' + monthNames[month2];
    }
    return slot;
}

/*
 * Add Slot Ends
 */