/**
 * Created by Павел on 03.08.2017.
 */
function tickets() {
    $('form[name="ticket"]').on('beforeSubmit', function(e) {
        // Before action
    }).on('submit', function(e){
        e.preventDefault();
        var form = $(e.target);
        var formData = form.serialize();

        function errorsHandler(response) {
            var response = response;

            this.setError = function(field, text) {
                form.find('input[name="Tickets[' + field + ']"]').addClass('errors').next().text(response.errors[field].toString());
            };

            this.removeError = function(field) {
                form.find('input[name="Tickets[' + field + ']"]').removeClass('errors').next().text('');
            };

            this.removeErrors = function() {
                form.find('.help-block-error').text('');
                form.find('input').removeClass('errors');
            };
        }

        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: formData,
            dataType: 'json',
            success: function (response) {
                if (response.status && response.status == 401) {
                    var errorNames = Object.keys(response.errors);
                    var errorHandler = new errorsHandler(response);

                    // clear form errors
                    errorHandler.removeErrors();

                    errorNames.forEach(function(field, i) {
                        errorHandler.setError(field);
                    });
                } else {
                    pnwidget.init({
                        containerId: "ponominalu_widget",
                        orderData: {
                            email: form.find('#tickets-email').val(),
                            name:  form.find('#tickets-name').val(),
                            phone: form.find('#tickets-phone').val()
                        }
                    });

                    popupCloseAll();
                    popup('ponominalu-list');

                    pnwidget.show({
                        closeButton: false,
                        hideHeader: true,
                        event: {
                            alias: response.p_alias,
                            date: response.p_dt,
                            time: response.p_time
                        },
                        referral_auth: response.p_auth
                    });
                }
            }
        });
    });
}