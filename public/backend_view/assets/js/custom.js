$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
function EmailMask(email) {
    var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    return expr.test(email);
}

function disableButtonById(id) {
    $(id).attr("disabled", true);
}

function addLoadingIcon(id){
    $(id).html(loadingImage());
}

function validateFieldsByFormId(e) {
    console.log($(e));
    event.preventDefault();
    const formId = $(e).closest('form').attr('id');
    const formURL = $(e).closest('form').attr('action');
    const validationSpanId = $(e).data('validation');
    const formData = new FormData( $('#'+formId)[0])
    console.log(validationSpanId, 'validationSpanId', 'formURL', formURL);
    var error = validateFields(formId);
    var errorMsg = '';
    var flag = true;
    if (error.length > 0) {
        showErrors(error);
        flag = false;
    }
    if (flag) {
        e.disabled = true;
        const buttonHtml = $(`#${validationSpanId}`).html();

        $.ajax({
            type: "POST",
            url: formURL,
            data: formData,
            dataType: "json",
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('.loader').css('visibility', 'visible');
            },
            success: function(data) {
                if (data.status == 'success') {
                    console.log('jdasksadhjk');
                    e.disabled=false;
                    console.log(data.redirect_to)
                    notificationAlert('success', data.message, 'Success!');
                    //  bsAlert(data.message, 'alert-success', 'alert_placeholder');
                    if (data.redirect_to != '' && typeof(data.redirect_to) != "undefined") {
                         console.log('redirecting',data);
                        setTimeout(function() {
                            reload_page(data.redirect_to)
                        }, 2000);
                        // setTimeout(, 12000);
                    } else {
                        console.log('not redirecting');
                    }
                } else {


                    console.log(data.message,'error message');
                    e.disabled=false;
                    var errors = data.errors;
                    console.log(errors,'error message 2');
                    $.each(errors, function(i, val) {
                        console.log(i);
                        if (errors[i] != 'undefined' && errors[i] != null) {
                            let nowErrorMessage = errors[i];
                            if (i == 'errors') {
                                console.log(errors[i]);
                                let newErrors = errors[i];
                                $.each(newErrors, function(index, value) {
                                    nowErrorMessage = newErrors[index][0] ? newErrors[index][0] : '';
                                });
                            }
                            errorMsg += nowErrorMessage + '<br>';
                        }
                    });

                    notificationAlert('error', errorMsg, 'Inconceivable!');

                    $(`#${validationSpanId}`).html(buttonHtml);
                }
            },
            error: function(data) {
                // Error...
                console.log(data)
                e.disabled=false;
                var errors = $.parseJSON(data.responseText);
                console.log(errors);
                $.each(errors, function(i, val) {
                    e.disabled = false;
                    if (errors[i] != 'undefined' && errors[i] != null) {
                        let nowErrorMessage = errors[i];
                        if (i == 'errors') {
                            console.log(errors[i]);
                            let newErrors = errors[i];
                            $.each(newErrors, function(index, value) {
                                nowErrorMessage = newErrors[index][0] ? newErrors[index][0] : '';
                            });
                        }
                        errorMsg += nowErrorMessage + '<br>';
                    }
                });



                notificationAlert('error', errorMsg, 'Inconceivable!');

                //  bsAlert(errorMsg, 'alert-danger', 'alert_placeholder');
                $(`#${validationSpanId}`).html(buttonHtml);
            },
            complete: function () {
                setTimeout(function() {
                    $('.loader').css('visibility', 'hidden');
                }, 2000);
            }
        });
    }
}

function validateFields(formId) {

    var fields = $("#" + formId + " :input").serializeArray();
    var error = [];
    var skipArray = ['action'];
    var emailArray = ['email', 'notification_email', 'secondary_notification_email'];
    var skipforEmpty = ['discount'];
    var fname = 'no_name';
    /*var regexy = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;*/
    /*## Ticket #1701*/
    var regexy = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    $.each(fields, function(i, field) {
        fname = field.name;
        if ($.inArray(fname, skipArray) == -1) {
            // console.log(fname);
            // console.log($.inArray(fname, emailArray));
            if ($.trim(field.value) == '') {
                // console.log($.inArray(fname, skipforEmpty),fname,skipforEmpty)
                //error.push( { fname :  'Please enter '+fname} );
                if ($.inArray(fname, skipforEmpty) == -1) {
                    var myregexp = /\[(.*?)\]/;
                    var match = myregexp.exec(fname);
                    if (match != null) {
                        fname = match[1];
                    }
                    error[i] = 'Please enter ' + fname;
                }
            } else if ($.inArray(fname, emailArray) > -1) {
                if (!regexy.test(field.value)) {
                    error[i] = 'Please enter correct format of email (example@example.com)';
                }
            }
        }
    });
    return error;
}

function showErrors(errors) {
    var msg = '';
    var error = '';
    $.each(errors, function(i, val) {
        if (errors[i] != '' && typeof(errors[i]) != "undefined") {
            error = errors[i] + '<br>';
            msg += error.replace(/_/g, ' ').toLowerCase().replace(/\b[a-z]/g, function(letter) {
                return letter.toUpperCase();
            });
        }
    });
    if (msg != '') {
        notificationAlert('error', msg);
        //bsAlert(msg, 'alert-danger', 'alert_placeholder');
    }
}

/*
 * ## Type can be either error, success, warning Or info
 * ## Content will show the Message to display
 * ## Title is the heading of Message if any
 * ## TimeOut in seconds
 * */
function notificationAlert(type, content, title, timeOut) {
    if (type == '' || typeof(type) == "undefined") {
        type = 'error';
    }
    if (content == '' || typeof(content) == "undefined") {
        content = 'You Got Error';
    }
    if (title == '' || typeof(title) == "undefined") {
        title = '';
    }
    if (timeOut == '' || typeof(timeOut) == "undefined") {
        timeOut = 5; // in seconds
    }
    timeOut = timeOut * 1000;
    /*// by Default Toastr accept time in Micro Seconds so multiplying by 1000*/

    content = content.replace(/_/g, ' ');

    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": timeOut,
        "extendedTimeOut": timeOut,
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    switch (type) {
        case 'success':
            toastr.success(content, title, { timeOut: timeOut });
            break;
        case 'error':
            toastr.error(content, title, { timeOut: timeOut });
            break;
        case 'info':
            toastr.info(content, title, { timeOut: timeOut });
            break;
        case 'warning':
            toastr.warning(content, title, { timeOut: timeOut });
            break;
    }

}

/*
 * message: text which show as error, warning or success messages.
 * cls: style class of alert as [alert-danger, alert-warning, alert-success, alert-info]
 */
function bsAlert(message, cls, id) {
    if (id == '') {
        id = 'alert_placeholder';
    }
    var html = '';
    var ms = 5000;
    if (cls == 'alert-warning') ms = 7000;
    html = '<div class="alert ' + cls + ' alert-dismissible fade in" role="alert">';
    html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
    html += '<span aria-hidden="true">&times;</span>';
    html += '</button>';
    html += '<strong>' + message + '</strong>';
    html += '</div>';

    if ($('#' + id).length > 0)
        $('#' + id).html(html);
    else
        alert('Alert placeholder not found on this page.');

    setTimeout(function() {
        // this will automatically clean alert placeholder in 5 secs
        $("#" + id).html('');

    }, ms);
}

function reload_page(url) {
    location.href = baseURL + url;
    // location.href = 'http://127.0.0.1:8000' + url;
}

function loadingImage() {
    var html = '<img src="' + baseURL + '/assets/images/loading.gif" >';
    return html;
}

function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function defaultTable() {
    var table = document.getElementById("exhibitionns");
    for (var i = 0, row; row = table.rows[i]; i++) {
        console.log($(row).closest("tr").find('.btn-success'));
        console.log($(row).closest("tr").find('.btn-success').text('Select'));
        console.log($(row).closest("tr").find('.btn-success').removeClass('btn-success'));

    }
}


function commonAjaxDeleteModel(route, id, containerId) {
    var url = '';
    var dataToPost = '';
    var modelhtml = '';
    modelhtml = '<div class="modal fade"  id="deleteModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">' +
        '<div class="modal-dialog" role="document">' +
        '<div class="modal-content" style="width: 600px">' +
        '<div class="modal-header">' +
        '<h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>' +
        '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
        '<span aria-hidden="true">&times;</span>' +
        '</button>' +
        '</div>' +
        '<div class="modal-body">' +
        '<h5 class="modal-title mt-1 mb-3" id="exampleModalLabel">Are You Sure to Delete This?</h5>' +
        '<div class="d-flex justify-content-end m-3">' +
        '<button type="button" class="is-allow-delete btn-sm btn-primary mr-3 btn-lg rounded-0" value="false" data-dismiss="modal">Close</button>' +
        '<button type="button" class="is-allow-delete btn-sm btn-danger btn-lg rounded-0" value="true" data-dismiss="modal">Delete</button>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>';
    $("body").append('<div id="deleteModalDiv"></div>');
    $('#deleteModalDiv').html(modelhtml);
    $('#deleteModel').modal('show');

    if (typeof(containerId) == "undefined" || containerId === '') {
        containerId = 'common_delete_popup_modal';
    }
    if (typeof(id) == "undefined" || id === '') {
        id = 0;
    }
    if (typeof(route) == "undefined" || route === '') {
        route = '';
    }
    if (route !== '') {
        $('.is-allow-delete').on('click', function() {
            if (this.value === 'true') {
                url = baseURL + '/' + route;
                dataToPost = {
                    "containerId": containerId,
                    "record_id": id,
                };
                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: url,
                    data: dataToPost,
                    dataType: "JSON",
                    beforeSend: function beforeSend() {
                        $('.loader').css('visibility', 'visible');
                    },
                    success: function (data) {
                        if (data.status === 'success') {
                            notificationAlert('success', data.message, 'Success!');
                            if (data.redirect_to !== '' && typeof (data.redirect_to) != "undefined") {
                                setTimeout(function () {
                                    reload_page(data.redirect_to)
                                }, 2000);
                            }
                        } else if (data.status === 'error') {
                            if(data.message instanceof Array) {
                                $.each(data.message, function(index, value) {
                                    notificationAlert('error', value, 'Error!')
                                    notificationAlert('error', data.errors[index], 'Error!')
                                });
                            } else {
                                notificationAlert('error', data.message, 'Error!');
                            }
                            if (data.redirect_to !== '' && typeof (data.redirect_to) !== "undefined") {
                                setTimeout(function () {
                                    reload_page(data.redirect_to)
                                }, 2000);
                            }
                        } else {
                            $.each(data, (index, item) => notificationAlert('error', item, 'Error!'));
                        }
                    },
                    error: function (data) {
                        notificationAlert('error', 'Something went wrong. Please try again later', 'error');
                    },
                    complete: function complete() {
                        $('.loader').css('visibility', 'hidden');
                    }
                });
            }
        });
    }
}

/*
 * ## Route and id are params for this function.
 * ## Function deletes record from db using id.
 * ## Sweetalert is used for confirmation.
* */
function deleteRecord(route, id, text) {
    if (typeof (route) == "undefined" || route === '') {
        route = '';
    }
    if (typeof (id) == "undefined" || id === '') {
        id = 0;
    }
    if (typeof (text) == "undefined" || text === '') {
        text = '';
    }
    if (route !== '') {
        Swal.fire({
            title: 'Are you sure?',
            text: text ? text + "You won't be able to revert this!" : "You won't be able to revert this!",
            type: "warning",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                var url = baseURL + '/' + route + "/" + id;
                $.ajax({
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "Delete",

                    beforeSend: function () {
                        $('.loader').css('visibility', 'visible');
                    },
                    success: function (data) {
                        if (data.status === 'success') {
                            // $('.loader').css('visibility', 'hidden');
                            swal.fire({
                                title: "Success!",
                                type: "success",
                                // text: "Corporate Deleted Successfully",
                                text: data.message ?? "",
                                icon: "success",
                                confirmButtonClass: "btn btn-outline-info",
                            });
                            if (data.redirect_to !== '' && typeof(data.redirect_to) != "undefined") {
                                setTimeout(function() {
                                    reload_page(data.redirect_to)
                                }, 2000);
                            }
                        }else if (data.status === 'error'){
                            swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Something went wrong!',
                            })
                        }

                    },
                    error: function (data) {
                        swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong!',
                        })
                    },
                    complete: function () {
                        $('.loader').css('visibility', 'hidden');
                    },
                })
            }
        })
    }else {
        notificationAlert('error', 'Route is not defined', 'Inconceivable!');
    }
}
function ledgerPayment(route,accountId,shopId){
    if (typeof (route) == "undefined" || route === '') {
        route = '';
    }
    if (typeof (accountId) == "undefined" || accountId === '') {
        accountId = 0;
    }
    if (typeof (shopId) == "undefined" || shopId === '') {
        shopId = 0;
    }
    if (route !== '') {
        Swal.fire({
            title: 'Please Add Amount for payment!',
            input: 'text',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Add Payment!'
        }).then((result) => {
            if (result.value) {
                var dataToPost = {
                  'shop_id': shopId,
                  'account_id': accountId,
                  'amount': result.value,
                };
                console.log(dataToPost)
                var url = baseURL + route ;
                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: url,
                    data: dataToPost,
                    dataType: "json",
                    beforeSend: function () {
                        $('.loader').css('visibility', 'visible');
                    },
                    success: function (data) {
                        if (data.status === 'success') {
                            // $('.loader').css('visibility', 'hidden');
                            swal.fire({
                                title: "Success!",
                                type: "success",
                                // text: "Corporate Deleted Successfully",
                                text: data.message ?? "",
                                icon: "success",
                                confirmButtonClass: "btn btn-outline-info",
                            });
                            if (data.redirect_to !== '' && typeof(data.redirect_to) != "undefined") {
                                setTimeout(function() {
                                    reload_page(data.redirect_to)
                                }, 2000);
                            }
                        }else if (data.status === 'error'){
                            errorMsg ='';
                            if(data.errors){
                                var errors = data.errors;
                                $.each(errors, function(i, val) {
                                    if (errors[i] != 'undefined' && errors[i] != null) {
                                        let nowErrorMessage = errors[i];
                                        if (i == 'errors') {
                                            console.log(errors[i]);
                                            let newErrors = errors[i];
                                            $.each(newErrors, function(index, value) {
                                                nowErrorMessage = newErrors[index][0] ? newErrors[index][0] : '';
                                            });
                                        }
                                        errorMsg += nowErrorMessage;
                                    }
                                });
                            }
                            console.log(data)
                            swal.fire({
                                icon: 'error',
                                title: data.message ?? 'Error',
                                text: (errorMsg != '') ? errorMsg : 'Something went wrong!',
                            })
                        }

                    },
                    error: function (data) {
                        errorMsg ='';
                        if(data.errors){
                            var errors = data.errors;
                            $.each(errors, function(i, val) {
                                if (errors[i] != 'undefined' && errors[i] != null) {
                                    let nowErrorMessage = errors[i];
                                    if (i == 'errors') {
                                        console.log(errors[i]);
                                        let newErrors = errors[i];
                                        $.each(newErrors, function(index, value) {
                                            nowErrorMessage = newErrors[index][0] ? newErrors[index][0] : '';
                                        });
                                    }
                                    errorMsg += nowErrorMessage;
                                }
                            });
                        }
                        swal.fire({
                            icon: 'error',
                            title: data.message ?? 'Error',
                            text: (errorMsg != '') ? errorMsg : 'Something went wrong!',
                        })
                    },
                    complete: function () {
                        $('.loader').css('visibility', 'hidden');
                    },
                })
            }
            else{
                swal.fire({
                    icon: 'error',
                    title: 'Please Enter Amount Field',
                })
            }
        })
    }else {
        notificationAlert('error', 'Route is not defined', 'Inconceivable!');
    }
}
function productPayemnt(route,productId)
{
    if (typeof (route) == "undefined" || route === '') {
        route = '';
    }
    if (typeof (productId) == "undefined" || productId === '') {
        productId = 0;
    }
    if (route !== '') {
        Swal.fire({
            title: 'Please Add Amount for payment!',
            input: 'text',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Add Payment!'
        }).then((result) => {
            if (result.value) {
                var dataToPost = {
                    'product_id': productId,
                    'amount': result.value,
                };
                console.log(dataToPost)
                var url = baseURL + route ;
                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: url,
                    data: dataToPost,
                    dataType: "json",
                    beforeSend: function () {
                        $('.loader').css('visibility', 'visible');
                    },
                    success: function (data) {
                        if (data.status === 'success') {
                            // $('.loader').css('visibility', 'hidden');
                            swal.fire({
                                title: "Success!",
                                type: "success",
                                // text: "Corporate Deleted Successfully",
                                text: data.message ?? "",
                                icon: "success",
                                confirmButtonClass: "btn btn-outline-info",
                            });
                            if (data.redirect_to !== '' && typeof(data.redirect_to) != "undefined") {
                                setTimeout(function() {
                                    reload_page(data.redirect_to)
                                }, 2000);
                            }
                        }else if (data.status === 'error'){
                            errorMsg ='';
                            if(data.errors){
                                var errors = data.errors;
                                $.each(errors, function(i, val) {
                                    if (errors[i] != 'undefined' && errors[i] != null) {
                                        let nowErrorMessage = errors[i];
                                        if (i == 'errors') {
                                            console.log(errors[i]);
                                            let newErrors = errors[i];
                                            $.each(newErrors, function(index, value) {
                                                nowErrorMessage = newErrors[index][0] ? newErrors[index][0] : '';
                                            });
                                        }
                                        errorMsg += nowErrorMessage;
                                    }
                                });
                            }
                            console.log(data)
                            swal.fire({
                                icon: 'error',
                                title: data.message ?? 'Error',
                                text: (errorMsg != '') ? errorMsg : 'Something went wrong!',
                            })
                        }

                    },
                    error: function (data) {
                        errorMsg ='';
                        if(data.errors){
                            var errors = data.errors;
                            $.each(errors, function(i, val) {
                                if (errors[i] != 'undefined' && errors[i] != null) {
                                    let nowErrorMessage = errors[i];
                                    if (i == 'errors') {
                                        console.log(errors[i]);
                                        let newErrors = errors[i];
                                        $.each(newErrors, function(index, value) {
                                            nowErrorMessage = newErrors[index][0] ? newErrors[index][0] : '';
                                        });
                                    }
                                    errorMsg += nowErrorMessage;
                                }
                            });
                        }
                        swal.fire({
                            icon: 'error',
                            title: data.message ?? 'Error',
                            text: (errorMsg != '') ? errorMsg : 'Something went wrong!',
                        })
                    },
                    complete: function () {
                        $('.loader').css('visibility', 'hidden');
                    },
                })
            }
            else{
                swal.fire({
                    icon: 'error',
                    title: 'Please Enter Amount Field',
                })
            }
        })
    }else {
        notificationAlert('error', 'Route is not defined', 'Inconceivable!');
    }
}


