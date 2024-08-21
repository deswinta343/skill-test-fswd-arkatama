let checked = [];
let unchecked = [];
let changed = {
    checked: { id: [], name: [] },
    unchecked: { id: [], name: [] }
};

$(document).on('draw.dt', '#matakuliah-setting-create-table', function() {
    setRefCheckbox();
});

$(document).on('draw.dt', '#matakuliah-setting-table', function() {
    setRefCheckbox();
});

$(document).on('change', 'input[data-setting-aksi]', function() {
    setChangedAction($(this).is(':checked'));
});

$(document).on('change', 'input[type="checkbox"][data-setting-checkbox]', function() {
    setChangedCheckbox($(this).val(), $(this).data('setting-checkbox'), $(this).is(':checked'));
});

$(document).on('click', 'button[data-setting-save]', function() {
            let display_confirmation = '';

            if (changed.checked.id.length > 0 && changed.unchecked.id.length > 0) {
                display_confirmation = `
            <p class="text-start">
                Anda akan menambahkan <b>${changed.checked.id.length}</b> matakuliah, yaitu:
            </p>
            <ol class="text-start">
                ${changed.checked.name.map(name => `<li>${name}</li>`).join('')}
            </ol>
            <p class="text-start">
                dan menghapus <b>${changed.unchecked.id.length}</b> matakuliah, yaitu:
            </p>
            <ol class="text-start">
                ${changed.unchecked.name.map(name => `<li>${name}</li>`).join('')}
            </ol>
        `;
            } else if (changed.checked.id.length > 0) {
                display_confirmation = `
            <p class="text-start">
                Anda akan menambahkan <b>${changed.checked.id.length}</b> matakuliah, yaitu:
            </p>
            <ol class="text-start">
                ${changed.checked.name.map(name => `<li>${name}</li>`).join('')}
            </ol>
        `;
    } else if (changed.unchecked.id.length > 0) {
        display_confirmation = `
            <p class="text-start">
                Anda akan menghapus <b>${changed.unchecked.id.length}</b> matakuliah, yaitu:
            </p>
            <ol class="text-start">
                ${changed.unchecked.name.map(name => `<li>${name}</li>`).join('')}
            </ol>
        `;
    }

    if (changed.checked.id.length > 0 || changed.unchecked.id.length > 0) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            html: display_confirmation,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak',
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary',
            },
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/pengaturan-rpl/matakuliah/setting",
                    method: "POST",
                    data: {
                        checked: changed.checked.id,
                        unchecked: changed.unchecked.id
                    },
                    success: function(response) {

                        console.log(response.data);
                        let display_response = '';

                        if (response.data.checked.name.length > 0 && response.data.unchecked.name.length > 0) {
                            display_response = `
                                <p class="text-start">
                                    Berhasil menambahkan <b>${response.data.checked.count}</b> matakuliah, yaitu:
                                </p>
                                <ol class="text-start">
                                    ${response.data.checked.name.map(name => `<li>${name}</li>`).join('')}
                                </ol>
                                <p class="text-start">
                                    dan menghapus <b>${response.data.unchecked.count}</b> matakuliah, yaitu:
                                </p>
                                <ol class="text-start">
                                    ${response.data.unchecked.name.map(name => `<li>${name}</li>`).join('')}
                                </ol>
                            `;
                        } else if (response.data.checked.name.length > 0) {
                            display_response = `
                                <p class="text-start">
                                    Berhasil menambahkan <b>${response.data.checked.count}</b> matakuliah, yaitu:
                                </p>
                                <ol class="text-start">
                                    ${response.data.checked.name.map(name => `<li>${name}</li>`).join('')}
                                </ol>
                            `;
                        } else if (response.data.unchecked.name.length > 0) {
                            display_response = `
                                <p class="text-start">
                                    Berhasil menghapus <b>${response.data.unchecked.count}</b> matakuliah, yaitu:
                                </p>
                                <ol class="text-start">
                                    ${response.data.unchecked.name.map(name => `<li>${name}</li>`).join('')}
                                </ol>
                            `;
                        };
                        Swal.fire({
                            title: response.message.title,
                            html: display_response,
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Tutup",
                            customClass: {
                                confirmButton: "btn btn-primary",
                            },
                        }).then(() => {
                            reloadTable('matakuliah-setting-create-table');
                        });
                    },
                    error: function(xhr) {
                        console.log(xhr);
                    }
                });
            }
        });
    }
});

$(document).on('click', 'button[data-setting-undo]', function() {
    if (changed.checked.id.length > 0 || changed.unchecked.id.length > 0) {
        Swal.fire({
            title: 'Perhatian!',
            text: 'Urungkan perubahan pengaturan matakuliah?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak',
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary',
            },
        }).then((result) => {
            if (result.isConfirmed) {
                $('input[type="checkbox"][data-setting-checkbox]').each(function() {
                    if (changed.checked.id.includes($(this).val())) {
                        $(this).prop('checked', false).trigger('change');
                    } else if (changed.unchecked.id.includes($(this).val())) {
                        $(this).prop('checked', true).trigger('change');
                    }
                });
                changed = {
                    checked: { id: [], name: [] },
                    unchecked: { id: [], name: [] }
                };
                if (checked.length === $('input[type="checkbox"][data-setting-checkbox]').length &&
                    checked.length > 0) {
                    $('input[data-setting-aksi]').prop('checked', true);
                } else {
                    $('input[data-setting-aksi]').prop('checked', false);
                }
                $('button[data-setting-save]').prop('disabled', true);
                $('button[data-setting-undo]').prop('disabled', true);
            }
        });
    }
});

$(document).on('click', 'button[data-setting-delete]', function() {
            const table_confirmation = `
        <table class="table table-striped">
            <tbody>
                ${changed.checked.name.map(name => `<tr><td>${name}</td><td><i class="fad fa-trash-alt fs-5 text-danger"></i></td></tr>`).join('')}
            </tbody>
        </table>
    `;
    if (changed.checked.id.length > 0) {
        Swal.fire({
            title: 'Perhatian!',
            html: table_confirmation,
            width: '50%',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak',
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary',
            },
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/pengaturan-rpl/matakuliah/setting/delete",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        checked: changed.checked.id,
                    },
                    success: function (response) {
                        const table_response = `
                            <table class="table table-striped">
                                <tbody>
                                    ${response.data.success.name.map(name => `<tr><td>${name}</td><td><i class="fad fa-check fs-5 text-success"></i></td></tr>`).join('')}
                                    ${response.data.failed.name.map(name => `<tr><td>${name}</td><td><i class="fad fa-times fs-5 text-danger"></i></td></tr>`).join('')}
                                </tbody>
                            </table>
                        `;
                        Swal.fire({
                            title: response.message.title,
                            html: table_response,
                            width: '50%',
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Tutup",
                            customClass: {
                                confirmButton: "btn btn-primary",
                            },
                        }).then(() => {
                            reloadTable('matakuliah-setting-table');
                        });
                    },
                    error: function (xhr) {
                        console.log(xhr);
                    }
                });
            }
        });
    }
});

$(document).on('click', 'button[data-setting-restore]', function () {
    const table_confirmation = `
        <table class="table table-striped">
            <tbody>
                ${changed.checked.name.map(name => `<tr><td>${name}</td><td><i class="fad fa-recycle fs-5 text-success"></i></td></tr>`).join('')}
            </tbody>
        </table>
    `;
    if (changed.checked.id.length > 0) {
        Swal.fire({
            title: 'Perhatian!',
            html: table_confirmation,
            width: '50%',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak',
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary',
            },
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/pengaturan-rpl/matakuliah/setting/restore",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        checked: changed.checked.id,
                    },
                    success: function (response) {
                        const table_response = `
                            <table class="table table-striped">
                                <tbody>
                                    ${response.data.name.map(name => `<tr><td>${name}</td><td><i class="fad fa-check fs-5 text-success"></i></td></tr>`).join('')}
                                </tbody>
                            </table>
                        `;
                        Swal.fire({
                            title: response.message.title,
                            html: table_response,
                            width: '50%',
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Tutup",
                            customClass: {
                                confirmButton: "btn btn-primary",
                            },
                        }).then(() => {
                            reloadTable('matakuliah-setting-table');
                        });
                    },
                    error: function (xhr) {
                        console.log(xhr);
                    }
                });
            }
        });
    }
});

function reloadTable(dataTable) {
    changed = {
        checked: { id: [], name: [] },
        unchecked: { id: [], name: [] }
    };
    if (dataTable === 'matakuliah-setting-create-table') {
        const prodi_id = $('#prodi_id').val();
        const setting = $('#select-show-setting').val();
        const table = window.LaravelDataTables['matakuliah-setting-create-table'];
        let url = "/pengaturan-rpl/matakuliah/setting/create";
        if (prodi_id) {
            url += `?prodi_id=${prodi_id}`;
            table.ajax.url(url).load();
        } else {
            table.ajax.reload();
        }
    } else if (dataTable === 'matakuliah-setting-table') {
        const table = window.LaravelDataTables['matakuliah-setting-table'];
        table.ajax.reload();
    }
}

function setRefAction(action, empty) {
    if (action) {
        $('input[data-setting-aksi]').prop('checked', true);
    } else {
        $('input[data-setting-aksi]').prop('checked', false);
    }
    if (empty) {
        $('input[type="checkbox"][data-setting-aksi]').prop('disabled', true);
    } else {
        $('input[type="checkbox"][data-setting-aksi]').prop('disabled', false);
    }
}

function setChangedAction(action) {
    if (action) {
        $('input[type="checkbox"][data-setting-checkbox]:not(:disabled)').each(function () {
            if (!$(this).is(':checked')) {
                $(this).prop('checked', true).trigger('change');
            }
        });
    } else {
        $('input[type="checkbox"][data-setting-checkbox]:not(:disabled)').each(function () {
            if ($(this).is(':checked')) {
                $(this).prop('checked', false).trigger('change');
            }
        });
    }
}

function setRefCheckbox() {
    checked = [];
    unchecked = [];
    $('input[type="checkbox"][data-setting-checkbox]').each(function () {
        if ($(this).is(':checked')) {
            checked.push($(this).val());
        } else {
            unchecked.push($(this).val());
        }
    });
    if ($('input[type="checkbox"][data-setting-checkbox]:checked').length ===
        $('input[type="checkbox"][data-setting-checkbox]').length) {
        if ($('input[type="checkbox"][data-setting-checkbox]').length > 0) {
            setRefAction(true, false);
        } else {
            setRefAction(false, true);
        }
    } else {
        setRefAction(false, false);
    }
    setRefButton();
}

function setChangedCheckbox(value, name, action) {
    if (action) {
        if (changed.unchecked.id.includes(value)) {
            changed.unchecked.id = changed.unchecked.id.filter((item) => item !== value);
            changed.unchecked.name = changed.unchecked.name.filter((item) => item !== name);
        } else {
            changed.checked.id.push(value);
            changed.checked.name.push(name);
        }
    } else {
        if (changed.checked.id.includes(value)) {
            changed.checked.id = changed.checked.id.filter((item) => item !== value);
            changed.checked.name = changed.checked.name.filter((item) => item !== name);
        } else {
            changed.unchecked.id.push(value);
            changed.unchecked.name.push(name);
        }
    }
    if ($('input[type="checkbox"][data-setting-checkbox]:checked').length ===
        $('input[type="checkbox"][data-setting-checkbox]').length) {
        if ($('input[type="checkbox"][data-setting-checkbox]').length > 0) {
            setRefAction(true, false);
        } else {
            setRefAction(false, true);
        }
    } else {
        setRefAction(false, false);
    }
    setRefButton();
}

function setRefButton() {
    if (changed.checked.id.length > 0 || changed.unchecked.id.length > 0) {
        if ($('button[data-setting-save]').length && $('button[data-setting-undo]').length) {
            $('button[data-setting-save]').prop('disabled', false);
            $('button[data-setting-undo]').prop('disabled', false);
        }
        if ($('button[data-setting-delete]').length) {
            $('button[data-setting-delete]').prop('disabled', false);
        }
        if ($('button[data-setting-restore]').length) {
            $('button[data-setting-restore]').prop('disabled', false);
        }
    } else {
        if ($('button[data-setting-save]').length && $('button[data-setting-undo]').length) {
            $('button[data-setting-save]').prop('disabled', true);
            $('button[data-setting-undo]').prop('disabled', true);
        }
        if ($('button[data-setting-delete]').length) {
            $('button[data-setting-delete]').prop('disabled', true);
        }
        if ($('button[data-setting-restore]').length) {
            $('button[data-setting-restore]').prop('disabled', true);
        }
    }
};