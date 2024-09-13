

function login() {

    $("#log_in").submit(function (event) {
      $("#flashData").hide();
        event.preventDefault();
        $('#loader').show();
        var form = $(this);
        console.log(form.attr("id"));

        $.ajax({
            url: form.attr("action"),
            type: 'post',
            dataType: 'json',
            data: form.serialize(),
            success: function (response) {
                console.log(response);
                console.log("successentry");
                if (response.success) {
                    $('#loader').hide();
                    console.log("success");
                    $("#user_id").val(response.user_id);
                    $("#login-form").hide();
                    $("#otp-check-form").show();
                } else {
                    $('#loader').hide();
                    console.log(response.error);
                    const idArray = ["mail_id", "password"];
                    const errorArray = ["mail_id_error", "password_error"];

                    errorDisplay(errorArray, idArray, response.error);

                }
            },
            error: function (response) {
                console.log(response);
            }

        });

    })
}


// sign up

function signup() {
    console.log("hello");
    $("#signup").submit(function (event) {
        event.preventDefault();
        $('#loader').show();
        console.log("entry");
        var form = $(this);
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

                    $('#loader').hide();
                    window.location.href = response.url;
                } else {
                    $('#loader').hide();
                    console.log(response.error);
                    const idArray = ["first_name", "last_name", "mail_id", "password", "confirm_password"];
                    const errorArray = ["first_name_error", "last_name_error", "mail_id_error", "password_error", "confirm_password_error"];

                    errorDisplay(errorArray, idArray, response.error);

                }
            },
            error: function (response) {
                console.log(response);
            }

        });

    })
}

function otpCheck(form) {

    //removeError()
    $('#loader').show();
    console.log("entry");
    $.ajax({
        url: form.attr("action"),
        type: 'post',
        dataType: 'json',
        data: form.serialize(),
        success: function (response) {
            console.log(response);
            console.log("successentry");
            if (response.success) {
                $('#loader').hide();
                console.log("success");

                window.location.href = response.url;
            } else {
                $('#loader').hide();

                $("#otp_error").text(response.msg);

            }
        },
        error: function (response) {
            console.log(response);
        }

    });
}
function forgetPassword(form) {
    removeError()
    $('#loader').show();
    $("#alert-msg").hide();
    console.log("entry");
    console.log(form.attr("action"));
    $.ajax({
        url: form.attr("action"),
        type: 'post',
        dataType: 'json',
        data: form.serialize(),
        success: function (response) {
            console.log(response);
            console.log("successentry");
            if (response.success) {
                $('#loader').hide();
                console.log("success");
                $("#alert-msg").text(response.msg);
                $("#alert-msg").show();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            } else {
                $('#loader').hide();
                console.log(response.error);
                const idArray = ["fp_mail_id"];
                const errorArray = ["fp_mail_id_error"];

                errorDisplay(errorArray, idArray, response.error);

            }
        },
        error: function (response) {
            console.log(response);
        }

    });

}

function removeError() {
    var errorElements = document.getElementsByClassName("error");
    for (let j = 0; j < errorElements.length; j++) {
        errorElements[j].style.display = "none";
    }
}
function errorDisplay(errorArray, idArray, messageArray) {
    for (let i = 0; i < idArray.length; i++) {
        // console.log(idArray[i]);
        var element = document.getElementById(errorArray[i])
        //console.log(element,"element");
        if (element) {
            if (idArray[i] in messageArray) {
                //       console.log(errorArray[i]);
                element.style.display = "block";
                element.innerText = messageArray[idArray[i]];
            } else {
                element.style.display = "none";
            }
        }
    }
}

function orderUpdate(form) {
    console.log("submit2");
    $('#loader').show();
    console.log("entry");
    console.log(form.attr("id"));
    console.log(form.serialize());
    //document.getElementById('submitBtn').disabled = true
    $("#submitBtn").prop("disabled", true);

    $.ajax({
        url: form.attr("action"),
        type: 'post',
        dataType: 'json',
        data: form.serialize(),
        success: function (response) {
            console.log(response);
            $('#loader').hide();

            if (response.success) {
                console.log("successentry");

                window.location.href = response.url;
            } else {
                $("#submitBtn").prop("disabled", false);
                console.log(response.error);
                console.log("failure");
                const idArray = ['order_id', 'reference_id', 'customer_id', 'order_date', 'type', 'colour', 'length', 'texture', 'ext_size', 'unit', 'bundle_count', 'quantity', 'due_date'];
                const errorArray = ["order_id_error", "reference_id_error", "customer_id_error", "order_date_error", "type_error", "colour_error", "length_error", "texture_error", "ext_size_error", "unit_error", "bundle_count_error", "quantity_error", "due_date_error"];

                errorDisplay(errorArray, idArray, response.error);

            }
        },
        error: function (response) {
            console.log(response);
        }

    });

}

function orderDelete(url, orderListId) {
    console.log("submit2");
    $('#loader').show();
    console.log("entry");
    //var form = $(this);

    $.ajax({
        url: url,
        type: 'post',
        dataType: 'json',
        data: { orderListId: orderListId },
        success: function (response) {
            console.log(response);
            $('#loader').hide();

            if (response.success) {
                console.log("successentry");


                window.location.href = response.url;
            } else {
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

    console.log("map")
    var editOrderData = order;
    console.log(typeof (editOrderData))
    if (editOrderData && Object.keys(editOrderData).length > 0) {
        $("#Action-type").text("Edit Order");
        $("#order_id").prop("readonly", true);

        console.log("editOrderData", editOrderData);
        var form = document.getElementById("orderForm");

        form.action = url + editOrderData['order_list_id'];
        var inputs = form.querySelectorAll("input");
        console.log(inputs)
        console.log("inputs", inputs);
        inputs.forEach(input => {
            console.log("input", input);
            input.value = editOrderData[input.id];
        })
        $("#is_bundle").val("1")
        $("#is_bundle1").val("0")
        if (editOrderData['bundle_count'] > 0) {
            $("#is_bundle").prop('checked', true);

            $("#bundle-count-div").show();
            $("#quantity-div").show();
        }
        else {
            $("#quantity-div").show();
            $("#is_bundle1").prop('checked', true);

        }
        console.log(inputs)
        var drpdwns = form.querySelectorAll("select");
        console.log("drpdwns", drpdwns);
        drpdwns.forEach(drpdwn => {
            console.log("drpdwn", drpdwn);
            console.log("drpdwnoption", drpdwn.options);
            var options = drpdwn.options;
            var matched = false

            for (let index = 0; index < options.length; index++) {
                const option = options[index];
                if (option.value == editOrderData[drpdwn.id]) {
                    option.selected = true;
                    matched = true;

                };


            }
            if (!matched && drpdwn.id == "colour") {
                drpdwn.value = "Others";
                $("#other_colour").show();
                $("#other_colour").val(editOrderData["colour"])

            }

        })
        var flag = document.createElement("input");
        flag.setAttribute("type", "hidden");
        flag.setAttribute("value", "true");
        flag.setAttribute("name", "isEdit");
        flag.setAttribute("id", "isEdit");
        form.insertBefore(flag, form.firstChild);
        $("#ord-item-button").hide();
        //  $("#item-button").hide();
        $("#item-form").show();
    }
}

function checkAndGenerateId(url, order_id) {

    if (order_id.length > 0) {
        $("#order_id_error").text("");
        $.ajax({
            url: url,
            type: 'get',
            data: { isAddItem: 0, order_id: order_id },
            dataType: 'json',
            success: function (response) {
                console.log(response);
                if (response.success) {
                    console.log("successentry");

                    console.log("response.output", response.output);
                    // var inputs = form.querySelectorAll("input");
                    //window.location.href = response.url;
                    $("#order_id").prop("readonly", true);
                    $("#item-form").show();
                    $("#item_id").val(response.output.item_id);

                    $("#ord-item-button").hide();
                    //  $("#item-button").hide();
                } else {
                    // $('#loader').hide();
                    $("#order_id_error").text(response.error['order_id'])
                    console.log(response.error);
                    console.log("failure");

                }
            },
            error: function (response) {
                console.log(response);
            }

        });
    }
    else {
        $("#order_id_error").text("Please enter order id.")
    }
}


function mapItemId(item_ord_Id) {
    console.log("entry");
    if (item_ord_Id && Object.keys(item_ord_Id).length > 0) {
        console.log("map");
        // if(item_ord_Id['order_id']==0){
        //     item_ord_Id['order_id']="";
        // }
        console.log("order_date", item_ord_Id["order_date"])
        $("#order_id").prop("readonly", true);
        $("#order_id").val(item_ord_Id['order_id']);
        $("#item_id").val(item_ord_Id['item_id']);
        $("#order_date").val(item_ord_Id["order_date"])
        $("#ord-item-button").hide();
        $("#item-form").show();
    }
}

function InsertTaskDetail(form) {

    $('#loader').show();
    // var form = $(this);
    console.log(form.attr("id"));

    $.ajax({
        url: form.attr("action"),
        type: 'post',
        dataType: 'json',
        data: form.serialize(),
        success: function (response) {
            console.log(response);
            console.log("successentry");
            $('#loader').hide();

            if (response.success) {
                console.log("success");
                window.location.href = response.url;

            } else {
                console.log(response.error);
                const idArray = ["task_name", "hours_taken", "supervisor", "is_qa", "quality_analyst"];
                const errorArray = ["task_name_error", "hours_taken_error", "supervisor_error", "is_qa_error", "quality_analyst_error",];

                errorDisplay(errorArray, idArray, response.error);

            }
        },
        error: function (response) {
            console.log(response);
        }

    });
}

function viewTaskDetail(url, taskDetId) {

    $('#loader').show();

    $.ajax({
        url: url,
        type: 'post',
        dataType: 'json',
        data: { task_detail_id: taskDetId },
        success: function (response) {
            console.log(response);
            console.log("successentry");

            $('#loader').hide();
            console.log("success");
            console.log("response", response.output)

            var taskDetContainer = document.getElementById('view-task-detail-modal');

            $.each(response.output, function (index, value) {
                console.log(typeof (index))
                console.log(typeof (value))
                if (typeof (value) == "object") {
                    var container = document.getElementById("qc");
                    console.log("here", response.output.qc);
                    response.output.qc.forEach(element => {
                        $.each(element, function (index1, value1) {
                            console.log("1", value1);
                            var data = document.createElement("p")
                            data.innerText = value1;
                            container.append(data);
                        })

                    })

                }
                else {

                    var element = document.getElementById(index);
                    element.innerText = value;
                }



            });


            $("#view-task-detail-modal").modal("show");


        },
        error: function (response) {
            console.log(response);
        }

    });


}
function taskDetailDelete(url, taskDetailId) {
    console.log("submit2");
    $('#loader').show();
    console.log("entry");

    $.ajax({
        url: url,
        type: 'post',
        dataType: 'json',
        data: { task_detail_id: taskDetailId },
        success: function (response) {
            console.log(response);
            $('#loader').hide();

            if (response.success) {
                console.log("successentry");


                window.location.href = response.url;
            } else {
                console.log(response.error);
                console.log("failure");

            }
        },
        error: function (response) {
            console.log(response);
        }

    });

}
function parentTaskUpd(url, parent_task_id, task_detail_id) {
    $('#loader').show();
    $.ajax({
        url: url,
        type: 'post',
        dataType: 'json',
        data: { task_detail_id: task_detail_id, parent_task_id: parent_task_id },
        success: function (response) {
            console.log(response);
            $('#loader').hide();

            if (response.success) {
                console.log("successentry");


                window.location.href = response.url;
            } else {
                console.log(response.error);
                console.log("failure");

            }
        },
        error: function (response) {
            console.log(response);
        }
    })
}
function searchStocks(form) {
    console.log(form.attr("id"));
    $.ajax({
        url: form.attr("action"),
        method: 'post',
        dataType: 'json',
        data: form.serialize(),
        success: function (response) {
            if (response.success) {
                $("#AddStock-div").removeClass("d-none");
                $("#AddStock-div-error").addClass("d-none");
                let stocks = response.output;
                //console.log(stocks);
                $('#dropdown-stock').html('');
                var selectedList = GetSelectedList();

                $.each(stocks, function (index, value) {
                    //console.log(value);
                    var stock = value.colour + " - " + value.length + " - " + value.texture + " - " + value.quantity;

                    disabled = "";

                    var flag = selectedList.includes(value.stock_list_id)
                    if (flag) {
                        disabled = "disabled";
                    }
                    $("#dropdown-stock").append("<option value='" + value.stock_list_id + "' " + disabled + " >" + stock + "</option>");

                });

            } else {
                $("#AddStock-div").addClass("d-none");
                $("#AddStock-div-error").removeClass("d-none");
                $("#AddStock-div-error").text("No matching stock found, Please try different filters.");

            }
        }
    });

    //  });
}

function searchOrder(url, query) {
    console.log(query)
    var ul = document.getElementById("autocompleteList");
    if (query != '') {
        $.ajax({
            url: url,
            method: 'post',
            dataType: 'json',
            data: {
                like: query
            },
            success: function (response) {
                if (response.success) {
                    let orders = response.output;
                    console.log(orders);
                    $('#autocompleteList').html('');
                    var input = document.getElementById("orderidsearch");

                    ul.classList.remove("d-none");
                    console.log(ul);
                    var clear = document.getElementById("clear");
                    $.each(orders, function (index, value) {
                        //var name = value.name;
                        console.log(value);
                        var li = document.createElement("li");
                        var ord_item_id = value.order_id + "-" + value.item_id;
                        li.textContent = ord_item_id
                        li.addEventListener("click", function () {
                            $("#orderListId").val(value.order_list_id);
                            $("#ordItemId").val(ord_item_id);

                            input.value = ord_item_id;
                            input.readOnly = true; // Set input field to read-only
                            ul.classList.add("d-none");
                            clear.classList.remove("d-none");
                            var outputDiv = document.getElementById("output-data");

                            var inputs = outputDiv.querySelectorAll("input");
                            console.log("inputs", inputs);
                            inputs.forEach(input => {
                                console.log("input", input);
                                var fieldName = input.id;
                                input.value = value[fieldName];
                            })
                        });
                        ul.appendChild(li);

                    });

                } else {
                    $('#autocompleteList').html('');
                }
            }
        });
    } else {
        ul.classList.add("d-none");
    }
    //  });
}

function clearInput() {
    var input = document.getElementById("orderidsearch");
    var ul = document.getElementById("autocompleteList");
    var clear = document.getElementById("clear");
    $("#orderListId").val("");
    $("#ordItemId").val("");
    ul.innerHTML = "";
    if (input.value.length > 0) {
        input.value = "";
        if (input.value == "") {
            clear.classList.add("d-none");
            input.readOnly = false;
        }
    }
    var outputDiv = document.getElementById("output-data");

    var inputs = outputDiv.querySelectorAll("input");
    console.log(inputs)
    console.log("inputs", inputs);
    inputs.forEach(input => {
        var fieldName = input.id;
        input.value = "";
    })
}

//shows preview of the selected stock
const inputArr = new Object()
function saveStockInput(form) {
    $("#stock_id_error").text("")
    $("#quantity_error").text("")
    //if (inputArr) {
    var qty = form.find('input[name="quantity"]').val();
    var stockId = form.find('select[name="stock_id"]').val();
    console.log("qty", qty);
    console.log(stockId);
    var qtyElement = document.getElementById("quantity");
    var selectElement = document.getElementById("dropdown-stock");
    var selectedOption = selectElement.options[selectElement.selectedIndex];
    console.log("selectedOption", selectedOption);

    console.log(inputArr)

    var selectedOptionText = selectedOption.innerText;
    var stockArr = selectedOptionText.split(" - ");
    console.log("qty", stockArr[3]);
    console.log(selectedOptionText);


    //Adding the preview of a selected stock if condition satisfies.
    if (parseFloat(qty) > 0 && parseFloat(qty) <= parseFloat(stockArr[3]) ) {
        // var flag = selectedList.includes(stockId)
        // console.log("flag",flag);
        if (stockId) {
            document.querySelectorAll("#dropdown-stock option").forEach(opt => {
                //console.log(opt)
                if (opt.value == stockId) {
                    opt.disabled = true;
                }
            });
            var previewContainer = document.getElementById('preview');
            var stockInput = document.createElement('div');
            var givenQty = document.createElement('b');

            givenQty.innerText = " [" + qty + "]";
            stockInput.className = 'stockInput';
            stockInput.innerText = selectedOptionText;

            stockInput.id = selectedOption.value;
            var removeButton = document.createElement('button');
            removeButton.innerText = 'X';
            removeButton.onclick = function () {
                document.querySelectorAll("#dropdown-stock option").forEach(opt => {
                    //console.log(opt)
                    if (opt.value == stockId) {
                        opt.disabled = false;
                    }

                    delete inputArr[stockId];
                });
                previewContainer.removeChild(stockInput);
            }
            stockInput.appendChild(givenQty);

            stockInput.appendChild(removeButton);
            previewContainer.appendChild(stockInput);
            //inputArr.push(input);
            inputArr[stockId] = qty
            console.log("2", inputArr)
        }
        else {

            $("#stock_id_error").text("This stock is already selected.")
        }
    }
    else {
        $("#quantity_error").text("Please enter valid quantity.")
    }
}

function GetSelectedList() {
    var elements = document.getElementsByClassName('stockInput');
    var stockInputArray = [];
    console.log("elements", elements);
    for (var i = 0; i < elements.length; i++) {
        console.log("here", elements[i]);
        var id = elements[i].id;
        stockInputArray.push(id);
    }
    console.log("here", stockInputArray);

    return stockInputArray;
}

function createTask(url) {


    var orderListId = $("#orderListId").val();
    var orderId = ($("#ordItemId").val()).split("-")[0];
    var itemId = ($("#ordItemId").val()).split("-")[1];

    if (orderListId == "") {
        $("#order_list_id_error").text("Order id field is required.")
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }
    else {
        var taskId = $("#dropdown-task").val();
        console.log(JSON.stringify(inputArr), orderListId, taskId);
        $("#submit-create-task").prop("disabled", true);

        $.ajax({
            url: url,
            type: 'post',
            dataType: 'json',
            data: { order_list_id: orderListId, order_id: orderId, item_id: itemId, task_detail_id: taskId, stock: inputArr },
            success: function (response) {
                console.log("success", response);
                console.log(response);
                if (response.success) {
                    console.log("successentry");
                    // var inputs = form.querySelectorAll("input");
                    window.location.href = response.url;
                } else {
                    // $('#loader').hide();
                    $("#submit-create-task").prop("disabled", false);
                    const idArray = ["stock"];
                    const errorArray = ["stock_error"];

                    errorDisplay(errorArray, idArray, response.error);
                    console.log(response.error);
                    console.log("failure");
                }

            },
            error: function (xhr, status, error, response) {

                console.log("error", response);
                // console.log("error", response.data.id); // Access data-id value
                console.log("error", xhr.responseText);
            }

        });
    }
    // console.log($('#create-task-form').serializeArray());
}
function employeeDetailDelete(url, employeeDetailId) {
    console.log("submit2");
    // $('#loader').show();
    console.log("entry", employeeDetailId);
    //var form = $(this);

    $.ajax({
        url: url,
        type: 'post',
        dataType: 'json',
        data: { id: employeeDetailId },
        success: function (response) {
            console.log("success", response);
            console.log(response);
            if (response.success) {
                console.log("successentry");
                // var inputs = form.querySelectorAll("input");
                window.location.href = response.url;
            } else {
                // $('#loader').hide();
                console.log(response.error);
                console.log("failure");
            }

        },
        error: function (xhr, status, error, response) {
            console.log("error", response);
            // console.log("error", response.data.id); // Access data-id value
            console.log("error", xhr.responseText);
        }

    });
}
function stockDetailDelete(url, stockDetailId) {
    console.log("submit2");
    // $('#loader').show();
    console.log("entry", stockDetailId);
    //var form = $(this);

    $.ajax({
        url: url,
        type: 'post',
        dataType: 'json',
        data: { id: stockDetailId },
        success: function (response) {
            console.log("success", response);
            if (response.success) {
                console.log("successentry");
                // var inputs = form.querySelectorAll("input");
                window.location.href = response.url;
            } else {
                // $('#loader').hide();
                console.log(response.error);
                console.log("failure");
            }
        },
        error: function (xhr, status, error, response) {
            console.log("error", response);
            // console.log("error", response.data.id); // Access data-id value
            console.log("error", xhr.responseText);
        }

    });
}

function calculateWeight(url, length, count, product_type) {
    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        data: { length: length, count: count, product_type: product_type },
        success: function (response) {
            console.log("success", response);
            console.log(response);
            if (response.success) {
                console.log("successentry");
                // var inputs = form.querySelectorAll("input");
                //window.location.href = response.url;
                $('#quantity').prop('readonly', true);
                $("#quantity").val(response.output);
                $("#quantity-div").show();
            } else {
                // $('#loader').hide();
                console.log(response.error);
                console.log("failure");
            }

        },
        error: function (xhr, status, error, response) {
            console.log("error", response);
            // console.log("error", response.data.id); // Access data-id value
            console.log("error", xhr.responseText);
        }

    });

}

function mapDeptEmp(form) {

    //$('#loader').show();
    // var form = $(this);


    $.ajax({
        url: form.attr("action"),
        type: 'post',
        dataType: 'json',
        data: form.serialize(),
        success: function (response) {
            console.log(response);
            console.log("successentry");
            $('#loader').hide();

            if (response.success) {
                console.log("success");
                window.location.href = response.url;

            } else {
                console.log(response.error);
                const idArray = ["department", "supervisor", "employee"];
                const errorArray = ["department_error", "supervisor_error", "employee_error"];

                errorDisplay(errorArray, idArray, response.error);

            }
        },
        error: function (response) {
            console.log(response);
        }

    });
}



function insertDepartment(form) {

    //$('#loader').show();
    // var form = $(this);


    $.ajax({
        url: form.attr("action"),
        type: 'post',
        dataType: 'json',
        data: form.serialize(),
        success: function (response) {
            console.log(response);
            console.log("successentry");
            //  $('#loader').hide();

            if (response.success) {
                console.log("success");
                // window.location.href = response.url;
                $("#deptForm-button").hide();
                $("#mapDeptEmpForm").show();
                $("#dept_id").val(response.output);
                $('#department').prop('readonly', true);
                removeError();

            } else {
                console.log(response.error);
                const idArray = ["department"];
                const errorArray = ["department_error"];

                errorDisplay(errorArray, idArray, response.error);

            }
        },
        error: function (response) {
            console.log(response);
        }

    });
}

function updateDepartment(form) {

    //$('#loader').show();
    // var form = $(this);


    $.ajax({
        url: form.attr("action"),
        type: 'post',
        dataType: 'json',
        data: form.serialize(),
        success: function (response) {
            console.log(response);
            console.log("successentry");
            //  $('#loader').hide();

            if (response.success) {
                console.log("success");
                window.location.href = response.url;
            } else {
                console.log(response.error);
                const idArray = ["department"];
                const errorArray = ["department_error"];

                errorDisplay(errorArray, idArray, response.error);

            }
        },
        error: function (response) {
            console.log(response);
        }

    });
}

