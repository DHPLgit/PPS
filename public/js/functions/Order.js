import { errorDisplay } from './Common.js';







// sign up




function orderUpdate(form) {
    console.log("submit2");
    // $('#loader').show();
    console.log("entry");
    //var form = $(this);
    console.log(form.attr("id"));
    console.log(form.serialize());
    $.ajax({
        url: form.attr("action"),
        type: 'post',
        dataType: 'json',
        data: form.serialize(),
        success: function (response) {
            console.log(response);
            if (response.success) {
                console.log("successentry");

                window.location.href = response.url;
            } else {
                // $('#loader').hide();
                console.log(response.error);
                console.log("failure");
                const idArray = ['Order_unique_id', 'Customer_id', 'Order_date', 'Type', 'Colour', 'Length', 'Texture', 'Ext_size', 'Quantity', 'Due_date'];
                const errorArray = ["Order_unique_id_error", "Customer_id_error", "Order_date_error", "Type_error", "Colour_error", "Length_error", "Texture_error", "Ext_size_error", "Quantity_error", "Due_date_error"];

                errorDisplay(errorArray, idArray, response.error);

            }
        },
        error: function (response) {
            console.log(response);
        }

    });

}

function orderDelete(url, orderId) {
    console.log("submit2");
    // $('#loader').show();
    console.log("entry");
    //var form = $(this);

    $.ajax({
        url: url,
        type: 'post',
        dataType: 'json',
        data: { orderId: orderId },
        success: function (response) {
            console.log(response);
            if (response.success) {
                console.log("successentry");

                window.location.href = response.url;
            } else {
                // $('#loader').hide();
                console.log(response.error);
                console.log("failure");

            }
        },
        error: function (response) {
            console.log(response);
        }

    });

}
function mapEditOrderData(order, url) {

    var editOrderData = order;
    if (editOrderData && editOrderData.Length > 0) {
        console.log("editOrderData", editOrderData);
        var form = document.getElementById("orderForm");

        form.action = url + editOrderData['Order_id'];
        var inputs = form.querySelectorAll("input");
        console.log("inputs", inputs);
        inputs.forEach(input => {
            console.log("input", input);
            input.value = editOrderData[input.id];
        })
        var drpdwns = form.querySelectorAll("select");
        console.log("drpdwns", drpdwns);
        drpdwns.forEach(drpdwn => {
            console.log("drpdwn", drpdwn);
            console.log("drpdwnoption", drpdwn.options);
            var options = drpdwn.options;

            for (let index = 0; index < options.length; index++) {
                const option = options[index];
                if (option.value == editOrderData[drpdwn.id]) {
                    option.selected = true;
                };

            }

        })
        var flag = document.createElement("input");
        flag.setAttribute("type", "hidden");
        flag.setAttribute("value", "true");
        flag.setAttribute("name", "isEdit");
        form.insertBefore(flag, form.firstChild);
    }
}