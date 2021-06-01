paypal.Buttons({

createOrder: function() {

    return fetch(magicFormSettings.ajaxUrl, {
        method: 'post',
        headers:  {"Content-Type": "application/x-www-form-urlencoded"},
        body: "action=magicform_paypal_payment&submission_id="+submissionId
    }).then(function(res) {
        return res.json();
    }).then(function(data) {
        var result = JSON.parse(data.data)
        accessToken = result.access_token;
        return result.id; // Use the same key name for order ID on the client and server
    });

},

onApprove: function(data, actions) {
                return fetch(magicFormSettings.ajaxUrl, {
                    method: 'post',
                    headers:  {"Content-Type": "application/x-www-form-urlencoded"},
                    body: "action=magicform_paypal_order_capture&order_id="+data.orderID+"&submission_id="+submissionId
                }).then(function(res) {
                    return res.json();
                }).then(function(orderData) {
                    var result = JSON.parse(orderData.data)
                   

                    if (errorDetail && errorDetail.issue === 'INSTRUMENT_DECLINED') {
                        // Recoverable state, see: "Handle Funding Failures"
                        // https://developer.paypal.com/docs/checkout/integration-features/funding-failure/
                        return actions.restart();
                    }

                    if (errorDetail) {
                        var msg = 'Sorry, your transaction could not be processed.';
                        if (errorDetail.description) msg += '\n\n' + errorDetail.description;
                        if (orderData.debug_id) msg += ' (' + orderData.debug_id + ')';
                        
                        return alert(msg);
                    }

                    
                    alert('Transaction completed by ' + result.payer.name.given_name);
                });
            }
}).render('#paypal-button-container');