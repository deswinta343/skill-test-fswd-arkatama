$(function () {
    "use strict";

    // Feather Icon Init Js
    // feather.replace();

    // $(".preloader").fadeOut();

    // =================================
    // Tooltip
    // =================================
    var tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // =================================
    // Popover
    // =================================
    var popoverTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="popover"]')
    );
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // increment & decrement
    $(".minus,.add").on("click", function () {
        var $qty = $(this).closest("div").find(".qty"),
            currentVal = parseInt($qty.val()),
            isAdd = $(this).hasClass("add");
        !isNaN(currentVal) &&
            $qty.val(
                isAdd
                    ? ++currentVal
                    : currentVal > 0
                        ? --currentVal
                        : currentVal
            );
    });

    // ==============================================================
    // Collapsable cards
    // ==============================================================
    $('a[data-action="collapse"]').on("click", function (e) {
        e.preventDefault();
        $(this)
            .closest(".card")
            .find('[data-action="collapse"] i')
            .toggleClass("ti-minus ti-plus");
        $(this).closest(".card").children(".card-body").collapse("toggle");
    });
    // Toggle fullscreen
    $('a[data-action="expand"]').on("click", function (e) {
        e.preventDefault();
        $(this)
            .closest(".card")
            .find('[data-action="expand"] i')
            .toggleClass("ti-arrows-maximize ti-arrows-maximize");
        $(this).closest(".card").toggleClass("card-fullscreen");
    });
    // Close Card
    $('a[data-action="close"]').on("click", function () {
        $(this).closest(".card").removeClass().slideUp("fast");
    });

    // fixed header
    $(window).scroll(function () {
        if ($(window).scrollTop() >= 60) {
            $(".app-header").addClass("fixed-header");
            $(".left-sidebar").addClass("position-fixed");
            $(".left-sidebar").addClass("theTop");
            $(".left-sidebar").removeClass("theSidebar-height");
            $(".left-sidebar").addClass("theSidebar-height-scroll");
        } else {
            $(".app-header").removeClass("fixed-header");
            $(".left-sidebar").removeClass("position-fixed");
            $(".left-sidebar").removeClass("theTop");
            $(".left-sidebar").addClass("theSidebar-height");
            $(".left-sidebar").removeClass("theSidebar-height-scroll");
        }
    });

    // Checkout
    $(function () {
        $(".billing-address").click(function () {
            $(".billing-address-content").hide();
        });
        $(".billing-address").click(function () {
            $(".payment-method-list").show();
        });
    });
});

/*change layout boxed/full */
$(".full-width").click(function () {
    $(".container-fluid").addClass("mw-100");
    $(".full-width i").addClass("text-primary");
    $(".boxed-width i").removeClass("text-primary");
});
$(".boxed-width").click(function () {
    $(".container-fluid").removeClass("mw-100");
    $(".full-width i").removeClass("text-primary");
    $(".boxed-width i").addClass("text-primary");
});

/*Dark/Light theme*/
$(".light-logo").hide();
$(".dark-theme").click(function () {
    $("nav.navbar-light").addClass("navbar-dark");
    $(".dark-theme i").addClass("text-primary");
    $(".light-theme i").removeClass("text-primary");
    $(".light-logo").show();
    $(".dark-logo").hide();
});
$(".light-theme").click(function () {
    $("nav.navbar-light").removeClass("navbar-dark");
    $(".dark-theme i").removeClass("text-primary");
    $(".light-theme i").addClass("text-primary");
    $(".light-logo").hide();
    $(".dark-logo").show();
});

/*Card border/shadow*/
$(".cardborder").click(function () {
    $("body").addClass("cardwithborder");
    $(".cardshadow i").addClass("text-dark");
    $(".cardborder i").addClass("text-primary");
});
$(".cardshadow").click(function () {
    $("body").removeClass("cardwithborder");
    $(".cardborder i").removeClass("text-primary");
    $(".cardshadow i").removeClass("text-dark");
});

$(".change-colors li a").click(function () {
    $(".change-colors li a").removeClass("active-theme");
    $(this).addClass("active-theme");
});

/*Theme color change*/
function toggleTheme(value) {
    $(".preloader").show();
    var sheets = document.getElementById("themeColors");
    sheets.href = value;
    $(".preloader").fadeOut();
}
$(".preloader").fadeOut();
$(document.body).removeAttr("style");

/*
 * getAllFields(selector, required)
 * @param selector string (the form id)
 * @param required boolean (selector for field that contain required attribute only default false)
 * to get all input , select , textarea in a form with selector and required
 * @return array
 */

const getAllFields = (selector, required = true) => {
    const fields = $(selector)
        .find(
            `input:not([type="submit"])${required ? "[required]" : ""
            }:not(.ck-hidden), textarea${required ? "[required]" : ""}, select${required ? "[required]" : ""
            }`
        )
        .not('input[name="_token"]')
        .not("input[name='']")
        .not("input[name='method']")
        .not('[aria-need-validation="false"]');
    return Array.from(fields);
};

/*
 * showValidationErrors(errors, formSelector)
 * @param errors object (the xhr response that contains errors of the fieldss)
 * @param formSelector string (the form id)
 * @param exept object<fieldname, Object<key, val>> (the field that custom for showing the error)
 * avaiable key : selector , class, style
 * to display all errors in a form and remove the error if the field is valid
 * @return void
 */

const showValidationErrors = (errors, formSelector, excepts = {}) => {
    const form = $(formSelector);
    const fieldErrorKeys = Object.keys(errors);
    const exceptFields = Object.keys(excepts);

    getAllFields(formSelector, false).forEach((field) => {
        const fieldTarget = form.find(
            exceptFields.includes(field.name)
                ? excepts[field.name].selector
                : `${field.localName}[name="${field.name}"]`
        );
        if (!fieldErrorKeys.includes(field.name)) {
            fieldTarget
                .removeClass("is-invalid")
                .addClass("is-valid")
                .next("small.invalid-feedback")
                .html("");
        } else {
            fieldTarget
                .removeClass("is-valid")
                .addClass("is-invalid")
                .nextAll("small.invalid-feedback")
                .html(errors[field.name][0]);
        }
    });
    if (exceptFields.length > 0) {
        exceptFields.forEach((ex) => {
            if (!fieldErrorKeys.includes(ex)) {
                if (excepts[ex].validClass) {
                    $(excepts[ex].selector)
                        .removeClass("is-invalid")
                        .addClass(excepts[ex].validClass);
                } else {
                    $(excepts[ex].selector)
                        .removeClass("is-invalid")
                        .css(excepts[ex].validStyle);
                }
                $(excepts[ex].selector).nextAll(".invalid-feedback").html("");
            } else {
                if (excepts[ex].invalidClass) {
                    $(excepts[ex].selector)
                        .addClass("is-invalid")
                        .addClass(excepts[ex].invalidClass);
                } else {
                    $(excepts[ex].selector)
                        .addClass("is-invalid")
                        .css(excepts[ex].invalidStyle);
                }
                if (
                    $(excepts[ex].selector).nextAll(".invalid-feedback")
                        .length > 0
                ) {
                    $(excepts[ex].selector)
                        .nextAll(".invalid-feedback")
                        .html(errors[ex][0]);
                } else {
                    const invalidFeedback = document.createElement("small");
                    invalidFeedback.classList.add("invalid-feedback");
                    invalidFeedback.innerHTML = errors[ex][0];
                    $(excepts[ex].selector).after(invalidFeedback);
                }
            }
        });
    }
};

/*
 * resetform
 * @param selector (the form selector)
 * to remove all errors on the form and reset the form
 * @return void
 */
let defaultFormAction = "";
const resetForm = (selector, excepts = {}) => {
    const form = $(selector);
    form[0].reset();
    const exceptFields = Object.keys(excepts);
    getAllFields(selector, false).forEach((field) => {
        const fieldTarget = form.find(
            exceptFields.includes(field.name)
                ? excepts[field.name].selector
                : `${field.localName}[name="${field.name}"]`
        );

        if (!fieldTarget) return;

        if (exceptFields.includes(field.name)) {
            excepts[field.name].styleValid
                ? fieldTarget
                    .removeAttr("style")
                    .nextAll(".invalid-feedback")
                    .html("")
                : fieldTarget
                    .removeClass(
                        excepts[field.name].class ??
                        "form-control is-invalid is-valid"
                    )
                    .nextAll(".invalid-feedback")
                    .html("");
        } else {
            fieldTarget
                .removeClass("is-invalid is-valid")
                .next(".invalid-feedback")
                .html("");
        }
    });

    const dropzoneInstances = form.find('[data-plugin="dropzone"]');

    if (dropzoneInstances.length > 0) {
        dropzoneInstances.each(function () {
            const id = $(this).attr("id");
            const dzInstance = Dropzone.forElement(`#${id}`);
            if ($(this).hasClass("dz-hasvalue")) {
                $(this).removeClass("dz-hasvalue");
                $(this).removeClass("dz-started");
                $(this).find(".dz-preview").remove();
            }
            dzInstance.removeAllFiles();
            $(this).removeAttr("style");
            $(this).nextAll(".invalid-feedback").html("");
            $(this).removeClass(["is-invalid", "is-valid"]);
        });
    }
    // handle select2 validation style
    form.find('[data-plugin="select-2"]').each(function () {
        $(this).val(null).trigger("change");
        $(this)
            .next(".select2-container")
            .find(".select2-selection")
            .removeAttr("style")
            .removeClass(["is-invalid", "is-valid"]);
        $(this).nextAll(".invalid-feedback").html("");
    });
    // handle summernote validation style
    form.find('[data-plugin="summernote"]').each(function () {
        $(this).summernote("reset");
        $(this)
            .next(".note-editor")
            .removeAttr("style")
            .removeClass(["is-invalid", "is-valid"]);
        $(this).nextAll(".invalid-feedback").html("");
    });

    form.find(".checkbox-group").each(function () {
        $(this).removeAttr("style").removeClass(["is-invalid"]);
        $(this).nextAll(".invalid-feedback").html("");
        $(this).find('input[type="checkbox"]').prop("checked", false);
    });
};

function debounce(func, delay) {
    let debounceTimer;
    return function () {
        const context = this;
        const args = arguments;
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => func.apply(context, args), delay);
    };
}

const showPageOverlay = () => {
    $(".transparent-preloader").show();
    $(document.body).css("overflow-y", "hidden").css("padding-right", "17px");
};

const hidePageOverlay = () => {
    $(".transparent-preloader").fadeOut();
    $(document.body).removeAttr("style");
};

function fillForm(selector, datas, values = {}) {
    const fieldKeys = [...Object.keys(datas), ...Object.keys(values)];
    const fields = getAllFields(selector, false).filter((field) =>
        fieldKeys.includes(field.name)
    );
    fields.forEach((field) => {
        if (field.localName == "input" && field.type == "radio") {
            $(field).prop(
                "checked",
                (values && values[field.name]
                    ? values[field.name].value
                    : datas[field.name]) == field.value
            );
        } else if (field.localName == "select") {
            if (values && Object.keys(values).includes(field.name)) {

                if (
                    $(field).data("plugin") == "select-2" &&
                    $(field).attr("data-source")
                ) {
                    $(field).append(
                        new Option(
                            values[field.name].label,
                            values[field.name].value,
                            true,
                            true
                        )
                    );
                } else {

                    $(field)
                        .find("option")
                        .each(function () {
                            if ($(this).val() == values[field.name].value) {
                                $(this).prop("selected", true);
                            } else {
                                $(this).prop("selected", false);
                            }
                        });
                }
            } else {

                $(field)
                    .find("option")
                    .each(function () {
                        if ($(field).data("plugin") == "select-2") {
                            if ($(this).val() == datas[field.name]) {
                                $(this)
                                    .prop("selected", true)
                                    .trigger("change");
                            } else {
                                $(this)
                                    .prop("selected", false)
                                    .trigger("change");
                            }
                        } else {

                            if ($(this).val() == datas[field.name] || $(this).val() == datas[field?.name]?.value) {
                                $(this)
                                    .prop("selected", true)
                                    .trigger("change");
                            } else {
                                $(this)
                                    .prop("selected", false)
                                    .trigger("change");
                            }
                        }
                    });
            }
        } else {
            if ($(field).data("plugin") == "summernote") {
                $(field).summernote(
                    "code",
                    values && values[field.name]
                        ? values[field.name]
                        : datas[field.name]
                );
                return;
            }
            $(field).val(
                values && values[field.name]
                    ? values[field.name]
                    : datas[field.name]
            );
        }
    });

    const dropzoneInstances = $(selector).find('[data-plugin="dropzone"]');
    if (dropzoneInstances.length > 0) {
        dropzoneInstances.each(function () {
            const id = $(this).attr("id");
            const files = datas[$(this).data("name")];
            if (files) {
                initDropzone(`#${id}`, files);
            }
        });
    }
}

function handleAjaxError(jqXhr, callback = () => { }) {
    if (jqXhr.status === 0 && jqXhr.statusText === "timeout") {
        toastr.error("Request Timeout", "Error");
    } else if (jqXhr.status === 500) {
        toastr.error("Gagal memproses, server Error, ", "Error");
    } else if (
        jqXhr.status >= 400 &&
        jqXhr.status < 500 &&
        jqXhr.status != 422
    ) {
        toastr.error(jqXhr.responseJSON.message.body, "Error");
    } else {
        callback();
    }
}

function showButtonLoader(button, label = "Sedang diproses...") {
    const oldText = button.html();
    const width = button.width();
    button.html(
        `</span><span class="me-3">${label}</span> <span class="spinner-border spinner-border-sm " role="status" aria-hidden="true">`
    );
    // button.width(width);
    button.prop("disabled", true);
    return function () {
        button.html(oldText);
        button.width("auto");
        button.prop("disabled", false);
    };
}

function getFormValue(form) {
    const arrayValues = form.serializeArray();
    return arrayValues.reduce((p, c, i) => {
        p[c.name] = c.value;
        return p;
    }, {});
}

function getSelect2Value(selector) {
    return selector.select2("data").map(function (v) {
        return v.id;
    });
}

function initDropzone(selector, values = []) {
    const supportedPreview = ["jpg", "jpeg", "png", "webp", "svg", "pdf"];
    const fieldName = $(selector).data("name");
    const previewTemplate =
        Dropzone.forElement(selector).options.previewTemplate;
    const createElement = (str) => {
        const el = document.createElement("div");
        el.innerHTML = str;
        return el.firstChild;
    };

    if (typeof values == "array") {
        values.forEach((value) => {
            const preview = createElement(previewTemplate);
            $(preview).find("[data-dz-name]").text(value.basename);
            if (supportedPreview.includes(value.extension)) {
                $(preview).find("[data-dz-name]").attr("href", value.path);
            }
            $(preview).find("[data-dz-size]").text(value.size);
            $(selector).append(preview);
        });
    } else {
        const preview = createElement(previewTemplate);
        const ext = values.filename.split(".").pop();
        $(preview).find("[data-dz-name]").text(values.filename);

        // check if preview element has dz-preview-with-img class
        if ($(preview).hasClass("dz-preview-with-img")) {
            // check if image
            const imgExt = ["jpg", "jpeg", "png", "webp", "svg"];
            if (imgExt.includes(ext)) {
                $(preview).find("[data-dz-img]").attr("src", values.preview);
            }
        }

        if (supportedPreview.includes(ext)) {
            $(preview)
                .find("[data-dz-name]")
                .attr("href", values.preview)
                .addClass("has-preview")
                .attr("title", "click to preview");
        }
        $(preview)
            .find("[data-dz-size]")
            .html(`<strong>${values.size}</strong>`);
        $(preview).append(
            createElement(
                `<input type="hidden" name="${fieldName}" value="${values.path}"/>`
            )
        );
        $(selector).append(preview);
    }
    $(selector).addClass("dz-started");
    $(selector).addClass("dz-hasvalue");
    $(selector)
        .find("[data-dz-remove]")
        .on("click", function (ev) {
            ev.preventDefault();
            $(this).parents(".dz-preview").remove();
            if ($(selector).find(".dz-preview").length == 0) {
                $(selector).removeClass("dz-started");
                $(selector).removeClass("dz-hasvalue");
            }
        });
}

function previewFileFromURL(url_file, previewContainerId) {
    let output;
    // check if element is a string or an object
    if (typeof previewContainerId === "string") {
        output = $("#" + previewContainerId);
    } else {
        output = previewContainerId;
    }
    if (url_file === "") {
        output.html("<p>Tidak ada file yang diupload.</p>");
    } else {
        // determine type of file
        const extension = url_file.split(".").pop().toLowerCase();
        if (extension === "pdf") {
            output.html(
                '<embed src="' +
                url_file +
                '" type="application/pdf" style="width: 100%; height: 500px;"></embed>'
            );
        } else if (extension === "docx" || extension === "doc") {
            output.html(
                '<img src="assets/media/icons/msword.png" class="img-fluid mx-auto d-block" style="max-width: 100%; max-height: 300px;" />'
            );
        } else if (extension === "xlsx" || extension === "xls") {
            output.html(
                '<img src="assets/media/icons/excel.png" class="img-fluid mx-auto d-block" style="max-width: 100%; max-height: 300px;" />'
            );
        } else if (extension === "pptx" || extension === "ppt") {
            output.html(
                '<img src="assets/media/icons/powerpoint.png" class="img-fluid mx-auto d-block" style="max-width: 100%; max-height: 300px;" />'
            );
        } else if (
            extension === "jpg" ||
            extension === "jpeg" ||
            extension === "png" ||
            extension === "gif"
        ) {
            output.html(
                '<img src="' +
                url_file +
                '" class="img-fluid mx-auto d-block" style="max-width: 100%; max-height: 300px;" />'
            );
        } else {
            output.html("<p>Tidak ada pratinjau yang tersedia.</p>");
        }
    }
}

// custom form handling

$(function () {
    let closeConfirmed = false;

    // plugin ininitialization
    if (Dropzone) {
        // start dropzone customizations
        $("[data-plugin='dropzone']").each(function () {
            const id = $(this).attr("id");
            const {
                url = "tes",
                accept,
                maxSize,
                maxFiles,
                multiple,
                folder,
                name,
            } = $(this).data();

            const el = $(this);
            const previewTemplate = el.find(".dz-preview").get(0).outerHTML;
            el.find(".dz-preview").get(0).remove();

            const existing = el.find(".dz-preview");
            let files = [];
            existing.map(function () {
                files.push({
                    filename: $(this).find("[data-dz-name]").text(),
                    path: $(this).find("[data-dz-name]").attr("href"),
                    preview: $(this).find("[data-dz-name]").attr("href"),
                    size: $(this).find("[data-dz-size]").text(),
                    extension: $(this)
                        .find("[data-dz-name]")
                        .text()
                        .split(".")
                        .pop(),
                });
            });
            el.find(".dz-preview").remove();

            new Dropzone(`#${id}`, {
                url: "/admin/media/upload",
                paramName: "file",
                maxFilesize: maxFiles ?? 2,
                createImageThumnails: false,
                clickable: `#${id} #btn_select `,
                autoQueue: false,
                previewTemplate,
                init: function () {
                    this.on("error", function (file, message) {
                        this.removeFile(file);
                        toastr.error(message, "Error");
                    });
                    this.on("sending", function (file, xhr, formData) {
                        formData.append("_token", "");
                        formData.append("folder", folder ?? "media");
                        formData.append("unique", false);
                    });
                    this.on("addedfile", function (file) {
                        const initname = file.name;
                        let filename = !(initname.slice(0, -4).length > 20)
                            ? initname
                            : initname.slice(0, 20) +
                            "..." +
                            initname.slice(-4);
                        const filetypes = file.type.split("/");

                        const fileReader = new FileReader();
                        fileReader.onload = function () {
                            if (
                                filetypes[0] == "image" ||
                                filetypes[1] == "pdf"
                            ) {
                                $(file.previewElement)
                                    .find("[data-dz-name]")
                                    .attr("href", fileReader.result);
                                $(file.previewElement)
                                    .find("[data-dz-name]")
                                    .addClass("has-preview");

                                // check if preview element has dz-preview-with-img class
                                if (
                                    $(file.previewElement).hasClass(
                                        "dz-preview-with-img"
                                    )
                                ) {
                                    // check if image
                                    $(file.previewElement)
                                        .find("[data-dz-img]")
                                        .attr("src", fileReader.result);
                                }
                            }
                        };
                        switch (file.type.split("/")[0]) {
                            case "image":
                                // $(file.previewElement).find('[data-dz-type]').addClass('img');
                                break;
                            case "video":
                                // $(file.previewElement).find('[data-dz-type]').addClass('video');
                                break;
                            case "audio":
                                // $(file.previewElement).find('[data-dz-type]').addClass('audio');
                                break;
                            default:
                            // $(file.previewElement).find('[data-dz-type]').addClass('file');
                        }
                        $(file.previewElement)
                            .find("[data-dz-name]")
                            .text(filename);

                        fileReader.readAsDataURL(file);
                    });

                    this.on("processing", (file) => {
                        $(file.previewElement)
                            .find("[dz-remove]")
                            .html("cancel");
                    });
                },
            });

            if (files.length > 0) {
                files.forEach((file) => {
                    initDropzone(`#${id}`, file);
                });
            }
        });
    }

    // select2 plugin
    $("[data-plugin='select-2']")
        .not("[custom]")
        .each(function () {
            const placeholder = $(this).data("placeholder");
            const source = $(this).data("source");
            const parent = $(this).data("parent");
            const multiple = Boolean($(this).attr("multiple"));

            const config = {
                placeholder: placeholder,
                dropdownParent: parent
                    ? $(parent).find(".modal-body")
                    : $(this).parents("form").length > 0 ? $(this).parents("form") : $(document.body),
                multiple,
            };

            if (source) {
                config.ajax = {
                    url: source,
                    dataType: "json",
                    processResults: function (data) {
                        return {
                            results: data.data,
                        };
                    },
                };
            }

            $(this).select2(config);
        });

    // base ajax handling

    $("form")
        .not("[custom-action]")
        .on("submit", function (ev) {
            ev.preventDefault();
            const currentForm = $(this);
            const tableId = currentForm.data("table-id");
            const hideButtonLoader = showButtonLoader(
                currentForm.find(
                    "button[type='submit']",
                    currentForm
                        .find("input[type='submit']")
                        .data("loading-text") ?? "Sedang diproses..."
                )
            );
            const exceptsRules = {};
            const formData = new FormData(this);
            const dropzoneInstances = currentForm.find(
                '[data-plugin="dropzone"]'
            );
            if (dropzoneInstances.length > 0) {
                dropzoneInstances.each(function () {
                    exceptsRules[$(this).data("name")] = {
                        selector: `#${$(this).attr("id")}`,
                        invalidStyle: {
                            borderColor: "var(--bs-form-invalid-border-color) ",
                        },
                        validStyle: {
                            borderColor: "var(--bs-form-valid-border-color) ",
                        },
                    };
                    if (!$(this).hasClass("dz-hasvalue")) {
                        const name = $(this).data("name");
                        const id = $(this).attr("id");
                        const dzInstance = Dropzone.forElement(`#${id}`);
                        const files = dzInstance?.files;
                        if (files.length > 1) {
                            files.forEach((file) => {
                                formData.append(`${name}[]`, file);
                            });
                        } else if (files.length > 0) {
                            formData.append(name, files[0]);
                        }
                    }
                });
            }

            const select2Instances = currentForm.find(
                '[data-plugin="select-2"]'
            );
            if (select2Instances.length > 0) {
                select2Instances.each(function () {
                    exceptsRules[$(this).attr("name")] = {
                        selector: $(this)
                            .next(".select2-container")
                            .find(".select2-selection")
                            .get(0),
                        invalidStyle: {
                            border: "1px solid var(--bs-form-invalid-border-color)",
                        },
                        validStyle: {
                            border: "1px solid var(--bs-form-valid-border-color)",
                        },
                    };
                });
            }
            // handle summernote validation style
            currentForm.find('[data-plugin="summernote"]').each(function () {
                const name = $(this).attr("name");
                exceptsRules[name] = {
                    selector: $(this).next(".note-editor").get(0),
                    invalidStyle: {
                        border: "1px solid var(--bs-form-invalid-border-color)",
                    },
                    validStyle: {
                        border: "1px solid var(--bs-form-valid-border-color)",
                    },
                };
            });

            currentForm.find(".checkbox-group").each(function () {
                const name = $(this).attr("name");
                exceptsRules[name] = {
                    selector: $(this),
                    invalidStyle: {
                        border: "1px solid var(--bs-form-invalid-border-color)",
                    },
                    validStyle: {
                        border: "1px solid var(--bs-form-valid-border-color)",
                    },
                };
            });

            $.ajax({
                url: currentForm.attr("action"),
                method: currentForm.attr("method"),
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    Swal.fire({
                        text: data.message?.body,
                        icon: "success",
                        title: data.message?.title,
                        buttonsStyling: false,
                        confirmButtonText: "Ya",
                        cancelButtonText: "Tidak",
                        customClass: {
                            confirmButton: "btn btn-primary",
                        },
                    }).then((result) => {
                        const parentModal = currentForm.parents(".modal");
                        $(document).trigger(
                            `form-submitted:${currentForm.attr("id")}`
                        );
                        if (tableId)
                            window.LaravelDataTables[tableId].ajax.reload();
                        if (
                            currentForm.parents(".modal[update-modal]").length >
                            0
                        ) {
                            closeConfirmed = true;
                        }
                        if (!parentModal) return;
                        parentModal.modal("hide");
                    });
                },
                error: function (jqXhr) {
                    handleAjaxError(jqXhr, () => {
                        showValidationErrors(
                            jqXhr.responseJSON.errors,
                            currentForm,
                            exceptsRules
                        );

                        // handle scroll when error
                        const firstError = currentForm
                            .find(".is-invalid")
                            .first();

                        if (firstError.length > 0) {
                            console.log(firstError);
                            if (currentForm.parents(".modal").length > 0) {
                                currentForm.parents(".modal").animate(
                                    {
                                        scrollTop:
                                            firstError.offset().top - 120,
                                    },
                                    100
                                );
                            } else {
                                $("html, body").animate(
                                    {
                                        scrollTop:
                                            firstError.offset().top - 120,
                                    },
                                    100
                                );
                            }
                        }

                        $(document).trigger(
                            `form-error:${currentForm.attr("id")}`,
                            jqXhr.responseJSON
                        );
                    });
                },
                complete: function () {
                    hideButtonLoader();
                    $(document).trigger(
                        `form-completed:${currentForm.attr("id")}`
                    );
                },
            });
        });

    $('input:not([type="submit"]), textarea, select').on("input", function () {
        if ($(this).hasClass("is-invalid")) {
            $(this)
                .removeClass("is-invalid")
                .next(".invalid-feedback")
                .html("");
        } else if ($(this).hasClass("is-valid")) {
            $(this).removeClass("is-valid");
        }
    });

    $(document).on("click", '[data-action="preview"]', function () {
        const url = $(this).data("url");
        const modal = $("#" + $(this).data("modal-id"));
        const title = $(this).data("title");
        console.log(title)
        modal.find(".modal-title").text(title);
        modal.find(".preview-container-modal").html("");
        previewFileFromURL(url, modal.find(".preview-container-modal"));
        modal.modal("show");
    });

    $(document).on("click", "button[action-need-confirm]", function (e) {
        let token = $('meta[name="csrf-token"]').attr("content");
        const currentButton = $(this);
        const actionType = currentButton.data("action-type") ?? "danger";
        const actionText = currentButton.data("action-text");
        let actionUrl = currentButton.data("action-url");
        const actionMethod = currentButton.data("action-method") ?? "DELETE";
        const buttonId = $(this).attr("id");

        Swal.fire({
            text: actionText,
            icon: actionType == "danger" ? "warning" : "question",
            showCancelButton: true,
            customClass: {
                confirmButton:
                    "btn" +
                    (actionType == "danger" ? " btn-danger" : " btn-primary"),
                cancelButton: "btn btn-secondary",
            },
        }).then((result) => {
            if (result.isConfirmed) {
                if (actionUrl) {
                    $.ajax({
                        url: actionUrl,
                        method: actionMethod,
                        data: {
                            _token: token,
                        },
                        beforeSend: showPageOverlay,
                        success: function (data) {
                            Swal.fire({
                                text: data.message.body,
                                icon: "success",
                                title: data.message.title,
                                customClass: {
                                    confirmButton: "btn btn-primary",
                                },
                            }).then((result) => {
                                $(document).trigger(
                                    "action-confirmed:" + buttonId
                                );
                            });
                        },
                        error: function (jqXhr) {
                            handleAjaxError(jqXhr, () => {
                                Swal.fire({
                                    text: jqXhr.responseJSON.message.body,
                                    icon: "error",
                                    title: jqXhr.responseJSON.message.title,
                                    customClass: {
                                        confirmButton: "btn btn-primary",
                                    },
                                });
                            });
                        },
                        complete: hidePageOverlay,
                    });
                } else {
                    $(document).trigger("action-confirmed:" + buttonId);
                }
            }
        });
    });

    $("div[update-form].modal").on("hide.bs.modal", function (ev) {
        if (!closeConfirmed) {
            ev.preventDefault();
            Swal.fire({
                html: "<h4>Apakah anda yakin untuk membatalkan?</h4>\n Segala perubahan yang dilakukan, akan hilang.",
                icon: "warning",
                buttonsStyling: false,
                showCancelButton: true,
                confirmButtonText: "Ya",
                cancelButtonText: "Tidak",
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-secondary",
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    closeConfirmed = true;
                    $(this).modal("hide");
                }
            });
        } else {
            closeConfirmed = false;
        }
    });

    $("form a[cancel-btn]").click(function (ev) {
        ev.preventDefault();
        const link = $(this).attr("href");
        Swal.fire({
            html: "<h4>Apakah anda yakin untuk membatalkan?</h4>\n  Segala perubahan yang dilakukan, akan hilang.",
            icon: "warning",
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: "Ya",
            cancelButtonText: "Tidak",
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: "btn btn-secondary",
            },
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = link;
            }
        });
    });

    $(".modal[aria-reset-on-close='true']").each(function () {
        const modal = $(this);
        modal.on("hidden.bs.modal", function () {
            if (modal.find("form").length > 0) {
                resetForm(modal.find("form"));
            }
            $(document.body).removeAttr("style");
        });
    });
});

function validateInputChar(input) {
    input.value = input.value.replace(/[^a-zA-Z\s]/g, "");
}

function validateInputNameJenjang(input) {
    input.value = input.value.replace(/[^a-zA-Z1-4\s]/g, "");
}

function limitToTwoDigits(input) {
    let value = input.value;
    input.value = input.value.replace(/[0-9]/g, "");
    let sanitizedValue = value.replace(/\D/g, "");

    if (sanitizedValue.length > 2) {
        input.value = sanitizedValue.slice(0, 2);
    } else {
        input.value = sanitizedValue;
    }
}

function validateInputKode(input) {
    input.value = input.value.replace(/[^\d.]/g, "");
    console.log(input.value);
}

function validateInputKodePayment(input) {
    input.value = input.value.replace(/[^a-zA-Z0-9_]/g, "");
}
