let csrfParam = $(`meta[name="csrf-param"]`).attr("content");
let csrfToken = $(`meta[name="csrf-token"]`).attr("content");
let flashMessageTimer;
let userRole = $("#user-role").val();

$(document).ready(function () {
  $("#header-toggle").on("click", function () {
    $("#header-toggle").removeClass("active");
    $.ajax({
      url: "/user/navbar-setting",
      type: "get",
    });
  });

  $(".flash-message-btn").on("click", function () {
    hideFlashMessage();
  });

  $(".header__user").on("click", function () {
    $(".user-header-content").toggle();
  });

  $(".header__menu").on("click", function () {
    $(".menu-header-content").toggle();
  });

  $(".new-doctor-button").on("click", function () {
    let data = $(
      ".doctor-modal-fixed input, .doctor-modal-fixed select"
    ).serialize();
    showLoader();
    $.ajax({
      url: "/user/ajax-create",
      data: data,

      type: "POST",
      success: function (response) {
        hideLoader();
        if (response.status === "success") {
          location.reload();
        } else {
          showFlashMessage({ type: "warning", message: response.message });
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".new-technician-price-list-button").on("click", function () {
    let errors = validateTechnicianForm(collectCTechnicianFormData());
    if (errors.length > 0) {
      showErrors(errors);
      return false;
    }
    showLoader();
    $.ajax({
      url: "/technician-price-list/ajax-create",
      data: collectCTechnicianFormData(),
      type: "POST",
      success: function (response) {
        hideLoader();
        if (response.status === "success") {
          location.reload();
        } else {
          showFlashMessage({ type: "warning", message: response.message });
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".edit-technician-price-list-button").on("click", function () {
    let formData = collectUTechnicianFormData();

    let errors = validateTechnicianForm(formData);
    if (errors.length > 0) {
      showErrors(errors);
      return false;
    }

    formData.id = $('.technician-update-modal-fixed input[name="id"]').val();

    showLoader();
    $.ajax({
      url: "/technician-price-list/ajax-update",
      data: formData,
      type: "POST",
      success: function (response) {
        hideLoader();
        if (response.status === "success") {
          location.reload();
        } else {
          showFlashMessage({ type: "warning", message: response.message });
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".edit-technician-price-list-btn").on("click", function () {
    let id = $(this).data("id");
    showLoader();
    $.ajax({
      url: "/technician-price-list/ajax-get-technician-price-list",
      data: { id: id },
      type: "GET",
      success: function (response) {
        hideLoader();
        if (response.status === "success") {
          let data = response.data;
          $('.technician-update-modal-fixed input[name="id"]').val(data.id);
          $('.technician-update-modal-fixed input[name="name"]').val(data.name);
          $('.technician-update-modal-fixed input[name="price"]').val(
            data.price
          );
        } else {
          showFlashMessage({ type: "warning", message: response.message });
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".new-patient-button").on("click", function () {
    let errors = validatePatientForm();
    if (errors.length > 0) {
      showErrors(errors);
      return false;
    }
    showLoader();
    $.ajax({
      url: "/patient/ajax-create",
      data: collectPatientFormData(),
      type: "POST",
      success: function (response) {
        hideLoader();
        if (response.status === "success") {
          location.reload();
        } else {
          showFlashMessage({ type: "warning", message: response.message });
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".new-price-list-button").on("click", function () {
    let data = {
      [csrfParam]: csrfToken,
      section: $("#cardOne #nameInput").val(),
    };

    if (data.section === "") {
      showFlashMessage({
        type: "warning",
        message: "Поле не может быть пустым",
      });
      return false;
    }

    $.ajax({
      url: "/price-list/ajax-create",
      data: data,
      type: "POST",
      success: function (response) {
        if (response.status === "fail") {
          showFlashMessage({ type: "warning", message: response.message });
        } else {
          location.reload();
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".save-price-list-item").on("click", function () {
    let data = {
      [csrfParam]: csrfToken,
      price_list_id: $("#cardOne .price_list_id").val(),
      name: $("#cardOne .name").val(),
      price: $("#cardOne .price").val(),
      consumable: $("#cardOne .consumable").val(),
      technician_price_list_id: $("#cardOne .technician_price_list_id").val(),
      discount_apply: $("#cardOne .discount_apply:checked").val(),
    };

    $.ajax({
      url: "/price-list-item/ajax-create",
      data: data,
      type: "POST",
      success: function (response) {
        if (response.status === "fail") {
          showFlashMessage({ type: "warning", message: response.message });
        } else {
          location.reload();
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".edit-price-list-button").on("click", function () {
    let data = {
      id: $(this).data("id"),
    };

    $.ajax({
      url: "/price-list/ajax-get",
      data: data,
      type: "GET",
      success: function (response) {
        if (response.status === "success") {
          $("#cardThree #nameInput").val(response.model.section);
          $("#cardThree #idInput").val(response.model.id);
        } else {
          location.reload();
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".save-edit-price-list-button").on("click", function () {
    let data = {
      [csrfParam]: csrfToken,
      section: $("#cardThree #nameInput").val(),
      id: $("#cardThree #idInput").val(),
    };

    if (data.section === "") {
      showFlashMessage({
        type: "warning",
        message: "Поле не может быть пустым",
      });
      return false;
    }

    $.ajax({
      url: "/price-list/ajax-update",
      data: data,
      type: "POST",
      success: function (response) {
        if (response.status === "fail") {
          showFlashMessage({ type: "warning", message: response.message });
        } else {
          location.reload();
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".new-price-list-item-button").on("click", function () {
    let data = $(
      ".price-list-modal-fixed input, .price-list-modal-fixed select"
    ).serialize();

    $.ajax({
      url: "/price-list-item/ajax-create",
      data: data,
      type: "POST",
      success: function () {
        location.reload();
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".save-edit-price-list-item").on("click", function () {
    let data = {
      [csrfParam]: csrfToken,
      id: $("#cardThree .id").val(),
      price_list_id: $("#cardThree .price_list_id").val(),
      name: $("#cardThree .name").val(),
      price: $("#cardThree .price").val(),
      consumable: $("#cardThree .consumable").val(),
      technician_price_list_id: $("#cardThree .technician_price_list_id").val(),
      discount_apply: $("#cardThree .discount_apply:checked").val(),
    };

    $.ajax({
      url: "/price-list-item/ajax-update",
      data: data,
      type: "POST",
      success: function (response) {
        if (response.status === "fail") {
          showFlashMessage({ type: "warning", message: response.message });
        } else {
          location.reload();
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".edit-price-list-btn").on("click", function () {
    let data = {
      id: $(this).data("id"),
    };

    $.ajax({
      url: "/price-list-item/ajax-get",
      data: data,
      type: "GET",
      success: function (response) {
        if (response.status === "success") {
          $("#cardThree .modal-body").html(response.content);
        } else {
          location.reload();
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".edit-price-list-group-button").on("click", function () {
    let data = {
      id: $(this).data("id"),
    };

    $.ajax({
      url: "/price-list-item/ajax-get-group",
      data: data,
      type: "GET",
      success: function (response) {
        if (response.status === "success") {
          $("#cardFour .modal_card__body").html(response.content);
        } else {
          location.reload();
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".save-edit-price-list-group-button").on("click", function () {
    let data = {
      [csrfParam]: csrfToken,
      name: $("#cardFour #nameInput").val(),
      price_lists: $("#cardFour #demo-multiple-select2").val(),
      id: $("#cardFour #idInput").val(),
    };

    $.ajax({
      url: "/price-list-item/ajax-update-group",
      data: data,
      type: "POST",
      success: function (response) {
        if (response.status === "fail") {
          showFlashMessage({ type: "warning", message: response.message });
        } else {
          location.reload();
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".edit-doctor-button").on("click", function () {
    let data = $(
      ".doctor-modal-fixed input, .doctor-modal-fixed select"
    ).serialize();
    showLoader();
    $.ajax({
      url: "/user/ajax-update",
      data: data,
      type: "POST",
      success: function (response) {
        hideLoader();
        if (response.status === "success") {
          location.reload();
        } else {
          showFlashMessage({ type: "warning", message: response.message });
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".new-price-list-group-button").on("click", function () {
    let data = {
      [csrfParam]: csrfToken,
      name: $("#cardTwo #textInput").val(),
      price_lists: $("#cardTwo #demo-multiple-select").val(),
      price_list_id: $("#cardTwo #priceListIdInput").val(),
    };

    if (data.name === "") {
      showFlashMessage({
        type: "warning",
        message: "Поле не может быть пустым",
      });
      return false;
    }

    if (data.price_lists == "") {
      showFlashMessage({ type: "warning", message: "Выберите прайс-листы" });
      return false;
    }

    $.ajax({
      url: "/price-list-item/ajax-create-group",
      data: data,
      type: "POST",
      success: function (response) {
        if (response.status === "fail") {
          showFlashMessage({ type: "warning", message: response.message });
        } else {
          location.reload();
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".edit-patient-button").on("click", function () {
    let errors = validatePatientForm();
    if (errors.length > 0) {
      showErrors(errors);
      return false;
    }
    showLoader();
    $.ajax({
      url: "/patient/ajax-update",
      data: collectPatientFormData(),
      type: "POST",
      success: function (response) {
        hideLoader();
        if (response.status === "success") {
          location.reload();
        } else {
          showFlashMessage({ type: "warning", message: response.message });
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".new-doctor-btn").on("click", function () {
    $(".doctor-modal-wrap").show();
  });

  $(".new-technician-btn").on("click", function () {
    $(".technician-modal-wrap").show();
  });

  $(".technician-modal-wrap").on("click", function () {
    $(".technician-modal-wrap").hide();
  });

  $(".technician-update-modal-wrap").on("click", function () {
    $(".technician-update-modal-wrap").hide();
  });

  $(".edit-technician-price-list-btn").on("click", function () {
    $(".technician-update-modal-wrap").show();
  });

  $(".doctor-modal-wrap").on("click", function (event) {
    $(".doctor-modal-wrap").hide();
  });

  $(".new-patient-btn").on("click", function () {
    $(".patient-modal-wrap").show();
  });

  $(".patient-modal-wrap").on("click", function (event) {
    $(".patient-modal-wrap").hide();
  });

  $(".new-price-list-btn").on("click", function () {
    $(".price-list-modal-wrap").show();
  });

  $(".price-list-modal-wrap").on("click", function (event) {
    $(".price-list-modal-wrap").hide();
  });

  $("#prices").change(function () {
    if ($("select option:selected").val() === "price-list") {
      $("#price-list").css("display", "block");
      $("#new-price-list-button").css("display", "block");
      $("#price-list-item").css("display", "none");
      $("#new-price-list-item-button").css("display", "none");
    }
    if ($("select option:selected").val() === "price-list-item") {
      $("#price-list").css("display", "none");
      $("#new-price-list-button").css("display", "none");
      $("#price-list-item").css("display", "block");
      $("#new-price-list-item-button").css("display", "block");
    }
  });

  $(".remove-user-select").on("click", function () {
    showHideRemoveBTN();
  });

  $(".remove-patient-select").on("click", function () {
    showHideRemovePatientBTN();
  });

  $(".remove-technician-select").on("click", function () {
    showHideRemoveTechnicianBTN();
  });

  $(".remove-selected-users").on("click", function () {
    if (confirm("Вы уверены?") == true) {
      let selectedIds = $(`input[type="checkbox"].remove-user-select:checked`)
        .map(function () {
          return $(this).data("id");
        })
        .get();
      $.ajax({
        url: "/user/ajax-delete",
        data: { ids: selectedIds, [csrfParam]: csrfToken },
        type: "POST",
        success: function () {
          location.reload();
        },
        error: function (response) {
          hideLoader();
          showFlashMessage({
            type: "warning",
            message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
          });
        },
      });
    }
  });

  $(".remove-selected-technicians").on("click", function () {
    if (confirm("Вы уверены?") == true) {
      let selectedIds = $(
        `input[type="checkbox"].remove-technician-select:checked`
      )
        .map(function () {
          return $(this).data("id");
        })
        .get();

      $.ajax({
        url: "/technician-price-list/ajax-delete",
        data: { ids: selectedIds, [csrfParam]: csrfToken },
        type: "POST",
        success: function () {
          location.reload();
        },
        error: function (response) {
          hideLoader();
          showFlashMessage({
            type: "warning",
            message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
          });
        },
      });
    }
  });

  $(".remove-selected-patients").on("click", function () {
    if (confirm("Вы уверены?") == true) {
      let selectedIds = $(
        `input[type="checkbox"].remove-patient-select:checked`
      )
        .map(function () {
          return $(this).data("id");
        })
        .get();
      showLoader();
      $.ajax({
        url: "/patient/ajax-delete",
        data: { ids: selectedIds, [csrfParam]: csrfToken },
        type: "POST",
        success: function (response) {
          hideLoader();
          if (response.status === "success") {
            location.reload();
          } else {
            showFlashMessage({ type: "warning", message: response.message });
          }
        },
        error: function (response) {
          hideLoader();
          showFlashMessage({
            type: "warning",
            message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
          });
        },
      });
    }
  });

  $(".add-new-price-cat").on("click", function () {
    $(".add-new-price-cat-input").show();
  });

  $(".save-new-price-cat").on("click", function () {
    $(this).attr("disabled");
    let priceCatTitle = $(`input[name="price-cat-title"]`).val();
    $.ajax({
      url: "/price-list/add-category",
      data: { title: priceCatTitle, [csrfParam]: csrfToken },
      type: "POST",
      success: function () {
        $(this).removeAttr("disabled");
        location.reload();
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".add-child-price").on("click", function () {
    $(".add-child-price-cat-input").show();
  });

  $(".save-child-price-cat").on("click", function () {
    $(this).attr("disabled");
    let priceCatTitle = $(`input[name="child-price-title"]`).val();
    let priceListId = $(`input[name="price_list_id"]`).val();
    let priceAmount = $(`input[name="child-price-amount"]`).val();
    $.ajax({
      url: "/price-list/add-child",
      data: {
        title: priceCatTitle,
        price_list_id: priceListId,
        amount: priceAmount,
        [csrfParam]: csrfToken,
      },
      type: "POST",
      success: function () {
        $(this).removeAttr("disabled");
        location.reload();
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".select2-instance").select2();

  $(".js-example-basic-single").select2();

  $(".add-new-record").on("click", addNewRecord);

  $(".sms-switch").on("click", function () {
    $(this).toggleClass("sms-off");
    $(".sms-time-block").toggleClass("sms-hide-block");
  });

  $(".sms-time-select").on("click", function () {
    $(".sms-time-select").addClass("checkbox__btn_outline");
    $(".sms-time-select").removeClass("checkbox__btn_orange");
    $(".sms-time-select").removeAttr("name");

    $(this).removeClass("checkbox__btn_outline");
    $(this).addClass("checkbox__btn_orange");
    $(this).attr("name", "sms-time");
  });

  const d = new Date();
  let hour = d.getHours();
  let minutes = d.getMinutes();
  if (document.getElementById("new-record-date")) {
    document.getElementById("new-record-date").valueAsDate = d;
    document.getElementById("time_from").value = hour + ":" + minutes;
  }

  $("#reception-doctor-filter").on("change", function () {
    let date = $(".activeCalendar").data("date");
    let calendar = $("#calendarSelect").val();
    let doctor_id = $(this).val();

    if (calendar === "day") {
      location.href =
        "/reception/index?date=" + date + "&doctor_id=" + doctor_id;
    } else {
      let dateArr = date.split("_");
      location.href =
        "/reception/week?start_date=" +
        dateArr[0] +
        "&end_date=" +
        dateArr[1] +
        "&doctor_id=" +
        doctor_id;
    }
  });

  $(".calendar_swiper .btn_prev").on("click", function () {
    let element = $(".swiper-wrapper .swiper-slide").first();
    let currentMarginLeft = parseInt(element.css("margin-left"));
    let newMargin = currentMarginLeft + 160;
    if (newMargin > 0) {
      return false;
    }
    element.animate({ marginLeft: `${newMargin}px` }, 200);
  });

  $(".calendar_swiper .btn_next").on("click", function () {
    let element = $(".swiper-wrapper .swiper-slide").first();
    let currentMarginLeft = parseInt(element.css("margin-left"));
    let newMargin = currentMarginLeft - 160;
    element.animate({ marginLeft: `${newMargin}px` }, 200);
  });

  $(".filter-change-date").on("click", function () {
    let date = $(this).data("date");

    let params = [
      {
        param: "doctor_id",
        value: $("#reception-doctor-filter").val(),
      },
      {
        param: "date",
        value: date,
      },
    ];

    let newData = params.map(function (item) {
      return `${item.param}=${item.value}`;
    });

    let urlParams = newData.join("&");

    location.href = `/reception/index?${urlParams}`;
  });

  $("#update-user-btn").on("click", function () {
    $(".doctor-modal-wrap").show();
  });

  $(".edit-patient").on("click", function () {
    $(".patient-modal-fixed h2").text("Редактировать данные пациента");
    $(".patient-modal-wrap").show();
  });

  $(".change-photo-input").on("change", function () {
    $(this).closest("form").submit();
  });

  $(".doctor-add-new-record").on("click", function () {
    $("#modalBtn").click();
    let doctorId = $(this).data("doctor-id");
    let sectionId = $(this).data("section-id");
    let recordDate = $(".reception-date").val();

    $("#new-record-date").val(recordDate);
    $('[name="doctor_id"]').val(doctorId).trigger("change");
    $('[name="category_id"]').val(sectionId).trigger("change");
  });

  $(".sms-switch").click();

  /* Preliminary invoice | Предварительный счет */
  $("#preliminary").click(function () {
    const isPreliminary = this.checked;
    if (isPreliminary) {
      $("#create_from_preliminary").css("display", "inline");
    } else {
      $("#create_from_preliminary").css("display", "none");
    }
  });

  /* Teeth Graph | График зубов */
  /* Select all | Выбрать все */
  $("#select_all").click(function () {
    const isChecked = this.checked;
    if (isChecked) {
      $(".right-tooth_icon__top").addClass("active");
      $(".right-tooth_icon__bottom").addClass("active");
    } else {
      $(".right-tooth_icon__top").removeClass("active");
      $(".right-tooth_icon__bottom").removeClass("active");
    }
  });

  /* Enable children's teeth | Включить детские зубы */
  $("#switchs").click(function () {
    var isChecked = this.checked;
    if (isChecked) {
      $(".right-tooth_icon__bottom").css("display", "block");
    } else {
      $(".right-tooth_icon__bottom").css("display", "none");
      if (!document.getElementById("select_all").checked) {
        $(".right-tooth_icon__bottom.active").removeClass("active");
      }
    }
  });

  /* Permanent teeth | Постоянные зубы */
  /* Children's teeth | Детские зубы */
  $(".right-tooth_icon__top, .right-tooth_icon__bottom").click(function () {
    var hasActiveClass = $(this).hasClass("active");
    if (hasActiveClass) {
      $(this).removeClass("active");
      if (document.getElementById("select_all").checked) {
        $("#select_all").prop("checked", false);
      }
    } else {
      $(this).addClass("active");
    }
  });

  /* Price list calculating to patient */
  $(".accordion__panel_item").click(function () {
    let hasActive = $(this).hasClass("active");
    if (hasActive) {
      $(this).removeClass("active");
      $(this).find("div:first-child").remove();
    } else {
      let content =
        '<div class="accordion__panel_item_img">\n' +
        '<img src="/img/circleCheck.svg" alt="">\n' +
        "</div>";
      $(this).addClass("active");
      $(this).prepend(content);
    }
  });

  fixToPositions();

  $("#preliminary").on("click", function () {
    $(this).toggleClass("checked");
  });

  $("#save").on("click", function () {
    if (!validateNewInvoiceForm()) {
      return false;
    }
    showLoader();
    let data = collectInvoiceData();
    $.ajax({
      url: "/invoice/ajax-create",
      data: data,
      type: "post",
      success: function (response) {
        hideLoader();
        if (response.status === "success") {
          location.href = `/patient/update?id=${data.patient_id}&invoice_id=${response.message}`;
        } else {
          showFlashMessage({ type: "warning", message: response.message });
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".pay_right_type span").on("click", function () {
    $(".pay_right_type span.active").removeClass("active");
    $(this).addClass("active");
  });

  $(".add-money-to-account").on("click", addMoneyToAccount);
  $(".invoice-pay").on("click", invoicePay);

  $(".new-schedule-form").on("click", function () {
    $(this).hide();
  });

  $(".new-schedule-form,.new-doctor-appointment-form").on("click", function () {
    $(this).hide();
  });

  $(".save-doctor-schedule").on("click", saveDoctorSchedule);
  $(".save-doctor-appointment").on("click", saveDoctorAppointment);

  $(".add-new-schedule").on("click", function () {
    $(".new-schedule-form").show();
  });

  $(".doctor-schedule .week-day").on("click", function () {
    const arr = {
      mon: "ПН",
      tue: "ВТ",
      wed: "СР",
      thu: "ЧТ",
      fri: "ПТ",
      sat: "СБ",
      sun: "ВС",
    };

    let weekday = $(this).data("week-day");
    $("#appointment-weekday").text(arr[weekday]);
    $('input[name="weekday"]').val(weekday);
    $(".new-doctor-appointment-form").show();
  });

  $("#report-top-select").on("change", function () {
    let url = $(this).val();
    location.href = `/report/${url}`;
  });

  $(".patient-search-input").on("keyup", patientSearch);
  $(".patient-search-input").focus();

  initListeners();

  $(".select-items div").on("click", function () {
    let data = {
      [csrfParam]: csrfToken,
    };
    data.id = $(this).parent().siblings("input[type=hidden]").val();
    data.status = $(this).data("id");
    showLoader();
    $.ajax({
      url: "/appointment-request/handle",
      data: data,
      type: "post",
      success: function (response) {
        hideLoader();
        if (response.status === "success") {
          location.reload();
        } else {
          showFlashMessage({ type: "warning", message: response.message });
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".patient-file-delete").on("click", function () {
    if (confirm("Вы уверены?")) {
      $(".patient-file-delete").off();
      let data = {
        [csrfParam]: csrfToken,
      };
      data.id = $(this).data("id");
      $.ajax({
        url: "/patient/delete-file",
        data: data,
        type: "POST",
        success: function () {
          location.reload();
        },
        error: function (response) {
          hideLoader();
          showFlashMessage({
            type: "warning",
            message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
          });
        },
      });
    }
  });

  $(".reception-date").on("change", function () {
    let date = $(this).val();
    let current_doctor_id = $(
      'input[name="reception_current_doctor_id"]'
    ).val();
    location.href = `/reception/index?date=${date}&doctor_id=${current_doctor_id}`;
  });

  $("#modalBtn, .reception-new-patient .modal-overlay").on(
    "click",
    function () {
      if ($(".modal-overlay").hasClass("modal-overlay--visible")) {
        $("body").addClass("new-record-popup");

        // let recordDate = $(".reception-date").val();
        // $("#new-record-date").val(recordDate);
      } else {
        $("body").removeClass("new-record-popup");
      }
    }
  );

  $("#profile-doctor-assistant").on("change", function () {
    let data = {
      [csrfParam]: csrfToken,
    };
    data.assistantId = $(this).val();
    data.doctorId = $("#profile-doctor-id").val();
    $.ajax({
      url: "/profile/change-assistant",
      data: data,
      type: "post",
      success: function (response) {
        location.reload();
      },
      error: function (response) {
        console.log(response);
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  removeIndicatorOnActiveListeners();

  $("#send-sms-code").on("click", function () {
    let data = {
      [csrfParam]: csrfToken,
    };
    data.login = $("#login").val();
    if (data.login.length < 1) {
      $("#login").addClass("validation-error");
      removeIndicatorOnActiveListeners();
      return false;
    }

    showLoader();
    $.ajax({
      url: "/site/send-sms-code",
      data: data,
      type: "post",
      success: function (response) {
        hideLoader();
        if (response.status === "success") {
          showFlashMessage({
            type: "success",
            message: `SMS с кодом отправлен на телефон ${data.login}`,
          });
          $(".reset-password-login").hide();
          $(".reset-password-new-password").show();
        } else if (response.status === "fail") {
          if (response.message === "not_found") {
            showFlashMessage({
              type: "warning",
              message: "Пользователь не найден!",
            });
          } else {
            showFlashMessage({ type: "warning", message: response.message });
          }
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });

    return false;
  });

  $(".flash-message-wrap").on("click", function () {
    clearTimeout(flashMessageTimer);
    $(this).hide();
  });

  $("#change-password-btn").on("click", function () {
    let data = {
      [csrfParam]: csrfToken,
    };
    data.code = $("#sms-code").val();
    data.password1 = $("#password1").val();
    data.password2 = $("#password2").val();
    data.login = $("#login").val();
    if (data.code.length < 1) {
      $("#sms-code").addClass("validation-error");
    }
    if (data.password1.length < 1) {
      $("#password1").addClass("validation-error");
    }
    if (data.password2.length < 1) {
      $("#password2").addClass("validation-error");
    }
    if ($(".validation-error").length > 0) {
      removeIndicatorOnActiveListeners();
      return false;
    }

    if (data.password1 !== data.password2) {
      showFlashMessage({ type: "warning", message: "Пароли не совпадают!" });
      return false;
    }

    showLoader();
    $.ajax({
      url: "/user/change-password",
      data: data,
      type: "post",
      success: function (response) {
        hideLoader();
        if (response.status === "success") {
          showFlashMessage({
            type: "success",
            message: `пароль успешно изменен`,
          });
          setTimeout(function () {
            location.href = "/site/login";
          }, 3000);
        } else if (response.status === "fail") {
          if (response.message === "passwords_different") {
            showFlashMessage({
              type: "warning",
              message: "Пароли не совпадают",
            });
          } else if (response.message === "invalid_code") {
            showFlashMessage({ type: "warning", message: "Код не правильный" });
          } else if (response.message === "user_not_found") {
            showFlashMessage({
              type: "warning",
              message: "Пользователь не найден",
            });
          } else if (response.message === "reset_code_expired") {
            showFlashMessage({ type: "warning", message: "Код устарел" });
          } else {
            showFlashMessage({ type: "warning", message: response.message });
          }
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });

    return false;
  });

  let selectors = [
    ".record-details-wrap",
    ".assign-discount-modal",
    ".add-money-modal",
    ".transfer-money-modal",
    ".invoice-details-modal",
    ".invoice-warning-modal",
    ".upload-files-modal",
    ".new-examination-modal",
    ".withdraw-money-modal",
    ".invoice-pay-wrap",
    ".accept-invoice-modal",
  ];

  $(selectors.join(",")).on("click", function () {
    $(this).hide();
  });

  stopPropagationList();

  initEditRemovePatientListener();

  $(".btn_orange").on("click", newRecordFormReset);

  $('select[name="per_page"]').on("change", function () {
    $(this).closest("form").submit();
  });

  $(".pagination_right_btn button").on("click", function () {
    if (!$(this).hasClass("button_active")) {
      return false;
    }
  });
  $(".pagination_right_btn .button_active").on("click", function () {
    let add = -1;
    if ($(this).hasClass("next-page")) {
      add = 1;
    }
    let currentPage = parseInt($('input[name="page"]').val());
    $('input[name="page"]').val(currentPage + add);
    $(this).closest("form").submit();
  });

  $(".cancel-reason").on("click", function () {
    $('textarea[name="cancel-reason"]').val($(this).text());
  });

  $(".cancel-record-btn").on("click", function () {
    let data = {
      [csrfParam]: csrfToken,
    };

    data.id = $(this).data("id");
    data.reason = $('textarea[name="cancel-reason"]').val();
    showLoader();
    $.ajax({
      url: "/reception/cancel-record",
      data: data,
      type: "post",
      success: function (response) {
        hideLoader();
        if (response.status === "success") {
          location.reload();
        } else {
          showFlashMessage({ type: "warning", message: response.message });
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".pli-title-filter").on("change", function () {
    $(`form[name="price-list-form"] input[name="sort"]`).val($(this).val());
    $(`form[name="price-list-form"]`).submit();
  });

  $(".pli-pl-filter").on("change", function () {
    $(`form[name="price-list-form"] input[name="price_list_id"]`).val(
      $(this).val()
    );
    $(`form[name="price-list-form"]`).submit();
  });

  $(".patients__filter").on("click", function () {
    $(".patient-filters-wrap").toggle();
  });

  $("body").on("click", function () {
    $(".patient-filters-wrap").hide();
  });

  $(".apply-patient-filters").on("click", function () {
    $(`input[name="discount_patients"]`).val(
      +$("#patient_with_discount").is(":checked")
    );
    $(`form[name="patient_list_form"]`).submit();
  });

  $(".active-filter span").on("click", function () {
    let name = $(this).data("name");
    $(`input[name="${name}"]`).val(0);
    $(`form[name="patient_list_form"]`).submit();
  });

  $(".assign_discount").on("click", function () {
    $(".assign-discount-modal").show();
    $(".director-wrap,.others-wrap").hide();
    if (currentUserCan("assign_discount")) {
      $(".director-wrap").show();
    } else {
      $(".others-wrap").show();
    }
  });

  $(".discount-type li").on("click", function () {
    $(".discount-type li").removeClass("active");
    $(this).addClass("active");
  });

  $(".assign-discount-btn").on("click", function () {
    let thisBTN = $(this);
    if ($(".modal__sale-body-boxes div.active").length < 1) {
      showFlashMessage({ type: "warning", message: "Выберите размер скидки" });
      return false;
    }

    let data = {
      [csrfParam]: csrfToken,
    };
    data.discount = $(".modal__sale-body-boxes div.active")
      .first()
      .data("discount");
    data.patient_id = thisBTN.data("patient-id");

    showLoader();
    $.ajax({
      url: "/patient/assign-discount",
      data: data,
      type: "POST",
      success: function (msg) {
        showLoader();
        location.reload();
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".request-discount-btn").on("click", function () {
    let thisBTN = $(this);
    if ($(".modal__sale-body-boxes div.active").length < 1) {
      showFlashMessage({ type: "warning", message: "Выберите размер скидки" });
      return false;
    }

    let data = {
      [csrfParam]: csrfToken,
    };
    data.discount = $(".modal__sale-body-boxes div.active")
      .first()
      .data("discount");
    data.patient_id = thisBTN.data("patient-id");

    showLoader();
    $.ajax({
      url: "/patient/request-discount",
      data: data,
      type: "POST",
      success: function (msg) {
        showLoader();
        location.reload();
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".transfer-money-patient").on("click", function () {
    $(".transfer-money-modal").show();
  });

  $(".add-money-patient").on("click", function () {
    $(".add-money-modal").show();
  });

  $(".withdraw-money-patient").on("click", function () {
    $(".withdraw-money-modal").show();
  });

  $(".payment-method-wrap div").on("click", function () {
    $(".payment-method-wrap div.active").removeClass("active");
    $(this).addClass("active");
  });

  $(".add-money-btn").on("click", function () {
    addMoneyToAccount();
  });

  $(".transfer-money-btn").on("click", function () {
    transferMoney();
  });

  $(".withdraw-money-money-btn").on("click", function () {
    withdrawMoney();
  });

  $(".invoice-details-btn").on("click", function () {
    let data = {};
    $(".invoice-details-modal").show();
    data.id = $(this).data("id");
    $(".invoice-details-form-wrap").html(
      '<img src="/img/loading3.gif" class="loader-card" />'
    );
    $.ajax({
      url: "/invoice/get-invoice",
      data: data,
      success: function (response) {
        let html = invoiceDetailsHTML(response);
        $(".invoice-details-form-wrap").html(html);
        invoiceDetailsModalListeners();
      },
      error: function (response) {
        console.log(response);
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".upload-patient-files-btn").on("click", function () {
    $(".upload-files-modal").show();
  });
  $(".new-examination-btn").on("click", function () {
    $(".new-examination-modal").show();
  });

  if (
    location.href.indexOf("/user/records") > -1 ||
    location.href.indexOf("/user/current-date-record") > -1
  ) {
    startCheck();
  }

  $(".save-doctor-percent").on("click", function () {
    showLoader();
    $.ajax({
      url: "/user/update-salary-percents",
      type: "POST",
      data: collectDoctorPercentData(),
      success: function (response) {
        location.reload();
      },
      error: function (response) {
        console.log(response);
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".show-salary-details").off("click");
  $(".show-salary-details").on("click", function () {
    $(this).closest("tr").next().toggle();
  });

  $(".approve-discount-request").on("click", function () {
    let thisItem = $(this);
    let data = {
      [csrfParam]: csrfToken,
      request_id: thisItem.data("id"),
    };

    showLoader();
    $.ajax({
      url: "/patient/approve-discount-request",
      data: data,
      type: "post",
      success: function (response) {
        hideLoader();
        if (response.status === "success") {
          location.reload();
        } else {
          showFlashMessage({ type: "warning", message: response.message });
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".check-perm").on("click", function () {
    let thisItem = $(this);
    let data = {
      [csrfParam]: csrfToken,
      status: thisItem.prop("checked") ? "on" : "off",
      user_id: thisItem.data("user-id"),
      permission: thisItem.data("permission"),
    };

    showLoader();
    $.ajax({
      url: "/user/change-permission",
      data: data,
      type: "post",
      success: function (response) {
        hideLoader();
        if (response.status === "success") {
          //location.reload();
        } else {
          showFlashMessage({ type: "warning", message: response.message });
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".make-actual").on("click", function () {
    let thisItem = $(this);
    let data = {
      [csrfParam]: csrfToken,
      schedule_id: thisItem.data("id"),
    };

    showLoader();
    $.ajax({
      url: "/user/make-schedule-actual",
      data: data,
      type: "post",
      success: function (response) {
        hideLoader();
        if (response.status === "success") {
          location.reload();
        } else {
          showFlashMessage({ type: "warning", message: response.message });
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".patient-note-input").on("keyup", function () {
    let newValue = $(".patient-note-input").val();
    let oldValue = $(".patient-note-hidden-input").val();
    if (newValue == oldValue) {
      $(".user_note_bottom_button").hide();
    } else {
      $(".user_note_bottom_button").show();
    }
  });

  $("#calendarSelect").on("change", function () {
    let thisItem = $(this).val();

    if (thisItem === "day") {
      window.location.href = "/reception/index";
    } else {
      window.location.href = "/reception/week";
    }
  });

  $(".swiper-slide").on("click", function () {
    let date = $(this).data("date");
    let calendar = $("#calendarSelect").val();
    let doctor_id = $("#reception-doctor-filter").val();

    if (calendar === "day") {
      location.href =
        "/reception/index?date=" + date + "&doctor_id=" + doctor_id;
    } else {
      let dateArr = date.split("_");
      location.href =
        "/reception/week?start_date=" +
        dateArr[0] +
        "&end_date=" +
        dateArr[1] +
        "&doctor_id=" +
        doctor_id;
    }
  });
});

function showHideRemoveBTN() {
  let checkboxes = $(`input[type="checkbox"].remove-user-select:checked`);
  if (checkboxes.length > 0) {
    $(".remove-selected-users").show();
  } else {
    $(".remove-selected-users").hide();
  }
}

function showHideRemovePatientBTN() {
  let checkboxes = $(`input[type="checkbox"].remove-patient-select:checked`);
  if (checkboxes.length > 0) {
    $(".remove-selected-patients").show();
  } else {
    $(".remove-selected-patients").hide();
  }
}

function showHideRemoveTechnicianBTN() {
  let checkboxes = $(`input[type="checkbox"].remove-technician-select:checked`);
  if (checkboxes.length > 0) {
    $(".remove-selected-technicians").show();
  } else {
    $(".remove-selected-technicians").hide();
  }
}

function hideNewPatientFields() {
  // $('.new-patient-fields').hide();
}

function showNewPatientFields() {
  $(".new-patient-fields").show();
}

function addNewRecord() {
  let errors = validateNewPatientForm();
  if (errors.length > 0) {
    showErrors(errors);
    return false;
  }
  showLoader();
  $.ajax({
    url: "/reception/new-record",
    data: collectNewPatientFormData(),
    type: "POST",
    success: function (response) {
      hideLoader();
      if (response.status === "success") {
        location.reload();
      } else {
        showFlashMessage({ type: "warning", message: response.message });
      }
    },
    error: function (response) {
      hideLoader();
      showFlashMessage({
        type: "warning",
        message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
      });
    },
  });
}

function showSubSection(element) {
  let idItem = $(element).attr("id");
  let idItemChild = "#" + idItem + "-child";
  switch ($(idItemChild).css("display")) {
    case "none":
      $(idItemChild).css("display", "block");
      break;
    case "block":
      $(idItemChild).css("display", "none");
      break;
    default:
      break;
  }
}

function bodyItemBtnPrepend(element) {
  let nextElem = $(element).next();
  let count = nextElem.html();
  if (count > 1) {
    count--;
    nextElem.html(count);
    addInvoice(element, count, -1);
  }
}

function bodyItemBtnAppend(element) {
  let prevElem = $(element).prev();
  let count = prevElem.html();
  count++;
  prevElem.html(count);
  addInvoice(element, count, 1);
}

/* Adding services to patient invoice */
function addInvoice(element, count, countEdit) {
  let teethCount = $(element).parent().prev().find("span:first-child").html();
  let servicePrice = $(element)
    .parent()
    .next()
    .find("span:first-child")
    .data("raw-price");
  let price = teethCount * count * servicePrice;
  let discountApply = $(element).parent().parent().data("discount-apply");

  $(element)
    .parent()
    .next()
    .next()
    .find("span:first-child")
    .html(numberWithSpace(price) + " сум");
  $(element)
    .parent()
    .next()
    .next()
    .find("span:first-child")
    .data("raw-price", price);

  updateTotals({
    invoiceCount: 1,
    servicePrice: teethCount * countEdit * servicePrice,
    discountApply: discountApply,
  });
}

// Adding service to invoice
function addServiceToInvoice() {
  let teeth = [];

  let teethTop = $(".right-tooth_icon__top.active");
  let teethBottom = $(".right-tooth_icon__bottom.active");

  teethTop.each(function () {
    teeth.push($(this).find("span").html());
    $(this).removeClass("active");
  });

  teethBottom.each(function () {
    teeth.push($(this).find("span").html());
    $(this).removeClass("active");
  });

  /*if (teeth.length < 1) {
        alert('Сначало выберите зуб(ы)!');
        return false;
    }*/

  let serviceItems = $(".accordion__panel_item.active");

  let invoiceSum = null;

  if (serviceItems.length < 1) {
    alert("Выберите услугу!");
    return false;
  }

  serviceItems.each(function () {
    let serviceName = $(this).find("p.info__text").html().trim();
    let serviceID = $(this).data("service-id");
    let servicePrice = $(this).find("p.text__bold").data("price");
    let discountApply = $(this).find("p.text__bold").data("discount-apply");
    let invoiceName = serviceName + " " + "(зуб(ы): " + teeth.join(", ") + ")";
    let invoiceCount = teeth.length || 1;
    invoiceSum = invoiceCount * servicePrice;

    let content = getServiceRow({
      invoiceCount: invoiceCount,
      invoiceName: invoiceName,
      servicePrice: servicePrice,
      invoiceSum: invoiceSum,
      serviceID: serviceID,
      teeth: teeth.join(", "),
      discountApply: discountApply,
    });

    $(".right_services_body").append(content);
    removeServiceListener();
    updateTotals({
      servicePrice: servicePrice,
      invoiceCount: invoiceCount,
      discountApply: discountApply,
    });

    $(this).removeClass("active");
    $(this).find("div:first-child").remove();
  });

  $(".accordion__button-active").click();
  $("#close__modal").click();
}

function fixToPositions() {
  $(".adoptive-trick_calendar_card-wrapper .card__item_box").each(function () {
    let element = $(this);
    let hour = parseInt(element.data("hour"));
    let minutes = parseInt(element.data("minutes"));
    let diff = hour - 9;
    let duration = element.data("duration");
    let top = diff * 200 + minutes * 3.33;
    let height = duration * 3.33;
    element.css({ top: top + "px", height: height + "px" });
  });
}

function collectInvoiceData() {
  let data = {
    [csrfParam]: csrfToken,
  };
  data.patient_id = $('input[name="patient_id"]').val();
  data.doctor_id = $('select[name="invoice-doctor"]').val();
  data.assistant_id = $('select[name="invoice-assistant"]').val();
  data.reception_id = $('select[name="reception_id"]').val();
  data.preliminary = +$("#preliminary").hasClass("checked");
  data.invoice_services = collectInvoiceServices();

  return data;
}

function collectInvoiceServices() {
  let invoiceServices = [];
  $(".right_services_body .right_services_body_row").each(function () {
    let invoiceService = {};
    invoiceService.id = $(this).data("service-id");
    invoiceService.teeth = $(this).data("teeth");
    invoiceService.amount = $(this).find(".body_item_number").text().trim();
    invoiceServices.push(invoiceService);
  });
  return invoiceServices;
}

function numberWithSpace(x) {
  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
}

function updateTotals(data) {
  let discount = $("#discount-wrap").data("raw-percent");
  let subtotalWrapper = $("#total_without_discount");
  let priceWithoutDiscount = subtotalWrapper.data("raw-price");
  let priceWithDiscount = $("#total_with_discount").data("raw-price");

  priceWithoutDiscount =
    data.invoiceCount * data.servicePrice + priceWithoutDiscount;

  if (data.discountApply && discount > 0) {
    priceWithDiscount =
      priceWithDiscount +
      (data.invoiceCount * data.servicePrice * discount) / 100;
  } else {
    priceWithDiscount =
      data.invoiceCount * data.servicePrice + priceWithDiscount;
  }

  subtotalWrapper.html(numberWithSpace(priceWithoutDiscount) + " сум");
  subtotalWrapper.data("raw-price", priceWithoutDiscount);

  $("#total_with_discount").html(numberWithSpace(priceWithDiscount) + " сум");
  $("#total_with_discount").data("raw-price", priceWithDiscount);
}

function getServiceRow(data) {
  return `
                <div class='right_services_body_row' data-service-id="${
                  data.serviceID
                }" data-teeth="${data.teeth}" data-discount-apply="${
    data.discountApply
  }"> 
                    <div class='right_services_body_item'> 
                        <span hidden>${data.invoiceCount}  </span>
                        <span>${data.invoiceName}</span>
                    </div>
                    <div class='right_services_body_item'>
                        <span class='body_item_btn body_item_btn_prepend' onclick='bodyItemBtnPrepend(this)'> 
                            <img src='/img/iconLeft.svg' alt=''> 
                        </span>
                        <span class='body_item_number'>1</span>
                        <span class='body_item_btn body_item_btn_append' onclick='bodyItemBtnAppend(this)'> 
                            <img src='/img/iconRight.svg' alt=''> 
                        </span>
                    </div>
                    <div class='right_services_body_item'> 
                        <span data-raw-price="${
                          data.servicePrice
                        }">${numberWithSpace(data.servicePrice)} сум</span>
                    </div>
                    <div class='right_services_body_item'>
                        <span data-raw-price="${
                          data.invoiceSum
                        }">${numberWithSpace(data.invoiceSum)} сум</span>
                    </div> 
                    <div class='right_services_body_item'>
                        <img src="/img/svg/remove_icon.svg" class="remove-service-item" style="cursor: pointer;" />
                    </div>
                </div>`;
}

function validateNewInvoiceForm() {
  let invoiceData = collectInvoiceData();
  let errors = [];
  if (invoiceData.doctor_id === "") {
    errors.push([" - Выберите врача"]);
  }
  if (invoiceData.reception_id === "") {
    errors.push([" - Выберите визит"]);
  }

  if ($(".right_services_body .right_services_body_row").length < 1) {
    errors.push([" - Выберите хотя бы одну услугу"]);
  }

  if (errors.length > 0) {
    let errorMessage = `Исправьте следующие ошибки: <br />`;
    errorMessage += errors.join("<br />");
    showFlashMessage({ type: "warning", message: errorMessage });
    return false;
  } else {
    return true;
  }
}

function addMoneyToAccount() {
  let data = {
    [csrfParam]: csrfToken,
    patient_id: $("input#patient-id").val(),
    amount: $('.add-money-form-wrap input[name="amount"]').val(),
    payment_method: $(".pay_right_type span.active").data("payment-method"),
    is_foreign_currency:
      $(".add-money-form-wrap #checkDollar").prop("checked") === true,
    foreign_currency_rate: $(
      '.add-money-form-wrap #dollarInput input[name="amount"]'
    ).val(),
  };

  if (data.amount.length < 1) {
    showFlashMessage({
      type: "warning",
      message: "Пожалуйста, укажите сумму пополнения!",
    });
    return false;
  }

  if (data.payment_method === undefined) {
    showFlashMessage({
      type: "warning",
      message: "Пожалуйста, выберите способ оплаты!",
    });
    return false;
  }

  if (
    data.is_foreign_currency === true &&
    data.foreign_currency_rate.length < 1
  ) {
    showFlashMessage({
      type: "warning",
      message: "Пожалуйста, укажите курс валюты!",
    });
    return false;
  }

  showLoader();
  $.ajax({
    url: "/patient/add-money-to-account",
    data: data,
    type: "post",
    success: function (response) {
      hideLoader();
      if (response.status === "success") {
        location.reload();
      } else {
        showFlashMessage({ type: "warning", message: response.message });
      }
    },
    error: function (response) {
      hideLoader();
      showFlashMessage({
        type: "warning",
        message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
      });
    },
  });
}

function transferMoney() {
  if (confirm("Вы уверены?")) {
    let data = {
      [csrfParam]: csrfToken,
      sender_patient_id: $("select#sender-patient-id").val(),
      recipient_patient_id: $("select#recipient-patient-id").val(),
      amount: $('.transfer-money-form-wrap input[name="amount"]').val(),
    };

    if (data.amount.length < 1) {
      showFlashMessage({
        type: "warning",
        message: "Пожалуйста, укажите сумму перевода!",
      });
      return false;
    }

    showLoader();
    $.ajax({
      url: "/cashier/transfer-money",
      data: data,
      type: "post",
      success: function (response) {
        hideLoader();
        if (response.status === "success") {
          location.reload();
        } else {
          showFlashMessage({ type: "warning", message: response.message });
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  }
}

function withdrawMoney() {
  if (confirm("Вы уверены?")) {
    let data = {
      [csrfParam]: csrfToken,
      patient_id: $("input#patient-id").val(),
      amount: $('.withdraw-money-form-wrap input[name="amount"]').val(),
      reason: $('.withdraw-money-form-wrap textarea[name="reason"]').val(),
    };

    if (data.amount.length < 1) {
      showFlashMessage({
        type: "warning",
        message: "Пожалуйста, укажите сумму!",
      });
      return false;
    }

    showLoader();
    $.ajax({
      url: "/cashier/withdraw-money",
      data: data,
      type: "post",
      success: function (response) {
        hideLoader();
        if (response.status === "success") {
          location.reload();
        } else {
          showFlashMessage({ type: "warning", message: response.message });
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  }
}

function showLoader() {
  $("#loader-main").show();
}

function hideLoader() {
  $("#loader-main").hide();
}

function hideFlashMessage() {
  $(".flash-message-wrap").hide();
}

function invoicePay() {
  if (confirm("Вы подтверждаете свое действие")) {
    let data = {
      [csrfParam]: csrfToken,
      invoice_id: $('.invoice-pay-content input[name="invoice_id"]').val(),
      amount: $('.invoice-pay-content input[name="amount"]').val(),
    };

    if (data.amount.length < 1) {
      showFlashMessage({
        type: "warning",
        message: "Пожалуйста, укажите сумму!",
      });
      return false;
    }

    showLoader();
    $.ajax({
      url: "/invoice/pay",
      data: data,
      type: "post",
      success: function (response) {
        hideLoader();
        if (response.status === "success") {
          location.reload();
        } else if (response.code === "not_enough_money") {
          $(".invoice-pay-wrap").hide();
          $(".invoice-warning-modal").show();
          data.need_amount = response.message;
          let html = notEnoughMoneyHTML(data);
          $(".invoice-warning-modal .invoice-warning-form-wrap").html(html);
        } else {
          showFlashMessage({ type: "warning", message: response.message });
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  }
}

function invoiceCancel() {
  let data = {
    [csrfParam]: csrfToken,
    invoice_id: $("input#invoice-id").val(),
  };

  if (confirm("Вы подтверждаете свое действие")) {
    showLoader();
    $.ajax({
      url: "/invoice/cancel",
      data: data,
      type: "post",
      success: function (response) {
        hideLoader();
        if (response.status === "success") {
          location.reload();
        } else {
          showFlashMessage({ type: "warning", message: response.message });
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  }
}

function updateInvoice() {
  let data = {
    [csrfParam]: csrfToken,
    id: $("input#invoice-id").val(),
  };

  $.ajax({
    url: "/invoice/get-invoice",
    data: data,
    type: "get",
    success: function (response) {
      if (response.invoiceServices.length > 0) {
        $(".right_services_body").html("");
        $.each(response.invoiceServices, function (key, invoiceService) {
          let serviceName = invoiceService.title;
          let invoiceName =
            serviceName + " " + "(зуб(ы): " + invoiceService.teeth + ")";
          let total =
            parseInt(invoiceService.teeth_amount) *
            parseInt(invoiceService.amount) *
            parseInt(invoiceService.price_with_discount);
          let servicePrice = invoiceService.price;
          let invoiceCount = invoiceService.amount;
          let discountApply = invoiceService.discountApply;

          let content = getServiceRow({
            invoiceCount: invoiceCount,
            invoiceName: invoiceName,
            servicePrice: servicePrice,
            invoiceSum: total,
            serviceID: invoiceService.price_list_item_id,
            teeth: invoiceService.teeth,
          });

          $(".right_services_body").append(content);

          updateTotals({
            servicePrice: servicePrice,
            invoiceCount: invoiceCount,
            discountApply: discountApply,
          });
        });
        $(".invoice-details-modal").hide();

        $("select[name=invoice-doctor]")
          .val(response.doctor_id)
          .trigger("change");
        $("select[name=invoice-assistant]")
          .val(response.doctor.assistant_id)
          .trigger("change");
      }
    },
    error: function (response) {
      hideLoader();
      showFlashMessage({
        type: "warning",
        message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
      });
    },
  });
}

function insuranceInvoice() {
  let data = {
    [csrfParam]: csrfToken,
    invoice_id: $("input#invoice-id").val(),
  };

  if (confirm("Вы подтверждаете свое действие")) {
    showLoader();
    $.ajax({
      url: "/invoice/insurance",
      data: data,
      type: "post",
      success: function (response) {
        hideLoader();
        if (response.status === "success") {
          location.reload();
        } else {
          showFlashMessage({ type: "warning", message: response.message });
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  }
}

function enumerationInvoice() {
  let data = {
    [csrfParam]: csrfToken,
    invoice_id: $("input#invoice-id").val(),
  };

  if (confirm("Вы подтверждаете свое действие")) {
    showLoader();
    $.ajax({
      url: "/invoice/enumeration",
      data: data,
      type: "post",
      success: function (response) {
        hideLoader();
        if (response.status === "success") {
          location.reload();
        } else {
          showFlashMessage({ type: "warning", message: response.message });
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  }
}

function debtInvoice() {
  let data = {
    [csrfParam]: csrfToken,
    invoice_id: $("input#invoice-id").val(),
  };

  if (confirm("Вы подтверждаете свое действие")) {
    showLoader();
    $.ajax({
      url: "/invoice/debt",
      data: data,
      type: "post",
      success: function (response) {
        hideLoader();
        if (response.status === "success") {
          location.reload();
        } else {
          showFlashMessage({ type: "warning", message: response.message });
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  }
}

function saveDoctorSchedule() {
  let data = { [csrfParam]: csrfToken };
  data.doctor_id = $('input[name="doctor_id"]').val();
  data.date_from = $('input[name="schedule_date_from"]').val();
  data.date_to = $('input[name="schedule_date_to"]').val();
  data.doctor_id = $('input[name="doctor_id"]').val();
  showLoader();
  $.ajax({
    url: "/user/doctor-schedule-add",
    data: data,
    type: "post",
    success: function (response) {
      hideLoader();
      if (response.status === "success") {
        location.reload();
      } else {
        showFlashMessage({ type: "warning", message: response.message });
      }
    },
    error: function (response) {
      hideLoader();
      showFlashMessage({
        type: "warning",
        message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
      });
    },
  });
}

function saveDoctorAppointment() {
  let data = { [csrfParam]: csrfToken };
  data.time_from = $('input[name="appointment_time_from"]').val();
  data.time_to = $('input[name="appointment_time_to"]').val();
  data.weekday = $('input[name="weekday"]').val();
  data.doctor_schedule_id = $("#schedule-id").val();

  showLoader();
  $.ajax({
    url: "/user/doctor-schedule-item-add",
    data: data,
    type: "post",
    success: function (response) {
      hideLoader();
      if (response.status === "success") {
        location.reload();
      } else {
        showFlashMessage({ type: "warning", message: response.message });
      }
    },
    error: function (response) {
      hideLoader();
      showFlashMessage({
        type: "warning",
        message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
      });
    },
  });
}

let timerID = null;

function patientSearch() {
  $(".patients__pagination").hide();
  let text = $(".patient-search-input").val();
  if (text.length > 2) {
    clearTimeout(timerID);
    timerID = setTimeout(patientSearchSendRequest, 500, text);
  } else if (text.length === 0) {
    location.reload();
  }
}

function patientSearchSendRequest(text) {
  showLoader();
  let data = { [csrfParam]: csrfToken };
  data.text = text;
  $.ajax({
    url: "/patient/search",
    data: data,
    success: function (response) {
      hideLoader();
      $(".patients__table table tr").remove();
      if (response.length > 0) {
        $(".patients__table table").append(getPatientTableHeader());
        $.each(response, function (key, patient) {
          $(".patients__table table").append(getPatientRow(patient));
        });
        initListeners();
      } else {
        $(".patients__table table").append(
          `<tr><td>Пациент не найден</td></tr>`
        );
      }
    },
    error: function (response) {
      hideLoader();
      showFlashMessage({
        type: "warning",
        message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
      });
    },
  });
}

function getPatientRow(patient) {
  let dob = patient.dob ? patient.dob : "";
  let prefix = userRole == "cashier" ? "finance" : "update";
  let out = `<tr>
                    <td><input type="checkbox" class="remove-patient-select" data-id="${patient.id}"></td>
                    <td><p>${patient.id}</p></td>
                    <td><a href="/patient/${prefix}?id=${patient.id}">${patient.lastname} ${patient.firstname}</a></td>
                    <td><p>${dob}</p></td>
                    <td><p>${patient.phone}</p></td>`;
  if (patient.vip === "1") {
    out += `<td><p class="tex_green">Да</p></td>`;
  } else {
    out += `<td><p class="tex_red">Нет</p></td>`;
  }
  if (currentUserCan("view_patient_discount")) {
    out +=
      parseInt(patient.discount) > 0
        ? `<td><p class="tex_green">${patient.discount}%</p></td>`
        : `<td><p class="tex_red">0%</p></td>`;
  }

  let doctor_name = "";
  if (patient.doctor) {
    doctor_name = `${patient.doctor.lastname} ${patient.doctor.firstname}`;
  }
  let last_visit = patient.last_visited ? patient.last_visited : "";
  out += `
                    <td><p>${doctor_name}</p></td>
                    <td><p>${last_visit}</p></td>
                </tr>`;

  return out;
}

function getPatientTableHeader() {
  //let filterImage = `<span class="filter_img"><img src="/img/icon_arrow.svg" alt="" /><img src="/img/icon_arrow_down.svg" alt="" /></span>`;
  let filterImage = ``;
  let discount = currentUserCan("view_patient_discount")
    ? `<td className="table_head"><div className="filter_text"><span>Скидка</span>${filterImage}</div></td>`
    : "";
  return `<tr>
                <td></td>
                <td><div class="filter_text"><span>#ID</span></div></td>
                <td class="table_head"><div class="filter_text"><span>ФИО</span>${filterImage}</div></td>
                <td class="table_head"><div class="filter_text"><span>Дата рождения</span>${filterImage}</div></td>
                <td class="table_head"><div class="filter_text"><span>Мобильный номер</span>${filterImage}</div></td>
                <td class="table_head"><div class="filter_text"><span>VIP</span>${filterImage}</div></td>
                ${discount}
                <td class="table_head"><div class="filter_text"><span>Лечащий врач</span>${filterImage}</div></td>
                <td class="table_head"><div class="filter_text"><span>Дата последнего визита</span>${filterImage}</div></td>
            </tr>`;
}

function initListeners() {
  $(".remove-patient-select").off("click");
  $(".remove-patient-select").on("click", function () {
    showHideRemovePatientBTN();
  });
}

function collectNewPatientFormData() {
  let data = {
    [csrfParam]: csrfToken,
  };
  data.patient_id = $(`#patient_id`).val();
  data.first_name = $(`input[name="first_name"]`).val();
  data.last_name = $(`input[name="last_name"]`).val();
  data.phone = $(`input[name="phone"]`).val();
  data.birthday = $(`input[name="birthday"]`).val();
  data.gender = $(`select[name="gender"]`).val();
  data.doctor_id = $(`select[name="doctor_id"]`).val();
  data.date = $(`input[name="date"]`).val();
  data.time_from = $(`input[name="time_from"]`).val();
  data.duration = $(`select[name="time_to"]`).val();
  data.time_to = addMinutes(`${data.time_from}:00`, data.duration);
  data.comments = $(`textarea[name="comments"]`).val();
  data.sms = !$(".sms-switch").hasClass("sms-off");
  data.sms_time = $('button[name="sms-time"]').val();
  data.category_id = $(`select[name="category_id"]`).val();
  data.record_id = $('input[name="record_id"]').val();

  return data;
}

function validateNewPatientForm() {
  $(".validation-error").removeClass("validation-error");
  let errors = [];
  let data = collectNewPatientFormData();
  if (data.patient_id == 0) {
    if (data.first_name.length < 1) {
      errors.push({
        element: $(`input[name="first_name"]`),
        message: "Заполните имя",
      });
    }

    if (data.last_name.length < 1) {
      errors.push({
        element: $(`input[name="last_name"]`),
        message: "Заполните фамилию",
      });
    }

    if (data.phone.length < 1) {
      errors.push({
        element: $(`input[name="phone"]`),
        message: "Заполните телефон",
      });
    }

    if (data.birthday.length < 1) {
      errors.push({
        element: $(`.doctor__date #new-birthday`),
        message: "Выберите дату",
      });
    }

    if (data.gender < 1) {
      errors.push({
        element: $(`.doctor__date .new_gender`),
        message: "Выберите пол",
      });
    }
  }

  if (data.doctor_id.length < 1) {
    errors.push({
      element: $(`#select-doctor-wrap`),
      message: "Выберите врача",
    });
  }

  if (data.date.length < 1) {
    errors.push({
      element: $(`.doctor__date #select5`),
      message: "Выберите дату",
    });
  }

  if (data.time_from < 1) {
    errors.push({
      element: $(`.time_from_wrap`),
      message: "Выберите время начала",
    });
  }

  if (data.duration === null) {
    errors.push({
      element: $(`time_to_wrap`),
      message: "Выберите время окончания",
    });
  }

  return errors;
}

function showErrors(errors) {
  $.each(errors, function (index, error) {
    error.element.addClass("validation-error");
  });

  removeIndicatorOnActiveListeners();
}

function removeIndicatorOnActiveListeners() {
  let className = "validation-error";
  let element = $(`.${className}`);
  element.off("focus");
  element.on("focus", function () {
    $(this).removeClass(`${className}`);
  });

  element.off("click");
  element.on("click", function () {
    $(this).removeClass(`${className}`);
  });
}

function showFlashMessage(data) {
  $(".message-icon-wrap").html(`<img src="/img/${data.type}.svg" />`);
  $(".flash-message-body").html(data.message);
  $(".flash-message-wrap").show();
  flashMessageTimer = setTimeout(function () {
    $(".flash-message-wrap").hide();
  }, 5000);
}

function stopPropagationList() {
  let list = [
    ".doctor-modal-fixed",
    ".technician-modal-fixed",
    ".technician-update-modal-fixed",
    ".patient-modal-fixed",
    ".price-list-modal-fixed",
    ".new-schedule-form > div",
    ".new-doctor-appointment-form > div",
    ".flash-message-content",
    ".record-details-content",
    ".edit-reception-record",
    ".remove-record",
    ".cancel-record-wrap",
    ".patient-filters-wrap",
    ".patients__filter",
    ".discount-form-wrap",
    ".add-money-form-wrap",
    ".transfer-money-form-wrap",
    ".invoice-details-form-wrap",
    ".invoice-warning-form-wrap",
    ".upload-files-form-wrap",
    ".new-examination-form-wrap",
    ".withdraw-money-form-wrap",
    ".invoice-pay-content",
    ".accept-invoice-form-wrap",
  ];

  let selectors = list.join(",");
  $(selectors).on("click", function (event) {
    event.stopPropagation();
  });
}

function collectRecordDetails(data) {
  let collected = {};
  let element = $(`.single-record-card[data-id=${data.id}]`);
  collected.duration = element.data("duration");
  collected.fullname = element.find("h2").text();
  return collected;
}

function recordDetailsHtml(data) {
  let sms = "Нет";
  if (data.sms === "day_before") {
    sms = "За день";
  } else if (data.sms === "on_the_day") {
    sms = "В день приёма";
  }

  let recordDate = new Date(`${data.record_date} ${data.record_time_from}`);
  let currentDate = new Date();
  let recordDateFormatted = new Date(`${data.record_date}`).toLocaleDateString(
    "ru-RU",
    {
      day: "numeric",
      month: "numeric",
      year: "numeric",
    }
  );

  let timePassed = recordDate.getTime() < currentDate.getTime();

  let telegram = "";
  if (data.patient.chat_id && !timePassed) {
    telegram = `<button class="btn-reset btn-blue-outline send-telegram-notification" data-id="${data.id}">
            Напомнить по телеграм
</button>`;
  }

  let smsNotification = "";
  if (!timePassed) {
    smsNotification = `<button class="btn-reset btn-blue-outline send-sms-notification" data-id="${data.id}">
            Напомнить по СМС
        </button>`;
  }
  let comment = data.comment ? data.comment : "Комментарий отсутствует";

  let section = data.category ? data.category.section : "";
  let patientUrl =
    userRole == "cashier" ? "/patient/finance?id=" : "/patient/update?id=";
  return `
<div class="doctor__modal">
    <div class="doctor__modal__header">
        <h2 class="doctor__modal__header-title">Информация о пациенте</h2>
        <img src="/img/scheduleNew/IconClose.svg" alt="" id="closeModalAll">
    </div>  
    <div class="doctor__modal-body">
         <div class="doctor__modal-body-top">
            <div class="doctor__modal-body-item">
                <span class="text-gray">Пациент:</span>
                <a href="${patientUrl}${data.patient.id}" class="text-black">${data.patient.firstname} ${data.patient.lastname}</a>
            </div>
            <div class="doctor__modal-body-item">
                <span class="text-gray">Врач:</span>
                <a href="/user/update?id=${data.doctor.id}" class="text-black">${data.doctor.firstname} ${data.doctor.lastname} (${section})</a>
            </div>
        </div>
         <div class="doctor__modal-body-center">
            <div class="doctor__modal-body-item">
            <span class="text-gray">Дата:</span>
            <p class="text-black">${recordDateFormatted}</p>
         </div>
             <div class="doctor__modal-body-item">
                <span class="text-gray">Время:</span>
                <p class="text-black">с ${data.record_time_from} до ${data.record_time_to}</p>
             </div>
             <div class="doctor__modal-body-item">
                <span class="text-gray">Мобильный номер:</span>
                <p class="text-black">${data.patient.phone}</p>
             </div>
         </div>
         <div class="doctor__modal-body-bottom">
            <div class="doctor__modal-body-comment">
                <span class="text-gray">Комментарии</span> 
                <p class="doctor__modal-body-comment-text">${comment} </p>         
             </div>
             <div class="doctor__modal-body-item">
                <span class="text-gray">Напомнить по СМС:</span>
                <p class="text-black">${sms}</p>
             </div>
         </div>
         <div class="record-view-actions">
            <div class="doctor__modal-body-footer">
                <button class="btn-reset btn-blue-delete remove-record" data-id="${data.id}">Удалить</button>
                <button class="btn-reset btn-blue-cancel cancel-record" data-id="${data.id}">Отменить</button>
                ${smsNotification}
                ${telegram}
                <button class="btn-reset btn-blue edit-reception-record" data-id="${data.id}">Редактировать</button>
                <button class="btn-reset btn-green the-patient-came" data-id="${data.id}">Пациент пришел</button>
            </div>
        </div>
         <div class="record-doctor-actions">
            <div class="doctor__modal-body-footer">
                <button class="btn-reset btn-blue start-admission" data-id="${data.id}">Начать прием</button>
                <a href="/patient/update?id=${data.patient.id}&record_id=${data.id}" class="btn-reset btn-blue create-invoice-btn" data-id="${data.id}">Выставить счет</a>
             </div>   
        </div>
    </div>      
</div>
    `;
}

function addMinutes(time, minsToAdd) {
  function D(J) {
    return (J < 10 ? "0" : "") + J;
  }

  var piece = time.split(":");
  var mins = piece[0] * 60 + +piece[1] + +minsToAdd;

  return D(((mins % (24 * 60)) / 60) | 0) + ":" + D(mins % 60);
}

function populateRecordForm(record) {
  $("#lastname").val(record.patient.lastname);
  $("#name").val(record.patient.firstname);
  $("#phonenumber").val(record.patient.phone);
  $('[name="patient_id"]').val(record.patient_id);
  $('[name="doctor_id"]').val(record.doctor_id).trigger("change");
  $('[name="category_id"]').val(record.category_id).trigger("change");
  $('textarea[name="comments"]').val(record.comment);
  $("#time_from").val(record.record_time_from.substr(0, 5));
  $("#time_to").val(record.duration);
  $("#new-record-date").val(record.record_date);

  if (record.sms === "day_before") {
    if (!$("#switch").is(":checked")) {
      $(".sms-switch").click();
    }
    $('button[value="day_before"]').click();
  } else if (record.sms === "on_the_day") {
    if (!$("#switch").is(":checked")) {
      $(".sms-switch").click();
    }
    $('button[value="on_the_day"]').click();
  } else {
    if ($("#switch").is(":checked")) {
      $(".sms-switch").click();
    }
  }
}

function newRecordFormReset() {
  $(".reception-new-patient h2").text("ЗАПИСАТЬ ПАЦИЕНТА");
  $('input[name="last_name"]').val("");
  $('input[name="first_name"]').val("");
  $('input[name="phone"]').val("");
  $('[name="doctor_id"]').val("").trigger("change");
  $('[name="category_id"]').val("").trigger("change");
  $('input[name="record_id"]').val("");
  $('textarea[name="comments"]').val("");
  $("#time_from").val("");
  $("#time_to").val(30);
  $("#new-record-date").val(new Date().toISOString().slice(0, 10));
}

function initEditRemovePatientListener() {
  $(".remove-record").off("click");
  $(".remove-record").on("click", function () {
    let data = {
      [csrfParam]: csrfToken,
    };
    data.id = $(this).data("id");
    if (confirm("Вы уверены?")) {
      showLoader();
      $.ajax({
        url: "/reception/remove-record",
        data: data,
        success: function () {
          hideLoader();
          location.reload();
        },
        error: function (response) {
          hideLoader();
          showFlashMessage({
            type: "warning",
            message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
          });
        },
      });
    }
  });

  $(".edit-reception-record").off("click");
  $(".edit-reception-record").on("click", function () {
    $("#modalBtn").click();
    $(".reception-new-patient h2").text("Редактировать запись");
    let data = {
      [csrfParam]: csrfToken,
    };
    data.id = $(this).data("id");
    $('input[name="record_id"]').val(data.id);
    showLoader();
    $.ajax({
      url: "/reception/get-record",
      data: data,
      success: function (response) {
        $(".record-details-wrap").hide();
        hidePatientFields();
        hideLoader();
        populateRecordForm(response);
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".send-sms-notification").off("click");
  $(".send-sms-notification").on("click", function () {
    let data = {
      [csrfParam]: csrfToken,
    };
    data.id = $(this).data("id");
    showLoader();
    $.ajax({
      url: "/reception/send-sms-notification",
      data: data,
      success: function (response) {
        hideLoader();
        if (response.status === "success") {
          showFlashMessage({
            type: "success",
            message: `СМС напоминание успешно отправлено!`,
          });
        } else if (response.status === "fail") {
          showFlashMessage({ type: "warning", message: response.message });
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".send-telegram-notification").off("click");
  $(".send-telegram-notification").on("click", function () {
    let data = {
      [csrfParam]: csrfToken,
    };
    data.id = $(this).data("id");
    showLoader();
    $.ajax({
      url: "/reception/send-telegram-notification",
      data: data,
      success: function (response) {
        hideLoader();
        if (response.status === "success") {
          showFlashMessage({
            type: "success",
            message: `Телеграм напоминание успешно отправлено!`,
          });
        } else if (response.status === "fail") {
          showFlashMessage({ type: "warning", message: response.message });
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".single-record-card").off("click");
  $(".single-record-card").on("click", function () {
    let data = {
      [csrfParam]: csrfToken,
    };
    $(".record-details-wrap").show();
    data.id = $(this).data("id");
    $(".record-details-content").html(
      '<img src="/img/loading3.gif" class="loader-card" />'
    );
    $.ajax({
      url: "/reception/get-record",
      data: data,
      success: function (response) {
        $(".cancel-record-wrap").hide();
        let html = recordDetailsHtml(response);
        $(".record-details-content").html(html).show();
        initEditRemovePatientListener();
        $(".cancel-record-btn").data("id", data.id);
        if (userRole == "doctor") {
          $(".record-view-actions").hide();
          $(".record-doctor-actions").show();
        }
        if (response.state == "admission_started") {
          $(".start-admission").hide();
          $(".the-patient-came").hide();
          $(".create-invoice-btn").show();
        } else if (response.state == "admission_finished") {
          $(".start-admission").hide();
          $(".record-view-actions").hide();
          if (userRole == "cashier") {
            $(".record-cashier-actions").show();
          }
        } else if (response.state == "patient_came") {
          $(".the-patient-came").hide();
          $(".start-admission").show();
        }
      },
      error: function (response) {
        console.log(response);
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".cancel-record").off("click");
  $(".cancel-record").on("click", function () {
    $(".cancel-record-wrap").show();
    $(".record-details-content").hide();
  });

  $(".the-patient-came").off("click");
  $(".the-patient-came").on("click", function () {
    let data = {
      [csrfParam]: csrfToken,
    };
    data.id = $(this).data("id");
    showLoader();
    $.ajax({
      url: "/reception/the-patient-came",
      data: data,
      success: function (response) {
        hideLoader();
        if (response.status === "success") {
          $(".record-details-wrap").hide();
          showFlashMessage({
            type: "success",
            message: `Уведомление врачу успешно отправлено!`,
          });
        } else if (response.status === "fail") {
          showFlashMessage({ type: "warning", message: response.message });
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".start-admission").off("click");
  $(".start-admission").on("click", function () {
    let data = {
      [csrfParam]: csrfToken,
    };
    data.id = $(this).data("id");
    showLoader();
    $.ajax({
      url: "/reception/start-admission",
      data: data,
      success: function (response) {
        hideLoader();
        if (response.status === "success") {
          $(".record-details-wrap").hide();
          $(".start-admission").hide();
          $(".create-invoice-btn").show();
        } else if (response.status === "fail") {
          showFlashMessage({ type: "warning", message: response.message });
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".finish-admission").off("click");
  $(".finish-admission").on("click", function () {
    let data = {
      [csrfParam]: csrfToken,
    };
    data.id = $(this).data("id");
    showLoader();
    $.ajax({
      url: "/reception/finish-admission",
      data: data,
      success: function (response) {
        hideLoader();
        if (response.status === "success") {
          showFlashMessage({ type: "success", message: `Прием завершен!` });
        } else if (response.status === "fail") {
          showFlashMessage({ type: "warning", message: response.message });
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".accept-invoice-btn").off("click");
  $(".accept-invoice-btn").on("click", function () {
    $(".accept-invoice-modal").show();
    showLoader();
    $.ajax({
      url: "/invoice/details",
      data: {
        id: $(this).data("id"),
      },
      type: "GET",
      success: function (response) {
        hideLoader();
        if (response.status === "success") {
          $(".accept-invoice-modal .invoice__modal .invoice__modal-body").html(
            response.content
          );
        } else if (response.status === "fail") {
          showFlashMessage({ type: "warning", message: response.message });
        }
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".pay-insurance-invoice-btn").off("click");
  $(".pay-insurance-invoice-btn").on("click", function () {
    if (confirm("Вы уверены, что хотите оплатить счет?")) {
      let data = {
        [csrfParam]: csrfToken,
        invoice_id: $('.accept-invoice-modal input[name="invoice_id"]').val(),
        amount: $('.accept-invoice-modal input[name="amount"]').val(),
      };

      if (data.amount.length < 1) {
        showFlashMessage({
          type: "warning",
          message: "Пожалуйста, укажите сумму!",
        });
        return false;
      }

      $.ajax({
        url: "/invoice/pay-insurance-invoice",
        data: data,
        type: "POST",
        success: function (response) {
          hideLoader();
          if (response.status === "success") {
            location.reload();
          } else if (response.status === "fail") {
            showFlashMessage({ type: "warning", message: response.message });
          }
        },
        error: function (response) {
          hideLoader();
          showFlashMessage({
            type: "warning",
            message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
          });
        },
      });
    }
  });

  $("#closeModalAll").click(function () {
    $(".record-details-wrap").hide();
  });
}

function collectPatientFormData() {
  let data = {
    [csrfParam]: csrfToken,
  };
  data.first_name = $('input[name="first_name"]').val();
  data.last_name = $('input[name="last_name"]').val();
  data.phone = $('input[name="phone"]').val();
  data.discount = $('input[name="discount"]').val();
  data.dob = $('input[name="dob"]').val();
  data.vip = $('select[name="vip"]').val();
  data.gender = $('select[name="gender"]').val();
  data.doctor_id = $('select[name="doctor_id"]').val();
  data.patient_id = $('input[name="patient_id"]').val();
  return data;
}

function validatePatientForm() {
  $(".validation-error").removeClass("validation-error");
  let errors = [];
  let data = collectPatientFormData();

  if (data.dob.length < 1) {
    errors.push({
      element: $(`input[name="dob"]`),
      message: "Выберите дату рождения",
    });
  }

  if (data.gender.length < 1) {
    errors.push({
      element: $(`select[name="gender"]`),
      message: "Выберите пол",
    });
  }

  if (data.first_name.length < 1) {
    errors.push({
      element: $(`input[name="first_name"]`),
      message: "Заполните имя",
    });
  }

  if (data.last_name.length < 1) {
    errors.push({
      element: $(`input[name="last_name"]`),
      message: "Заполните фамилию",
    });
  }

  if (data.phone.length < 1) {
    errors.push({
      element: $(`input[name="phone"]`),
      message: "Заполните телефон",
    });
  }
  return errors;
}

function collectCTechnicianFormData() {
  let data = {
    [csrfParam]: csrfToken,
  };
  data.name = $('.technician-modal-fixed input[name="name"]').val();
  data.price = $('.technician-modal-fixed input[name="price"]').val();
  return data;
}

function collectUTechnicianFormData() {
  let data = {
    [csrfParam]: csrfToken,
  };
  data.name = $('.technician-update-modal-fixed input[name="name"]').val();
  data.price = $('.technician-update-modal-fixed input[name="price"]').val();
  return data;
}

function validateTechnicianForm(formData) {
  $(".validation-error").removeClass("validation-error");
  let errors = [];
  let data = formData;

  if (data.name.length < 1) {
    errors.push({
      element: $(`input[name="name"]`),
      message: "Заполните название",
    });
  }

  if (data.price.length < 1) {
    errors.push({
      element: $(`input[name="price"]`),
      message: "Заполните цена",
    });
  }

  return errors;
}

function invoiceDetailsHTML(data) {
  let status = "";
  let statusHtml;
  let statusClass = "";
  let deleteButton = "";
  if (data.preliminary == 0) {
    if (
      data.type == 1 ||
      (data.type == 2 && data.status == 1) ||
      (data.type == 3 && data.status == 1)
    ) {
      status = "Закрытий";
      statusClass = "green_status";
    } else if (data.type == 2 && data.status == 0) {
      status = "Долг";
      statusClass = "red_status";
    } else if (data.type == 3 && data.status == 0) {
      status = "Страховой";
      statusClass = "purple_status";
    } else if (data.type == 0) {
      status = "Новый";
      statusClass = "blue_status";
    } else if (data.type == 4) {
      status = "Аннулированный";
      statusClass = "yellow_status";
    } else if (data.type == 5) {
      status = "Перечисление";
      statusClass = "darkblue_status";
    }

    statusHtml =
      `<span class="modal__bill-status-badge ${statusClass}">` +
      status +
      `</span>`;
    deleteButton =
      (data.type == 2 || data.type == 3) &&
      data.status == 0 &&
      currentUserCan("invoice_delete")
        ? `<button type="button" class="delete-invoice-btn" data-id="${data.id}">Удалить</button>`
        : ``;
  } else {
    statusHtml = `<span class="modal__bill-status-badge red_status">Предварительный</span>`;
    deleteButton = ``;
  }

  let services = "";
  let total = 0;
  let totalWithDiscount = 0;
  let payButton =
    (data.type == 0 ||
      (data.type == 2 && data.status == 0) ||
      (data.type == 5 && data.status == 0) ||
      (data.type == 3 && data.status == 0)) &&
    data.preliminary == 0 &&
    currentUserCan("invoice_pay")
      ? `<button class="btn-reset btn-green pay-invoice-btn" data-id="${data.id}">Оплатить</button>`
      : "";
  let cancelButton =
    data.type == 0 && data.preliminary == 0 && currentUserCan("invoice_cancel")
      ? `<button class="btn-reset btn-cancel cancel-invoice-btn" data-id="${data.id}">Отменить</button>`
      : "";
  let updateButton =
    data.preliminary == 1 && currentUserCan("invoice_ajax_create")
      ? `<button class="btn-reset btn-blue update-invoice-btn" data-id="${data.id}">Выставить инвойс</button>`
      : "";
  let insuranceButton =
    data.type == 0 &&
    data.preliminary == 0 &&
    currentUserCan("invoice_insurance")
      ? `<button class="btn-reset btn-blue insurance-invoice-btn" style="width: 140px;" data-id="${data.id}">
                Выставить страховой
            </button>`
      : "";
  let enumerationButton =
    data.type == 0 &&
    data.preliminary == 0 &&
    currentUserCan("invoice_enumeration")
      ? `<button class="btn-reset btn-transfer enumeration-invoice-btn" style="width: 140px;" data-id="${data.id}">
                Перечесление
            </button>`
      : "";
  let debtButton =
    data.type == 0 && data.preliminary == 0 && currentUserCan("invoice_debt")
      ? `<button class="btn-reset btn-black debt-invoice-btn" data-id="${data.id}">Записать в долг</button>`
      : "";

  let paymentsHtml = "";
  let transactionsHtml = "";
  if (data.payments.length > 0) {
    $.each(data.payments, function (key, payment) {
      let paymentDateFormatted = new Date(
        `${payment.created_at}`
      ).toLocaleDateString("ru-RU", {
        day: "numeric",
        month: "numeric",
        year: "numeric",
      });
      let paymentTimeFormatted = new Date(
        `${payment.created_at}`
      ).toLocaleTimeString("ru-RU", {
        hour: "numeric",
        minute: "numeric",
      });
      let paymentUser = payment.user.lastname ? payment.user.lastname : "";

      transactionsHtml += `<div class="paymentContainer">
                <div class="info">
                    <div class="date">
                        <p>${paymentDateFormatted}</p>
                        <p>${paymentTimeFormatted}</p>
                    </div>
                    <div class="bills">
                        <p class="section">Счет</p>
                        <p>#${data.id}</p>
                    </div>
                    <div class="personalInfo">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M12 1.875C6.21053 1.875 1.5 6.58553 1.5 12.375C1.5 18.1645 6.21053 22.875 12 22.875C17.7895 22.875 22.5 18.1645 22.5 12.375C22.5 6.58553 17.7895 1.875 12 1.875ZM17.8684 9.61184L11.1579 16.2697C10.7632 16.6645 10.1316 16.6908 9.71053 16.2961L6.1579 13.0592C5.73684 12.6645 5.71053 12.0066 6.07895 11.5855C6.47368 11.1645 7.13158 11.1382 7.55263 11.5329L10.3684 14.1118L16.3684 8.11184C16.7895 7.69079 17.4474 7.69079 17.8684 8.11184C18.2895 8.5329 18.2895 9.19079 17.8684 9.61184Z" fill="#27A55D"/>
                            </svg>
                        </span>
                        <p>${paymentUser}</p>
                    </div>
                </div>
                <div class="paymentInfo">
                    <div class="paymentType">
                        <p class="section">Тип</p>
                        <p>Оплата</p>
                    </div>
                    <p class="sum">${numberWithSpace(payment.amount)} сум</p>
                </div>
            </div>`;
    });
    paymentsHtml = `<div class="paymentWrapper">
            <div class="paymentWrapperHeader">
                <h2>История транзакций</h2>
            </div>
            ${transactionsHtml}
        </div>`;
  }

  if (data.invoiceServices.length > 0) {
    $.each(data.invoiceServices, function (key, invoiceService) {
      let price =
        parseInt(invoiceService.teeth_amount) *
        parseInt(invoiceService.amount) *
        parseInt(invoiceService.price);
      let priceWithDiscount =
        parseInt(invoiceService.teeth_amount) *
        parseInt(invoiceService.amount) *
        parseInt(invoiceService.price_with_discount);
      total += price;
      totalWithDiscount += priceWithDiscount;
      services += `
            <tr>
                <td style="width: 140px;">${invoiceService.title}</td>
                <td>${invoiceService.teeth}</td>
                <td>${invoiceService.amount}</td>
                <td>${numberWithSpace(price)} сум</td>
                <td>${numberWithSpace(priceWithDiscount)} сум</td>
            </tr>
            `;
    });
    return `
            <div class="modal__bill">
                <div class="modal__bill-header">
                    <div class="modal__bill-header-left">
                        <h2 class="modal__bill-header-left-title">Инвойс №${
                          data.id
                        }</h2>
                        <a href="/print/invoice?id=${
                          data.id
                        }" class="modal__bill-header-print" target="_blank">
                            <img src="/img/scheduleNew/print.svg" alt="">Распечатать
                        </a>
                        ${deleteButton}
                    </div>
                    <div class="modal__bill-header-right test" id="closeModalAll">
                        <img src="/img/scheduleNew/IconClose.svg" alt="">
                    </div>
                </div>
                <div class='modal__bill-top' style="display:flex; justify-content: space-between; padding: 1.5rem 1.5rem 0 1.5rem;">
                    <div class="modal__bill-status">
                        <p class="text-gray">Статус:</p>
                        ${statusHtml}
                    </div>
                    <div class="modal__bill-info">
                        <div class="modal__bill-info-item">
                            <p class="text-gray">Врач:</p>
                            <p class="text-black">${data.doctor.lastname} ${
      data.doctor.firstname
    }</p>
                        </div>
                    </div>
                    <div class="modal__bill-info">
                        <div class="modal__bill-info-item">
                            <p class="text-gray">Скидка:</p>
                            <p class="text-black">${data.discount}%</p>
                        </div>
                    </div>
                </div>
                <div  class='modal__bill-body' style="display:flex; column-gap: 1.25rem; padding: 0 1.5rem  1.5rem 1.5rem;">
                    <div class="modal__bill-body">
                        <div class="modal__bill-table-wrapper">
                            <table class="modal__bill-table" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>Услуга</th>
                                    <th>Зубы</th>
                                    <th>Количество</th>
                                    <th>Цена</th>
                                    <th>Цена со скидкой</th>
                                </tr>
                                </thead>
                                <tbody>
                                ${services}
                                </tbody>
                            </table>
                        </div>
                        <div class="modal__bill-info">
                            <div class="modal__bill-info-item">
                                <p class="text-gray">Сумма всех услуг:</p>
                                <p class="text-black">${numberWithSpace(
                                  total
                                )} сум</p>
                            </div>
                            <div class="modal__bill-info-item">
                                <p class="text-gray">Сумма всех услуг со скидкой:</p>
                                <p class="text-black">${numberWithSpace(
                                  totalWithDiscount
                                )} сум</p>
                            </div>
                            <div class="modal__bill-info-item" style="margin-top: 24px">
                                <p class="text-gray">Итого:</p>
                                <p class="text-black" style="font-size: 24px;">${numberWithSpace(
                                  totalWithDiscount
                                )} сум</p>
                            </div>
                        </div>
                        <input type="hidden" id="invoice-id" value="${
                          data.id
                        }" />
                        <div class="modal__bill-buttons">
                            ${payButton}
                            ${cancelButton}
                            ${updateButton}
                            ${enumerationButton}
                            ${debtButton}
                            ${insuranceButton}
                        </div>
                    </div>
                    ${paymentsHtml}
                </div>
            </div>
        `;
  }
}

function invoicePayDetailsHTML(data) {
  let invoiceTotal = 0;
  let invoicePayTotal = 0;
  $.each(data.invoiceServices, function (key, invoiceService) {
    invoiceTotal +=
      parseInt(invoiceService.teeth_amount) *
      parseInt(invoiceService.amount) *
      parseInt(invoiceService.price_with_discount);
  });
  $.each(data.payments, function (key, payment) {
    invoicePayTotal += parseInt(payment.amount);
  });
  return `
        <div class="invoice__new">
            <div class="invoice__new-header">
                 <h3 class="invoice__new-header-title">Инвойс №${data.id}</h3>
                <img src="/img/scheduleNew/IconClose.svg" onclick="closeModal('.invoice-pay-wrap')" alt="">
            </div>
            <div class="invoice__new-body">
                 <div class="invoice__new-body-item" style="margin-bottom: 30px">
                      <p class="text-gray">Сумма инвойса:</p>
                      <p class="text-bold" style="font-size: 24px;">${numberWithSpace(
                        invoiceTotal
                      )} Сум</p>
                 </div>
                 <div class="invoice__new-body-item" style="margin-bottom: 10px">
                      <p class="text-gray">Оплачено:</p>
                      <p class="text-bold">${numberWithSpace(
                        invoicePayTotal
                      )} Сум</p>
                 </div> 
                 <div class="invoice__new-body-item" style="margin-bottom: 25px">
                      <p class="text-gray">Осталось:</p>
                      <p class="text-bold">${numberWithSpace(
                        invoiceTotal - invoicePayTotal
                      )} Сум</p>
                 </div>
                 <label class="invoice__new-body-item-input">
                    Сумма
                    <input type="number" name="amount" class="modal__input pay-invoice-sum" min="1000" max="9999999999" step="1000" placeholder="Введите сумму" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57) && this.value.length < 10)">
                    <input type="hidden" name="invoice_id" value="${data.id}"/>
                 </label>
            </div>
            <div class="invoice__new-footer">
            <button class="invoice__new-footer-btn btn-reset btn-green invoice-pay-btn">Оплатить</button>
            </div>
        </div>
    `;
}

function invoiceDetailsModalListeners() {
  $(".pay-invoice-btn").off("click");
  $(".pay-invoice-btn").on("click", function () {
    $(".invoice-pay-wrap").show();
    $(".invoice-details-modal").hide();

    let data = {
      [csrfParam]: csrfToken,
    };
    data.id = $(this).data("id");
    $(".invoice-pay-content").html(
      '<img src="/img/loading3.gif" class="loader-card" />'
    );
    $.ajax({
      url: "/invoice/get-invoice-payment-details",
      data: data,
      success: function (response) {
        let html = invoicePayDetailsHTML(response);
        $(".invoice-pay-content").html(html);
        invoiceDetailsModalListeners();
      },
      error: function (response) {
        hideLoader();
        showFlashMessage({
          type: "warning",
          message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
        });
      },
    });
  });

  $(".cancel-invoice-btn").off("click");
  $(".cancel-invoice-btn").on("click", function () {
    invoiceCancel();
  });

  $(".update-invoice-btn").off("click");
  $(".update-invoice-btn").on("click", function () {
    updateInvoice();
  });

  $(".insurance-invoice-btn").off("click");
  $(".insurance-invoice-btn").on("click", function () {
    insuranceInvoice();
  });

  $(".enumeration-invoice-btn").off("click");
  $(".enumeration-invoice-btn").on("click", function () {
    enumerationInvoice();
  });

  $(".debt-invoice-btn").off("click");
  $(".debt-invoice-btn").on("click", function () {
    debtInvoice();
  });

  $(".invoice-pay-btn").off("click");
  $(".invoice-pay-btn").on("click", function () {
    invoicePay();
  });

  $(".delete-invoice-btn").on("click", function () {
    if (confirm("Вы уверены, что хотите удалить счет?")) {
      let data = {
        [csrfParam]: csrfToken,
        id: $(this).data("id"),
      };
      $.ajax({
        url: "/invoice/delete?id=" + data.id,
        data: data,
        type: "POST",
        success: function (response) {
          if (response.status === "success") {
            location.reload();
          } else if (response.status === "fail") {
            showFlashMessage({ type: "warning", message: response.message });
          }
        },
        error: function (response) {
          showFlashMessage({
            type: "warning",
            message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
          });
        },
      });
    }
  });

  $("#closeModalAll").on("click", function () {
    $(".invoice-details-modal").hide();
  });
}

function invoiceWarningModalListeners() {
  $(".pay-for-loan").off("click");
  $(".pay-for-loan").on("click", function () {
    let id = $(this).data("id");
    $(`.invoice-details-btn[data-id="${id}"]`).data("pay_loan", "yes");
    invoicePay();
  });

  $(".close-invoice-modal").off("click");
  $(".close-invoice-modal").on("click", function () {
    $(".invoice-warning-modal").hide();
  });
}

function notEnoughMoneyHTML(data) {
  return `<div style="text-align: center;"><img src="/img/warning.svg" /></div>
        <p>На счету пациента недостаточно средств для оплаты данного счета (№${data.invoice_id})</p>
        <p>Недостающая сумма: ${data.need_amount} сум</p>`;
}

function collectExaminationFormData() {
  let data = {
    [csrfParam]: csrfToken,
  };
  data.patient_id = $(`#examination_patient_id`).val();
  data.complaints = $(`#complaints`).val();
  data.anamnesis = $(`#anamnesis`).val();
  data.weight = $(`#weight`).val();
  data.height = $(`#height`).val();
  data.head_circumference = $(`#head_circumference`).val();
  data.temperature = $(`#temperature`).val();
  data.inspection_data = $(`#inspection_data`).val();
  data.diagnosis = $(`#diagnosis`).val();
  data.recommendations = $(`#recommendations`).val();
  return data;
}

$("#save-examination-btn").on("click", function () {
  showLoader();
  $.ajax({
    url: "/patient/new-examination-create",
    data: collectExaminationFormData(),
    type: "POST",
    success: function (response) {
      hideLoader();
      if (response.status === "success") {
        location.reload();
      } else {
        showFlashMessage({ type: "warning", message: response.message });
      }
    },
    error: function (response) {
      hideLoader();
      showFlashMessage({
        type: "warning",
        message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
      });
    },
  });
});

$(".doctor-week-day-schedule").on("click", function () {
  let doctor_schedule_id = $("#schedule-id").val();
  let week_day = $(this).data("week-day");
  $.ajax({
    url: "/user/get-schedule-item",
    data: {
      doctor_schedule_id: doctor_schedule_id,
      week_day: week_day,
    },
    type: "get",
    success: function (response) {
      if (response.status === "success") {
        $(".new-doctor-appointment-form .doctor_schedule_item_form").html(
          response.content
        );
      }
    },
    error: function (response) {
      showFlashMessage({
        type: "warning",
        message: response.responseText.replace(/(<([^>]+)>)/gi, ""),
      });
    },
  });
});

$(".price-list-delete-btn").on("click", function () {
  $("#cardTwo .price-list-delete").attr(
    "href",
    "/price-list-item/delete?id=" + $(this).data("id")
  );
});

function checkChanges() {
  let data = { [csrfParam]: csrfToken };
  data.start = $("#date-start-filter").val();
  data.end = $("#date-end-filter").val();
  $.ajax({
    url: "/user/get-records",
    type: "POST",
    data: data,
    success: function (response) {
      if (response.length > 0) {
        $(".single-record-card").removeClass("sj-blink-yellow");
        $.each(response, function (key, record) {
          let cardRecord = $(`.single-record-card[data-id="${record.id}"]`);
          cardRecord.attr("data-state", record.state);
          if (record.state == "patient_came") {
            removeOtherClasses(cardRecord);
            cardRecord.addClass("orange");
          } else if (record.state == "admission_started") {
            removeOtherClasses(cardRecord);
            cardRecord.addClass("blue");
          } else if (record.state == "admission_finished") {
            removeOtherClasses(cardRecord);
            cardRecord.addClass("green");
          }
        });
      }
    },
  });
}

$("#lastname").autocomplete({
  source: function (term, response) {
    $.getJSON("/reception/ajax-search", { lastname: term }, function (data) {
      response(data);
    });
  },
  select: function (event, ui) {
    event.preventDefault();
    $("#lastname").val(ui.item.lastname);
    $("#name").val(ui.item.firstname);
    $("#patient_id").val(ui.item.id);
    $("#phonenumber").val(ui.item.phone);
    hidePatientFields();
  },
  response: function (even, ui) {
    if (ui.content.length === 0) {
      showPatientFields();
    }
  },
  appendTo: $(".modal--1"),
  minLength: 3,
});

$("#name").autocomplete({
  source: function (term, response) {
    $.getJSON(
      "/reception/ajax-search",
      { firstname: term, lastname: { term: $("#lastname").val() } },
      function (data) {
        response(data);
      }
    );
  },
  select: function (event, ui) {
    event.preventDefault();
    $("#lastname").val(ui.item.lastname);
    $("#name").val(ui.item.firstname);
    $("#patient_id").val(ui.item.id);
    $("#phonenumber").val(ui.item.phone);
    hidePatientFields();
  },
  response: function (even, ui) {
    if (ui.content.length === 0) {
      showPatientFields();
    }
  },

  appendTo: $(".modal--1"),
  minLength: 3,
});

function hidePatientFields() {
  $(".modal__doctor.new-patient-fields").hide();
}

function showPatientFields() {
  $(".modal__doctor.new-patient-fields").show();
}

$("#sender-patient-id, #recipient-patient-id").on("change", function () {
  let _this = $(this);
  let patientId = $(this).val();
  $.ajax({
    url: "/patient/get-balance",
    type: "GET",
    data: {
      patientId: patientId,
    },
    success: function (response) {
      if (response.status === "success") {
        _this
          .siblings(".body-select-item-price")
          .find(".body-select-item-text")
          .text("Баланс: " + response.balance + " сум");
      } else {
        _this
          .siblings(".body-select-item-price")
          .find(".body-select-item-text")
          .text("");
      }
    },
  });
});

$("#pricelistitem-price, #pricelistitem-consumable").on("keyup", function () {
  let price = $("#pricelistitem-price").val();
  let consumablePercent = $("#pricelistitem-consumable").val();
  $("#pricelistitem-utilities").val(
    (price * $("#pricelistitem-utilities").data("percent")) / 100
  );
  $("#pricelistitem-marketing").val(
    (price * $("#pricelistitem-marketing").data("percent")) / 100
  );
  $("#pricelistitem-amortization").val(
    (price * $("#pricelistitem-amortization").data("percent")) / 100
  );
  $("#pricelistitem-other_expenses").val(
    (price * $("#pricelistitem-other_expenses").data("percent")) / 100
  );
  $("#pricelistitem-consumable")
    .siblings(".input-group-prepend")
    .find(".input-group-text")
    .text((price * consumablePercent) / 100);
});

function startCheck() {
  setInterval(checkChanges, 5000);
}

function collectDoctorPercentData() {
  let data = {
    [csrfParam]: csrfToken,
    user_id: $('input[name="percent-user-id"]').val(),
  };
  data.items = [];
  $(".percent-category").each(function () {
    let thisItem = $(this);
    data.items.push({
      cat_id: thisItem.data("cat-id"),
      percent: thisItem.find('input[name="percent"]').val(),
    });
  });

  return data;
}

function removeOtherClasses(element) {
  let classes = ["admission_finished", "sj-blink-green", "sj-blink-yellow"];

  $.each(classes, function (key, val) {
    element.removeClass(val);
  });
}

function currentUserCan(permission) {
  return permissions.filter((elem) => {
    return elem === permission;
  }).length;
}

function removeServiceListener() {
  $(".remove-service-item").off("click");
  $(".remove-service-item").on("click", function () {
    $(this).closest(".right_services_body_row").remove();
    updateInvoiceTotal();
  });
}

function updateInvoiceTotal() {
  const discount = +$("#discount-wrap").data("raw-percent");
  const $rows = $(".right_services_body .right_services_body_row");
  const [priceWithoutDiscount, priceWithDiscount] = $rows.get().reduce(
    ([priceWithoutDiscount, priceWithDiscount], row) => {
      const discountApply = $(row).data("discount-apply");
      const price = +$(row)
        .find(".right_services_body_item:nth-child(4) span")
        .data("raw-price");
      priceWithoutDiscount += price;
      priceWithDiscount += discountApply
        ? (price * (100 - discount)) / 100
        : price;
      return [priceWithoutDiscount, priceWithDiscount];
    },
    [0, 0]
  );

  $("#total_without_discount").html(
    numberWithSpace(priceWithoutDiscount) + " сум"
  );
  $("#total_without_discount").data("raw-price", priceWithoutDiscount);
  $("#total_with_discount").html(numberWithSpace(priceWithDiscount) + " сум");
  $("#total_with_discount").data("raw-price", priceWithDiscount);
}

function onSettings() {
  const settings = document.getElementById("settings");
  const infoBtn = document.getElementById("infoBtn");

  if (settings.style.display === "block") {
    settings.style.display = "none";
    infoBtn.classList.remove("active");
  } else {
    settings.style.display = "block";
    infoBtn.classList.add("active");
  }

  // click outside close settings
  document.addEventListener("click", function (e) {
    if (e.target.closest(".user-info")) return;
    settings.style.display = "none";
    infoBtn.classList.remove("active");
  });
}

const modalBox = document.getElementById("modalWrapper"),
  cardOne = document.getElementById("cardOne"),
  cardTwo = document.getElementById("cardTwo"),
  cardThree = document.getElementById("cardThree"),
  cardFour = document.getElementById("cardFour"),
  modalcloseBtn = document.querySelectorAll("#modal_close");

function handleModal(e) {
  modalBox.style.display = "flex";

  modalcloseBtn.forEach((btn) => {
    btn.onclick = function () {
      modalBox.style.display = "none";
    };
  });

  modalBox.onclick = function (event) {
    if (event.target == modalBox) {
      modalBox.style.display = "none";
    }
  };

  if (e === 1) {
    cardOne.style.display = "block";
    if (cardTwo) {
      cardTwo.style.display = "none";
    }
    if (cardThree) {
      cardThree.style.display = "none";
    }
    if (cardFour) {
      cardFour.style.display = "none";
    }
  } else if (e === 2) {
    if (cardOne) {
        cardOne.style.display = "none";
    }
    if (cardTwo) {
        cardTwo.style.display = "block";
    }
    if (cardThree) {
        cardThree.style.display = "none";
    }
    if (cardFour) {
        cardFour.style.display = "none";
    }
  } else if (e === 3) {
    if (cardOne) {
        cardOne.style.display = "none";
    }
    if (cardTwo) {
      cardTwo.style.display = "none";
    }
    if (cardThree) {
      cardThree.style.display = "block";
    }
    if (cardFour) {
      cardFour.style.display = "none";
    }
  } else if (e === 4) {
    if (cardOne) {
        cardOne.style.display = "none";
    }
    if (cardTwo) {
      cardTwo.style.display = "none";
    }
    if (cardThree) {
      cardThree.style.display = "none";
    }
    if (cardFour) {
      cardFour.style.display = "block";
    }
  } else {
    cardOne.style.display = "none";
    cardTwo.style.display = "none";
    cardThree.style.display = "none";
    cardFour.style.display = "none";
  }
}

// НАЗНАЧИТЬ СКИДКУ ПАЦИЕНТУ

const saleBox = document.querySelectorAll(".sale_box");
saleBox.forEach((box) => {
  box.addEventListener("click", () => {
    saleBox.forEach((box) => {
      box.classList.remove("active");
    });
    box.classList.add("active");
  });
});

const cancelReason = document.querySelectorAll(".cancel-reason");
cancelReason.forEach((box) => {
  box.addEventListener("click", () => {
    cancelReason.forEach((box) => {
      box.classList.remove("active");
    });
    box.classList.add("active");
  });
});

function handleClick(e) {
  const dollarInput = document.getElementById("dollarInput");

  if (e.checked) {
    dollarInput.style.display = "flex";
  } else {
    dollarInput.style.display = "none";
  }
}

function closeModal(className) {
  $(className).hide();
}

// let sumField = $('.pay-invoice-sum');
// $('body').on('keyup', function(event) {
//     return /[0-9]/i.test(event.key);
// });